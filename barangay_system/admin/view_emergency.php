<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';

// Handle status update from this page
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'], $_POST['emergency_id'])) {
    $id = intval($_POST['emergency_id']);
    $st = $_POST['status'];
    if (in_array($st, ['Urgent', 'Responding', 'Resolved'])) {
        $s = $conn->prepare("UPDATE emergencies SET status=? WHERE id=?");
        $s->bind_param("si", $st, $id);
        $s->execute();
    }
    header("Location: view_emergency.php?id=$id&updated=1");
    exit();
}

// Get emergency ID from URL
if (!isset($_GET['id'])) {
    header("Location: manage_emergencies.php");
    exit();
}

$id   = intval($_GET['id']);
$stmt = $conn->prepare("SELECT e.*, u.username, u.fname, u.lname, u.email FROM emergencies e JOIN users u ON e.user_id = u.id WHERE e.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$r = $stmt->get_result()->fetch_assoc();

if (!$r) {
    header("Location: manage_emergencies.php");
    exit();
}

$page_title = "Emergency Report #" . $id;
$page_sub   = "Reported on " . date('F d, Y \a\t h:i A', strtotime($r['created_at']));
include '../includes/admin_nav.php';
?>

<?php if (isset($_GET['updated'])): ?>
<div class="alert success">&#10003; Emergency status updated successfully.</div>
<?php endif; ?>

<!-- Back link -->
<div style="margin-bottom:20px;">
  <a href="manage_emergencies.php" style="color:var(--blue);text-decoration:none;font-size:13px;font-weight:600;">
    &#8592; Back to All Emergencies
  </a>
</div>

<!-- Status Banner -->
<?php
$statusColor = ['Urgent' => '#c0392b', 'Responding' => '#d4770a', 'Resolved' => '#1a8a4a'];
$statusBg    = ['Urgent' => '#fdecea', 'Responding' => '#fef3e2', 'Resolved' => '#e6f7ed'];
$sc = $statusColor[$r['status']] ?? '#333';
$sb = $statusBg[$r['status']]   ?? '#f4f5f7';
?>
<div style="background:<?= $sb ?>;border:1px solid <?= $sc ?>30;border-left:5px solid <?= $sc ?>;border-radius:10px;padding:18px 22px;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between;">
  <div>
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:<?= $sc ?>;margin-bottom:4px;">Current Status</div>
    <div style="font-size:22px;font-weight:700;color:<?= $sc ?>;"><?= htmlspecialchars($r['status']) ?></div>
  </div>
  <div style="font-size:32px;">
    <?php
    $icons = ['Urgent' => '&#128680;', 'Responding' => '&#128657;', 'Resolved' => '&#10003;'];
    echo $icons[$r['status']] ?? '&#128680;';
    ?>
  </div>
</div>

<div class="two-col" style="gap:20px;align-items:start;">

  <!-- LEFT: Emergency Details -->
  <div>
    <div class="card" style="margin-bottom:20px;">
      <div class="card-header"><h3>&#128680; Emergency Details</h3></div>
      <div style="padding:20px;">

        <div style="margin-bottom:18px;">
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--gray3);margin-bottom:6px;">Emergency Type</div>
          <span class="type-badge" style="font-size:13px;padding:6px 14px;"><?= htmlspecialchars($r['type']) ?></span>
        </div>

        <div style="margin-bottom:18px;">
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--gray3);margin-bottom:6px;">Location</div>
          <div style="font-size:14px;color:var(--text);font-weight:600;">&#128205; <?= htmlspecialchars($r['location']) ?></div>
        </div>

        <div style="margin-bottom:18px;">
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--gray3);margin-bottom:6px;">Date &amp; Time Reported</div>
          <div style="font-size:14px;color:var(--text);">&#128336; <?= date('F d, Y \a\t h:i A', strtotime($r['created_at'])) ?></div>
        </div>

        <div>
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--gray3);margin-bottom:6px;">Full Description</div>
          <div style="font-size:14px;color:var(--text);line-height:1.8;background:var(--gray);padding:14px 16px;border-radius:8px;">
            <?= nl2br(htmlspecialchars($r['description'])) ?>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- RIGHT: Reporter Info + Update Status -->
  <div>

    <!-- Reporter Info -->
    <div class="card" style="margin-bottom:20px;">
      <div class="card-header"><h3>&#128100; Reporter Information</h3></div>
      <div style="padding:20px;">

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
          <div class="avatar" style="width:46px;height:46px;font-size:15px;flex-shrink:0;">
            <?= strtoupper(substr($r['username'], 0, 2)) ?>
          </div>
          <div>
            <div style="font-size:15px;font-weight:700;color:var(--text);">
              <?= htmlspecialchars(($r['fname']??'') . ' ' . ($r['lname']??'')) ?>
            </div>
            <div style="font-size:12px;color:var(--gray3);">@<?= htmlspecialchars($r['username']) ?></div>
          </div>
        </div>

        <div style="border-top:1px solid var(--gray2);padding-top:14px;">
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--gray3);margin-bottom:6px;">Email</div>
          <div style="font-size:13px;color:var(--text);">&#9993; <?= htmlspecialchars($r['email']) ?></div>
        </div>

      </div>
    </div>

    <!-- Update Status -->
    <div class="card">
      <div class="card-header"><h3>&#9998; Update Status</h3></div>
      <div style="padding:20px;">
        <form method="POST">
          <input type="hidden" name="emergency_id" value="<?= $r['id'] ?>">
          <div class="form-group">
            <label>Change Status To</label>
            <select name="status" style="width:100%;padding:10px 14px;border:1.5px solid var(--gray2);border-radius:6px;font-size:14px;outline:none;">
              <option value="Urgent"     <?= $r['status']==='Urgent'     ?'selected':'' ?>>&#128680; Urgent — Not yet responded</option>
              <option value="Responding" <?= $r['status']==='Responding' ?'selected':'' ?>>&#128657; Responding — Officials on the way</option>
              <option value="Resolved"   <?= $r['status']==='Resolved'   ?'selected':'' ?>>&#10003; Resolved — Situation handled</option>
            </select>
          </div>
          <button type="submit" class="btn-primary red" style="width:100%;">&#128680; Update Emergency Status</button>
        </form>

        <div style="margin-top:16px;padding:12px;background:var(--orange-light);border-radius:6px;font-size:12px;color:var(--orange);">
          <strong>Reminder:</strong> For active emergencies, also coordinate with local MDRRMO and call 911 if needed.
        </div>
      </div>
    </div>

  </div>
</div>

<?php include '../includes/layout_bottom.php'; ?>
