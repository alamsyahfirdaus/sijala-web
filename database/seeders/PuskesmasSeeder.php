<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuskesmasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | KOTA CIMAHI
        |--------------------------------------------------------------------------
        | service_type:
        | 1 = Rawat Inap
        | 2 = Non Rawat Inap
        |--------------------------------------------------------------------------
        */

        $data = [

            // =====================================================
            // KECAMATAN CIMAHI SELATAN
            // =====================================================
            [
                'code' => 'P3277010201',
                'name' => 'Cimahi Selatan',
                'village' => 'Utama',
                'address' => 'Jl. Baros No. 16 Kel. Utama, Kec. Cimahi Selatan',
                'phone' => '0226629300',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277010202',
                'name' => 'Melong Asih',
                'village' => 'Melong',
                'address' => 'Jl. Melong Blok I No. 1 Kel. Melong, Kec. Cimahi Selatan',
                'phone' => '0226023833',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277010203',
                'name' => 'Cibeureum',
                'village' => 'Cibeureum',
                'address' => 'Jl. Raya Cibeureum No. 125 Kel. Cibeureum, Kec. Cimahi Selatan',
                'phone' => '0226075623',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277010204',
                'name' => 'Cibeber',
                'village' => 'Cibeber',
                'address' => 'Jl. Puri Fajar No. 1 Kel. Cibeber, Kec. Cimahi Selatan',
                'phone' => '0226628983',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277010205',
                'name' => 'Leuwigajah',
                'village' => 'Leuwigajah',
                'address' => 'Jl. Kihapit Barat RT 08 RW 09 Kel. Leuwigajah, Kec. Cimahi Selatan',
                'phone' => '0226677649',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277010206',
                'name' => 'Melong Tengah',
                'village' => 'Melong',
                'address' => 'Jl. Melong Tengah RT 02 RW 04 Kel. Melong, Kec. Cimahi Selatan',
                'phone' => '0226004991',
                'service_type' => 2,
            ],

            // =====================================================
            // KECAMATAN CIMAHI TENGAH
            // =====================================================
            [
                'code' => 'P3277020201',
                'name' => 'Cimahi Tengah',
                'village' => 'Cimahi',
                'address' => 'Jl. Djulaeha Karmita No. 5 Kel. Cimahi, Kec. Cimahi Tengah',
                'phone' => '0226630213',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277020202',
                'name' => 'Cigugur Tengah',
                'village' => 'Cigugur Tengah',
                'address' => 'Jl. Abdul Halim No. 199 Kel. Cigugur Tengah, Kec. Cimahi Tengah',
                'phone' => '0226632343',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277020203',
                'name' => 'Padasuka',
                'village' => 'Padasuka',
                'address' => 'Jl. Kebon Manggu Kel. Padasuka, Kec. Cimahi Tengah',
                'phone' => '0226621701',
                'service_type' => 2,
            ],

            // =====================================================
            // KECAMATAN CIMAHI UTARA
            // =====================================================
            [
                'code' => 'P3277030201',
                'name' => 'Cimahi Utara',
                'village' => 'Cibabat',
                'address' => 'Jl. Serut No. 16 Kel. Cibabat, Kec. Cimahi Utara',
                'phone' => '0226631547',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277030202',
                'name' => 'Cipageran',
                'village' => 'Cipageran',
                'address' => 'Jl. Bobojong No. 148 Kel. Cipageran, Kec. Cimahi Utara',
                'phone' => '0226627698',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277030203',
                'name' => 'Pasirkaliki',
                'village' => 'Pasirkaliki',
                'address' => 'Jl. Cidamar Kel. Pasirkaliki, Kec. Cimahi Utara',
                'phone' => '0222021935',
                'service_type' => 2,
            ],
            [
                'code' => 'P3277030204',
                'name' => 'Citeureup',
                'village' => 'Citeureup',
                'address' => 'Kel. Citeureup, Kec. Cimahi Utara',
                'phone' => '0226628983',
                'service_type' => 2,
            ],
        ];

        foreach ($data as $item) {

            $village = DB::table('villages')
                ->where('name', $item['village'])
                ->first();

            if (!$village) {
                $this->command?->warn("Kelurahan '{$item['village']}' tidak ditemukan.");
                continue;
            }

            DB::table('puskesmas')->updateOrInsert(
                [
                    'code' => $item['code'],
                ],
                [
                    'name' => $item['name'],
                    'village_id' => $village->id,
                    'address' => $item['address'],
                    'phone' => $item['phone'],
                    'email' => null,
                    'head_name' => null,
                    'service_type' => $item['service_type'],
                    'description' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}