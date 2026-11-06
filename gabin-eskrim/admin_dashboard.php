<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['hapus_order'])) {
    $id_order = intval($_GET['hapus_order']);
    $q_foto = mysqli_query($koneksi, "SELECT bukti_pembayaran FROM orders WHERE id = '$id_order'");
    $d_foto = mysqli_fetch_assoc($q_foto);
    if ($d_foto && !empty($d_foto['bukti_pembayaran']) && file_exists('uploads/' . $d_foto['bukti_pembayaran'])) {
        unlink('uploads/' . $d_foto['bukti_pembayaran']);
    }

    mysqli_query($koneksi, "DELETE FROM orders WHERE id = '$id_order'");
    header("Location: admin_dashboard.php");
    exit;
}

$q_orders = mysqli_query($koneksi, "SELECT COUNT(*) as total_order, SUM(total_harga) as total_omset FROM orders");
$d_orders = mysqli_fetch_assoc($q_orders);

$q_products = mysqli_query($koneksi, "SELECT COUNT(*) as total_produk FROM products");
$d_products = mysqli_fetch_assoc($q_products);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gabin Es Krim</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-nav { background: #2d3436; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; color: white; flex-wrap: wrap; gap: 10px; }
        .admin-nav div { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; }
        .admin-nav a { color: white; text-decoration: none; font-weight: bold; }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; border-bottom: 4px solid #ff5252; }
        .stat-card h3 { font-size: 2rem; color: #ff5252; margin-top: 5px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.8rem; text-transform: uppercase; }
        .badge-dana { background: #0080ff; color: white; }
        .badge-gopay { background: #00a5cf; color: white; }
        .badge-cod { background: #2ed573; color: white; }
        .bukti-img { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; cursor: pointer; transition: 0.3s; }
        .bukti-img:hover { transform: scale(2.2); }
    </style>
</head>
<body style="background: #f4f6f9;">

    <div class="admin-nav">
        <h2>🛠️ Admin Panel</h2>
        <div>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_produk.php">Kelola Produk</a>
            <a href="home.php" target="_blank">Lihat Website ↗</a>
            <a href="logout.php" style="background: #ff5252; padding: 6px 12px; border-radius: 6px;">Logout</a>
        </div>
    </div>

    <main>
        <h2>Dashboard Ringkasan</h2>
        <br>

        <div class="stat-grid">
            <div class="stat-card">
                <p>Total Pesanan Masuk</p>
                <h3><?= number_format($d_orders['total_order'] ?? 0) ?></h3>
            </div>
            <div class="stat-card">
                <p>Total Pendapatan (Omset)</p>
                <h3>Rp <?= number_format($d_orders['total_omset'] ?? 0, 0, ',', '.') ?></h3>
            </div>
            <div class="stat-card">
                <p>Total Variasi Produk</p>
                <h3><?= number_format($d_products['total_produk'] ?? 0) ?></h3>
            </div>
        </div>

        <h2>Daftar Pesanan Masuk</h2>
        <br>
        <!-- Pembungkus agar tabel rapi di HP -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>WhatsApp</th>
                        <th>Alamat</th>
                        <th>Rincian</th>
                        <th>Metode & Bukti</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $orders = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY id DESC");
                    if (mysqli_num_rows($orders) == 0):
                    ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Belum ada pesanan masuk.</td>
                    </tr>
                    <?php else: ?>
                        <?php while ($o = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td>#<?= $o['id'] ?></td>
                            <td><small><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></small></td>
                            <td><strong><?= htmlspecialchars($o['nama_pelanggan']) ?></strong></td>
                            <td><a href="https://wa.me/<?= $o['whatsapp'] ?>" target="_blank" style="color:#00b894; font-weight:bold;"><?= htmlspecialchars($o['whatsapp']) ?></a></td>
                            <td><small><?= htmlspecialchars($o['alamat']) ?></small></td>
                            <td><pre style="font-family: inherit; font-size: 0.85rem; margin: 0;"><?= htmlspecialchars($o['rincian_pesanan']) ?></pre></td>
                            <td>
                                <?php 
                                $m = strtolower($o['metode_pembayaran']);
                                $badgeClass = $m == 'dana' ? 'badge-dana' : ($m == 'gopay' ? 'badge-gopay' : 'badge-cod');
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($o['metode_pembayaran']) ?></span>
                                <?php if(!empty($o['bukti_pembayaran'])): ?>
                                    <br><br>
                                    <a href="uploads/<?= $o['bukti_pembayaran'] ?>" target="_blank" title="Klik untuk memperbesar">
                                        <img src="uploads/<?= $o['bukti_pembayaran'] ?>" class="bukti-img" alt="Bukti Transfer">
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td style="font-weight: bold; color: #ff5252;">Rp <?= number_format($o['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <a href="admin_dashboard.php?hapus_order=<?= $o['id'] ?>" onclick="return confirm('Yakin ingin menghapus pesanan ini?')" style="color: red; font-weight: bold; text-decoration: none;">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>