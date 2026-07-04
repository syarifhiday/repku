<?php

namespace App\Http\Controllers;

use App\Models\ProgramEnrollment;
use App\Models\ProgramScheduleOverride;
use App\Models\WorkoutSession;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user       = auth()->user();
        $enrollment = $user->activeEnrollment()->with('program')->first();

        $calendar = null;
        if ($enrollment) {
            $calendar = $this->generateCalendar($enrollment);
        }

        $totalSessions    = $user->workoutSessions()->count();
        $thisWeekSessions = $user->workoutSessions()
            ->whereBetween('session_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return view('dashboard.index', compact('enrollment', 'calendar', 'totalSessions', 'thisWeekSessions'));
    }

    public function generateCalendar(ProgramEnrollment $enrollment): array
    {
        $startDate = $enrollment->started_at->copy()->startOfDay();
        $totalDays = $enrollment->program->duration_weeks * 7;

        // Jadwal mingguan: array 7 elemen [Mon..Sun]
        // nilai = program day number, null = rest
        $weeklySchedule = $enrollment->program->resolvedWeeklySchedule();

        $overrides = ProgramScheduleOverride::where('program_enrollment_id', $enrollment->id)
            ->get()->keyBy(fn ($o) => $o->date->toDateString());

        $sessions = WorkoutSession::where('program_enrollment_id', $enrollment->id)
            ->with('logs.exercise')
            ->get()->keyBy(fn ($s) => $s->session_date->toDateString());

        $days    = [];
        $byMonth = [];
        $today   = now()->startOfDay();

        for ($i = 0; $i < $totalDays; $i++) {
            $date    = $startDate->copy()->addDays($i);
            $dateStr = $date->toDateString();

            // Hari ke-berapa dalam siklus minggu ini (0=Mon, 6=Sun)
            // Carbon: dayOfWeek 0=Sun, 1=Mon. Kita normalize ke 0=Mon
            $dow = (($date->dayOfWeek + 6) % 7); // 0=Mon..6=Sun

            $isBaseWorkout = $weeklySchedule[$dow] !== null;
            $baseDayNumber = $weeklySchedule[$dow]; // null atau integer

            $override = $overrides[$dateStr] ?? null;
            $session  = $sessions[$dateStr]  ?? null;

            [$isEffectiveWorkout, $effectiveDayNumber] = $this->applyOverride(
                $isBaseWorkout, $baseDayNumber, $override
            );

            $status = $this->resolveStatus($date, $today, $isEffectiveWorkout, $override, $session);

            $logSummary = [];
            if ($session) {
                $logSummary = $session->logs
                    ->groupBy('exercise_id')
                    ->map(fn ($logs) => [
                        'exercise'   => $logs->first()->exercise->name ?? '-',
                        'sets'       => $logs->groupBy('set_number')->count(),
                        'max_weight' => $logs->max('weight_value'),
                        'unit'       => $logs->first()->weight_unit,
                    ])->values()->toArray();
            }

            $dayData = [
                'date'                 => $dateStr,
                'day_num'              => (int) $date->format('j'),
                'day_of_week'          => $date->format('D'),
                'dow_index'            => $dow, // 0=Mon..6=Sun
                'is_today'             => $date->equalTo($today),
                'is_past'              => $date->lt($today),
                'is_base_workout'      => $isBaseWorkout,
                'is_effective_workout' => $isEffectiveWorkout,
                'program_day_number'   => $effectiveDayNumber,
                'status'               => $status,
                'notes'                => $override?->notes ?? $session?->notes,
                'override_type'        => $override?->override_type,
                'session_id'           => $session?->id,
                'log_summary'          => $logSummary,
            ];

            $days[$dateStr] = $dayData;

            $monthKey = $date->format('Y-m');
            if (!isset($byMonth[$monthKey])) {
                // Offset blank cells: mulai dari Senin, cek hari pertama bulan ini
                $firstOfMonth = $date->copy()->startOfMonth();
                $firstDow     = ($firstOfMonth->dayOfWeek + 6) % 7; // 0=Mon
                $blanks = array_fill(0, $firstDow, null);

                $byMonth[$monthKey] = [
                    'label' => $date->format('F Y'),
                    'days'  => $blanks,
                ];
            }
            $byMonth[$monthKey]['days'][] = $dayData;
        }

        return [
            'days'      => $days,
            'byMonth'   => array_values($byMonth),
            'startDate' => $startDate->toDateString(),
            'endDate'   => $startDate->copy()->addDays($totalDays - 1)->toDateString(),
        ];
    }

    private function applyOverride(bool $isBaseWorkout, ?int $baseDayNumber, $override): array
    {
        if (!$override) return [$isBaseWorkout, $baseDayNumber];
        return match ($override->override_type) {
            'rest', 'skip' => [false, null],
            'workout'      => [true, $override->day_number ?? $baseDayNumber ?? 1],
            default        => [$isBaseWorkout, $baseDayNumber],
        };
    }

    private function resolveStatus(Carbon $date, Carbon $today, bool $isEffectiveWorkout, $override, $session): string
    {
        if ($session)                              return 'done';
        if ($override?->override_type === 'skip') return 'skip';
        if (!$isEffectiveWorkout)                 return 'rest';
        if ($date->lt($today))                    return 'missed';
        if ($date->equalTo($today))               return 'today';
        return 'upcoming';
    }
}
