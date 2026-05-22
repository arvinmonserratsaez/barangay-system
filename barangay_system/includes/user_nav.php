<?php
$current  = basename($_SERVER['PHP_SELF']);
$initials = strtoupper(substr($_SESSION['username'] ?? 'U', 0, 2));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'Barangay System') ?></title>
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body>
<div class="app-layout">
  <div class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-name">&#127963; Barangay System</div>
      <div class="brand-sub">Sto. Angel, San Pablo City</div>
    </div>
    <a href="edit_profile.php" class="sidebar-user" style="text-decoration:none;color:inherit;cursor:pointer;">
      <div class="avatar"><?= $initials ?></div>
      <div>
        <div class="uname"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></div>
        <div class="urole">Resident</div>
      </div>
    </a>
    <div class="nav-section">
      <div class="nav-label">Main Menu</div>
      <a href="dashboard.php"          class="nav-item <?= $current==='dashboard.php'          ?'active':'' ?>"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
      <a href="complaint.php"          class="nav-item <?= $current==='complaint.php'          ?'active':'' ?>"><i class="ti ti-message-report"></i> File Complaint</a>
      <a href="view_complaints.php"    class="nav-item <?= $current==='view_complaints.php'    ?'active':'' ?>"><i class="ti ti-list-details"></i> My Complaints</a>
      <a href="appointment.php"        class="nav-item <?= $current==='appointment.php'        ?'active':'' ?>"><i class="ti ti-calendar-plus"></i> Book Appointment</a>
      <a href="view_appointments.php"  class="nav-item <?= $current==='view_appointments.php'  ?'active':'' ?>"><i class="ti ti-calendar-event"></i> My Appointments</a>
      <a href="announcements.php"      class="nav-item <?= $current==='announcements.php'      ?'active':'' ?>"><i class="ti ti-speakerphone"></i> Announcements</a>
      <a href="emergency.php"          class="nav-item emergency-link <?= $current==='emergency.php' ?'active':'' ?>"><i class="ti ti-alert-triangle"></i> Report Emergency</a>
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
