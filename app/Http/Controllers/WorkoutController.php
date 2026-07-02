<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\WorkoutLog;
use App\Models\WorkoutSession;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function create(Request $request)
    {
        $enrollment = auth()->user()->activeEnrollment()->with('program')->first();

        if (!$enrollment) {
            return redirect()->route('programs.index')
                ->with('error', 'Kamu belum enroll ke program apapun.');
        }

        $dayNumber = (int) $request->get('day', 1);
        $programExercises = $enrollment->program->programExercises()
            ->where('day_number', $dayNumber)
            ->with('exercise.muscleGroup')
            ->get();

        // Saran progressive overload per gerakan
        $suggestions = [];
        foreach ($programExercises as $pe) {
            $suggestions[$pe->exercise_id] = $this->suggestNextLoad($pe->exercise, $pe);
        }

        // Daftar semua gerakan untuk mode custom
        $allExercises = Exercise::where('is_active', true)
            ->with('muscleGroup')
            ->orderBy('name')
            ->get();

        return view('workout.create', compact(
            'enrollment', 'programExercises', 'dayNumber', 'suggestions', 'allExercises'
        ));
    }

    public function store(Request $request)
    {
        $isCustom = $request->boolean('is_custom');

        $base = $request->validate([
            'program_enrollment_id' => 'required|exists:program_enrollments,id',
            'day_number'            => 'required|integer',
            'location'              => 'required|in:gym,rumah',
            'duration_minutes'      => 'nullable|integer|min:1',
            'notes'                 => 'nullable|string|max:1000',
            'is_custom'             => 'boolean',
        ]);

        if ($isCustom) {
            $request->validate([
                'custom_sets'                    => 'required|array|min:1',
                'custom_sets.*.exercise_id'      => 'required|exists:exercises,id',
                'custom_sets.*.set_number'       => 'required|integer|min:1',
                'custom_sets.*.weight_unit'      => 'required|in:kg,band_level',
                'custom_sets.*.weight_value'     => 'nullable|numeric|min:0',
                'custom_sets.*.band_color'       => 'nullable|string|max:50',
                'custom_sets.*.reps'             => 'required|integer|min:0|max:200',
                'custom_sets.*.rpe'              => 'nullable|integer|min:1|max:10',
            ]);
            $sets = $request->input('custom_sets');
        } else {
            $request->validate([
                'sets'                      => 'required|array|min:1',
                'sets.*.exercise_id'        => 'required|exists:exercises,id',
                'sets.*.set_number'         => 'required|integer|min:1',
                'sets.*.weight_unit'        => 'required|in:kg,band_level',
                'sets.*.weight_value'       => 'nullable|numeric|min:0',
                'sets.*.band_color'         => 'nullable|string|max:50',
                'sets.*.reps'              => 'required|integer|min:0|max:200',
                'sets.*.rpe'               => 'nullable|integer|min:1|max:10',
            ]);
            $sets = $request->input('sets');
        }

        $session = WorkoutSession::create([
            'user_id'               => auth()->id(),
            'program_enrollment_id' => $base['program_enrollment_id'],
            'session_date'          => now()->toDateString(),
            'day_number'            => $base['day_number'],
            'location'              => $base['location'],
            'notes'                 => $base['notes'] ?? null,
            'duration_minutes'      => $base['duration_minutes'] ?? null,
        ]);

        foreach ($sets as $set) {
            WorkoutLog::create([
                'workout_session_id' => $session->id,
                'exercise_id'        => $set['exercise_id'],
                'set_number'         => $set['set_number'],
                'weight_unit'        => $set['weight_unit'],
                'weight_value'       => $set['weight_value'] ?? null,
                'band_color'         => $set['band_color'] ?? null,
                'reps'               => $set['reps'],
                'rpe'                => $set['rpe'] ?? null,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Latihan berhasil dicatat!');
    }

    public function history()
    {
        $sessions = auth()->user()->workoutSessions()
            ->with(['logs.exercise', 'enrollment.program'])
            ->latest('session_date')
            ->paginate(10);

        return view('workout.history', compact('sessions'));
    }

    public function progress(Exercise $exercise)
    {
        $logs = WorkoutLog::where('exercise_id', $exercise->id)
            ->whereHas('session', fn ($q) => $q->where('user_id', auth()->id()))
            ->with('session')
            ->get()
            ->groupBy(fn ($log) => $log->session->session_date->toDateString())
            ->map(fn ($groupedLogs) => [
                'date'         => $groupedLogs->first()->session->session_date->format('d M Y'),
                'max_weight'   => $groupedLogs->max('weight_value'),
                'total_volume' => $groupedLogs->sum(fn ($l) => ($l->weight_value ?? 0) * $l->reps),
                'sets'         => $groupedLogs->count(),
            ])->values();

        return view('workout.progress', compact('exercise', 'logs'));
    }

    private function suggestNextLoad(Exercise $exercise, $programExercise): array
    {
        $lastSession = WorkoutSession::where('user_id', auth()->id())
            ->whereHas('logs', fn ($q) => $q->where('exercise_id', $exercise->id))
            ->latest('session_date')
            ->first();

        if (!$lastSession) {
            return [
                'message'          => 'Belum ada riwayat. Mulai dengan beban yang terasa menantang di rep terakhir.',
                'suggested_weight' => $programExercise->suggested_start_weight,
                'is_new'           => true,
            ];
        }

        $lastLogs    = $lastSession->logs()->where('exercise_id', $exercise->id)->get();
        $targetReps  = $programExercise->target_reps;
        $allHit      = $lastLogs->every(fn ($l) => $l->reps >= $targetReps);
        $lastMax     = $lastLogs->max('weight_value') ?? 0;

        if ($allHit && $exercise->weight_unit_default === 'kg') {
            $increment = $lastMax >= 20 ? 2.5 : 1;
            return [
                'message'          => "Semua set capai {$targetReps} reps. Naikkan beban → " . ($lastMax + $increment) . " kg.",
                'suggested_weight' => $lastMax + $increment,
                'is_new'           => false,
            ];
        } elseif ($allHit) {
            return [
                'message'          => "Semua set capai target. Naikkan ke level resistance band berikutnya.",
                'suggested_weight' => $lastMax,
                'is_new'           => false,
            ];
        }

        return [
            'message'          => "Belum semua set capai {$targetReps} reps. Pertahankan beban ({$lastMax}) dulu.",
            'suggested_weight' => $lastMax,
            'is_new'           => false,
        ];
    }
}
