<?php
include 'koneksi.php';
require 'vendor/autoload.php';
include 'auth.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$pesan = '';

// Ambil daftar kapal
$kapalQuery = $koneksi->query("SELECT id, nama FROM kapal");
$kapalList = [];
while ($row = $kapalQuery->fetch_assoc()) {
    $kapalList[] = $row;
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['manual'])) {
        $kapal_id = $_POST['kapal'] ?? '';
        $jenis = $_POST['jenis_tiket'] ?? '';
        $produksi = $_POST['produksi'] ?? '';
        $tanggal = $_POST['tanggal'] ?? '';

        if ($kapal_id && $jenis && is_numeric($produksi) && $tanggal) {
            $stmt = $koneksi->prepare("INSERT INTO produksi (kapal_id, jenis_tiket, jumlah_produksi, tanggal) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isis", $kapal_id, $jenis, $produksi, $tanggal);
            $stmt->execute();
            $pesan = "✅ Data manual berhasil disimpan.";
        } else {
            $pesan = "❌ Semua data harus diisi dan valid.";
        }
    } else {
        if (
            isset($_FILES['excelFile']) &&
            is_uploaded_file($_FILES['excelFile']['tmp_name']) &&
            isset($_POST['tanggal']) &&
            !empty($_POST['tanggal'])
        ) {
            $kapal_id = $_POST['kapal'] ?? '';
            $tanggal = $_POST['tanggal'];
            $fileTmpPath = $_FILES['excelFile']['tmp_name'];
            $spreadsheet = IOFactory::load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $jenisIndex = null;
            $produksiIndex = null;
            foreach ($rows[0] as $i => $header) {
                if (stripos($header, 'jenis tiket') !== false) {
                    $jenisIndex = $i;
                } elseif (stripos($header, 'produksi') !== false) {
                    $produksiIndex = $i;
                }
            }

            if ($jenisIndex === null || $produksiIndex === null) {
                $pesan = "❌ Kolom 'jenis tiket' atau 'produksi' tidak ditemukan.";
            } else {
                $stmt = $koneksi->prepare("INSERT INTO produksi (kapal_id, jenis_tiket, jumlah_produksi, tanggal) VALUES (?, ?, ?, ?)");
                for ($i = 1; $i < count($rows); $i++) {
                    $jenis = $rows[$i][$jenisIndex];
                    $jumlah = $rows[$i][$produksiIndex];

                    if (!empty($jenis) && is_numeric($jumlah)) {
                        $stmt->bind_param("isis", $kapal_id, $jenis, $jumlah, $tanggal);
                        $stmt->execute();
                    }
                }
                $pesan = "✅ Data dari Excel berhasil diunggah.";
            }
        } else {
            $pesan = "❌ Pilih file, kapal, dan tanggal terlebih dahulu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Data Produksi - ASDP Merauke</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <div class="logo">
      <h1>Trend dan Produksi Penumpang Kapal ASDP Cabang Merauke</h1>
    </div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="upload.php" class="active">Input Data</a></li>
        <li><a href="kapal.php">Lihat Kapal</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Form Input Data Produksi</h2>

    <?php if ($pesan): ?>
      <div class="alert"><?= $pesan ?></div>
    <?php endif; ?>

    <div class="row chart-grid">
      <!-- Kolom kiri -->
      <div class="left-column">
        <div class="form-box">
          <h3>Input Manual</h3>
          <form method="post">
            <input type="hidden" name="manual" value="1">

            <label for="kapal">Pilih Kapal:</label>
            <select name="kapal" required>
              <?php foreach ($kapalList as $kapal): ?>
                <option value="<?= $kapal['id'] ?>"><?= htmlspecialchars($kapal['nama']) ?></option>
              <?php endforeach; ?>
            </select>

            <label for="jenis_tiket">Jenis Tiket:</label>
            <input type="text" name="jenis_tiket" required>

            <label for="produksi">Jumlah Produksi:</label>
            <input type="number" name="produksi" required>

            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" required>

            <button type="submit">Simpan Data</button>
          </form>
        </div>
      </div>

      <!-- Kolom EXCEL -->
      <div class="right-column">
        <div class="form-box full-height">
          <h3>Upload Excel</h3>
          <form method="post" enctype="multipart/form-data">
            <label for="kapal">Pilih Kapal:</label>
            <select name="kapal" required>
              <?php foreach ($kapalList as $kapal): ?>
                <option value="<?= $kapal['id'] ?>"><?= htmlspecialchars($kapal['nama']) ?></option>
              <?php endforeach; ?>
            </select>

            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" required>

            <label for="excelFile">File Excel (.xlsx):</label>
            <input type="file" name="excelFile" accept=".xlsx" required>

            <button type="submit">Upload Excel</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
