<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$uid  = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM appointments WHERE user_id=? ORDER BY appointment_date DESC");
$stmt->bind_param("i",$uid); $stmt->execute();
$rows = $stmt->get_result();
$page_title = "My Appointments";
$page_sub   = "View and track your appointment bookings";
include '../includes/user_nav.php';
?>
<div class="card">
  <div class="card-header"><h3>&#128467; My Appointments</h3><a href="appointment.php">+ Book New</a></div>
  <?php if ($rows->num_rows > 0): ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>#</th><th>Purpose</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th>Booked On</th></tr></thead>
      <tbody>
      <?php $i=1; while ($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($r['purpose']??'—') ?></td>
          <td><?= htmlspecialchars($r['service']??'—') ?></td>
          <td><?= date('M d, Y', strtotime($r['appointment_date'])) ?></td>
          <td><?= date('h:i A', strtotime($r['appointment_time'])) ?></td>
          <td><span class="badge <?= strtolower($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
          <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
  <div class="empty-state"><div class="ei">&#128235;</div><p>No appointments booked yet.</p><a href="appointment.php">Book Your First Appointment</a></div>
  <?php endif; ?>
</div>
<?php include '../includes/layout_bottom.php'; ?>
