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
    /**
     * Login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|email',

            'password' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ],422);

        }

        $user = AUser::where('email',$request->email)->first();

        if (!$user || !Hash::check($request->password,$user->password)) {

            return response()->json([
                'success'=>false,
                'message'=>'Email atau password salah.'
            ],401);

        }

        $token = $user->createToken('flutter')->plainTextToken;

        return response()->json([

            'success'=>true,

            'message'=>'Login berhasil.',

            'token'=>$token,

            'data'=>$user

        ]);
    }

    /**
     * Register
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name'=>'required',

            'email'=>'required|email|unique:a_users,email',

            'password'=>'required|confirmed|min:6',

            'phone'=>'nullable'

        ]);

        if($validator->fails()){

            return response()->json([

                'success'=>false,

                'errors'=>$validator->errors()

            ],422);

        }

        $user = AUser::create([

            'name'=>$request->name,

            'email'=>$request->email,

            'password'=>Hash::make($request->password),

            'phone'=>$request->phone,

            'role'=>'user'

        ]);

        $token = $user->createToken('flutter')->plainTextToken;

        return response()->json([

            'success'=>true,

            'message'=>'Registrasi berhasil.',

            'token'=>$token,

            'data'=>$user

        ],201);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([

            'success'=>true,

            'message'=>'Logout berhasil.'

        ]);
    }

    /**
     * Profile
     */
    public function profile(Request $request)
    {
        return response()->json([

            'success'=>true,

            'data'=>$request->user()

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