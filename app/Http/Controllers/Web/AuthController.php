<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // =========================================================
        // VALIDASI INPUT
        // =========================================================
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.max' => 'Username maksimal 50 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // =========================================================
        // CEK USER
        // =========================================================
        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        // =========================================================
        // CEK STATUS AKUN
        // =========================================================
        if (! $user->is_active) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
        }

        // =========================================================
        // LOGIN
        // =========================================================
        if (! Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ], $request->boolean('remember'))) {

            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        // =========================================================
        // REGENERATE SESSION
        // =========================================================
        $request->session()->regenerate();

        // =========================================================
        // REDIRECT DASHBOARD
        // =========================================================
        return redirect()
            ->intended(route('home'))
            ->with('success', 'Login berhasil.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('landing')
            ->with('success', 'Logout berhasil.');
    }
}
