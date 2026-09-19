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

    'download_url'  => 'https://drive.google.com/file/d/10ts9iD1I0yJB2chVBq9aq-paygxd1JKK/view?usp=drive_link',

    'release_notes' => [
        'Pembaruan sistem Video Call Agora RTC',
        'Peningkatan stabilitas koneksi konsultasi',
        'Pembaruan notifikasi panggilan',
    ],

    'file_size'     => 277000000,

    'force_update'  => false,
];