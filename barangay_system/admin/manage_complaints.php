<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['complaint_id'],$_POST['status'])) {
    $id=$_POST['complaint_id']; $st=$_POST['status'];
    if (in_array($st,['Pending','Resolved'])) {
        $s=$conn->prepare("UPDATE complaints SET status=? WHERE id=?"); $s->bind_param("si",$st,$id); $s->execute();
    }
    header("Location: manage_complaints.php?updated=1"); exit();
}
$rows=$conn->query("SELECT c.*,u.username,u.email FROM complaints c JOIN users u ON c.user_id=u.id ORDER BY c.created_at DESC");
$page_title="Manage Complaints"; $page_sub="Review and resolve resident complaints";
include '../includes/admin_nav.php';
?>
<?php if (isset($_GET['updated'])): ?><div class="alert success">&#10003; Complaint status updated.</div><?php endif; ?>
<div class="card">
  <div class="card-header"><h3>&#128203; All Complaints</h3></div>
  <?php if ($rows && $rows->num_rows>0): ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>#</th><th>Resident</th><th>Category</th><th>Description</th><th>Status</th><th>Date</th><th>Update</th></tr></thead>
      <tbody>
      <?php $i=1; while ($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><strong><?= htmlspecialchars($r['username']) ?></strong><br><small><?= htmlspecialchars($r['email']) ?></small></td>
          <td style="font-weight:600"><?= htmlspecialchars($r['category']) ?></td>
          <td style="max-width:220px;color:var(--gray4)"><?= htmlspecialchars(substr($r['description'],0,80)) ?>...</td>
          <td><span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
          <td><?= date('M d, Y',strtotime($r['created_at'])) ?></td>
          <td>
            <form method="POST" style="display:flex;gap:4px">
              <input type="hidden" name="complaint_id" value="<?= $r['id'] ?>">
              <select name="status" class="status-sel">
                <option value="Pending"  <?= $r['status']==='Pending'  ?'selected':'' ?>>Pending</option>
                <option value="Resolved" <?= $r['status']==='Resolved' ?'selected':'' ?>>Resolved</option>
              </select>
              <button type="submit" class="btn-save">Save</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?><div class="empty-state"><div class="ei">&#128235;</div><p>No complaints yet.</p></div><?php endif; ?>
</div>
<?php include '../includes/layout_bottom.php'; ?>
