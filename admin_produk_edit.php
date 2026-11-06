<?php
session_start();
include 'koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
$p = mysqli_fetch_assoc($query);

if (!$p) {
    header("Location: admin_produk.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga     = intval($_POST['harga']);
    $stok      = intval($_POST['stok']); // Menangkap input stok baru
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $ukuran    = mysqli_real_escape_string($koneksi, $_POST['ukuran']);
    
    $nama_gambar = $p['gambar']; // Default pakai gambar lama

    // Cek apakah admin mengupload file gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];
        $nama_file_asli = $_FILES['gambar']['name'];
        $ukuran_file    = $_FILES['gambar']['size'];
        $tmp_file       = $_FILES['gambar']['tmp_name'];
        $ekstensi       = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

        if (in_array($ekstensi, $ekstensi_boleh)) {
            if ($ukuran_file <= 2 * 1024 * 1024) { // Maksimal 2MB
                $nama_gambar = time() . '_' . rand(100, 999) . '.' . $ekstensi;
                
                if (!is_dir('img')) {
                    mkdir('img', 0777, true);
                }
                
                move_uploaded_file($tmp_file, 'img/' . $nama_gambar);
            }
        }
    }

    // Update data produk beserta stok ke database
    $update = "UPDATE products SET nama='$nama', harga='$harga', stok='$stok', gambar='$nama_gambar', deskripsi='$deskripsi', ukuran='$ukuran' WHERE id='$id'";
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
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #593539; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; background: #fff; }
    </style>
</head>
<body style="background: #f4f6f9;">

    <div class="admin-nav">
        <h2>🛠️ Admin Panel - BinIce</h2>
        <div>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_produk.php">Kelola Produk</a>
        </div>
    </div>

    <main style="max-width: 600px; margin: 30px auto; padding: 0 20px;">
        <h2>Edit Produk #<?= $p['id'] ?></h2>
        <br>
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($p['nama']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" value="<?= $p['harga'] ?>" required>
                </div>

                <!-- Input Stok Produk yang Dapat Diedit Admin -->
                <div class="form-group">
                    <label>Stok Produk</label>
                    <input type="number" name="stok" value="<?= isset($p['stok']) ? $p['stok'] : 0 ?>" required min="0">
                </div>

                <div class="form-group">
                    <label>Pilih Ukuran / Baris Katalog</label>
                    <select name="ukuran" required>
                        <option value="large" <?= ($p['ukuran'] == 'large') ? 'selected' : '' ?>>Large (Masuk Baris Atas)</option>
                        <option value="medium" <?= ($p['ukuran'] == 'medium') ? 'selected' : '' ?>>Medium (Masuk Baris Bawah)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ganti Foto Produk (Opsional)</label>
                    <p style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Foto saat ini: <?= htmlspecialchars($p['gambar']) ?></p>
                    <input type="file" name="gambar" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Deskripsi Produk</label>
                    <textarea name="deskripsi" rows="3" required><?= htmlspecialchars($p['deskripsi']) ?></textarea>
                </div>

                <button type="submit" class="btn" style="background: #0984e3; color: white; padding: 10px; border: none; border-radius: 5px; font-weight: bold; width: 100%; cursor: pointer;">Update Produk</button>
                <a href="admin_produk.php" class="btn" style="background: #636e72; color: white; padding: 10px; text-decoration: none; border-radius: 5px; text-align: center; margin-top: 10px; display: block;">Batal</a>
            </form>
        </div>
    </main>

</body>
</html>