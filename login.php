<?php
session_start();
include "includes/config.php";

// If already logged in, redirect
if (isLoggedIn()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } elseif (!validateEmail($email)) {
        $error = 'Invalid email format';
    } else {
        $user = getRecord("users", "email = '$email'");
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            logActivity($user['id'], 'login', 'User logged in');
            header("Location: " . BASE_URL . "dashboard.php");
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Leave Management System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            background-size: cover;
            background-attachment: fixed;
            padding: var(--spacing-lg);
        }
        #sky-background {
            position: fixed; top: 0; right: 0; bottom: 0; left: 0;
            /* Gradient from dark blue to lighter blue/orange */
            background: linear-gradient(to bottom, #033d5e 0%, #70a9d1 100%);
            animation: dayNight 2s infinite alternate; 
            }

        @keyframes dayNight {
            0% { background-position: 0% 0%; }
            50% { background-position: 0% 100%; }
        }
        
        .login-container {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            max-width: 450px;
            width: 100%;
            padding: var(--spacing-2xl);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
        }
        
        .login-logo {
            font-size: 3rem;
            margin-bottom: var(--spacing-md);
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            margin-bottom: var(--spacing-sm);
        }
        
        .login-header p {
            color: var(--gray);
        }
        
        .form-group {
            margin-bottom: var(--spacing-lg);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            margin-bottom: var(--spacing-lg);
        }
        
        .forgot-password {
            color: var(--primary);
        }
        
        .signup-link {
            text-align: center;
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--light-gray);
        }
        
        .signup-link p {
            margin: 0;
        }
        
        .signup-link a {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-wrapper" id="sky-background">
        
        <div class="login-container">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Leave Management System</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <span>✗</span>
                    <div><?php echo htmlspecialchars($error); ?></div>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label required" for="email">Email Address</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($email); ?>"
                        placeholder="you@example.com"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label class="form-label required" for="password">Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        required
                    >
                </div>
                
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary btn-block">
                    Sign In
                </button>
            </form>
            
            <div class="signup-link">
                <p>Don't have an account? <a href="<?php echo BASE_URL; ?>register.php">Create one now</a></p>
            </div>
        </div>
    </div>
</body>
</html>
