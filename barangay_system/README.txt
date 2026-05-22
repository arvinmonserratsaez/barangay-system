============================================================
BARANGAY SERVICE MANAGEMENT SYSTEM
Installation & Setup Guide
============================================================

REQUIREMENTS
------------
- XAMPP with Apache and MySQL enabled
- PHP 8.0 or higher
- Web browser (Chrome, Firefox, Edge)

PROJECT SETUP
-------------
1. Copy the project folder to:
   C:\xampp\htdocs\barangay_system\

2. Start XAMPP and enable Apache and MySQL.

3. Open phpMyAdmin:
   http://localhost/phpmyadmin

4. Create a new database named:
   barangay_system

5. Import the SQL schema:
   - Select the `barangay_system` database
   - Click the `Import` tab
   - Choose `barangay_system_setup.sql`
   - Click `Go`

OR use the SQL tab to run the contents of `barangay_system_setup.sql`.

6. Open the application in your browser:
   http://localhost/barangay_system/

DATABASE CONNECTION
-------------------
The app uses the following default database settings in `config/database.php`:
- Host: localhost
- User: root
- Password: (empty)
- Database: barangay_system

If your MySQL user or password is different, update `config/database.php`.

DEFAULT ADMIN CREDENTIALS
-------------------------
Username: admin
Password: password

> After first login, change the admin password immediately.

RESET ADMIN PASSWORD
--------------------
If you forget the admin password, run this SQL in phpMyAdmin:

UPDATE users
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE username = 'admin';

Then log in with password: `password`

PROJECT STRUCTURE
-----------------
barangay_system/
├── index.php
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
│   ├── admin_nav.php
│   ├── layout_bottom.php
│   └── user_nav.php
├── user/
│   ├── announcements.php
│   ├── appointment.php
│   ├── complaint.php
│   ├── dashboard.php
│   ├── emergency.php
│   ├── view_appointments.php
│   └── view_complaints.php
└── admin/
    ├── announcements.php
    ├── dashboard.php
    ├── manage_appointments.php
    ├── manage_complaints.php
    └── manage_emergencies.php

SYSTEM FEATURES
---------------
Residents (user role):
- Register and log in
- File complaints
- Book appointments
- Report emergencies
- View announcements

Admin:
- View and update complaint status
- Approve or reject appointments
- Update emergency response status
- Post and delete announcements
- View dashboard statistics

Troubleshooting
---------------
- If the site shows a database error, confirm MySQL is running and `barangay_system` exists.
- If login fails, verify the `users` table has the admin account or reset the password using the SQL above.
- If assets do not load, confirm the project folder is in `htdocs` and the URL is correct.

============================================================
