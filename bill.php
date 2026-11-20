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

// Nomor WhatsApp admin
$nomor_admin_wa = "6289530628664"; 
$nomor_admin_tampil = "0895-3062-8664";

// Susun teks pesan otomatis untuk WhatsApp
$pesan_wa = "Halo Admin BinIce, saya ingin mengonfirmasi pesanan gabin es krim saya:\n\n" .
            "No. Pesanan: #{$order['id']}\n" .
            "Nama: {$order['nama_pelanggan']}\n" .
            "No. WhatsApp: {$order['whatsapp']}\n" .
            "Alamat: {$order['alamat']}\n" .
            "Metode Pembayaran: {$order['metode_pembayaran']}\n\n" .
            "Rincian Pesanan:\n{$order['rincian_pesanan']}\n" .
            "Total Harga: Rp " . number_format($order['total_harga'], 0, ',', '.') . "\n\n" .
            "Mohon segera diproses ya kak, terima kasih! 🍦✨";

$link_wa = "https://wa.me/" . $nomor_admin_wa . "?text=" . urlencode($pesan_wa);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan / Bill - BinIce</title>
    <!-- Import Font Estetik dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body { 
            background-color: #fce4ec; 
            background-image: url('bgbaru.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #593539; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            overflow-x: hidden;
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
            position: relative;
            z-index: 100;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo-img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.25);
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.08);
        }

        nav { display: flex; gap: 12px; align-items: center; }
        nav a { 
            text-decoration: none; 
            color: #593539; 
            font-weight: 500; 
            font-size: 0.95rem; 
            padding: 8px 18px; 
            border-radius: 20px; 
            background: rgba(255, 255, 255, 0.78); 
            border: 1px solid rgba(216, 27, 96, 0.2); 
            transition: 0.3s; 
        }
        nav a:hover { background: #d81b60; color: white; border-color: #d81b60; transform: translateY(-2px); }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 6px;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(216, 27, 96, 0.3);
            padding: 10px 12px;
            border-radius: 12px;
            z-index: 101;
        }

        .menu-toggle span {
            display: block;
            width: 24px;
            height: 3px;
            background-color: #d81b60;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }
        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        .menu-toggle.active span:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }

        /* Layout Utama: Horizontal di Desktop/Laptop */
        main { 
            flex: 1; 
            padding: 20px 5% 45px 5%; 
            max-width: 1240px; 
            margin: 0 auto; 
            width: 100%; 
        }

        .bill-layout {
            display: flex;
            flex-direction: row;
            gap: 24px;
            align-items: flex-start;
            justify-content: center;
        }
        
        .bill-card { 
            flex: 1.75;
            background: rgba(255, 255, 255, 0.94); 
            backdrop-filter: blur(6px);
            padding: 28px 30px; 
            border-radius: 25px; 
            box-shadow: 0 10px 30px rgba(216, 27, 96, 0.16); 
            border: 2px solid rgba(255, 255, 255, 0.85);
            text-align: center; 
        }

        /* Panel Kanan: Hubungi Admin & QR WhatsApp */
        .admin-qris-panel {
            flex: 0.95;
            min-width: 310px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(6px);
            padding: 26px 22px;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(216, 27, 96, 0.16);
            border: 2px solid rgba(216, 27, 96, 0.25);
            text-align: center;
            position: sticky;
            top: 24px;
        }

        .admin-qris-panel h3 {
            font-family: 'Fredoka', cursive, sans-serif;
            color: #c2185b;
            font-size: 1.3rem;
            margin-bottom: 6px;
        }

        .admin-qris-sub {
            font-size: 0.84rem;
            color: #6d4c52;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .qris-box {
            background: #fff9fa;
            border: 2px dashed #f48fb1;
            border-radius: 20px;
            padding: 14px;
            margin-bottom: 16px;
        }

        .qris-header-badge {
            display: inline-block;
            background: #25d366;
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            padding: 3px 12px;
            border-radius: 999px;
            margin-bottom: 10px;
        }

        .qris-img-wrap {
            background: #ffffff;
            padding: 10px;
            border-radius: 16px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(216, 27, 96, 0.08);
            border: 1px solid #f8bbd0;
        }

        .qris-img {
            width: 165px;
            height: 165px;
            object-fit: contain;
            border-radius: 12px;
            display: block;
            transition: transform 0.3s ease;
        }

        .qris-img:hover {
            transform: scale(1.04);
        }

        .qris-merchant-name {
            margin-top: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            color: #880e4f;
        }

        .qris-dummy-note {
            margin-top: 4px;
            font-size: 0.74rem;
            color: #ad1457;
            background: #fce4ec;
            padding: 5px 10px;
            border-radius: 10px;
            font-weight: 600;
        }

        .bill-card h2 { 
            font-family: 'Fredoka', cursive, sans-serif;
            color: #d81b60; 
            font-size: 1.65rem; 
            margin-bottom: 4px; 
            font-weight: 600;
        }

        .bill-sub { color: #880e4f; font-size: 0.92rem; margin-bottom: 16px; font-weight: 500; }

        .instruction-box { 
            background: #ffebee; 
            border: 1.5px dashed #d81b60; 
            padding: 12px 16px; 
            border-radius: 15px; 
            margin-bottom: 18px; 
            font-size: 0.88rem; 
            color: #c2185b; 
            text-align: center; 
            font-weight: 500;
            line-height: 1.45;
        }

        /* Isi kartu kiri dibuat horizontal menyamping di desktop/laptop */
        .bill-details-horizontal {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 18px;
            text-align: left;
            background: #fff9fa;
            border: 1px solid #f8bbd0;
            border-radius: 20px;
            padding: 20px;
            font-size: 0.92rem;
            line-height: 1.65;
        }

        .bill-col-left {
            padding-right: 14px;
            border-right: 1px dashed #f48fb1;
        }

        .bill-col-right {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .bill-details-horizontal p {
            margin-bottom: 7px;
        }

        .bill-details-horizontal strong { color: #880e4f; font-weight: 600; }

        .rincian-box { 
            background: white; 
            border: 1px dashed #f8bbd0; 
            padding: 12px 14px; 
            border-radius: 12px; 
            margin-top: 6px; 
            white-space: pre-line; 
            font-size: 0.88rem; 
            color: #555; 
        }

        .bill-total-row {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px dashed #f8bbd0;
            padding-top: 12px;
            gap: 10px;
        }

        .bukti-container {
            margin-top: 14px; 
            text-align: center;
            background: white;
            padding: 12px;
            border-radius: 15px;
            border: 1px solid #f8bbd0;
        }

        .bukti-img { 
            width: 130px; 
            height: 130px; 
            object-fit: cover; 
            border-radius: 12px; 
            border: 2px solid #f8bbd0; 
            margin-top: 6px; 
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
            padding: 13px 16px; 
            border-radius: 25px; 
            text-decoration: none; 
            font-weight: 500; 
            text-align: center; 
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3); 
            transition: all 0.3s ease; 
            font-size: 0.98rem; 
        }
        .btn-wa:hover { background: #1ebe5d; transform: translateY(-2px); }

        .btn-home { 
            font-family: 'Fredoka', cursive, sans-serif;
            display: block; 
            width: 100%; 
            background: #636e72; 
            color: white; 
            padding: 12px 16px; 
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

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Tampilan HP: kembali ke susunan vertikal rapi */
        @media(max-width: 900px) {
            header { padding: 15px 5%; }
            .menu-toggle { display: flex; }
            nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(255, 240, 245, 0.98);
                backdrop-filter: blur(10px);
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                gap: 12px;
                box-shadow: 0 10px 25px rgba(128, 0, 0, 0.1);
                animation: slideDown 0.3s ease-out forwards;
            }
            nav.active { display: flex; }
            nav a { width: 80%; text-align: center; background: white; }

            main { padding: 15px 5% 40px 5%; max-width: 680px; }
            .bill-layout {
                flex-direction: column;
                gap: 20px;
                align-items: stretch;
            }
            .bill-details-horizontal {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 16px;
            }
            .bill-col-left {
                padding-right: 0;
                border-right: none;
                border-bottom: 1px dashed #f48fb1;
                padding-bottom: 10px;
            }
            .admin-qris-panel {
                position: static;
                min-width: unset;
                padding: 24px 18px;
            }
            .bill-card { padding: 24px 18px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="index.php">
                <img src="kawai.png" alt="Logo BinIce" class="logo-img">
            </a>
        </div>
        <!-- Tombol Garis Tiga (Hamburger Menu) untuk HP -->
        <button class="menu-toggle" id="menuToggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav id="navMenu">
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <main>
        <div class="bill-layout">
            <!-- Bagian Kiri: Struk Bill Pesanan (Horizontal di Desktop/Laptop) -->
            <div class="bill-card">
                <h2>🎉 Pesanan Berhasil Dibuat!</h2>
                <p class="bill-sub">Simpan struk bill ini untuk proses verifikasi pesanan.</p>

                <div class="instruction-box">
                    ⚠️ Wajib kirim/screenshot bill ini beserta bukti transfer ke WhatsApp Admin (<strong><?= $nomor_admin_tampil ?></strong>) agar pesanan dapat segera diproses!
                </div>

                <div class="bill-details-horizontal">
                    <div class="bill-col-left">
                        <p><strong>No. Pesanan:</strong> #<?= $order['id'] ?></p>
                        <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($order['nama_pelanggan']) ?></p>
                        <p><strong>WhatsApp:</strong> <?= htmlspecialchars($order['whatsapp']) ?></p>
                        <p><strong>Alamat:</strong> <?= htmlspecialchars($order['alamat']) ?></p>
                        <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($order['metode_pembayaran']) ?></p>
                    </div>

                    <div class="bill-col-right">
                        <div>
                            <strong>Rincian Pesanan:</strong>
                            <div class="rincian-box"><?= htmlspecialchars($order['rincian_pesanan']) ?></div>
                        </div>

                        <div class="bill-total-row">
                            <strong style="font-size: 1rem;">Total Pembayaran:</strong>
                            <span style="color: #d81b60; font-size: 1.2rem; font-weight: 700; font-family: 'Fredoka', cursive;">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                        </div>

                        <?php if (!empty($order['bukti_pembayaran'])): ?>
                            <div class="bukti-container">
                                <strong style="color: #880e4f; font-size: 0.88rem;">Foto Bukti Pembayaran:</strong><br>
                                <a href="uploads/<?= htmlspecialchars($order['bukti_pembayaran']) ?>" target="_blank" title="Klik untuk memperbesar">
                                    <img src="uploads/<?= htmlspecialchars($order['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" class="bukti-img">
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Bagian Paling Kanan: Hubungi Admin untuk Proses Gabin & QR WhatsApp -->
            <aside class="admin-qris-panel">
                <h3>🍦 Hubungi Admin untuk Proses Gabin</h3>
                <p class="admin-qris-sub">
                    Scan QR WhatsApp atau klik tombol di bawah untuk konfirmasi pesanan <strong>#<?= $order['id'] ?></strong> ke admin (<strong><?= $nomor_admin_tampil ?></strong>).
                </p>

                <div class="qris-box">
                    <span class="qris-header-badge">QR WHATSAPP ADMIN</span>
                    <div class="qris-img-wrap">
                        <a href="<?= $link_wa ?>" target="_blank" title="Scan QR atau klik untuk chat WhatsApp Admin">
                            <img src="qr dana.png" alt="QR WhatsApp Admin BinIce" class="qris-img">
                        </a>
                    </div>
                    <div class="qris-merchant-name">BINICE ADMIN • <?= $nomor_admin_tampil ?></div>
                    <div class="qris-dummy-note">Scan QR ini di WhatsApp untuk chat langsung</div>
                </div>

                <a href="<?= $link_wa ?>" target="_blank" class="btn-wa">📱 Hubungi Admin Proses Gabin ➔</a>
                <a href="produk.php" class="btn-home">🏠 Kembali ke Beranda Produk</a>
            </aside>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> BinIce. Semua Hak Dilindungi.</p>
    </footer>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', () => {
                menuToggle.classList.toggle('active');
                navMenu.classList.toggle('active');
            });
        }
    </script>
</body>
</html>