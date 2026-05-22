<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';

$uid = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Get current user data
$stmt = $conn->prepare("SELECT username, fname, lname, email FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (!$fname || !$lname || !$email) {
        $message = 'First name, last name, and email are required.';
        $message_type = 'error';
    } else {
        // Check if email is already used by another user
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check->bind_param("si", $email, $uid);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $message = 'This email is already in use by another account.';
            $message_type = 'error';
        } else {
            // Check if password change is requested
            if (!empty($new_password)) {
                // Verify current password
                $pwd_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
                $pwd_stmt->bind_param("i", $uid);
                $pwd_stmt->execute();
                $pwd_result = $pwd_stmt->get_result()->fetch_assoc();
                
                if (!password_verify($current_password, $pwd_result['password'])) {
                    $message = 'Current password is incorrect.';
                    $message_type = 'error';
                } elseif ($new_password !== $confirm_password) {
                    $message = 'New passwords do not match.';
                    $message_type = 'error';
                } elseif (strlen($new_password) < 6) {
                    $message = 'New password must be at least 6 characters long.';
                    $message_type = 'error';
                } else {
                    // Update profile and password
                    $hashed_pwd = password_hash($new_password, PASSWORD_BCRYPT);
                    $update = $conn->prepare("UPDATE users SET fname=?, lname=?, email=?, password=? WHERE id=?");
                    $update->bind_param("ssssi", $fname, $lname, $email, $hashed_pwd, $uid);
                    if ($update->execute()) {
                        $message = 'Profile and password updated successfully!';
                        $message_type = 'success';
                        // Refresh user data
                        $stmt = $conn->prepare("SELECT username, fname, lname, email FROM users WHERE id = ?");
                        $stmt->bind_param("i", $uid);
                        $stmt->execute();
                        $user = $stmt->get_result()->fetch_assoc();
                    } else {
                        $message = 'An error occurred while updating your profile.';
                        $message_type = 'error';
                    }
                }
            } else {
                // Update profile only
                $update = $conn->prepare("UPDATE users SET fname=?, lname=?, email=? WHERE id=?");
                $update->bind_param("sssi", $fname, $lname, $email, $uid);
                if ($update->execute()) {
                    $message = 'Profile updated successfully!';
                    $message_type = 'success';
                    // Refresh user data
                    $stmt = $conn->prepare("SELECT username, fname, lname, email FROM users WHERE id = ?");
                    $stmt->bind_param("i", $uid);
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();
                } else {
                    $message = 'An error occurred while updating your profile.';
                    $message_type = 'error';
                }
            }
        }
    }
}

$page_title = "Edit Profile";
$page_sub   = "Update your account information";
include '../includes/user_nav.php';
?>

<div class="card" style="max-width:600px;">
  <div class="card-header"><h3>✏️ Edit Your Profile</h3></div>
  <div class="card-body">
    <?php if ($message): ?>
      <div style="padding:14px;border-radius:8px;margin-bottom:16px;background:<?= $message_type === 'success' ? '#d4edda' : '#f8d7da' ?>;border:1px solid <?= $message_type === 'success' ? '#c3e6cb' : '#f5c6cb' ?>;color:<?= $message_type === 'success' ? '#155724' : '#721c24' ?>;font-size:14px;">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" style="display:flex;flex-direction:column;gap:16px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
          <label style="display:block;font-weight:600;margin-bottom:6px;font-size:13px;">Username</label>
          <input type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>" readonly style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;background:#f5f5f5;cursor:not-allowed;">
        </div>
        <div></div>
      </div>
      
      <div style="border-top:1px solid #e5e7eb;padding-top:16px;">
        <div style="font-size:13px;font-weight:600;color:#666;margin-bottom:12px;">Personal Information</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div>
            <label style="display:block;font-weight:600;margin-bottom:6px;font-size:13px;">First Name *</label>
            <input type="text" name="fname" value="<?= htmlspecialchars($user['fname'] ?? '') ?>" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
          </div>
          <div>
            <label style="display:block;font-weight:600;margin-bottom:6px;font-size:13px;">Last Name *</label>
            <input type="text" name="lname" value="<?= htmlspecialchars($user['lname'] ?? '') ?>" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
          </div>
        </div>
        
        <div style="margin-top:16px;">
          <label style="display:block;font-weight:600;margin-bottom:6px;font-size:13px;">Email *</label>
          <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
        </div>
      </div>
      
      <div style="border-top:1px solid #e5e7eb;padding-top:16px;">
        <div style="font-size:13px;font-weight:600;color:#666;margin-bottom:12px;">Change Password (Optional)</div>
        <p style="font-size:12px;color:#888;margin-bottom:12px;">Leave blank if you don't want to change your password</p>
        
        <div>
          <label style="display:block;font-weight:600;margin-bottom:6px;font-size:13px;">Current Password</label>
          <input type="password" name="current_password" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
        </div>
        
        <div style="margin-top:16px;">
          <label style="display:block;font-weight:600;margin-bottom:6px;font-size:13px;">New Password</label>
          <input type="password" name="new_password" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
        </div>
        
        <div style="margin-top:16px;">
          <label style="display:block;font-weight:600;margin-bottom:6px;font-size:13px;">Confirm New Password</label>
          <input type="password" name="confirm_password" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
        </div>
      </div>
      
      <div style="display:flex;gap:12px;margin-top:8px;border-top:1px solid #e5e7eb;padding-top:16px;">
        <button type="submit" style="padding:10px 24px;background:#3b82f6;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;font-size:14px;">Save Changes</button>
        <a href="dashboard.php" style="padding:10px 24px;background:#e5e7eb;color:#333;border-radius:6px;text-decoration:none;font-weight:600;font-size:14px;">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include '../includes/layout_bottom.php'; ?>
