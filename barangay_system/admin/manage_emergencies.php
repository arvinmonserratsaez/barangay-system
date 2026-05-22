<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['emergency_id'],$_POST['status'])) {
    $id=$_POST['emergency_id']; $st=$_POST['status'];
    if (in_array($st,['Urgent','Responding','Resolved'])) {
        $s=$conn->prepare("UPDATE emergencies SET status=? WHERE id=?"); $s->bind_param("si",$st,$id); $s->execute();
    }
    header("Location: manage_emergencies.php?updated=1"); exit();
}
$rows=$conn->query("SELECT e.*,u.username,u.email FROM emergencies e JOIN users u ON e.user_id=u.id ORDER BY e.created_at DESC");
$page_title="Manage Emergencies"; $page_sub="Monitor and respond to reported emergencies";
include '../includes/admin_nav.php';
?>
<?php if (isset($_GET['updated'])): ?><div class="alert success">&#10003; Emergency status updated.</div><?php endif; ?>
<div class="card">
  <div class="card-header"><h3>&#128680; All Emergency Reports</h3></div>
  <?php if ($rows && $rows->num_rows>0): ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>#</th><th>Reporter</th><th>Type</th><th>Location</th><th>Description</th><th>Status</th><th>Date &amp; Time</th><th>Update</th><th>View</th></tr></thead>
      <tbody>
      <?php $i=1; while ($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><strong><?= htmlspecialchars($r['username']) ?></strong><br><small><?= htmlspecialchars($r['email']) ?></small></td>
          <td><span class="type-badge"><?= htmlspecialchars($r['type']) ?></span></td>
          <td><?= htmlspecialchars($r['location']) ?></td>
          <td style="max-width:180px;color:var(--gray4)"><?= htmlspecialchars(substr($r['description'],0,70)) ?>...</td>
          <td><span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
          <td><?= date('M d, Y h:i A',strtotime($r['created_at'])) ?></td>
          <td>
            <form method="POST" style="display:flex;gap:4px">
              <input type="hidden" name="emergency_id" value="<?= $r['id'] ?>">
              <select name="status" class="status-sel">
                <option value="Urgent"     <?= $r['status']==='Urgent'     ?'selected':'' ?>>Urgent</option>
                <option value="Responding" <?= $r['status']==='Responding' ?'selected':'' ?>>Responding</option>
                <option value="Resolved"   <?= $r['status']==='Resolved'   ?'selected':'' ?>>Resolved</option>
              </select>
              <button type="submit" class="btn-save red">Save</button>
            </form>
          </td>
          <td>
            <a href="view_emergency.php?id=<?= $r['id'] ?>" style="display:inline-block;padding:5px 12px;background:var(--blue-light);color:var(--blue);border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">
              &#128065; View
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?><div class="empty-state"><div class="ei">&#10003;</div><p>No emergencies reported.</p></div><?php endif; ?>
</div>
<?php include '../includes/layout_bottom.php'; ?>
