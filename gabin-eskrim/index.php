<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Gabin Es Krim BinIce</title>
    <!-- Memuat Google Fonts: Great Vibes (sambung) dan Fredoka (bubble font) -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Great+Vibes&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body.intro-body {
            
            background-image: url('betul.png');
            background-size: 100% 100%; 
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #593539;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            margin: 0;
            font-family: 'Quicksand', sans-serif;
        }

        .intro-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex: 1;
            padding: 0 8%;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .intro-text {
            flex: 1;
            animation: slideInLeft 1s ease-out;
        }

        /* Styling Judul Utama menggunakan Bubble Font (Fredoka) */
        .intro-text h1 {
            font-family: 'Fredoka', cursive, sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 20px;
            color: #800000;
            text-shadow: 2px 2px 0px #fff, 3px 3px 8px rgba(128, 0, 0, 0.2);
        }

        /* Khusus bagian "Gabin Renyah" menggunakan Font Sambung Estetik */
        .intro-text h1 span {
            font-family: 'Great Vibes', cursive;
            font-size: 4.2rem;
            font-weight: normal;
            color: #d81b60;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.9);
            display: inline-block;
            vertical-align: middle;
        }

        .intro-text p {
            font-size: 1.15rem;
            color: #593539;
            margin-bottom: 30px;
            max-width: 500px;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
        }

        .intro-image-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: slideInRight 1s ease-out;
        }

      .animasi-eskrim {
    width: 300px;
    height: 300px;
    object-fit: cover;
    border-radius: 50%;
    border: 6px solid #fff;
    box-shadow: 0 10px 30px rgba(128, 0, 0, 0.2);
    animation: floatImage 3s ease-in-out infinite;
    
    margin-top: 50px;
}

        @keyframes floatImage {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
            100% { transform: translateY(0px); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .btn-play {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #800000;
            color: white;
            padding: 15px 35px;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 5px 20px rgba(128, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .btn-play:hover {
            background: #500000;
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(80, 0, 0, 0.4);
        }

        /* Running Text disesuaikan pas di atas meja dalam gambar */
        .running-text-container {
            background: rgba(255, 182, 193, 0.95);
            color: #800000;
            padding: 10px 0;
            white-space: nowrap;
            overflow: hidden;
            max-width: 75%;
            margin: 0 auto 15px auto;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .running-text {
            display: inline-block;
            animation: marquee 18s linear infinite;
            font-weight: bold;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        @media(max-width: 900px) {
            .intro-container {
                flex-direction: column-reverse;
                text-align: center;
                padding: 20px;
            }
            .intro-text h1 { font-size: 2.2rem; }
            .intro-text h1 span { font-size: 3rem; }
            .animasi-eskrim { width: 240px; height: 240px; margin-bottom: 20px; }
            .intro-text p { margin: 0 auto 20px auto; }
            .running-text-container { max-width: 90%; }
        }
    </style>
</head>
<body class="intro-body">

    <header style="background: transparent; box-shadow: none; position: absolute; width: 100%;">
        <div class="logo" style="padding-left: 5%; color: #d63031;">🍦 GABIN ES KRIM</div>
    </header>

    <div class="intro-container">
        <div class="intro-text">
            <h1>Sensasi <span>Gabin Renyah</span> & Es Krim Lumer Terbaik</h1>
            <p>Nikmati perpaduan biskuit gabin gurih dengan kelembutan es krim berbagai varian rasa buah yang manis dan menyegarkan.</p>
            <a href="home.php" class="btn-play">
                ▶ Masuk ke Toko
            </a>
        </div>
        <div class="intro-image-container">
            <img src="kawai.png" alt="Gabin Es Krim Pastel" class="animasi-eskrim">
        </div>
    </div>

    <div class="running-text-container">
        <div class="running-text">
            🌸 SELAMAT DATANG DI TOKO GABIN ES KRIM PASTEL! • 🍦 RENYAH DI LUAR, LUMER DI DALAM! • ✨ PILIHAN RASA STRAWBERRY, ANGGUR, MANGGA, MATCHA & COOKIES AND CREAM • 🚀 PESAN SEKARANG VIA DANA, GOPAY & COD!
        </div>
    </div>

</body>
</html>