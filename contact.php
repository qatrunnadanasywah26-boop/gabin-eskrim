<?php
session_start();

$total_keranjang = 0;
if (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $arr_ukuran) {
        if (is_array($arr_ukuran)) {
            foreach ($arr_ukuran as $ukuran => $jumlah) {
                $total_keranjang += (int)$jumlah;
            }
        } else {
            $total_keranjang += (int)$arr_ukuran;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - BinIce</title>
    <!-- Memuat Google Fonts: Fredoka dan Quicksand -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Quicksand', sans-serif; }
        
        body { 
            background-image: url('r.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #593539; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden; 
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
        
        /* Styling Logo Bulat Menggunakan kawai.png */
        .logo-container {
            display: flex;
            align-items: center;
            flex: 1; /* Memberikan ruang seimbang di kiri */
        }

        .logo-img {
            width: 80px;  
            height: 80px; 
            object-fit: cover; 
            border-radius: 50%; 
            border: 3px solid #fff; 
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.25); 
            transition: transform 0.3s ease;
            display: block;
        }
        .logo-img:hover {
            transform: scale(1.08);
        }

        /* Navigasi Utama Berbentuk Button / Kotak Diposisikan Tepat di Tengah */
        nav { 
            display: flex; 
            gap: 12px; 
            align-items: center; 
            justify-content: center;
            flex: 2; /* Menempatkan nav di tengah secara presisi */
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
            flex: 1; /* Ruang seimbang di kanan */
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

        /* Tombol Hamburger (Garis Tiga) untuk HP */
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

        .mobile-auth { display: none; }

        /* Konten Halaman Kontak */
        .contact-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .contact-title {
            font-family: 'Fredoka', cursive, sans-serif;
            font-size: 2.5rem;
            color: #800000;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(255,255,255,0.9);
            text-align: center;
        }

        .contact-subtitle {
            font-size: 1.1rem;
            color: #593539;
            margin-bottom: 40px;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
            text-align: center;
        }

        /* Container Tombol Daftar Kontak */
        .contact-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 450px;
        }

        /* Styling Tombol Pill dengan Ikon Hati */
        .contact-btn {
            display: flex;
            align-items: center;
            background: rgba(255, 182, 193, 0.85);
            backdrop-filter: blur(5px);
            border: 2px solid #fff;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            color: #800000;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 8px 20px rgba(216, 27, 96, 0.15);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .contact-btn:hover {
            background: #d81b60;
            color: white;
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 25px rgba(216, 27, 96, 0.3);
        }

        /* Ikon Hati di sebelah kiri */
        .heart-icon {
            font-size: 1.4rem;
            color: #d81b60;
            margin-right: 20px;
            transition: transform 0.3s ease;
        }

        .contact-btn:hover .heart-icon {
            color: white;
            transform: scale(1.2);
        }

        .contact-text {
            flex: 1;
            text-align: center;
            letter-spacing: 0.5px;
        }

        footer { 
            background: rgba(173, 20, 87, 0.85); 
            backdrop-filter: blur(5px);
            color: white; 
            text-align: center; 
            padding: 20px; 
            font-size: 0.9rem; 
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media(max-width: 900px) {
            header { padding: 15px 5%; justify-content: space-between; }
            .logo-container, .nav-icons { flex: unset; }
            .nav-icons { display: none; }
            .menu-toggle { display: flex; }
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
            nav.active { display: flex; }
            .contact-title { font-size: 2rem; }
            .contact-btn { font-size: 1rem; padding: 12px 20px; }
        }
    </style>
</head>
<body>

    <!-- Header / Navbar dengan Posisi Tengah yang Rapi -->
    <header>
        <div class="logo-container">
            <a href="index.php">
                <img src="kawai.png" alt="Logo BinIce" class="logo-img">
            </a>
        </div>
        
        <!-- Tombol Garis Tiga (Hamburger Menu) untuk HP -->
        <button class="menu-toggle" id="menuToggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="navMenu">
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="contact.php" class="active">Contact</a>
            
            <!-- Menu tambahan khusus versi HP di dalam dropdown -->
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

        <!-- Tampilan Icon / Tombol di Desktop -->
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

    <!-- Bagian Utama Kontak -->
    <section class="contact-section">
        <h1 class="contact-title">Hubungi Kami 💕</h1>
        <p class="contact-subtitle">Pilih salah satu platform di bawah untuk terhubung dengan BinIce!</p>

        <div class="contact-list">
            <!-- Tombol WhatsApp dengan Nomor Admin yang Benar -->
            <a href="https://wa.me/6289530628664?text=Halo%20Admin%20BinIce,%20saya%20mau%20tanya%20tentang%20produk%20gabin%20es%20krimnya" target="_blank" class="contact-btn">
                <span class="heart-icon">♥</span>
                <span class="contact-text">WhatsApp Admin 📱</span>
            </a>

            <!-- Tombol Instagram -->
            <a href="https://www.instagram.com/gemasgabin_/" target="_blank" class="contact-btn">
                <span class="heart-icon">♥</span>
                <span class="contact-text">Instagram @gemasgabin_ 📸</span>
            </a>

            <!-- Tombol TikTok -->
            <a href="https://www.tiktok.com/@gemasgabin?lang=en" target="_blank" class="contact-btn">
                <span class="heart-icon">♥</span>
                <span class="contact-text">TikTok @gemasgabin 🎵</span>
            </a>
        </div>
    </section>

    <footer>
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>

    <!-- Script JavaScript untuk Tombol Hamburger -->
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