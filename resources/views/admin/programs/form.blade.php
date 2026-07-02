@extends('layouts.app')
@section('title', 'Buat Program Preset')

@section('content')
<h1 class="text-3xl font-black uppercase mb-8">Buat Program Preset Baru</h1>

<form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data" id="programForm" class="space-y-6">
    @csrf

    <div class="card-flat p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Nama Program</label>
                <input type="text" name="name" class="input-flat w-full" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Goal / Tujuan</label>
                <select name="goal" class="input-flat w-full" required>
                    @foreach($goalLabels as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Deskripsi</label>
            <textarea name="description" rows="3" class="input-flat w-full"></textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Durasi (minggu)</label>
                <input type="number" name="duration_weeks" value="8" min="1" max="52" class="input-flat w-full" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Sesi per Minggu</label>
                <input type="number" name="sessions_per_week" id="sessionsPerWeek" value="3" min="1" max="7" class="input-flat w-full" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Lokasi</label>
                <select name="location_type" class="input-flat w-full">
                    <option value="gym">🏋️ Gym</option>
                    <option value="rumah">🏠 Rumah</option>
                    <option value="keduanya">🏋️🏠 Keduanya</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Tag Peralatan (pilih semua yang dipakai)</label>
            <div class="flex flex-wrap gap-2">
                @php
                $allTags = ['barbell'=>'Barbell','dumbell'=>'Dumbbell','cable'=>'Cable','mesin_gym'=>'Mesin Gym',
                           'resistance_band'=>'Resistance Band','pull_up_bar'=>'Pull-Up Bar','kettlebell'=>'Kettlebell','bodyweight'=>'Tanpa Alat'];
                @endphp
                @foreach($allTags as $val => $label)
                <label class="flex items-center gap-1 text-xs font-bold cursor-pointer px-3 py-1" style="border:2px solid #2A2A2A;">
                    <input type="checkbox" name="equipment_tags[]" value="{{ $val }}"> {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Cover Image Program (opsional)</label>
            <input type="file" name="cover_image" accept="image/*" class="input-flat w-full">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Ukuran ideal: 600×400px atau rasio 3:2. Maks 4MB.</p>
        </div>
    </div>

    <div id="daysContainer"></div>
    <button type="button" onclick="renderDays()" class="btn-outline text-sm">⚙️ Generate Form Hari Latihan</button>
    <button type="submit" class="btn-primary">Simpan Program</button>
</form>

<script>
const exercises = @json($exercises->map(function($e) {
    return ['id' => $e->id, 'name' => $e->name, 'muscle' => $e->muscleGroup->name];
}));

function exerciseOptions() {
    return exercises.map(function(e) {
        return '<option value="' + e.id + '">' + e.name + ' (' + e.muscle + ')</option>';
    }).join('');
}

function renderDays() {
    const total = parseInt(document.getElementById('sessionsPerWeek').value) || 1;
    const container = document.getElementById('daysContainer');
    container.innerHTML = '';
    for (let d = 0; d < total; d++) {
        const div = document.createElement('div');
        div.className = 'card-flat p-6';
        div.innerHTML = '<h3 class="font-black uppercase mb-4" style="color:#FFD500;">Hari ' + (d+1) + '</h3>' +
            '<div id="exList-' + d + '" class="space-y-2"></div>' +
            '<button type="button" class="btn-outline text-xs mt-3" onclick="addEx(' + d + ')">+ Tambah Gerakan</button>';
        container.appendChild(div);
        addEx(d);
    }
}

function addEx(d) {
    const list = document.getElementById('exList-' + d);
    const row = document.createElement('div');
    row.className = 'grid gap-2 items-center';
    row.style.gridTemplateColumns = '1fr 80px 80px 100px 80px';
    row.innerHTML =
        '<select name="days[' + d + '][exercises][][exercise_id]" class="input-flat" required>' +
        '<option value="">Pilih Gerakan</option>' + exerciseOptions() + '</select>' +
        '<input type="number" name="days[' + d + '][exercises][][target_sets]" placeholder="Sets" min="1" max="10" value="3" class="input-flat" required>' +
        '<input type="number" name="days[' + d + '][exercises][][target_reps]" placeholder="Reps" min="1" max="100" value="12" class="input-flat" required>' +
        '<input type="number" step="0.5" name="days[' + d + '][exercises][][suggested_start_weight]" placeholder="Beban Awal" class="input-flat">' +
        '<button type="button" onclick="this.closest(\'.grid\').remove()" class="btn-outline text-xs" style="border-color:#ef4444;color:#ef4444;">Hapus</button>';
    list.appendChild(row);
}

renderDays();
</script>
@endsection
