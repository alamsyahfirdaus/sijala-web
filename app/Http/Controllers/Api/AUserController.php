<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AUserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'    => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required'    => 'Email wajib diisi.',
                'email.email'       => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = AUser::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $token = Str::random(60);

        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'phone'     => $user->phone,
                'token'     => $token,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'required|string|max:100',
                'email'    => 'required|email|unique:a_users,email',
                'password' => 'required|string|min:6|confirmed',
                'phone'    => 'nullable|string|max:20',
            ],
            [
                'name.required'      => 'Nama wajib diisi.',
                'name.max'           => 'Nama maksimal 100 karakter.',
                'email.required'     => 'Email wajib diisi.',
                'email.email'        => 'Format email tidak valid.',
                'email.unique'       => 'Email sudah terdaftar.',
                'password.required'  => 'Password wajib diisi.',
                'password.min'       => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
                'phone.max'          => 'Nomor telepon maksimal 20 karakter.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = AUser::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => 'user',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil.',
            'data'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 401);
        }

        $user->update([
            'remember_token' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil.',
            'data'    => $user,
        ]);
    }


    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // =========================
        // 1. Validasi
        // =========================
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // =========================
        // 2. Data yang akan diupdate
        // =========================
        $data = [
            'name'  => $request->name,
            'phone' => $request->phone,
        ];

        // =========================
        // 3. Handle Upload Foto
        // =========================
        if ($request->hasFile('photo')) {

            // Hapus foto lama jika ada
            if (!empty($user->photo)) {
                $oldPath = public_path('images/' . $user->photo);

                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Pastikan folder tersedia
            $destinationPath = public_path('images');

            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Simpan foto baru
            $fileName = Str::random(20) . '.' . $request->file('photo')->extension();

            $request->file('photo')->move($destinationPath, $fileName);

            // Simpan nama file ke database
            $data['photo'] = $fileName;
        }

        // =========================
        // 4. Update Data
        // =========================
        $user->update($data);

        // =========================
        // 5. Response
        // =========================
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $user->fresh(),
        ]);
    }

    public function users()
    {
        return response()->json([
            'success' => true,
            'data' => AUser::where('role', 'user')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get(),
        ]);
    }
}