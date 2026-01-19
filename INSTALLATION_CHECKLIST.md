✅ INSTALLATION CHECKLIST - Leave Management System

═══════════════════════════════════════════════════════════════════════════

📋 PRE-INSTALLATION
─────────────────────────────────────────────────────────────────────────

□ PHP 7.4 or higher installed
□ MySQL server running
□ Apache/XAMPP running
□ Project files in: C:\xampp\htdocs\leave\
□ PhpMyAdmin accessible (http://localhost/phpmyadmin)

═══════════════════════════════════════════════════════════════════════════

📦 DATABASE SETUP
─────────────────────────────────────────────────────────────────────────

□ Open PhpMyAdmin
□ Create new database: "leave_system"
□ Select "leave_system" database
□ Go to "Import" tab
□ Select "database-setup.sql"
□ Click "Import" button
□ Verify tables created:
  □ users
  □ leaves
  □ activity_logs
  □ leave_types
□ Check sample data imported:
  □ 1 Admin account
  □ 2 Employee accounts
  □ 5 Leave types

═══════════════════════════════════════════════════════════════════════════

📁 FILE STRUCTURE
─────────────────────────────────────────────────────────────────────────

Verify directory structure:

leave/
│
├── assets/
│   ├── css/
│   │   └── ✅ main.css (850+ lines)
│   └── js/
│       └── (optional JavaScript)
│
├── includes/
│   ├── ✅ config.php (700+ lines)
│   ├── ✅ header.php (Navigation)
│   └── ✅ pdf-helper.php (PDF export)
│
├── admin/
│   ├── ✅ employees.php
│   ├── ✅ leaves.php
│   └── ✅ reports.php
│
├── pages/
│   ├── ✅ leave-apply.php
│   └── ✅ my-leaves.php
│
├── vendor/
│   └── (Optional - for PDF)
│
├── ✅ login.php (Modern redesign)
├── ✅ register.php
├── ✅ dashboard.php
├── ✅ logout.php
├── ✅ config.php (Legacy)
├── ✅ database-setup.sql
├── ✅ composer.json
├── ✅ README.md
├── ✅ QUICKSTART.md
├── ✅ API.md
└── ✅ PROJECT_SUMMARY.md

═══════════════════════════════════════════════════════════════════════════

⚙️ CONFIGURATION
─────────────────────────────────────────────────────────────────────────

Database Configuration:
□ Edit: includes/config.php
□ Line 13-17:
  - DB_HOST: "localhost" ✅
  - DB_USER: "root" ✅
  - DB_PASS: "" (empty for XAMPP) ✅
  - DB_NAME: "leave_system" ✅
  - BASE_URL: "http://localhost/leave/" ✅

═══════════════════════════════════════════════════════════════════════════

✨ FEATURES VERIFICATION
─────────────────────────────────────────────────────────────────────────

Backend Features:
□ Authentication system ✅
□ Role-based access ✅
□ Database connection ✅
□ Input validation ✅
□ Permission system ✅
□ Activity logging ✅
□ PDF export ✅

Frontend Features:
□ Modern CSS framework ✅
□ Responsive design ✅
□ Mobile menu ✅
□ Form validation ✅
□ Alert system ✅
□ Modal dialogs ✅
□ Professional UI ✅

═══════════════════════════════════════════════════════════════════════════

🧪 TESTING - PART 1 (Setup)
─────────────────────────────────────────────────────────────────────────

□ Open browser: http://localhost/leave/
□ See login page ✅
□ Check styling loaded (blue theme) ✅
□ Check mobile responsiveness ✅
□ Try smaller screen size ✅

═══════════════════════════════════════════════════════════════════════════

🧪 TESTING - PART 2 (Admin Account)
─────────────────────────────────────────────────────────────────────────

Login as Admin:
□ Email: admin@leavesystem.com
□ Password: admin123
□ Click "Sign In"
□ Should redirect to dashboard

Dashboard (Admin):
□ See statistics (employees, leaves, etc.)
□ See quick actions cards
□ See PDF export option

Navigation Menu:
□ Click "👥 Employees" → Go to employee list
□ Click "✅ Manage Leaves" → Go to leave management
□ Click "📊 Reports" → Go to reports

Employee Management:
□ See John Doe and Jane Smith in list
□ Click "✏️ Edit" on an employee
□ See edit form with pre-filled data
□ Click "Cancel" to go back
□ Try "🗑️ Delete" (confirm dialog appears)

Leave Management:
□ See leave requests (if any)
□ See "Review" button for pending leaves
□ Click "Review" → Modal appears
□ Choose Approve/Reject
□ Add comment (optional)
□ Click "Submit Decision"

Reports:
□ See statistics cards
□ Use filters (Status, Month, Year)
□ Click "🔍 Filter"
□ See filtered results
□ Click "📄 Export to PDF" → Download/Print

Logout:
□ Click "🚪 Logout" in navbar
□ Should return to login page
□ Session cleared ✅

═══════════════════════════════════════════════════════════════════════════

🧪 TESTING - PART 3 (Employee Account)
─────────────────────────────────────────────────────────────────────────

Login as Employee:
□ Email: john@example.com
□ Password: employee123
□ Click "Sign In"
□ Should redirect to dashboard

Dashboard (Employee):
□ See statistics (total leaves, remaining days, etc.)
□ See "Apply for Leave" and "My Leave Requests" cards

Apply for Leave:
□ Click "📝 Apply Leave" or use menu
□ See leave balance info
□ Fill in leave type (select one)
□ Select start date
□ Select end date (after start)
□ Enter reason
□ Click "Submit Request"
□ Should show success message
□ Should redirect to "My Leaves"

My Leaves:
□ See the leave request you just created
□ Status should be "⏳ Pending"
□ See "👁️ View" and "✏️ Edit" buttons
□ Try "📄 Export" → Download/Print

Try Invalid Input:
□ Apply with end date before start date
□ Should show error: "End date must be after..."
□ Try empty fields → "All fields are required"
□ Try too many days → "You only have X days remaining"

Navigation:
□ Test hamburger menu on mobile size
□ All menu items visible
□ Click "🚪 Logout"
□ Return to login page

═══════════════════════════════════════════════════════════════════════════

🧪 TESTING - PART 4 (Mobile Responsiveness)
─────────────────────────────────────────────────────────────────────────

Browser Developer Tools (F12):
□ Open DevTools
□ Click device toolbar
□ Select "iPhone X" (375px)
□ Refresh page

Mobile Testing:
□ Layout adjusts to mobile width ✅
□ Hamburger menu appears ✅
□ Navbar items hidden ✅
□ Click hamburger → menu opens ✅
□ Tables scroll horizontally ✅
□ Buttons properly sized ✅
□ Forms full width ✅
□ Cards stack vertically ✅

Tablet Testing (768px):
□ Select "iPad" in device toolbar
□ Layout adjusts to tablet ✅
□ Navbar expands ✅
□ 2-column layouts appear ✅

Desktop Testing (1024px+):
□ Select "Desktop" view
□ Full layout with all features ✅
□ 3-4 column layouts ✅

═══════════════════════════════════════════════════════════════════════════

🔐 TESTING - PART 5 (Security)
─────────────────────────────────────────────────────────────────────────

Authentication:
□ Try accessing /admin/employees.php without login
  → Should redirect to login ✅
□ Try accessing /pages/leave-apply.php without login
  → Should redirect to login ✅
□ Try accessing /dashboard.php without login
  → Should redirect to login ✅

Authorization:
□ Login as employee
□ Try accessing /admin/employees.php
  → Should show "Access Denied" ✅
□ Try accessing /admin/leaves.php
  → Should show "Access Denied" ✅

Input Validation:
□ Register with invalid email → Error ✅
□ Register with existing email → Error ✅
□ Login with wrong password → Error ✅
□ Apply leave with invalid dates → Error ✅

Session Security:
□ Login, then click "Back" button
  → Should not go back ✅
□ Logout, then click browser back
  → Should not access previous page ✅

═══════════════════════════════════════════════════════════════════════════

📊 TESTING - PART 6 (Features)
─────────────────────────────────────────────────────────────────────────

Leave Balance:
□ Employee shows 20 days total ✅
□ Shows correct remaining days ✅
□ Deducts correctly when approved ✅
□ Updates after admin approves ✅

PDF Export:
□ Click "📄 Export" on any page
□ PDF/HTML opens in new window ✅
□ Formatting looks professional ✅
□ Can print to PDF ✅
□ Contains correct data ✅

Activity Logging:
□ Check database activity_logs table
□ Login recorded ✅
□ Leave applied recorded ✅
□ Leave approved recorded ✅
□ Logout recorded ✅

Permissions:
□ Admin can delete employees ✅
□ Employee cannot delete employees ✅
□ Admin can approve leaves ✅
□ Employee cannot approve leaves ✅
□ Employee can only see own leaves ✅

═══════════════════════════════════════════════════════════════════════════

🎨 TESTING - PART 7 (UI/UX)
─────────────────────────────────────────────────────────────────────────

Design:
□ Professional color scheme ✅
□ Consistent styling throughout ✅
□ Smooth animations/transitions ✅
□ No broken images/icons ✅
□ Text readable and clear ✅

Navigation:
□ Clear menu structure ✅
□ Active page highlighted ✅
□ Breadcrumbs/back buttons work ✅
□ Logical flow between pages ✅

Forms:
□ Form fields properly spaced ✅
□ Labels clear and descriptive ✅
□ Placeholder text helpful ✅
□ Error messages clear ✅
□ Success messages visible ✅

Buttons:
□ All buttons clickable ✅
□ Hover effects work ✅
□ Icons meaningful ✅
□ Disabled state visible ✅

Tables:
□ Headers clear ✅
□ Data properly formatted ✅
□ Sorting/filtering work (if available) ✅
□ Status badges color-coded ✅

═══════════════════════════════════════════════════════════════════════════

✅ POST-INSTALLATION
─────────────────────────────────────────────────────────────────────────

Documentation:
□ README.md reviewed
□ QUICKSTART.md reviewed
□ API.md reviewed
□ PROJECT_SUMMARY.md reviewed

Customization (Optional):
□ Change company name
□ Adjust leave days allowance
□ Modify color scheme
□ Add custom leave types
□ Configure email notifications (optional)

Deployment (Optional):
□ Change BASE_URL for production
□ Update database credentials
□ Set proper file permissions
□ Configure backup routine
□ Set up SSL/HTTPS

═══════════════════════════════════════════════════════════════════════════

🎉 FINAL CHECKLIST
─────────────────────────────────────────────────────────────────────────

✅ Database setup complete
✅ All files in place
✅ Configuration verified
✅ Admin account working
✅ Employee account working
✅ Responsive design working
✅ Security features active
✅ PDF export functional
✅ Permissions working
✅ UI/UX professional
✅ Documentation available
✅ Ready for use!

═══════════════════════════════════════════════════════════════════════════

🚀 NEXT STEPS
─────────────────────────────────────────────────────────────────────────

1. Create real admin account
   - Register new user
   - Update role to 'admin' in database

2. Import actual employee list
   - Add employees through admin panel
   - OR import via database directly

3. Configure company settings
   - Change company name
   - Set leave year
   - Adjust leave allowances

4. Customize appearance (Optional)
   - Update colors in main.css
   - Change logo/branding
   - Modify form fields

5. Deploy to production
   - Update BASE_URL
   - Configure SSL/HTTPS
   - Setup database backups

═══════════════════════════════════════════════════════════════════════════

📞 SUPPORT & TROUBLESHOOTING
─────────────────────────────────────────────────────────────────────────

Common Issues:

"Database Connection Error"
→ Verify MySQL running
→ Check credentials in includes/config.php
→ Ensure database "leave_system" exists

"Login Failed"
→ Verify database imported
→ Clear browser cache
→ Check user exists in database

"Permission Denied"
→ Verify you're logged in
→ Check your account role
→ Check directory permissions

"Styling Not Loading"
→ Check includes/css/main.css exists
→ Verify file path in HTML
→ Clear browser cache

═══════════════════════════════════════════════════════════════════════════

✨ CONGRATULATIONS!
─────────────────────────────────────────────────────────────────────────

Your Leave Management System is now:

✅ Installed
✅ Configured
✅ Tested
✅ Ready to Use

Version: 2.0 (Professional Edition)
Date: January 18, 2026
Status: Production Ready

═══════════════════════════════════════════════════════════════════════════

Start using: http://localhost/leave/

═══════════════════════════════════════════════════════════════════════════
