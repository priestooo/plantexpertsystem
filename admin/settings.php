<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

$message = '';
$messageType = '';

// Get current settings
$settingsSql = "SELECT * FROM settings";
$settingsResult = $conn->query($settingsSql);
$settings = [];
while ($row = $settingsResult->fetch_assoc()) {
    $settings[$row['setting_key']] = $row;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update WhatsApp number
    if (isset($_POST['vet_whatsapp_number'])) {
        $whatsappNumber = trim($_POST['vet_whatsapp_number']);
        
        // Validate WhatsApp number (should be numbers only)
        if (!preg_match('/^[0-9]+$/', $whatsappNumber)) {
            $message = "WhatsApp number should contain numbers only (include country code without +).";
            $messageType = "error";
        } else {
            // Update the setting
            $updateSql = "UPDATE settings SET setting_value = ? WHERE setting_key = 'vet_whatsapp_number'";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("s", $whatsappNumber);
            
            if ($stmt->execute()) {
                $message = "Settings updated successfully!";
                $messageType = "success";
                
                // Update local settings array
                $settings['vet_whatsapp_number']['setting_value'] = $whatsappNumber;
            } else {
                $message = "Error updating settings: " . $conn->error;
                $messageType = "error";
            }
            
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Agricultural Expert System</title>
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
                <li class="active"><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <h2>System Settings</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form class="admin-form" method="post" action="">
                <div class="form-section">
                    <h3>Veterinary Contact Settings</h3>
                    <div class="form-group">
                        <label for="vet_whatsapp_number">Veterinarian WhatsApp Number</label>
                        <input type="text" id="vet_whatsapp_number" name="vet_whatsapp_number" value="<?php echo htmlspecialchars($settings['vet_whatsapp_number']['setting_value'] ?? ''); ?>" required>
                        <small>Enter the WhatsApp number including country code without the + symbol (e.g., 2348012345678)</small>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Save Settings</button>
                    <a href="dashboard.php" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

