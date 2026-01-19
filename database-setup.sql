-- Leave Management System Database Setup
-- Run this SQL in phpMyAdmin or MySQL command line

CREATE DATABASE IF NOT EXISTS leave_system;
USE leave_system;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    department VARCHAR(100),
    role ENUM('admin', 'employee') DEFAULT 'employee',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leaves Table
CREATE TABLE IF NOT EXISTS leaves (
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_start_date (start_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity Logs Table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leave Types Table (Optional)
CREATE TABLE IF NOT EXISTS leave_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    max_days INT DEFAULT 20,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Data
-- Create admin account (password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('System Admin', 'admin@leavesystem.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/KFm', 'admin');

-- Create sample employee (password: employee123)
INSERT INTO users (name, email, password, role, department) VALUES 
('John Doe', 'john@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/KFm', 'employee', 'HR'),
('Jane Smith', 'jane@example.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/KFm', 'employee', 'IT');

-- Insert leave types
INSERT INTO leave_types (name, description, max_days) VALUES 
('Annual Leave', 'Regular annual leave', 20),
('Sick Leave', 'Medical leave', 10),
('Maternity Leave', 'Maternity leave', 90),
('Study Leave', 'Educational leave', 5),
('Unpaid Leave', 'Unpaid leave', 0);

-- Create view for leave statistics
CREATE OR REPLACE VIEW leave_statistics AS
SELECT 
    u.id,
    u.name,
    u.email,
    COUNT(l.id) as total_leaves,
    SUM(CASE WHEN l.status = 'approved' THEN 1 ELSE 0 END) as approved_leaves,
    SUM(CASE WHEN l.status = 'pending' THEN 1 ELSE 0 END) as pending_leaves,
    SUM(CASE WHEN l.status = 'rejected' THEN 1 ELSE 0 END) as rejected_leaves
FROM users u
LEFT JOIN leaves l ON u.id = l.user_id
WHERE u.role = 'employee'
GROUP BY u.id, u.name, u.email;

-- Done!
-- Login with:
-- Email: admin@leavesystem.com
-- Password: admin123
--
-- Employee Test Account:
-- Email: john@example.com
-- Password: employee123
