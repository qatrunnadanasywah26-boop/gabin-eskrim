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
        .btn-bill-admin { display: inline-block; background: #0984e3; color: white; padding: 5px 10px; border-radius: 5px; font-size: 0.85rem; text-decoration: none; font-weight: bold; margin-bottom: 5px; }
        .btn-bill-admin:hover { background: #74b9ff; }
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

    <main style="padding: 20px 5%;">
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
            <table style="width: 100%; background: white; border-collapse: collapse; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <thead>
                    <tr style="background: #f1f2f6; text-align: left;">
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Tanggal</th>
                        <th style="padding: 12px;">Pelanggan</th>
                        <th style="padding: 12px;">WhatsApp</th>
                        <th style="padding: 12px;">Alamat</th>
                        <th style="padding: 12px;">Rincian</th>
                        <th style="padding: 12px;">Metode & Bukti</th>
                        <th style="padding: 12px;">Total</th>
                        <th style="padding: 12px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $orders = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY id DESC");
                    if (mysqli_num_rows($orders) == 0):
                    ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 20px;">Belum ada pesanan masuk.</td>
                    </tr>
                    <?php else: ?>
                        <?php while ($o = mysqli_fetch_assoc($orders)): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px;">#<?= $o['id'] ?></td>
                            <td style="padding: 12px;"><small><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></small></td>
                            <td style="padding: 12px;"><strong><?= htmlspecialchars($o['nama_pelanggan']) ?></strong></td>
                            <td style="padding: 12px;"><a href="https://wa.me/<?= $o['whatsapp'] ?>" target="_blank" style="color:#00b894; font-weight:bold;"><?= htmlspecialchars($o['whatsapp']) ?></a></td>
                            <td style="padding: 12px;"><small><?= htmlspecialchars($o['alamat']) ?></small></td>
                            <td style="padding: 12px;"><pre style="font-family: inherit; font-size: 0.85rem; margin: 0;"><?= htmlspecialchars($o['rincian_pesanan']) ?></pre></td>
                            <td style="padding: 12px;">
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
                            <td style="padding: 12px; font-weight: bold; color: #ff5252;">Rp <?= number_format($o['total_harga'], 0, ',', '.') ?></td>
                            <td style="padding: 12px;">
                                <!-- Tombol Lihat Bill -->
                                <a href="bill.php?id=<?= $o['id'] ?>" target="_blank" class="btn-bill-admin">📄 Lihat Bill</a><br>
                                <a href="admin_dashboard.php?hapus_order=<?= $o['id'] ?>" onclick="return confirm('Yakin ingin menghapus pesanan ini?')" style="color: red; font-weight: bold; text-decoration: none; font-size: 0.9rem;">Hapus</a>
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