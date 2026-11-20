<?php
session_start();
include 'koneksi.php';
$total_keranjang = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BinIce - Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Background Foto Menyatu Penuh */
        body { 
            background-image: url('bgbaru.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #593539; 
            overflow-x: hidden; 
            min-height: 100vh;
        }

        header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 20px 8%; 
            background: transparent; 
            position: relative;
            z-index: 100;
        }
        
        /* Styling Logo Bulat di Pojok Kiri Atas */
        .logo-container {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .logo-img {
            width: 80px;  
            height: 80px; 
            object-fit: cover; 
            border-radius: 50%; 
            border: 3px solid #fff; 
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.25); 
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.08);
        }

        /* Navigasi Utama Berbentuk Button / Kotak Diposisikan di Tengah */
        nav { 
            display: flex; 
            gap: 12px; 
            align-items: center; 
            justify-content: center;
            flex: 2;
        }
        nav a { 
            text-decoration: none; 
            color: #593539; 
            font-weight: 600; 
            font-size: 0.95rem; 
            padding: 8px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(216, 27, 96, 0.2);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: 0.3s; 
        }
        nav a:hover, nav a.active { 
            background: #d81b60;
            color: white; 
            border-color: #d81b60;
            transform: translateY(-2px);
        }

        /* Bagian Icon Kanan (Keranjang & Login) */
        .nav-icons { 
            display: flex; 
            gap: 15px; 
            font-size: 1.1rem; 
            align-items: center; 
            justify-content: flex-end;
            flex: 1;
        }
        
        /* Tombol Keranjang Berbentuk Button Jelas */
        .cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #593539;
            background: rgba(255, 255, 255, 0.85);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            border: 1px solid rgba(216, 27, 96, 0.3);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: relative;
            transition: 0.3s;
        }
        .cart-btn:hover {
            background: #fff;
            color: #d81b60;
        }
        
        .cart-badge { 
            background: #d81b60; 
            color: white; 
            font-size: 0.75rem; 
            padding: 2px 7px; 
            border-radius: 50%; 
            font-weight: bold; 
        }

        /* Tombol Login / Admin Desktop */
        .nav-auth-desktop a {
            text-decoration: none;
            font-size: 0.9rem;
            background: #d81b60; 
            color: white; 
            padding: 8px 18px; 
            border-radius: 20px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(216, 27, 96, 0.3);
            transition: 0.3s;
            display: inline-block;
        }
        .nav-auth-desktop a:hover {
            background: #ad1457;
            transform: translateY(-2px);
        }

        /* Tombol Hamburger (Garis Tiga) */
        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 6px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(216, 27, 96, 0.3);
            padding: 10px 12px;
            border-radius: 12px;
            z-index: 101;
        }
        .menu-toggle span {
            display: block;
            width: 24px;
            height: 3px;
            background-color: #d81b60;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .menu-toggle.active span:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }
        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        .menu-toggle.active span:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }

        /* Hero Section */
        .hero-section { display: flex; align-items: center; justify-content: space-between; padding: 40px 8%; position: relative; flex-wrap: wrap; }
        .hero-text { flex: 1; max-width: 520px; z-index: 2; }

        /* Efek Kata Glowing Jalan Satu Arah & Lebih Pelan */
        .hero-text h1 { 
            font-size: 3.5rem; 
            font-weight: 800; 
            line-height: 1.2; 
            margin-bottom: 20px; 
            background: linear-gradient(
                105deg,
                #c2185b 0%,
                #c2185b 36%,
                #ff75a8 46%,
                #ffe6f0 50%,
                #ff75a8 54%,
                #c2185b 64%,
                #c2185b 100%
            );
            background-size: 250% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: glowingOneWaySlow 5.8s linear infinite, glowPulseSlow 3.6s ease-in-out infinite alternate;
        }

        @keyframes glowingOneWaySlow {
            0% {
                background-position: 125% center;
            }
            100% {
                background-position: -125% center;
            }
        }

        @keyframes glowPulseSlow {
            0% {
                filter: drop-shadow(0 0 5px rgba(216, 27, 96, 0.22)) drop-shadow(0 2px 4px rgba(255, 255, 255, 0.8));
            }
            100% {
                filter: drop-shadow(0 0 12px rgba(255, 105, 165, 0.48)) drop-shadow(0 0 20px rgba(255, 194, 219, 0.35));
            }
        }

        .hero-text p { font-size: 1rem; color: #593539; margin-bottom: 30px; line-height: 1.6; font-weight: 600; text-shadow: 1px 1px 2px rgba(255,255,255,0.8); }
        .hero-buttons { display: flex; gap: 15px; }
        .btn-primary { background-color: #d81b60; color: white; padding: 12px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 15px rgba(216, 27, 96, 0.3); transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background-color: #ad1457; transform: translateY(-2px); }
        .btn-secondary { background-color: rgba(255, 255, 255, 0.85); color: #c2185b; padding: 12px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .btn-secondary:hover { background-color: white; }
        
        .hero-image-container { 
            flex: 1; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            position: relative; 
            min-height: 410px;
            max-width: 520px;
            margin: 0 auto;
        }
        
        @keyframes floatImage { 
            0% { transform: translateY(0px); } 
            50% { transform: translateY(-10px); } 
            100% { transform: translateY(0px); } 
        }

        .main-logo { 
            width: 320px; 
            height: auto; 
            object-fit: contain; 
            position: relative; 
            z-index: 2; 
            animation: floatImage 3s ease-in-out infinite; 
        }

        /* Cipratan Es Krim Strawberry Lebih Gede & Tertata di Sekeliling Maskot */
        .strawberry-splashes {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .splash-piece {
            position: absolute;
            filter: drop-shadow(0 8px 14px rgba(216, 27, 96, 0.25));
        }

        .splash-left-top {
            width: 108px;
            top: 18px;
            left: 16px;
            animation: splashLeftAnim 4s ease-in-out infinite;
        }

        .splash-right-top {
            width: 112px;
            top: 22px;
            right: 16px;
            animation: splashRightAnim 4.3s ease-in-out infinite 0.5s;
        }

        .splash-left-bottom {
            width: 96px;
            bottom: 24px;
            left: 28px;
            animation: splashBottomLeftAnim 3.8s ease-in-out infinite 0.9s;
        }

        .splash-right-bottom {
            width: 102px;
            bottom: 20px;
            right: 26px;
            animation: splashBottomRightAnim 4.1s ease-in-out infinite 1.2s;
        }

        .berry-dot {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #ffb3d0, #ff5c93 65%, #d81b60 100%);
            box-shadow: 0 5px 12px rgba(216, 27, 96, 0.28);
        }

        .dot-1 {
            width: 24px;
            height: 24px;
            top: 132px;
            left: 22px;
            animation: dotFloat1 3.4s ease-in-out infinite;
        }

        .dot-2 {
            width: 20px;
            height: 20px;
            top: 142px;
            right: 20px;
            animation: dotFloat2 3.8s ease-in-out infinite 0.6s;
        }

        .dot-3 {
            width: 26px;
            height: 26px;
            bottom: 125px;
            right: 14px;
            animation: dotFloat1 3.6s ease-in-out infinite 1.1s;
        }

        .dot-4 {
            width: 18px;
            height: 18px;
            bottom: 132px;
            left: 14px;
            animation: dotFloat2 4s ease-in-out infinite 0.3s;
        }

        @keyframes splashLeftAnim {
            0%, 100% {
                transform: translate(0, 0) rotate(-8deg) scale(0.96);
            }
            50% {
                transform: translate(-6px, -11px) rotate(5deg) scale(1.06);
            }
        }

        @keyframes splashRightAnim {
            0%, 100% {
                transform: translate(0, 0) rotate(8deg) scale(0.96);
            }
            50% {
                transform: translate(6px, -11px) rotate(-5deg) scale(1.06);
            }
        }

        @keyframes splashBottomLeftAnim {
            0%, 100% {
                transform: translate(0, 0) rotate(-5deg) scale(0.96);
            }
            50% {
                transform: translate(-5px, -9px) rotate(6deg) scale(1.05);
            }
        }

        @keyframes splashBottomRightAnim {
            0%, 100% {
                transform: translate(0, 0) rotate(5deg) scale(0.96);
            }
            50% {
                transform: translate(5px, -9px) rotate(-6deg) scale(1.05);
            }
        }

        @keyframes dotFloat1 {
            0%, 100% {
                transform: translateY(0) scale(0.94);
            }
            50% {
                transform: translateY(-12px) scale(1.1);
            }
        }

        @keyframes dotFloat2 {
            0%, 100% {
                transform: translateY(0) scale(1.05);
            }
            50% {
                transform: translateY(10px) scale(0.92);
            }
        }
        
        /* 5 Kartu Fitur Flip di Bawah */
        .features-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(175px, 1fr)); 
            gap: 20px; 
            padding: 10px 8% 60px 8%; 
        }

        .flip-card {
            background-color: transparent;
            height: 225px;
            perspective: 1000px;
            cursor: pointer;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.65s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform-style: preserve-3d;
        }

        .flip-card.flipped .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.08);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.9);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .flip-card-front {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(5px);
        }

        .flip-card:hover .flip-card-front {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 12px 25px rgba(216, 27, 96, 0.2);
            border-color: #d81b60;
        }

        .flip-card-front h3 { 
            font-size: 1.02rem; 
            font-weight: 800; 
            color: #c2185b; 
            margin-bottom: 10px; 
        }

        .feature-img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            animation: floatImage 3.5s ease-in-out infinite;
            transition: transform 0.3s ease;
        }

        .flip-card:hover .feature-img {
            transform: scale(1.1);
        }

        .flip-hint {
            margin-top: 8px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #880e4f;
            background: rgba(248, 187, 208, 0.45);
            padding: 2px 10px;
            border-radius: 12px;
        }

        .flip-card-back {
            background: linear-gradient(145deg, #fff0f5 0%, #fce4ec 100%);
            transform: rotateY(180deg);
            border: 2px solid #d81b60;
            justify-content: space-between;
            padding: 16px 14px;
        }

        .back-label {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: white;
            background: #d81b60;
            padding: 3px 10px;
            border-radius: 12px;
        }

        .flip-card-back h4 {
            font-size: 0.95rem;
            font-weight: 800;
            color: #800000;
            margin-top: 4px;
        }

        .flip-card-back p {
            font-size: 0.78rem;
            line-height: 1.45;
            color: #593539;
            font-weight: 600;
            overflow-y: auto;
            max-height: 110px;
        }

        .back-footer {
            font-size: 0.7rem;
            font-weight: 700;
            color: #d81b60;
        }

        .mobile-auth {
            display: none;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media(max-width: 900px) {
            header { 
                padding: 15px 5%; 
                justify-content: space-between;
            } 
            
            .logo-container, .nav-icons { 
                flex: unset; 
            }

            .nav-icons {
                display: none;
            }

            .menu-toggle { 
                display: flex; 
            }

            .mobile-auth {
                display: flex;
                flex-direction: column;
                gap: 12px;
                width: 80%;
                align-items: center;
                margin-top: 5px;
            }

            nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(255, 240, 245, 0.98);
                backdrop-filter: blur(10px);
                flex-direction: column;
                align-items: center;
                padding: 25px 0;
                gap: 15px;
                box-shadow: 0 10px 25px rgba(128, 0, 0, 0.1);
                border-bottom: 2px solid rgba(255, 182, 193, 0.8);
                animation: slideDown 0.3s ease-out forwards;
            }

            nav a {
                width: 80%;
                text-align: center;
                background: white;
            }

            nav.active {
                display: flex;
            }

            .hero-section { 
                flex-direction: column; 
                text-align: center; 
                padding: 20px 5%; 
            }
            .hero-text { 
                max-width: 100%; 
                margin-bottom: 30px; 
            }
            .hero-text h1 { 
                font-size: 2.3rem; 
            }
            .hero-buttons { 
                justify-content: center; 
            }
            .hero-image-container {
                min-height: 340px;
                width: 100%;
                max-width: 390px;
            }
            .main-logo { 
                width: 240px; 
            }
            .splash-left-top, .splash-right-top {
                width: 82px;
                top: 12px;
            }
            .splash-left-top { left: 6px; }
            .splash-right-top { right: 6px; }
            .splash-left-bottom, .splash-right-bottom {
                width: 74px;
                bottom: 14px;
            }
            .splash-left-bottom { left: 14px; }
            .splash-right-bottom { right: 14px; }
            .dot-1, .dot-3 { width: 18px; height: 18px; }
            .dot-2, .dot-4 { width: 14px; height: 14px; }
            .features-grid { 
                padding: 20px 5% 40px 5%; 
                grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
            }
            .flip-card {
                height: 215px;
            }
            .feature-img { 
                width: 95px; 
                height: 95px; 
            }
        }
    </style>
</head>
<body>

    <header>
        <!-- Logo Bulat Gambar (kawai.png) di Pojok Kiri Atas -->
        <div class="logo-container">
            <a href="index.php">
                <img src="kawai.png" alt="Logo BinIce" class="logo-img">
            </a>
        </div>
        
        <!-- Tombol Garis Tiga (Hamburger Menu) -->
        <button class="menu-toggle" id="menuToggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="navMenu">
            <a href="home.php" class="active">Home</a>
            <a href="produk.php">Products</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="contact.php">Contact</a>
            
            <div class="mobile-auth">
                <a href="keranjang.php" style="background: #fff; color: #d81b60; font-weight: bold;">
                    🛒 Keranjang <span class="cart-badge"><?= $total_keranjang ?></span>
                </a>
                <?php if(isset($_SESSION['admin_logged'])): ?>
                    <a href="admin_dashboard.php" style="background: #d81b60; color: white;">Admin Panel</a>
                <?php else: ?>
                    <a href="login.php" style="background: #d81b60; color: white;">🔑 Login Admin</a>
                <?php endif; ?>
            </div>
        </nav>

        <div class="nav-icons">
            <a href="keranjang.php" class="cart-btn" title="Keranjang">
                🛒 Keranjang <span class="cart-badge"><?= $total_keranjang ?></span>
            </a>
            
            <div class="nav-auth-desktop">
                <?php if(isset($_SESSION['admin_logged'])): ?>
                    <a href="admin_dashboard.php">Admin</a>
                <?php else: ?>
                    <a href="login.php">🔑 Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-text">
            <h1>Ice Creams Created With LOVE &amp; FUN!</h1>
            <p>Nikmati kelezatan biskuit gabin renyah yang dipadu dengan es krim lumer beraneka rasa buah segar pilihan terbaik.</p>
            <div class="hero-buttons">
                <a href="produk.php" class="btn-primary">Products →</a>
                <a href="contact.php" class="btn-secondary">contact →</a>
            </div>
        </div>
        <div class="hero-image-container">
            <div class="strawberry-splashes" aria-hidden="true">
                <!-- Cipratan Es Krim Strawberry Kiri Atas -->
                <svg class="splash-piece splash-left-top" viewBox="0 0 120 120">
                    <path d="M58 14 C72 24, 96 22, 102 44 C108 64, 90 78, 84 96 C66 102, 48 94, 34 84 C16 72, 14 48, 30 34 C44 22, 44 18, 58 14 Z" fill="#ff70a6"/>
                    <path d="M46 28 C58 34, 76 32, 82 48" fill="none" stroke="#ff9ec4" stroke-width="6" stroke-linecap="round"/>
                    <circle cx="54" cy="54" r="3.5" fill="#fff0f6"/>
                    <circle cx="72" cy="48" r="3" fill="#fff0f6"/>
                    <circle cx="66" cy="68" r="3.5" fill="#fff0f6"/>
                    <circle cx="46" cy="70" r="3" fill="#fff0f6"/>
                </svg>

                <!-- Cipratan Es Krim Strawberry Kanan Atas -->
                <svg class="splash-piece splash-right-top" viewBox="0 0 120 120">
                    <path d="M54 14 C68 18, 92 20, 100 42 C106 62, 92 80, 74 94 C54 104, 32 94, 24 72 C18 52, 36 38, 54 14 Z" fill="#ff548d"/>
                    <path d="M40 42 C54 34, 74 36, 82 52" fill="none" stroke="#ff9ec4" stroke-width="6" stroke-linecap="round"/>
                    <circle cx="62" cy="46" r="3.5" fill="#fff0f6"/>
                    <circle cx="78" cy="58" r="3" fill="#fff0f6"/>
                    <circle cx="54" cy="68" r="3.5" fill="#fff0f6"/>
                    <circle cx="70" cy="78" r="3" fill="#fff0f6"/>
                </svg>

                <!-- Percikan Es Krim Strawberry Kiri Bawah -->
                <svg class="splash-piece splash-left-bottom" viewBox="0 0 120 120">
                    <path d="M56 16 C74 28, 96 42, 92 68 C88 90, 62 102, 38 90 C18 80, 18 52, 34 38 C46 28, 48 22, 56 16 Z" fill="#ff85b3"/>
                    <circle cx="56" cy="58" r="3.5" fill="#ffffff"/>
                    <circle cx="72" cy="68" r="3" fill="#ffffff"/>
                    <circle cx="48" cy="76" r="3" fill="#ffffff"/>
                </svg>

                <!-- Percikan Es Krim Strawberry Kanan Bawah -->
                <svg class="splash-piece splash-right-bottom" viewBox="0 0 120 120">
                    <path d="M64 16 C86 24, 102 46, 96 72 C90 94, 62 102, 38 90 C20 80, 22 54, 38 40 C50 30, 54 22, 64 16 Z" fill="#f24b88"/>
                    <circle cx="54" cy="56" r="3.5" fill="#ffe6f0"/>
                    <circle cx="72" cy="64" r="3" fill="#ffe6f0"/>
                    <circle cx="62" cy="80" r="3" fill="#ffe6f0"/>
                </svg>

                <!-- Butiran Es Krim Strawberry Lebih Besar -->
                <span class="berry-dot dot-1"></span>
                <span class="berry-dot dot-2"></span>
                <span class="berry-dot dot-3"></span>
                <span class="berry-dot dot-4"></span>
            </div>

            <img src="fto.png" alt="BinIce Mascot" class="main-logo">
        </div>
    </section>

    <!-- 5 KARTU FITUR FLIP DI BAWAH -->
    <section class="features-grid">
        <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <h3>Natural Ingredients</h3>
                    <img src="fto2.png" alt="Natural Ingredients" class="feature-img">
                    <span class="flip-hint">Klik untuk lihat bahan ↻</span>
                </div>
                <div class="flip-card-back">
                    <span class="back-label">Ingredients</span>
                    <h4>Natural Ingredients</h4>
                    <p>Biskuit gabin pilihan, susu segar, krim manis, dan ekstrak buah asli tanpa bahan pengawet buatan.</p>
                    <span class="back-footer">Klik lagi untuk balik ↺</span>
                </div>
            </div>
        </div>

        <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <h3>Rich Flavour</h3>
                    <img src="anggur.png" alt="Rich Flavour" class="feature-img">
                    <span class="flip-hint">Klik untuk lihat bahan ↻</span>
                </div>
                <div class="flip-card-back">
                    <span class="back-label">Ingredients</span>
                    <h4>Rich Flavour</h4>
                    <p>Perpaduan es krim lembut aneka varian rasa buah segar, matcha, dan cokelat yang lumer serta kaya rasa.</p>
                    <span class="back-footer">Klik lagi untuk balik ↺</span>
                </div>
            </div>
        </div>

        <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <h3>No Artificial Colors</h3>
                    <img src="co.png" alt="No Artificial Colors" class="feature-img">
                    <span class="flip-hint">Klik untuk lihat bahan ↻</span>
                </div>
                <div class="flip-card-back">
                    <span class="back-label">Ingredients</span>
                    <h4>No Artificial Colors</h4>
                    <p>100% tanpa pewarna sintetis berbahaya—warna cantiknya alami dari sari buah dan bahan baku pilihan.</p>
                    <span class="back-footer">Klik lagi untuk balik ↺</span>
                </div>
            </div>
        </div>

        <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <h3>Best Cream Formula</h3>
                    <img src="mg.png" alt="Best Cream Formula" class="feature-img">
                    <span class="flip-hint">Klik untuk lihat bahan ↻</span>
                </div>
                <div class="flip-card-back">
                    <span class="back-label">Ingredients</span>
                    <h4>Best Cream Formula</h4>
                    <p>Racikan whipped cream, susu cair, dan gula pas khas BinIce agar lumer di mulut tapi tidak gampang meleleh.</p>
                    <span class="back-footer">Klik lagi untuk balik ↺</span>
                </div>
            </div>
        </div>

        <div class="flip-card" onclick="this.classList.toggle('flipped')">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <h3>Crispy Biscuit</h3>
                    <img src="fto.png" alt="Crispy Biscuit" class="feature-img">
                    <span class="flip-hint">Klik untuk lihat bahan ↻</span>
                </div>
                <div class="flip-card-back">
                    <span class="back-label">Ingredients</span>
                    <h4>Crispy Biscuit</h4>
                    <p>Roti/biskuit gabin gurih renyah yang dipanggang pas untuk mengapit isian es krim dingin yang lumer.</p>
                    <span class="back-footer">Klik lagi untuk balik ↺</span>
                </div>
            </div>
        </div>
    </section>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    </script>

</body>
</html>