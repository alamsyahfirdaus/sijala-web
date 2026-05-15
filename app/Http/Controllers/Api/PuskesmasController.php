<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Puskesmas;
use Illuminate\Http\Request;

class PuskesmasController extends Controller
{

    /**
     * Daftar puskesmas dengan alamat lengkap
     */
    public function index()
    {
        $data = Puskesmas::select(
            'puskesmas.id',
            'puskesmas.name as puskesmas',
            'villages.name as village',
            'districts.name as district',
            'regencies.name as regency',
            // 'provinces.name as province' --- IGNORE ---
        )
            ->join('villages', 'puskesmas.village_id', '=', 'villages.id')
            ->join('districts', 'villages.district_id', '=', 'districts.id')
            ->join('regencies', 'districts.regency_id', '=', 'regencies.id')
            // ->join('provinces','regencies.province_id','=','provinces.id')
            ->orderBy('puskesmas.name', 'asc')
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'puskesmas' => "{$item->puskesmas} ({$item->village} - {$item->district} - {$item->regency})",
                    'name' => 'Puskesmas ' . $item->puskesmas,
                    // 'full_address' =>
                    //     "{$item->puskesmas} - {$item->village} - {$item->district} - {$item->regency} - {$item->province}"
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * GET /api/puskesmas/search?q=...
     * POST /api/puskesmas/search
     * Pencarian puskesmas + alamat lengkap (autocomplete)
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $query = Puskesmas::select(
            'puskesmas.id',
            'puskesmas.name as puskesmas',
            'villages.name as village',
            'districts.name as district',
            'regencies.name as regency',
            'provinces.name as province'
        )
            ->join('villages', 'puskesmas.village_id', '=', 'villages.id')
            ->join('districts', 'villages.district_id', '=', 'districts.id')
            ->join('regencies', 'districts.regency_id', '=', 'regencies.id')
            ->join('provinces', 'regencies.province_id', '=', 'provinces.id');

        // jika ada keyword → lakukan pencarian
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('puskesmas.name', 'LIKE', "%$keyword%")
                    ->orWhere('villages.name', 'LIKE', "%$keyword%")
                    ->orWhere('districts.name', 'LIKE', "%$keyword%")
                    ->orWhere('regencies.name', 'LIKE', "%$keyword%");
            });
        }

        $data = $query
            ->orderBy('puskesmas.name', 'asc')
            ->limit(20) // penting untuk performa
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'puskesmas' => "Puskesmas {$item->puskesmas} - {$item->village} - {$item->district}",
                    // 'puskesmas' => $item->puskesmas,
                    // 'full_address' =>
                    // "{$item->puskesmas} - {$item->village} - {$item->district} - {$item->regency} - {$item->province}"
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
