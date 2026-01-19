# 🚀 Quick Start Guide - Leave Management System

## Installation in 5 Steps

### Step 1: Database Setup
1. Open **phpMyAdmin** (http://localhost/phpmyadmin)
2. Create new database: `leave_system`
3. Go to **Import** tab
4. Upload `database-setup.sql`
5. Click **Import**

### Step 2: Verify File Structure
Project files are organized:
```
leave/
├── assets/css/main.css          ✅ Main stylesheet
├── includes/                     ✅ Core functions
├── admin/                        ✅ Admin pages
├── pages/                        ✅ Employee pages
├── login.php                     ✅ Login page
├── register.php                  ✅ Registration
├── dashboard.php                 ✅ Main dashboard
└── logout.php                    ✅ Logout
```

### Step 3: Access the Application
- **URL**: http://localhost/leave/
- **You'll see**: Login page

### Step 4: Login with Test Accounts

**Admin Account:**
- Email: `admin@leavesystem.com`
- Password: `admin123`

**Employee Account:**
- Email: `john@example.com`
- Password: `employee123`

### Step 5: Start Using!

**As Admin:**
1. ✅ Manage employees (Add, Edit, Delete)
2. ✅ Review leave requests (Approve/Reject)
3. ✅ Generate reports and export to PDF
4. ✅ View statistics and activity

**As Employee:**
1. ✅ Apply for leave
2. ✅ View leave history
3. ✅ Check remaining days
4. ✅ Export leave records

---

## 🎯 Key Features

### Modern Design ✨
- Beautiful gradient backgrounds
- Responsive layout (Mobile, Tablet, Desktop)
- Smooth animations and transitions
- Professional color scheme
- Intuitive navigation

### Smart Functionality 🧠
- **Leave Balance Management**: Automatic calculation
- **Permission System**: Role-based access
- **PDF Export**: Download reports
- **Activity Logging**: Track all actions
- **Form Validation**: Client & server-side

### Security 🔐
- Password hashing (bcrypt)
- SQL injection prevention
- Input sanitization
- Session management
- Permission checks

---

## 📱 Responsive Design

### Mobile (320px+)
- Full-width layout
- Hamburger menu
- Touch-friendly buttons
- Optimized forms

### Tablet (768px+)
- 2-column grid
- Full navigation
- Flexible tables

### Desktop (1024px+)
- 4-column grid
- Sidebar ready
- Advanced features

---

## 🔧 Common Tasks

### Add New Employee
1. Login as Admin
2. Go to **👥 Employees**
3. Click **➕ Add Employee**
4. Fill form and save

### Apply for Leave
1. Login as Employee
2. Go to **📝 Apply Leave**
3. Select dates and reason
4. Click **Submit Request**

### Approve Leave
1. Login as Admin
2. Go to **✅ Manage Leaves**
3. Click **📝 Review**
4. Choose Approve/Reject
5. Add comment (optional)
6. Submit decision

### Export Report
1. Login as Admin
2. Go to **📊 Reports**
3. Filter if needed
4. Click **📄 Export to PDF**
5. Your report opens in new window
6. Use browser Print → Save as PDF

---

## 🆘 Troubleshooting

### "Database Connection Error"
❌ **Problem**: Can't connect to database
✅ **Solution**: 
- Check MySQL is running
- Verify database `leave_system` exists
- Check credentials in `includes/config.php`

### "Login Failed"
❌ **Problem**: Can't login with test accounts
✅ **Solution**:
- Ensure SQL was imported correctly
- Clear browser cache (Ctrl+Shift+Delete)
- Check if user exists in database

### "Permission Denied"
❌ **Problem**: Accessing pages you shouldn't
✅ **Solution**:
- Make sure you're logged in
- Check your account role
- Clear session (logout and login again)

### "Tables Not Showing Data"
❌ **Problem**: Empty tables
✅ **Solution**:
- Verify database tables were created
- Re-import `database-setup.sql`
- Add sample data through UI

---

## 📊 File Structure Explained

### /assets/css/main.css
Modern CSS framework with:
- CSS variables for colors
- Responsive grid system
- Button styles
- Form elements
- Card components

### /includes/config.php
Core application file with:
- Database connection
- Authentication functions
- Database helpers
- Permission checks
- Validation functions

### /includes/header.php
Navigation component with:
- Responsive navbar
- User menu
- Role-based menu items
- Mobile hamburger menu

### /admin/employees.php
Admin employee management:
- List all employees
- Add new employee
- Edit employee
- Delete employee

### /admin/leaves.php
Manage leave requests:
- View all requests
- Approve/reject leaves
- Add comments
- Filter by status

### /admin/reports.php
Generate reports:
- Statistics dashboard
- Filter reports
- Export to PDF
- Employee data

### /pages/leave-apply.php
Employee leave application:
- Select dates
- Choose leave type
- Add reason
- Submit request

### /pages/my-leaves.php
Employee leave history:
- View all requests
- Check status
- Filter by status
- Export personal record

---

## 🎨 Customization Tips

### Change Colors
Edit `/assets/css/main.css` line 8-30:
```css
:root {
    --primary: #2563eb;        /* Change primary color */
    --success: #10b981;        /* Change success color */
    --danger: #ef4444;         /* Change danger color */
    /* ... etc */
}
```

### Modify Leave Days
Edit `/includes/config.php` in `getRemainingLeaveDays()` function:
```php
$totalDays = 20; // Change this value
```

### Update Company Info
Edit `/includes/header.php`:
```php
Leave Management System  ← Change this
```

---

## 🚀 Next Steps

1. ✅ Test the system with provided accounts
2. ✅ Add real employees
3. ✅ Process leave requests
4. ✅ Generate reports
5. ✅ Customize to your needs

---

## 📞 Support

If you encounter issues:
1. Check error messages carefully
2. Review the README.md file
3. Verify database and files
4. Check browser console (F12)
5. Review PHP error logs

---

**Congratulations! 🎉 Your Leave Management System is ready!**

Visit: **http://localhost/leave/**
