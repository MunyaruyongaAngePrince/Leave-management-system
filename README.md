# Leave Management System - Modern Professional Edition

A modern, responsive, and feature-rich leave management system built with pure PHP, MySQL, and modern CSS.

## 🎯 Features

### Modern Design
- ✅ Professional, mobile-first design
- ✅ Fully responsive layout (Mobile, Tablet, Desktop)
- ✅ Modern gradient UI with smooth animations
- ✅ Intuitive navigation and user experience

### Core Features
- ✅ Secure user authentication (Login/Register)
- ✅ Role-based access control (Admin/Employee)
- ✅ Leave application with validation
- ✅ Leave approval workflow
- ✅ Leave balance management
- ✅ Employee management with CRUD operations
- ✅ PDF export functionality
- ✅ Activity logging
- ✅ Professional reports and statistics

### Technical Features
- ✅ Database-driven architecture
- ✅ Secure password hashing (bcrypt)
- ✅ Input validation and sanitization
- ✅ SQL injection prevention
- ✅ Session management
- ✅ Permission-based access control
- ✅ RESTful URL structure
- ✅ Organized file structure

## 📁 Project Structure

```
leave/
├── assets/
│   ├── css/
│   │   └── main.css           # Modern CSS framework
│   └── js/
│       └── main.js            # JavaScript utilities
├── includes/
│   ├── config.php             # Database config & core functions
│   ├── header.php             # Navigation component
│   └── pdf-helper.php         # PDF export functionality
├── admin/
│   ├── employees.php          # Employee management
│   ├── leaves.php             # Leave management
│   └── reports.php            # Reports & statistics
├── pages/
│   ├── leave-apply.php        # Apply for leave
│   └── my-leaves.php          # View leave history
├── vendor/                    # Composer packages
├── login.php                  # Login page
├── register.php               # Registration page
├── dashboard.php              # Main dashboard
├── logout.php                 # Logout
├── config.php                 # Old config (legacy)
└── composer.json              # PHP dependencies
```

## 🚀 Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- Composer (for PDF export)

### Steps

1. **Clone/Extract the project**
   ```bash
   cd C:\xampp\htdocs\
   # Extract project here
   ```

2. **Create Database**
   ```sql
   CREATE DATABASE leave_system;
   USE leave_system;
   
   -- Create tables (run the SQL from database-setup.sql)
   ```

3. **Install Dependencies** (Optional - for PDF export)
   ```bash
   cd C:\xampp\htdocs\leave
   composer install
   ```

4. **Update config.php**
   - Edit `includes/config.php`
   - Set correct database credentials

5. **Access the Application**
   - Open browser: `http://localhost/leave/`

## 📊 Database Schema

### users table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    department VARCHAR(100),
    role ENUM('admin', 'employee') DEFAULT 'employee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### leaves table
```sql
CREATE TABLE leaves (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    leave_type VARCHAR(50) DEFAULT 'general',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_comment TEXT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### activity_logs table
```sql
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## 👥 User Roles

### Admin
- Manage all employee accounts
- Approve/reject leave requests
- View employee list
- Generate reports
- Export data to PDF
- View system activity logs

### Employee
- Submit leave requests
- View leave history and status
- Check remaining leave balance
- Export personal leave history
- Update profile information

## 🔐 Security Features

- ✅ Password hashing with bcrypt
- ✅ Input validation and sanitization
- ✅ SQL injection prevention
- ✅ CSRF protection ready
- ✅ Session timeout
- ✅ Role-based access control
- ✅ Activity logging

## 🎨 UI/UX Highlights

### Color Scheme
- Primary Blue: #2563eb
- Success Green: #10b981
- Danger Red: #ef4444
- Dark Gray: #1e293b
- Light Gray: #f8fafc

### Components
- Modern navbar with user profile
- Responsive cards and grids
- Professional tables
- Alert notifications
- Modal dialogs
- Form validation
- Badge system
- Pagination

### Responsive Design
- Mobile: 320px+
- Tablet: 768px+
- Desktop: 1024px+

## 📄 PDF Export

Features include:
- Export individual leave requests
- Export all leave data
- Export employee list
- Print-friendly styling
- Professional formatting

Install DOMPDF for server-side PDF generation:
```bash
composer require dompdf/dompdf
```

## 🔄 Workflow

1. **Employee** submits leave request
2. **Request** marked as "Pending"
3. **Admin** reviews request
4. **Admin** approves or rejects
5. **Employee** receives notification
6. **Leave balance** updated for approved leaves

## 📊 Key Functions

### Authentication
- `checkLogin()` - Verify user is logged in
- `checkAdmin()` - Verify admin access
- `checkEmployee()` - Verify employee access

### Database
- `getRecord()` - Get single record
- `getRecords()` - Get multiple records
- `insertRecord()` - Insert new record
- `updateRecord()` - Update record
- `deleteRecord()` - Delete record

### Utilities
- `sanitize()` - Clean input
- `validateEmail()` - Validate email
- `calculateDays()` - Calculate leave days
- `getRemainingLeaveDays()` - Get available leave
- `formatDate()` - Format date display

### Permissions
- `canViewLeave()` - Check view permission
- `canEditLeave()` - Check edit permission
- `canManageLeaves()` - Check management permission

## 🌐 API Endpoints

### Authentication
- `POST /login.php` - User login
- `POST /register.php` - User registration
- `GET /logout.php` - User logout

### Employee
- `GET /dashboard.php` - Main dashboard
- `GET /pages/leave-apply.php` - Apply for leave
- `POST /pages/leave-apply.php` - Submit request
- `GET /pages/my-leaves.php` - View leave history

### Admin
- `GET /admin/employees.php` - Manage employees
- `GET /admin/leaves.php` - Manage leave requests
- `GET /admin/reports.php` - View reports

## 🐛 Troubleshooting

### Database Connection Error
- Verify credentials in `includes/config.php`
- Ensure MySQL server is running
- Check database exists

### File Upload Issues
- Check folder permissions
- Verify write permissions

### PDF Export Not Working
- Install DOMPDF: `composer install`
- Check vendor folder exists

## 📝 License

This project is open-source and available for educational and commercial use.

## 👨‍💻 Support

For issues and support:
1. Check the error messages carefully
2. Verify database setup
3. Review permissions and configurations
4. Check browser console for JavaScript errors

---

**Version:** 2.0 (Modern Professional Edition)
**Last Updated:** January 2024
