<?php
$current  = basename($_SERVER['PHP_SELF']);
$initials = strtoupper(substr($_SESSION['username'] ?? 'A', 0, 2));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'Admin Panel') ?></title>
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body>
<div class="app-layout">
  <div class="sidebar admin">
    <div class="sidebar-brand">
      <div class="brand-name">&#127963; Admin Panel</div>
      <div class="brand-sub">Sto. Angel, San Pablo City</div>
    </div>
    <div class="sidebar-user">
      <div class="avatar red"><?= $initials ?></div>
      <div>
        <div class="uname"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></div>
        <div class="urole">Administrator</div>
      </div>
    </div>
    <div class="nav-section">
      <div class="nav-label">Management</div>
      <a href="dashboard.php"            class="nav-item <?= $current==='dashboard.php'            ?'active red-a':'' ?>"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
      <a href="manage_residents.php"     class="nav-item <?= $current==='manage_residents.php'     ?'active red-a':'' ?>"><i class="ti ti-users"></i> Residents</a>
      <a href="manage_complaints.php"    class="nav-item <?= $current==='manage_complaints.php'    ?'active red-a':'' ?>"><i class="ti ti-message-report"></i> Complaints</a>
      <a href="manage_appointments.php"  class="nav-item <?= $current==='manage_appointments.php'  ?'active red-a':'' ?>"><i class="ti ti-calendar-event"></i> Appointments</a>
      <a href="manage_emergencies.php"   class="nav-item <?= $current==='manage_emergencies.php'   ?'active red-a':'' ?>"><i class="ti ti-alert-triangle"></i> Emergencies</a>
      <a href="announcements.php"        class="nav-item <?= $current==='announcements.php'        ?'active red-a':'' ?>"><i class="ti ti-speakerphone"></i> Announcements</a>
    </div>
    <div class="sidebar-footer">
      <a href="../auth/logout.php" class="logout-btn"><i class="ti ti-logout"></i> Sign Out</a>
    </div>
  </div>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title"><?= htmlspecialchars($page_title ?? 'Dashboard') ?></div>
        <div class="topbar-sub"><?= htmlspecialchars($page_sub ?? date('l, F d, Y')) ?></div>
      </div>
    </div>
    <div class="page">
