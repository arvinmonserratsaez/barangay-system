<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$uid = $_SESSION['user_id'];

$rc = $conn->prepare("SELECT COUNT(*) c FROM complaints WHERE user_id=?");   $rc->bind_param("i",$uid); $rc->execute(); $cc = $rc->get_result()->fetch_assoc()['c'];
$ra = $conn->prepare("SELECT COUNT(*) c FROM appointments WHERE user_id=?"); $ra->bind_param("i",$uid); $ra->execute(); $ca = $ra->get_result()->fetch_assoc()['c'];
$re = $conn->prepare("SELECT COUNT(*) c FROM emergencies WHERE user_id=?");  $re->bind_param("i",$uid); $re->execute(); $ce = $re->get_result()->fetch_assoc()['c'];

$latest_complaint = $conn->prepare("SELECT category, status, created_at FROM complaints WHERE user_id=? ORDER BY created_at DESC LIMIT 1");
$latest_complaint->bind_param("i", $uid);
$latest_complaint->execute();
$latest_complaint = $latest_complaint->get_result()->fetch_assoc();

$next_appointment = $conn->prepare("SELECT appointment_date, appointment_time, service, status FROM appointments WHERE user_id=? ORDER BY appointment_date ASC, appointment_time ASC LIMIT 1");
$next_appointment->bind_param("i", $uid);
$next_appointment->execute();
$next_appointment = $next_appointment->get_result()->fetch_assoc();

$latest_emergency = $conn->prepare("SELECT type, status, created_at FROM emergencies WHERE user_id=? ORDER BY created_at DESC LIMIT 1");
$latest_emergency->bind_param("i", $uid);
$latest_emergency->execute();
$latest_emergency = $latest_emergency->get_result()->fetch_assoc();

$ann_count = $conn->query("SELECT COUNT(*) c FROM announcements")->fetch_assoc()['c'];
$latest    = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 1")->fetch_assoc();

$page_title = "Dashboard";
$page_sub   = "Welcome back, " . htmlspecialchars($_SESSION['username']) . " — " . date('l, F d, Y');
include '../includes/user_nav.php';
?>

<div class="stats-row">
  <a href="view_complaints.php" style="display:block;text-decoration:none;color:inherit;">
    <div class="stat-card"><div class="s-icon orange">&#128203;</div><div class="s-val"><?= $cc ?></div><div class="s-label">My Complaints</div>
      <?php if ($latest_complaint): ?><div class="s-sub">Latest: <?= htmlspecialchars($latest_complaint['category']) ?> — <?= htmlspecialchars($latest_complaint['status']) ?></div><?php else: ?><div class="s-sub">No complaints yet</div><?php endif; ?>
    </div>
  </a>
  <a href="view_appointments.php" style="display:block;text-decoration:none;color:inherit;">
    <div class="stat-card"><div class="s-icon blue">&#128197;</div><div class="s-val"><?= $ca ?></div><div class="s-label">My Appointments</div>
      <?php if ($next_appointment): ?><div class="s-sub">Next: <?= date('M d', strtotime($next_appointment['appointment_date'])) ?> <?= date('h:i A', strtotime($next_appointment['appointment_time'])) ?> (<?= htmlspecialchars($next_appointment['status']) ?>)</div><?php else: ?><div class="s-sub">No appointments booked</div><?php endif; ?>
    </div>
  </a>
  <a href="emergency.php" style="display:block;text-decoration:none;color:inherit;">
    <div class="stat-card"><div class="s-icon red">&#128680;</div><div class="s-val"><?= $ce ?></div><div class="s-label">Emergency Reports</div>
      <?php if ($latest_emergency): ?><div class="s-sub">Last: <?= htmlspecialchars($latest_emergency['type']) ?> — <?= htmlspecialchars($latest_emergency['status']) ?></div><?php else: ?><div class="s-sub">No emergency reports</div><?php endif; ?>
    </div>
  </a>
  <a href="announcements.php" style="display:block;text-decoration:none;color:inherit;">
    <div class="stat-card"><div class="s-icon green">&#128226;</div><div class="s-val"><?= $ann_count ?></div><div class="s-label">Announcements</div>
      <?php if ($latest): ?><div class="s-sub">Latest: <?= htmlspecialchars(substr($latest['title'], 0, 24)) ?>...</div><?php endif; ?>
    </div>
  </a>
</div>

<div class="section-title">Quick Access</div>
<div class="module-grid">
  <a href="edit_profile.php"      class="module-tile"><div class="t-icon">👤</div><div class="t-title">Edit Profile</div><div class="t-desc">Update your information</div></a>
  <a href="complaint.php"         class="module-tile"><div class="t-icon">&#128203;</div><div class="t-title">File a Complaint</div><div class="t-desc">Submit concerns or issues</div></a>
  <a href="view_complaints.php"   class="module-tile"><div class="t-icon">&#128269;</div><div class="t-title">View Complaints</div><div class="t-desc">Track your complaint status</div></a>
  <a href="appointment.php"       class="module-tile"><div class="t-icon">&#128197;</div><div class="t-title">Book Appointment</div><div class="t-desc">Schedule a barangay visit</div></a>
  <a href="view_appointments.php" class="module-tile"><div class="t-icon">&#128467;</div><div class="t-title">My Appointments</div><div class="t-desc">View booking status</div></a>
  <a href="announcements.php"     class="module-tile"><div class="t-icon">&#128226;</div><div class="t-title">Announcements</div><div class="t-desc">Barangay news &amp; updates</div></a>
  <a href="emergency.php"         class="module-tile emergency"><div class="t-icon">&#128680;</div><div class="t-title">Report Emergency</div><div class="t-desc">Alert barangay officials</div></a>
</div>

<?php if ($latest): ?>
<div class="section-title">Latest Announcement</div>
<div class="ann-preview">
  <div class="ap-label">&#128226; Latest Update</div>
  <h3><?= htmlspecialchars($latest['title']) ?></h3>
  <p><?= htmlspecialchars(substr($latest['content'], 0, 220)) ?>...</p>
  <div class="ap-meta">&#128336; <?= date('F d, Y', strtotime($latest['created_at'])) ?></div>
  <a href="announcements.php" class="see-all">See all announcements &rarr;</a>
</div>
<?php endif; ?>

<?php include '../includes/layout_bottom.php'; ?>
