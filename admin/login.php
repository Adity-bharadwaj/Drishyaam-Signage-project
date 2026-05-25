<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $dataFile = __DIR__ . '/../data/admin.json';
        if (!file_exists($dataFile)) {
            $error = 'Configuration error.';
        } else {
            $admin = json_decode(file_get_contents($dataFile), true);
            if (!$admin || $username !== $admin['username']) {
                $error = 'Invalid username or password.';
            } else {
                $valid = false;
                $storedHash = $admin['password_hash'] ?? '';
                $storedPlain = $admin['password'] ?? '';

                // Try hash verification first
                if (!empty($storedHash) && password_verify($password, $storedHash)) {
                    $valid = true;
                }
                // Fallback to plaintext comparison + auto-upgrade to hash
                elseif (!empty($storedPlain) && $password === $storedPlain) {
                    $valid = true;
                    // Upgrade: hash the password and save
                    $admin['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
                    $admin['password'] = ''; // clear plaintext
                    file_put_contents($dataFile, json_encode($admin, JSON_PRETTY_PRINT));
                }

                if ($valid) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username'] = $username;
                    header('Location: dashboard.php');
                    exit;
                }
                $error = 'Invalid username or password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DRISHYAAM SIGNAGE · Admin Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #060e25 0%, #0b1f4d 50%, #0f2a5e 100%);
    font-family: 'Poppins', sans-serif;
    padding: 20px;
}
.login-wrapper {
    width: 100%;
    max-width: 420px;
}
.brand {
    text-align: center;
    margin-bottom: 32px;
}
.brand a {
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 1px;
    opacity: 0.7;
    transition: opacity 0.3s;
}
.brand a:hover {
    opacity: 1;
}
.brand h1 {
    color: #fff;
    font-size: 24px;
    font-weight: 700;
    letter-spacing: 2px;
    margin-top: 6px;
}
.brand h1 span {
    color: #ff7a00;
}
.card {
    background: rgba(255, 255, 255, 0.06);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 40px 36px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
}
.card h2 {
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 6px;
    letter-spacing: 0.5px;
}
.card .subtitle {
    color: rgba(255, 255, 255, 0.5);
    font-size: 13px;
    font-weight: 400;
    margin-bottom: 28px;
}
.error-msg {
    background: rgba(255, 60, 60, 0.15);
    border: 1px solid rgba(255, 60, 60, 0.25);
    border-radius: 10px;
    padding: 12px 16px;
    color: #ff6b6b;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 20px;
    display: <?= $error ? 'block' : 'none' ?>;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 8px;
}
.form-group input {
    width: 100%;
    padding: 14px 16px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: #fff;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    outline: none;
    transition: border-color 0.3s, box-shadow 0.3s;
}
.form-group input::placeholder {
    color: rgba(255, 255, 255, 0.25);
}
.form-group input:focus {
    border-color: #ff7a00;
    box-shadow: 0 0 0 3px rgba(255, 122, 0, 0.15);
}
.btn-login {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #ff7a00, #e66a00);
    border: none;
    border-radius: 12px;
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.3s;
    margin-top: 8px;
}
.btn-login:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(255, 122, 0, 0.35);
}
.btn-login:active {
    transform: translateY(0);
}
.footer-text {
    text-align: center;
    margin-top: 24px;
    color: rgba(255, 255, 255, 0.3);
    font-size: 11px;
    letter-spacing: 0.5px;
}
@media (max-width: 480px) {
    .card { padding: 30px 24px; }
    .brand h1 { font-size: 20px; }
}
</style>
</head>
<body>
<div class="login-wrapper">
    <div class="brand">
        <a href="../index.html">&larr; Drishyaam Signage</a>
        <h1>DRISHYAAM<span>SIGNAGE</span></h1>
    </div>
    <div class="card">
        <h2>Admin Login</h2>
        <p class="subtitle">Sign in to manage your content</p>
        <div class="error-msg" id="errorMsg"><?= htmlspecialchars($error) ?></div>
        <form method="post" action="login.php" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-login">Sign In</button>
        </form>
    </div>
    <div class="footer-text">&copy; <?= date('Y') ?> Drishyaam Signage. All rights reserved.</div>
</div>
</body>
</html>
