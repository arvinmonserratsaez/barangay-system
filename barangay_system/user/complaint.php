<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$success = $error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_SESSION['user_id'];
    $cat = $_POST['category'];
    $desc = trim($_POST['description']);
    if (empty($desc)) {
        $error = "Please describe your complaint.";
    } else {
        $stmt = $conn->prepare("INSERT INTO complaints (user_id, category, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $uid, $cat, $desc);
        $stmt->execute() ? $success = "Complaint submitted successfully!" : $error = "Something went wrong.";
    }
}
$page_title = "File a Complaint";
$page_sub   = "Submit a new complaint or concern";
include '../includes/user_nav.php';
?>
<?php if ($success): ?><div class="alert success">&#10003; <?= $success ?> <a href="view_complaints.php">View my complaints &rarr;</a></div><?php endif; ?>
<?php if ($error):   ?><div class="alert error">&#10005; <?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="form-card">
  <form method="POST">
    <div class="form-group">
      <label>Category <span style="color:var(--red)">*</span></label>
      <select name="category" required>
        <option value="Noise Complaint">Noise Complaint</option>
        <option value="Garbage / Sanitation">Garbage / Sanitation</option>
        <option value="Road Damage">Road Damage</option>
        <option value="Illegal Structures">Illegal Structures</option>
        <option value="Public Safety">Public Safety</option>
        <option value="Neighborhood Dispute">Neighborhood Dispute</option>
        <option value="Others">Others</option>
      </select>
    </div>
    <div class="form-group">
      <label>Description <span style="color:var(--red)">*</span></label>
      <textarea name="description" placeholder="Describe the issue in detail. Include location, time, and persons involved..." required></textarea>
    </div>
    <button type="submit" class="btn-primary">&#128203; Submit Complaint</button>
  </form>
</div>
<?php include '../includes/layout_bottom.php'; ?>
