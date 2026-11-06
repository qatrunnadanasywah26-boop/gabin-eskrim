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
            background-image: url('bgbaru.jpg'); /* Ganti dengan nama file foto background kamu */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #593539; 
            overflow-x: hidden; 
            min-height: 100vh;
        }

        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: transparent; }
        .logo { font-size: 1.8rem; font-weight: 800; color: #d81b60; letter-spacing: 1px; text-shadow: 1px 1px 2px rgba(255,255,255,0.8); }
        nav { display: flex; gap: 30px; align-items: center; }
        nav a { text-decoration: none; color: #593539; font-weight: 600; font-size: 1rem; transition: 0.3s; }
        nav a:hover, nav a.active { color: #d81b60; }
        .nav-icons { display: flex; gap: 20px; font-size: 1.2rem; align-items: center; }
        .nav-icons a { text-decoration: none; color: #593539; position: relative; }
        .cart-badge { position: absolute; top: -8px; right: -10px; background: #d81b60; color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 50%; font-weight: bold; }
        
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

        /* Maskot Utama (Tanpa efek multiply supaya tidak saling menimpa/glitch) */
        .main-logo { 
            width: 340px; 
            height: auto; 
            object-fit: contain; 
            position: relative; 
            z-index: 2; 
            animation: floatImage 3s ease-in-out infinite; 
        }
        
        .features-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
            gap: 25px; 
            padding: 10px 8% 60px 8%; 
        }
        .feature-card { 
            background: rgba(255, 255, 255, 0.6); 
            backdrop-filter: blur(5px);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.08); 
            padding: 20px 15px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            text-align: center; 
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .feature-card h3 { 
            font-size: 1.1rem; 
            font-weight: 800; 
            color: #c2185b; 
            margin-bottom: 15px; 
        }

        .feature-img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            animation: floatImage 3.5s ease-in-out infinite;
            transition: transform 0.3s ease;
        }

        .feature-card:nth-child(2) .feature-img { animation-duration: 3.2s; }
        .feature-card:nth-child(3) .feature-img { animation-duration: 3.8s; }
        .feature-card:nth-child(4) .feature-img { animation-duration: 3.4s; }

        .feature-card:hover .feature-img {
            transform: scale(1.1);
        }

        @media(max-width: 900px) {
            header { flex-direction: column; gap: 15px; padding: 15px; }
            nav { gap: 15px; }
            .hero-section { flex-direction: column; text-align: center; padding: 20px 5%; }
            .hero-text { max-width: 100%; margin-bottom: 30px; }
            .hero-text h1 { font-size: 2.3rem; }
            .hero-buttons { justify-content: center; }
            .main-logo { width: 260px; }
            .features-grid { padding: 20px 5% 40px 5%; }
            .feature-img { width: 120px; height: 120px; }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">🍦 BinIce</div>
        <nav>
            <a href="home.php" class="active">Home</a>
            <a href="produk.php">Products</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="contact.php">Contact</a>
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

    <!-- KARTU FITUR BAWAH -->
    <section class="features-grid">
        <div class="feature-card">
            <h3>Natural Ingredients</h3>
            <img src="fto2.png" alt="Natural Ingredients" class="feature-img">
        </div>
        <div class="feature-card">
            <h3>Rich Flavour</h3>
            <img src="eskrim.png" alt="Rich Flavour" class="feature-img">
        </div>
        <div class="feature-card">
            <h3>No Artificial Colors</h3>
            <img src="anggur.png" alt="No Artificial Colors" class="feature-img">
        </div>
        <div class="feature-card">
            <h3>Best Cream Formula</h3>
            <img src="mangga.png" alt="Best Cream Formula" class="feature-img">
        </div>
    </section>

</body>
</html>