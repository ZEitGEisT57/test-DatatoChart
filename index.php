<?php
include 'koneksi.php';

// Ambil daftar kapal
$kapalQuery = $koneksi->query("SELECT id, nama FROM kapal");
$kapalList = [];
while ($row = $kapalQuery->fetch_assoc()) {
    $kapalList[] = $row;
}

// Ambil kapal, bulan, dan tahun dari GET
$selectedKapalId = $_GET['kapal_id'] ?? null;
$selectedBulan = $_GET['bulan'] ?? date('m');
$selectedTahun = $_GET['tahun'] ?? date('Y');

// Siapkan data untuk grafik
$labels = [];
$values = [];

if ($selectedKapalId) {
    $dataQuery = $koneksi->prepare("
        SELECT jenis_tiket, SUM(jumlah_produksi) AS total 
        FROM produksi 
        WHERE kapal_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        GROUP BY jenis_tiket
    ");
    $dataQuery->bind_param("iii", $selectedKapalId, $selectedBulan, $selectedTahun);
    $dataQuery->execute();
    $result = $dataQuery->get_result();

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['jenis_tiket'];
        $values[] = (int)$row['total'];
    }
}

// Untuk dropdown bulan & tahun
$bulanList = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$tahunSekarang = date('Y');
$tahunList = range($tahunSekarang - 5, $tahunSekarang + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Trend dan Produksi Penumpang Kapal ASDP Cabang Merauke</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <header>
    <div class="logo">
      <h1>Trend dan Produksi Penumpang Kapal ASDP Cabang Merauke</h1>
    </div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="upload.php">Input Data</a></li>
        <li><a href="kapal.html">Lihat Kapal</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <form method="get" class="kapal-form">
      <label for="kapal_id">Pilih Kapal:</label>
      <select name="kapal_id" id="kapal_id">
        <?php foreach ($kapalList as $kapal): ?>
          <option value="<?= $kapal['id'] ?>" <?= $kapal['id'] == $selectedKapalId ? 'selected' : '' ?>>
            <?= htmlspecialchars($kapal['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="bulan">Bulan:</label>
      <select name="bulan" id="bulan">
        <?php foreach ($bulanList as $num => $nama): ?>
          <option value="<?= $num ?>" <?= $num == $selectedBulan ? 'selected' : '' ?>><?= $nama ?></option>
        <?php endforeach; ?>
      </select>

      <label for="tahun">Tahun:</label>
      <select name="tahun" id="tahun">
        <?php foreach ($tahunList as $tahun): ?>
          <option value="<?= $tahun ?>" <?= $tahun == $selectedTahun ? 'selected' : '' ?>><?= $tahun ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Tampilkan</button>
    </form>

    <div class="row chart-grid">
        <div class="left-column">
          <div class="chart-box">
            <h3>Grafik Bar</h3>
            <canvas id="barChart"></canvas>
          </div>
          <div class="chart-box">
            <h3>Grafik Line</h3>
            <canvas id="lineChart"></canvas>
          </div>
        </div>

        <div class="right-column">
          <div class="chart-box full-height">
            <h3>Grafik Pie</h3>
            <canvas id="pieChart"></canvas>
          </div>
        </div>
    </div>
  </div>

  <script>
    window.chartData = {
      labels: <?= json_encode($labels) ?>,
      values: <?= json_encode($values) ?>
    };
  </script>
  <script src="script.js"></script>
</body>
</html>
