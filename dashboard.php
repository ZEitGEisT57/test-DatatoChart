<?php
include 'koneksi.php';

// Ambil semua kapal
$kapal_sql = "SELECT id, nama FROM kapal";
$kapal_result = $koneksi->query($kapal_sql);

$rekap = [];
while ($kapal = $kapal_result->fetch_assoc()) {
    $kapal_id = $kapal['id'];
    $nama_kapal = $kapal['nama'];

    // Query total jumlah produksi per jenis tiket untuk setiap kapal
    $query = "
        SELECT jenis_tiket, SUM(jumlah_produksi) AS total
        FROM produksi
        WHERE kapal_id = $kapal_id
        GROUP BY jenis_tiket
    ";
    $produksi_result = $koneksi->query($query);

    // Siapkan default nilai
    $data = ['penumpang' => 0, 'kendaraan' => 0, 'trip' => 0];
    while ($row = $produksi_result->fetch_assoc()) {
        $jenis = strtolower($row['jenis_tiket']);
        if (isset($data[$jenis])) {
            $data[$jenis] = $row['total'];
        }
    }

    $rekap[] = [
        'nama_kapal' => $nama_kapal,
        'penumpang' => $data['penumpang'],
        'kendaraan' => $data['kendaraan'],
        'trip' => $data['trip']
    ];
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Produksi Kapal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">
        <h1>ASDP Dashboard</h1>
    </div>
    <nav>
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="upload.php">Input Data</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <main class="main">
        <h2 class="title">Ringkasan Produksi Kapal</h2>

        <?php if (empty($rekap)): ?>
            <p>Tidak ada data produksi yang tersedia.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($rekap as $kapal): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($kapal['nama_kapal']) ?></h3>
                        <ul>
                            <li>🧍 Penumpang: <strong><?= number_format($kapal['penumpang']) ?></strong></li>
                            <li>🚗 Kendaraan: <strong><?= number_format($kapal['kendaraan']) ?></strong></li>
                            <li>🚢 Trip: <strong><?= number_format($kapal['trip']) ?></strong></li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
