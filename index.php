<?php
include 'koneksi.php';
include 'auth.php';

// Ambil daftar kapal
$kapalQuery = $koneksi->query("SELECT id, nama FROM kapal");
$kapalList = [];
while ($row = $kapalQuery->fetch_assoc()) {
    $kapalList[] = $row;
}

// Ambil kapal, bulan, dan tahun dari GET
$selectedKapalId = $_GET['kapal_id'] ?? null;
$selectedBulan   = $_GET['bulan']    ?? date('m');
$selectedTahun   = $_GET['tahun']    ?? date('Y');

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

// Dropdown bulan & tahun
$bulanList = [
    '01'=>'Januari','02'=>'Februari','03'=>'Maret',
    '04'=>'April','05'=>'Mei','06'=>'Juni',
    '07'=>'Juli','08'=>'Agustus','09'=>'September',
    '10'=>'Oktober','11'=>'November','12'=>'Desember'
];
$tahunSekarang = date('Y');
$tahunList     = range($tahunSekarang - 5, $tahunSekarang + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Trend & Produksi Penumpang ASDP Merauke</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <header>
    <div class="container header-inner">
      <div class="logo">
        <img src="5f5091ef-6eb8-4132-99ed-af27a6a040c2.png" alt="Logo ASDP" />
      </div>
      <h1>Trend & Produksi Penumpang Kapal ASDP Merauke</h1>
      <nav>
        <ul class="nav-list">
          <li><a href="dashboard.php" class="nav-link active">Home</a></li>
          <li><a href="upload.php" class="nav-link">Input Data</a></li>
          <li><a href="kapal.php" class="nav-link">Lihat Kapal</a></li>
          <li><a href="logout.php" class="nav-link">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <aside class="sidebar">
    <form method="get" class="filter-form">
      <label for="kapal_id">Pilih Kapal:</label>
      <select id="kapal_id" name="kapal_id" required>
        <option value="" disabled <?= $selectedKapalId ? '' : 'selected' ?>>-- Pilih Kapal --</option>
        <?php foreach ($kapalList as $k): ?>
          <option value="<?= $k['id'] ?>" <?= $k['id']==$selectedKapalId? 'selected':''?>>
            <?= htmlspecialchars($k['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="bulan">Bulan:</label>
      <select id="bulan" name="bulan" required>
        <?php foreach ($bulanList as $num=>$nama): ?>
          <option value="<?= $num ?>" <?= $num==$selectedBulan? 'selected':''?>><?= $nama ?></option>
        <?php endforeach; ?>
      </select>

      <label for="tahun">Tahun:</label>
      <select id="tahun" name="tahun" required>
        <?php foreach ($tahunList as $t): ?>
          <option value="<?= $t ?>" <?= $t==$selectedTahun? 'selected':''?>><?= $t ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Tampilkan</button>
    </form>
  </aside>

  <main class="content">
    <section class="charts-top">
      <div class="chart-box">
        <h3>Grafik Bar</h3>
        <canvas id="barChart"></canvas>
      </div>
      <div class="chart-box">
        <h3>Grafik Pie</h3>
        <canvas id="pieChart"></canvas>
      </div>
    </section>

    <section class="charts-line">
      <div class="chart-box">
        <h3>Grafik Line</h3>
        <canvas id="lineChart"></canvas>
      </div>
    </section>
  </main>

  <script>
    window.chartData = {
      labels: <?= json_encode($labels) ?>,
      values: <?= json_encode($values) ?>
    };
  </script>
  <script src="script.js"></script>
</body>
</html>
