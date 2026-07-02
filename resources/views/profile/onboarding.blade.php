@extends('layouts.app')
@section('title', 'Lengkapi Profil')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-black uppercase mb-2">Lengkapi Profil Kamu</h1>
    <p class="text-gray-400 mb-8 text-sm">Isi data ini supaya program latihan bisa disesuaikan dengan kondisi tubuhmu.</p>

    @if($errors->any())
        <div class="card-flat p-4 mb-6 text-sm" style="border-color:#ff3b3b;">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('onboarding.store') }}" method="POST" class="card-flat p-8 space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Jenis Kelamin</label>
                <select name="gender" class="input-flat w-full" required>
                    <option value="">Pilih</option>
                    <option value="pria">Pria</option>
                    <option value="wanita">Wanita</option>
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Tanggal Lahir</label>
                <input type="date" name="birthdate" class="input-flat w-full" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Tinggi (cm)</label>
                <input type="number" step="0.1" name="height_cm" class="input-flat w-full" required>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Berat (kg)</label>
                <input type="number" step="0.1" name="weight_kg" class="input-flat w-full" required>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Tingkat Aktivitas Harian</label>
            <select name="activity_level" class="input-flat w-full" required>
                <option value="sangat_jarang">Sangat Jarang Bergerak</option>
                <option value="jarang">Jarang Olahraga</option>
                <option value="sedang">Olahraga Sedang (1-3x/minggu)</option>
                <option value="aktif">Aktif (3-5x/minggu)</option>
                <option value="sangat_aktif">Sangat Aktif (Setiap Hari)</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Lokasi Latihan</label>
                <select name="training_location" class="input-flat w-full" required>
                    <option value="rumah">Di Rumah</option>
                    <option value="gym">Di Gym</option>
                    <option value="keduanya">Keduanya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase font-bold mb-2">Alat yang Dimiliki</label>
                <select name="equipment_access" class="input-flat w-full" required>
                    <option value="dumbell">Dumbbell</option>
                    <option value="resistance_band">Resistance Band</option>
                    <option value="keduanya">Keduanya</option>
                    <option value="tidak_ada">Belum Punya</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Level Pengalaman Latihan</label>
            <select name="experience_level" class="input-flat w-full" required>
                <option value="pemula">Pemula</option>
                <option value="menengah">Menengah</option>
                <option value="lanjutan">Lanjutan</option>
            </select>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Target Berat Badan (kg) — opsional</label>
            <input type="number" step="0.1" name="target_weight_kg" class="input-flat w-full">
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Riwayat Cedera / Kondisi Khusus — opsional</label>
            <textarea name="injury_notes" rows="2" class="input-flat w-full"></textarea>
        </div>

        <div>
            <label class="block text-xs uppercase font-bold mb-2">Tujuan / Catatan Tambahan — opsional</label>
            <textarea name="goal_notes" rows="2" class="input-flat w-full"></textarea>
        </div>

        <button type="submit" class="btn-primary w-full">Simpan & Lanjutkan</button>
    </form>
</div>
@endsection
