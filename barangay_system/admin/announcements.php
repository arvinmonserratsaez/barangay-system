<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
include '../config/database.php';
$success=$error="";
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action']) && $_POST['action']==='create') {
    $title=trim($_POST['title']); $content=trim($_POST['content']);
    if (empty($title)||empty($content)) { $error="Title and content are required."; }
    else {
        $s=$conn->prepare("INSERT INTO announcements (title,content) VALUES (?,?)");
        $s->bind_param("ss",$title,$content);
        $s->execute() ? $success="Announcement posted!" : $error="Something went wrong.";
    }
}
if (isset($_GET['delete'])) {
    $id=intval($_GET['delete']);
    $conn->query("DELETE FROM announcements WHERE id=$id");
    header("Location: announcements.php?deleted=1"); exit();
}
$rows=$conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
$page_title="Announcements"; $page_sub="Post and manage barangay announcements";
include '../includes/admin_nav.php';
?>
<?php if ($success): ?><div class="alert success">&#10003; <?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert error">&#10005; <?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><div class="alert success">&#128465; Announcement deleted.</div><?php endif; ?>

<div class="form-card" style="margin-bottom:28px;">
  <div class="section-title">&#128226; Post New Announcement</div>
  <form method="POST" style="margin-top:16px;">
    <input type="hidden" name="action" value="create">
    <div class="form-group"><label>Title <span style="color:var(--red)">*</span></label><input type="text" name="title" placeholder="Announcement title..." required></div>
    <div class="form-group"><label>Content <span style="color:var(--red)">*</span></label><textarea name="content" placeholder="Write the full announcement here..." required></textarea></div>
    <button type="submit" class="btn-primary">&#128226; Post Announcement</button>
  </form>
</div>

<div class="section-title">All Posted Announcements</div>
<?php if ($rows && $rows->num_rows>0): while ($r=$rows->fetch_assoc()): ?>
  <div class="ann-card">
    <h3><?= htmlspecialchars($r['title']) ?></h3>
    <p><?= nl2br(htmlspecialchars($r['content'])) ?></p>
    <div class="meta">
      <span>&#128336; <?= date('F d, Y \a\t h:i A',strtotime($r['created_at'])) ?></span>
      <a href="announcements.php?delete=<?= $r['id'] ?>" class="btn-del" onclick="return confirm('Delete this announcement?')">&#128465; Delete</a>
    </div>
  </div>
<?php endwhile; else: ?>
  <div class="empty-state"><div class="ei">&#128235;</div><p>No announcements posted yet.</p></div>
<?php endif; ?>
<?php include '../includes/layout_bottom.php'; ?>
