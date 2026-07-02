<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Traits\ConvertsImageToWebP;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    use ConvertsImageToWebP;

    public function index()
    {
        $exercises = Exercise::with('muscleGroup')->orderBy('name')->paginate(15);
        return view('admin.exercises.index', compact('exercises'));
    }

    public function create()
    {
        $muscleGroups = MuscleGroup::orderBy('name')->get();
        return view('admin.exercises.form', compact('muscleGroups'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('illustration')) {
            $data['illustration_path'] = $this->storeImageAsWebP(
                $request->file('illustration'), 'exercises'
            );
        }

        $data['slug'] = str()->slug($data['name']) . '-' . str()->random(5);
        Exercise::create($data);

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Gerakan berhasil ditambahkan.');
    }

    public function edit(Exercise $exercise)
    {
        $muscleGroups = MuscleGroup::orderBy('name')->get();
        return view('admin.exercises.form', compact('exercise', 'muscleGroups'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('illustration')) {
            $data['illustration_path'] = $this->storeImageAsWebP(
                $request->file('illustration'), 'exercises'
            );
        }

        $exercise->update($data);

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Gerakan berhasil diperbarui.');
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return redirect()->route('admin.exercises.index')
            ->with('success', 'Gerakan berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'muscle_group_id'    => 'required|exists:muscle_groups,id',
            'name'               => 'required|string|max:255',
            'equipment_type'     => 'required|in:dumbell,barbell,cable,mesin_gym,resistance_band,pull_up_bar,kettlebell,bodyweight,lainnya',
            'difficulty'         => 'required|in:pemula,menengah,lanjutan',
            'description'        => 'required|string',
            'how_to'             => 'nullable|string',
            'video_url'          => 'nullable|url',
            'weight_unit_default'=> 'required|in:kg,band_level',
            'is_active'          => 'boolean',
            'illustration'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);
    }
}