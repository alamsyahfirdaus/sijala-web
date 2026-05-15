<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyEmpowermentQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Counter urutan global (dimensi + pertanyaan)
        $order = 1;

        // Rentang skor default skala Likert
        $minScore = 1;
        $maxScore = 4;

        // ======================================================
        // DATA DIMENSI DAN PERTANYAAN
        // ======================================================
        $dimensions = [
            [
                'name' => 'Kemampuan keluarga mengenal masalah',
                'questions' => [
                    ['text' => 'Risiko jatuh pada lansia adalah kemungkinan lansia untuk terjatuh karena berbagai faktor, baik yang berasal dari diri lansia itu sendiri maupun dari lingkungannya.'],
                    ['text' => 'Faktor usia, penyakit yang lama, otot yang lemah, keseimbangan yang tidak baik, dan pemakaian obat tertentu merupakan faktor yang dapat menimbulkan risiko jatuh.'],
                    ['text' => 'Lansia sering tersandung merupakan tanda ketidakseimbangan lansia yang dapat berpotensi jatuh.'],
                    ['text' => 'Lansia yang pernah mengalami jatuh akan berisiko mengalami jatuh kembali.'],
                    ['text' => 'Kondisi lingkungan rumah, seperti pencahayaan kurang, lantai licin, atau rumah yang berantakan, dapat menjadi penyebab jatuh pada lansia.'],
                    ['text' => 'Pusing pada lansia tidak berkaitan dengan risiko jatuh.'],
                    ['text' => 'Lansia dengan gangguan penglihatan berisiko jatuh.'],
                ],
            ],
            [
                'name' => 'Kemampuan keluarga mengambil keputusan',
                'questions' => [
                    ['text' => 'Bagi keluarga kami, risiko jatuh lansia merupakan masalah yang serius.'],
                    ['text' => 'Bagi keluarga kami, jatuh pada lansia dapat menyebabkan patah tulang, kecacatan, bahkan kematian.'],
                    ['text' => 'Perlu tindakan segera ketika lansia mengalami jatuh.'],
                    ['text' => 'Kami memutuskan melakukan upaya pencegahan jatuh, misalnya dengan memasang pegangan di kamar mandi dan mengawasi aktivitas lansia.'],
                    ['text' => 'Keluarga menunda keputusan modifikasi rumah karena biaya tinggi meskipun risiko jatuh tinggi.'],
                    ['text' => 'Keluarga ragu menerapkan latihan fisik karena takut lansia lelah.'],
                ],
            ],
            [
                'name' => 'Kemampuan keluarga merawat anggota keluarga yang sakit',
                'questions' => [
                    ['text' => 'Saya melibatkan anggota keluarga yang lain dalam mencegah jatuh pada lansia.'],
                    ['text' => 'Saya mengajarkan pada lansia latihan berjalan dan memakai alat bantu jalan yang benar agar terhindar dari jatuh.'],
                    ['text' => 'Saya mengajarkan latihan keseimbangan pada lansia untuk mengurangi risiko jatuh.'],
                    ['text' => 'Saya mengondisikan rumah yang aman untuk lansia agar terhindar dari jatuh, misalnya pencahayaan yang terang dan lantai tidak licin.'],
                    ['text' => 'Saya mengabaikan kondisi anggota keluarga yang berisiko jatuh.'],
                    ['text' => 'Saya membatasi aktivitas lansia secara berlebihan agar tidak jatuh.'],
                    ['text' => 'Saya memantau obat yang dikonsumsi lansia dan memperhatikan kemungkinan efek samping seperti pusing.'],
                    ['text' => 'Lansia yang pernah jatuh tidak perlu diawasi dalam beraktivitas.'],
                ],
            ],
            [
                'name' => 'Kemampuan keluarga memodifikasi lingkungan',
                'questions' => [
                    ['text' => 'Saya menempatkan lansia dalam ruangan dengan pencahayaan yang cukup agar tidak jatuh.'],
                    ['text' => 'Keluarga membatasi kebisingan agar lansia fokus berjalan.'],
                    ['text' => 'Saya mencegah lantai basah dan licin agar lansia terhindar dari jatuh.'],
                    ['text' => 'Pegangan rambatan diperlukan untuk mencegah jatuh.'],
                    ['text' => 'Tidak perlu menata perabotan karena bukan penyebab jatuh.'],
                    ['text' => 'Tidak masalah kabel listrik berserakan karena bukan penyebab jatuh lansia.'],
                    ['text' => 'Kami sering lupa membersihkan area basah di dapur atau kamar mandi.'],
                    ['text' => 'Menumpuk barang di lorong tidak berkaitan dengan risiko jatuh lansia.'],
                ],
            ],
            [
                'name' => 'Kemampuan keluarga memanfaatkan fasilitas kesehatan',
                'questions' => [
                    ['text' => 'Saya mengantar lansia ke puskesmas untuk kontrol penyakit lansia.'],
                    ['text' => 'Pemeriksaan kesehatan lansia secara rutin dapat mencegah risiko jatuh.'],
                    ['text' => 'Pemeriksaan risiko jatuh dilakukan di fasilitas kesehatan.'],
                    ['text' => 'Saya tidak memeriksakan kesehatan lansia ke fasilitas kesehatan karena jauh.'],
                    ['text' => 'Keluarga kurang percaya pada tenaga kesehatan terkait pencegahan jatuh.'],
                    ['text' => 'Saya membawa lansia yang berisiko jatuh ke pengobatan alternatif sebagai pengganti pengobatan medis.'],
                ],
            ],
        ];

        foreach ($dimensions as $dimension) {
            // ==================================================
            // INSERT DIMENSI
            // Skor tetap diisi agar child dapat mewarisi nilai
            // ==================================================
            $dimensionId = DB::table('family_empowerment_questions')->insertGetId([
                'dimension_id' => null,
                'question'     => $dimension['name'],
                'min_score'    => $minScore,
                'max_score'    => $maxScore,
                'order'        => $order++,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            // ==================================================
            // INSERT PERTANYAAN
            // min_score dan max_score dikosongkan (inherit dari dimensi)
            // ==================================================
            foreach ($dimension['questions'] as $question) {
                DB::table('family_empowerment_questions')->insert([
                    'dimension_id' => $dimensionId,
                    'question'     => $question['text'],
                    'min_score'    => null,
                    'max_score'    => null,
                    'order'        => $order++,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }
    }
}