============================================================
  BARANGAY SERVICE MANAGEMENT SYSTEM
  Installation & Setup Guide
============================================================

REQUIREMENTS
------------
- XAMPP (Apache + MySQL running)
- PHP 8.0 or higher
- Web browser (Chrome, Firefox, Edge)


STEP 1 — COPY PROJECT FOLDER
------------------------------
Copy the entire "barangay_system" folder to:

  Windows:  C:\xampp\htdocs\barangay_system\
  Android:  Internal Storage > ksweb > www > barangay_system\


STEP 2 — CREATE DATABASE
--------------------------
1. Open: http://localhost/phpmyadmin
2. Click "New" in the left sidebar
3. Enter name: barangay_system
4. Click "Create"


STEP 3 — IMPORT YOUR SQL FILE
-------------------------------
Option A — Import your existing SQL:
  1. Select barangay_system database
  2. Click "Import" tab
  3. Choose your barangay_system.sql file
  4. Click "Go"

Option B — Run the setup script:
  1. Select barangay_system database
  2. Click "SQL" tab
  3. Paste contents of barangay_system_setup.sql
  4. Click "Go"


STEP 4 — OPEN THE SYSTEM
--------------------------
Go to: http://localhost/barangay_system/


ADMIN LOGIN
-----------
  Username : admin
  Password : password

  ⚠ Change this password after your first login!


RESET ADMIN PASSWORD
---------------------
If you forget your admin password, run this in phpMyAdmin SQL tab:

  UPDATE users
  SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
  WHERE username = 'admin';

Then login with password: password


FOLDER STRUCTURE
----------------
barangay_system/
├── index.php               (redirects to login)
├── barangay_system_setup.sql
├── README.txt
├── assets/
│   └── style.css
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── config/
│   └── database.php
├── includes/
│   ├── user_nav.php
│   ├── admin_nav.php
│   └── layout_bottom.php
├── user/
│   ├── dashboard.php
│   ├── complaint.php
│   ├── view_complaints.php
│   ├── appointment.php
│   ├── view_appointments.php
│   ├── emergency.php
│   └── announcements.php
└── admin/
    ├── dashboard.php
    ├── manage_complaints.php
    ├── manage_appointments.php
    ├── manage_emergencies.php
    └── announcements.php


SYSTEM FEATURES
---------------
RESIDENTS (user role):
  - Register & Login
  - File complaints
  - Book appointments
  - Report emergencies
  - Read announcements

ADMIN:
  - View all complaints → update status (Pending / Resolved)
  - View all appointments → approve or reject
  - View all emergencies → update response status
  - Post & delete announcements
  - Dashboard with live statistics

============================================================
