<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJALA | Konseling Jaga Lansia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #f9b233;
            --primary-dark: #a56700;
            --background: #fffaf0;
            --white: #ffffff;
            --shadow: 0 8px 20px rgba(0, 0, 0, .08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: #333;
        }

        a {
            text-decoration: none;
        }

        header {
            background: linear-gradient(135deg, #ffd54f, #f9b233);
            text-align: center;
            padding: 80px 20px;
        }

        header h1 {
            color: #000;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        header p {
            max-width: 700px;
            margin: auto;
            color: #6d4700;
            line-height: 1.8;
            font-size: 17px;
        }

        .hero-buttons {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: .3s;
            box-shadow: var(--shadow);
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        .btn-download {
            background: #fff;
            color: var(--primary-dark);
        }

        .btn-youtube {
            background: #ff0000;
            color: #fff;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 60px auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-title h2 {
            color: var(--primary-dark);
            font-size: 32px;
        }

        .video-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 25px;
        }

        .video-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: .3s;
        }

        .video-card:hover {
            transform: translateY(-5px);
        }

        .video-card iframe {
            width: 100%;
            height: 220px;
            border: none;
        }

        .video-info {
            padding: 18px;
        }

        .video-date {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }

        .video-info h3 {
            color: var(--primary-dark);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
            min-height: 55px;
        }

        .video-info p {
            color: #666;
            font-size: 14px;
            line-height: 1.7;
        }

        .empty-video {
            grid-column: 1 / -1;
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }

        .apk-section {
            margin-top: 50px;
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .apk-section h2 {
            color: var(--primary-dark);
            margin-bottom: 15px;
        }

        .apk-section p {
            max-width: 700px;
            margin: 0 auto 25px;
            color: #666;
            line-height: 1.8;
        }

        .apk-btn {
            display: inline-block;
            background: var(--primary);
            color: var(--primary-dark);
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 700;
            transition: .3s;
            box-shadow: var(--shadow);
        }

        .apk-btn:hover {
            transform: translateY(-3px);
        }

        footer {
            margin-top: 60px;
            background: var(--primary-dark);
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        @media(max-width:768px) {

            header {
                padding: 60px 20px;
            }

            header h1 {
                font-size: 34px;
            }

            header p {
                font-size: 15px;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            .video-container {
                grid-template-columns: 1fr;
            }

            .apk-section {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

    <header>

        <h1>Konseling Jaga Lansia</h1>

        <p>
            Konseling Jaga Lansia menyediakan layanan edukasi, konsultasi, dan pendampingan
            kesehatan lansia untuk membantu meningkatkan kesejahteraan lansia dan keluarga.
        </p>

        <div class="hero-buttons">

            <a href="https://drive.google.com/uc?export=download&id=10ts9iD1I0yJB2chVBq9aq-paygxd1JKK"
                class="btn btn-download">
                Unduh Aplikasi
            </a>

            <a href="https://www.youtube.com/@jagalansia" target="_blank" class="btn btn-youtube">
                Channel YouTube
            </a>

        </div>

    </header>

    <div class="container">

        <div class="section-title">
            <h2>Video Edukasi Lansia</h2>
        </div>

        <div class="video-container">

            @forelse($videos as $video)
                @if (isset($video['id']['videoId']))
                    <div class="video-card">

                        <iframe src="https://www.youtube.com/embed/{{ $video['id']['videoId'] }}"
                            title="{{ $video['snippet']['title'] }}" allowfullscreen>
                        </iframe>

                        <div class="video-info">

                            <div class="video-date" style="display: none;">
                                {{ \Carbon\Carbon::parse($video['snippet']['publishedAt'])->translatedFormat('d F Y') }}
                            </div>

                            <h3>
                                {{ $video['snippet']['title'] }}
                            </h3>

                            <p>
                                {{ \Illuminate\Support\Str::limit($video['snippet']['description'], 200) }}
                            </p>

                        </div>

                    </div>
                @endif

            @empty

                <div class="empty-video">
                    <h3>Tidak ada video tersedia</h3>
                    <p>Video edukasi akan ditampilkan di sini.</p>
                </div>
            @endforelse

        </div>

        <div class="apk-section">

            <h2>Aplikasi Jaga Lansia</h2>

            <p>
                Unduh aplikasi Jaga Lansia untuk mendapatkan akses informasi kesehatan,
                edukasi, skrining, pemantauan, serta layanan pendampingan lansia dalam
                satu aplikasi yang mudah digunakan.
            </p>

            <a href="https://drive.google.com/uc?export=download&id=10ts9iD1I0yJB2chVBq9aq-paygxd1JKK" class="apk-btn">
                Download APK Sekarang
            </a>

        </div>

    </div>

    <footer>
        © {{ date('Y') }} Jaga Lansia Official. All Rights Reserved.
    </footer>

</body>

</html>
