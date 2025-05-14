<?php
session_start();

if (!isset($_SESSION['user'])):
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Akses Ditolak</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .modal {
      background-color: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      text-align: center;
      max-width: 400px;
      width: 90%;
      animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
      from {
        transform: translateY(-20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .modal h2 {
      color: #d8000c;
      margin-bottom: 1rem;
    }

    .modal p {
      margin-bottom: 1.5rem;
    }

    .modal a {
      display: inline-block;
      background-color: #1E315C;
      color: white;
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .modal a:hover {
      background-color: #2E4D68;
    }
  </style>
</head>
<body>
  <div class="modal">
    <h2>Akses Ditolak</h2>
    <p>Anda belum login. Silakan login terlebih dahulu untuk mengakses halaman ini.</p>
    <a href="login.php">Login Sekarang</a>
  </div>
</body>
</html>

<?php
exit;
endif;
?>
