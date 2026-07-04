@extends('layouts.app')
@section('title', 'Buat Program Sendiri')

@section('content')

@php
$exercisesJson = $exercises->map(function($e) {
    return ['id' => $e->id, 'name' => $e->name, 'muscle' => $e->muscleGroup->name];
});
@endphp

<h1 class="text-3xl font-black uppercase mb-2">Buat Program Latihan Sendiri</h1>
<p class="text-gray-400 text-sm mb-8">Atur jadwal mingguan dan gerakan untuk tiap hari latihan.</p>

<form action="{{ route('programs.store-custom') }}" method="POST" id="customForm">
    @csrf

    {{-- Info dasar --}}
    <div class="card-flat p-6 mb-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Nama Program</label>
                <input type="text" name="name" class="input-flat w-full" placeholder="misal: Program Push Pull Ku" required>
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
    </div>

    {{-- JADWAL MINGGUAN --}}
    <div class="card-flat p-6 mb-6">
        <h2 class="font-black uppercase mb-1" style="color:#FFD500;">Jadwal Mingguan</h2>
        <p class="text-xs text-gray-400 mb-5">Klik tiap hari untuk set apakah itu hari latihan atau rest. Drag & assign sesi berapa untuk tiap hari workout.</p>

        <div id="scheduleBuilder" class="grid grid-cols-7 gap-2 mb-4">
            @php $dayNames = ['Sen','Sel','Rab','Kam','Jum','Sab','Min']; @endphp
            @foreach($dayNames as $di => $dn)
            <div>
                <div class="text-center text-xs font-bold uppercase mb-1 text-gray-400">{{ $dn }}</div>
                <button type="button"
                        id="dayBtn-{{ $di }}"
                        onclick="toggleDay({{ $di }})"
                        class="w-full py-3 font-black text-sm flex flex-col items-center justify-center gap-1"
                        style="border:3px solid #2A2A2A; background:#161616; color:#fff;"
                        data-active="0" data-day-index="{{ $di }}">
                    <span id="dayIcon-{{ $di }}">💤</span>
                    <span id="dayLabel-{{ $di }}" class="text-xs">Rest</span>
                </button>
                {{-- Hidden input untuk weekly_schedule --}}
                <input type="hidden" name="weekly_schedule[{{ $di }}]" id="scheduleInput-{{ $di }}" value="">
            </div>
            @endforeach
        </div>

        <div class="text-xs text-gray-500 uppercase font-bold">
            Sesi aktif: <span id="sessionCount" style="color:#FFD500;">0</span> /minggu
        </div>
        <input type="hidden" name="sessions_per_week" id="sessionsPerWeek" value="0">
    </div>

    {{-- GERAKAN PER HARI --}}
    <div id="daysContainer" class="space-y-4 mb-6"></div>

    <button type="submit" id="submitBtn" class="btn-primary w-full text-lg" disabled
            style="opacity:0.4; cursor:not-allowed;">
        Simpan & Aktifkan Program
    </button>
</form>

<script>
const allExercises = @json($exercisesJson);
let schedule = [null, null, null, null, null, null, null]; // index 0=Sen..6=Min
let daySetCounts = {};

function exerciseOptions() {
    return allExercises.map(function(e) {
        return '<option value="' + e.id + '">' + e.name + ' — ' + e.muscle + '</option>';
    }).join('');
}

function toggleDay(dayIndex) {
    const btn      = document.getElementById('dayBtn-' + dayIndex);
    const icon     = document.getElementById('dayIcon-' + dayIndex);
    const label    = document.getElementById('dayLabel-' + dayIndex);
    const input    = document.getElementById('scheduleInput-' + dayIndex);
    const isActive = btn.dataset.active === '1';

    if (isActive) {
        // Matikan -> rest
        schedule[dayIndex] = null;
        btn.dataset.active = '0';
        btn.style.background = '#161616';
        btn.style.borderColor = '#2A2A2A';
        btn.style.color = '#fff';
        icon.textContent = '💤';
        label.textContent = 'Rest';
        input.value = '';
    } else {
        // Aktifkan -> assign ke sesi berikutnya
        const nextSession = assignNextSession();
        schedule[dayIndex] = nextSession;
        btn.dataset.active = '1';
        btn.style.background = '#FFD500';
        btn.style.borderColor = '#FFD500';
        btn.style.color = '#0A0A0A';
        icon.textContent = '💪';
        label.textContent = 'Sesi ' + nextSession;
        input.value = nextSession;
    }

    rebuildDayNumbers();
    updateSessionCount();
    renderDayForms();
}

function assignNextSession() {
    // Assign nomor sesi berurutan dari yang sudah ada
    const usedSessions = schedule.filter(s => s !== null);
    return usedSessions.length + 1;
}

function rebuildDayNumbers() {
    // Re-assign nomor sesi berurutan sesuai urutan hari (Sen..Min)
    let sessionNum = 1;
    for (let i = 0; i < 7; i++) {
        if (schedule[i] !== null) {
            schedule[i] = sessionNum;
            const btn   = document.getElementById('dayBtn-' + i);
            const label = document.getElementById('dayLabel-' + i);
            const input = document.getElementById('scheduleInput-' + i);
            label.textContent = 'Sesi ' + sessionNum;
            input.value = sessionNum;
            sessionNum++;
        }
    }
}

function updateSessionCount() {
    const count = schedule.filter(s => s !== null).length;
    document.getElementById('sessionCount').textContent = count;
    document.getElementById('sessionsPerWeek').value = count;
    const btn = document.getElementById('submitBtn');
    if (count > 0) {
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    } else {
        btn.disabled = true;
        btn.style.opacity = '0.4';
        btn.style.cursor = 'not-allowed';
    }
}

function renderDayForms() {
    const totalSessions = schedule.filter(s => s !== null).length;
    const container = document.getElementById('daysContainer');
    const dayFullNames = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

    // Kumpulkan sesi aktif per day index
    const activeDays = [];
    for (let i = 0; i < 7; i++) {
        if (schedule[i] !== null) {
            activeDays.push({ dowIndex: i, session: schedule[i] });
        }
    }

    // Rebuild hanya kalau jumlah sesi berubah
    const existing = container.querySelectorAll('.day-form-card').length;
    if (existing === activeDays.length) {
        // Update label saja
        activeDays.forEach(function(d) {
            const card = document.getElementById('dayCard-' + d.session);
            if (card) {
                const header = card.querySelector('.day-header-label');
                if (header) header.textContent = dayFullNames[d.dowIndex] + ' — Sesi ' + d.session;
            }
        });
        return;
    }

    container.innerHTML = '';
    daySetCounts = {};

    activeDays.forEach(function(d) {
        daySetCounts[d.session] = 0;
        const div = document.createElement('div');
        div.className = 'card-flat overflow-hidden day-form-card';
        div.id = 'dayCard-' + d.session;
        div.innerHTML =
            '<div class="px-6 py-3 flex justify-between items-center" style="background:#1a1a1a; border-bottom:2px solid #2A2A2A;">' +
            '<h3 class="font-black uppercase day-header-label" style="color:#FFD500;">' +
            dayFullNames[d.dowIndex] + ' — Sesi ' + d.session + '</h3></div>' +
            '<div class="p-6">' +
            '<div class="space-y-2" id="exList-' + d.session + '"></div>' +
            '<button type="button" class="btn-outline text-xs mt-4" onclick="addExRow(' + d.session + ')">+ Tambah Gerakan</button>' +
            '</div>';
        container.appendChild(div);
        addExRow(d.session);
    });
}

function addExRow(sessionNum) {
    if (!daySetCounts[sessionNum] && daySetCounts[sessionNum] !== 0) daySetCounts[sessionNum] = 0;
    const idx  = daySetCounts[sessionNum]++;
    const dIdx = sessionNum - 1; // days[] array index
    const list = document.getElementById('exList-' + sessionNum);
    const row  = document.createElement('div');
    row.className = 'card-flat p-4';
    row.style.borderColor = '#2A2A2A';
    row.innerHTML =
        '<div class="flex gap-3 items-center mb-3">' +
        '<select name="days[' + dIdx + '][exercises][' + idx + '][exercise_id]" class="input-flat flex-1" required>' +
        '<option value="">Pilih Gerakan</option>' + exerciseOptions() + '</select>' +
        '<button type="button" onclick="this.closest(\'.card-flat\').remove()" ' +
        'class="btn-outline text-xs flex-shrink-0" style="border-color:#ef4444;color:#ef4444;">Hapus</button>' +
        '</div>' +
        '<div class="grid grid-cols-2 gap-3">' +
        '<div><label class="block text-xs uppercase text-gray-500 font-bold mb-1">Target Set</label>' +
        '<input type="number" name="days[' + dIdx + '][exercises][' + idx + '][target_sets]" ' +
        'value="3" min="1" max="10" class="input-flat w-full" required></div>' +
        '<div><label class="block text-xs uppercase text-gray-500 font-bold mb-1">Target Reps</label>' +
        '<input type="number" name="days[' + dIdx + '][exercises][' + idx + '][target_reps]" ' +
        'value="12" min="1" max="100" class="input-flat w-full" required></div>' +
        '</div>';
    list.appendChild(row);
}
</script>

@endsection
