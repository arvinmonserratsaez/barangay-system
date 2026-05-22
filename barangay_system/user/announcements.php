<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$rows = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
$page_title = "Announcements";
$page_sub   = "Latest news and updates from the Barangay";
include '../includes/user_nav.php';
?>
<?php if ($rows && $rows->num_rows > 0): while ($r=$rows->fetch_assoc()):
  $isNew = strtotime($r['created_at']) > strtotime('-7 days');
?>
  <div class="ann-card">
    <h3><?= htmlspecialchars($r['title']) ?><?php if ($isNew): ?><span class="badge-new">NEW</span><?php endif; ?></h3>
    <p><?= nl2br(htmlspecialchars($r['content'])) ?></p>
    <div class="meta"><span>&#128336; <?= date('F d, Y \a\t h:i A', strtotime($r['created_at'])) ?></span></div>
  </div>
<?php endwhile; else: ?>
  <div class="empty-state"><div class="ei">&#128235;</div><p>No announcements at this time. Check back later.</p></div>
<?php endif; ?>
<?php include '../includes/layout_bottom.php'; ?>
