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

// Nomor WhatsApp admin sudah disesuaikan ke nomor kamu
$nomor_admin_wa = "6289530628664"; 
$nomor_admin_tampil = "0895-3062-8664"; // Format rapi untuk dibaca

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
    <!-- Import Font Estetik dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body { 
            background-color: #fce4ec; 
            color: #593539; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            animation: fadeInBody 0.8s ease-in-out;
        }

        @keyframes fadeInBody {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 20px 8%; 
            background: transparent; 
        }

        .logo { 
            font-family: 'Fredoka', cursive, sans-serif;
            font-size: 1.8rem; 
            font-weight: 600; 
            color: #d81b60; 
            letter-spacing: 1px; 
        }

        nav { display: flex; gap: 12px; align-items: center; }
        nav a { 
            text-decoration: none; 
            color: #593539; 
            font-weight: 500; 
            font-size: 0.95rem; 
            padding: 8px 18px; 
            border-radius: 20px; 
            background: rgba(255, 255, 255, 0.75); 
            border: 1px solid rgba(216, 27, 96, 0.2); 
            transition: 0.3s; 
        }
        nav a:hover { background: #d81b60; color: white; border-color: #d81b60; transform: translateY(-2px); }

        main { flex: 1; padding: 20px 8% 40px 8%; max-width: 680px; margin: 0 auto; width: 100%; }
        
        .bill-card { 
            background: white; 
            padding: 35px; 
            border-radius: 25px; 
            box-shadow: 0 10px 30px rgba(216, 27, 96, 0.12); 
            border: 2px solid rgba(255, 182, 193, 0.6);
            text-align: center; 
        }

        .bill-card h2 { 
            font-family: 'Fredoka', cursive, sans-serif;
            color: #d81b60; 
            font-size: 1.7rem; 
            margin-bottom: 5px; 
            font-weight: 600;
        }

        .bill-sub { color: #880e4f; font-size: 0.95rem; margin-bottom: 20px; font-weight: 500; }

        .instruction-box { 
            background: #ffebee; 
            border: 1.5px dashed #d81b60; 
            padding: 14px 18px; 
            border-radius: 15px; 
            margin-bottom: 25px; 
            font-size: 0.9rem; 
            color: #c2185b; 
            text-align: center; 
            font-weight: 500;
            line-height: 1.5;
        }

        .bill-details { 
            text-align: left; 
            background: #fff9fa; 
            border: 1px solid #f8bbd0; 
            border-radius: 20px; 
            padding: 22px; 
            margin-bottom: 25px; 
            font-size: 0.95rem; 
            line-height: 1.7; 
        }

        .bill-details p {
            margin-bottom: 8px;
        }

        .bill-details strong { color: #880e4f; font-weight: 600; }

        .rincian-box { 
            background: white; 
            border: 1px dashed #f8bbd0; 
            padding: 14px; 
            border-radius: 12px; 
            margin-top: 8px; 
            white-space: pre-line; 
            font-size: 0.9rem; 
            color: #555; 
        }

        .bukti-container {
            margin-top: 20px; 
            text-align: center;
            background: white;
            padding: 15px;
            border-radius: 15px;
            border: 1px solid #f8bbd0;
        }

        .bukti-img { 
            width: 160px; 
            height: 160px; 
            object-fit: cover; 
            border-radius: 12px; 
            border: 2px solid #f8bbd0; 
            margin-top: 8px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .bukti-img:hover {
            transform: scale(1.05);
        }

        .btn-wa { 
            font-family: 'Fredoka', cursive, sans-serif;
            display: block; 
            width: 100%; 
            background: #25d366; 
            color: white; 
            padding: 14px; 
            border-radius: 25px; 
            text-decoration: none; 
            font-weight: 500; 
            text-align: center; 
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3); 
            transition: all 0.3s ease; 
            font-size: 1.05rem; 
            margin-top: 15px; 
        }
        .btn-wa:hover { background: #1ebe5d; transform: translateY(-2px); }

        .btn-home { 
            font-family: 'Fredoka', cursive, sans-serif;
            display: block; 
            width: 100%; 
            background: #636e72; 
            color: white; 
            padding: 12px; 
            border-radius: 25px; 
            text-decoration: none; 
            font-weight: 500; 
            text-align: center; 
            margin-top: 10px; 
            transition: all 0.3s ease;
        }
        .btn-home:hover { background: #2d3436; transform: translateY(-2px); }

        footer { 
            font-family: 'Poppins', sans-serif;
            background: #ad1457; 
            color: white; 
            text-align: center; 
            padding: 18px; 
            margin-top: auto; 
            font-size: 0.9rem; 
            font-weight: 500;
        }
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
                
                <div style="margin-top: 12px;">
                    <strong>Rincian Pesanan:</strong>
                    <div class="rincian-box"><?= htmlspecialchars($order['rincian_pesanan']) ?></div>
                </div>

                <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #f8bbd0; padding-top: 12px;">
                    <strong style="font-size: 1.05rem;">Total Pembayaran:</strong>
                    <span style="color: #d81b60; font-size: 1.25rem; font-weight: 700; font-family: 'Fredoka', cursive;">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                </div>

                <?php if (!empty($order['bukti_pembayaran'])): ?>
                    <div class="bukti-container">
                        <strong style="color: #880e4f; font-size: 0.9rem;">Foto Bukti Pembayaran:</strong><br>
                        <a href="uploads/<?= htmlspecialchars($order['bukti_pembayaran']) ?>" target="_blank" title="Klik untuk memperbesar">
                            <img src="uploads/<?= htmlspecialchars($order['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" class="bukti-img">
                        </a>
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