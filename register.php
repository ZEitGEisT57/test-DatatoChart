<?php
session_start();
include 'koneksi.php';

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // Cek duplikat username atau email
    $stmt = $koneksi->prepare("SELECT id FROM pengguna WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username atau email sudah digunakan.";
    } else {
        // Hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Simpan
        $stmt = $koneksi->prepare(
            "INSERT INTO pengguna (username, email, password, role) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $username, $email, $hashed, $role);

        if ($stmt->execute()) {
            $success = "Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
        } else {
            $error = "Terjadi kesalahan saat menyimpan data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Registrasi</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: #f8f9fa;
      padding: 1rem;
    }
    .login-box {
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,.1);
      width: 100%;
      max-width: 420px;
    }
    .login-box h3 { text-align:center; color:#003366; margin-bottom:1.5rem; }
    .login-box label { font-weight:600; margin-top:1rem; display:block; }
    .login-box input, .login-box select {
      width:100%; padding:.55rem; border:1px solid #ccc; border-radius:6px; margin-top:.3rem;
    }
    .login-box input:focus, .login-box select:focus {
      border-color:#8bc34a; outline:none; box-shadow:0 0 5px #8bc34a88;
    }
    .login-box button {
      width:100%; margin-top:1.5rem; padding:.75rem;
      background:#8bc34a; color:#fff; border:none; border-radius:6px;
      font-weight:700; cursor:pointer; transition:.3s;
    }
    .login-box button:hover { background:#7cb342; }
    .alert {
      padding:.75rem 1rem; border-radius:6px; margin-bottom:1rem; text-align:center; font-weight:600;
    }
    .alert.error   { background:#ffe0e0; color:#d8000c; }
    .alert.success { background:#e0ffe0; color:#006600; }
  </style>
</head>
<body>
<div class="login-container">
  <div class="login-box">
    <h3>Registrasi Akun</h3>

    <?php if ($error): ?>
      <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" action="register.php">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>

      <label for="role">Role</label>
      <select name="role" id="role" required>
        <option value="">-- Pilih Role --</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
      </select>

      <button type="submit">Daftar</button>
    </form>

    <p style="text-align:center; margin-top:1rem;">
      Sudah punya akun? <a href="login.php">Login di sini</a><br>
      <a href="forgot.php">Lupa password?</a>
    </p>    
  </div>
</div>
</body>
</html>
