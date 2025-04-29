<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - eBook Koperasi</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        body {
            display: flex;
            height: 100vh;
            background: #f3f4f6;
        }
        .sidebar {
            width: 220px;
            background-color: #2c3e50;
            color: white;
            padding: 2rem 1rem;
        }
        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 1rem 0;
            padding: 0.5rem;
            border-radius: 8px;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .main {
            flex: 1;
            padding: 2rem;
        }
        .welcome-card {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .welcome-card h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .btn-logout {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-logout:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>eKoperasi</h2>
        <a href="#">Dashboard</a>
        <a href="#">Simpanan</a>
        <a href="#">Iuran</a>
        <a href="#">Laporan</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="main">
        <div class="welcome-card">
            <h1>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h1>
            <p>Anda telah berhasil login ke sistem eKoperasi.</p>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</body>
</html>
