@extends('layouts.app')
@section('title', 'Mulai Latihan')

@section('content')

@php
    $allExercisesJson = $allExercises->map(function($e) {
        return [
            'id'     => $e->id,
            'name'   => $e->name,
            'muscle' => $e->muscleGroup->name,
            'unit'   => $e->weight_unit_default,
        ];
    });
@endphp

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-black uppercase">{{ $enrollment->program->name }}</h1>
        <p class="text-gray-400 text-sm mt-1">Hari {{ $dayNumber }} &middot; {{ now()->format('d M Y') }}</p>
    </div>
    <div class="flex gap-2">
        <button id="btnProgram" onclick="setMode('program')" class="btn-primary text-xs">Program</button>
        <button id="btnCustom"  onclick="setMode('custom')"  class="btn-outline text-xs">Sesi Custom</button>
    </div>
</div>



<form id="workoutForm" action="{{ route('workout.store') }}" method="POST">
    @csrf
    <input type="hidden" name="program_enrollment_id" value="{{ $enrollment->id }}">
    <input type="hidden" name="day_number" value="{{ $dayNumber }}">
    <input type="hidden" name="is_custom" id="isCustomInput" value="0">

    {{-- Lokasi & Durasi --}}
    <div class="card-flat p-6 grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="block text-xs uppercase font-bold mb-2">Lokasi</label>
            <select name="location" class="input-flat w-full" required>
                <option value="rumah">Di Rumah</option>
                <option value="gym">Di Gym</option>
            </select>
        </div>
        <div>
            <label class="block text-xs uppercase font-bold mb-2">Durasi (menit)</label>
            <input type="number" name="duration_minutes" class="input-flat w-full" placeholder="Opsional">
        </div>
    </div>

    {{-- ===== MODE PROGRAM ===== --}}
    <div id="modeProgram">
        @foreach($programExercises as $i => $pe)
        @php $sugg = $suggestions[$pe->exercise_id]; $isBand = $pe->exercise->weight_unit_default === 'band_level'; @endphp
        <div class="card-flat p-6 mb-4">

            {{-- Nama gerakan --}}
            <div class="mb-3">
                <h3 class="font-black text-lg">{{ $pe->exercise->name }}</h3>
                <p class="text-xs text-gray-400 uppercase mt-1">
                    {{ $pe->exercise->muscleGroup->name }}
                    &middot; {{ str_replace('_',' ',$pe->exercise->equipment_type) }}
                    &middot; Target {{ $pe->target_sets }}×{{ $pe->target_reps }}
                </p>
            </div>

            {{-- Ilustrasi gerakan --}}
            @if($pe->exercise->illustration_path)
                <div class="mb-4" style="border:2px solid #2A2A2A;">
                    <img src="{{ asset('storage/'.$pe->exercise->illustration_path) }}"
                         alt="{{ $pe->exercise->name }}"
                         class="w-full object-contain"
                         style="max-height:280px; background:#0A0A0A;">
                </div>
            @endif

            {{-- Cara melakukan --}}
            @if($pe->exercise->how_to)
                <p class="text-xs text-gray-400 mb-4 leading-relaxed">📋 {{ $pe->exercise->how_to }}</p>
            @endif

            {{-- Saran progressive overload --}}
            <p class="text-sm mb-4 p-3" style="background:#0A0A0A; border-left:3px solid #FFD500;">
                💡 {{ $sugg['message'] }}
            </p>

            {{-- Header kolom --}}
            <div class="grid gap-2 text-xs uppercase text-gray-500 font-bold mb-1 px-1"
                 style="grid-template-columns: 40px 1fr 1fr 1fr 32px;">
                <span>Set</span>
                <span>{{ $isBand ? 'Jml. Karet' : 'Beban (KG)' }}</span>
                <span>Reps</span>
                <span>Kondisi Set</span>
                <span></span>
            </div>

            <div id="programSets-{{ $i }}" class="space-y-2">
                @for($s = 1; $s <= $pe->target_sets; $s++)
                <div class="grid gap-2 items-center" style="grid-template-columns: 40px 1fr 1fr 1fr 32px;">
                    <span class="font-bold text-sm">{{ $s }}</span>
                    <input type="hidden" name="sets[{{ $i }}_{{ $s }}][exercise_id]"  value="{{ $pe->exercise_id }}">
                    <input type="hidden" name="sets[{{ $i }}_{{ $s }}][set_number]"   value="{{ $s }}">
                    <input type="hidden" name="sets[{{ $i }}_{{ $s }}][weight_unit]"  value="{{ $pe->exercise->weight_unit_default }}">

                    @if($isBand)
                        {{-- Jumlah karet resistance band --}}
                        <input type="number" min="1" max="20"
                               name="sets[{{ $i }}_{{ $s }}][weight_value]"
                               placeholder="Jml Karet" class="input-flat">
                    @else
                        <input type="number" step="0.5"
                               name="sets[{{ $i }}_{{ $s }}][weight_value]"
                               value="{{ $sugg['suggested_weight'] }}"
                               placeholder="KG" class="input-flat">
                    @endif

                    <input type="number" name="sets[{{ $i }}_{{ $s }}][reps]"
                           placeholder="Reps" min="0" max="200" class="input-flat wo-reps">
                    <select name="sets[{{ $i }}_{{ $s }}][rpe]" class="input-flat">
                        <option value="">— Pilih</option>
                        <option value="3">😌 Mudah</option>
                        <option value="6">💪 Sedang</option>
                        <option value="10">🔥 Failure</option>
                    </select>
                    <span></span>
                </div>
                @endfor
            </div>

            <button type="button"
                onclick="addProgramSet({{ $i }}, {{ $pe->exercise_id }}, '{{ $pe->exercise->weight_unit_default }}')"
                class="btn-outline text-xs mt-3">+ Set</button>
        </div>
        @endforeach
    </div>

    {{-- ===== MODE CUSTOM ===== --}}
    <div id="modeCustom" class="hidden">
        <div class="card-flat p-5 mb-4" style="border-color:#FFD500;">
            <p class="text-sm font-bold mb-1" style="color:#FFD500;">Mode Sesi Custom</p>
            <p class="text-xs text-gray-400">Pilih gerakan bebas, atur beban, reps, dan set sendiri. Cocok kalau lagi di luar gym atau alat terbatas.</p>
        </div>
        <div id="customExercisesContainer" class="space-y-4"></div>
        <button type="button" onclick="addCustomExercise()" class="btn-outline text-sm mt-4">+ Tambah Gerakan</button>
    </div>

    {{-- Notes --}}
    <div class="card-flat p-6 mt-6 mb-6">
        <label class="block text-xs uppercase font-bold mb-2">Catatan Sesi (opsional)</label>
        <textarea name="notes" rows="2" class="input-flat w-full"
                  placeholder="Kondisi hari ini, catatan cedera, pengurangan beban, dll..."></textarea>
    </div>

    <button type="button" onclick="beforeSubmit()" class="btn-primary w-full text-lg">Simpan Latihan</button>
</form>

{{-- Confirmation Modal Simpan --}}
<div id="submitModal" class="fixed inset-0 z-50 hidden" style="background:rgba(0,0,0,0.88);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md card-flat p-8">
            <h3 class="text-xl font-black uppercase mb-4">Simpan Latihan?</h3>
            <div id="submitWarning" class="mb-4 p-3 text-sm hidden" style="border-left:3px solid #FFD500; background:#0A0A0A;">
                <p class="font-bold text-white mb-1">⚠️ Ada set yang belum diisi reps-nya</p>
                <p class="text-gray-400 text-xs">Set yang kosong akan disimpan dengan nilai <strong class="text-white">0 reps</strong>. Pastikan ini memang yang kamu inginkan.</p>
            </div>
            <p class="text-gray-300 text-sm mb-6">Latihan akan dicatat dan jadwal kalendermu akan terupdate.</p>
            <div class="flex gap-3">
                <button onclick="closeSubmitModal()" class="btn-outline flex-1">Cek Lagi</button>
                <button onclick="doSubmit()" class="btn-primary flex-1">Ya, Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
const allExercises = @json($allExercisesJson);

let customExerciseCount = 0;
let programSetCounts = {};

@foreach($programExercises as $i => $pe)
programSetCounts[{{ $i }}] = {{ $pe->target_sets }};
@endforeach

// ===== MODE TOGGLE =====
function setMode(mode) {
    const isCustom = mode === 'custom';
    document.getElementById('modeProgram').classList.toggle('hidden', isCustom);
    document.getElementById('modeCustom').classList.toggle('hidden', !isCustom);
    document.getElementById('isCustomInput').value = isCustom ? '1' : '0';
    document.getElementById('btnProgram').className = isCustom ? 'btn-outline text-xs' : 'btn-primary text-xs';
    document.getElementById('btnCustom').className  = isCustom ? 'btn-primary text-xs' : 'btn-outline text-xs';
    if (isCustom && customExerciseCount === 0) addCustomExercise();
}

if (new URLSearchParams(window.location.search).get('custom') === '1') setMode('custom');

// ===== SUBMIT DENGAN KONFIRMASI =====
function beforeSubmit() {
    // Isi 0 untuk reps yang kosong
    let hasEmpty = false;
    document.querySelectorAll('.wo-reps, .wo-custom-reps').forEach(el => {
        if (el.value === '' || el.value === null) {
            el.value = 0;
            hasEmpty = true;
        }
    });

    const warning = document.getElementById('submitWarning');
    warning.classList.toggle('hidden', !hasEmpty);
    document.getElementById('submitModal').classList.remove('hidden');
}

function closeSubmitModal() {
    document.getElementById('submitModal').classList.add('hidden');
}

function doSubmit() {
    document.getElementById('workoutForm').submit();
}

document.getElementById('submitModal').addEventListener('click', function(e) {
    if (e.target === this) closeSubmitModal();
});

// ===== PROGRAM MODE: TAMBAH SET =====
function addProgramSet(exerciseIndex, exerciseId, unit) {
    const count = ++programSetCounts[exerciseIndex];
    const container = document.getElementById('programSets-' + exerciseIndex);
    const isBand = unit === 'band_level';
    const row = document.createElement('div');
    row.className = 'grid gap-2 items-center';
    row.style.gridTemplateColumns = '40px 1fr 1fr 1fr 32px';
    row.innerHTML = `
        <span class="font-bold text-sm">Set ${count}</span>
        <input type="hidden" name="sets[${exerciseIndex}_${count}][exercise_id]" value="${exerciseId}">
        <input type="hidden" name="sets[${exerciseIndex}_${count}][set_number]"  value="${count}">
        <input type="hidden" name="sets[${exerciseIndex}_${count}][weight_unit]" value="${unit}">
        <input type="number" ${isBand ? 'min="1" max="20"' : 'step="0.5"'}
               name="sets[${exerciseIndex}_${count}][weight_value]"
               placeholder="${isBand ? 'Jml Karet' : 'KG'}" class="input-flat">
        <input type="number" name="sets[${exerciseIndex}_${count}][reps]"
               placeholder="Reps" min="0" max="200" class="input-flat wo-reps">
        <select name="sets[${exerciseIndex}_${count}][rpe]" class="input-flat">
            <option value="">— Pilih</option>
            <option value="3">😌 Mudah</option>
            <option value="6">💪 Sedang</option>
            <option value="10">🔥 Failure</option>
        </select>
        <button type="button" onclick="this.closest('.grid').remove()"
                class="text-xs font-bold" style="color:#ef4444;">✕</button>
    `;
    container.appendChild(row);
}

// ===== CUSTOM MODE =====
function exerciseOptions() {
    return allExercises.map(e =>
        `<option value="${e.id}" data-unit="${e.unit}">${e.name} (${e.muscle})</option>`
    ).join('');
}

let customSetCounts = {};

function addCustomExercise() {
    const idx = customExerciseCount++;
    const container = document.getElementById('customExercisesContainer');
    const div = document.createElement('div');
    div.className = 'card-flat p-5';
    div.id = 'customEx-' + idx;
    div.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <select id="customExSelect-${idx}" onchange="syncCustomUnit(${idx})"
                class="input-flat mr-3" style="flex:1;">
                <option value="">Pilih Gerakan</option>
                ${exerciseOptions()}
            </select>
            <button type="button" onclick="document.getElementById('customEx-${idx}').remove()"
                class="btn-outline text-xs flex-shrink-0" style="border-color:#ef4444;color:#ef4444;">Hapus</button>
        </div>
        <div class="grid gap-2 text-xs uppercase text-gray-500 font-bold mb-1"
             style="grid-template-columns:40px 1fr 1fr 1fr 32px;">
            <span>Set</span><span id="customUnitLabel-${idx}">Beban</span><span>Reps</span><span>Kondisi Set</span><span></span>
        </div>
        <div id="customSets-${idx}" class="space-y-2"></div>
        <button type="button" onclick="addCustomSet(${idx})" class="btn-outline text-xs mt-3">+ Set</button>
    `;
    container.appendChild(div);
    addCustomSet(idx);
}

function addCustomSet(exIdx) {
    if (!customSetCounts[exIdx]) customSetCounts[exIdx] = 0;
    const setNum = ++customSetCounts[exIdx];
    const container = document.getElementById('customSets-' + exIdx);
    const select = document.getElementById('customExSelect-' + exIdx);
    const unit = select?.options[select.selectedIndex]?.dataset?.unit ?? 'kg';
    const isBand = unit === 'band_level';

    const row = document.createElement('div');
    row.className = 'grid gap-2 items-center';
    row.style.gridTemplateColumns = '40px 1fr 1fr 1fr 32px';
    row.innerHTML = `
        <span class="font-bold text-sm">Set ${setNum}</span>
        <input type="hidden" name="custom_sets[${exIdx}_${setNum}][exercise_id]"
               class="custom-ex-id-${exIdx}" value="${select?.value ?? ''}">
        <input type="hidden" name="custom_sets[${exIdx}_${setNum}][set_number]" value="${setNum}">
        <input type="hidden" name="custom_sets[${exIdx}_${setNum}][weight_unit]"
               class="custom-unit-${exIdx}" value="${unit}">
        <input type="number" ${isBand ? 'min="1" max="20"' : 'step="0.5"'}
               name="custom_sets[${exIdx}_${setNum}][weight_value]"
               placeholder="${isBand ? 'Jml Karet' : 'KG'}" class="input-flat">
        <input type="number" name="custom_sets[${exIdx}_${setNum}][reps]"
               placeholder="Reps" min="0" max="200" class="input-flat wo-custom-reps">
        <select name="custom_sets[${exIdx}_${setNum}][rpe]" class="input-flat">
            <option value="">— Pilih</option>
            <option value="3">😌 Mudah</option>
            <option value="6">💪 Sedang</option>
            <option value="10">🔥 Failure</option>
        </select>
        <button type="button" onclick="this.closest('.grid').remove()"
                class="text-xs font-bold" style="color:#ef4444;">✕</button>
    `;
    container.appendChild(row);
}

function syncCustomUnit(exIdx) {
    const select  = document.getElementById('customExSelect-' + exIdx);
    const opt     = select.options[select.selectedIndex];
    const unit    = opt?.dataset?.unit ?? 'kg';
    const isBand  = unit === 'band_level';
    const exId    = select.value;

    document.querySelectorAll('.custom-ex-id-' + exIdx).forEach(el => el.value = exId);
    document.querySelectorAll('.custom-unit-' + exIdx).forEach(el => el.value = unit);

    const label = document.getElementById('customUnitLabel-' + exIdx);
    if (label) label.textContent = isBand ? 'Jml. Karet' : 'Beban (KG)';
}
</script>
@endsection
