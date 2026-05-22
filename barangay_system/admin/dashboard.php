<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';

$total_users   = $conn->query("SELECT COUNT(*) c FROM users WHERE role='user'")->fetch_assoc()['c'];
$total_comp    = $conn->query("SELECT COUNT(*) c FROM complaints")->fetch_assoc()['c'];
$pending_comp  = $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Pending'")->fetch_assoc()['c'];
$total_appts   = $conn->query("SELECT COUNT(*) c FROM appointments")->fetch_assoc()['c'];
$pending_appts = $conn->query("SELECT COUNT(*) c FROM appointments WHERE status='Pending'")->fetch_assoc()['c'];
$total_emerg   = $conn->query("SELECT COUNT(*) c FROM emergencies")->fetch_assoc()['c'];
$active_emerg  = $conn->query("SELECT COUNT(*) c FROM emergencies WHERE status!='Resolved'")->fetch_assoc()['c'];

$recent_comp  = $conn->query("SELECT c.*, u.username FROM complaints c JOIN users u ON c.user_id=u.id ORDER BY c.created_at DESC LIMIT 5");
$recent_emerg = $conn->query("SELECT e.*, u.username FROM emergencies e JOIN users u ON e.user_id=u.id ORDER BY e.created_at DESC LIMIT 5");
$page_title = "Admin Dashboard";
$page_sub   = "System Overview — " . date('l, F d, Y');
include '../includes/admin_nav.php';
?>

<?php if ($active_emerg > 0): ?>
<div style="background:#fdecea;border:1px solid #c0392b30;border-left:5px solid #c0392b;border-radius:10px;padding:14px 20px;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between;">
  <div style="display:flex;align-items:center;gap:10px;">
    <span style="font-size:22px;">&#128680;</span>
    <div>
      <div style="font-weight:700;color:#c0392b;font-size:14px;"><?= $active_emerg ?> Active Emergency<?= $active_emerg > 1 ? 's' : '' ?> Reported</div>
      <div style="font-size:12px;color:#888;margin-top:2px;">Immediate attention may be required</div>
    </div>
  </div>
  <a href="manage_emergencies.php" style="padding:8px 16px;background:#c0392b;color:#fff;border-radius:6px;text-decoration:none;font-size:13px;font-weight:600;">View Now &rarr;</a>
</div>
<?php endif; ?>

<div class="stats-row">
  <a href="manage_residents.php" style="display:block;text-decoration:none;color:inherit;">
    <div class="stat-card"><div class="s-icon blue">&#128101;</div><div class="s-val"><?= $total_users ?></div><div class="s-label">Registered Residents</div></div>
  </a>
  <a href="manage_complaints.php" style="display:block;text-decoration:none;color:inherit;">
    <div class="stat-card"><div class="s-icon orange">&#128203;</div><div class="s-val"><?= $total_comp ?></div><div class="s-label">Total Complaints</div><div class="s-sub pending"><?= $pending_comp ?> pending</div></div>
  </a>
  <a href="manage_appointments.php" style="display:block;text-decoration:none;color:inherit;">
    <div class="stat-card"><div class="s-icon green">&#128197;</div><div class="s-val"><?= $total_appts ?></div><div class="s-label">Appointments</div><div class="s-sub pending"><?= $pending_appts ?> pending</div></div>
  </a>
  <a href="manage_emergencies.php" style="display:block;text-decoration:none;color:inherit;">
    <div class="stat-card"><div class="s-icon red">&#128680;</div><div class="s-val"><?= $total_emerg ?></div><div class="s-label">Emergencies</div><div class="s-sub active-e"><?= $active_emerg ?> active</div></div>
  </a>
</div>

<div class="two-col">
  <div>
    <div class="section-title">Recent Complaints</div>
    <div class="card">
      <div class="table-wrap">
        <table class="data-table">
          <thead><tr><th>Resident</th><th>Category</th><th>Status</th></tr></thead>
          <tbody>
          <?php while ($r=$recent_comp->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($r['username']) ?></td>
              <td><?= htmlspecialchars($r['category']) ?></td>
              <td><span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
    <a href="manage_complaints.php" style="font-size:13px;color:var(--blue);text-decoration:none;">View all &rarr;</a>
  </div>
  <div>
    <div class="section-title">Recent Emergencies</div>
    <div class="card">
      <div class="table-wrap">
        <table class="data-table">
          <thead><tr><th>Resident</th><th>Type</th><th>Status</th><th></th></tr></thead>
          <tbody>
          <?php while ($r=$recent_emerg->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($r['username']) ?></td>
              <td><?= htmlspecialchars($r['type']) ?></td>
              <td><span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
              <td><a href="view_emergency.php?id=<?= $r['id'] ?>" style="font-size:12px;color:var(--blue);text-decoration:none;font-weight:600;">View &rarr;</a></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
    <a href="manage_emergencies.php" style="font-size:13px;color:var(--blue);text-decoration:none;">View all &rarr;</a>
  </div>
</div>

<?php include '../includes/layout_bottom.php'; ?>
