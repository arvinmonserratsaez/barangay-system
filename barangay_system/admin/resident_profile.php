<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$resident = null;
$error = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, fname, lname, username, email FROM users WHERE id = ? AND role = 'user'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resident = $result->fetch_assoc();
    if (!$resident) {
        $error = 'Resident not found or unavailable.';
    }
} else {
    $error = 'Resident not found.';
}
$page_title = 'Resident Profile';
$page_sub   = 'Profile details for the selected resident';
include '../includes/admin_nav.php';
?>
<div class="card">
  <div class="card-header"><h3>&#128101; Resident Profile</h3></div>
  <?php if ($error): ?>
    <div class="empty-state"><div class="ei">&#128235;</div><p><?= htmlspecialchars($error) ?></p></div>
  <?php else: ?>
    <div class="card-body" style="padding:24px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;max-width:760px;">
        <div>
          <div class="field-label">Full Name</div>
          <div class="field-value"><?= htmlspecialchars(trim($resident['fname'] . ' ' . $resident['lname'])) ?></div>
        </div>
        <div>
          <div class="field-label">Username</div>
          <div class="field-value"><?= htmlspecialchars($resident['username']) ?></div>
        </div>
        <div>
          <div class="field-label">Email</div>
          <div class="field-value"><?= htmlspecialchars($resident['email']) ?></div>
        </div>
        <div>
          <div class="field-label">Role</div>
          <div class="field-value">Resident</div>
        </div>
      </div>
      <div style="margin-top:22px;">
        <a href="manage_residents.php" style="display:inline-block;padding:10px 18px;background:#3b82f6;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;">&larr; Back to Residents</a>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php include '../includes/layout_bottom.php'; ?>