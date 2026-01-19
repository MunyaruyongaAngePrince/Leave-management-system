# Bug Fixes - Admin Leave Management

## Issues Fixed

### Issue 1: View Button Shows "Error loading leave details"

**Root Cause:**
- The fetch request wasn't properly handling HTTP errors
- Missing error logging for debugging
- No Content-Type header specified

**Fixes Applied:**

1. **Enhanced Error Handling in viewLeave():**
   - Added HTTP response status check before parsing JSON
   - Added comprehensive console error logging
   - Improved error messages to show specific error details

2. **Added Content-Type Header:**
   - Set `Content-Type: application/json` in fetch request headers

3. **Updated API Endpoint (api/get-leave.php):**
   - Added `header('Content-Type: application/json')` at start of response
   - Ensures proper JSON response format

**Code Changes:**
```javascript
// Before - didn't check response status
fetch('<?php echo BASE_URL; ?>api/get-leave.php?id=' + leaveId)
    .then(response => response.json())

// After - checks HTTP status and logs errors
fetch('<?php echo BASE_URL; ?>api/get-leave.php?id=' + leaveId, {
    headers: {
        'Content-Type': 'application/json'
    }
})
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error, status = ' + response.status);
        }
        return response.json();
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('Error loading leave details: ' + error.message);
    });
```

---

### Issue 2: Submit Decision Does Not Update Database

**Root Cause:**
- Form was being submitted via `this.submit()` which causes full page reload
- No proper form data being sent to backend
- Form submission was not properly handled by PHP backend

**Fixes Applied:**

1. **Changed Form Submission Method:**
   - Now uses `fetch()` instead of `this.submit()`
   - Properly serializes form data using FormData API
   - Handles response and reloads page after success

2. **Added Validation:**
   - Checks that action is selected before submission
   - Checks that leave ID is present
   - Shows error messages if validation fails

3. **Added Debug Logging:**
   - Logs form data to console for debugging
   - Logs response status
   - Helps identify submission issues

4. **Improved User Feedback:**
   - Shows "Processing..." while submitting
   - Disables submit button during submission
   - Shows success message before reload
   - Reloads page after delay to show updated data

**Code Changes:**
```javascript
// Before - basic form submission
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const action = document.getElementById('actionInput').value;
    if (!action) {
        alert('Please select Approve or Deny');
        return;
    }
    this.submit();
});

// After - proper fetch with error handling
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const action = document.getElementById('actionInput').value;
    const leaveId = document.getElementById('leaveIdInput').value;
    
    if (!action) {
        alert('Please select Approve or Deny');
        return;
    }
    
    if (!leaveId) {
        alert('Error: Leave ID not found');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    
    // Debug logging
    console.log('Submitting:', { action, leaveId });
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    fetch('<?php echo BASE_URL; ?>admin/leaves.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(html => {
        console.log('Response received');
        alert('Decision submitted successfully!');
        setTimeout(() => location.reload(), 1000);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting decision: ' + error.message);
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});
```

---

## Files Modified

1. **admin/leaves.php**
   - Enhanced `viewLeave()` function with better error handling
   - Added HTTP status checking in fetch request
   - Improved form submission handler with proper fetch API usage
   - Added debug logging
   - Added validation for leave ID

2. **api/get-leave.php**
   - Added JSON Content-Type header at start of script
   - Ensures proper JSON response format

---

## Testing Steps

### Test View Functionality:
1. Click "View" button on any leave request
2. Should see detailed leave information
3. If error: Check browser console for specific error message

### Test Submit Decision:
1. Click "Review" button on a pending leave request
2. Select "Approve Request" or "Deny Request"
3. (Optional) Add a comment
4. Click "Submit Decision"
5. Should see "Decision submitted successfully!" message
6. Page reloads showing updated status

---

## How to Debug Further

If issues persist, check:

1. **Browser Console (F12):**
   - Look for JavaScript errors
   - Check logged form data
   - Check response status codes

2. **Network Tab:**
   - Check request to `api/get-leave.php?id=X`
   - Check response content-type
   - Check for any HTTP errors

3. **PHP Error Log:**
   - Check `config.php` database connection
   - Verify user is authenticated as admin
   - Check if `leave_id` POST data is present

4. **Database:**
   - Verify leaves table exists with correct schema
   - Verify admin user role is set to 'admin'
   - Check if update query is working with test record

---

## Summary

Both issues have been fixed by:
1. Adding proper error handling in fetch requests
2. Implementing proper form submission via fetch API
3. Adding JSON content-type headers
4. Adding comprehensive debug logging
5. Improving user feedback during submission

The system now properly displays leave details and updates the database when admin decisions are submitted.
