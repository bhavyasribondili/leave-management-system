 # Employee Leave Management System

## Project Overview

The Employee Leave Management System is a role-based web application developed using PHP and MySQL to streamline employee leave management processes within an organization.

The system enables employees to apply for leave, managers to review and approve/reject requests, and administrators to manage users, leave configurations, reports, and audit logs. The application is designed to provide a simple and efficient workflow for handling leave requests while maintaining transparency and accountability.

---

## Features

### Authentication & Authorization

* Secure login system
* Role-based access control
* Separate dashboards for Admin, Manager, and Employee
* Session-based authentication

### Admin Module

* Manage employees and managers
* Add, edit, activate, and deactivate users
* Configure leave types
* View all leave requests
* Access reports and audit logs
* Monitor overall system activity

### Employee Module

* Apply for leave
* View leave application history
* Track leave request status
* View available leave balance
* Dashboard with leave statistics

### Manager Module

* Review leave requests
* Approve leave applications
* Reject leave applications with remarks
* Track employee leave records

### Leave Management

* Multiple leave types support
* Leave balance tracking
* Automatic leave deduction after approval
* Prevention of overlapping leave requests
* Validation for insufficient leave balance

### Reporting & Auditing

* Leave request reports
* User activity tracking
* Audit log maintenance
* Historical leave records

---

## Technology Stack

### Frontend

* HTML5
* CSS3
* JavaScript
* AJAX

### Backend

* PHP 8.x

### Database

* MySQL / MariaDB

### Development Environment

* XAMPP
* phpMyAdmin
* Git
* GitHub

---

## Project Structure

```text
leave-management-system/

├── admin/
├── auth/
├── config/
├── employee/
├── manager/
├── assets/
├── screenshots/
├── README.md
└── leave_management.sql
```

---

## Database Setup

1. Start Apache and MySQL using XAMPP.
2. Open phpMyAdmin.
3. Create a database named:

```sql
leave_management
```

4. Import the provided:

```text
leave_management.sql
```

5. Verify that all required tables are created successfully.

---

## Application Setup

1. Clone the repository:

```bash
git clone <repository-url>
```

2. Copy the project into:

```text
xampp/htdocs/
```

3. Import the database using the provided SQL file.

4. Configure database credentials in:

```text
config/db.php
```

5. Start Apache and MySQL.

6. Open the application:

```text
http://localhost/leave-management-system
```

---

## Test Credentials

### Administrator

Email:

```text
admin@test.com
```

Password:

```text
1234
```

### Manager

Email:

```text
manager@test.com
```

Password:

```text
1234
```

### Employee

Email:

```text
emp@test.com
```

Password:

```text
1234
```

---

## Business Rules Implemented

* Employees can apply only for available leave balance.
* Leave balance is deducted only after manager approval.
* Employees cannot submit overlapping leave requests.
* Rejected leave requests require manager remarks.
* User accounts can be activated or deactivated by administrators.
* Audit logs are maintained for important leave actions.
* Access to modules is restricted based on user roles.

---

## Assumptions

* A single manager can review all employee leave requests.
* Leave balances are preconfigured by the administrator.
* Authentication is handled through session management.
* The application is intended for internal organizational use.
* Email notifications are outside the scope of this implementation.

---

## Screenshots

Application screenshots demonstrating key workflows are available in the `screenshots` folder.

---

## Future Enhancements

* Email notifications
* Leave request cancellation workflow
* Multi-level approval process
* Dashboard analytics and charts
* Employee profile management
* Responsive mobile interface

---



