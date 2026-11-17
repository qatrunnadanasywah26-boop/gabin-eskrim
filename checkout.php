<?php
session_start();
include 'koneksi.php';

if (empty($_SESSION['keranjang'])) {
    header("Location: keranjang.php");
    exit;
}

$error = "";
$sukses_checkout = false;

// Harga fix sesuai ketentuan
$harga_large = 11000;
$harga_medium = 6000;

if (isset($_POST['proses_checkout'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $whatsapp = mysqli_real_escape_string($koneksi, $_POST['whatsapp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $metode = mysqli_real_escape_string($koneksi, $_POST['metode']);
    
    $total_harga = 0;
    $rincian_pesanan = "";
    
    // Perulangan membaca keranjang berdasarkan [id][ukuran]
    foreach ($_SESSION['keranjang'] as $id => $arr_ukuran) {
        $q = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
        $p = mysqli_fetch_assoc($q);
        
        foreach ($arr_ukuran as $ukuran => $jumlah) {
            $harga_satuan = ($ukuran == 'medium') ? $harga_medium : $harga_large;
            $subtotal = $harga_satuan * $jumlah;
            $total_harga += $subtotal;
            
            $nama_produk_db = isset($p['nama']) ? $p['nama'] : 'Produk';
            $rincian_pesanan .= "- " . $nama_produk_db . " (" . ucfirst($ukuran) . " - " . $jumlah . "x) = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }
    }

    $bukti_nama = "";
    if ($metode == 'DANA' || $metode == 'GoPay') {
        if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
            $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];
            $nama_file = $_FILES['bukti']['name'];
            $ukuran_file = $_FILES['bukti']['size'];
            $tmp_file = $_FILES['bukti']['tmp_name'];
            $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

            if (in_array($ekstensi, $ekstensi_boleh)) {
                if ($ukuran_file <= (2 * 1024 * 1024)) {
                    $bukti_nama = time() . '_' . rand(100,999) . '.' . $ekstensi;
                    if (!is_dir('uploads')) {
                        mkdir('uploads', 0777, true);
                    }
                    move_uploaded_file($tmp_file, 'uploads/' . $bukti_nama);
                } else {
                    $error = "Ukuran file bukti pembayaran terlalu besar (Maksimal 2MB)!";
                }
            } else {
                $error = "Format file bukti pembayaran harus JPG, PNG, atau WEBP!";
            }
        } else {
            $error = "Metode pembayaran " . $metode . " wajib mengunggah foto bukti transfer!";
        }
    }

    if (empty($error)) {
        $query_insert = "INSERT INTO orders (nama_pelanggan, whatsapp, alamat, metode_pembayaran, total_harga, rincian_pesanan, bukti_pembayaran, created_at) 
                         VALUES ('$nama', '$whatsapp', '$alamat', '$metode', '$total_harga', '$rincian_pesanan', '$bukti_nama', NOW())";
        
        if (mysqli_query($koneksi, $query_insert)) {
            
            // --- PENGURANGAN STOK SPESIFIK BERDASARKAN UKURAN ---
            foreach ($_SESSION['keranjang'] as $id => $arr_ukuran) {
                foreach ($arr_ukuran as $ukuran => $jumlah) {
                    if ($ukuran == 'medium') {
                        mysqli_query($koneksi, "UPDATE products SET stok_medium = stok_medium - $jumlah WHERE id = '$id'");
                    } else {
                        mysqli_query($koneksi, "UPDATE products SET stok_large = stok_large - $jumlah WHERE id = '$id'");
                    }
                }
            }
            // ----------------------------------------------------

            unset($_SESSION['keranjang']);
            $sukses_checkout = true; 
        } else {
            $error = "Terjadi kesalahan pada database: " . mysqli_error($koneksi);
        }
    }
}

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
    <title>Checkout - BinIce</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fce4ec; color: #593539; min-height: 100vh; display: flex; flex-direction: column; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: transparent; position: relative; z-index: 100; }
        .logo { font-size: 1.8rem; font-weight: 800; color: #d81b60; letter-spacing: 1px; }
        nav { display: flex; gap: 12px; align-items: center; }
        nav a { text-decoration: none; color: #593539; font-weight: 600; font-size: 0.95rem; padding: 8px 18px; border-radius: 20px; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(216, 27, 96, 0.2); transition: 0.3s; }
        nav a:hover, nav a.active { background: #d81b60; color: white; border-color: #d81b60; transform: translateY(-2px); }
        .nav-icons { display: flex; gap: 15px; font-size: 1.1rem; align-items: center; }
        .cart-btn { display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: #593539; background: rgba(255, 255, 255, 0.85); padding: 8px 15px; border-radius: 20px; font-weight: bold; border: 1px solid rgba(216, 27, 96, 0.3); transition: 0.3s; }
        .cart-btn:hover { background: #fff; color: #d81b60; }
        .cart-badge { background: #d81b60; color: white; font-size: 0.75rem; padding: 2px 7px; border-radius: 50%; font-weight: bold; }
        main { flex: 1; padding: 20px 8% 60px 8%; max-width: 800px; margin: 0 auto; width: 100%; display: flex; flex-direction: column; justify-content: center; }
        h2 { color: #c2185b; margin-bottom: 25px; font-size: 2rem; }
        .form-container { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #880e4f; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #f8bbd0; border-radius: 12px; font-size: 0.95rem; background: #fff9fa; }
        .info-box { background: #fce4ec; border-left: 4px solid #d81b60; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; color: #880e4f; }
        .error-box { background: #ffebee; border-left: 4px solid #c62828; color: #c62828; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .btn { display: inline-block; width: 100%; background: #d81b60; color: white; padding: 14px; border-radius: 25px; text-decoration: none; font-weight: bold; border: none; cursor: pointer; text-align: center; box-shadow: 0 4px 15px rgba(216, 27, 96, 0.3); transition: 0.3s; font-size: 1rem; }
        .btn:hover { background: #ad1457; }
        .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(252, 228, 236, 0.96); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; text-align: center; }
        .spinner { width: 70px; height: 70px; border: 6px solid #f8bbd0; border-top: 6px solid #d81b60; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .loading-text { color: #880e4f; font-size: 1.6rem; font-weight: 800; }
        footer { background: #ad1457; color: white; text-align: center; padding: 20px; margin-top: auto; font-size: 0.9rem; }
    </style>
</head>
<body>

    <?php if ($sukses_checkout): ?>
        <div class="loading-overlay">
            <div class="spinner" id="spinnerIcon"></div>
            <div class="loading-text" id="loadingText">Pesananmu sedang diproses... 🍦✨</div>
        </div>
        <script>
            setTimeout(function() {
                var spinner = document.getElementById('spinnerIcon');
                if(spinner) spinner.style.display = 'none';
                document.getElementById('loadingText').innerHTML = "Pesananmu berhasil dibuat! 🎉✨";
            }, 1500);

            setTimeout(function() {
                window.location.href = 'produk.php';
            }, 3000);
        </script>
    <?php endif; ?>

    <header>
        <div class="logo">🍦 BinIce</div>
        <nav>
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="contact.php">Contact</a>
        </nav>
        <div class="nav-icons">
            <a href="keranjang.php" class="cart-btn">🛒 Keranjang <span class="cart-badge"><?= $total_keranjang ?></span></a>
        </div>
    </header>

    <main>
        <h2>Formulir Checkout Pesanan</h2>

        <?php if (!empty($error)): ?>
            <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Masukkan nama lengkap Anda">
                </div>

                <div class="form-group">
                    <label>Nomor WhatsApp (Aktif)</label>
                    <input type="number" name="whatsapp" required placeholder="Contoh: 081234567890">
                </div>

                <div class="form-group">
                    <label>Alamat Pengiriman Lengkap</label>
                    <textarea name="alamat" rows="3" required placeholder="Masukkan alamat lengkap tujuan pengiriman"></textarea>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode" id="metode" required onchange="toggleBukti()">
                        <option value="DANA">DANA (0812-3456-7890 a.n BinIce)</option>
                        <option value="GoPay">GoPay (0812-3456-7890 a.n BinIce)</option>
                        <option value="COD">COD (Bayar di Tempat)</option>
                    </select>
                </div>

                <div class="info-box" id="info-transfer">
                    💡 Silakan transfer terlebih dahulu ke nomor DANA/GoPay di atas, lalu unggah foto bukti transfer pada kolom di bawah ini.
                </div>

                <div class="form-group" id="group-bukti">
                    <label>Upload Bukti Pembayaran (Wajib untuk DANA/GoPay)</label>
                    <input type="file" name="bukti" id="input-bukti" accept="image/*">
                </div>

                <button type="submit" name="proses_checkout" class="btn">Proses Pesanan Sekarang ➔</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>

    <script>
        function toggleBukti() {
            var metode = document.getElementById('metode').value;
            var groupBukti = document.getElementById('group-bukti');
            var infoTransfer = document.getElementById('info-transfer');
            var inputBukti = document.getElementById('input-bukti');

            if (metode === 'COD') {
                groupBukti.style.display = 'none';
                infoTransfer.style.display = 'none';
                inputBukti.required = false;
            } else {
                groupBukti.style.display = 'block';
                infoTransfer.style.display = 'block';
                inputBukti.required = true;
            }
        }
        toggleBukti();
    </script>
</body>
</html>