<?php
session_start();
$total_keranjang = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
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

        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: transparent; }
        .logo { font-size: 1.8rem; font-weight: 800; color: #d81b60; letter-spacing: 1px; text-shadow: 1px 1px 2px rgba(255,255,255,0.8); }
        nav { display: flex; gap: 30px; align-items: center; }
        nav a { text-decoration: none; color: #593539; font-weight: 600; font-size: 1rem; transition: 0.3s; }
        nav a:hover, nav a.active { color: #d81b60; }
        .nav-icons { display: flex; gap: 20px; font-size: 1.2rem; align-items: center; }
        .nav-icons a { text-decoration: none; color: #593539; position: relative; }
        .cart-badge { position: absolute; top: -8px; right: -10px; background: #d81b60; color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 50%; font-weight: bold; }

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

        /* Styling Tombol Pill dengan Ikon Hati sesuai contoh */
        .contact-btn {
            display: flex;
            align-items: center;
            background: rgba(255, 182, 193, 0.85); /* Pink pastel lembut */
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

        @media(max-width: 768px) {
            header { flex-direction: column; gap: 15px; padding: 15px; }
            nav { gap: 15px; }
            .contact-title { font-size: 2rem; }
            .contact-btn { font-size: 1rem; padding: 12px 20px; }
        }
    </style>
</head>
<body>

    <!-- Header / Navbar dengan tambahan menu Contact -->
    <header>
        <div class="logo">🍦 BinIce</div>
        <nav>
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
             <a href="keranjang.php">Keranjang</a>
             <a href="contact.php" class="active">Contact</a>
        </nav>
        <div class="nav-icons">
            <a href="keranjang.php" title="Keranjang">
                🛒 <span class="cart-badge"><?= $total_keranjang ?></span>
            </a>
            <?php if(isset($_SESSION['admin_logged'])): ?>
                <a href="admin_dashboard.php" style="font-size: 0.9rem; background: #d81b60; color: white; padding: 5px 12px; border-radius: 15px;">Admin</a>
            <?php else: ?>
                <a href="login.php" style="font-size: 0.9rem;">🔑 Login</a>
            <?php endif; ?>
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
                <span class="contact-text">Instagram @binice.official 📸</span>
            </a>

            <!-- Tombol TikTok -->
            <a href="https://www.tiktok.com/@gemasgabin?lang=en" target="_blank" class="contact-btn">
                <span class="heart-icon">♥</span>
                <span class="contact-text">TikTok @binIce_eskrim 🎵</span>
            </a>
        </div>
    </section>

    <footer>
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>

</body>
</html>