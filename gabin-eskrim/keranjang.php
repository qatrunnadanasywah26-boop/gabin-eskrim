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
        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: transparent; }
        .logo { font-size: 1.8rem; font-weight: 800; color: #d81b60; letter-spacing: 1px; }
        nav { display: flex; gap: 30px; align-items: center; }
        nav a { text-decoration: none; color: #593539; font-weight: 600; transition: 0.3s; }
        nav a:hover, nav a.active { color: #d81b60; }
        .nav-icons { display: flex; gap: 20px; font-size: 1.2rem; align-items: center; }
        .nav-icons a { text-decoration: none; color: #593539; position: relative; }
        .cart-badge { position: absolute; top: -8px; right: -10px; background: #d81b60; color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 50%; font-weight: bold; }
        
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
        @media(max-width: 768px) { header { flex-direction: column; gap: 15px; padding: 15px; } nav { gap: 15px; } }
    </style>
</head>
<body>

    <header>
        <div class="logo">🍦 BinIce</div>
        <nav>
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
            <a href="keranjang.php" class="active">Keranjang</a>
            <a href="contact.php">Contact</a>
        </nav>
        <div class="nav-icons">
            <a href="keranjang.php">🛒 <span class="cart-badge"><?= $total_keranjang ?></span></a>
            <?php if(isset($_SESSION['admin_logged'])): ?>
                <a href="admin_dashboard.php" style="font-size: 0.9rem; background: #d81b60; color: white; padding: 5px 12px; border-radius: 15px;">Admin</a>
            <?php else: ?>
                <a href="login.php" style="font-size: 0.9rem;">🔑 Login</a>
            <?php endif; ?>
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
                            <td><a href="keranjang.php?hapus=<?= $id ?>" style="color: #d81b60; font-weight: bold; text-decoration: none;">Hapus</a></td>
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

</body>
</html>