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
    <title>Katalog Produk - BinIce</title>
    <!-- Mengimpor Google Fonts 'Playfair Display' untuk huruf judul yang sangat elegan dan cantik -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
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
        
        /* Styling Logo Bulat Menggunakan kawai.png di Pojok Kiri Atas */
        .logo-container {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .logo-img {
            width: 65px;  
            height: 65px; 
            object-fit: cover; 
            border-radius: 50%; 
            border: 3px solid #fff; 
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.25); 
            transition: transform 0.3s ease;
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

        .mobile-auth { display: none; }
        
        /* Main Layout */
        main { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            padding: 20px 20px 60px 20px; 
            max-width: 1000px; 
            margin: 0 auto; 
            width: 100%; 
        }
        
        /* Judul Katalog Produk dengan Font Sangat Cantik & Elegan */
        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .page-title h2 {
            font-family: 'Playfair Display', serif;
            color: #800000;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            position: relative;
            display: inline-block;
            text-shadow: 2px 2px 4px rgba(255,255,255,0.9);
        }
        .page-title h2::after {
            content: '';
            display: block;
            width: 70%;
            height: 3px;
            background-color: #d81b60;
            margin: 8px auto 0 auto;
            border-radius: 2px;
        }

        /* Container Carousel */
        .carousel-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 860px; 
            margin: 0 auto;
        }

        /* Efek Gradasi / Blur di Sisi Kiri & Kanan */
        .carousel-container::before,
        .carousel-container::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100px;
            z-index: 5;
            pointer-events: none;
        }
        .carousel-container::before {
            left: -10px;
            background: linear-gradient(to right, rgba(255,255,255,0.75), transparent);
            backdrop-filter: blur(2px);
            border-radius: 35px 0 0 35px;
        }
        .carousel-container::after {
            right: -10px;
            background: linear-gradient(to left, rgba(255,255,255,0.75), transparent);
            backdrop-filter: blur(2px);
            border-radius: 0 35px 35px 0;
        }

        .scroll-wrapper {
            width: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth;
            padding: 30px 10px;
        }

        .grid-row { 
            display: flex; 
            gap: 25px; 
            width: max-content;
            padding-top: 35px;
            transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        /* Tombol Geser Kiri / Kanan */
        .scroll-btn {
            background: linear-gradient(135deg, #800000 0%, #b30000 100%);
            color: white;
            border: 2px solid #fff;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.4rem;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(128,0,0,0.35);
            transition: 0.3s;
            position: absolute;
            z-index: 10;
        }
        .scroll-btn:hover {
            background: linear-gradient(135deg, #500000 0%, #800000 100%);
            transform: scale(1.12);
            box-shadow: 0 8px 25px rgba(128,0,0,0.5);
        }
        .scroll-btn.left { left: -65px; }
        .scroll-btn.right { right: -65px; }

        /* Desain Kartu Produk yang Sangat Simetris, Rapi, & Elegan */
        .card { 
            background: linear-gradient(145deg, #ffffff 0%, #fff0f3 100%);
            border-radius: 35px; 
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.12); 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
            transition: all 0.4s ease; 
            position: relative;
            border: 3px solid rgba(255, 182, 193, 0.6);
            width: 260px; 
            height: 380px; /* Tinggi kartu dikunci presisi agar simetris sempurna */
            padding: 25px 20px 22px 20px;
            opacity: 0.35;
            filter: blur(3px);
            transform: scale(0.9);
        }
        
        /* Kartu yang aktif di tengah */
        .card.active {
            opacity: 1;
            filter: blur(0);
            transform: scale(1.06); 
            box-shadow: 0 20px 45px rgba(216, 27, 96, 0.25);
            border: 3px solid #d81b60;
            z-index: 2;
            background: linear-gradient(145deg, #ffffff 0%, #ffe6ee 100%);
        }

        /* Ikon Love di Atas Tengah Kartu */
        .heart-icon {
            text-align: center;
            color: #d81b60;
            font-size: 1.3rem;
            margin-bottom: 2px;
            text-shadow: 0 2px 5px rgba(216, 27, 96, 0.3);
        }

        /* Container Bulat Foto: GAMBAR FULL DI DALAM LINGKARAN (Tanpa kotak di dalam) */
        .img-circle-container {
            width: 120px;
            height: 120px;
            background-color: #800000;
            border-radius: 50%;
            margin: 0 auto 15px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden; /* Mengunci gambar agar mengikuti bentuk lingkaran penuh */
            border: 4px solid #fff;
            box-shadow: 0 8px 20px rgba(128,0,0,0.2);
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .card.active .img-circle-container {
            transform: scale(1.05);
        }

        /* Gambar di dalam lingkaran dibuat 100% full memenuhi lingkaran */
        .card img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; /* Membuat gambar mengisi penuh tanpa distorsi/kotak */
            border-radius: 50%;
        }

        .card-body { 
            padding: 0; 
            position: relative; 
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        
        /* Judul Nama Produk dengan Font Cantik & Simetris */
        .card-title { 
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem; 
            color: #800000; 
            font-weight: 700; 
            line-height: 1.25;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 48px; /* Ketinggian teks judul disamakan rata */
        }

        /* Label Teks Manis di Bawah Nama */
        .card-subtitle {
            font-size: 0.78rem;
            color: #d81b60;
            font-weight: 600;
            font-style: italic;
            background: rgba(255, 182, 193, 0.3);
            padding: 3px 10px;
            border-radius: 12px;
            display: inline-block;
            margin: 0 auto;
        }

        .card-desc { 
            display: none; 
        }

        /* Bagian Bawah Kartu (Harga & Tombol) Diselaraskan Rata Simetris */
        .card-footer-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 12px;
            border-top: 2px dashed rgba(216, 27, 96, 0.2);
        }

        .card-price { 
            font-size: 1.25rem; 
            font-weight: 800; 
            color: #d81b60; 
        }

        .btn-beli { 
            background: linear-gradient(135deg, #d81b60 0%, #ad1457 100%); 
            color: white; 
            text-align: center; 
            border-radius: 12px; 
            text-decoration: none; 
            font-weight: bold; 
            padding: 8px 18px;
            font-size: 0.95rem;
            letter-spacing: 1px;
            transition: 0.3s; 
            box-shadow: 0 4px 12px rgba(216, 27, 96, 0.3);
        }
        .btn-beli:hover { 
            background: linear-gradient(135deg, #ad1457 0%, #800000 100%);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(216, 27, 96, 0.4);
        }
        
        footer { background: rgba(255, 182, 193, 0.9); color: #593539; text-align: center; padding: 15px; margin-top: auto; font-size: 0.9rem; font-weight: 600; border-top: 1px solid rgba(255,255,255,0.6); }
        
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
            .scroll-btn.left { left: -30px; width: 40px; height: 40px; font-size: 1.1rem; }
            .scroll-btn.right { right: -30px; width: 40px; height: 40px; font-size: 1.1rem; }
            .carousel-container { max-width: 280px; }
        }
    </style>
</head>
<body>

    <header>
        <!-- Logo Bulat Gambar (kawai.png) di Pojok Kiri Atas -->
        <div class="logo-container">
            <a href="home.php">
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
            <h2>Katalog Produk</h2>
        </div>

        <!-- TAMPILAN CAROUSEL SIMETRIS -->
        <div class="carousel-container">
            <button class="scroll-btn left" onclick="slideCard(-1)">&#10094;</button>
            
            <div class="scroll-wrapper" id="scrollWrapper">
                <div class="grid-row" id="gridRow">
                    <?php 
                    $query_produk = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC");
                    
                    $produk_array = [];
                    while ($row = mysqli_fetch_assoc($query_produk)) {
                        $produk_array[] = $row;
                    }
                    
                    $initial_index = floor(count($produk_array) / 2);

                    foreach ($produk_array as $index => $p): 
                        $harga_tampil = ($p['harga'] >= 1000) ? ($p['harga'] / 1000) . 'K' : 'Rp ' . number_format($p['harga'], 0, ',', '.');
                    ?>
                    <div class="card" data-index="<?= $index ?>">
                        <div>
                            <div class="heart-icon">♥</div>
                            <!-- Foto di dalam bulatan dibuat FULL melingkar tanpa kotak putih di dalamnya -->
                            <div class="img-circle-container">
                                <img src="<?= !empty($p['gambar']) ? 'img/' . htmlspecialchars($p['gambar']) : 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600' ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
                            </div>
                            <div class="card-body">
                                <div class="card-title"><?= htmlspecialchars($p['nama']) ?></div>
                                <div class="card-subtitle">✨ Manis & Lumer di Mulut! 🍦</div>
                                <div class="card-desc"><?= htmlspecialchars($p['deskripsi']) ?></div>
                            </div>
                        </div>
                        <div class="card-footer-flex">
                            <div class="card-price"><?= $harga_tampil ?></div>
                            <a href="detail_produk.php?id=<?= $p['id'] ?>" class="btn-beli">+</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="scroll-btn right" onclick="slideCard(1)">&#10095;</button>
        </div>

    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        const cards = document.querySelectorAll('.card');
        const gridRow = document.getElementById('gridRow');
        
        let currentIndex = <?= $initial_index ?>;

        function updateActiveCard() {
            cards.forEach((card, index) => {
                if (index === currentIndex) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            });

            if (cards.length > 0) {
                const cardWidth = cards[0].offsetWidth + 25; 
                const offset = (gridRow.parentElement.offsetWidth / 2) - (cardWidth / 2) - (currentIndex * cardWidth);
                gridRow.style.transform = `translateX(${offset}px)`;
            }
        }

        function slideCard(direction) {
            currentIndex += direction;
            if (currentIndex < 0) {
                currentIndex = 0;
            } else if (currentIndex >= cards.length) {
                currentIndex = cards.length - 1;
            }
            updateActiveCard();
        }

        window.addEventListener('load', () => {
            updateActiveCard();
        });

        window.addEventListener('resize', () => {
            updateActiveCard();
        });
    </script>

</body>
</html>