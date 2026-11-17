<?php
session_start();
include 'koneksi.php';

// Ambil semua data pesanan dari database, diurutkan dari yang terbaru
$query_orders = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin BinIce</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-nav { background: #333; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; color: white; }
        .admin-nav a { color: white; text-decoration: none; margin-left: 15px; font-weight: bold; }
        
        main { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        h2 { color: #593539; margin-bottom: 20px; }
        
        .table-container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; color: #333; font-weight: bold; }
        tr:hover { background: #f9f9f9; }
        
        .btn-bill { display: inline-block; background: #0984e3; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.85rem; transition: 0.3s; }
        .btn-bill:hover { background: #74b9ff; }
        
        .empty-state { text-align: center; padding: 40px; color: #666; }
    </style>
</head>
<body style="background: #f4f6f9;">

    <div class="admin-nav">
        <h2>🛠️ Admin Panel - BinIce</h2>
        <div>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_produk.php">Kelola Produk</a>
            <a href="admin_pesanan.php" style="color: #74b9ff;">Kelola Pesanan</a>
        </div>
    </div>

    <main>
        <h2>Daftar Pesanan & Bill Masuk</h2>
        
        <div class="table-container">
            <?php if (mysqli_num_rows($query_orders) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>Nama Pemesan</th>
                            <th>WhatsApp</th>
                            <th>Total Bayar</th>
                            <th>Metode</th>
                            <th>Aksi Bill</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query_orders)): ?>
                            <tr>
                                <td><strong>#<?= $row['id'] ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                <td><?= htmlspecialchars($row['whatsapp']) ?></td>
                                <td style="font-weight: bold; color: #d81b60;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                                <td>
                                    <!-- Tombol untuk membuka bill.php milik pesanan tersebut -->
                                    <a href="bill.php?id=<?= $row['id'] ?>" class="btn-bill" target="_blank">📄 Lihat Bill & Bukti</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>Belum ada pesanan yang masuk ke database.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>