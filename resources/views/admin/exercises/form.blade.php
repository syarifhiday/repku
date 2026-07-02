@extends('layouts.app')
@section('title', isset($exercise) ? 'Edit Gerakan' : 'Tambah Gerakan')

@section('content')
<h1 class="text-3xl font-black uppercase mb-8">{{ isset($exercise) ? 'Edit Gerakan' : 'Tambah Gerakan Baru' }}</h1>

<form action="{{ isset($exercise) ? route('admin.exercises.update', $exercise) : route('admin.exercises.store') }}"
      method="POST" enctype="multipart/form-data" class="card-flat p-8 space-y-5">
    @csrf
    @if(isset($exercise)) @method('PUT') @endif

    @if($errors->any())
        <div class="p-4 text-sm" style="border:2px solid #ef4444;">
            <ul class="list-disc pl-5">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <div>
        <label class="block text-xs uppercase font-bold mb-2">Nama Gerakan</label>
        <input type="text" name="name" value="{{ old('name', $exercise->name ?? '') }}" class="input-flat w-full" required>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-xs uppercase font-bold mb-2">Muscle Group</label>
            <select name="muscle_group_id" class="input-flat w-full" required>
                @foreach($muscleGroups as $mg)
                    <option value="{{ $mg->id }}" @selected(old('muscle_group_id', $exercise->muscle_group_id ?? '')==$mg->id)>{{ $mg->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs uppercase font-bold mb-2">Jenis Alat</label>
            <select name="equipment_type" class="input-flat w-full" required>
                @php
                $equipTypes = [
                    'dumbell' => '🏋️ Dumbbell',
                    'barbell' => '🏋️ Barbell',
                    'cable' => '🔗 Cable Machine',
                    'mesin_gym' => '⚙️ Mesin Gym',
                    'resistance_band' => '🪢 Resistance Band',
                    'pull_up_bar' => '🔝 Pull-Up Bar',
                    'kettlebell' => '🫙 Kettlebell',
                    'bodyweight' => '🙆 Bodyweight/Tanpa Alat',
                    'lainnya' => '📦 Lainnya',
                ];
                @endphp
                @foreach($equipTypes as $val => $label)
                    <option value="{{ $val }}" @selected(old('equipment_type', $exercise->equipment_type ?? '')==$val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs uppercase font-bold mb-2">Satuan Beban Default</label>
            <select name="weight_unit_default" class="input-flat w-full" required>
                <option value="kg" @selected(old('weight_unit_default', $exercise->weight_unit_default ?? '')=='kg')>KG</option>
                <option value="band_level" @selected(old('weight_unit_default', $exercise->weight_unit_default ?? '')=='band_level')>Jumlah Karet (Band)</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs uppercase font-bold mb-2">Tingkat Kesulitan</label>
        <select name="difficulty" class="input-flat w-full" required>
            <option value="pemula" @selected(old('difficulty', $exercise->difficulty ?? '')=='pemula')>Pemula</option>
            <option value="menengah" @selected(old('difficulty', $exercise->difficulty ?? '')=='menengah')>Menengah</option>
            <option value="lanjutan" @selected(old('difficulty', $exercise->difficulty ?? '')=='lanjutan')>Lanjutan</option>
        </select>
    </div>

    <div>
        <label class="block text-xs uppercase font-bold mb-2">Deskripsi Singkat</label>
        <textarea name="description" rows="2" class="input-flat w-full" required>{{ old('description', $exercise->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-xs uppercase font-bold mb-2">Cara Melakukan (How To)</label>
        <textarea name="how_to" rows="4" class="input-flat w-full">{{ old('how_to', $exercise->how_to ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-xs uppercase font-bold mb-2">Ilustrasi Gerakan (Gambar / GIF)</label>
        @if(isset($exercise) && $exercise->illustration_path)
            <img src="{{ asset('storage/'.$exercise->illustration_path) }}"
                 class="mb-3 w-full object-contain" style="max-height:200px; background:#0A0A0A; border:2px solid #2A2A2A;">
            <p class="text-xs text-gray-500 mb-2">Upload baru untuk menggantikan gambar di atas.</p>
        @endif
        <input type="file" name="illustration" accept="image/*,.gif" class="input-flat w-full">
        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF, WebP. Maks 4MB. Ukuran ideal: minimal 400×300px.</p>
    </div>

    <div>
        <label class="block text-xs uppercase font-bold mb-2">Link Video Tutorial (opsional)</label>
        <input type="url" name="video_url" value="{{ old('video_url', $exercise->video_url ?? '') }}" class="input-flat w-full" placeholder="https://youtube.com/...">
    </div>

    <label class="flex items-center gap-2 text-sm cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $exercise->is_active ?? true) ? 'checked' : '' }}>
        Aktifkan gerakan ini (tampil di daftar program)
    </label>

    <button type="submit" class="btn-primary w-full">Simpan Gerakan</button>
</form>
@endsection
