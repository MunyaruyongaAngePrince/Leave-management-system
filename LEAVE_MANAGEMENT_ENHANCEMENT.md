# Leave Management System - Admin Leave Request Management Enhancement

## Summary of Changes

This update adds comprehensive functionality to manage leave requests in the admin dashboard with the ability to view detailed information, accept, or deny requests with comments, and track request status.

---

## Files Modified/Created

### 1. **admin/leaves.php** - Main Management Page
**Location:** `c:\xampp1\htdocs\leave\admin\leaves.php`

#### Key Enhancements:
- **Statistics Dashboard**: Display cards showing:
  - Pending requests count (⏳ Awaiting review)
  - Approved requests count (✅ Total approved)
  - Rejected requests count (❌ Total rejected)

- **Leave Request Table**: Shows all leave requests with:
  - Employee name
  - Start and end dates
  - Number of days
  - Reason (truncated preview)
  - Status badge (Pending/Approved/Rejected)
  - Action buttons:
    - **View Button**: Available for all requests - opens detailed view modal
    - **Review Button**: Available only for pending requests - opens decision modal

#### Modals Added:

**View Modal (viewModal)**
- Displays complete leave request details:
  - Employee information (name, email, department)
  - Leave type
  - Start and end dates
  - Total number of days
  - Reason for leave (full text)
  - Current status badge
  - Admin comment (if exists and request is processed)
  - Processed by admin and date (if request is approved/rejected)
- Features:
  - Large modal size for better readability
  - "Review Request" button appears only for pending requests
  - Can navigate directly to review from here

**Review Modal (reviewModal)**
- For admin to make approval/denial decision:
  - Two prominent buttons:
    - ✓ Approve Request (green)
    - ✕ Deny Request (red)
  - Optional comment field for:
    - Reason for denial
    - Additional notes
    - Feedback to employee
  - Visual feedback when decision is selected (opacity change)
  - Validation to ensure decision is selected before submission

#### JavaScript Functions:
- `viewLeave(leaveId)`: Fetches and displays leave details via API
- `reviewLeave(leaveId)`: Opens review modal for pending requests
- `openReviewModal()`: Navigation from view modal to review modal
- `setDecision(action)`: Sets approval/rejection decision with visual feedback
- `openModal(modalId)`: Opens any modal
- `closeModal(modalId)`: Closes modal and resets form state
- `formatDate(dateStr)`: Client-side date formatting
- `capitalizeFirst(str)`: Capitalize first letter
- `getStatusBadge(status)`: Returns styled status badge with icon

---

### 2. **api/get-leave.php** - New API Endpoint
**Location:** `c:\xampp1\htdocs\leave\api\get-leave.php`

#### Purpose:
Provides REST API to fetch detailed leave request information for the view modal.

#### Features:
- **Authentication**: Requires admin access
- **Validation**: Checks for valid leave ID
- **Returns JSON** with:
  - Leave details (dates, reason, type, status, comments)
  - Employee information
  - Admin who processed the request (if applicable)
  - Calculated number of days

#### Response Format:
```json
{
  "success": true,
  "leave": {
    "id": 1,
    "start_date": "2026-01-20",
    "end_date": "2026-01-22",
    "reason": "...",
    "leave_type": "general",
    "status": "pending",
    "admin_comment": null,
    "updated_at": "...",
    "created_at": "..."
  },
  "user": {
    "id": 2,
    "name": "Employee Name",
    "email": "employee@example.com",
    "department": "HR"
  },
  "admin": null,
  "days": 3
}
```

---

### 3. **assets/css/main.css** - Styling Updates
**Location:** `c:\xampp1\htdocs\leave\assets\css\main.css`

#### New CSS Classes Added:

**Modal Size Variants**
- `.modal-lg`: Large modal (max-width: 700px) - used for detailed view
- `.modal-sm`: Small modal (max-width: 400px)

**Detail Grid System**
- `.detail-grid`: 2-column responsive grid for displaying key-value information
- `.detail-item`: Individual detail item with styled label and value
- `.detail-item.full-width`: Takes full width (used for reason and comments)
- `.detail-label`: Uppercase, smaller font, gray color for field names
- `.detail-value`: Main content display with proper line-height
- `.detail-value.detail-reason`: Preserves whitespace for multiline content

#### Styling Features:
- Clean, professional appearance with consistent spacing
- Left border accent (4px primary color) on detail items
- Light background for better readability
- Mobile-responsive grid layout

---

## User Experience Flow

### 1. **Viewing Leave Requests**
```
Admin views Manage Leave Requests page
  ↓
Sees statistics dashboard (pending/approved/rejected counts)
  ↓
Views table of all leave requests with status
```

### 2. **Viewing Request Details**
```
Click "View" button on any request
  ↓
Modal opens with complete request information:
  - Employee details
  - Leave dates and duration
  - Full reason text
  - Current status
  - Admin comments (if processed)
```

### 3. **Accepting or Denying a Pending Request**
```
View pending request
  ↓
Click "Review Request" button in view modal
  OR
Click "Review" button directly from table
  ↓
Review modal opens with two clear options:
  - ✓ Approve Request (green)
  - ✕ Deny Request (red)
  ↓
Admin clicks their decision
  ↓
(Optional) Add comment explaining decision
  ↓
Click "Submit Decision"
  ↓
Request is processed and status is updated
  ↓
Success message displays
  ↓
Page refreshes showing updated status
```

---

## Status Display

### Pending Requests
- Badge: `⏳ Pending` (warning color - orange)
- "Review" button available
- Can be approved or rejected

### Approved Requests
- Badge: `✅ Approved` (success color - green)
- Shows admin who approved and date
- No further action available

### Rejected Requests
- Badge: `❌ Rejected` (danger color - red)
- Shows admin comment explaining rejection
- No further action available

---

## Database Integration

### Uses Existing Tables:
- **leaves**: Stores leave requests
  - `status`: ENUM('pending', 'approved', 'rejected')
  - `admin_comment`: TEXT field for admin notes
  - `updated_by`: Foreign key to admin user
  - `updated_at`: Timestamp when status changed

- **users**: Employee and admin information
  - Used for getting employee details
  - Used for getting admin who processed request

---

## Security Features

1. **Admin Check**: All admin endpoints require admin authentication
2. **Input Validation**: Leave IDs validated before processing
3. **SQL Safety**: Uses prepared data and escaping
4. **CORS Consideration**: API endpoints properly validate requests
5. **Data Sanitization**: All displayed data is properly escaped

---

## Pending Status Indicator

The system properly tracks and displays:
- **Pending Status**: All new leave requests default to "pending"
- **Pending Count**: Statistics card shows number of pending requests
- **Visual Indicator**: 🏳️ Pending badge with warning color
- **Action Available**: Review button only shows for pending requests

---

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Responsive design for desktop viewing
- Uses standard Fetch API for AJAX calls
- CSS Grid with fallbacks

---

## Notes for Deployment

1. Ensure `api/` directory exists and is writable
2. Database tables must have the `status` field with ENUM values
3. Admin user must have proper role set to 'admin'
4. `BASE_URL` constant must be properly configured
5. All helper functions in `includes/config.php` must be available

---

## Testing Checklist

- [ ] View leave request details with complete information
- [ ] Accept/approve a pending leave request
- [ ] Deny/reject a pending leave request with comment
- [ ] Verify status updates after decision
- [ ] Check statistics dashboard updates
- [ ] Verify admin comment displays on processed requests
- [ ] Test modal opening/closing
- [ ] Verify responsive design
- [ ] Check error handling for invalid leave IDs
- [ ] Confirm activity logging works
