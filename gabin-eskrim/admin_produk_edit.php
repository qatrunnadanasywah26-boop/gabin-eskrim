<?php
session_start();
include 'koneksi.php';

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
$p = mysqli_fetch_assoc($query);

if (!$p) {
    header("Location: admin_produk.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga     = intval($_POST['harga']);
    $gambar    = mysqli_real_escape_string($koneksi, $_POST['gambar']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $update = "UPDATE products SET nama='$nama', harga='$harga', gambar='$gambar', deskripsi='$deskripsi' WHERE id='$id'";
    if (mysqli_query($koneksi, $update)) {
        header("Location: admin_produk.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-nav { background: #333; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; color: white; }
        .admin-nav a { color: white; text-decoration: none; margin-left: 15px; font-weight: bold; }
    </style>
</head>
<body style="background: #f4f6f9;">

    <div class="admin-nav">
        <h2>🛠️ Admin Panel - Gabin Es Krim</h2>
        <div>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_produk.php">Kelola Produk</a>
        </div>
    </div>

    <main style="max-width: 600px;">
        <h2>Edit Produk #<?= $p['id'] ?></h2>
        <br>
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <form method="POST">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($p['nama']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" value="<?= $p['harga'] ?>" required>
                </div>

                <div class="form-group">
                    <label>URL Gambar Produk</label>
                    <input type="text" name="gambar" value="<?= htmlspecialchars($p['gambar']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Produk</label>
                    <textarea name="deskripsi" rows="3" required><?= htmlspecialchars($p['deskripsi']) ?></textarea>
                </div>

                <button type="submit" class="btn" style="background: #0984e3; width: 100%;">Update Produk</button>
                <a href="admin_produk.php" class="btn" style="background: #636e72; width: 100%; text-align: center; margin-top: 10px; display: block;">Batal</a>
            </form>
        </div>
    </main>

</body>
</html>