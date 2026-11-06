<?php
session_start();
include 'koneksi.php';

// Logika ?beli lama sudah digantikan oleh detail_produk.php, 
// tapi bagian ini tetap dibiarkan aman jika ada fungsi lain yang memanggilnya.
$total_keranjang = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - BinIce</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Background Menggunakan Gambar */
        body { 
            background-image: url('betul3.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #593539; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            padding-bottom: 30px; 
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
        
        .logo { 
            font-size: 1.8rem; 
            font-weight: 800; 
            color: #d81b60; 
            letter-spacing: 1px; 
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8); 
        }

        /* Navigasi Utama Berbentuk Button / Kotak */
        nav { 
            display: flex; 
            gap: 12px; 
            align-items: center; 
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

        .mobile-auth { display: none; }
        
        main { flex: 1; padding: 20px 5% 40px 5%; max-width: 1300px; margin: 0 auto; width: 100%; }
        
        /* Judul Varian dan Harga */
        .page-title {
            text-align: center;
            margin: 10px 0 15px 0;
        }
        .page-title h2 {
            color: #800000;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            display: inline-block;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
        }
        .page-title h2::after {
            content: '';
            display: block;
            width: 100%;
            height: 3px;
            background-color: #800000;
            margin: 6px auto 0 auto;
            border-radius: 2px;
        }

        /* Teks Pemisah di Tengah (Ukuran Medium) */
        .section-divider {
            text-align: center;
            margin: 25px 0 10px 0;
        }
        .section-divider h3 {
            color: #800000;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            display: inline-block;
            position: relative;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
        }
        .section-divider h3::after {
            content: '';
            display: block;
            width: 100%;
            height: 2px;
            background-color: #800000;
            margin: 4px auto 0 auto;
            border-radius: 2px;
        }

        .row-section {
            margin-bottom: 15px;
        }

        .catalog-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .scroll-container {
            width: 100%;
            overflow-x: auto;
            padding: 10px 10px 25px 10px;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            scrollbar-color: #d81b60 rgba(255, 182, 193, 0.3);
        }

        .scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        .scroll-container::-webkit-scrollbar-track {
            background: rgba(255, 182, 193, 0.3);
            border-radius: 10px;
        }
        .scroll-container::-webkit-scrollbar-thumb {
            background: #d81b60;
            border-radius: 10px;
        }

        .grid-row { 
            display: flex; 
            gap: 25px; 
            width: max-content;
            padding-top: 35px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .scroll-btn {
            background-color: #800000;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: 0.3s;
            z-index: 10;
            flex-shrink: 0;
        }
        .scroll-btn:hover {
            background-color: #500000;
            transform: scale(1.1);
        }
        .scroll-btn.left { margin-right: 10px; }
        .scroll-btn.right { margin-left: 10px; }

        .card { 
            background: linear-gradient(180deg, #ffb6c1 0%, #ff8da1 100%);
            border-radius: 30px; 
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.15); 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
            transition: transform 0.3s; 
            position: relative;
            border: 2px solid rgba(255, 255, 255, 0.7);
            width: 240px;
            padding: 20px 16px 16px 16px;
        }
        .card:hover { transform: translateY(-5px); }

        .img-circle-container {
            width: 110px;
            height: 110px;
            background-color: #800000;
            border-radius: 50%;
            margin: -65px auto 10px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 4px solid #fff;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }
        .card img { 
            width: 85%; 
            height: 85%; 
            object-fit: cover; 
            border-radius: 8px;
        }

        .card-body { 
            padding: 0 0 10px 0; 
            position: relative; 
        }
        
        .card-title { 
            font-size: 1rem; 
            color: #600000; 
            font-weight: 700; 
            min-height: 38px; 
        }

        .card-desc { 
            display: none; 
        }

        .heart-icon {
            position: absolute;
            right: 0;
            top: 0;
            color: #800000;
            font-size: 1.1rem;
        }

        .card-footer-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-price { 
            font-size: 1.2rem; 
            font-weight: 800; 
            color: #800000; 
        }

        .btn-beli { 
            background: #800000; 
            color: white; 
            text-align: center; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: bold; 
            padding: 7px 16px;
            font-size: 0.9rem;
            letter-spacing: 1px;
            transition: 0.3s; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-beli:hover { background: #500000; }
        
        footer { background: rgba(255, 182, 193, 0.9); color: #593539; text-align: center; padding: 15px; margin-top: 20px; font-size: 0.9rem; font-weight: 600; border-top: 1px solid rgba(255,255,255,0.6); }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media(max-width: 900px) { 
            header { padding: 15px 5%; } 
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
            .scroll-btn { display: none; }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">🍦 BinIce</div>
        
        <!-- Tombol Garis Tiga (Hamburger Menu) -->
        <button class="menu-toggle" id="menuToggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="navMenu">
            <a href="home.php">Home</a>
            <a href="produk.php" class="active">Products</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="contact.php">Contact</a>
            
            <!-- Menu tambahan khusus versi HP (masuk dalam dropdown garis 3) -->
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

    <main>
        <div class="page-title">
            <h2>VARIAN DAN HARGA</h2>
        </div>

        <!-- BARIS ATAS (KHUSUS LARGE) -->
        <div class="row-section">
            <div class="catalog-wrapper">
                <button class="scroll-btn left" onclick="scrollRow('scrollContainer1', -300)">&#10094;</button>
                <div class="scroll-container" id="scrollContainer1">
                    <div class="grid-row">
                        <?php 
                        $query_large = mysqli_query($koneksi, "SELECT * FROM products WHERE ukuran = 'large' ORDER BY id DESC");
                        while ($p = mysqli_fetch_assoc($query_large)): 
                            $harga_tampil = ($p['harga'] >= 1000) ? ($p['harga'] / 1000) . 'K' : 'Rp ' . number_format($p['harga'], 0, ',', '.');
                        ?>
                        <div class="card">
                            <div>
                                <div class="img-circle-container">
                                    <img src="<?= !empty($p['gambar']) ? 'img/' . htmlspecialchars($p['gambar']) : 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600' ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
                                </div>
                                <div class="card-body">
                                    <div class="card-title"><?= htmlspecialchars($p['nama']) ?></div>
                                    <div class="heart-icon">♥</div>
                                    <div class="card-desc"><?= htmlspecialchars($p['deskripsi']) ?></div>
                                </div>
                            </div>
                            <div class="card-footer-flex">
                                <div class="card-price"><?= $harga_tampil ?></div>
                                <!-- Diarahkan ke detail_produk.php agar bisa pilih ukuran, cek stok & atur jumlah -->
                                <a href="detail_produk.php?id=<?= $p['id'] ?>" class="btn-beli">+</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <button class="scroll-btn right" onclick="scrollRow('scrollContainer1', 300)">&#10095;</button>
            </div>
        </div>

        <!-- TEKS PEMISAH DI TENGAH -->
        <div class="section-divider">
            <h3>Ukuran Medium</h3>
        </div>

        <!-- BARIS BAWAH (KHUSUS MEDIUM) -->
        <div class="row-section">
            <div class="catalog-wrapper">
                <button class="scroll-btn left" onclick="scrollRow('scrollContainer2', -300)">&#10094;</button>
                <div class="scroll-container" id="scrollContainer2">
                    <div class="grid-row">
                        <?php 
                        $query_medium = mysqli_query($koneksi, "SELECT * FROM products WHERE ukuran = 'medium' ORDER BY id DESC");
                        while ($p = mysqli_fetch_assoc($query_medium)): 
                            $harga_tampil = ($p['harga'] >= 1000) ? ($p['harga'] / 1000) . 'K' : 'Rp ' . number_format($p['harga'], 0, ',', '.');
                        ?>
                        <div class="card">
                            <div>
                                <div class="img-circle-container">
                                    <img src="<?= !empty($p['gambar']) ? 'img/' . htmlspecialchars($p['gambar']) : 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600' ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
                                </div>
                                <div class="card-body">
                                    <div class="card-title"><?= htmlspecialchars($p['nama']) ?></div>
                                    <div class="heart-icon">♥</div>
                                    <div class="card-desc"><?= htmlspecialchars($p['deskripsi']) ?></div>
                                </div>
                            </div>
                            <div class="card-footer-flex">
                                <div class="card-price"><?= $harga_tampil ?></div>
                                <!-- Diarahkan ke detail_produk.php agar bisa pilih ukuran, cek stok & atur jumlah -->
                                <a href="detail_produk.php?id=<?= $p['id'] ?>" class="btn-beli">+</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <button class="scroll-btn right" onclick="scrollRow('scrollContainer2', 300)">&#10095;</button>
            </div>
        </div>

    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>

    <script>
        function scrollRow(containerId, amount) {
            document.getElementById(containerId).scrollBy({
                left: amount,
                behavior: 'smooth'
            });
        }

        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    </script>

</body>
</html>