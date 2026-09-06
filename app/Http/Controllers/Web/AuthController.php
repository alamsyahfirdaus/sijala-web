<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ElderlyCounselee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
        // $this->insertDummyKonseli1();
        // $this->insertDummyKonseli();
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
            'username.min'      => 'Username minimal 3 karakter.',
            'username.max'      => 'Username maksimal 50 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        // =========================================================
        // CEK USER BERDASARKAN USERNAME
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
        // CEK ROLE
        // HANYA ADMIN YANG BOLEH LOGIN KE WEB ADMIN
        // =========================================================
        if ($user->role !== 'admin') {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Anda tidak memiliki hak akses ke halaman administrator.');
        }

        // =========================================================
        // PROSES LOGIN
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
        // MENCEGAH SESSION FIXATION
        // =========================================================
        $request->session()->regenerate();

        // =========================================================
        // REDIRECT KE DASHBOARD
        // =========================================================
        return redirect()
            ->intended(route('home'))
            ->with('success', 'Selamat datang, ' . Auth::user()->name . '.');
    }

    public function logout(Request $request)
    {
        // =========================================================
        // LOGOUT USER
        // =========================================================
        Auth::logout();

        // =========================================================
        // HAPUS SEMUA SESSION
        // =========================================================
        $request->session()->invalidate();

        // =========================================================
        // GENERATE TOKEN BARU
        // =========================================================
        $request->session()->regenerateToken();

        // =========================================================
        // REDIRECT KE HALAMAN LOGIN
        // =========================================================
        return redirect()
            ->route('landing')
            ->with('success', 'Anda berhasil logout.');
    }

    public function insertDummyKonseli()
    {
        DB::beginTransaction();

        try {

            $dataKonseli = [
                ['name' => 'Irfafitri',    'username' => 'irfafitri',    'phone' => '081320426899', 'puskesmas_id' => 12],
                ['name' => 'Nani',         'username' => 'nani',         'phone' => '089654359089', 'puskesmas_id' => 7],
                ['name' => 'Wiwi Yulianti', 'username' => 'wiwi yulianti', 'phone' => '089658025406', 'puskesmas_id' => 10],
                ['name' => 'Susi',         'username' => 'susi',         'phone' => '082219572733', 'puskesmas_id' => 9],
                ['name' => 'Eni',          'username' => 'eni',          'phone' => '085722676984', 'puskesmas_id' => 9],
                ['name' => 'Julaeha',      'username' => 'julaeha',      'phone' => '089531504928', 'puskesmas_id' => 8],
                ['name' => 'Risa',         'username' => 'risa',         'phone' => '085721035729', 'puskesmas_id' => 8],
                ['name' => 'Ikah',         'username' => 'ikah',         'phone' => '088220309758', 'puskesmas_id' => 5],
                ['name' => 'Neni',         'username' => 'neni',         'phone' => '088226132038', 'puskesmas_id' => 5],
                ['name' => 'Imas',         'username' => 'imas',         'phone' => '083820221652', 'puskesmas_id' => 5],
                ['name' => 'Novie',        'username' => 'novie',        'phone' => '08981671673',  'puskesmas_id' => 1],
                ['name' => 'Tri',          'username' => 'tri',          'phone' => '081323281819', 'puskesmas_id' => 1],
                ['name' => 'Dela',         'username' => 'dela',         'phone' => '081809771152', 'puskesmas_id' => 4],
                ['name' => 'Entit',        'username' => 'entit',        'phone' => '082370316383', 'puskesmas_id' => 4],
                ['name' => 'Nia',          'username' => 'nia',          'phone' => '082246779056', 'puskesmas_id' => 3],
                ['name' => 'Neni',         'username' => 'neni',         'phone' => '0882000865896', 'puskesmas_id' => 2],
                ['name' => 'Sri',          'username' => 'sri',          'phone' => '085861736014', 'puskesmas_id' => 2],
                ['name' => 'Reni',         'username' => 'reni',         'phone' => '085171166698', 'puskesmas_id' => 6],
                ['name' => 'Lia',          'username' => 'lia',          'phone' => '083822348363', 'puskesmas_id' => 13],
                ['name' => 'Heni',         'username' => 'heni',         'phone' => '089697266812', 'puskesmas_id' => 13],
                ['name' => 'M. Rifqi',     'username' => 'm. rifqi',     'phone' => '08593480315',  'puskesmas_id' => 13],
                ['name' => 'Purwaningsih', 'username' => 'purwaningsih', 'phone' => '081214959660', 'puskesmas_id' => 13],
                ['name' => 'Dian',         'username' => 'dian',         'phone' => '082115626784', 'puskesmas_id' => 13],
                ['name' => 'Yani',         'username' => 'yani',         'phone' => '082317005836', 'puskesmas_id' => 13],
                ['name' => 'Imas',         'username' => 'imas',         'phone' => '083874425402', 'puskesmas_id' => 13],
                ['name' => 'Rani',         'username' => 'rani',         'phone' => '082315107744', 'puskesmas_id' => 13],
                ['name' => 'Salma',        'username' => 'salma',        'phone' => '082113512138', 'puskesmas_id' => 13],
            ];

            $jumlahBerhasil = 0;

            foreach ($dataKonseli as $data) {

                // =====================================================
                // 1. BUAT USERNAME UNIK
                // =====================================================

                $baseUsername = strtolower(
                    preg_replace('/[^a-zA-Z0-9]/', '', $data['username'])
                );

                $username = $baseUsername;
                $counter = 1;

                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }


                // =====================================================
                // 2. INSERT KE TABEL USERS
                // =====================================================

                $user = User::create([
                    'name'         => $data['name'],
                    'username'     => $username,
                    'phone'        => $data['phone'],
                    'password'     => Hash::make('123456'),
                    'role'         => 'konseli',
                    'puskesmas_id' => $data['puskesmas_id'],
                    'is_active'    => true,
                    'is_online'    => false,
                ]);


                // =====================================================
                // 3. INSERT KE TABEL ELDERLY_COUNSELEE
                // =====================================================

                ElderlyCounselee::create([
                    'counselee_id'         => $user->id,
                ]);

                $jumlahBerhasil++;
            }


            // =====================================================
            // 4. COMMIT
            // =====================================================

            DB::commit();

            echo json_encode([
                'status'  => true,
                'message' => 'Dummy konseli berhasil ditambahkan.',
                'jumlah'  => $jumlahBerhasil,
            ], 200);

        } catch (\Throwable $e) {

            // Jika terjadi error, batalkan seluruh proses
            DB::rollBack();

            echo json_encode([
                'status'  => false,
                'message' => 'Gagal menambahkan dummy konseli.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function insertDummyKonseli1()
    {
        DB::beginTransaction();

        try {

            $dataKonseli = [
                [
                    'name'         => 'Yuli',
                    'username'     => 'yuli',
                    'phone'        => '085322235477',
                    'puskesmas_id' => 7,
                ],
                [
                    'name'         => 'Rosi',
                    'username'     => 'rosi',
                    'phone'        => '081513213324',
                    'puskesmas_id' => 10,
                ],
                [
                    'name'         => 'Sumyati',
                    'username'     => 'sumyati',
                    'phone'        => '088971547662',
                    'puskesmas_id' => 3,
                ],
                [
                    'name'         => 'Cahyati',
                    'username'     => 'cahyati',
                    'phone'        => '08985106742',
                    'puskesmas_id' => 3,
                ],
            ];

            $jumlahBerhasil = 0;

            foreach ($dataKonseli as $data) {

                // =====================================================
                // 1. BUAT USERNAME UNIK
                // =====================================================

                $baseUsername = strtolower(
                    preg_replace('/[^a-zA-Z0-9]/', '', $data['username'])
                );

                $username = $baseUsername;
                $counter = 1;

                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }


                // =====================================================
                // 2. INSERT KE TABEL USERS
                // =====================================================

                $user = User::create([
                    'name'         => $data['name'],
                    'username'     => $username,
                    'phone'        => $data['phone'],
                    'password'     => Hash::make('123456'),
                    'role'         => 'konseli',
                    'puskesmas_id' => $data['puskesmas_id'],
                    'is_active'    => true,
                    'is_online'    => false,
                ]);


                // =====================================================
                // 3. INSERT KE TABEL ELDERLY_COUNSELEE
                // =====================================================

                ElderlyCounselee::create([
                    'counselee_id'         => $user->id,
                ]);

                $jumlahBerhasil++;
            }


            // =====================================================
            // 4. COMMIT
            // =====================================================

            DB::commit();

            echo json_encode([
                'status'  => true,
                'message' => 'Dummy konseli berhasil ditambahkan.',
                'jumlah'  => $jumlahBerhasil,
            ], 200);

        } catch (\Throwable $e) {

            // Jika terjadi error, batalkan seluruh proses
            DB::rollBack();

            echo json_encode([
                'status'  => false,
                'message' => 'Gagal menambahkan dummy konseli.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
