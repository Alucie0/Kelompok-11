<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

// Cek apakah diakses dari localhost
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['REMOTE_ADDR'] != '::1') {
    die("Access denied. This page can only be accessed from localhost.");
}

if (isLoggedIn()) {
    redirect('dashboard.php');
}

if (!isset($_SESSION['register_data'])) {
    redirect('register.php');
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_code = sanitizeInput($_POST['verification_code']);
    $correct_code = $_SESSION['verification_code'];
    
    if ($user_code == $correct_code) {
        // Simpan data ke database
        $data = $_SESSION['register_data'];
        $sql = "INSERT INTO customers (name, email, address, phone, username, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $data['name'], $data['email'], $data['address'], $data['phone'], $data['username'], $data['password']);
        
        if ($stmt->execute()) {
            // Bersihkan session
            unset($_SESSION['register_data']);
            unset($_SESSION['verification_code']);
            unset($_SESSION['verification_email']);
            
            // Redirect ke halaman login dengan pesan sukses
            $_SESSION['register_success'] = "Registration successful! Please login.";
            redirect('login.php');
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Invalid verification code";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        // Auto-fill the verification code in local development
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we're on localhost
            if (window.location.hostname === "localhost" || window.location.hostname === "127.0.0.1") {
                // Get the correct code from PHP session (passed to JavaScript)
                const correctCode = "<?php echo isset($_SESSION['verification_code']) ? $_SESSION['verification_code'] : ''; ?>";
                if (correctCode) {
                    document.getElementById('verification_code').value = correctCode;
                    
                    // Optional: Auto-submit the form after a short delay
                    // setTimeout(function() {
                    //     document.querySelector('form').submit();
                    // }, 1000);
                }
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Email Verification</h1>
        <p>We've sent a verification code to <?php echo $_SESSION['verification_email']; ?></p>
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="verify.php" method="post">
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" id="verification_code" name="verification_code" required>
            </div>
            <button type="submit" class="btn">Verify</button>
            <a href="register.php" class="btn secondary">Cancel</a>
        </form>
        
        <!-- Development note - only show on localhost -->
        <?php if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1'): ?>
        <div class="dev-note" style="margin-top: 20px; padding: 10px; background: #f0f0f0; border-radius: 5px;">
            <strong>Development Note:</strong> The verification code has been auto-filled for testing.
            <p>Code: <?php echo isset($_SESSION['verification_code']) ? $_SESSION['verification_code'] : 'Not set'; ?></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>