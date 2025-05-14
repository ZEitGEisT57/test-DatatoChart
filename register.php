<?php
session_start();
include 'koneksi.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Cek jika username sudah ada
    $stmt = $koneksi->prepare("SELECT id FROM pengguna WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username sudah digunakan.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert ke database
        $stmt = $koneksi->prepare("INSERT INTO pengguna (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            $success = "Registrasi berhasil! Silakan login.";
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
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
  <div class="login-box">
    <h3>Register</h3>

    <?php if (!empty($error)) : ?>
      <div class="alert" style="background-color:#ffe0e0; color:#d8000c;"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)) : ?>
      <div class="alert" style="background-color:#e0ffe0; color:#006600;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="register.php">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required>

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
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </p>
  </div>
</div>
</body>
</html>
