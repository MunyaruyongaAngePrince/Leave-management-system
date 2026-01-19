# Quick Reference - Admin Leave Management Features

## ✅ Features Implemented

### 1. View Leave Request Details
- Click **"View"** button on any leave request
- See complete information:
  - Employee name, email, department
  - Leave dates and total days
  - Full reason text
  - Leave type
  - Current status
  - (If approved/rejected) Admin comment and who processed it

### 2. Accept/Deny Leave Requests
- Only available for **pending** requests
- **Two ways to access review:**
  - Click **"Review"** button directly from table
  - Click **"Review Request"** button from view modal

- **Decision Options:**
  - ✓ **Approve Request** (Green button)
  - ✕ **Deny Request** (Red button)

- **Optional Comment:**
  - Add notes/feedback (especially useful when denying)

### 3. Track Request Status
- **Pending** ⏳ (Orange) - Awaiting admin review
- **Approved** ✅ (Green) - Request accepted
- **Rejected** ❌ (Red) - Request denied

### 4. Statistics Dashboard
- **Pending**: Count of requests awaiting review
- **Approved**: Total number of approved requests
- **Rejected**: Total number of rejected requests

---

## 📋 What Gets Displayed in View Modal

| Field | Description |
|-------|-------------|
| **Employee Name** | Who submitted the request |
| **Employee Email** | Contact information |
| **Department** | Employee's department |
| **Leave Type** | Type of leave (general, sick, maternity, etc.) |
| **Start Date** | When leave begins |
| **End Date** | When leave ends |
| **Number of Days** | Total days of leave requested |
| **Status** | Current status with badge |
| **Reason for Leave** | Full text explaining why |
| **Admin Comment** | Your notes (only if already processed) |
| **Processed By** | Admin name and date (only if already processed) |

---

## 🎯 How to Manage Requests

### Approve a Request
1. Click **View** button
2. Review all details
3. Click **Review Request**
4. Click **✓ Approve Request**
5. (Optional) Add a comment like "Approved"
6. Click **Submit Decision**

### Deny a Request
1. Click **View** button
2. Review all details
3. Click **Review Request**
4. Click **✕ Deny Request**
5. Add comment explaining why (e.g., "Already on leave during this period")
6. Click **Submit Decision**

---

## 🔄 Workflow Status

- **Pending Request**: Shows ⏳ badge, has Review button
- **Approved Request**: Shows ✅ badge, no Review button, displays admin info
- **Rejected Request**: Shows ❌ badge, no Review button, displays rejection reason

---

## 💾 What Gets Saved

When you approve or reject:
- ✅ Request status is updated
- ✅ Your admin comment is saved
- ✅ Timestamp of decision is recorded
- ✅ Activity log is updated
- ✅ Employee notifications (if configured)

---

## 🛠️ Technical Details

- **View Details API**: `api/get-leave.php?id=[leave_id]`
- **Submit Decision**: POST to `admin/leaves.php` with action and comment
- **Database Fields Updated**: `status`, `admin_comment`, `updated_by`, `updated_at`

---

## 📱 Mobile Compatibility

The interface is responsive and works on:
- Desktop browsers
- Tablets
- Mobile devices (read-only, review on desktop recommended)

---

## 🔐 Permissions

- **Admin Only**: All admin functions require admin login
- **Automatic Validation**: Invalid leave IDs are rejected
- **Secure**: All data is properly escaped and validated
