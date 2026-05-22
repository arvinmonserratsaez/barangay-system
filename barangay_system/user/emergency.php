<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$success = $error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid  = $_SESSION['user_id'];
    $type = trim($_POST['type']);
    $desc = trim($_POST['description']);
    $loc  = trim($_POST['location']);
    if (empty($type)||empty($desc)||empty($loc)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO emergencies (user_id, type, description, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $uid, $type, $desc, $loc);
        $stmt->execute() ? $success = "Emergency reported! Barangay officials have been notified. For immediate danger, also call 911." : $error = "Something went wrong.";
    }
}
$page_title = "Report Emergency";
$page_sub   = "Alert Barangay officials immediately";
include '../includes/user_nav.php';
?>
<?php if ($success): ?><div class="alert success">&#128680; <?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert error">&#10005; <?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="emergency-banner">
  <h3>&#128680; Emergency Report</h3>
  <p>For life-threatening emergencies, also call <strong>911</strong> or your local MDRRMO hotline immediately</p>
</div>
<div class="form-card">
  <form method="POST">
    <div class="form-group">
      <label>Emergency Type <span style="color:var(--red)">*</span></label>
      <select name="type" required>
        <option value="">-- Select Emergency Type --</option>
        <option value="Fire">Fire</option>
        <option value="Medical Emergency">Medical Emergency</option>
        <option value="Flood">Flood</option>
        <option value="Crime / Robbery">Crime / Robbery</option>
        <option value="Accident">Accident</option>
        <option value="Domestic Violence">Domestic Violence</option>
        <option value="Missing Person">Missing Person</option>
        <option value="Others">Others</option>
      </select>
    </div>
    <div class="form-group">
      <label>Exact Location <span style="color:var(--red)">*</span></label>
      <input type="text" name="location" placeholder="House no., Street, Purok, Landmark..." required>
    </div>
    <div class="form-group">
      <label>Description <span style="color:var(--red)">*</span></label>
      <textarea name="description" placeholder="Describe what is happening in detail..." required></textarea>
    </div>
    <button type="submit" class="btn-primary red">&#128680; REPORT EMERGENCY NOW</button>
  </form>
</div>
<?php include '../includes/layout_bottom.php'; ?>
