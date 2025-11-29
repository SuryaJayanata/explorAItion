<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($request->username === 'admin' && $request->password === 'admin123') {
            $admin = User::where('email', 'admin@eduspace.com')->first();
            
            if (!$admin) {
                $admin = User::create([
                    'nama' => 'Administrator',
                    'email' => 'admin@eduspace.com',
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                ]);
            }

            Auth::login($admin);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['username' => 'Invalid admin credentials.']);
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_teachers' => User::where('role', 'guru')->count(),
            'total_students' => User::where('role', 'siswa')->count(),
            'total_classes' => Kelas::count(),
            'total_materials' => Materi::count(),
            'total_assignments' => Tugas::count(),
        ];

        $recentClasses = Kelas::with('guru')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentClasses', 'recentUsers'));
    }

    public function users()
    {
        $users = User::withCount(['kelasDiajar', 'anggotaKelas'])
                    ->latest()
                    ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function classes()
    {
        $classes = Kelas::with(['guru', 'anggota'])
                    ->withCount(['anggota', 'materi', 'tugas'])
                    ->latest()
                    ->paginate(10);

        return view('admin.classes.index', compact('classes'));
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isGuru()) {
            $user->kelasDiajar()->delete();
        }

        $user->anggotaKelas()->delete();
        $user->pengumpulanTugas()->delete();
        $user->komentar()->delete();
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function deleteClass(Kelas $class)
    {
        $class->delete();
        return redirect()->route('admin.classes')->with('success', 'Class deleted successfully.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}