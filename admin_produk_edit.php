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

// Definisikan harga dari database (atau set default jika belum ada)
$harga_large_db = isset($p['harga']) ? intval($p['harga']) : 11000;
$harga_medium_db = 6000; // Harga medium tetap 6000 atau bisa disesuaikan

$stok_large_db = isset($p['stok_large']) ? intval($p['stok_large']) : 0;
$stok_medium_db = isset($p['stok_medium']) ? intval($p['stok_medium']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga     = intval($_POST['harga']);
    $stok      = intval($_POST['stok']); 
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $ukuran    = mysqli_real_escape_string($koneksi, $_POST['ukuran']); 
    
    $nama_gambar = $p['gambar']; 

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];
        $nama_file_asli = $_FILES['gambar']['name'];
        $ukuran_file    = $_FILES['gambar']['size'];
        $tmp_file       = $_FILES['gambar']['tmp_name'];
        $ekstensi       = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

        if (in_array($ekstensi, $ekstensi_boleh)) {
            if ($ukuran_file <= (2 * 1024 * 1024)) {
                $nama_gambar = time() . '_' . rand(100, 999) . '.' . $ekstensi;
                
                if (!is_dir('img')) {
                    mkdir('img', 0777, true);
                }
                
                move_uploaded_file($tmp_file, 'img/' . $nama_gambar);
            }
        }
    }

    // Update data berdasarkan ukuran yang dipilih admin saat form dikirim
    if ($ukuran == 'medium') {
        $update = "UPDATE products SET nama='$nama', stok_medium='$stok', gambar='$nama_gambar', deskripsi='$deskripsi' WHERE id='$id'";
    } else {
        // Jika large, update harga dan stok large-nya
        $update = "UPDATE products SET nama='$nama', harga='$harga', stok_large='$stok', gambar='$nama_gambar', deskripsi='$deskripsi' WHERE id='$id'";
    }

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
        <h2>halaman admin BinIce</h2>
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
                    <label id="labelHarga">Harga (Rp)</label>
                    <input type="number" name="harga" id="inputHarga" value="<?= $harga_large_db ?>" required>
                </div>

                <div class="form-group">
                    <label>Pilih Ukuran yang Ingin Diatur</label>
                    <select name="ukuran" id="pilihUkuran" required onchange="ubahUkuranAdmin()">
                        <option value="large">Large</option>
                        <option value="medium">Medium</option>
                    </select>
                </div>

                <div class="form-group">
                    <label id="labelStok">Stok Produk</label>
                    <input type="number" name="stok" id="inputStok" value="<?= $stok_large_db ?>" required min="0">
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

    <script>
        const hargaLarge = <?= $harga_large_db ?>;
        const hargaMedium = <?= $harga_medium_db ?>;
        
        const stokLarge = <?= $stok_large_db ?>;
        const stokMedium = <?= $stok_medium_db ?>;

        function ubahUkuranAdmin() {
            const pilihan = document.getElementById('pilihUkuran').value;
            const inputStok = document.getElementById('inputStok');
            const labelStok = document.getElementById('labelStok');
            const inputHarga = document.getElementById('inputHarga');
            const labelHarga = document.getElementById('labelHarga');

            if (pilihan === 'medium') {
                inputStok.value = stokMedium;
                labelStok.textContent = 'Stok Ukuran Medium';
                
                inputHarga.value = hargaMedium;
                inputHarga.readOnly = true; // Medium harganya fix, jadi gak perlu diubah-ubah di sini
                inputHarga.style.background = '#eee';
                labelHarga.textContent = 'Harga Ukuran Medium (Fix)';
            } else {
                inputStok.value = stokLarge;
                labelStok.textContent = 'Stok Ukuran Large';
                
                inputHarga.value = hargaLarge; // Pakai harga tersimpan dari database (misal udah diubah jadi 12000)
                inputHarga.readOnly = false; // Large boleh diedit harganya
                inputHarga.style.background = '#fff';
                labelHarga.textContent = 'Harga Ukuran Large (Rp)';
            }
        }
        ubahUkuranAdmin();
    </script>
</body>
</html>