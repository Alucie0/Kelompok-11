<?php
// Konfigurasi database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'login_system');

// Membuat koneksi ke database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Fungsi untuk mengirim email verifikasi (simulasi)
function sendVerificationEmail($email, $verification_code) {
    // Dalam implementasi nyata, ini akan mengirim email
    // Untuk localhost, kita simpan di session
    session_start();
    $_SESSION['verification_code'] = $verification_code;
    $_SESSION['verification_email'] = $email;
    return true;
}
?>
