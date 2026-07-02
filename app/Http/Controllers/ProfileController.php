<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->profile;
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'gender' => 'required|in:pria,wanita',
            'birthdate' => 'required|date|before:today',
            'height_cm' => 'required|numeric|min:50|max:250',
            'weight_kg' => 'required|numeric|min:20|max:300',
            'activity_level' => 'required|in:sangat_jarang,jarang,sedang,aktif,sangat_aktif',
            'training_location' => 'required|in:gym,rumah,keduanya',
            'equipment_access' => 'required|in:dumbell,resistance_band,keduanya,tidak_ada',
            'experience_level' => 'required|in:pemula,menengah,lanjutan',
            'injury_notes' => 'nullable|string|max:1000',
            'goal_notes' => 'nullable|string|max:1000',
            'target_weight_kg' => 'nullable|numeric|min:20|max:300',
        ]);

        auth()->user()->profile()->updateOrCreate(['user_id' => auth()->id()], $data);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
