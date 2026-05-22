+<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$success = $error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid     = $_SESSION['user_id'];
    $purpose = trim($_POST['purpose']);
    $service = $_POST['service'];
    $date    = $_POST['appointment_date'];
    $time    = $_POST['appointment_time'];
    if (empty($purpose)||empty($service)||empty($date)||empty($time)) {
        $error = "Please fill in all required fields.";
    } elseif (strtotime($date) < strtotime(date('Y-m-d'))) {
        $error = "Appointment date cannot be in the past.";
    } else {
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, purpose, service, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $uid, $purpose, $service, $date, $time);
        $stmt->execute() ? $success = "Appointment submitted! Please wait for approval." : $error = "Something went wrong.";
    }
}
$page_title = "Book Appointment";
$page_sub   = "Schedule a visit to the Barangay Hall";
include '../includes/user_nav.php';
?>
<?php if ($success): ?><div class="alert success">&#10003; <?= $success ?> <a href="view_appointments.php">View my appointments &rarr;</a></div><?php endif; ?>
<?php if ($error):   ?><div class="alert error">&#10005; <?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="form-card">
  <form method="POST">
    <div class="form-group">
      <label>Purpose / Reason <span style="color:var(--red)">*</span></label>
      <input type="text" name="purpose" placeholder="e.g. Request Barangay Clearance for employment" required>
    </div>
    <div class="form-group">
      <label>Service Needed <span style="color:var(--red)">*</span></label>
      <select name="service" required>
        <option value="">-- Select Service --</option>
        <option>Barangay Clearance</option>
        <option>Certificate of Residency</option>
        <option>Business Permit</option>
        <option>Indigency Certificate</option>
        <option>Good Moral Certificate</option>
        <option>Complaint Resolution</option>
        <option>Others</option>
      </select>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Preferred Date <span style="color:var(--red)">*</span></label>
        <input type="date" name="appointment_date" min="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="form-group">
        <label>Preferred Time <span style="color:var(--red)">*</span></label>
        <input type="time" name="appointment_time" min="08:00" max="17:00" required>
      </div>
    </div>
    <button type="submit" class="btn-primary">&#128197; Submit Appointment Request</button>
  </form>
</div>
<?php include '../includes/layout_bottom.php'; ?>
