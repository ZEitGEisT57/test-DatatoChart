<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['username']); // bisa berupa username atau email
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT * FROM pengguna WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username/email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .login-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f8f9fa;
      padding: 1rem;
    }

    .login-box {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-box h3 {
      color: #003366;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .login-box label {
      font-weight: 600;
      margin-bottom: 0.25rem;
      display: block;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 1rem;
      font-size: 1rem;
      transition: border-color 0.2s ease;
    }

    .login-box input:focus {
      border-color: #8bc34a;
      box-shadow: 0 0 5px #8bc34a88;
      outline: none;
    }

    .login-box button {
      background-color: #8bc34a;
      color: #fff;
      border: none;
      padding: 0.75rem;
      width: 100%;
      border-radius: 6px;
      font-weight: bold;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-box button:hover {
      background-color: #7cb342;
    }

    .alert {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      padding: 0.75rem 1rem;
      border-radius: 6px;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .info-text {
      margin-top: 1rem;
      text-align: center;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-box">
      <h3>Login</h3>

      <?php if (!empty($error)) : ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" action="login.php">
        <label for="username">Username atau Email</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Masuk</button>
      </form>

      <div class="info-text">
        <a href="forgot.php">Lupa password?</a><br>
        Belum punya akun? <a href="register.php">Daftar di sini</a>
      </div>
    </div>
  </div>
</body>
</html>
