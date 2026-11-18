<?php
session_start();
include 'koneksi.php';

// Harga fix sesuai ketentuan
$harga_medium = 6000;

// Logika Tambah / Kurang Jumlah Item di Keranjang
if (isset($_GET['aksi']) && isset($_GET['id']) && isset($_GET['ukuran'])) {
    $id_item = $_GET['id'];
    $ukuran_item = $_GET['ukuran'];
    $aksi = $_GET['aksi'];

    if ($aksi == 'tambah') {
        $_SESSION['keranjang'][$id_item][$ukuran_item] += 1;
    } elseif ($aksi == 'kurang') {
        if (isset($_SESSION['keranjang'][$id_item][$ukuran_item])) {
            $_SESSION['keranjang'][$id_item][$ukuran_item] -= 1;
            // Jika jumlahnya jadi 0 atau kurang, hapus item tersebut
            if ($_SESSION['keranjang'][$id_item][$ukuran_item] <= 0) {
                unset($_SESSION['keranjang'][$id_item][$ukuran_item]);
            }
        }
    }

    // Jika array ukuran kosong, hapus indeks ID produknya
    if (isset($_SESSION['keranjang'][$id_item]) && empty($_SESSION['keranjang'][$id_item])) {
        unset($_SESSION['keranjang'][$id_item]);
    }

    header('Location: keranjang.php');
    exit;
}

// Logika Hapus item dari keranjang berdasarkan ID dan Ukuran
if (isset($_GET['hapus_id']) && isset($_GET['ukuran'])) {
    $id_hapus = $_GET['hapus_id'];
    $ukuran_hapus = $_GET['ukuran'];
    
    unset($_SESSION['keranjang'][$id_hapus][$ukuran_hapus]);
    
    if (empty($_SESSION['keranjang'][$id_hapus])) {
        unset($_SESSION['keranjang'][$id_hapus]);
    }
    
    header('Location: keranjang.php');
    exit;
}

// Hitung total item di keranjang untuk badge navbar
$total_keranjang = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $arr_ukuran) {
        foreach ($arr_ukuran as $ukuran => $jumlah) {
            $total_keranjang += $jumlah;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - BinIce</title>
    <!-- Import Font Estetik & Lucu dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        html, body {
            height: 100%;
        }

        body { 
            background-image: url('bg kr2.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #593539; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            animation: fadeInBody 0.8s ease-in-out;
        }

        @keyframes fadeInBody {
            from { opacity: 0; }
            to { opacity: 1; }
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
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .logo-img:hover {
            transform: scale(1.12) rotate(5deg);
        }

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
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(216, 27, 96, 0.2);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease; 
        }
        nav a:hover, nav a.active { 
            background: #d81b60;
            color: white; 
            border-color: #d81b60;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(216, 27, 96, 0.3);
        }

        .nav-icons { 
            display: flex; 
            gap: 15px; 
            font-size: 1.1rem; 
            align-items: center; 
            justify-content: flex-end;
            flex: 1;
        }
        
        .cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #593539;
            background: rgba(255, 255, 255, 0.85);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid rgba(216, 27, 96, 0.3);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: relative;
            transition: all 0.3s ease;
        }
        .cart-btn:hover {
            background: #fff;
            color: #d81b60;
            transform: translateY(-2px);
        }
        
        .cart-badge { 
            background: #d81b60; 
            color: white; 
            font-size: 0.75rem; 
            padding: 2px 7px; 
            border-radius: 50%; 
            font-weight: bold; 
            animation: pulseBadge 2s infinite;
        }

        @keyframes pulseBadge {
            0% { transform: scale(1); }
            50% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }

        .nav-auth-desktop a {
            text-decoration: none;
            font-size: 0.9rem;
            background: #d81b60; 
            color: white; 
            padding: 8px 18px; 
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(216, 27, 96, 0.3);
            transition: all 0.3s ease;
            display: inline-block;
        }
        .nav-auth-desktop a:hover {
            background: #ad1457;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(216, 27, 96, 0.4);
        }

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
        
        main { 
            flex: 1; 
            padding: 20px 8% 40px 8%; 
            max-width: 1000px; 
            margin: 0 auto; 
            width: 100%; 
            animation: slideUpMain 0.6s ease-out;
        }

        @keyframes slideUpMain {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Judul Keranjang Cantik & Estetik */
        .page-title-box {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            padding: 20px 30px;
            border-radius: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(216, 27, 96, 0.12);
            border: 2px solid rgba(255, 182, 193, 0.9);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            transition: transform 0.3s ease;
        }
        .page-title-box:hover {
            transform: translateY(-2px);
        }

        h2 { 
            font-family: 'Fredoka', cursive, sans-serif;
            color: #800000; 
            font-size: 1.6rem; 
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .subtitle-lucu {
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            color: #d81b60;
            font-weight: 500;
            background: #fce4ec;
            padding: 8px 16px;
            border-radius: 15px;
            border: 1px solid #f8bbd0;
            box-shadow: 0 2px 8px rgba(216,27,96,0.1);
        }
        
        .table-responsive { 
            width: 100%; 
            overflow-x: auto; 
            background: rgba(255, 255, 255, 0.94); 
            backdrop-filter: blur(8px); 
            border-radius: 25px; 
            box-shadow: 0 10px 30px rgba(216, 27, 96, 0.15); 
            padding: 22px; 
            margin-bottom: 25px; 
            border: 2px solid rgba(255,255,255,0.9); 
        }

        table { width: 100%; border-collapse: collapse; min-width: 500px; }
        th, td { padding: 14px 18px; text-align: left; border-bottom: 1px solid #f8bbd0; font-size: 0.95rem; }
        th { 
            font-family: 'Fredoka', cursive, sans-serif;
            background: #f8bbd0; 
            color: #880e4f; 
            font-weight: 500; 
            border-radius: 10px; 
            letter-spacing: 0.5px;
        }
        
        tr {
            transition: background-color 0.2s ease;
        }
        tr:hover {
            background-color: rgba(255, 240, 245, 0.7);
        }

        /* Styling Tombol Plus Minus Jumlah */
        .qty-box {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fce4ec;
            padding: 4px 8px;
            border-radius: 12px;
            border: 1px solid #f8bbd0;
        }
        .qty-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            background: #d81b60;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 50%;
            font-size: 0.9rem;
            transition: 0.2s;
        }
        .qty-btn:hover {
            background: #ad1457;
            transform: scale(1.1);
        }
        .qty-value {
            font-weight: 600;
            min-width: 20px;
            text-align: center;
            color: #800000;
        }

        .btn { 
            font-family: 'Fredoka', cursive, sans-serif;
            display: inline-block; 
            background: #800000; 
            color: white; 
            padding: 12px 25px; 
            border-radius: 25px; 
            text-decoration: none; 
            font-weight: 500; 
            border: none; 
            cursor: pointer; 
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.2); 
            transition: all 0.3s ease; 
        }
        .btn:hover { 
            background: #500000; 
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(128, 0, 0, 0.35);
        }
        .btn-secondary { background: #f8bbd0; color: #880e4f; }
        .btn-secondary:hover { background: #f48fb1; }
        
        .badge-ukuran {
            display: inline-block;
            background: #fce4ec;
            color: #d81b60;
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 6px;
            border: 1px solid rgba(216,27,96,0.3);
        }

        footer { 
            font-family: 'Poppins', sans-serif;
            background: rgba(173, 20, 87, 0.95); 
            backdrop-filter: blur(5px); 
            color: white; 
            text-align: center; 
            padding: 15px 20px; 
            width: 100%;
            margin-top: auto; 
            font-size: 0.9rem; 
            font-weight: 500;
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
        }
    </style>
</head>
<body>

    <header>
        <div class="logo-container">
            <a href="home.php">
                <img src="kawai.png" alt="Logo BinIce" class="logo-img">
            </a>
        </div>
        
        <button class="menu-toggle" id="menuToggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="navMenu">
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
            <a href="keranjang.php" class="active">Keranjang</a>
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

    <main>
        <!-- Judul Keranjang Cantik & Estetik -->
        <div class="page-title-box">
            <h2>🍨 Keranjang Belanja Kamu</h2>
            <span class="subtitle-lucu">✨ Yuk, wujudkan hari manis-mu sekarang!</span>
        </div>

        <?php if (empty($_SESSION['keranjang'])): ?>
            <div style="background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(8px); padding: 45px; border-radius: 25px; text-align: center; box-shadow: 0 10px 30px rgba(216, 27, 96, 0.12); border: 2px solid rgba(255, 182, 193, 0.9);">
                <p style="font-size: 1.15rem; margin-bottom: 20px; color: #7b5558; font-weight: 600;">Waduh, keranjang belanjanya masih kosong nih! 🥺</p>
                <a href="produk.php" class="btn">🍦 Pilih Es Krim Dulu Yuk!</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_bayar = 0;
                        foreach ($_SESSION['keranjang'] as $id => $arr_ukuran):
                            $query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
                            $p = mysqli_fetch_assoc($query);
                            
                            if (!$p) continue;

                            foreach ($arr_ukuran as $ukuran => $jumlah):
                                // Tentukan harga berdasarkan ukuran
                                $harga_satuan = ($ukuran == 'medium') ? $harga_medium : intval($p['harga']);
                                $subtotal = $harga_satuan * $jumlah;
                                $total_bayar += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($p['nama']) ?></strong>
                                <span class="badge-ukuran"><?= ucfirst($ukuran) ?></span>
                            </td>
                            <td>Rp <?= number_format($harga_satuan, 0, ',', '.') ?></td>
                            <td>
                                <!-- Tombol Plus dan Minus Jumlah -->
                                <div class="qty-box">
                                    <a href="keranjang.php?aksi=kurang&id=<?= $id ?>&ukuran=<?= $ukuran ?>" class="qty-btn">-</a>
                                    <span class="qty-value"><?= $jumlah ?></span>
                                    <a href="keranjang.php?aksi=tambah&id=<?= $id ?>&ukuran=<?= $ukuran ?>" class="qty-btn">+</a>
                                </div>
                            </td>
                            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td><a href="keranjang.php?hapus_id=<?= $id ?>&ukuran=<?= $ukuran ?>" style="color: #d81b60; font-weight: bold; text-decoration: none; transition: 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">BATAL</a></td>
                        </tr>
                        <?php 
                            endforeach;
                        endforeach; 
                        ?>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 600; font-size: 1.1rem; color: #800000; font-family: 'Fredoka', cursive;">Total Bayar:</td>
                            <td colspan="2" style="font-weight: 600; color: #d81b60; font-size: 1.25rem; font-family: 'Fredoka', cursive;">Rp <?= number_format($total_bayar, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                <a href="produk.php" class="btn btn-secondary">← Lanjut Belanja</a>
                <a href="checkout.php" class="btn">Lanjut ke Checkout ➔</a>
            </div>
        <?php endif; ?>
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
    </script>

</body>
</html>