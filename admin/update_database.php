<?php
// Start session
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

$message = '';
$messageType = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Execute the SQL files
        require_once '../database/update_database.php';
        
        $message = "Database updated successfully with additional plants and livestock!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error updating database: " . $e->getMessage();
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Database - Agricultural Expert System</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-header">
        <div class="logo">
            <i class="fas fa-leaf"></i>
            <span>Agricultural Expert System</span>
        </div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    
    <div class="admin-container">
        <div class="admin-sidebar">
            <h3>Navigation</h3>
            <ul class="admin-menu">
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="plants.php"><i class="fas fa-seedling"></i> Manage Plants</a></li>
                <li><a href="livestock.php"><i class="fas fa-horse"></i> Manage Livestock</a></li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
                <?php endif; ?>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li class="active"><a href="update_database.php"><i class="fas fa-database"></i> Update Database</a></li>
                <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <h2>Update Database</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="info-box">
                <h3><i class="fas fa-info-circle"></i> Database Update Information</h3>
                <p>This page allows you to update the database with additional Nigerian plants and livestock entries. The update will add:</p>
                <ul>
                    <li>15 more Nigerian plants with detailed information</li>
                    <li>15 more Nigerian livestock entries, including fishes and poultry birds</li>
                </ul>
                <p><strong>Note:</strong> This action will only add new entries and will not affect existing data.</p>
            </div>
            
            <form method="post" action="">
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Update Database</button>
                    <a href="dashboard.php" class="btn-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
