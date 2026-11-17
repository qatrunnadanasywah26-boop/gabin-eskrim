<?php
session_start();
include 'koneksi.php';

// Ambil ID pesanan terakhir atau berdasarkan parameter URL
$id_order = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($koneksi, "SELECT * FROM orders WHERE id = '$id_order'");
$order = mysqli_fetch_assoc($query);

if (!$order) {
    header("Location: produk.php");
    exit;
}

// Nomor WhatsApp admin (sesuaikan nomornya di sini, awali dengan 62 tanpa angka 0 di depan)
$nomor_admin_wa = "6281234567890"; 
$nomor_admin_tampil = "0812-3456-7890"; // Format rapi untuk dibaca

// Susun teks pesan otomatis untuk WhatsApp
$pesan_wa = "Halo Admin BinIce, saya ingin mengonfirmasi pesanan baru:\n\n" .
            "No. Pesanan: #{$order['id']}\n" .
            "Nama: {$order['nama_pelanggan']}\n" .
            "No. WhatsApp: {$order['whatsapp']}\n" .
            "Alamat: {$order['alamat']}\n" .
            "Metode Pembayaran: {$order['metode_pembayaran']}\n\n" .
            "Rincian Pesanan:\n{$order['rincian_pesanan']}\n" .
            "Total Harga: Rp " . number_format($order['total_harga'], 0, ',', '.') . "\n\n" .
            "Bukti transfer dan bill sudah saya lampirkan. Mohon segera diproses ya kak, terima kasih! 🍦✨";

$link_wa = "https://wa.me/" . $nomor_admin_wa . "?text=" . urlencode($pesan_wa);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan / Bill - BinIce</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fce4ec; color: #593539; min-height: 100vh; display: flex; flex-direction: column; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: transparent; }
        .logo { font-size: 1.8rem; font-weight: 800; color: #d81b60; letter-spacing: 1px; }
        nav { display: flex; gap: 12px; align-items: center; }
        nav a { text-decoration: none; color: #593539; font-weight: 600; font-size: 0.95rem; padding: 8px 18px; border-radius: 20px; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(216, 27, 96, 0.2); transition: 0.3s; }
        nav a:hover { background: #d81b60; color: white; border-color: #d81b60; }
        main { flex: 1; padding: 30px 8%; max-width: 650px; margin: 0 auto; width: 100%; }
        .bill-card { background: white; padding: 35px; border-radius: 25px; box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08); text-align: center; }
        .bill-card h2 { color: #d81b60; font-size: 1.8rem; margin-bottom: 5px; }
        .bill-sub { color: #880e4f; font-size: 0.95rem; margin-bottom: 25px; }
        .bill-details { text-align: left; background: #fff9fa; border: 1px solid #f8bbd0; border-radius: 15px; padding: 20px; margin-bottom: 25px; font-size: 0.95rem; line-height: 1.6; }
        .bill-details strong { color: #880e4f; }
        .rincian-box { background: white; border: 1px dashed #f8bbd0; padding: 12px; border-radius: 10px; margin-top: 10px; white-space: pre-line; font-size: 0.9rem; color: #555; }
        .bukti-img { width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 2px solid #f8bbd0; margin-top: 10px; }
        .instruction-box { background: #ffebee; border: 1px dashed #d81b60; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem; color: #c2185b; text-align: center; font-weight: bold; }
        .btn-wa { display: block; width: 100%; background: #25d366; color: white; padding: 14px; border-radius: 25px; text-decoration: none; font-weight: bold; text-align: center; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3); transition: 0.3s; font-size: 1rem; margin-top: 15px; }
        .btn-wa:hover { background: #1ebe5d; }
        .btn-home { display: block; width: 100%; background: #636e72; color: white; padding: 12px; border-radius: 25px; text-decoration: none; font-weight: bold; text-align: center; margin-top: 10px; }
        .btn-home:hover { background: #2d3436; }
        footer { background: #ad1457; color: white; text-align: center; padding: 20px; margin-top: auto; font-size: 0.9rem; }
    </style>
</head>
<body>
    <header>
        <div class="logo">🍦 BinIce</div>
        <nav>
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <main>
        <div class="bill-card">
            <h2>🎉 Pesanan Berhasil Dibuat!</h2>
            <p class="bill-sub">Simpan struk bill ini untuk proses verifikasi pesanan.</p>

            <div class="instruction-box">
                ⚠️ Wajib kirim/screenshot bill ini beserta bukti transfer ke WhatsApp Admin (<strong><?= $nomor_admin_tampil ?></strong>) agar pesanan dapat segera diproses!
            </div>

            <div class="bill-details">
                <p><strong>No. Pesanan:</strong> #<?= $order['id'] ?></p>
                <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($order['nama_pelanggan']) ?></p>
                <p><strong>WhatsApp:</strong> <?= htmlspecialchars($order['whatsapp']) ?></p>
                <p><strong>Alamat:</strong> <?= htmlspecialchars($order['alamat']) ?></p>
                <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($order['metode_pembayaran']) ?></p>
                
                <div style="margin-top: 15px;">
                    <strong>Rincian Pesanan:</strong>
                    <div class="rincian-box"><?= htmlspecialchars($order['rincian_pesanan']) ?></div>
                </div>

                <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f8bbd0; padding-top: 12px;">
                    <strong>Total Pembayaran:</strong>
                    <span style="color: #d81b60; font-size: 1.2rem; font-weight: bold;">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                </div>

                <?php if (!empty($order['bukti_pembayaran'])): ?>
                    <div style="margin-top: 15px; text-align: center;">
                        <strong>Foto Bukti Pembayaran:</strong><br>
                        <img src="uploads/<?= htmlspecialchars($order['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" class="bukti-img">
                    </div>
                <?php endif; ?>
            </div>

            <a href="<?= $link_wa ?>" target="_blank" class="btn-wa">📱 Kirim Bill ke WhatsApp Admin (<?= $nomor_admin_tampil ?>) ➔</a>
            <a href="produk.php" class="btn-home">🏠 Kembali ke Beranda Produk</a>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>
</body>
</html>