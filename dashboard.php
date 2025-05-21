<?php
include 'koneksi.php';

$kapal_sql = "SELECT id, nama FROM kapal";
$kapal_result = $koneksi->query($kapal_sql);

$rekap = [];
$summary = ['penumpang' => 0, 'kendaraan' => 0, 'barang' => 0];

while ($kapal = $kapal_result->fetch_assoc()) {
    $kapal_id = $kapal['id'];
    $nama_kapal = $kapal['nama'];

    $query = "
        SELECT jenis_tiket, SUM(jumlah_produksi) AS total
        FROM produksi
        WHERE kapal_id = $kapal_id
        GROUP BY jenis_tiket
    ";
    $produksi_result = $koneksi->query($query);

    $data = ['penumpang' => 0, 'kendaraan' => 0, 'barang' => 0];

    while ($row = $produksi_result->fetch_assoc()) {
        $jenis = strtolower($row['jenis_tiket']);
        $jumlah = (int)$row['total'];

        if (preg_match('/\b(eksekutif|bisnis|ekonomi|penumpang)\b/', $jenis)) {
            $data['penumpang'] += $jumlah;
            $summary['penumpang'] += $jumlah;
        } elseif (preg_match('/\b(golongan)\b/', $jenis)){
            $data['kendaraan'] += $jumlah;
            $summary['kendaraan'] += $jumlah;
        } elseif (preg_match('/\b(barang)\b/', $jenis)){
            $data['barang'] += $jumlah;
            $summary['barang'] += $jumlah;
        }
    }
    $rekap[] = [
        'nama_kapal' => $nama_kapal,
        'penumpang' => $data['penumpang'],
        'kendaraan' => $data['kendaraan'],
        'barang' => $data['barang']
    ];
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Produksi Kapal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .scroll-row {
            display: flex;
            overflow-x: auto;
            gap: 1rem;
            padding: 1rem 0;
        }

        .scroll-row::-webkit-scrollbar {
            height: 8px;
        }

        .scroll-row::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        .card {
            min-width: 240px;
            background: #fff;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.1);
            padding: 1rem;
            flex-shrink: 0;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 0.15);
        }

        .chart-area {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .chart-box {
            background: #fff;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.1);
            padding: 1rem;
            flex: 1 1 300px;
        }
    </style>
</head>
<body>

<header>
    <div class="container header-inner">
      <div class="logo">
        <img src="img\ASDP_Logo_2023.png" alt="Logo ASDP" />
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

<div class="container content-dash">
    <main class="main">
        <h2 class="title">Ringkasan Produksi Kapal</h2>

        <?php if (empty($rekap)): ?>
            <p>Tidak ada data produksi yang tersedia.</p>
        <?php else: ?>
            <div class="scroll-row">
                <?php foreach ($rekap as $kapal): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($kapal['nama_kapal']) ?></h3>
                        <ul style="list-style:none; padding-left:0; margin-top:0.5rem;">
                            <li>🧍 Penumpang: <strong><?= number_format($kapal['penumpang']) ?></strong></li>
                            <li>🚗 Kendaraan: <strong><?= number_format($kapal['kendaraan']) ?></strong></li>
                            <li>📦 Barang: <strong><?= number_format($kapal['barang']) ?></strong></li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2 class="title">Statistik Produksi Keseluruhan</h2>
            <div class="chart-area">
                <div class="chart-box">
                    <h3>Total Produksi</h3>
                    <canvas id="barChart"></canvas>
                </div>
                <div class="chart-box">
                    <h3>Komposisi Tiket</h3>
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<script>
    const summaryData = <?= json_encode($summary) ?>;

    // Bar chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Penumpang', 'Kendaraan', 'Barang'],
            datasets: [{
                label: 'Jumlah Produksi',
                data: [summaryData.penumpang, summaryData.kendaraan, summaryData.barang],
                backgroundColor: ['#4caf50', '#2196f3', '#964B00'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Pie chart
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Penumpang', 'Kendaraan', 'Barang'],
            datasets: [{
                data: [summaryData.penumpang, summaryData.kendaraan, summaryData.barang],
                backgroundColor: ['#4caf50', '#2196f3', '#964B00'],
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

</body>
</html>
