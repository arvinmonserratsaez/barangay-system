<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../" . ($_SESSION['role'] === 'admin' ? 'admin' : 'user') . "/dashboard.php");
    exit();
}
include '../config/database.php';
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            header("Location: ../" . ($user['role'] === 'admin' ? 'admin' : 'user') . "/dashboard.php");
            exit();
        }
    }
    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Barangay System</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="logo-icon">&#127963;</div>
      <h2>Barangay Service System</h2>
      <p>Sto. Angel, San Pablo City &mdash; Digital Services Portal</p>
    </div>
    <?php if ($error): ?>
      <div class="alert error"><i class="ti ti-alert-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="Enter your username" required autofocus>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn-primary"><i class="ti ti-login"></i> Sign In</button>
    </form>
    <div class="auth-switch">
      Don't have an account? <a href="register.php">Register here</a>
    </div>
  </div>
</div>
</body>
</html>
