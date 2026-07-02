@extends('layouts.app')
@section('title', 'Buat Program Sendiri')

@section('content')

@php
$exercisesJson = $exercises->map(function($e) {
    return [
        'id'     => $e->id,
        'name'   => $e->name,
        'muscle' => $e->muscleGroup->name,
        'unit'   => $e->weight_unit_default,
    ];
});
@endphp

<h1 class="text-3xl font-black uppercase mb-2">Buat Program Latihan Sendiri</h1>
<p class="text-gray-400 text-sm mb-8">Pilih gerakan, atur set & rep untuk tiap hari latihan. Program ini akan langsung diaktifkan setelah disimpan.</p>

<form action="{{ route('programs.store-custom') }}" method="POST" id="customForm">
    @csrf

    {{-- Info dasar --}}
    <div class="card-flat p-6 mb-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Nama Program</label>
                <input type="text" name="name" class="input-flat w-full" placeholder="misal: Program Saya Minggu Ini" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Durasi (minggu)</label>
                <input type="number" name="duration_weeks" value="8" min="1" max="52" class="input-flat w-full" required>
            </div>
        </div>
        <div>
            <label class="block text-xs uppercase font-bold mb-2">Deskripsi (opsional)</label>
            <textarea name="description" rows="2" class="input-flat w-full" placeholder="Tujuan program ini..."></textarea>
        </div>
        <div>
            <label class="block text-xs uppercase font-bold mb-2">Jumlah Sesi per Minggu</label>
            <div class="flex items-center gap-3">
                <input type="number" name="sessions_per_week" id="sessionsPerWeek"
                       value="3" min="1" max="7" class="input-flat" style="width:80px;" required>
                <button type="button" onclick="renderDays()" class="btn-primary text-sm">
                    Generate Hari Latihan
                </button>
            </div>
        </div>
    </div>

    {{-- Container hari --}}
    <div id="daysContainer" class="space-y-4 mb-6"></div>

    <button type="submit" class="btn-primary w-full text-lg">Simpan & Aktifkan Program</button>
</form>

<script>
const allExercises = @json($exercisesJson);
let daySetCounts = {};

function exerciseOptions() {
    return allExercises.map(function(e) {
        return '<option value="' + e.id + '" data-unit="' + e.unit + '">' + e.name + ' — ' + e.muscle + '</option>';
    }).join('');
}

function renderDays() {
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
            '<div class="p-6">' +
            '<div class="space-y-2" id="exList-' + d + '"></div>' +
            '<button type="button" class="btn-outline text-xs mt-4" onclick="addExerciseRow(' + d + ')">+ Tambah Gerakan</button>' +
            '</div>';
        container.appendChild(div);
        addExerciseRow(d); // satu default
    }
}

function addExerciseRow(dayIdx) {
    const idx = daySetCounts[dayIdx]++;
    const list = document.getElementById('exList-' + dayIdx);
    const row = document.createElement('div');
    row.className = 'card-flat p-4';
    row.style.borderColor = '#2A2A2A';
    row.innerHTML =
        '<div class="flex gap-3 items-center mb-3">' +
        '<select name="days[' + dayIdx + '][exercises][' + idx + '][exercise_id]" ' +
        '        class="input-flat flex-1" required>' +
        '<option value="">Pilih Gerakan</option>' + exerciseOptions() +
        '</select>' +
        '<button type="button" onclick="this.closest(\'.card-flat\').remove()" ' +
        '        class="btn-outline text-xs flex-shrink-0" style="border-color:#ef4444;color:#ef4444;">Hapus</button>' +
        '</div>' +
        '<div class="grid grid-cols-2 gap-3">' +
        '<div>' +
        '<label class="block text-xs uppercase text-gray-500 font-bold mb-1">Target Set</label>' +
        '<input type="number" name="days[' + dayIdx + '][exercises][' + idx + '][target_sets]" ' +
        '       value="3" min="1" max="10" class="input-flat w-full" required>' +
        '</div>' +
        '<div>' +
        '<label class="block text-xs uppercase text-gray-500 font-bold mb-1">Target Reps</label>' +
        '<input type="number" name="days[' + dayIdx + '][exercises][' + idx + '][target_reps]" ' +
        '       value="12" min="1" max="100" class="input-flat w-full" required>' +
        '</div>' +
        '</div>';
    list.appendChild(row);
}

// Auto-render saat halaman load
renderDays();
</script>

@endsection