<?php
session_start();
include 'koneksi.php';

// Harga fix sesuai ketentuan
$harga_medium = 6000;

// Logika Tambah / Kurang Jumlah Item di Keranjang
if (isset($_GET['aksi']) && isset($_GET['id']) && isset($_GET['ukuran'])) {
    $id_item = intval($_GET['id']);
    $ukuran_item = $_GET['ukuran'];
    $aksi = $_GET['aksi'];

    if ($aksi == 'tambah') {
        $q_stok = mysqli_query($koneksi, "SELECT nama, stok_large, stok_medium FROM products WHERE id = '$id_item'");
        $row_stok = mysqli_fetch_assoc($q_stok);
        $max_stok = ($ukuran_item == 'medium') ? intval($row_stok['stok_medium'] ?? 999) : intval($row_stok['stok_large'] ?? 999);
        $jumlah_saat_ini = intval($_SESSION['keranjang'][$id_item][$ukuran_item] ?? 0);

        if ($jumlah_saat_ini + 1 > $max_stok) {
            $_SESSION['notif_stok'] = "Stok " . ($row_stok['nama'] ?? 'produk') . " ukuran " . ucfirst($ukuran_item) . " maksimal " . $max_stok . " pcs.";
        } else {
            $_SESSION['keranjang'][$id_item][$ukuran_item] = $jumlah_saat_ini + 1;
        }
    } elseif ($aksi == 'kurang') {
        if (isset($_SESSION['keranjang'][$id_item][$ukuran_item])) {
            $_SESSION['keranjang'][$id_item][$ukuran_item] -= 1;
            if ($_SESSION['keranjang'][$id_item][$ukuran_item] <= 0) {
                unset($_SESSION['keranjang'][$id_item][$ukuran_item]);
            }
        }
    }

    if (isset($_SESSION['keranjang'][$id_item]) && empty($_SESSION['keranjang'][$id_item])) {
        unset($_SESSION['keranjang'][$id_item]);
    }

    header('Location: keranjang.php');
    exit;
}

// Logika Hapus item dari keranjang berdasarkan ID dan Ukuran
if (isset($_GET['hapus_id']) && isset($_GET['ukuran'])) {
    $id_hapus = intval($_GET['hapus_id']);
    $ukuran_hapus = $_GET['ukuran'];

    unset($_SESSION['keranjang'][$id_hapus][$ukuran_hapus]);

    if (empty($_SESSION['keranjang'][$id_hapus])) {
        unset($_SESSION['keranjang'][$id_hapus]);
    }

    header('Location: keranjang.php');
    exit;
}

$notif_stok = "";
if (!empty($_SESSION['notif_stok'])) {
    $notif_stok = $_SESSION['notif_stok'];
    unset($_SESSION['notif_stok']);
}

// Hitung total item di keranjang untuk badge navbar
$total_keranjang = 0;
if (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $arr_ukuran) {
        if (is_array($arr_ukuran)) {
            foreach ($arr_ukuran as $ukuran => $jumlah) {
                $total_keranjang += (int)$jumlah;
            }
        } else {
            $total_keranjang += (int)$arr_ukuran;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - BinIce</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        html, body {
            height: 100%;
        }

        body {
            background-image: url('bg kr2.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #593539;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            animation: fadeInBody 0.6s ease-in-out;
        }

        @keyframes fadeInBody {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 8%;
            background: transparent;
            position: relative;
            z-index: 100;
        }

        .logo-container {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .logo-img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.25);
            transition: transform 0.3s ease;
            display: block;
        }

        .logo-img:hover {
            transform: scale(1.08);
        }

        nav {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: center;
            flex: 2;
        }

        nav a {
            text-decoration: none;
            color: #593539;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 8px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(216, 27, 96, 0.2);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.25s ease;
        }

        nav a:hover, nav a.active {
            background: #d81b60;
            color: white;
            border-color: #d81b60;
            transform: translateY(-2px);
        }

        .nav-icons {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: flex-end;
            flex: 1;
        }

        .cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #593539;
            background: rgba(255, 255, 255, 0.9);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid rgba(216, 27, 96, 0.3);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.25s ease;
        }

        .cart-btn:hover {
            background: #fff;
            color: #d81b60;
        }

        .cart-badge {
            background: #d81b60;
            color: white;
            font-size: 0.75rem;
            padding: 2px 7px;
            border-radius: 50%;
            font-weight: bold;
        }

        .nav-auth-desktop a {
            text-decoration: none;
            font-size: 0.9rem;
            background: #d81b60;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(216, 27, 96, 0.3);
            transition: all 0.25s ease;
            display: inline-block;
        }

        .nav-auth-desktop a:hover {
            background: #ad1457;
            transform: translateY(-2px);
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
            background: rgba(255, 255, 255, 0.9);
            border: 1.5px solid rgba(216, 27, 96, 0.35);
            padding: 10px 12px;
            border-radius: 12px;
            z-index: 101;
        }

        .menu-toggle span {
            display: block;
            width: 22px;
            height: 2.8px;
            background-color: #d81b60;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: translateY(7.8px) rotate(45deg);
        }
        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        .menu-toggle.active span:nth-child(3) {
            transform: translateY(-7.8px) rotate(-45deg);
        }

        .mobile-auth { display: none; }

        main {
            flex: 1;
            padding: 16px 6% 40px 6%;
            max-width: 1020px;
            margin: 0 auto;
            width: 100%;
        }

        .notif-stok {
            background: #fff3cd;
            color: #7a4900;
            border: 1.5px solid #ffc107;
            padding: 10px 14px;
            border-radius: 14px;
            margin-bottom: 14px;
            font-size: 0.84rem;
            font-weight: 600;
        }

        .page-title-box {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(8px);
            padding: 16px 24px;
            border-radius: 20px;
            margin-bottom: 18px;
            box-shadow: 0 8px 24px rgba(216, 27, 96, 0.12);
            border: 1.5px solid rgba(255, 182, 193, 0.9);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        h2 {
            font-family: 'Fredoka', cursive, sans-serif;
            color: #800000;
            font-size: 1.45rem;
            font-weight: 600;
        }

        .subtitle-lucu {
            font-size: 0.82rem;
            color: #d81b60;
            font-weight: 600;
            background: #fce4ec;
            padding: 6px 14px;
            border-radius: 14px;
            border: 1px solid #f8bbd0;
        }

        /* Tampilan Desktop: Tabel Rapi */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 22px;
            box-shadow: 0 10px 28px rgba(216, 27, 96, 0.14);
            padding: 20px;
            margin-bottom: 20px;
            border: 1.5px solid #ffd6e5;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 13px 16px; text-align: left; border-bottom: 1px solid #f8bbd0; font-size: 0.92rem; }
        th {
            font-family: 'Fredoka', cursive, sans-serif;
            background: #f8bbd0;
            color: #880e4f;
            font-weight: 500;
            border-radius: 10px;
        }

        .qty-box {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff0f5;
            padding: 3px 6px;
            border-radius: 10px;
            border: 1px solid #f8bbd0;
        }

        .qty-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: #d81b60;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 50%;
            font-size: 0.85rem;
            line-height: 1;
            transition: 0.2s;
        }

        .qty-btn:hover {
            background: #ad1457;
        }

        .qty-value {
            font-weight: 700;
            min-width: 18px;
            text-align: center;
            color: #800000;
            font-size: 0.86rem;
        }

        .btn-batal {
            display: inline-block;
            background: #ffeef4;
            color: #c2185b;
            border: 1px solid #f48fb1;
            padding: 4px 10px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.75rem;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-batal:hover {
            background: #d81b60;
            color: #fff;
        }

        .badge-ukuran {
            display: inline-block;
            background: #fce4ec;
            color: #d81b60;
            padding: 2px 8px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 700;
            border: 1px solid rgba(216,27,96,0.25);
        }

        /* Kartu Keranjang Shopee Khusus HP (disembunyikan di desktop) */
        .mobile-cart-list {
            display: none;
        }

        .shopee-cart-item {
            background: rgba(255, 255, 255, 0.96);
            border: 1.5px solid #ffd6e5;
            border-radius: 16px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 6px 18px rgba(216, 27, 96, 0.1);
        }

        .shopee-item-top {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .shopee-thumb {
            width: 58px;
            height: 58px;
            border-radius: 12px;
            object-fit: cover;
            border: 1.5px solid #f8bbd0;
            flex-shrink: 0;
        }

        .shopee-item-meta {
            flex: 1;
            min-width: 0;
        }

        .shopee-item-name {
            font-size: 0.86rem;
            font-weight: 700;
            color: #6d1b2b;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .shopee-item-subrow {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .shopee-unit-price {
            font-size: 0.76rem;
            color: #7a4950;
            font-weight: 600;
        }

        .shopee-item-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed #f8bbd0;
            gap: 8px;
        }

        .shopee-subtotal-wrap {
            text-align: right;
        }

        .shopee-subtotal-label {
            font-size: 0.68rem;
            color: #880e4f;
            font-weight: 600;
            display: block;
        }

        .shopee-subtotal-val {
            font-family: 'Fredoka', cursive, sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #d81b60;
        }

        .shopee-total-bar {
            background: rgba(255, 255, 255, 0.96);
            border: 1.5px solid #f48fb1;
            border-radius: 16px;
            padding: 12px 14px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 6px 18px rgba(216, 27, 96, 0.12);
        }

        .shopee-total-label {
            font-size: 0.86rem;
            font-weight: 700;
            color: #800000;
        }

        .shopee-total-amount {
            font-family: 'Fredoka', cursive, sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #d81b60;
        }

        .btn {
            font-family: 'Fredoka', cursive, sans-serif;
            display: inline-block;
            background: #800000;
            color: white;
            padding: 11px 22px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(128, 0, 0, 0.2);
            transition: all 0.25s ease;
            font-size: 0.92rem;
        }

        .btn:hover {
            background: #500000;
            transform: translateY(-2px);
        }

        .btn-secondary { background: #f8bbd0; color: #880e4f; }
        .btn-secondary:hover { background: #f48fb1; }

        .cart-actions-bar {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        footer {
            background: rgba(173, 20, 87, 0.95);
            color: white;
            text-align: center;
            padding: 15px 20px;
            width: 100%;
            margin-top: auto;
            font-size: 0.86rem;
            font-weight: 500;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media(max-width: 900px) {
            header { padding: 14px 5%; justify-content: space-between; }
            .logo-container, .nav-icons { flex: unset; }
            .nav-icons { display: none; }
            .logo-img { width: 56px; height: 56px; }
            .menu-toggle { display: flex; }

            .mobile-auth {
                display: flex;
                flex-direction: column;
                gap: 10px;
                width: 85%;
                align-items: center;
                margin-top: 4px;
            }

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
                padding: 20px 0 24px 0;
                gap: 12px;
                box-shadow: 0 10px 25px rgba(128, 0, 0, 0.12);
                border-bottom: 2px solid rgba(255, 182, 193, 0.85);
                animation: slideDown 0.25s ease-out forwards;
            }

            nav a {
                width: 85%;
                text-align: center;
                background: white;
            }

            nav.active { display: flex; }

            main { padding: 10px 4% 32px 4%; }
            .page-title-box { padding: 12px 14px; border-radius: 16px; margin-bottom: 12px; }
            h2 { font-size: 1.15rem; }
            .subtitle-lucu { font-size: 0.72rem; padding: 4px 10px; }

            /* Sembunyikan tabel lebar di HP, tampilkan kartu ala Shopee yang ringkas */
            .table-responsive { display: none; }
            .mobile-cart-list { display: block; }

            .cart-actions-bar {
                flex-direction: row;
                gap: 8px;
            }
            .cart-actions-bar .btn {
                flex: 1;
                padding: 10px 12px;
                font-size: 0.82rem;
                text-align: center;
                border-radius: 14px;
            }
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

        <button class="menu-toggle" id="menuToggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="navMenu">
            <a href="home.php">Home</a>
            <a href="produk.php">Products</a>
            <a href="keranjang.php" class="active">Keranjang</a>
            <a href="contact.php">Contact</a>

            <div class="mobile-auth">
                <a href="keranjang.php" style="background: #fff; color: #d81b60; font-weight: bold;">
                    🛒 Keranjang <span class="cart-badge"><?= $total_keranjang ?></span>
                </a>
                <?php if(isset($_SESSION['admin_logged'])): ?>
                    <a href="admin_dashboard.php" style="background: #d81b60; color: white;">Admin Panel</a>
                <?php else: ?>
                    <a href="login.php" style="background: #d81b60; color: white;">🔑 Login Admin</a>
                <?php endif; ?>
            </div>
        </nav>

        <div class="nav-icons">
            <a href="keranjang.php" class="cart-btn" title="Keranjang">
                🛒 Keranjang <span class="cart-badge"><?= $total_keranjang ?></span>
            </a>

            <div class="nav-auth-desktop">
                <?php if(isset($_SESSION['admin_logged'])): ?>
                    <a href="admin_dashboard.php">Admin</a>
                <?php else: ?>
                    <a href="login.php">🔑 Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <div class="page-title-box">
            <h2>🍨 Keranjang Belanja Kamu</h2>
            <span class="subtitle-lucu">✨ Yuk, wujudkan hari manis-mu sekarang!</span>
        </div>

        <?php if (!empty($notif_stok)): ?>
            <div class="notif-stok">⚠️ <?= htmlspecialchars($notif_stok) ?></div>
        <?php endif; ?>

        <?php if (empty($_SESSION['keranjang'])): ?>
            <div style="background: rgba(255, 255, 255, 0.94); backdrop-filter: blur(8px); padding: 36px 20px; border-radius: 22px; text-align: center; box-shadow: 0 10px 30px rgba(216, 27, 96, 0.12); border: 2px solid rgba(255, 182, 193, 0.9);">
                <p style="font-size: 1rem; margin-bottom: 18px; color: #7b5558; font-weight: 600;">Waduh, keranjang belanjanya masih kosong nih! 🥺</p>
                <a href="produk.php" class="btn">🍦 Pilih Es Krim Dulu Yuk!</a>
            </div>
        <?php else: ?>
            <?php
            $items_keranjang = [];
            $total_bayar = 0;

            foreach ($_SESSION['keranjang'] as $id => $arr_ukuran) {
                $id_safe = intval($id);
                $query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id_safe'");
                $p = mysqli_fetch_assoc($query);

                if (!$p || !is_array($arr_ukuran)) continue;

                foreach ($arr_ukuran as $ukuran => $jumlah) {
                    $jumlah = intval($jumlah);
                    if ($jumlah <= 0) continue;

                    $harga_satuan = ($ukuran == 'medium') ? $harga_medium : intval($p['harga']);
                    $subtotal = $harga_satuan * $jumlah;
                    $total_bayar += $subtotal;

                    $items_keranjang[] = [
                        'id' => $id_safe,
                        'nama' => $p['nama'],
                        'gambar' => $p['gambar'] ?? '',
                        'ukuran' => $ukuran,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $harga_satuan,
                        'subtotal' => $subtotal
                    ];
                }
            }
            ?>

            <!-- Tampilan Desktop: Tabel -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items_keranjang as $item): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($item['nama']) ?></strong>
                                <span class="badge-ukuran"><?= htmlspecialchars(ucfirst($item['ukuran'])) ?></span>
                            </td>
                            <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                            <td>
                                <div class="qty-box">
                                    <a href="keranjang.php?aksi=kurang&id=<?= $item['id'] ?>&ukuran=<?= urlencode($item['ukuran']) ?>" class="qty-btn">-</a>
                                    <span class="qty-value"><?= $item['jumlah'] ?></span>
                                    <a href="keranjang.php?aksi=tambah&id=<?= $item['id'] ?>&ukuran=<?= urlencode($item['ukuran']) ?>" class="qty-btn">+</a>
                                </div>
                            </td>
                            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            <td>
                                <a href="keranjang.php?hapus_id=<?= $item['id'] ?>&ukuran=<?= urlencode($item['ukuran']) ?>" class="btn-batal">BATAL</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 600; font-size: 1.05rem; color: #800000; font-family: 'Fredoka', cursive;">Total Bayar:</td>
                            <td colspan="2" style="font-weight: 700; color: #d81b60; font-size: 1.2rem; font-family: 'Fredoka', cursive;">Rp <?= number_format($total_bayar, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tampilan HP: Kartu Ringkas Ala Shopee (Tidak Kepotong ke Samping) -->
            <div class="mobile-cart-list">
                <?php foreach ($items_keranjang as $item):
                    $img_src = !empty($item['gambar']) ? 'img/' . htmlspecialchars($item['gambar']) : 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=300';
                ?>
                <div class="shopee-cart-item">
                    <div class="shopee-item-top">
                        <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($item['nama']) ?>" class="shopee-thumb">
                        <div class="shopee-item-meta">
                            <div class="shopee-item-name"><?= htmlspecialchars($item['nama']) ?></div>
                            <div class="shopee-item-subrow">
                                <span class="badge-ukuran"><?= htmlspecialchars(ucfirst($item['ukuran'])) ?></span>
                                <span class="shopee-unit-price">@ Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <a href="keranjang.php?hapus_id=<?= $item['id'] ?>&ukuran=<?= urlencode($item['ukuran']) ?>" class="btn-batal">BATAL</a>
                    </div>

                    <div class="shopee-item-bottom">
                        <div class="qty-box">
                            <a href="keranjang.php?aksi=kurang&id=<?= $item['id'] ?>&ukuran=<?= urlencode($item['ukuran']) ?>" class="qty-btn">-</a>
                            <span class="qty-value"><?= $item['jumlah'] ?></span>
                            <a href="keranjang.php?aksi=tambah&id=<?= $item['id'] ?>&ukuran=<?= urlencode($item['ukuran']) ?>" class="qty-btn">+</a>
                        </div>

                        <div class="shopee-subtotal-wrap">
                            <span class="shopee-subtotal-label">Subtotal</span>
                            <span class="shopee-subtotal-val">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="shopee-total-bar">
                    <span class="shopee-total-label">Total Bayar</span>
                    <span class="shopee-total-amount">Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="cart-actions-bar">
                <a href="produk.php" class="btn btn-secondary">← Lanjut Belanja</a>
                <a href="checkout.php" class="btn">Lanjut ke Checkout ➔</a>
            </div>
        <?php endif; ?>
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