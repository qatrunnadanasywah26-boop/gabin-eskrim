<?php
session_start();
include 'koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
$p = mysqli_fetch_assoc($query);

if (!$p) {
    header("Location: produk.php");
    exit;
}

// Proses ketika tombol "Tambah ke Keranjang" ditekan dari halaman detail
if (isset($_POST['tambah_keranjang'])) {
    $jumlah_beli = intval($_POST['jumlah']);
    $pilihan_ukuran = $_POST['ukuran_dipilih'];
    
    // Validasi stok
    if ($jumlah_beli > $p['stok']) {
        $error = "Jumlah pembelian melebihi stok yang tersedia (Stok: " . $p['stok'] . ")";
    } else {
        // Simpan ke keranjang (bisa pakai key unik gabungan id dan ukuran jika mau, atau standar id)
        if (isset($_SESSION['keranjang'][$id])) {
            $_SESSION['keranjang'][$id] += $jumlah_beli;
        } else {
            $_SESSION['keranjang'][$id] = $jumlah_beli;
        }
        header("Location: keranjang.php");
        exit;
    }
}

$total_keranjang = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?= htmlspecialchars($p['nama']) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { 
            background-color: #fce4ec; 
            color: #593539; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
        }
        header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 20px 8%; 
            background: transparent; 
        }
        .logo { font-size: 1.8rem; font-weight: 800; color: #d81b60; letter-spacing: 1px; }
        nav { display: flex; gap: 12px; align-items: center; }
        nav a { 
            text-decoration: none; color: #593539; font-weight: 600; font-size: 0.95rem; 
            padding: 8px 18px; border-radius: 20px; background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(216, 27, 96, 0.2); transition: 0.3s; 
        }
        nav a:hover, nav a.active { background: #d81b60; color: white; border-color: #d81b60; }
        
        main { flex: 1; padding: 30px 8%; max-width: 900px; margin: 0 auto; width: 100%; }
        
        .product-detail-card {
            background: white;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: center;
        }

        .product-img-box {
            width: 100%;
            height: 320px;
            background: #800000;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info h2 { color: #800000; font-size: 1.8rem; margin-bottom: 10px; }
        .price { font-size: 1.5rem; font-weight: 800; color: #d81b60; margin-bottom: 15px; }
        .stock-info { font-size: 0.95rem; color: #666; margin-bottom: 15px; font-weight: bold; }
        .desc { font-size: 0.95rem; color: #555; margin-bottom: 20px; line-height: 1.5; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #880e4f; font-size: 0.9rem; }
        .form-group select, .form-group input {
            width: 100%; padding: 10px; border: 1px solid #f8bbd0; border-radius: 10px; font-size: 1rem; background: #fff9fa;
        }

        .btn-add {
            display: block; width: 100%; background: #d81b60; color: white; padding: 12px;
            border-radius: 25px; text-align: center; font-weight: bold; border: none; cursor: pointer;
            box-shadow: 0 4px 15px rgba(216, 27, 96, 0.3); transition: 0.3s; font-size: 1rem; margin-top: 15px;
        }
        .btn-add:hover { background: #ad1457; }
        .error-msg { background: #ffebee; color: #c62828; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9rem; }

        @media(max-width: 768px) {
            .product-detail-card { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">🍦 BinIce</div>
        <nav>
            <a href="home.php">Home</a>
            <a href="produk.php" class="active">Products</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <main>
        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <div class="product-detail-card">
            <div class="product-img-box">
                <img src="<?= !empty($p['gambar']) ? 'img/' . htmlspecialchars($p['gambar']) : 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600' ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
            </div>

            <div class="product-info">
                <h2><?= htmlspecialchars($p['nama']) ?></h2>
                <div class="price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                <div class="stock-info">📦 Stok Tersedia: <strong><?= $p['stok'] ?> pcs</strong></div>
                <div class="desc"><?= nl2br(htmlspecialchars($p['deskripsi'])) ?></div>

                <form method="POST">
                    <div class="form-group">
                        <label>Pilih Ukuran Varian</label>
                        <select name="ukuran_dipilih" required>
                            <option value="large" <?= ($p['ukuran'] == 'large') ? 'selected' : '' ?>>Large</option>
                            <option value="medium" <?= ($p['ukuran'] == 'medium') ? 'selected' : '' ?>>Medium</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Beli</label>
                        <input type="number" name="jumlah" value="1" min="1" max="<?= $p['stok'] ?>" required>
                    </div>

                    <button type="submit" name="tambah_keranjang" class="btn-add">🛒 Masukkan ke Keranjang</button>
                </form>
            </div>
        </div>
    </main>

    <footer style="background: #ad1457; color: white; text-align: center; padding: 20px; margin-top: auto;">
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>

</body>
</html>