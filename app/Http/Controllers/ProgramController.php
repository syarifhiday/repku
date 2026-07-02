<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Program;
use App\Models\ProgramEnrollment;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::where('is_active', true)->where('type', 'preset')->get()->groupBy('goal');
        $goalLabels = Program::goalLabels();
        $activeEnrollment = auth()->user()->activeEnrollment()->with('program')->first();

        return view('programs.index', compact('programs', 'goalLabels', 'activeEnrollment'));
    }

    public function show(Program $program)
    {
        $exercisesByDay = $program->exercisesByDay();
        return view('programs.show', compact('program', 'exercisesByDay'));
    }

    public function enroll(Program $program)
    {
        // Nonaktifkan enrollment aktif sebelumnya
        auth()->user()->enrollments()->where('status', 'active')->update(['status' => 'paused']);

        ProgramEnrollment::create([
            'user_id' => auth()->id(),
            'program_id' => $program->id,
            'started_at' => now(),
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')->with('success', "Berhasil enroll ke program {$program->name}!");
    }

    public function createCustom()
    {
        $exercises = Exercise::where('is_active', true)->with('muscleGroup')->orderBy('name')->get();
        return view('programs.create-custom', compact('exercises'));
    }

    public function storeCustom(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sessions_per_week' => 'required|integer|min:1|max:7',
            'duration_weeks' => 'required|integer|min:1|max:52',
            'days' => 'required|array|min:1',
            'days.*.exercises' => 'required|array|min:1',
            'days.*.exercises.*.exercise_id' => 'required|exists:exercises,id',
            'days.*.exercises.*.target_sets' => 'required|integer|min:1|max:10',
            'days.*.exercises.*.target_reps' => 'required|integer|min:1|max:100',
        ]);

        $program = Program::create([
            'created_by' => auth()->id(),
            'name' => $data['name'],
            'slug' => str()->slug($data['name']).'-'.auth()->id().'-'.now()->timestamp,
            'goal' => 'custom',
            'description' => $data['description'] ?? null,
            'duration_weeks' => $data['duration_weeks'],
            'sessions_per_week' => $data['sessions_per_week'],
            'type' => 'custom',
        ]);

        foreach ($data['days'] as $dayIndex => $day) {
            foreach ($day['exercises'] as $order => $ex) {
                $program->programExercises()->create([
                    'exercise_id' => $ex['exercise_id'],
                    'day_number' => $dayIndex + 1,
                    'order' => $order + 1,
                    'target_sets' => $ex['target_sets'],
                    'target_reps' => $ex['target_reps'],
                ]);
            }
        }

        auth()->user()->enrollments()->where('status', 'active')->update(['status' => 'paused']);

        ProgramEnrollment::create([
            'user_id' => auth()->id(),
            'program_id' => $program->id,
            'started_at' => now(),
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')->with('success', 'Program custom kamu berhasil dibuat & diaktifkan!');
    }
}
