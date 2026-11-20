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

// Menetapkan harga pasti sesuai permintaan: Medium 6rb, Large 11rb
$harga_large = intval($p['harga']);
$harga_medium = 6000;

// Ambil stok dari kolom database yang baru
$stok_large_tersedia = intval($p['stok_large']);
$stok_medium_tersedia = intval($p['stok_medium']);

// Proses ketika tombol "Tambah ke Keranjang" ditekan
if (isset($_POST['tambah_keranjang'])) {
    $jumlah_beli = intval($_POST['jumlah']);
    $pilihan_ukuran = $_POST['ukuran_dipilih']; // 'large' atau 'medium'

    $stok_tersedia = ($pilihan_ukuran == 'medium') ? $stok_medium_tersedia : $stok_large_tersedia;

    if ($jumlah_beli < 1) {
        $error = "Jumlah pembelian minimal 1 pcs.";
    } elseif ($jumlah_beli > $stok_tersedia) {
        $error = "Jumlah pembelian melebihi stok ukuran " . ucfirst($pilihan_ukuran) . " yang tersedia (Stok: " . $stok_tersedia . ")";
    } else {
        if (isset($_SESSION['keranjang'][$id][$pilihan_ukuran])) {
            $_SESSION['keranjang'][$id][$pilihan_ukuran] += $jumlah_beli;
        } else {
            $_SESSION['keranjang'][$id][$pilihan_ukuran] = $jumlah_beli;
        }
        header("Location: keranjang.php");
        exit;
    }
}

$total_keranjang = 0;
if (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $prod_id => $arr_uk) {
        if (is_array($arr_uk)) {
            foreach ($arr_uk as $uk => $jml) {
                $total_keranjang += (int)$jml;
            }
        } else {
            $total_keranjang += (int)$arr_uk;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?= htmlspecialchars($p['nama']) ?> | BinIce</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            transition: 0.3s;
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
            font-weight: 700;
            font-size: 0.9rem;
            border: 1px solid rgba(216, 27, 96, 0.3);
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            transition: 0.3s;
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
            font-weight: 700;
        }

        /* Hamburger Button (Garis Tiga) */
        .menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: center;
            cursor: pointer;
            gap: 5px;
            background: rgba(255, 255, 255, 0.9);
            border: 1.5px solid rgba(216, 27, 96, 0.35);
            padding: 10px 12px;
            border-radius: 12px;
            z-index: 101;
            box-shadow: 0 4px 12px rgba(216, 27, 96, 0.12);
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

        .mobile-auth {
            display: none;
        }

        /* Breadcrumb & Main Layout ala Shopee */
        main {
            flex: 1;
            padding: 12px 6% 45px 6%;
            max-width: 1020px;
            margin: 0 auto;
            width: 100%;
        }

        .breadcrumb-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            font-size: 0.85rem;
            color: #7a4950;
            font-weight: 600;
        }

        .back-link {
            text-decoration: none;
            color: #c2185b;
            background: rgba(255, 255, 255, 0.85);
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid rgba(216, 27, 96, 0.25);
            transition: 0.25s;
        }

        .back-link:hover {
            background: #d81b60;
            color: #fff;
        }

        .product-detail-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            padding: 24px;
            border-radius: 22px;
            box-shadow: 0 12px 30px rgba(216, 27, 96, 0.14);
            border: 1.5px solid #ffd6e5;
            display: grid;
            grid-template-columns: 0.95fr 1.15fr;
            gap: 28px;
            align-items: start;
        }

        .product-img-box {
            width: 100%;
            aspect-ratio: 1 / 1;
            max-height: 360px;
            background: #fff5f8;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffe0ec;
            position: relative;
        }

        .product-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-badge-shopee {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #d81b60;
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            letter-spacing: 0.4px;
            box-shadow: 0 3px 10px rgba(216, 27, 96, 0.25);
        }

        .product-info h2 {
            font-family: 'Fredoka', cursive, sans-serif;
            color: #6d1b2b;
            font-size: 1.55rem;
            line-height: 1.25;
            margin-bottom: 10px;
        }

        .meta-strip {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            margin-bottom: 14px;
            font-size: 0.82rem;
            color: #7a4950;
            font-weight: 600;
        }

        /* Blok Harga ala Shopee */
        .shopee-price-box {
            background: linear-gradient(90deg, #ffeef4 0%, #fff7fa 100%);
            border-left: 4px solid #d81b60;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .shopee-price-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #ad1457;
            letter-spacing: 0.5px;
        }

        .price {
            font-family: 'Fredoka', cursive, sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: #d81b60;
            line-height: 1.1;
        }

        .stock-badge-box {
            text-align: right;
            font-size: 0.82rem;
            font-weight: 600;
            color: #593539;
        }

        .stock-badge-box strong {
            color: #ad1457;
        }

        .desc-box {
            background: #fff9fb;
            border: 1px solid #fce4ec;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 18px;
        }

        .desc-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #880e4f;
            margin-bottom: 4px;
            letter-spacing: 0.4px;
        }

        .desc {
            font-size: 0.88rem;
            color: #593539;
            line-height: 1.55;
        }

        .form-row-shopee {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .row-label {
            min-width: 95px;
            font-size: 0.86rem;
            font-weight: 700;
            color: #7a3b46;
        }

        /* Pilihan Chip Ukuran ala Shopee */
        .variant-chips {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .variant-chip {
            border: 1.5px solid #f48fb1;
            background: #fff;
            color: #593539;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .variant-chip:hover {
            border-color: #d81b60;
            background: #fff5f8;
        }

        .variant-chip.active {
            border-color: #d81b60;
            background: #d81b60;
            color: #fff;
            box-shadow: 0 4px 12px rgba(216, 27, 96, 0.25);
        }

        /* Stepper Jumlah (- / input / +) ala Shopee */
        .qty-stepper {
            display: inline-flex;
            align-items: center;
            border: 1.5px solid #f48fb1;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        .qty-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: #fff5f8;
            color: #c2185b;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .qty-btn:hover {
            background: #d81b60;
            color: #fff;
        }

        .qty-stepper input[type="number"] {
            width: 56px;
            height: 36px;
            border: none;
            border-left: 1px solid #f8bbd0;
            border-right: 1px solid #f8bbd0;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 700;
            color: #593539;
            background: #fff;
            -moz-appearance: textfield;
        }

        .qty-stepper input[type="number"]::-webkit-outer-spin-button,
        .qty-stepper input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .btn-add {
            font-family: 'Fredoka', cursive, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: linear-gradient(135deg, #d81b60 0%, #ad1457 100%);
            color: white;
            padding: 13px 18px;
            border-radius: 14px;
            text-align: center;
            font-weight: 600;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(216, 27, 96, 0.3);
            transition: all 0.25s ease;
            font-size: 1rem;
            margin-top: 8px;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #ad1457 0%, #880e4f 100%);
            transform: translateY(-2px);
        }

        .error-msg {
            background: #ffebee;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #ef9a9a;
            margin-bottom: 14px;
            font-size: 0.88rem;
            font-weight: 600;
        }

        footer {
            background: rgba(173, 20, 87, 0.9);
            color: white;
            text-align: center;
            padding: 16px;
            margin-top: auto;
            font-size: 0.88rem;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Tampilan HP: garis tiga di pinggir kanan + tata letak rapi ala Shopee */
        @media (max-width: 900px) {
            header {
                padding: 14px 5%;
                justify-content: space-between;
            }

            .logo-container, .nav-icons {
                flex: unset;
            }

            .nav-icons {
                display: none;
            }

            .logo-img {
                width: 58px;
                height: 58px;
            }

            .menu-toggle {
                display: flex;
            }

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
                box-shadow: 0 12px 25px rgba(128, 0, 0, 0.14);
                border-bottom: 2px solid rgba(255, 182, 193, 0.85);
                animation: slideDown 0.28s ease-out forwards;
            }

            nav a {
                width: 85%;
                text-align: center;
                background: #fff;
            }

            nav.active {
                display: flex;
            }

            main {
                padding: 10px 4% 35px 4%;
            }

            .product-detail-card {
                grid-template-columns: 1fr;
                gap: 18px;
                padding: 16px;
                border-radius: 18px;
            }

            .product-img-box {
                max-height: 290px;
            }

            .product-info h2 {
                font-size: 1.35rem;
            }

            .price {
                font-size: 1.45rem;
            }

            .form-row-shopee {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .row-label {
                min-width: unset;
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

        <!-- Tombol Garis Tiga (Hamburger Menu) di Pinggir Kanan -->
        <button class="menu-toggle" id="menuToggle" aria-label="Menu Navigasi">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="navMenu">
            <a href="home.php">Home</a>
            <a href="produk.php" class="active">Products</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="contact.php">Contact</a>

            <div class="mobile-auth">
                <a href="keranjang.php" style="background: #fff; color: #d81b60; font-weight: 700;">
                    🛒 Keranjang <span class="cart-badge"><?= $total_keranjang ?></span>
                </a>
                <?php if (isset($_SESSION['admin_logged'])): ?>
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
        </div>
    </header>

    <main>
        <div class="breadcrumb-bar">
            <a href="produk.php" class="back-link">← Kembali ke Katalog</a>
            <span>BinIce Official Store</span>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-msg">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="product-detail-card">
            <div class="product-img-box">
                <span class="product-badge-shopee">GABIN ES KRIM</span>
                <img src="<?= !empty($p['gambar']) ? 'img/' . htmlspecialchars($p['gambar']) : 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600' ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
            </div>

            <div class="product-info">
                <h2><?= htmlspecialchars($p['nama']) ?></h2>

                <div class="meta-strip">
                    <span>🍦 Fresh &amp; Lumer</span>
                </div>

                <div class="shopee-price-box">
                    <div>
                        <div class="shopee-price-label">Harga Satuan</div>
                        <div class="price" id="textHarga">Rp <?= number_format($harga_large, 0, ',', '.') ?></div>
                    </div>
                    <div class="stock-badge-box">
                        <div>📦 Stok Tersedia</div>
                        <strong id="textStok"><?= $stok_large_tersedia ?> pcs</strong>
                    </div>
                </div>

                <div class="desc-box">
                    <div class="desc-title">Deskripsi Produk</div>
                    <div class="desc"><?= nl2br(htmlspecialchars($p['deskripsi'] ?? 'Renyahnya gabin di setiap gigitan')) ?></div>
                </div>

                <form method="POST">
                    <div class="form-row-shopee">
                        <span class="row-label">Ukuran Varian</span>
                        <div class="variant-chips" role="group" aria-label="Pilih Ukuran Varian">
                            <button type="button" class="variant-chip active" data-size="large" onclick="selectVariant('large')">
                                Large (Rp <?= number_format($harga_large, 0, ',', '.') ?>)
                            </button>
                            <button type="button" class="variant-chip" data-size="medium" onclick="selectVariant('medium')">
                                Medium (Rp <?= number_format($harga_medium, 0, ',', '.') ?>)
                            </button>
                        </div>
                        <select name="ukuran_dipilih" id="pilihUkuran" style="display:none;" required>
                            <option value="large" selected>Large</option>
                            <option value="medium">Medium</option>
                        </select>
                    </div>

                    <div class="form-row-shopee">
                        <span class="row-label">Jumlah Beli</span>
                        <div class="qty-stepper">
                            <button type="button" class="qty-btn" onclick="changeQty(-1)" aria-label="Kurangi jumlah">−</button>
                            <input type="number" name="jumlah" value="1" min="1" max="<?= $stok_large_tersedia ?>" id="inputJumlah" required>
                            <button type="button" class="qty-btn" onclick="changeQty(1)" aria-label="Tambah jumlah">+</button>
                        </div>
                    </div>

                    <button type="submit" name="tambah_keranjang" class="btn-add">🛒 Masukkan ke Keranjang</button>
                </form>
            </div>
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

        const hargaLarge = <?= $harga_large ?>;
        const hargaMedium = <?= $harga_medium ?>;
        const stokLarge = <?= $stok_large_tersedia ?>;
        const stokMedium = <?= $stok_medium_tersedia ?>;

        const selectUkuran = document.getElementById('pilihUkuran');
        const textHarga = document.getElementById('textHarga');
        const textStok = document.getElementById('textStok');
        const inputJumlah = document.getElementById('inputJumlah');
        const variantChips = document.querySelectorAll('.variant-chip');

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function applyVariant(val) {
            selectUkuran.value = val;
            variantChips.forEach(chip => {
                chip.classList.toggle('active', chip.dataset.size === val);
            });

            if (val === 'large') {
                textHarga.textContent = formatRupiah(hargaLarge);
                textStok.textContent = stokLarge + ' pcs';
                inputJumlah.max = stokLarge;
            } else if (val === 'medium') {
                textHarga.textContent = formatRupiah(hargaMedium);
                textStok.textContent = stokMedium + ' pcs';
                inputJumlah.max = stokMedium;
            }
            inputJumlah.value = 1;
        }

        function selectVariant(val) {
            applyVariant(val);
        }

        selectUkuran.addEventListener('change', function() {
            applyVariant(this.value);
        });

        function changeQty(delta) {
            const minVal = parseInt(inputJumlah.min, 10) || 1;
            const maxVal = parseInt(inputJumlah.max, 10) || 1;
            let currentVal = parseInt(inputJumlah.value, 10) || minVal;
            currentVal += delta;

            if (currentVal < minVal) currentVal = minVal;
            if (maxVal > 0 && currentVal > maxVal) currentVal = maxVal;

            inputJumlah.value = currentVal;
        }
    </script>
</body>
</html>