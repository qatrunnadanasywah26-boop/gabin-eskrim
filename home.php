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
        .hero-text { flex: 1; max-width: 500px; z-index: 2; }
        .hero-text h1 { font-size: 3.5rem; font-weight: 800; color: #c2185b; line-height: 1.2; margin-bottom: 20px; text-shadow: 1px 1px 2px rgba(255,255,255,0.8); }
        .hero-text p { font-size: 1rem; color: #593539; margin-bottom: 30px; line-height: 1.6; font-weight: 600; text-shadow: 1px 1px 2px rgba(255,255,255,0.8); }
        .hero-buttons { display: flex; gap: 15px; }
        .btn-primary { background-color: #d81b60; color: white; padding: 12px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 15px rgba(216, 27, 96, 0.3); transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background-color: #ad1457; transform: translateY(-2px); }
        .btn-secondary { background-color: rgba(255, 255, 255, 0.85); color: #c2185b; padding: 12px 25px; border-radius: 30px; text-decoration: none; font-weight: bold; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .btn-secondary:hover { background-color: white; }
        
        .hero-image-container { flex: 1; display: flex; justify-content: center; align-items: center; position: relative; min-height: 380px; }
        
        @keyframes floatImage { 
            0% { transform: translateY(0px); } 
            50% { transform: translateY(-10px); } 
            100% { transform: translateY(0px); } 
        }

        .main-logo { 
            width: 340px; 
            height: auto; 
            object-fit: contain; 
            position: relative; 
            z-index: 2; 
            animation: floatImage 3s ease-in-out infinite; 
        }
        
        /* 5 Kartu Fitur di Bawah */
        .features-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); 
            gap: 20px; 
            padding: 10px 8% 60px 8%; 
        }
        .feature-card { 
            background: rgba(255, 255, 255, 0.75); 
            backdrop-filter: blur(5px);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.08); 
            padding: 20px 15px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            text-align: center; 
            border: 1px solid rgba(255, 255, 255, 0.9);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 12px 25px rgba(216, 27, 96, 0.2);
            border-color: #d81b60;
        }
        .feature-card h3 { 
            font-size: 1.05rem; 
            font-weight: 800; 
            color: #c2185b; 
            margin-bottom: 12px; 
        }

        .feature-img {
            width: 130px;
            height: 130px;
            object-fit: contain;
            animation: floatImage 3.5s ease-in-out infinite;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-img {
            transform: scale(1.12);
        }

        /* Styling Modal / Popup Detail Ingredients */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-box {
            background: #fff0f5;
            border-radius: 25px;
            padding: 30px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.25);
            border: 3px solid #d81b60;
            animation: slideDown 0.3s ease-out forwards;
            position: relative;
        }

        .modal-box img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .modal-box h2 {
            color: #800000;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .modal-box p {
            color: #593539;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .modal-close-btn {
            background: #d81b60;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(216, 27, 96, 0.3);
        }

        .modal-close-btn:hover {
            background: #ad1457;
            transform: scale(1.05);
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
            .main-logo { 
                width: 260px; 
            }
            .features-grid { 
                padding: 20px 5% 40px 5%; 
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
            .feature-img { 
                width: 100px; 
                height: 100px; 
            }
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
            <h1>Ice Creams Created With LOVE & FUN!</h1>
            <p>Nikmati kelezatan biskuit gabin renyah yang dipadu dengan es krim lumer beraneka rasa buah segar pilihan terbaik.</p>
            <div class="hero-buttons">
                <a href="produk.php" class="btn-primary">Products →</a>
                <a href="produk.php" class="btn-secondary">Explore Menu →</a>
            </div>
        </div>
        <div class="hero-image-container">
            <img src="fto.png" alt="BinIce Mascot" class="main-logo">
        </div>
    </section>

    <!-- 5 KARTU FITUR DI BAWAH (DILENGKAPI DATA INGREDIENTS) -->
    <section class="features-grid">
        <div class="feature-card" onclick="openModal('Natural Ingredients', 'fto2.png', 'Dibuat dari bahan-bahan alami pilihan tanpa pengawet buatan. Menggunakan biskuit gabin berkualitas tinggi dan ekstrak buah segar untuk menjaga kebaikan nutrisinya di setiap gigitan!')">
            <h3>Natural Ingredients</h3>
            <img src="fto2.png" alt="Natural Ingredients" class="feature-img">
        </div>
        <div class="feature-card" onclick="openModal('Rich Flavour', 'anggur.png', 'Menghadirkan sensasi rasa yang kaya dan autentik. Perpaduan es krim lembut dengan aneka buah pilihan memberikan ledakan rasa manis yang pas di lidah.')">
            <h3>Rich Flavour</h3>
            <img src="anggur.png" alt="Rich Flavour" class="feature-img">
        </div>
        <div class="feature-card" onclick="openModal('No Artificial Colors', 'co.png', '100% Bebas pewarna buatan! Warna menarik pada es krim BinIce murni berasal dari sari buah alami dan bahan berkualitas aman yang menyehatkan.')">
            <h3>No Artificial Colors</h3>
            <img src="co.png" alt="No Artificial Colors" class="feature-img">
        </div>
        <div class="feature-card" onclick="openModal('Best Cream Formula', 'mg.png', 'Formula krim rahasia khas BinIce yang diracik khusus agar teksturnya sangat lembut, lumer di mulut, namun tidak mudah cair saat dinikmati.')">
            <h3>Best Cream Formula</h3>
            <img src="mg.png" alt="Best Cream Formula" class="feature-img">
        </div>
        <!-- Kartu Kelima Tambahan Baru -->
        <div class="feature-card" onclick="openModal('Crispy Biscuit', 'kawai.png', 'Lapisan biskuit gabin gurih di luar yang dipanggang dengan tingkat renyah sempurna, menciptakan kombinasi unik ice cream lumer dan biskuit yang renyah!')">
            <h3>Crispy Biscuit</h3>
            <img src="fto.png" alt="Crispy Biscuit" class="feature-img">
        </div>
    </section>

    <!-- MODAL POPUP DETAIL INGREDIENTS -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            <img id="modalImg" src="" alt="Icon">
            <h2 id="modalTitle">Judul</h2>
            <p id="modalDesc">Deskripsi detail bahan dan keunggulan...</p>
            <button class="modal-close-btn" onclick="closeModal()">Tutup</button>
        </div>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Fungsi JavaScript untuk Mengatur Modal Popup
        function openModal(title, imgSrc, desc) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalImg').src = imgSrc;
            document.getElementById('modalDesc').innerText = desc;
            document.getElementById('modalOverlay').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
        }

        // Tutup modal jika user klik di luar kotak popup
        window.onclick = function(event) {
            const overlay = document.getElementById('modalOverlay');
            if (event.target == overlay) {
                closeModal();
            }
        }
    </script>

</body>
</html>