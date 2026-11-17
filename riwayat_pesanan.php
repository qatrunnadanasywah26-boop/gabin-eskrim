<?php
session_start();
include 'koneksi.php';

// Ambil semua data pesanan dari database, diurutkan dari yang terbaru
$query_orders = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY id DESC");

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
    <title>Riwayat Pesanan & Bill - BinIce</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fce4ec; color: #593539; min-height: 100vh; display: flex; flex-direction: column; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: transparent; }
        .logo { font-size: 1.8rem; font-weight: 800; color: #d81b60; letter-spacing: 1px; }
        nav { display: flex; gap: 12px; align-items: center; }
        nav a { text-decoration: none; color: #593539; font-weight: 600; font-size: 0.95rem; padding: 8px 18px; border-radius: 20px; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(216, 27, 96, 0.2); transition: 0.3s; }
        nav a:hover, nav a.active { background: #d81b60; color: white; border-color: #d81b60; }
        .cart-btn { display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: #593539; background: rgba(255, 255, 255, 0.85); padding: 8px 15px; border-radius: 20px; font-weight: bold; border: 1px solid rgba(216, 27, 96, 0.3); }
        .cart-badge { background: #d81b60; color: white; font-size: 0.75rem; padding: 2px 7px; border-radius: 50%; font-weight: bold; }
        
        main { flex: 1; padding: 30px 8%; max-width: 900px; margin: 0 auto; width: 100%; }
        h2 { color: #c2185b; margin-bottom: 20px; font-size: 1.8rem; }
        
        .table-container { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #f8bbd0; }
        th { background: #fff9fa; color: #880e4f; font-weight: bold; }
        tr:hover { background: #fff5f8; }
        
        .btn-bill { display: inline-block; background: #d81b60; color: white; padding: 6px 14px; border-radius: 15px; text-decoration: none; font-weight: bold; font-size: 0.85rem; box-shadow: 0 2px 8px rgba(216, 27, 96, 0.3); transition: 0.3s; }
        .btn-bill:hover { background: #ad1457; }
        
        .empty-state { text-align: center; padding: 40px; color: #880e4f; }
        footer { background: #ad1457; color: white; text-align: center; padding: 20px; margin-top: auto; font-size: 0.9rem; }
    </style>
</head>
<body>

    <header>
        <div class="logo">🍦 BinIce</div>
        <nav>
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
            <a href="riwayat_pesanan.php" class="active">Riwayat Bill</a>
            <a href="contact.php">Contact</a>
        </nav>
        <div>
            <a href="keranjang.php" class="cart-btn">🛒 Keranjang <span class="cart-badge"><?= $total_keranjang ?></span></a>
        </div>
    </header>

    <main>
        <h2>📋 Riwayat Pesanan & Struk Bill</h2>
        
        <div class="table-container">
            <?php if (mysqli_num_rows($query_orders) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>Nama Pemesan</th>
                            <th>Total Bayar</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query_orders)): ?>
                            <tr>
                                <td><strong>#<?= $row['id'] ?></strong></td>
                                <td><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
                                <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                <td style="font-weight: bold; color: #d81b60;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                                <td>
                                    <a href="bill.php?id=<?= $row['id'] ?>" class="btn-bill" target="_blank">📄 Lihat Bill</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>Belum ada riwayat pesanan yang tercatat.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>

</body>
</html>