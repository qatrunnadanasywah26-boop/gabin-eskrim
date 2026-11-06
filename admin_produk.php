<?php
session_start();
include 'koneksi.php';

if (isset($_GET['hapus_produk'])) {
    $id_produk = intval($_GET['hapus_produk']);
    mysqli_query($koneksi, "DELETE FROM products WHERE id = '$id_produk'");
    header("Location: admin_produk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin Gabin Es Krim</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-nav { background: #333; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; color: white; }
        .admin-nav a { color: white; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .img-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body style="background: #f4f6f9;">

    <div class="admin-nav">
        <h2>🛠️ Admin Panel - Gabin Es Krim</h2>
        <div>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_produk.php">Kelola Produk</a>
            <a href="index.php" target="_blank" style="color: #ffda79;">Lihat Website ↗</a>
        </div>
    </div>

    <main>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Daftar Produk Gabin Es Krim</h2>
            <a href="admin_produk_tambah.php" class="btn" style="background: #00b894;">+ Tambah Produk Baru</a>
        </div>
        <br>

        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $products = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC");
                while ($p = mysqli_fetch_assoc($products)):
                ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($p['gambar']) ?>" class="img-thumb" alt="produk"></td>
                    <td><strong><?= htmlspecialchars($p['nama']) ?></strong></td>
                    <td><small><?= htmlspecialchars($p['deskripsi']) ?></small></td>
                    <td style="font-weight: bold; color: #ff5252;">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                    <td>
                        <a href="admin_produk_edit.php?id=<?= $p['id'] ?>" style="color: #0984e3; font-weight: bold; margin-right: 10px;">Edit</a>
                        <a href="admin_produk.php?hapus_produk=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')" style="color: red; font-weight: bold;">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

</body>
</html>