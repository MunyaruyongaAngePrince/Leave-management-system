<?php
include "../includes/config.php";
checkAdmin();

$action = $_GET['action'] ?? 'list';
$empId = $_GET['id'] ?? null;

// Handle add/edit employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_employee'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone'] ?? '');
    
    if ($action === 'edit' && $empId) {
        if (emailExists($email, $empId)) {
            setError('Email already exists');
        } else {
            updateRecord('users', [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
            ], "id = " . (int)$empId);
            setSuccess('Employee updated successfully');
            header("Location: " . BASE_URL . "admin/employees.php");
            exit;
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && canDeleteEmployees()) {
    deleteRecord('users', "id = " . (int)$_GET['delete']);
    setSuccess('Employee deleted successfully');
    header("Location: " . BASE_URL . "admin/employees.php");
    exit;
}

$employee = null;
if ($action === 'edit' && $empId) {
    $employee = getRecord('users', "id = " . (int)$empId);
}

$employees = getRecords('users', "role = 'employee'", 'name ASC');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees - Leave Management System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    
    <div class="page-wrapper">
        <div class="container">
            <div class="page-header">
                <div class="flex-between">
                    <div>
                        <div class="page-title">
                            <?php echo ($action === 'edit') ? 'Edit Employee' : 'Manage Employees'; ?>
                        </div>
                        <p class="page-subtitle">Manage employee accounts and permissions</p>
                    </div>
                    <?php if ($action !== 'edit'): ?>
                        <a href="<?php echo BASE_URL; ?>admin/employees.php?action=add" class="btn btn-primary">
                        Add Employee
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php displayAlert(); ?>
            
            <?php if ($action === 'add' || $action === 'edit'): ?>
                <!-- Form -->
                <div class="card">
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Full Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $employee['name'] ?? ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $employee['email'] ?? ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" value="<?php echo $employee['phone'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo BASE_URL; ?>admin/employees.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="save_employee" class="btn btn-primary">Save Employee</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- List -->
                <div class="card">
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $i => $emp): ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($emp['name']); ?></td>
                                        <td><?php echo htmlspecialchars($emp['email']); ?></td>
                                        <td><?php echo formatDate($emp['created_at']); ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="?action=edit&id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="?delete=<?php echo $emp['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this employee?')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <footer class="footer">
        <div class="footer-text">
            © 2024 Leave Management System | All Rights Reserved
        </div>
    </footer>
</body>
</html>
