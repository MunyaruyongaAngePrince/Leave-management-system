<?php
/**
 * PDF Export Helper
 * Using DOMPDF - install via: composer require dompdf/dompdf
 */

class PDFExporter {
    /**
     * Export leave request to PDF
     */
    public static function exportLeave($leaveId) {
        global $conn;
        
        $leave = getRecord("leaves", "id = " . (int)$leaveId);
        if (!$leave) return false;
        
        $user = getRecord("users", "id = " . (int)$leave['user_id']);
        
        $html = self::generateLeaveHTML($leave, $user);
        self::generatePDF($html, "leave-request-" . $leaveId);
    }
    
    /**
     * Export all leaves to PDF
     */
    public static function exportAllLeaves($filters = []) {
        global $conn;
        
        $sql = "SELECT leaves.*, users.name, users.email FROM leaves 
                JOIN users ON leaves.user_id = users.id";
        
        if (!empty($filters)) {
            $conditions = [];
            if (!empty($filters['status'])) {
                $conditions[] = "leaves.status = '" . sanitize($filters['status']) . "'";
            }
            if (!empty($filters['user_id'])) {
                $conditions[] = "leaves.user_id = " . (int)$filters['user_id'];
            }
            if (!empty($filters['start_date'])) {
                $conditions[] = "leaves.start_date >= '" . sanitize($filters['start_date']) . "'";
            }
            if (!empty($filters['end_date'])) {
                $conditions[] = "leaves.end_date <= '" . sanitize($filters['end_date']) . "'";
            }
            
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
        }
        
        $result = executeQuery($sql);
        $leaves = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        $html = self::generateLeavesListHTML($leaves);
        self::generatePDF($html, "leave-report-" . date('Y-m-d'));
    }
    
    /**
     * Export employee list to PDF
     */
    public static function exportEmployeeList() {
        $employees = getRecords("users", "role = 'employee'", "name ASC");
        
        $html = self::generateEmployeeListHTML($employees);
        self::generatePDF($html, "employee-list-" . date('Y-m-d'));
    }
    
    /**
     * Generate leave HTML for PDF
     */
    private static function generateLeaveHTML($leave, $user) {
        $days = calculateDays($leave['start_date'], $leave['end_date']);
        $status = getStatusIcon($leave['status']) . ' ' . ucfirst($leave['status']);
        
        return <<<HTML
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: Arial, sans-serif; color: #1e293b; }
                .header { background: #1e293b; color: white; padding: 20px; text-align: center; margin-bottom: 30px; }
                .section { margin-bottom: 30px; }
                .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 5px; }
                .row { display: flex; margin-bottom: 10px; }
                .label { font-weight: bold; width: 150px; color: #64748b; }
                .value { flex: 1; }
                .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Leave Request</h1>
            </div>
            
            <div class="section">
                <div class="section-title">Employee Information</div>
                <div class="row">
                    <div class="label">Name:</div>
                    <div class="value">' . htmlspecialchars($user.name) . '</div>
                    
                </div>
                <div class="row">
                    <div class="label">Email:</div>
                    <div class="value">' . htmlspecialchars($user.email) . '</div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Leave Details</div>
                <div class="row">
                    <div class="label">Start Date:</div>
                    <div class="value">' . formatDate($leave.start_date) . '</div>
                </div>
                <div class="row">
                    <div class="label">End Date:</div>
                    <div class="value">' . formatDate($leave.end_date) . '</div>
                </div>
                <div class="row">
                    <div class="label">Total Days:</div>
                    <div class="value">' . $days . ' day(s)</div>
                </div>
                <div class="row">
                    <div class="label">Reason:</div>
                    <div class="value">' . htmlspecialchars($leave.reason) . '</div>
                </div>
                <div class="row">
                    <div class="label">Status:</div>
                    <div class="value">' . $status . '</div>
                </div>
                <div class="row">
                    <div class="label">Applied On:</div>
                    <div class="value">' . formatDate($leave.created_at) . '</div>
                </div>
            </div>
            
            <div class="footer">
                <p>This is an official leave request document. Generated on ' . date('Y-m-d H:i:s') . '</p>
            </div>
        </body>
        </html>
        HTML;
    }
    
    private static function generateLeavesListHTML($leaves) {
        $rows = '';
        foreach ($leaves as $leave) {
            $days = calculateDays($leave['start_date'], $leave['end_date']);
            $status = ucfirst($leave['status']);
            $rows .= <<<HTML
            <tr>
                <td>{$leave['name']}</td>
                <td>{$leave['start_date']}</td>
                <td>{$leave['end_date']}</td>
                <td>{$days}</td>
                <td>{$status}</td>
            </tr>
            HTML;
        }
        
        return <<<HTML
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: Arial, sans-serif; color: #1e293b; }
                .header { background: #1e293b; color: white; padding: 20px; text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th { background: #2563eb; color: white; padding: 10px; text-align: left; font-weight: bold; }
                td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
                .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Leave Requests Report</h1>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    {$rows}
                </tbody>
            </table>
            
        </body>
        </html>
        HTML;
    }
    
    /**
     * Generate employee list HTML for PDF
     */
    private static function generateEmployeeListHTML($employees) {
        $rows = '';
        foreach ($employees as $emp) {
            $leaveCount = count(getRecords("leaves", "user_id = " . $emp['id']));
            $rows .= <<<HTML
            <tr>
                <td>{$emp['name']}</td>
                <td>{$emp['email']}</td>
                <td>{formatDate($emp.created_at)}</td>
                <td>{$leaveCount}</td>
            </tr>
            HTML;
        }
        
        return <<<HTML
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: Arial, sans-serif; color: #1e293b; }
                .header { background: #1e293b; color: white; padding: 20px; text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th { background: #2563eb; color: white; padding: 10px; text-align: left; font-weight: bold; }
                td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
                .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Employee List</h1>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Total Leaves</th>
                    </tr>
                </thead>
                <tbody>
                    {$rows}
                </tbody>
            </table>
            
            <div class="footer">
                <p>Generated on {date('Y-m-d H:i:s')} | Total Employees: {count($employees)}</p>
            </div>
        </body>
        </html>
        HTML;
    }
    
    /**
     * Generate PDF (using built-in PHP or external library)
     */
    private static function generatePDF($html, $filename) {
        // For now, output HTML for browser to print
        // In production, integrate with DOMPDF or similar library
        
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.html"');
        echo $html;
        exit;
    }
}

/**
 * Simple PDF printer class for server-side PDF generation
 * Requires: composer require dompdf/dompdf
 */
class SimplePDFPrinter {
    public static function print($html, $filename = 'document') {
        // Check if DOMPDF is installed
        $dompdfPath = __DIR__ . '/../vendor/autoload.php';
        
        if (file_exists($dompdfPath)) {
            require_once $dompdfPath;
            
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
            
            echo $dompdf->output();
            exit;
        } else {
            // Fallback to browser print
            header('Content-Type: text/html; charset=utf-8');
            echo <<<HTML
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .print-notice { background: #fff3cd; padding: 15px; margin-bottom: 20px; border: 1px solid #ffc107; }
                </style>
            </head>
            <body>
                <div class="print-notice">
                    <strong>Print to PDF:</strong> Use your browser's print function (Ctrl+P or Cmd+P) and select "Save as PDF"
                </div>
                {$html}
            </body>
            </html>
            HTML;
            exit;
        }
    }
}

?>
