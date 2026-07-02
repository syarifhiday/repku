@extends('layouts.app')
@section('title', 'Pilih Program')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-black uppercase">Program Latihan</h1>
    <a href="{{ route('programs.create-custom') }}" class="btn-outline text-sm">+ Buat Program Sendiri</a>
</div>

@php
$locationIcons = ['gym' => '🏋️ Gym', 'rumah' => '🏠 Rumah', 'keduanya' => '🏋️🏠'];
$equipmentIcons = [
    'barbell' => 'Barbell', 'dumbell' => 'Dumbbell', 'cable' => 'Cable',
    'mesin_gym' => 'Mesin', 'resistance_band' => 'Resistance Band',
    'pull_up_bar' => 'Pull-Up Bar', 'kettlebell' => 'Kettlebell', 'bodyweight' => 'Tanpa Alat',
];
@endphp

@foreach($programs as $goal => $list)
<div class="mb-12">
    <h2 class="text-base font-black uppercase mb-4 pb-2" style="color:#FFD500; border-bottom:2px solid #2A2A2A;">
        {{ $goalLabels[$goal] ?? $goal }}
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($list as $program)
        <div class="card-flat flex flex-col overflow-hidden hover:border-yellow-400 transition-all" style="border-color:#2A2A2A;">
            {{-- Cover Image --}}
            @if($program->cover_image_path)
                <img src="{{ asset('storage/'.$program->cover_image_path) }}"
                     alt="{{ $program->name }}"
                     class="w-full object-cover"
                     style="height:140px;">
            @else
                <div class="w-full flex items-center justify-center font-black text-2xl"
                     style="height:140px; background:#0f0f0f; color:#2A2A2A;">
                    {{ match($goal) {
                        'latihan_dada' => '🏋️',
                        'latihan_punggung' => '💪',
                        'latihan_kaki' => '🦵',
                        'latihan_bokong_paha' => '🍑',
                        'latihan_perut' => '🔥',
                        'latihan_bahu_lengan' => '💪',
                        'membesarkan_badan' => '📈',
                        'menguruskan_badan' => '📉',
                        'full_body' => '⚡',
                        default => '🎯',
                    } }}
                </div>
            @endif

            <div class="p-5 flex flex-col flex-1">
                <h3 class="font-black mb-1 text-sm leading-tight">{{ $program->name }}</h3>
                <p class="text-gray-500 text-xs mb-3 flex-1 leading-relaxed" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $program->description }}
                </p>

                {{-- Meta --}}
                <div class="flex flex-wrap gap-1 mb-3">
                    {{-- Lokasi --}}
                    <span class="text-xs px-2 py-0.5 font-bold" style="background:#161616; border:1px solid #3f3f3f;">
                        {{ $locationIcons[$program->location_type] ?? $program->location_type }}
                    </span>
                    {{-- Equipment Tags --}}
                    @foreach(($program->equipment_tags ?? []) as $tag)
                        <span class="text-xs px-2 py-0.5 font-bold" style="background:#161616; border:1px solid #3f3f3f;">
                            {{ $equipmentIcons[$tag] ?? $tag }}
                        </span>
                    @endforeach
                </div>

                {{-- Stats --}}
                <div class="flex justify-between text-xs text-gray-500 uppercase font-bold mb-4" style="border-top:1px solid #2A2A2A; padding-top:10px; margin-top:auto;">
                    <span>{{ $program->sessions_per_week }}x/minggu</span>
                    <span>{{ $program->duration_weeks }} minggu</span>
                </div>

                {{-- CTA --}}
                <div class="flex gap-2">
                    <a href="{{ route('programs.show', $program->slug) }}" class="btn-outline text-xs flex-1 text-center">Detail</a>
                    <button type="button"
                        onclick="confirmEnroll('{{ $program->slug }}', '{{ addslashes($program->name) }}', {{ $program->duration_weeks }})"
                        class="btn-primary text-xs flex-1">Enroll</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

{{-- Hidden enroll forms --}}
@foreach($programs->flatten() as $program)
<form id="enrollForm-{{ $program->slug }}" action="{{ route('programs.enroll', $program->slug) }}" method="POST" class="hidden">
    @csrf
</form>
@endforeach

{{-- Confirmation Modal --}}
<div id="enrollModal" class="fixed inset-0 z-50 hidden" style="background:rgba(0,0,0,0.88);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md card-flat p-8" style="border-color:#FFD500;">
            <h3 class="text-xl font-black uppercase mb-2">Enroll ke Program Ini?</h3>
            <p id="enrollModalProgramName" class="font-bold mb-5" style="color:#FFD500;"></p>
            <div class="space-y-2 mb-6 text-sm text-gray-400">
                <p>⚠️ Program aktif kamu saat ini akan <strong class="text-white">dijeda</strong>.</p>
                <p>📅 Jadwal mulai ulang dari <strong class="text-white">Hari 1 hari ini</strong>.</p>
                <p>💾 Riwayat dan progress sebelumnya <strong class="text-white">tetap tersimpan</strong>.</p>
                <p>🗓️ Durasi program: <strong class="text-white" id="enrollModalDuration"></strong> minggu.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeEnrollModal()" class="btn-outline flex-1">Batal</button>
                <button onclick="submitEnroll()" class="btn-primary flex-1">Ya, Mulai</button>
            </div>
        </div>
    </div>
</div>

<script>
let pendingSlug = null;
function confirmEnroll(slug, name, weeks) {
    pendingSlug = slug;
    document.getElementById('enrollModalProgramName').textContent = name;
    document.getElementById('enrollModalDuration').textContent = weeks;
    document.getElementById('enrollModal').classList.remove('hidden');
}
function closeEnrollModal() {
    document.getElementById('enrollModal').classList.add('hidden');
    pendingSlug = null;
}
function submitEnroll() {
    if (pendingSlug) document.getElementById('enrollForm-' + pendingSlug).submit();
}
document.getElementById('enrollModal').addEventListener('click', function(e) {
    if (e.target === this) closeEnrollModal();
});
</script>
@endsection
