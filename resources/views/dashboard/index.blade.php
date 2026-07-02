@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-black uppercase">Dashboard</h1>
    <p class="text-gray-400 text-sm">Halo, <span class="font-bold text-white">{{ auth()->user()->name }}</span> 👊</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="card-flat p-5">
        <p class="text-3xl font-black" style="color:#FFD500;">{{ $totalSessions }}</p>
        <p class="text-xs uppercase text-gray-400 mt-1">Total Sesi</p>
    </div>
    <div class="card-flat p-5">
        <p class="text-3xl font-black" style="color:#FFD500;">{{ $thisWeekSessions }}</p>
        <p class="text-xs uppercase text-gray-400 mt-1">Sesi Minggu Ini</p>
    </div>
    <div class="card-flat p-5">
        <p class="text-3xl font-black" style="color:#FFD500;">{{ $enrollment?->program->sessions_per_week ?? '-' }}</p>
        <p class="text-xs uppercase text-gray-400 mt-1">Target/Minggu</p>
    </div>
</div>

@if(!$enrollment)
    <div class="card-flat p-10 text-center">
        <p class="mb-4 text-gray-300">Kamu belum punya program aktif.</p>
        <a href="{{ route('programs.index') }}" class="btn-primary">Pilih Program Latihan</a>
    </div>
@else

{{-- Program Header --}}
<div class="card-flat p-6 mb-6 flex justify-between items-center">
    <div>
        <p class="text-xs uppercase text-gray-400 mb-1">Program Aktif</p>
        <h2 class="text-xl font-black">{{ $enrollment->program->name }}</h2>
        <p class="text-xs text-gray-400 mt-1">Mulai {{ $enrollment->started_at->format('d M Y') }} &middot; {{ $enrollment->program->duration_weeks }} minggu</p>
    </div>
    <a href="{{ route('programs.index') }}" class="btn-outline text-xs">Ganti Program</a>
</div>

{{-- KALENDER --}}
@if($calendar)
<div class="mb-8">
    <h2 class="text-lg font-black uppercase mb-4" style="color:#FFD500;">Jadwal Latihan</h2>

    {{-- Legend --}}
    <div class="flex gap-4 mb-4 text-xs uppercase font-bold flex-wrap">
        <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block" style="background:#FFD500;"></span> Selesai</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block" style="background:#ef4444;"></span> Skip/Absen</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block" style="background:#3f3f3f;"></span> Rest Day</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block border-2" style="border-color:#FFD500;"></span> Hari Ini</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block" style="background:#2A2A2A;"></span> Upcoming</span>
    </div>

    @foreach($calendar['byMonth'] as $month)
    <div class="card-flat p-5 mb-4">
        <h3 class="font-black uppercase text-sm mb-4" style="color:#FFD500;">{{ $month['label'] }}</h3>
        {{-- Header hari --}}
        <div class="grid grid-cols-7 gap-1 mb-1">
            @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $d)
                <div class="text-center text-xs text-gray-500 uppercase font-bold py-1">{{ $d }}</div>
            @endforeach
        </div>
        {{-- Sel-sel hari --}}
        <div class="grid grid-cols-7 gap-1">
            @foreach($month['days'] as $day)
                @if($day === null)
                    <div></div>
                @else
                    @php
                        $status = $day['status'];
                        $bg = match($status) {
                            'done'     => '#FFD500',
                            'skip','missed' => '#3b0000',
                            'rest'     => '#1a1a1a',
                            'today'    => '#161616',
                            default    => '#161616',
                        };
                        $border = match($status) {
                            'done'     => '#FFD500',
                            'skip','missed' => '#ef4444',
                            'today'    => '#FFD500',
                            default    => '#2A2A2A',
                        };
                        $textColor = $status === 'done' ? '#0A0A0A' : '#fff';
                        $icon = match($status) {
                            'done'    => '✓',
                            'skip'    => '✗',
                            'missed'  => '✗',
                            'rest'    => '💤',
                            'today'   => '▶',
                            default   => '',
                        };
                        $dayJson = json_encode($day, JSON_HEX_APOS | JSON_HEX_QUOT);
                    @endphp
                    <button
                        type="button"
                        onclick="openDayModal({{ $dayJson }})"
                        class="relative flex flex-col items-center justify-center py-2 text-xs font-bold transition-all"
                        style="background:{{ $bg }}; border:2px solid {{ $border }}; color:{{ $textColor }}; min-height:52px;"
                        title="{{ $day['date'] }} — {{ ucfirst($status) }}"
                    >
                        <span>{{ $day['day_num'] }}</span>
                        <span class="text-xs mt-0.5">{{ $icon }}</span>
                        @if($day['program_day_number'] && $status !== 'rest')
                            <span class="text-xs opacity-60">D{{ $day['program_day_number'] }}</span>
                        @endif
                    </button>
                @endif
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endif

@endif {{-- end if enrollment --}}

{{-- MODAL DETAIL HARI --}}
<div id="dayModal" class="fixed inset-0 z-50 hidden" style="background:rgba(0,0,0,0.85);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-lg card-flat p-8 relative" style="max-height:90vh; overflow-y:auto;">
            <button onclick="closeDayModal()" class="absolute top-4 right-4 font-black text-gray-400 hover:text-white text-xl">✕</button>

            <div id="modalHeader" class="mb-6">
                <p id="modalDate" class="text-xs uppercase text-gray-400"></p>
                <h3 id="modalTitle" class="text-xl font-black mt-1"></h3>
                <span id="modalBadge" class="text-xs uppercase font-bold px-2 py-1 mt-2 inline-block"></span>
            </div>

            {{-- Exercises / Log Summary --}}
            <div id="modalExercises" class="mb-6"></div>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="block text-xs uppercase font-bold mb-2">Catatan Hari Ini</label>
                <textarea id="modalNotes" rows="3" class="input-flat w-full" placeholder="Catat sesuatu... (cidera, kondisi, dll)"></textarea>
                <button onclick="saveNote()" class="btn-outline text-xs mt-2">Simpan Catatan</button>
            </div>

            {{-- Action Buttons --}}
            <div id="modalActions" class="flex gap-3 flex-wrap"></div>
        </div>
    </div>
</div>
@php
    $exercisesByDayJson = $enrollment
        ? $enrollment->program->exercisesByDay()->map(function($items) {
            return $items->map(function($pe) {
                return [
                    'name'        => $pe->exercise->name,
                    'muscle'      => $pe->exercise->muscleGroup->name,
                    'target_sets' => $pe->target_sets,
                    'target_reps' => $pe->target_reps,
                ];
            });
        })
        : [];
@endphp
<script>
const enrollmentId = {{ $enrollment?->id ?? 'null' }};
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
    || '{{ csrf_token() }}';

let currentDay = null;

// Data program exercises per day (untuk tampil di modal)
const programExercisesByDay = @json($exercisesByDayJson);

function openDayModal(day) {
    currentDay = day;
    const modal = document.getElementById('dayModal');

    // Header
    const dateObj = new Date(day.date + 'T00:00:00');
    document.getElementById('modalDate').textContent =
        dateObj.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });

    const titles = {
        done: 'Latihan Selesai ✓',
        skip: 'Absen / Skip',
        missed: 'Tidak Berlatih',
        rest: 'Rest Day 💤',
        today: 'Hari Ini — Latihan Sekarang',
        upcoming: `Hari Latihan — Sesi ${day.program_day_number ?? '?'}`,
    };
    document.getElementById('modalTitle').textContent = titles[day.status] ?? day.status;

    // Badge
    const badgeColors = {
        done: '#FFD500', skip: '#ef4444', missed: '#ef4444',
        rest: '#3f3f3f', today: '#FFD500', upcoming: '#2A2A2A',
    };
    const badge = document.getElementById('modalBadge');
    badge.textContent = day.status.toUpperCase();
    badge.style.background = badgeColors[day.status] ?? '#2A2A2A';
    badge.style.color = day.status === 'done' || day.status === 'today' ? '#0A0A0A' : '#fff';

    // Exercises
    const exDiv = document.getElementById('modalExercises');
    exDiv.innerHTML = '';

    if (day.log_summary && day.log_summary.length > 0) {
        // Tampilkan yang sudah dilakukan
        exDiv.innerHTML = '<p class="text-xs uppercase font-bold text-gray-400 mb-2">Yang Sudah Dilakukan</p>';
        day.log_summary.forEach(log => {
            exDiv.innerHTML += `
                <div class="flex justify-between text-sm border-b py-2" style="border-color:#2A2A2A;">
                    <span class="font-bold">${log.exercise}</span>
                    <span class="text-gray-400">${log.sets} set &middot; maks ${log.max_weight ?? '-'} ${log.unit}</span>
                </div>`;
        });
    } else if (day.is_effective_workout && day.program_day_number && programExercisesByDay[day.program_day_number]) {
        // Tampilkan yang direncanakan
        exDiv.innerHTML = '<p class="text-xs uppercase font-bold text-gray-400 mb-2">Gerakan Hari Ini</p>';
        programExercisesByDay[day.program_day_number].forEach(ex => {
            exDiv.innerHTML += `
                <div class="flex justify-between text-sm border-b py-2" style="border-color:#2A2A2A;">
                    <span class="font-bold">${ex.name}</span>
                    <span class="text-gray-400">${ex.target_sets} x ${ex.target_reps} &middot; ${ex.muscle}</span>
                </div>`;
        });
    } else if (day.status === 'rest') {
        exDiv.innerHTML = '<p class="text-sm text-gray-500">Hari ini rest. Istirahat yang cukup!</p>';
    }

    // Notes
    document.getElementById('modalNotes').value = day.notes ?? '';

    // Action buttons
    const actDiv = document.getElementById('modalActions');
    actDiv.innerHTML = '';

    if (day.status === 'today' || day.status === 'upcoming') {
        actDiv.innerHTML += `
            <a href="/workout/create?day=${day.program_day_number ?? 1}"
               class="btn-primary text-sm">Mulai Latihan Program</a>
            <a href="/workout/create?day=${day.program_day_number ?? 1}&custom=1"
               class="btn-outline text-sm">Sesi Custom</a>`;
    }

    if (day.is_base_workout && day.status !== 'done') {
        // Tombol ganti ke rest
        const isCurrentlyRest = day.override_type === 'rest';
        actDiv.innerHTML += `
            <button onclick="swapDay('${day.date}', '${isCurrentlyRest ? 'workout' : 'rest'}')"
                class="btn-outline text-xs" style="border-color:#888; color:#888;">
                ${isCurrentlyRest ? '↩ Kembalikan ke Workout' : '💤 Ganti ke Rest'}
            </button>`;
    }

    if (!day.is_base_workout && day.status !== 'done') {
        // Rest day bisa dijadikan workout
        actDiv.innerHTML += `
            <button onclick="swapDay('${day.date}', 'workout')"
                class="btn-outline text-xs">
                ＋ Jadikan Hari Latihan
            </button>`;
    }

    if (day.is_effective_workout && day.status !== 'done' && !day.is_past) {
        actDiv.innerHTML += `
            <button onclick="swapDay('${day.date}', 'skip')"
                class="btn-outline text-xs" style="border-color:#ef4444; color:#ef4444;">
                ✗ Tandai Skip
            </button>`;
    }

    modal.classList.remove('hidden');
}

function closeDayModal() {
    document.getElementById('dayModal').classList.add('hidden');
    currentDay = null;
}

async function swapDay(date, overrideType) {
    const dayNumber = currentDay?.program_day_number ?? 1;
    const res = await fetch('/schedule/swap', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
            date,
            program_enrollment_id: enrollmentId,
            override_type: overrideType,
            day_number: dayNumber,
        }),
    });
    if (res.ok) {
        closeDayModal();
        window.location.reload();
    }
}

async function saveNote() {
    if (!currentDay) return;
    const notes = document.getElementById('modalNotes').value;
    const res = await fetch('/schedule/note', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
            date: currentDay.date,
            program_enrollment_id: enrollmentId,
            notes,
        }),
    });
    if (res.ok) {
        // Feedback singkat
        const btn = document.querySelector('button[onclick="saveNote()"]');
        const orig = btn.textContent;
        btn.textContent = '✓ Tersimpan';
        setTimeout(() => { btn.textContent = orig; }, 1500);
    }
}

// Tutup modal saat klik background
document.getElementById('dayModal').addEventListener('click', function(e) {
    if (e.target === this) closeDayModal();
});
</script>
@endsection