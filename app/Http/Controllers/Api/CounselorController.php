<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use Illuminate\Http\Request;

class CounselorController extends Controller
{

    /**
     * GET /api/counselors
     * POST /api/counselors
     *
     * Menampilkan daftar konselor
     * - Jika ada puskesmas_id → filter berdasarkan puskesmas
     * - Jika tidak ada → tampilkan semua konselor
     */
    public function index(Request $request)
    {
        // =========================
        // 1. Query awal + relasi
        // =========================
        $query = Counselor::with([
            'user',
            'puskesmas.village.district.regency'
        ]);

        // =========================
        // 2. Filter (optional)
        // =========================
        $puskesmasId = $request->input('puskesmas_id');

        if (!empty($puskesmasId)) {
            $query->where('puskesmas_id', $puskesmasId);
        }

        // =========================
        // 3. Ambil data + mapping
        // =========================
        $data = $query
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($item) {

                // Format alamat puskesmas
                $address = collect([
                    $item->puskesmas?->village?->name,
                    $item->puskesmas?->village?->district?->name,
                    $item->puskesmas?->village?->district?->regency?->name,
                ])->filter()->implode(', ');

                $puskesmasFull = collect([
                    'Puskesmas ' . $item->puskesmas->name,
                    $item->puskesmas?->village?->name,
                    $item->puskesmas?->village?->district?->name,
                ])->filter()->implode(' - ');

                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,

                    // ================= USER =================
                    'name' => $item->user?->name,
                    'username' => $item->user?->username,
                    'phone' => $item->user?->phone,
                    'photo' => $item->user->photo ? $item->user->photo : null,

                    // ================= KONSELOR =================
                    'specialization' => $item->specialization,
                    'registration_number' => $item->registration_number,
                    'education' => $item->education,
                    'description' => $item->description,

                    // ================= PUSKESMAS =================
                    'puskesmas_id' => $item->puskesmas_id,
                    'puskesmas_name' => 'Puskesmas ' . $item->puskesmas?->name,
                    'puskesmas_address' => $address ?: null,

                    // versi gabungan (optional untuk UI)
                    'puskesmas_full' => $puskesmasFull,
                ];
            });

        // =========================
        // 4. Response
        // =========================
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }


    /**
     * GET /api/counselors/{id}
     * Menampilkan detail konselor
     */

    public function show($id)
    {
        $data = Counselor::with('user', 'puskesmas')->find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data konselor tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $data->id,
                'user_id' => $data->user_id,

                'name' => $data->user->name ?? null,
                'username' => $data->user->username ?? null,
                'phone' => $data->user->phone ?? null,

                'registration_number' => $data->registration_number,
                'specialization' => $data->specialization,
                'education' => $data->education,
                'description' => $data->description,
                'puskesmas_id' => $data->puskesmas_id,
                'puskesmas' => $data->puskesmas->name ?? null,
            ]
        ]);
    }

    public function updateConselor(Request $request, $id)
    {
        // ambil data konselor (auto 404 jika tidak ditemukan)
        $counselor = Counselor::findOrFail($id);

        // validasi input (update parsial)
        $validatedData = $request->validate([
            'registration_number' => 'sometimes|nullable|string|max:255',
            'specialization'      => 'sometimes|nullable|string|max:255',
            'education'           => 'sometimes|nullable|string|max:255',
            'description'         => 'sometimes|nullable|string',
            'puskesmas_id'        => 'sometimes|exists:puskesmas,id',
        ]);

        // jika tidak ada data yang dikirim
        if (empty($validatedData)) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diperbarui'
            ], 400);
        }

        // update data
        $counselor->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Data konselor berhasil diperbarui',
            'data' => $counselor
        ]);
    }
}
