@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-black uppercase mb-2">Profil Saya</h1>
    <p class="text-gray-400 mb-8 text-sm">Update data tubuh & kondisimu kapan saja agar program tetap akurat.</p>

    @if($profile)
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="card-flat p-4 text-center">
            <p class="text-2xl font-black text-gf-yellow" style="color:#FFD500;">{{ $profile->age ?? '-' }}</p>
            <p class="text-xs uppercase text-gray-400">Usia</p>
        </div>
        <div class="card-flat p-4 text-center">
            <p class="text-2xl font-black" style="color:#FFD500;">{{ $profile->bmi ?? '-' }}</p>
            <p class="text-xs uppercase text-gray-400">BMI</p>
        </div>
        <div class="card-flat p-4 text-center">
            <p class="text-2xl font-black" style="color:#FFD500;">{{ $profile->weight_kg }} kg</p>
            <p class="text-xs uppercase text-gray-400">Berat Saat Ini</p>
        </div>
    </div>
    @endif

    @if($errors->any())
        <div class="card-flat p-4 mb-6 text-sm" style="border-color:#ff3b3b;">
            <ul class="list-disc pl-5">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="card-flat p-8 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Jenis Kelamin</label>
                <select name="gender" class="input-flat w-full" required>
                    <option value="pria" @selected(old('gender', $profile->gender ?? '')=='pria')>Pria</option>
                    <option value="wanita" @selected(old('gender', $profile->gender ?? '')=='wanita')>Wanita</option>
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Tanggal Lahir</label>
                <input type="date" name="birthdate" value="{{ old('birthdate', $profile->birthdate?->toDateString()) }}" class="input-flat w-full" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Tinggi (cm)</label>
                <input type="number" step="0.1" name="height_cm" value="{{ old('height_cm', $profile->height_cm ?? '') }}" class="input-flat w-full" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Berat (kg)</label>
                <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $profile->weight_kg ?? '') }}" class="input-flat w-full" required>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Tingkat Aktivitas Harian</label>
            <select name="activity_level" class="input-flat w-full" required>
                @php $levels = ['sangat_jarang'=>'Sangat Jarang','jarang'=>'Jarang','sedang'=>'Sedang','aktif'=>'Aktif','sangat_aktif'=>'Sangat Aktif']; @endphp
                @foreach($levels as $val => $label)
                    <option value="{{ $val }}" @selected(old('activity_level', $profile->activity_level ?? '')==$val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Lokasi Latihan</label>
                <select name="training_location" class="input-flat w-full" required>
                    <option value="rumah" @selected(old('training_location', $profile->training_location ?? '')=='rumah')>Di Rumah</option>
                    <option value="gym" @selected(old('training_location', $profile->training_location ?? '')=='gym')>Di Gym</option>
                    <option value="keduanya" @selected(old('training_location', $profile->training_location ?? '')=='keduanya')>Keduanya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Alat yang Dimiliki</label>
                <select name="equipment_access" class="input-flat w-full" required>
                    <option value="dumbell" @selected(old('equipment_access', $profile->equipment_access ?? '')=='dumbell')>Dumbbell</option>
                    <option value="resistance_band" @selected(old('equipment_access', $profile->equipment_access ?? '')=='resistance_band')>Resistance Band</option>
                    <option value="keduanya" @selected(old('equipment_access', $profile->equipment_access ?? '')=='keduanya')>Keduanya</option>
                    <option value="tidak_ada" @selected(old('equipment_access', $profile->equipment_access ?? '')=='tidak_ada')>Belum Punya</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Level Pengalaman</label>
            <select name="experience_level" class="input-flat w-full" required>
                <option value="pemula" @selected(old('experience_level', $profile->experience_level ?? '')=='pemula')>Pemula</option>
                <option value="menengah" @selected(old('experience_level', $profile->experience_level ?? '')=='menengah')>Menengah</option>
                <option value="lanjutan" @selected(old('experience_level', $profile->experience_level ?? '')=='lanjutan')>Lanjutan</option>
            </select>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Target Berat Badan (kg)</label>
            <input type="number" step="0.1" name="target_weight_kg" value="{{ old('target_weight_kg', $profile->target_weight_kg ?? '') }}" class="input-flat w-full">
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Riwayat Cedera / Kondisi Khusus</label>
            <textarea name="injury_notes" rows="2" class="input-flat w-full">{{ old('injury_notes', $profile->injury_notes ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Tujuan / Catatan Tambahan</label>
            <textarea name="goal_notes" rows="2" class="input-flat w-full">{{ old('goal_notes', $profile->goal_notes ?? '') }}</textarea>
        </div>

        <button type="submit" class="btn-primary w-full">Simpan Perubahan</button>
    </form>
</div>
@endsection
