<?php
session_start();
include 'koneksi.php';

$mode = '';
$success = '';
$error = '';

// Mode default: form permintaan reset password
if (isset($_GET['token'])) {
    $mode = 'reset';
    $token = $_GET['token'];

    // Validasi token
    $stmt = $koneksi->prepare("SELECT * FROM pengguna WHERE reset_token = ? AND reset_expire > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $error = "Token tidak valid atau sudah kedaluwarsa.";
        $mode = '';
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset_password'])) {
        // Proses reset password
        $token = $_POST['token'];
        $new_password = $_POST['password'];
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $koneksi->prepare("UPDATE pengguna SET password = ?, reset_token = NULL, reset_expire = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed, $token);
        if ($stmt->execute()) {
            $success = "Password berhasil diubah. Silakan <a href='login.php'>login</a>.";
            $mode = '';
        } else {
            $error = "Terjadi kesalahan saat mengubah password.";
        }
    } else {
        // Proses kirim email reset
        $email = trim($_POST['email']);
        $stmt = $koneksi->prepare("SELECT * FROM pengguna WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $token = bin2hex(random_bytes(16));
            $expire = date("Y-m-d H:i:s", time() + 3600); // 1 jam

            $stmt = $koneksi->prepare("UPDATE pengguna SET reset_token = ?, reset_expire = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $expire, $email);
            $stmt->execute();

            // Kirim email (gunakan PHPMailer di production)
            $link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/forgot.php?token=$token";
            $subject = "Reset Password";
            $message = "Klik link berikut untuk mengatur ulang password Anda:\n\n$link\n\nLink berlaku 1 jam.";

            // Untuk keperluan pengujian, kita tampilkan link-nya
            $success = "Link reset telah dikirim ke email. <br><br><strong>Link sementara (untuk testing):</strong><br><a href='$link'>$link</a>";

        } else {
            $error = "Email tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Password</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background-color: #f4f6f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .box {
      background-color: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    h3 {
      margin-bottom: 1rem;
      color: #1E315C;
    }

    label {
      display: block;
      margin-top: 1rem;
    }

    input {
      width: 100%;
      padding: 0.6rem;
      margin-top: 0.3rem;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    button {
      width: 100%;
      padding: 0.7rem;
      background-color: #1E315C;
      color: white;
      border: none;
      border-radius: 8px;
      margin-top: 1.2rem;
      cursor: pointer;
    }

    .alert {
      margin-top: 1rem;
      padding: 0.7rem;
      border-radius: 6px;
    }

    .error {
      background-color: #ffe0e0;
      color: #d8000c;
    }

    .success {
      background-color: #e0ffe0;
      color: #006600;
    }

    a {
      color: #1E315C;
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="box">
  <?php if (!empty($success)) : ?>
    <div class="alert success"><?= $success ?></div>
  <?php elseif (!empty($error)) : ?>
    <div class="alert error"><?= $error ?></div>
  <?php endif; ?>

  <?php if ($mode === 'reset') : ?>
    <h3>Atur Ulang Password</h3>
    <form method="post">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <label for="password">Password Baru</label>
      <input type="password" name="password" id="password" required>
      <button type="submit" name="reset_password">Reset Password</button>
    </form>

  <?php else : ?>
    <h3>Lupa Password</h3>
    <form method="post">
      <label for="email">Masukkan Email Anda</label>
      <input type="email" name="email" id="email" required>
      <button type="submit">Kirim Link Reset</button>
    </form>
  <?php endif; ?>
</div>

</body>
</html>
