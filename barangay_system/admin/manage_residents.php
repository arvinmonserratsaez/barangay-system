<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_resident_id'])) {
    $delete_id = intval($_POST['delete_resident_id']);
    if ($delete_id > 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        header("Location: manage_residents.php?deleted=1");
        exit();
    }
}

$rows = $conn->query("SELECT id, fname, lname, username, email FROM users WHERE role='user' ORDER BY username ASC");
$page_title = "Manage Residents"; $page_sub = "View and manage registered residents";
include '../includes/admin_nav.php';
?>
<div class="card">
<?php if (isset($_GET['deleted'])): ?><div class="alert success">Resident deleted successfully.</div><?php endif; ?>
  <div class="card-header"><h3>&#128101; Registered Residents</h3></div>
  <?php if ($rows && $rows->num_rows>0): ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>#</th><th>Resident</th><th>Username</th><th>Email</th><th>Action</th></tr></thead>
      <tbody>
      <?php $i=1; while ($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><strong><a href="resident_profile.php?id=<?= $r['id'] ?>" style="color:inherit;text-decoration:none;"><?= htmlspecialchars(trim(($r['fname'] . ' ' . $r['lname']) ?: $r['username'])) ?></a></strong></td>
          <td><?= htmlspecialchars($r['username']) ?></td>
          <td><?= htmlspecialchars($r['email']) ?></td>
          <td>
            <form method="POST" onsubmit="return confirm('Delete this resident permanently?');" style="margin:0;">
              <input type="hidden" name="delete_resident_id" value="<?= $r['id'] ?>">
              <button type="submit" class="btn-delete" style="background:#e74c3c;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;">Delete</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
  <div class="empty-state"><div class="ei">&#128235;</div><p>No residents registered yet.</p></div>
  <?php endif; ?>
</div>
<?php include '../includes/layout_bottom.php'; ?>