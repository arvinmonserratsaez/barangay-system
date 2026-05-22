<?php
session_start();
include '../config/database.php';
$error = $success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    if (empty($fname)||empty($lname)||empty($username)||empty($email)||empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, password, role) VALUES (?, ?, ?, ?, ?, 'user')");
            $stmt->bind_param("sssss", $fname, $lname, $username, $email, $hash);
            $stmt->execute() ? $success = "Account created successfully!" : $error = "Something went wrong.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Barangay System</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="logo-icon">&#127963;</div>
      <h2>Create Account</h2>
      <p>Register as a Barangay Resident</p>
    </div>
    <?php if ($error):   ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert success"><?= $success ?> <a href="login.php">Login now &rarr;</a></div><?php endif; ?>
    <?php if (!$success): ?>
    <form method="POST">
      <div class="form-row">
        <div class="form-group"><label>First Name</label><input type="text" name="fname" placeholder="Juan" required></div>
        <div class="form-group"><label>Last Name</label><input type="text" name="lname" placeholder="dela Cruz" required></div>
      </div>
      <div class="form-group"><label>Username</label><input type="text" name="username" placeholder="juandelacruz" required></div>
      <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="juan@email.com" required></div>
      <div class="form-row">
        <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="Min. 6 characters" required></div>
        <div class="form-group"><label>Confirm Password</label><input type="password" name="confirm" placeholder="Repeat password" required></div>
      </div>
      <button type="submit" class="btn-primary"><i class="ti ti-user-plus"></i> Create Account</button>
    </form>
    <?php endif; ?>
    <div class="auth-switch">Already have an account? <a href="login.php">Sign in here</a></div>
  </div>
</div>
</body>
</html>
