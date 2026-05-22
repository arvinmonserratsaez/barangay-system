<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$uid  = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM complaints WHERE user_id=? ORDER BY created_at DESC");
$stmt->bind_param("i",$uid); $stmt->execute();
$rows = $stmt->get_result();
$page_title = "My Complaints";
$page_sub   = "Track the status of all your submitted complaints";
include '../includes/user_nav.php';
?>
<div class="card">
  <div class="card-header"><h3>&#128203; My Complaints</h3><a href="complaint.php">+ New Complaint</a></div>
  <?php if ($rows->num_rows > 0): ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>#</th><th>Category</th><th>Description</th><th>Status</th><th>Date Submitted</th></tr></thead>
      <tbody>
      <?php $i=1; while ($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($r['category']) ?></td>
          <td style="max-width:300px;color:var(--gray4)"><?= htmlspecialchars(substr($r['description'],0,100)) ?>...</td>
          <td><span class="badge <?= strtolower($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
          <td><?= date('M d, Y h:i A', strtotime($r['created_at'])) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
  <div class="empty-state"><div class="ei">&#128235;</div><p>No complaints submitted yet.</p><a href="complaint.php">File Your First Complaint</a></div>
  <?php endif; ?>
</div>
<?php include '../includes/layout_bottom.php'; ?>
