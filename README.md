# Cybersecurity Incident Reporting & Management System

A web-based system for reporting, monitoring, and managing cybersecurity incidents. The system provides separate interfaces for users and administrators, with role-based access control and incident status tracking.

## Features

### User
- Create an account and log in securely
- Report cybersecurity incidents
- View only their own reported incidents
- View incident details
- Track incident status: Open, In Progress, Resolved
- View incident date and report date

### Administrator
- Secure admin login
- Dashboard with total, open, in-progress, and resolved incident counts
- View recent incidents
- Open and review incident details
- View reporter information
- Change incident status
- Add administrative remarks

## Technology Stack

- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- PHP
- MySQL
- XAMPP
- Git & GitHub

## Project Structure

```text
cybersecurity_incident_system/
├── admin_dashboard.php
├── admin_view_incident.php
├── db.php
├── index.php
├── login.php
├── logout.php
├── register.php
├── report_incident.php
├── user_dashboard.php
└── view_incident.php
```

## Security

- Passwords are stored using PHP password hashing.
- Prepared statements are used for database queries.
- User and admin pages use role-based session checks.
- Users can access only their own incident records.
- Output is escaped using `htmlspecialchars()` where appropriate.

## Setup

1. Install XAMPP with Apache and MySQL.
2. Copy the project folder into the XAMPP `htdocs` directory.
3. Start Apache and MySQL from XAMPP.
4. Create the project database in phpMyAdmin.
5. Configure the database connection in `db.php`.
6. Open the project through localhost.
7. Register a normal user account.
8. Create/configure an admin account in the database.
9. Test both user and admin workflows.

## Main Workflow

```text
User Registration
       ↓
User Login
       ↓
Report Incident
       ↓
User Tracks Incident
       ↓
Admin Reviews Incident
       ↓
Admin Updates Status / Remarks
       ↓
User Sees Updated Status
```

## Future Enhancements

- Email notifications
- Incident search and filtering
- File/evidence attachments
- Audit logs
- Advanced analytics and reports
- Password reset functionality

## Author

Yash Sanjay Haldankar

## Project Type

Academic BSc IT Project
