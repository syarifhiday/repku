@extends('layouts.app')
@section('title', 'Edit Program: '.$program->name)

@section('content')

@php
$exercisesJson = $exercises->map(function($e) {
    return ['id' => $e->id, 'name' => $e->name, 'muscle' => $e->muscleGroup->name];
});

// Susun existing exercises per hari untuk prefill form
$existingDays = [];
foreach ($exercisesByDay as $dayNum => $items) {
    $existingDays[$dayNum - 1] = $items->map(function($pe) {
        return [
            'exercise_id'            => $pe->exercise_id,
            'target_sets'            => $pe->target_sets,
            'target_reps'            => $pe->target_reps,
            'suggested_start_weight' => $pe->suggested_start_weight,
        ];
    })->values()->toArray();
}

$allTags = [
    'barbell' => 'Barbell', 'dumbell' => 'Dumbbell', 'cable' => 'Cable',
    'mesin_gym' => 'Mesin Gym', 'resistance_band' => 'Resistance Band',
    'pull_up_bar' => 'Pull-Up Bar', 'kettlebell' => 'Kettlebell', 'bodyweight' => 'Tanpa Alat',
];
@endphp

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-black uppercase">Edit Program</h1>
    <a href="{{ route('admin.programs.index') }}" class="btn-outline text-sm">← Kembali</a>
</div>

<form action="{{ route('admin.programs.update', $program) }}" method="POST"
      enctype="multipart/form-data" id="editForm" class="space-y-6">
    @csrf
    @method('PUT')

    @if($errors->any())
        <div class="p-4 text-sm" style="border:2px solid #ef4444;">
            <ul class="list-disc pl-5">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="card-flat p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Nama Program</label>
                <input type="text" name="name" value="{{ old('name', $program->name) }}"
                       class="input-flat w-full" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Goal</label>
                <select name="goal" class="input-flat w-full" required>
                    @foreach($goalLabels as $val => $label)
                        <option value="{{ $val }}" @selected(old('goal', $program->goal) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Deskripsi</label>
            <textarea name="description" rows="3" class="input-flat w-full">{{ old('description', $program->description) }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Durasi (minggu)</label>
                <input type="number" name="duration_weeks"
                       value="{{ old('duration_weeks', $program->duration_weeks) }}"
                       min="1" max="52" class="input-flat w-full" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Sesi per Minggu</label>
                <input type="number" name="sessions_per_week" id="sessionsPerWeek"
                       value="{{ old('sessions_per_week', $program->sessions_per_week) }}"
                       min="1" max="7" class="input-flat w-full" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Lokasi</label>
                <select name="location_type" class="input-flat w-full">
                    <option value="gym"      @selected(old('location_type', $program->location_type) === 'gym')>🏋️ Gym</option>
                    <option value="rumah"    @selected(old('location_type', $program->location_type) === 'rumah')>🏠 Rumah</option>
                    <option value="keduanya" @selected(old('location_type', $program->location_type) === 'keduanya')>🏋️🏠 Keduanya</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Tag Peralatan</label>
            <div class="flex flex-wrap gap-2">
                @foreach($allTags as $val => $label)
                <label class="flex items-center gap-1 text-xs font-bold cursor-pointer px-3 py-1"
                       style="border:2px solid #2A2A2A;">
                    <input type="checkbox" name="equipment_tags[]" value="{{ $val }}"
                        {{ in_array($val, old('equipment_tags', $program->equipment_tags ?? [])) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Cover Image</label>
            @if($program->cover_image_path)
                <img src="{{ asset('storage/'.$program->cover_image_path) }}"
                     class="mb-3 w-full object-cover" style="max-height:160px; border:2px solid #2A2A2A;">
                <p class="text-xs text-gray-500 mb-2">Upload baru untuk menggantikan cover di atas.</p>
            @endif
            <input type="file" name="cover_image" accept="image/*" class="input-flat w-full">
        </div>

        <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $program->is_active) ? 'checked' : '' }}>
            Program aktif (tampil di halaman program)
        </label>
    </div>

    {{-- Hari Latihan --}}
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-black uppercase" style="color:#FFD500;">Hari Latihan</h2>
        <button type="button" onclick="renderDays()" class="btn-outline text-xs">⚙️ Reset & Generate Ulang</button>
    </div>

    <div id="daysContainer" class="space-y-4"></div>

    <button type="submit" class="btn-primary w-full">Simpan Perubahan</button>
</form>

<script>
const allExercises = @json($exercisesJson);
const existingDays = @json($existingDays);
let daySetCounts = {};

function exerciseOptions(selectedId) {
    return allExercises.map(function(e) {
        return '<option value="' + e.id + '"' + (e.id == selectedId ? ' selected' : '') + '>' +
               e.name + ' (' + e.muscle + ')</option>';
    }).join('');
}

function renderDays(prefill) {
    const total = parseInt(document.getElementById('sessionsPerWeek').value) || 1;
    const container = document.getElementById('daysContainer');
    container.innerHTML = '';
    daySetCounts = {};

    for (let d = 0; d < total; d++) {
        daySetCounts[d] = 0;
        const div = document.createElement('div');
        div.className = 'card-flat overflow-hidden';
        div.innerHTML =
            '<div class="px-6 py-3 flex justify-between items-center" style="background:#1a1a1a; border-bottom:2px solid #2A2A2A;">' +
            '<h3 class="font-black uppercase" style="color:#FFD500;">Hari ' + (d + 1) + '</h3></div>' +
            '<div class="p-6"><div class="space-y-3" id="exList-' + d + '"></div>' +
            '<button type="button" class="btn-outline text-xs mt-4" onclick="addExRow(' + d + ', null, 3, 12, null)">+ Tambah Gerakan</button>' +
            '</div>';
        container.appendChild(div);

        const dayData = prefill ? (prefill[d] || []) : [];
        if (dayData.length > 0) {
            dayData.forEach(function(ex) {
                addExRow(d, ex.exercise_id, ex.target_sets, ex.target_reps, ex.suggested_start_weight);
            });
        } else {
            addExRow(d, null, 3, 12, null);
        }
    }
}

function addExRow(dayIdx, exId, sets, reps, weight) {
    const idx = daySetCounts[dayIdx]++;
    const list = document.getElementById('exList-' + dayIdx);
    const row = document.createElement('div');
    row.className = 'card-flat p-4';
    row.style.borderColor = '#2A2A2A';
    row.innerHTML =
        '<div class="flex gap-3 items-center mb-3">' +
        '<select name="days[' + dayIdx + '][exercises][' + idx + '][exercise_id]" class="input-flat flex-1" required>' +
        '<option value="">Pilih Gerakan</option>' + exerciseOptions(exId) + '</select>' +
        '<button type="button" onclick="this.closest(\'.card-flat\').remove()" ' +
        'class="btn-outline text-xs flex-shrink-0" style="border-color:#ef4444;color:#ef4444;">Hapus</button>' +
        '</div>' +
        '<div class="grid grid-cols-3 gap-3">' +
        '<div><label class="block text-xs uppercase text-gray-500 font-bold mb-1">Sets</label>' +
        '<input type="number" name="days[' + dayIdx + '][exercises][' + idx + '][target_sets]" ' +
        'value="' + (sets || 3) + '" min="1" max="10" class="input-flat w-full" required></div>' +
        '<div><label class="block text-xs uppercase text-gray-500 font-bold mb-1">Reps</label>' +
        '<input type="number" name="days[' + dayIdx + '][exercises][' + idx + '][target_reps]" ' +
        'value="' + (reps || 12) + '" min="1" max="100" class="input-flat w-full" required></div>' +
        '<div><label class="block text-xs uppercase text-gray-500 font-bold mb-1">Beban Awal (kg)</label>' +
        '<input type="number" step="0.5" name="days[' + dayIdx + '][exercises][' + idx + '][suggested_start_weight]" ' +
        'value="' + (weight || '') + '" class="input-flat w-full"></div>' +
        '</div>';
    list.appendChild(row);
}

// Load dengan data existing saat halaman pertama dibuka
renderDays(existingDays);
</script>

@endsection