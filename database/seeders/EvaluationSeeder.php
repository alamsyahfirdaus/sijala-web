<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [

            /*
            |--------------------------------------------------------------------------
            | TOPIK 1: PENCEGAHAN JATUH PADA LANSIA
            |--------------------------------------------------------------------------
            */
            [
                'topic' => 'Pencegahan Jatuh pada Lansia',
                'description' => 'Evaluasi pengetahuan keluarga mengenai faktor risiko, pencegahan, dan penanganan jatuh pada lansia.',
                'order' => 1,
                'questions' => [
                    [
                        'question' => 'Manakah yang termasuk faktor internal (dari dalam) penyebab jatuh pada lansia?',
                        'option_a' => 'Lantai licin',
                        'option_b' => 'Kabel listrik berserakan',
                        'option_c' => 'Penurunan keseimbangan dan kekuatan otot',
                        'option_d' => 'Pencahayaan buruk',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 1,
                    ],
                    [
                        'question' => 'Berikut yang termasuk faktor lingkungan penyebab jatuh adalah …',
                        'option_a' => 'Osteoporosis',
                        'option_b' => 'Stroke',
                        'option_c' => 'Diabetes',
                        'option_d' => 'Karpet yang mudah bergeser',
                        'correct_answer' => 'd',
                        'score' => 1,
                        'order' => 2,
                    ],
                    [
                        'question' => 'Salah satu cara keluarga mencegah risiko jatuh pada lansia adalah …',
                        'option_a' => 'Membiarkan lansia berjalan sendiri tanpa pengawasan',
                        'option_b' => 'Menyediakan lingkungan rumah yang aman',
                        'option_c' => 'Mengurangi aktivitas fisik lansia sepenuhnya',
                        'option_d' => 'Membatasi komunikasi dengan lansia',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 3,
                    ],
                    [
                        'question' => 'Apa yang harus dilakukan pertama kali saat lansia terjatuh?',
                        'option_a' => 'Langsung memaksa lansia berdiri',
                        'option_b' => 'Memberikan makanan dan minuman',
                        'option_c' => 'Tetap tenang dan memeriksa kondisi lansia',
                        'option_d' => 'Membiarkan lansia beristirahat sendiri',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 4,
                    ],
                    [
                        'question' => 'Bila dicurigai terdapat patah tulang atau cedera kepala setelah jatuh, tindakan yang tepat adalah …',
                        'option_a' => 'Dipijat segera',
                        'option_b' => 'Dibantu berjalan perlahan',
                        'option_c' => 'Diberikan obat tidur',
                        'option_d' => 'Segera dibawa ke fasilitas kesehatan',
                        'correct_answer' => 'd',
                        'score' => 1,
                        'order' => 5,
                    ],
                    [
                        'question' => 'Perilaku berikut yang dapat meningkatkan risiko jatuh pada lansia adalah …',
                        'option_a' => 'Bangun dari tempat tidur secara perlahan',
                        'option_b' => 'Menggunakan alas kaki yang sesuai',
                        'option_c' => 'Tergesa-gesa saat berjalan',
                        'option_d' => 'Menggunakan pegangan tangan di kamar mandi',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 6,
                    ],
                    [
                        'question' => 'Mengapa latihan fisik dan program keseimbangan penting bagi lansia?',
                        'option_a' => 'Membuat lansia cepat lelah',
                        'option_b' => 'Menurunkan kekuatan otot',
                        'option_c' => 'Membantu menjaga keseimbangan dan kekuatan tubuh',
                        'option_d' => 'Mengurangi kemampuan berjalan',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 7,
                    ],
                    [
                        'question' => 'Pencegahan jatuh pada lansia terbukti dapat …',
                        'option_a' => 'Mengurangi kemandirian lansia',
                        'option_b' => 'Meningkatkan risiko cedera',
                        'option_c' => 'Menurunkan kualitas hidup',
                        'option_d' => 'Mempertahankan kemandirian lansia',
                        'correct_answer' => 'd',
                        'score' => 1,
                        'order' => 8,
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | TOPIK 2: PENGGUNAAN ALAT BANTU JALAN YANG BENAR
            |--------------------------------------------------------------------------
            */
            [
                'topic' => 'Penggunaan Alat Bantu Jalan yang Benar',
                'description' => 'Evaluasi pengetahuan tentang penggunaan tongkat dan walker secara aman.',
                'order' => 2,
                'questions' => [
                    [
                        'question' => 'Apa manfaat utama penggunaan alat bantu jalan pada lansia?',
                        'option_a' => 'Membuat lansia lebih cepat berjalan',
                        'option_b' => 'Mengurangi risiko jatuh dan meningkatkan stabilitas',
                        'option_c' => 'Menghilangkan seluruh nyeri sendi',
                        'option_d' => 'Membatasi aktivitas lansia',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 1,
                    ],
                    [
                        'question' => 'Tongkat sebaiknya digunakan pada tangan yang …',
                        'option_a' => 'Sama dengan kaki yang lemah',
                        'option_b' => 'Paling kuat',
                        'option_c' => 'Berlawanan dengan kaki yang lemah atau nyeri',
                        'option_d' => 'Dominan digunakan sehari-hari',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 2,
                    ],
                    [
                        'question' => 'Tinggi tongkat yang benar adalah ketika pegangan tongkat …',
                        'option_a' => 'Setinggi bahu',
                        'option_b' => 'Sejajar lutut',
                        'option_c' => 'Sejajar lipatan pergelangan tangan saat berdiri tegak',
                        'option_d' => 'Setinggi pinggang',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 3,
                    ],
                    [
                        'question' => 'Saat berjalan menggunakan tongkat, langkah yang benar adalah …',
                        'option_a' => 'Kaki kuat maju lebih dulu',
                        'option_b' => 'Tongkat diangkat tinggi-tinggi',
                        'option_c' => 'Tongkat dimajukan bersama kaki yang lemah',
                        'option_d' => 'Tongkat diletakkan jauh di samping tubuh',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 4,
                    ],
                    [
                        'question' => 'Berikut ini yang harus diperiksa secara rutin pada walker adalah …',
                        'option_a' => 'Warna walker',
                        'option_b' => 'Berat walker',
                        'option_c' => 'Bentuk pegangan walker',
                        'option_d' => 'Karet, baut, roda, dan rem walker',
                        'correct_answer' => 'd',
                        'score' => 1,
                        'order' => 5,
                    ],
                    [
                        'question' => 'Saat berdiri menggunakan walker, tindakan yang benar adalah …',
                        'option_a' => 'Menarik walker agar tubuh terangkat',
                        'option_b' => 'Berdiri sambil menarik walker',
                        'option_c' => 'Dorong badan ke ujung kursi lalu berdiri dan pegang walker',
                        'option_d' => 'Mengangkat walker terlebih dahulu',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 6,
                    ],
                    [
                        'question' => 'Cara berjalan yang benar menggunakan walker adalah …',
                        'option_a' => 'Walker diangkat tinggi-tinggi',
                        'option_b' => 'Walker didorong sedikit ke depan lalu kaki lemah melangkah terlebih dahulu',
                        'option_c' => 'Kaki kuat melangkah jauh ke depan',
                        'option_d' => 'Walker diseret sambil berjalan cepat',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 7,
                    ],
                    [
                        'question' => 'Banyak lansia jatuh saat menggunakan alat bantu jalan karena …',
                        'option_a' => 'Lansia terlalu aktif',
                        'option_b' => 'Alat bantu terlalu mahal',
                        'option_c' => 'Alat bantu tidak pas atau cara penggunaan salah',
                        'option_d' => 'Lansia kurang tidur',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 8,
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | TOPIK 3: KOMUNIKASI EFEKTIF DENGAN LANSIA
            |--------------------------------------------------------------------------
            */
            [
                'topic' => 'Komunikasi Efektif dengan Lansia',
                'description' => 'Evaluasi pengetahuan tentang teknik komunikasi yang empatik dan efektif.',
                'order' => 3,
                'questions' => [
                    [
                        'question' => 'Apa yang dimaksud dengan validasi perasaan pada lansia?',
                        'option_a' => 'Mengabaikan perasaan lansia',
                        'option_b' => 'Menyalahkan perasaan lansia',
                        'option_c' => 'Menghargai dan memahami perasaan lansia',
                        'option_d' => 'Memaksa lansia mengikuti pendapat orang lain',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 1,
                    ],
                    [
                        'question' => 'Dalam berkomunikasi dengan lansia, sikap yang paling penting adalah …',
                        'option_a' => 'Berbicara dengan cepat',
                        'option_b' => 'Menyimak dengan penuh perhatian',
                        'option_c' => 'Memotong pembicaraan lansia',
                        'option_d' => 'Mengubah topik pembicaraan terus-menerus',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 2,
                    ],
                    [
                        'question' => 'Nostalgia pada lansia biasanya berkaitan dengan …',
                        'option_a' => 'Keinginan membeli barang baru',
                        'option_b' => 'Cerita dan pengalaman masa lalu',
                        'option_c' => 'Kemampuan bekerja berat',
                        'option_d' => 'Aktivitas olahraga berat',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 3,
                    ],
                    [
                        'question' => 'Tujuan komunikasi yang baik dengan lansia adalah …',
                        'option_a' => 'Membuat lansia merasa takut',
                        'option_b' => 'Membatasi interaksi sosial lansia',
                        'option_c' => 'Membantu lansia merasa dihargai dan nyaman',
                        'option_d' => 'Menghindari percakapan dengan lansia',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 4,
                    ],
                    [
                        'question' => 'Saat lansia sedang bercerita, tindakan yang tepat adalah …',
                        'option_a' => 'Menyela pembicaraan',
                        'option_b' => 'Mengabaikan cerita',
                        'option_c' => 'Mendengarkan dengan sabar',
                        'option_d' => 'Meminta lansia berhenti berbicara',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 5,
                    ],
                    [
                        'question' => 'Berikut ini contoh komunikasi yang kurang baik pada lansia adalah …',
                        'option_a' => 'Menggunakan bahasa yang sopan',
                        'option_b' => 'Menatap lawan bicara saat berbicara',
                        'option_c' => 'Berbicara sambil marah-marah',
                        'option_d' => 'Memberi kesempatan lansia berbicara',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 6,
                    ],
                    [
                        'question' => 'Mengapa validasi perasaan penting bagi lansia?',
                        'option_a' => 'Agar lansia merasa dihukum',
                        'option_b' => 'Agar lansia merasa dihargai dan dipahami',
                        'option_c' => 'Agar lansia lebih banyak diam',
                        'option_d' => 'Agar komunikasi cepat selesai',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 7,
                    ],
                    [
                        'question' => 'Salah satu manfaat menyimak aktif pada lansia adalah …',
                        'option_a' => 'Lansia merasa tidak diperhatikan',
                        'option_b' => 'Mengurangi kepercayaan diri lansia',
                        'option_c' => 'Membantu terciptanya hubungan yang baik',
                        'option_d' => 'Membatasi komunikasi dengan keluarga',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 8,
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | TOPIK 4: MASALAH PSIKOLOGIS DALAM MERAWAT LANSIA
            |--------------------------------------------------------------------------
            */
            [
                'topic' => 'Masalah Psikologis dalam Merawat Lansia',
                'description' => 'Evaluasi pengetahuan tentang stres, burnout, dan strategi coping caregiver.',
                'order' => 4,
                'questions' => [
                    [
                        'question' => 'Apa faktor utama yang menyebabkan masalah psikologis pada keluarga yang merawat lansia?',
                        'option_a' => 'Dukungan sosial yang terlalu banyak',
                        'option_b' => 'Tuntutan perawatan yang tinggi baik secara fisik maupun emosional',
                        'option_c' => 'Lansia yang masih sangat mandiri',
                        'option_d' => 'Waktu perawatan yang singkat',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 1,
                    ],
                    [
                        'question' => 'Kondisi lansia seperti apa yang dapat menyebabkan waktu perawatan menjadi panjang dan memicu stres keluarga?',
                        'option_a' => 'Lansia dengan flu ringan',
                        'option_b' => 'Lansia dengan hobi berkebun',
                        'option_c' => 'Kondisi demensia (pikun) atau disabilitas dengan ketergantungan tinggi',
                        'option_d' => 'Lansia yang rutin berolahraga',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 2,
                    ],
                    [
                        'question' => 'Manakah yang termasuk masalah psikologis yang sering dialami caregiver?',
                        'option_a' => 'Peningkatan rasa percaya diri',
                        'option_b' => 'Burnout (kelelahan hebat) dan depresi',
                        'option_c' => 'Hubungan sosial yang makin luas',
                        'option_d' => 'Tubuh yang terasa makin segar',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 3,
                    ],
                    [
                        'question' => 'Selain kecemasan, perasaan negatif apa yang sering muncul karena merasa tidak maksimal dalam merawat lansia?',
                        'option_a' => 'Rasa bangga',
                        'option_b' => 'Rasa bersalah',
                        'option_c' => 'Rasa tidak peduli',
                        'option_d' => 'Rasa tenang',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 4,
                    ],
                    [
                        'question' => 'Apa dampak dari masalah psikologis keluarga terhadap kualitas asuhan lansia?',
                        'option_a' => 'Kualitas perawatan lansia menurun',
                        'option_b' => 'Lansia menjadi lebih sehat',
                        'option_c' => 'Perawatan menjadi lebih profesional',
                        'option_d' => 'Tidak ada dampak pada lansia',
                        'correct_answer' => 'a',
                        'score' => 1,
                        'order' => 5,
                    ],
                    [
                        'question' => 'Risiko berbahaya apa yang meningkat jika beban psikologis keluarga tidak segera ditangani?',
                        'option_a' => 'Peningkatan ekonomi keluarga',
                        'option_b' => 'Risiko kekerasan pada lansia (elder abuse)',
                        'option_c' => 'Lansia menjadi lebih penurut',
                        'option_d' => 'Komunikasi keluarga menjadi lebih lancar',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 6,
                    ],
                    [
                        'question' => 'Strategi apa yang disarankan untuk memberikan jeda istirahat sementara bagi caregiver?',
                        'option_a' => 'Isolasi sosial',
                        'option_b' => 'Pengabaian perawatan',
                        'option_c' => 'Time-Out atau Respite Care',
                        'option_d' => 'Manajemen konflik',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 7,
                    ],
                    [
                        'question' => 'Jika keluarga merasa tidak mampu menangani tekanan emosional sendiri, langkah yang paling tepat adalah …',
                        'option_a' => 'Menanggung beban sendiri',
                        'option_b' => 'Berhenti merawat lansia sepenuhnya',
                        'option_c' => 'Mencari bantuan profesional',
                        'option_d' => 'Mengurangi komunikasi dengan keluarga',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 8,
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | TOPIK 5: LATIHAN OTAGO UNTUK KESEIMBANGAN LANSIA
            |--------------------------------------------------------------------------
            */
            [
                'topic' => 'Latihan Otago untuk Keseimbangan Lansia',
                'description' => 'Evaluasi pengetahuan tentang Otago Exercise Programme untuk mengurangi risiko jatuh.',
                'order' => 5,
                'questions' => [
                    [
                        'question' => 'Apa tujuan utama dari Otago Exercise Programme (OEP)?',
                        'option_a' => 'Menurunkan berat badan secara drastis',
                        'option_b' => 'Mencegah kejadian jatuh melalui peningkatan kekuatan otot dan keseimbangan',
                        'option_c' => 'Mengobati penyakit jantung akut',
                        'option_d' => 'Mengganti peran obat-obatan medis',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 1,
                    ],
                    [
                        'question' => 'Berapa frekuensi dan durasi latihan OEP yang disarankan?',
                        'option_a' => 'Setiap hari selama 1 jam',
                        'option_b' => 'Dua kali seminggu selama 10 menit',
                        'option_c' => 'Tiga kali seminggu dengan durasi 20–30 menit',
                        'option_d' => 'Satu kali seminggu selama 45 menit',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 2,
                    ],
                    [
                        'question' => 'Siapa yang diperbolehkan melakukan latihan OEP?',
                        'option_a' => 'Lansia usia 65 tahun ke atas yang mampu berdiri dan berjalan',
                        'option_b' => 'Lansia yang menggunakan kursi roda sepenuhnya',
                        'option_c' => 'Lansia dengan gangguan kognitif berat',
                        'option_d' => 'Lansia dengan masalah kardiovaskular akut',
                        'correct_answer' => 'a',
                        'score' => 1,
                        'order' => 3,
                    ],
                    [
                        'question' => 'Gerakan "Heel Raises" dilakukan dengan posisi awal …',
                        'option_a' => 'Duduk di kursi',
                        'option_b' => 'Berbaring tegak',
                        'option_c' => 'Berdiri dan memegang kursi',
                        'option_d' => 'Berjalan mundur',
                        'correct_answer' => 'c',
                        'score' => 1,
                        'order' => 4,
                    ],
                    [
                        'question' => 'Apa yang dimaksud dengan gerakan "Tandem Stance"?',
                        'option_a' => 'Mengangkat satu kaki selama 20 detik',
                        'option_b' => 'Berdiri dengan satu kaki di depan kaki lain seperti di garis lurus',
                        'option_c' => 'Jalan santai selama 5 menit',
                        'option_d' => 'Menekuk lutut seperti akan duduk',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 5,
                    ],
                    [
                        'question' => 'Pada latihan "Sit to Stand", berapa kali pengulangan yang disarankan?',
                        'option_a' => '5 kali',
                        'option_b' => '10 kali',
                        'option_c' => '20 kali',
                        'option_d' => '30 kali',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 6,
                    ],
                    [
                        'question' => 'Kondisi berikut yang menjadi kontraindikasi latihan OEP adalah …',
                        'option_a' => 'Lansia yang ingin hidup mandiri',
                        'option_b' => 'Lansia dengan nyeri akut atau gangguan muskuloskeletal berat',
                        'option_c' => 'Lansia yang merasa percaya diri',
                        'option_d' => 'Lansia yang ingin meningkatkan kekuatan otot',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 7,
                    ],
                    [
                        'question' => 'Hal penting terkait keselamatan saat latihan di rumah adalah …',
                        'option_a' => 'Melakukan latihan di permukaan licin',
                        'option_b' => 'Memakai alas kaki yang tidak licin dan berhenti jika merasa nyeri',
                        'option_c' => 'Tetap memaksakan latihan meskipun sangat lelah',
                        'option_d' => 'Tidak perlu berkonsultasi dengan tenaga kesehatan',
                        'correct_answer' => 'b',
                        'score' => 1,
                        'order' => 8,
                    ],
                ],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($topics) {
            foreach ($topics as $topicData) {
                $questions = $topicData['questions'];
                unset($topicData['questions']);

                $topicId = DB::table('evaluation_topics')->insertGetId([
                    'topic' => $topicData['topic'],
                    'description' => $topicData['description'],
                    'is_active' => true,
                    'order' => $topicData['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($questions as $questionIndex => $question) {
                    DB::table('evaluation_questions')->insert([
                        'evaluation_topic_id' => $topicId,
                        'question' => $question['question'],
                        'option_a' => $question['option_a'],
                        'option_b' => $question['option_b'],
                        'option_c' => $question['option_c'],
                        'option_d' => $question['option_d'],
                        'correct_answer' => $question['correct_answer'],
                        'score' => 1,
                        'is_active' => true,
                        'order' => $questionIndex + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

        });
    }
}
