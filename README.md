# Cybersecurity Incident Reporting & Management System

A web-based system for reporting, monitoring, and managing cybersecurity incidents. The system provides separate interfaces for users and administrators with role-based access control and incident status tracking.

## Features

### User
- Create an account and log in
- Report cybersecurity incidents
- View their own reported incidents
- View incident details
- Track incident status
- View incident date and report date
- Filter incidents by status: Open, In Progress, Resolved

### Administrator
- Secure admin login
- Dashboard with incident statistics
- View recent incidents
- Review incident details
- View reporter information
- Change incident status
- Add administrative remarks
- Filter incidents by status

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
│
├── admin/
│   ├── dashboard.php
│   └── view_incident.php
│
├── assets/
│   ├── css/
│   └── js/
│
├── includes/
│   └── config.php
│
├── index.php
├── login.php
├── logout.php
├── register.php
├── report_incident.php
├── user_dashboard.php
├── view_incident.php
├── .gitignore
└── README.md
```

## Database

Database name:

```text
cybersecurity_incidents
```

Main tables:

- `users`
- `incidents`

The `users` table stores user and administrator accounts.

The `incidents` table stores reported cybersecurity incidents, their severity, status, reporter information, incident date, and administrative remarks.

## Security

- Passwords are stored using PHP password hashing.
- Prepared statements are used for database queries.
- Role-based session checks protect user and administrator pages.
- Users can access only their own incident records.
- Output is escaped using `htmlspecialchars()` where appropriate.
- Database configuration is kept separately in the `includes` directory.

## Setup

1. Install XAMPP with Apache and MySQL.
2. Copy the project folder into the XAMPP `htdocs` directory.
3. Start Apache and MySQL from XAMPP.
4. Create the `cybersecurity_incidents` database in phpMyAdmin.
5. Configure the database connection in `includes/config.php`.
6. Open the project using localhost.
7. Register a normal user account.
8. Configure an administrator account in the database.
9. Test the user and administrator workflows.

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

## Status Tracking

Incidents can have the following statuses:

- Open
- In Progress
- Resolved

Both administrators and users can track the current incident status through their respective dashboards.

## Future Enhancements

- Email notifications
- Incident search and advanced filtering
- File/evidence attachments
- Audit logs
- Advanced analytics and reports
- Password reset functionality

## Author

Yash Sanjay Haldankar

## Project Type

Academic BSc IT Project