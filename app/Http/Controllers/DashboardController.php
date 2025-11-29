<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isGuru()) {
            // Ambil kelas yang diajar oleh guru ini
            $kelas = $user->kelasDiajar()->withCount('anggota')->get();
        } else {
            // Ambil kelas yang diikuti oleh siswa ini
            $kelas = $user->anggotaKelas()->with('kelas.guru')->get()->pluck('kelas');
        }
        
        return view('dashboard', compact('kelas', 'user'));
    }
}