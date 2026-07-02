<?php

namespace App\Http\Controllers;

use App\Models\ProgramScheduleOverride;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Toggle override sebuah hari: swap workout↔rest, atau set skip.
     * Kalau override sudah ada dan sama, hapus (balik ke default).
     */
    public function swap(Request $request)
    {
        $data = $request->validate([
            'date'                  => 'required|date',
            'program_enrollment_id' => 'required|exists:program_enrollments,id',
            'override_type'         => 'required|in:workout,rest,skip',
            'day_number'            => 'nullable|integer|min:1|max:7',
        ]);

        // Pastikan enrollment milik user ini
        $enrollment = auth()->user()->enrollments()->findOrFail($data['program_enrollment_id']);

        $existing = ProgramScheduleOverride::where('program_enrollment_id', $enrollment->id)
            ->where('date', $data['date'])
            ->first();

        // Kalau sama → toggle off (hapus override, balik ke jadwal normal)
        if ($existing && $existing->override_type === $data['override_type']) {
            $existing->delete();
            return response()->json(['status' => 'removed', 'date' => $data['date']]);
        }

        $override = ProgramScheduleOverride::updateOrCreate(
            [
                'program_enrollment_id' => $enrollment->id,
                'date'                  => $data['date'],
            ],
            [
                'user_id'       => auth()->id(),
                'override_type' => $data['override_type'],
                'day_number'    => $data['day_number'] ?? null,
                'notes'         => $existing?->notes, // pertahankan notes yang sudah ada
            ]
        );

        return response()->json(['status' => 'set', 'override' => $override]);
    }

    /**
     * Simpan / update catatan harian untuk tanggal tertentu.
     */
    public function saveNote(Request $request)
    {
        $data = $request->validate([
            'date'                  => 'required|date',
            'program_enrollment_id' => 'required|exists:program_enrollments,id',
            'notes'                 => 'nullable|string|max:1000',
        ]);

        $enrollment = auth()->user()->enrollments()->findOrFail($data['program_enrollment_id']);

        $override = ProgramScheduleOverride::firstOrNew([
            'program_enrollment_id' => $enrollment->id,
            'date'                  => $data['date'],
        ]);

        if (!$override->exists) {
            $override->user_id       = auth()->id();
            $override->override_type = 'rest'; // default, notes-only tidak ubah status
        }

        $override->notes = $data['notes'];
        $override->save();

        // Kalau override_type-nya rest dan aslinya hari ini workout day,
        // kita cukup simpan notes — status workout/rest tetap ditentukan DashboardController
        return response()->json(['status' => 'saved']);
    }
}
