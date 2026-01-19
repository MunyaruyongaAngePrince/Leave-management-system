# API Documentation - Leave Management System

## Table of Contents
1. [Authentication API](#authentication-api)
2. [Employee API](#employee-api)
3. [Admin API](#admin-api)
4. [Utility Functions](#utility-functions)

---

## Authentication API

### Login
**Endpoint**: `POST /login.php`

```php
// Required POST parameters
$_POST['email']    // User email
$_POST['password'] // User password
$_POST['login']    // Submit button

// Response
// Success: Redirect to dashboard.php
// Error: Shows error message on login page
```

### Register
**Endpoint**: `POST /register.php`

```php
// Required POST parameters
$_POST['name']     // Full name
$_POST['email']    // Email address
$_POST['password'] // Password
$_POST['role']     // 'admin' or 'employee'

// Response
// Success: Redirect to login.php
// Error: Shows error message on register page
```

### Logout
**Endpoint**: `GET /logout.php`

```php
// No parameters required
// Clears session and redirects to login.php
```

---

## Employee API

### Apply for Leave
**Endpoint**: `POST /pages/leave-apply.php`

```php
// Required POST parameters
$_POST['start_date']   // YYYY-MM-DD format
$_POST['end_date']     // YYYY-MM-DD format
$_POST['reason']       // Leave reason (max 500 chars)
$_POST['leave_type']   // Type of leave
$_POST['apply_leave']  // Submit button

// Response
// Success: Redirect to my-leaves.php with success message
// Error: Shows error on form page
```

### View Leave History
**Endpoint**: `GET /pages/my-leaves.php`

```php
// No parameters required
// Displays all employee's leave requests with status

// Query Parameters (optional):
// ?export_pdf=1  → Export history to PDF/HTML
```

### Export Personal Leaves
**Endpoint**: `GET /pages/my-leaves.php?export_pdf=1`

```php
// Generates PDF/HTML of employee's leave records
// Can be printed or downloaded
```

---

## Admin API

### Get Employees List
**Endpoint**: `GET /admin/employees.php`

```php
// Displays all employee accounts
// Shows: Name, Email, Department, Joined Date

// Query Parameters:
// ?action=list    → Show all employees (default)
// ?action=add     → Show add employee form
// ?action=edit&id=X → Show edit form for employee X
```

### Add Employee
**Endpoint**: `POST /admin/employees.php`

```php
// Required POST parameters
$_POST['name']           // Employee name
$_POST['email']          // Email address
$_POST['phone']          // Phone number (optional)
$_POST['department']     // Department (optional)
$_POST['save_employee']  // Submit button

// Response
// Success: Redirect to employees.php with success message
// Error: Shows error on form
```

### Update Employee
**Endpoint**: `POST /admin/employees.php?action=edit&id=X`

```php
// Required POST parameters (same as Add)
$_POST['name']
$_POST['email']
$_POST['phone']
$_POST['department']
$_POST['save_employee']

// Response
// Success: Updated employee record
// Error: Shows validation error
```

### Delete Employee
**Endpoint**: `GET /admin/employees.php?delete=X`

```php
// Deletes employee with ID X
// Requires confirmation in UI

// Response
// Redirects to employees.php with success message
```

### Manage Leave Requests
**Endpoint**: `GET /admin/leaves.php`

```php
// Displays all leave requests from all employees
// Shows: Employee, Dates, Status, Actions

// Query Parameters:
// ?export_pdf=1  → Export all leaves to PDF/HTML
```

### Approve/Reject Leave
**Endpoint**: `POST /admin/leaves.php`

```php
// Required POST parameters
$_POST['leave_id']  // ID of leave request
$_POST['action']    // 'approved' or 'rejected'
$_POST['comment']   // Admin comment (optional)

// Response
// Success: Leave status updated, page refreshes
// Error: Shows error message
```

### Generate Reports
**Endpoint**: `GET /admin/reports.php`

```php
// Displays leave statistics and data

// Query Parameters:
// ?status=all      → All statuses (default)
// ?status=pending  → Only pending requests
// ?status=approved → Only approved leaves
// ?status=rejected → Only rejected leaves
// ?month=MM        → Filter by month (01-12)
// ?year=YYYY       → Filter by year
// ?export_pdf=1    → Export report to PDF
```

### Export All Leaves
**Endpoint**: `GET /admin/reports.php?export_pdf=1`

```php
// Exports filtered leave data to PDF/HTML
// Can use with other query parameters:
// ?status=approved&month=01&year=2024&export_pdf=1
```

---

## Utility Functions

### Authentication Checks

```php
// Check if user is logged in
checkLogin();      // Redirect if not logged in
isLoggedIn();       // Return boolean

// Check if user is admin
checkAdmin();       // Redirect if not admin
isAdmin();          // Return boolean

// Check if user is employee
checkEmployee();    // Redirect if not employee
isEmployee();       // Return boolean

// Get current user data
getCurrentUser();   // Return user array
getUserRole();      // Return 'admin' or 'employee'
```

### Database Operations

```php
// Execute query
executeQuery($sql);                    // Return query result

// Get records
getRecord($table, $condition);         // Return single record
getRecords($table, $condition, $orderBy, $limit); // Return array of records

// Insert record
insertRecord($table, $data);           // Return inserted ID

// Update record
updateRecord($table, $data, $condition); // Return true/false

// Delete record
deleteRecord($table, $condition);      // Return true/false
```

### Input Validation

```php
// Sanitize input
sanitize($input);              // Remove HTML/SQL injection

// Validate email
validateEmail($email);         // Return boolean

// Validate password strength
validatePassword($password);   // Return boolean

// Check if email exists
emailExists($email, $excludeId); // Return boolean

// Validate date range
validateDateRange($start, $end); // Return boolean
```

### Leave Management

```php
// Calculate days between dates
calculateDays($startDate, $endDate);   // Return number of days

// Get remaining leave days for employee
getRemainingLeaveDays($userId, $year); // Return remaining days

// Get leave status badge class
getStatusBadgeClass($status);          // Return CSS class

// Get status icon
getStatusIcon($status);                // Return emoji icon
```

### Permission Checks

```php
// Check if can view leave
canViewLeave($leaveId);       // Return boolean

// Check if can edit leave
canEditLeave($leaveId);       // Return boolean

// Check if can delete leave
canDeleteLeave($leaveId);     // Return boolean

// Check if can manage leaves
canManageLeaves();            // Return boolean

// Check if can view employees
canViewEmployees();           // Return boolean

// Check if can edit employees
canEditEmployees();           // Return boolean

// Check if can delete employees
canDeleteEmployees();         // Return boolean
```

### Utility Functions

```php
// Format date for display
formatDate($date, $format);    // Return formatted date

// Log activity
logActivity($userId, $action, $description); // Log user action

// Set success message
setSuccess($message);          // Store in session

// Set error message
setError($message);            // Store in session

// Get and clear success message
getSuccess();                  // Return and clear

// Get and clear error message
getError();                    // Return and clear

// Display alert
displayAlert();                // Echo success/error alert
```

---

## Error Handling

### Common HTTP Status Codes

```php
200 OK           // Request successful
302 Found        // Redirect (login, logout)
400 Bad Request  // Invalid input
403 Forbidden    // Access denied (permission)
404 Not Found    // Page not found
500 Server Error // Database/server error
```

### Error Messages

```php
"All fields are required"
"Invalid email or password"
"Email already exists"
"End date must be after start date"
"You only have X days remaining"
"Access Denied: Admin access required"
"Leave request not found"
```

---

## Database Schema Reference

### users table
```
id              INT PRIMARY KEY
name            VARCHAR(100)
email           VARCHAR(100) UNIQUE
password        VARCHAR(255)
phone           VARCHAR(20)
department      VARCHAR(100)
role            ENUM('admin', 'employee')
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### leaves table
```
id              INT PRIMARY KEY
user_id         INT (FOREIGN KEY)
start_date      DATE
end_date        DATE
reason          TEXT
leave_type      VARCHAR(50)
status          ENUM('pending', 'approved', 'rejected')
admin_comment   TEXT
updated_by      INT (FOREIGN KEY)
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### activity_logs table
```
id              INT PRIMARY KEY
user_id         INT (FOREIGN KEY)
action          VARCHAR(100)
description     TEXT
ip_address      VARCHAR(45)
user_agent      TEXT
created_at      TIMESTAMP
```

---

## Response Format

### Success Response
```php
// Redirect to success page with message in session
$_SESSION['success'] = "Leave request submitted successfully";
header("Location: my-leaves.php");
exit;

// Display alert
displayAlert(); // Shows green success box
```

### Error Response
```php
// Set error in session
$_SESSION['error'] = "End date must be after start date";

// Display alert
displayAlert(); // Shows red error box
```

---

## Code Examples

### Example: Apply for Leave (Employee)
```php
<?php
include "includes/config.php";
checkEmployee();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_leave'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $reason = sanitize($_POST['reason']);
    
    if (!validateDateRange($startDate, $endDate)) {
        setError('Invalid date range');
    } else {
        $days = calculateDays($startDate, $endDate);
        $remaining = getRemainingLeaveDays(getCurrentUser()['id']);
        
        if ($days > $remaining) {
            setError("Only $remaining days remaining");
        } else {
            insertRecord('leaves', [
                'user_id' => getCurrentUser()['id'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'reason' => $reason,
                'status' => 'pending'
            ]);
            setSuccess('Request submitted');
            header("Location: my-leaves.php");
        }
    }
}
?>
```

### Example: Approve Leave (Admin)
```php
<?php
include "includes/config.php";
checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $leaveId = (int)$_POST['leave_id'];
    $action = $_POST['action'];
    
    if (in_array($action, ['approved', 'rejected'])) {
        updateRecord('leaves', [
            'status' => $action,
            'admin_comment' => sanitize($_POST['comment']),
            'updated_by' => getCurrentUser()['id']
        ], "id = $leaveId");
        
        setSuccess("Leave " . $action);
    }
}
?>
```

---

## Security Notes

✅ All inputs are sanitized
✅ Passwords are hashed with bcrypt
✅ SQL injection is prevented
✅ Session-based authentication
✅ Role-based access control
✅ Activity logging enabled

---

**Version**: 2.0
**Last Updated**: January 2024
