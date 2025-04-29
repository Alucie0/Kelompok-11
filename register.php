<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $address = sanitizeInput($_POST['address']);
    $phone = sanitizeInput($_POST['phone']);
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    // Validasi
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Cek apakah username atau email sudah ada
        $sql = "SELECT id FROM customers WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Username or email already exists";
        } else {
            // Generate verification code
            $verification_code = rand(100000, 999999);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Simpan data sementara di session untuk verifikasi
            $_SESSION['register_data'] = [
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
                'username' => $username,
                'password' => $hashed_password,
                'verification_code' => $verification_code
            ];
            
            // "Kirim" email verifikasi (simulasi)
            sendVerificationEmail($email, $verification_code);
            redirect('verify.php');
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Create Account</button>
            <a href="login.php" class="btn secondary">Cancel</a>
        </form>
    </div>
</body>
</html>