<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Menampilkan daftar kelurahan dengan alamat lengkap
     * Format: Kelurahan - Kecamatan - Kabupaten - Provinsi
     */

    // public function villages()
    // {
    //     $villages = Village::with([
    //         'district.regency.province'
    //     ])
    //         ->select('id', 'name', 'district_id')
    //         ->get()
    //         ->map(function ($village) {

    //             $district = $village->district->name ?? '';
    //             $regency  = $village->district->regency->name ?? '';
    //             $province = $village->district->regency->province->name ?? '';

    //             return [
    //                 'id' => $village->id,
    //                 'village' => $village->name,
    //                 'district' => $district,
    //                 'regency' => $regency,
    //                 'province' => $province,
    //                 'full_address' => "{$village->name} - {$district} - {$regency} - {$province}"
    //             ];
    //         });

    //     return response()->json([
    //         'status' => true,
    //         'data' => $villages
    //     ]);
    // }

    public function villages()
    {
        $villages = Village::select(
            'villages.id',
            'villages.name as village',
            'districts.name as district',
            'regencies.name as regency',
            'provinces.name as province'
        )
            ->leftJoin('districts', 'villages.district_id', '=', 'districts.id')
            ->leftJoin('regencies', 'districts.regency_id', '=', 'regencies.id')
            ->leftJoin('provinces', 'regencies.province_id', '=', 'provinces.id')
            ->orderBy('villages.name', 'asc')
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    // 'village' => "{$v->village} - {$v->district} - {$v->regency} - {$v->province}"
                    'village' => "{$v->village} - {$v->district} - {$v->regency}"
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $villages
        ]);
    }


    /**
     * Menampilkan daftar semua provinsi
     */
    public function provinces()
    {
        $provinces = Province::all();

        return response()->json([
            'status' => true,
            'data' => $provinces
        ]);
    }


    /**
     * Menampilkan daftar kabupaten/kota
     * berdasarkan ID provinsi
     */
    public function regencies($province_id)
    {
        $regencies = Regency::where('province_id', $province_id)->get();

        return response()->json([
            'status' => true,
            'data' => $regencies
        ]);
    }


    /**
     * Menampilkan daftar kecamatan
     * berdasarkan ID kabupaten/kota
     */
    public function districts($regency_id)
    {
        $districts = District::where('regency_id', $regency_id)->get();

        return response()->json([
            'status' => true,
            'data' => $districts
        ]);
    }


    /**
     * Menampilkan daftar kelurahan/desa
     * berdasarkan ID kecamatan
     */
    public function villagesByDistrict($district_id)
    {
        $villages = Village::where('district_id', $district_id)->get();

        return response()->json([
            'status' => true,
            'data' => $villages
        ]);
    }

    /**
     * Pencarian kelurahan + kecamatan + kabupaten + provinsi
     * Cocok untuk autocomplete alamat
     */
    public function searchVillage(Request $request)
    {
        $keyword = $request->q ?? '';

        $villages = Village::select(
            'villages.id',
            'villages.name as village',
            'districts.name as district',
            'regencies.name as regency',
            'provinces.name as province'
        )
            ->join('districts', 'villages.district_id', '=', 'districts.id')
            ->join('regencies', 'districts.regency_id', '=', 'regencies.id')
            ->join('provinces', 'regencies.province_id', '=', 'provinces.id')
            ->where(function ($query) use ($keyword) {
                $query->where('villages.name', 'LIKE', "%{$keyword}%")
                    ->orWhere('districts.name', 'LIKE', "%{$keyword}%")
                    ->orWhere('regencies.name', 'LIKE', "%{$keyword}%")
                    ->orWhere('provinces.name', 'LIKE', "%{$keyword}%");
            })
            ->limit(20)
            ->orderBy('villages.name', 'asc')
            ->get()

            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'village' => "{$v->village} - {$v->district} - {$v->regency}"
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $villages
        ]);
    }
}
