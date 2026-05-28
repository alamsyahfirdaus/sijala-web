<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jaga Lansia Official</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #333;
        }

        header{
            background: linear-gradient(135deg, #1565c0, #0d47a1);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        header h1{
            font-size: 36px;
            margin-bottom: 10px;
        }

        header p{
            font-size: 18px;
            opacity: 0.9;
        }

        .container{
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .video-container{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
        }

        .video-card{
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .video-card:hover{
            transform: translateY(-6px);
        }

        .video-card iframe{
            width: 100%;
            height: 220px;
            border: none;
        }

        .video-info{
            padding: 18px;
        }

        .video-info h3{
            font-size: 18px;
            line-height: 1.5;
            color: #1565c0;
        }

        .channel-link{
            text-align: center;
            margin: 50px 0;
        }

        .channel-link a{
            text-decoration: none;
            background: #ff0000;
            color: white;
            padding: 14px 24px;
            border-radius: 10px;
            display: inline-block;
            font-weight: bold;
            transition: 0.3s;
        }

        .channel-link a:hover{
            background: #d50000;
        }

        footer{
            background: #0d47a1;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <header>
        <h1>Jaga Lansia Official</h1>
        <p>Kumpulan Video Edukasi dan Informasi Seputar Lansia</p>
    </header>

    <div class="container">

        <div class="video-container">

            <!-- Video 1 -->
            <div class="video-card">
                <iframe 
                    src="https://www.youtube.com/embed/oPG9EYbCp9w?si=i_JU62bHwKfx-A4V"
                    title="YouTube video player"
                    allowfullscreen>
                </iframe>

                <div class="video-info">
                    <h3>
                        Cara Aman Menggunakan Walker untuk Lansia | Proper Walker Walking Technique
                    </h3>
                </div>
            </div>

            <!-- Video 2 -->
            <div class="video-card">
                <iframe 
                    src="https://www.youtube.com/embed/5UlD1n-6QqU?si=Ya95KizVemveWRJ1"
                    title="YouTube video player"
                    allowfullscreen>
                </iframe>

                <div class="video-info">
                    <h3>
                        Cara Aman Menggunakan Tongkat untuk Lansia | Proper Cane Walking Technique
                    </h3>
                </div>
            </div>

            <!-- Video 3 -->
            <div class="video-card">
                <iframe 
                    src="https://www.youtube.com/embed/1CpcOTZlka8?si=Sw2UbEjEQaWoeSV2"
                    title="YouTube video player"
                    allowfullscreen>
                </iframe>

                <div class="video-info">
                    <h3>
                        Cara Aman Menggunakan Kursi Roda untuk Lansia | Wheelchair Safety Tips for Seniors
                    </h3>
                </div>
            </div>

            <!-- Video 4 -->
            <div class="video-card">
                <iframe 
                    src="https://www.youtube.com/embed/Kb-YJe_OpS4?si=3-C7f2Ay35x4bMDx"
                    title="YouTube video player"
                    allowfullscreen>
                </iframe>

                <div class="video-info">
                    <h3>
                        Latihan Keseimbangan untuk Mencegah Risiko Jatuh pada Lansia | Fall Prevention Exercise
                    </h3>
                </div>
            </div>

            <!-- Video 5 -->
            <div class="video-card">
                <iframe 
                    src="https://www.youtube.com/embed/ZbtdHBsXnC8?si=Ip2B1qY3w6UzsKhJ"
                    title="YouTube video player"
                    allowfullscreen>
                </iframe>

                <div class="video-info">
                    <h3>
                        Latihan Keseimbangan bagi Lansia Pengguna Kursi Roda | Wheelchair Balance Exercise
                    </h3>
                </div>
            </div>

        </div>

        <div class="channel-link">
            <a href="https://www.youtube.com/@jagalansia" target="_blank">
                Kunjungi Channel YouTube
            </a>
        </div>

    </div>

    <footer>
        © {{ date('Y') }} Jaga Lansia Official - Semua Hak Dilindungi
    </footer>

</body>
</html>