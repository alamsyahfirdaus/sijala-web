<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'gender' => 'required|in:L,P',
                'phone' => 'required|numeric|unique:users,phone',
                'role' => 'required|in:konselor,konseli',

                // opsional tapi harus valid jika diisi
                'village_id'   => 'nullable|exists:villages,id',
                'puskesmas_id' => 'nullable|exists:puskesmas,id'
            ],
            [
                'name.required' => 'Nama wajib diisi.',
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'gender.in' => 'Jenis kelamin harus L atau P.',
                'phone.required' => 'Nomor handphone wajib diisi.',
                'phone.numeric' => 'Nomor handphone harus berupa angka.',
                'phone.unique' => 'Nomor handphone sudah terdaftar.',
                'role.required' => 'Peran pengguna wajib dipilih.',
                'role.in' => 'Peran harus konselor atau konseli.',

                'village_id.exists' => 'Kelurahan tidak ditemukan.',
                'puskesmas_id.exists' => 'Puskesmas tidak ditemukan.'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            // generate username
            $baseUsername = strtolower(str_replace(' ', '', $request->name));
            $username = $baseUsername;
            $counter = 1;

            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $password = '123456';

            // simpan user + village_id
            $user = User::create([
                'name' => $request->name,
                'username' => $username,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => Hash::make($password),
                'role' => $request->role,
                'village_id' => $request->village_id // opsional
            ]);

            // jika role konselor → insert ke counselors
            if ($request->role === 'konselor') {

                Counselor::create([
                    'user_id' => $user->id,
                    'puskesmas_id' => $request->puskesmas_id // opsional
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'password' => $password,
                    'role' => $user->role
                ]
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Registrasi gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => 'Username / email / nomor HP wajib diisi.',
                'password.required' => 'Password wajib diisi.'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->orWhere('phone', $request->username)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Akun tidak ditemukan.'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah.'
            ], 401);
        }

        $token = Str::random(60);

        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'phone' => $user->phone,
                'role' => $user->role,
                'token' => $token
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO LOGIN
    |--------------------------------------------------------------------------
    */

    public function autoLogin(Request $request)
    {
        $user = $request->attributes->get('user');

        return response()->json([
            'status' => true,
            'message' => 'Auto login berhasil',
            'data' => $user
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile(Request $request)
    {
        // Ambil user yang sedang login beserta relasi puskesmas
        $user = User::with([
            'puskesmas.village.district.regency.province'
        ])->find($request->attributes->get('user')->id);

        // Format tanggal lahir
        $formatDate = fn($date) =>
            $date ? \Carbon\Carbon::parse($date)->format('d-m-Y') : null;

        // Format alamat puskesmas
        $buildPuskesmasAddress = fn($p) =>
            $p?->village_id
                ? collect([
                    $p->name,
                    $p->village?->name,
                    $p->village?->district?->name,
                    $p->village?->district?->regency?->name,
                ])->filter()->implode(', ')
                : null;

        // Data user
        $data = [
            'user_id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'photo' => $user->photo ?: 'user.png',
            'birth_place' => $user->birth_place,
            'birth_date' => $formatDate($user->birth_date),
            'occupation' => $user->occupation,
            'education' => $user->education,
            'address' => $user->address,
            'is_active' => $user->is_active,

            // Data puskesmas
            'puskesmas_id' => $user->puskesmas_id,
            'puskesmas_name' => $user->puskesmas?->name,
            'puskesmas_code' => $user->puskesmas?->code,
            'puskesmas_phone' => $user->puskesmas?->phone,
            'puskesmas_address' => $buildPuskesmasAddress($user->puskesmas),
        ];

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $user = $request->attributes->get('user');

        $user->remember_token = null;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CHANGE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function changePassword(Request $request)
    {
        $user = $request->attributes->get('user');

        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed'
            ],
            [
                'current_password.required' => 'Password lama wajib diisi.',
                'new_password.required' => 'Password baru wajib diisi.',
                'new_password.min' => 'Password baru minimal 6 karakter.',
                'new_password.confirmed' => 'Konfirmasi password tidak sama.'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password lama tidak sesuai.'
            ], 400);
        }

        // Password baru tidak boleh sama dengan password lama
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama.'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFILE
    |--------------------------------------------------------------------------
    */

    public function updateProfile(Request $request)
    {
        // =========================
        // 1. Ambil user dari middleware
        // =========================
        $user = $request->attributes->get('user');

        if ($request->has('birth_date')) {
            try {
                $date = $request->birth_date;

                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
                    $date = Carbon::createFromFormat('d-m-Y', $date);
                } else {
                    $date = Carbon::parse($date);
                }

                $request->merge([
                    'birth_date' => $date->format('Y-m-d')
                ]);
            } catch (\Exception $e) {
                // biarkan validator handle error format tanggal
            }
        }

        // =========================
        // 2. Validasi input
        // =========================
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'sometimes|string|max:255',

                'email' => [
                    'sometimes',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],

                'phone' => [
                    'sometimes',
                    'string',
                    'min:10',
                    'max:15',
                    Rule::unique('users', 'phone')->ignore($user->id),
                ],

                'gender' => 'sometimes|in:L,P',
                'birth_date' => 'sometimes|date_format:Y-m-d|before:today',
                'village_id' => 'sometimes|exists:villages,id',
                'address' => 'sometimes|string|max:500',
                'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data yang sudah valid
        $data = $validator->validated();

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
        // 4. Update data user
        // =========================
        $user->update($data);

        // =========================
        // 5. Response
        // =========================
        return response()->json([
            'status'  => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $user
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REFRESH TOKEN
    |--------------------------------------------------------------------------
    */

    public function refreshToken(Request $request)
    {
        $user = $request->attributes->get('user');

        $token = Str::random(60);

        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Token berhasil diperbarui',
            'token' => $token
        ]);
    }
}
