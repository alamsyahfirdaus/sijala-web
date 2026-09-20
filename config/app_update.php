<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Update Aplikasi SIJALA (Android APK)
    |--------------------------------------------------------------------------
    |
    | Pengaturan versi aplikasi Android untuk pengecekan in-place update.
    | Distribusi APK dilakukan melalui Google Drive atau URL langsung.
    |
    */

    'version_name'  => '1.1.0',

    'version_code'  => 2,

    'download_url'  => 'https://drive.google.com/file/d/1ELt9sFfHialvU875oz4mkwtQFUcBtYOZ/view?usp=sharing',

    'release_notes' => [
        'Pembaruan sistem Video Call Agora RTC',
        'Peningkatan stabilitas koneksi konsultasi',
        'Pembaruan notifikasi panggilan',
    ],

    'file_size'     => 277000000,

    'force_update'  => false,
];