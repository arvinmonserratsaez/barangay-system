<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['appointment_id'],$_POST['status'])) {
    $id=$_POST['appointment_id']; $st=$_POST['status'];
    if (in_array($st,['Pending','Approved','Rejected'])) {
        $s=$conn->prepare("UPDATE appointments SET status=? WHERE id=?"); $s->bind_param("si",$st,$id); $s->execute();
    }
    header("Location: manage_appointments.php?updated=1"); exit();
}
$rows=$conn->query("SELECT a.*,u.username,u.email FROM appointments a JOIN users u ON a.user_id=u.id ORDER BY a.appointment_date ASC,a.appointment_time ASC");
$page_title="Manage Appointments"; $page_sub="Review and approve appointment requests";
include '../includes/admin_nav.php';
?>
<?php if (isset($_GET['updated'])): ?><div class="alert success">&#10003; Appointment status updated.</div><?php endif; ?>
<div class="card">
  <div class="card-header"><h3>&#128197; All Appointments</h3></div>
  <?php if ($rows && $rows->num_rows>0): ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>#</th><th>Resident</th><th>Purpose</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th>Update</th></tr></thead>
      <tbody>
      <?php $i=1; while ($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><strong><?= htmlspecialchars($r['username']) ?></strong><br><small><?= htmlspecialchars($r['email']) ?></small></td>
          <td style="font-weight:600"><?= htmlspecialchars($r['purpose']??'—') ?></td>
          <td><?= htmlspecialchars($r['service']??'—') ?></td>
          <td><?= date('M d, Y',strtotime($r['appointment_date'])) ?></td>
          <td><?= date('h:i A',strtotime($r['appointment_time'])) ?></td>
          <td><span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
          <td>
            <form method="POST" style="display:flex;gap:4px">
              <input type="hidden" name="appointment_id" value="<?= $r['id'] ?>">
              <select name="status" class="status-sel">
                <option value="Pending"  <?= $r['status']==='Pending'  ?'selected':'' ?>>Pending</option>
                <option value="Approved" <?= $r['status']==='Approved' ?'selected':'' ?>>Approved</option>
                <option value="Rejected" <?= $r['status']==='Rejected' ?'selected':'' ?>>Rejected</option>
              </select>
              <button type="submit" class="btn-save">Save</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?><div class="empty-state"><div class="ei">&#128235;</div><p>No appointments yet.</p></div><?php endif; ?>
</div>
<?php include '../includes/layout_bottom.php'; ?>
