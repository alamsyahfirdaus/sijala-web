<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ElderlyCounselee;
use App\Models\CounselingSession;
use App\Models\FallRiskScreening;
use App\Models\EmpowermentAssessment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ElderlyCounseleeController extends Controller
{
    /**
     * Menampilkan daftar data lansia.
     */
    public function index(Request $request)
    {
        $user = $request->attributes->get('user');

        $query = ElderlyCounselee::with([
            'counselee:id,name,gender,birth_date,puskesmas_id,occupation,education',
            'counselee.puskesmas:id,code,name,address,phone',
        ])->orderBy('elderly_name', 'asc');

        // Jika bukan admin, hanya tampilkan data miliknya
        if ($user->role !== 'admin') {
            $query->where('counselee_id', $user->id);
        }

        $data = $query->get()->map(function ($item) {
            $counselee = $item->counselee;

            return [
                // Data lansia
                'id' => $item->id,
                'counselee_id' => $item->counselee_id,
                'care_duration_months' => $item->care_duration_months,
                'elderly_name' => $item->elderly_name,
                'elderly_gender' => $item->elderly_gender,
                'elderly_age' => $item->elderly_age,
                'health_problems' => $item->health_problems,
                'has_fallen' => (bool) $item->has_fallen,

                // Data konseli
                'counselee_name' => $counselee?->name,
                'gender' => $counselee?->gender,
                'birth_date' => $counselee?->birth_date,
                'age' => $counselee?->birth_date
                    ? Carbon::parse($counselee->birth_date)->age
                    : null,
                'occupation' => $counselee?->occupation,
                'education' => $counselee?->education,

                // Data puskesmas
                'puskesmas_id' => $counselee?->puskesmas_id,
                'puskesmas_name' => 'Puskesmas ' . $counselee?->puskesmas?->name,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data lansia berhasil diambil.',
            'data' => $data,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data konseli dan lansia.
     */

    public function store(Request $request)
    {
        $user = $request->attributes->get('user');

        // ================= VALIDASI =================
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'nullable|exists:elderly_counselee,id',

                // Data konseli
                'name' => 'required|string|max:255',
                'gender' => 'required|in:L,P',
                'birth_date' => 'required|date|before_or_equal:today',
                'occupation' => 'required|string|max:255',
                'education' => 'required|string|max:255',
                'puskesmas_id' => 'required|exists:puskesmas,id',

                // Data lansia
                'counselee_id' => 'nullable|exists:users,id',
                'care_duration_months' => 'required|integer|min:0',
                'elderly_name' => 'required|string|max:255',
                'elderly_gender' => 'required|in:L,P',
                'elderly_age' => 'required|integer|min:0|max:150',
                'health_problems' => 'required|string',
                'has_fallen' => 'required|boolean',
            ],
            [
                'name.required' => 'Nama konseli wajib diisi.',
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'birth_date.required' => 'Tanggal lahir wajib diisi.',
                'birth_date.date' => 'Format tanggal lahir tidak valid.',
                'birth_date.before_or_equal' =>
                    'Tanggal lahir tidak boleh melebihi tanggal hari ini.',
                'occupation.required' => 'Pekerjaan wajib diisi.',
                'education.required' => 'Pendidikan wajib diisi.',
                'puskesmas_id.required' => 'Puskesmas wajib dipilih.',
                'puskesmas_id.exists' => 'Puskesmas yang dipilih tidak valid.',

                'care_duration_months.required' => 'Lama merawat wajib diisi.',
                'care_duration_months.integer' =>
                    'Lama merawat harus berupa angka.',
                'care_duration_months.min' =>
                    'Lama merawat minimal 0 bulan.',

                'elderly_name.required' => 'Nama lansia wajib diisi.',
                'elderly_name.max' =>
                    'Nama lansia maksimal 255 karakter.',

                'elderly_gender.required' =>
                    'Jenis kelamin lansia wajib dipilih.',
                'elderly_gender.in' =>
                    'Jenis kelamin lansia tidak valid.',

                'elderly_age.required' => 'Usia lansia wajib diisi.',
                'elderly_age.integer' =>
                    'Usia lansia harus berupa angka.',
                'elderly_age.min' =>
                    'Usia lansia minimal 0 tahun.',
                'elderly_age.max' =>
                    'Usia lansia maksimal 150 tahun.',

                'health_problems.required' =>
                    'Masalah kesehatan wajib diisi.',

                'has_fallen.required' =>
                    'Riwayat jatuh wajib diisi.',
                'has_fallen.boolean' =>
                    'Riwayat jatuh harus bernilai true atau false.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // ================= TRANSACTION =================
        DB::beginTransaction();

        try {
            // ================= CREATE / UPDATE =================
            if ($request->filled('id')) {
                $data = ElderlyCounselee::find($request->id);

                if (!$data) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data lansia tidak ditemukan.',
                    ], 404);
                }

                // User non-admin hanya boleh mengubah datanya sendiri
                if (
                    $user->role != 'admin' &&
                    $data->counselee_id != $user->id
                ) {
                    return response()->json([
                        'status' => false,
                        'message' =>
                            'Anda tidak memiliki akses untuk mengubah data ini.',
                    ], 403);
                }

                $message = 'Data konseli dan lansia berhasil diperbarui.';
            } else {
                $data = new ElderlyCounselee();
                $message = 'Data konseli dan lansia berhasil ditambahkan.';
            }

            // ================= TENTUKAN COUNSELEE ID =================
            $data->counselee_id =
                ($user->role == 'admin' && $request->filled('counselee_id'))
                    ? $request->counselee_id
                    : $user->id;

            // ================= UPDATE DATA KONSELI =================
            $counselee = User::find($data->counselee_id);

            if (!$counselee) {
                throw new \Exception('Data konseli tidak ditemukan.');
            }

            $counselee->name = $request->name;
            $counselee->gender = $request->gender;
            $counselee->birth_date = $request->birth_date;
            $counselee->occupation = $request->occupation;
            $counselee->education = $request->education;
            $counselee->puskesmas_id = $request->puskesmas_id;
            $counselee->save();

            // ================= UPDATE DATA LANSIA =================
            $data->care_duration_months = $request->care_duration_months;
            $data->elderly_name = $request->elderly_name;
            $data->elderly_gender = $request->elderly_gender;
            $data->elderly_age = $request->elderly_age;
            $data->health_problems = $request->health_problems;
            $data->has_fallen = $request->has_fallen;
            $data->save();

            // ================= BUAT / AMBIL SESI KONSELING =================
            $counselingSession = $this->addCounselingSession($counselee->puskesmas_id, $data->id);

            // ================= LOAD RELASI =================
            $data->load([
                'counselee:id,name,gender,birth_date,occupation,education,puskesmas_id',
                'counselee.puskesmas:id,code,name,address,phone',
            ]);

            // ================= COMMIT =================
            DB::commit();

            // ================= RESPONSE =================
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => array_merge(
                    $data->toArray(),
                    [
                        'counseling_session_id' => $counselingSession?->id,
                    ]
                ),
            ]);
        } catch (\Throwable $e) {
            // ================= ROLLBACK =================
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function addCounselingSession($puskesmasId, $elderlyCounseleeId)
    {
        // Khusus ujicoba
        if ($elderlyCounseleeId >= 56 && $elderlyCounseleeId <= 82) {

            $counselorId = 3;

        } elseif ($elderlyCounseleeId >= 83 && $elderlyCounseleeId <= 86) {

            $counselorId = 125;

        } else {

            // Cari konselor berdasarkan puskesmas
            $counselor = User::where([
                'role'         => 'konselor',
                'puskesmas_id' => $puskesmasId,
            ])->first();

            // Jika konselor tidak ditemukan
            if (!$counselor) {
                return null;
            }

            $counselorId = $counselor->id;
        }

        // Selalu buat sesi baru
        return CounselingSession::create([
            'elderly_counselee_id' => $elderlyCounseleeId,
            'counselor_id'         => $counselorId,
            'status'               => 'ongoing',
        ]);
    }

    private function addCounselingSession1($puskesmasId, $elderlyCounseleeId) 
    {
        // Cari konselor berdasarkan puskesmas
        $counselor = User::where([
            'role' => 'konselor',
            'puskesmas_id' => $puskesmasId,
        ])->first();

        // Jika konselor tidak ditemukan
        if (!$counselor) {
            return null;
        }

        // SELALU BUAT SESI BARU
        return CounselingSession::create([
            'elderly_counselee_id' => $elderlyCounseleeId,
            'counselor_id'         => $counselor->id,
            'status'               => 'ongoing',
        ]);
    }
}