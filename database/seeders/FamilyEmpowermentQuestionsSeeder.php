<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyEmpowermentQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Urutan tampilan (dimensi + pertanyaan)
        $order = 1;

        // Nomor butir instrumen
        $itemNumber = 1;

        /*
        |--------------------------------------------------------------------------
        | DATA DIMENSI DAN PERTANYAAN
        |--------------------------------------------------------------------------
        | favorable  = true
        | unfavorable = false
        |--------------------------------------------------------------------------
        */
        $dimensions = [

            [
                'name' => 'Kemampuan keluarga mengenal masalah',
                'questions' => [
                    ['text' => 'Risiko jatuh pada lansia adalah kemungkinan lansia untuk terjatuh karena berbagai faktor, baik yang berasal dari diri lansia itu sendiri maupun dari lingkungannya.', 'favorable' => true],
                    ['text' => 'Faktor usia, penyakit yang lama, otot yang lemah, keseimbangan yang tidak baik, dan pemakaian obat tertentu merupakan faktor yang dapat menimbulkan risiko jatuh.', 'favorable' => true],
                    ['text' => 'Lansia sering tersandung merupakan tanda ketidakseimbangan lansia yang dapat berpotensi jatuh.', 'favorable' => true],
                    ['text' => 'Lansia yang pernah mengalami jatuh akan berisiko mengalami jatuh kembali.', 'favorable' => true],
                    ['text' => 'Kondisi lingkungan rumah, seperti pencahayaan kurang, lantai licin, atau rumah yang berantakan, dapat menjadi penyebab jatuh pada lansia.', 'favorable' => true],
                    ['text' => 'Pusing pada lansia tidak berkaitan dengan risiko jatuh.', 'favorable' => false],
                    ['text' => 'Lansia dengan gangguan penglihatan berisiko jatuh.', 'favorable' => true],
                ],
            ],

            [
                'name' => 'Kemampuan keluarga mengambil keputusan',
                'questions' => [
                    ['text' => 'Bagi keluarga kami, risiko jatuh lansia merupakan masalah yang serius.', 'favorable' => true],
                    ['text' => 'Bagi keluarga kami, jatuh pada lansia dapat menyebabkan patah tulang, kecacatan, bahkan kematian.', 'favorable' => true],
                    ['text' => 'Perlu tindakan segera ketika lansia mengalami jatuh.', 'favorable' => true],
                    ['text' => 'Kami memutuskan melakukan upaya pencegahan jatuh, misalnya dengan memasang pegangan di kamar mandi dan mengawasi aktivitas lansia.', 'favorable' => true],
                    ['text' => 'Keluarga menunda keputusan modifikasi rumah karena biaya tinggi meskipun risiko jatuh tinggi.', 'favorable' => false],
                    ['text' => 'Keluarga ragu menerapkan latihan fisik karena takut lansia lelah.', 'favorable' => false],
                ],
            ],

            [
                'name' => 'Kemampuan keluarga merawat anggota keluarga yang sakit',
                'questions' => [
                    ['text' => 'Saya melibatkan anggota keluarga yang lain dalam mencegah jatuh pada lansia.', 'favorable' => true],
                    ['text' => 'Saya mengajarkan pada lansia latihan berjalan dan memakai alat bantu jalan yang benar agar terhindar dari jatuh.', 'favorable' => true],
                    ['text' => 'Saya mengajarkan latihan keseimbangan pada lansia untuk mengurangi risiko jatuh.', 'favorable' => true],
                    ['text' => 'Saya mengondisikan rumah yang aman untuk lansia agar terhindar dari jatuh, misalnya pencahayaan yang terang dan lantai tidak licin.', 'favorable' => true],
                    ['text' => 'Saya mengabaikan kondisi anggota keluarga yang berisiko jatuh.', 'favorable' => false],
                    ['text' => 'Saya membatasi aktivitas lansia secara berlebihan agar tidak jatuh.', 'favorable' => false],
                    ['text' => 'Saya memantau obat yang dikonsumsi lansia dan memperhatikan kemungkinan efek samping seperti pusing.', 'favorable' => true],
                    ['text' => 'Lansia yang pernah jatuh tidak perlu diawasi dalam beraktivitas.', 'favorable' => false],
                ],
            ],

            [
                'name' => 'Kemampuan keluarga memodifikasi lingkungan',
                'questions' => [
                    ['text' => 'Saya menempatkan lansia dalam ruangan dengan pencahayaan yang cukup agar tidak jatuh.', 'favorable' => true],
                    ['text' => 'Keluarga membatasi kebisingan agar lansia fokus berjalan.', 'favorable' => false],
                    ['text' => 'Saya mencegah lantai basah dan licin agar lansia terhindar dari jatuh.', 'favorable' => true],
                    ['text' => 'Pegangan rambatan diperlukan untuk mencegah jatuh.', 'favorable' => true],
                    ['text' => 'Tidak perlu menata perabotan karena bukan penyebab jatuh.', 'favorable' => false],
                    ['text' => 'Tidak masalah kabel listrik berserakan karena bukan penyebab jatuh lansia.', 'favorable' => false],
                    ['text' => 'Kami sering lupa membersihkan area basah di dapur atau kamar mandi.', 'favorable' => false],
                    ['text' => 'Menumpuk barang di lorong tidak berkaitan dengan risiko jatuh lansia.', 'favorable' => false],
                ],
            ],

            [
                'name' => 'Kemampuan keluarga memanfaatkan fasilitas kesehatan',
                'questions' => [
                    ['text' => 'Saya mengantar lansia ke puskesmas untuk kontrol penyakit lansia.', 'favorable' => true],
                    ['text' => 'Pemeriksaan kesehatan lansia secara rutin dapat mencegah risiko jatuh.', 'favorable' => true],
                    ['text' => 'Pemeriksaan risiko jatuh dilakukan di fasilitas kesehatan.', 'favorable' => true],
                    ['text' => 'Saya tidak memeriksakan kesehatan lansia ke fasilitas kesehatan karena jauh.', 'favorable' => false],
                    ['text' => 'Keluarga kurang percaya pada tenaga kesehatan terkait pencegahan jatuh.', 'favorable' => false],
                    ['text' => 'Saya membawa lansia yang berisiko jatuh ke pengobatan alternatif sebagai pengganti pengobatan medis.', 'favorable' => false],
                ],
            ],

        ];

        foreach ($dimensions as $dimension) {

            /*
            |--------------------------------------------------------------------------
            | INSERT DIMENSI
            |--------------------------------------------------------------------------
            */
            $dimensionId = DB::table('family_empowerment_questions')->insertGetId([
                'dimension_id' => null,
                'item_number'  => null,
                'question'     => $dimension['name'],
                'is_favorable' => null,
                'order'        => $order++,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            /*
            |--------------------------------------------------------------------------
            | INSERT PERTANYAAN
            |--------------------------------------------------------------------------
            */
            foreach ($dimension['questions'] as $question) {

                DB::table('family_empowerment_questions')->insert([
                    'dimension_id' => $dimensionId,
                    'item_number'  => $itemNumber++,
                    'question'     => $question['text'],
                    'is_favorable' => $question['favorable'],
                    'order'        => $order++,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }
    }
}