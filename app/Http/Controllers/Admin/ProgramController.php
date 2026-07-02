<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Traits\ConvertsImageToWebP;

class ProgramController extends Controller
{
    use ConvertsImageToWebP;
    public function index()
    {
        $programs = Program::where('type', 'preset')->latest()->paginate(20);
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        $exercises = Exercise::with('muscleGroup')->orderBy('name')->get();
        $goalLabels = Program::goalLabels();
        return view('admin.programs.form', compact('exercises', 'goalLabels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'goal'             => 'required|string',
            'description'      => 'nullable|string',
            'duration_weeks'   => 'required|integer|min:1|max:52',
            'sessions_per_week'=> 'required|integer|min:1|max:7',
            'location_type'    => 'required|in:gym,rumah,keduanya',
            'equipment_tags'   => 'nullable|array',
            'equipment_tags.*' => 'string',
            'cover_image'      => 'nullable|image|max:4096',
            'days'             => 'required|array|min:1',
            'days.*.exercises' => 'required|array|min:1',
            'days.*.exercises.*.exercise_id'           => 'required|exists:exercises,id',
            'days.*.exercises.*.target_sets'           => 'required|integer|min:1|max:10',
            'days.*.exercises.*.target_reps'           => 'required|integer|min:1|max:100',
            'days.*.exercises.*.suggested_start_weight'=> 'nullable|numeric|min:0',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $this->storeImageAsWebP($request->file('cover_image'), 'programs/covers');
        }

        $program = Program::create([
            'created_by'       => auth()->id(),
            'name'             => $data['name'],
            'slug'             => str()->slug($data['name']).'-'.str()->random(5),
            'goal'             => $data['goal'],
            'description'      => $data['description'] ?? null,
            'duration_weeks'   => $data['duration_weeks'],
            'sessions_per_week'=> $data['sessions_per_week'],
            'type'             => 'preset',
            'is_active'        => true,
            'location_type'    => $data['location_type'],
            'equipment_tags'   => $data['equipment_tags'] ?? [],
            'cover_image_path' => $coverPath,
        ]);

        foreach ($data['days'] as $dayIndex => $day) {
            foreach ($day['exercises'] as $order => $ex) {
                $program->programExercises()->create([
                    'exercise_id'            => $ex['exercise_id'],
                    'day_number'             => $dayIndex + 1,
                    'order'                  => $order + 1,
                    'target_sets'            => $ex['target_sets'],
                    'target_reps'            => $ex['target_reps'],
                    'suggested_start_weight' => $ex['suggested_start_weight'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.programs.index')->with('success', 'Program preset berhasil dibuat.');
    }

    public function edit(Program $program)
    {
        $exercises  = Exercise::with('muscleGroup')->orderBy('name')->get();
        $goalLabels = Program::goalLabels();
        $exercisesByDay = $program->exercisesByDay();
        return view('admin.programs.edit', compact('program', 'exercises', 'goalLabels', 'exercisesByDay'));
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'goal'              => 'required|string',
            'description'       => 'nullable|string',
            'duration_weeks'    => 'required|integer|min:1|max:52',
            'sessions_per_week' => 'required|integer|min:1|max:7',
            'location_type'     => 'required|in:gym,rumah,keduanya',
            'equipment_tags'    => 'nullable|array',
            'equipment_tags.*'  => 'string',
            'cover_image'       => 'nullable|image|max:4096',
            'is_active'         => 'boolean',
            'days'              => 'required|array|min:1',
            'days.*.exercises'                         => 'required|array|min:1',
            'days.*.exercises.*.exercise_id'           => 'required|exists:exercises,id',
            'days.*.exercises.*.target_sets'           => 'required|integer|min:1|max:10',
            'days.*.exercises.*.target_reps'           => 'required|integer|min:1|max:100',
            'days.*.exercises.*.suggested_start_weight'=> 'nullable|numeric|min:0',
        ]);

        $coverPath = $program->cover_image_path;
        if ($request->hasFile('cover_image')) {
            $coverPath = $this->storeImageAsWebP($request->file('cover_image'), 'programs/covers');
        }

        $program->update([
            'name'              => $data['name'],
            'goal'              => $data['goal'],
            'description'       => $data['description'] ?? null,
            'duration_weeks'    => $data['duration_weeks'],
            'sessions_per_week' => $data['sessions_per_week'],
            'location_type'     => $data['location_type'],
            'equipment_tags'    => $data['equipment_tags'] ?? [],
            'cover_image_path'  => $coverPath,
            'is_active'         => $request->boolean('is_active', true),
        ]);

        // Hapus semua exercise lama, buat ulang dari form
        $program->programExercises()->delete();

        foreach ($data['days'] as $dayIndex => $day) {
            foreach ($day['exercises'] as $order => $ex) {
                $program->programExercises()->create([
                    'exercise_id'             => $ex['exercise_id'],
                    'day_number'              => $dayIndex + 1,
                    'order'                   => $order + 1,
                    'target_sets'             => $ex['target_sets'],
                    'target_reps'             => $ex['target_reps'],
                    'suggested_start_weight'  => $ex['suggested_start_weight'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }
}
