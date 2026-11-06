<?php
session_start();
include 'koneksi.php';

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    unset($_SESSION['keranjang'][$id_hapus]);
    header('Location: keranjang.php');
    exit;
}

$total_keranjang = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - BinIce</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fce4ec; color: #593539; min-height: 100vh; display: flex; flex-direction: column; }
        
        header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 20px 8%; 
            background: transparent; 
            position: relative;
            z-index: 100;
        }
        
        .logo { font-size: 1.8rem; font-weight: 800; color: #d81b60; letter-spacing: 1px; }

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
        
        main { flex: 1; padding: 20px 8% 60px 8%; max-width: 1000px; margin: 0 auto; width: 100%; }
        h2 { color: #c2185b; margin-bottom: 25px; font-size: 2rem; }
        
        .table-responsive { width: 100%; overflow-x: auto; background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08); padding: 20px; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; min-width: 500px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #f8bbd0; font-size: 0.95rem; }
        th { background: #f8bbd0; color: #880e4f; font-weight: 700; border-radius: 8px; }
        
        .btn { display: inline-block; background: #d81b60; color: white; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: bold; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(216, 27, 96, 0.3); transition: 0.3s; }
        .btn:hover { background: #ad1457; }
        .btn-secondary { background: #f8bbd0; color: #880e4f; }
        .btn-secondary:hover { background: #f48fb1; }
        
        footer { background: #ad1457; color: white; text-align: center; padding: 20px; margin-top: auto; font-size: 0.9rem; }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media(max-width: 768px) { 
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
            <a href="produk.php">Products</a>
            <a href="keranjang.php" class="active">Keranjang</a>
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
        <h2>Keranjang Belanja Anda</h2>

        <?php if (empty($_SESSION['keranjang'])): ?>
            <div style="background: white; padding: 40px; border-radius: 20px; text-align: center; box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08);">
                <p style="font-size: 1.1rem; margin-bottom: 20px; color: #7b5558;">Keranjang belanja Anda masih kosong.</p>
                <a href="produk.php" class="btn">Pilih Produk Dulu Yuk!</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_bayar = 0;
                        foreach ($_SESSION['keranjang'] as $id => $jumlah): 
                            $query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
                            $p = mysqli_fetch_assoc($query);
                            $subtotal = $p['harga'] * $jumlah;
                            $total_bayar += $subtotal;
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['nama']) ?></strong></td>
                            <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                            <td><?= $jumlah ?></td>
                            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td><a href="keranjang.php?hapus=<?= $id ?>" style="color: #d81b60; font-weight: bold; text-decoration: none;">BATAL</a></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: bold; font-size: 1.1rem;">Total Bayar:</td>
                            <td colspan="2" style="font-weight: bold; color: #d81b60; font-size: 1.2rem;">Rp <?= number_format($total_bayar, 0, ',', '.') ?></td>
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