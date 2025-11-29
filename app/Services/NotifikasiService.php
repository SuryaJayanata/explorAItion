<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\Kelas;
use App\Models\User;

class NotifikasiService
{
    public static function createNotifikasi($userId, $type, $title, $message, $link = null, $data = null)
    {
        return Notifikasi::create([
            'id_user' => $userId,
            'tipe' => $type,
            'judul' => $title,
            'pesan' => $message,
            'tautan' => $link,
            'data' => $data,
            'dibaca' => false,
        ]);
    }

    // Notifikasi untuk siswa: materi baru
    public static function notifyMateriBaru(Kelas $kelas, $materi)
    {
        $siswa = $kelas->anggota()->with('user')->get();
        
        foreach ($siswa as $anggota) {
            self::createNotifikasi(
                $anggota->id_user,
                'materi_baru',
                'Materi Baru',
                "Materi baru '{$materi->judul}' telah ditambahkan di kelas {$kelas->nama_kelas}",
                route('kelas.materi.show', [$kelas->id_kelas, $materi->id_materi]),
                [
                    'kelas_id' => $kelas->id_kelas,
                    'materi_id' => $materi->id_materi,
                    'guru_nama' => $kelas->guru->nama
                ]
            );
        }
    }

    // Notifikasi untuk siswa: tugas baru
    public static function notifyTugasBaru(Kelas $kelas, $tugas)
    {
        $siswa = $kelas->anggota()->with('user')->get();
        
        foreach ($siswa as $anggota) {
            self::createNotifikasi(
                $anggota->id_user,
                'tugas_baru',
                'Tugas Baru',
                "Tugas baru '{$tugas->judul}' telah ditambahkan di kelas {$kelas->nama_kelas}. Deadline: " . $tugas->deadline->format('d M Y H:i'),
                route('kelas.tugas.show', [$kelas->id_kelas, $tugas->id_tugas]),
                [
                    'kelas_id' => $kelas->id_kelas,
                    'tugas_id' => $tugas->id_tugas,
                    'deadline' => $tugas->deadline,
                    'guru_nama' => $kelas->guru->nama
                ]
            );
        }
    }

    // Notifikasi untuk siswa: nilai diberikan
    public static function notifyNilaiDiberikan($pengumpulan)
    {
        self::createNotifikasi(
            $pengumpulan->id_user,
            'nilai_diberikan',
            'Nilai Tugas',
            "Tugas '{$pengumpulan->tugas->judul}' telah dinilai: {$pengumpulan->nilai->nilai}/100",
            route('kelas.tugas.show', [$pengumpulan->tugas->kelas->id_kelas, $pengumpulan->tugas->id_tugas]),
            [
                'kelas_id' => $pengumpulan->tugas->kelas->id_kelas,
                'tugas_id' => $pengumpulan->tugas->id_tugas,
                'nilai' => $pengumpulan->nilai->nilai,
                'komentar' => $pengumpulan->nilai->komentar_guru
            ]
        );
    }

    // Notifikasi untuk guru: siswa bergabung
    public static function notifySiswaBergabung(Kelas $kelas, User $siswa)
    {
        self::createNotifikasi(
            $kelas->id_guru,
            'siswa_bergabung',
            'Siswa Bergabung',
            "{$siswa->nama} telah bergabung dengan kelas {$kelas->nama_kelas}",
            route('kelas.show', $kelas->id_kelas),
            [
                'kelas_id' => $kelas->id_kelas,
                'siswa_id' => $siswa->id_user,
                'siswa_nama' => $siswa->nama
            ]
        );
    }

    // Notifikasi untuk guru: tugas dikumpulkan
    public static function notifyTugasDikumpulkan($pengumpulan)
    {
        self::createNotifikasi(
            $pengumpulan->tugas->kelas->id_guru,
            'tugas_dikumpulkan',
            'Tugas Dikumpulkan',
            "{$pengumpulan->user->nama} telah mengumpulkan tugas '{$pengumpulan->tugas->judul}'",
            route('kelas.tugas.show', [$pengumpulan->tugas->kelas->id_kelas, $pengumpulan->tugas->id_tugas]),
            [
                'kelas_id' => $pengumpulan->tugas->kelas->id_kelas,
                'tugas_id' => $pengumpulan->tugas->id_tugas,
                'siswa_id' => $pengumpulan->id_user,
                'siswa_nama' => $pengumpulan->user->nama
            ]
        );
    }

    // Notifikasi: komentar baru (untuk guru dan pemilik post)
    public static function notifyKomentarBaru($komentar, $target, $tipe)
    {
        $targetModel = $tipe === 'materi' ? $target->kelas : $target->kelas;
        $guruId = $targetModel->id_guru;
        
        // Notifikasi untuk guru (kecuali jika guru yang komentar)
        if ($guruId !== $komentar->id_user) {
            self::createNotifikasi(
                $guruId,
                'komentar_baru',
                'Komentar Baru',
                "{$komentar->user->nama} memberi komentar pada {$tipe} '{$target->judul}'",
                $tipe === 'materi' 
                    ? route('kelas.materi.show', [$targetModel->id_kelas, $target->id_materi])
                    : route('kelas.tugas.show', [$targetModel->id_kelas, $target->id_tugas]),
                [
                    'tipe' => $tipe,
                    'target_id' => $tipe === 'materi' ? $target->id_materi : $target->id_tugas,
                    'komentar_id' => $komentar->id_komentar,
                    'user_id' => $komentar->id_user,
                    'user_nama' => $komentar->user->nama
                ]
            );
        }

        // Notifikasi untuk pemilik komentar parent (jika ada parent_id)
        if ($komentar->parent_id && $komentar->parent->id_user !== $komentar->id_user && $komentar->parent->id_user !== $guruId) {
            self::createNotifikasi(
                $komentar->parent->id_user,
                'komentar_baru',
                'Balasan Komentar',
                "{$komentar->user->nama} membalas komentar Anda",
                $tipe === 'materi' 
                    ? route('kelas.materi.show', [$targetModel->id_kelas, $target->id_materi]) . '#comment-' . $komentar->id_komentar
                    : route('kelas.tugas.show', [$targetModel->id_kelas, $target->id_tugas]) . '#comment-' . $komentar->id_komentar,
                [
                    'tipe' => $tipe,
                    'target_id' => $tipe === 'materi' ? $target->id_materi : $target->id_tugas,
                    'komentar_id' => $komentar->id_komentar,
                    'parent_id' => $komentar->parent_id,
                    'user_id' => $komentar->id_user,
                    'user_nama' => $komentar->user->nama
                ]
            );
        }
    }
}