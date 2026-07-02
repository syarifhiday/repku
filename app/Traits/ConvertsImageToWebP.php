<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ConvertsImageToWebP
{
    /**
     * Simpan gambar ke storage.
     * GIF → simpan langsung tanpa konversi.
     * PNG / JPG / JPEG → konversi ke WebP dulu, lalu simpan.
     *
     * @return string path relatif di disk 'public'
     */
    protected function storeImageAsWebP(UploadedFile $file, string $folder): string
    {
        // GIF: langsung simpan, jangan dikonversi (animasi bisa rusak)
        if ($file->getMimeType() === 'image/gif') {
            return $file->store($folder, 'public');
        }

        // Buat nama file & path tujuan
        $filename  = Str::uuid() . '.webp';
        $directory = storage_path('app/public/' . $folder);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $outputPath = $directory . '/' . $filename;

        // Load image dari mime type sumber
        $mime = $file->getMimeType();
        $src  = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($file->getPathname()),
            'image/png'  => $this->pngToTruecolor($file->getPathname()),
            'image/webp' => imagecreatefromwebp($file->getPathname()),
            default      => null,
        };

        if (!$src) {
            // Fallback: simpan apa adanya kalau mime tidak dikenal
            return $file->store($folder, 'public');
        }

        // Konversi ke WebP, quality 85 (balance ukuran vs kualitas)
        imagewebp($src, $outputPath, 85);
        imagedestroy($src);

        return $folder . '/' . $filename;
    }

    /**
     * PNG bisa punya alpha channel — perlu handle transparency
     * agar background tidak jadi hitam saat dikonversi ke WebP.
     */
    private function pngToTruecolor(string $path): \GdImage|false
    {
        $src = imagecreatefrompng($path);
        if (!$src) return false;

        $w   = imagesx($src);
        $h   = imagesy($src);
        $out = imagecreatetruecolor($w, $h);

        // Isi background putih (WebP support transparency tapi lebih aman)
        imagefill($out, 0, 0, imagecolorallocate($out, 255, 255, 255));
        imagecopy($out, $src, 0, 0, 0, 0, $w, $h);
        imagedestroy($src);

        return $out;
    }
}