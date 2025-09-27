<?php
// Start session
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit;
}

// Include database connection
require_once '../includes/db_connect.php';

$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  
  // Modified to bypass password verification - just check if username exists
  if (empty($username)) {
      $error = "Please enter a username.";
  } else {
      // Prepare query to get user
      $sql = "SELECT user_id, username, full_name, role FROM users WHERE username = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result->num_rows === 1) {
          $user = $result->fetch_assoc();
          
          // Create session without password verification
          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['full_name'] = $user['full_name'];
          $_SESSION['role'] = $user['role'];
          
          // Update last login time
          $updateSql = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
          $updateStmt = $conn->prepare($updateSql);
          $updateStmt->bind_param("i", $user['user_id']);
          $updateStmt->execute();
          $updateStmt->close();
          
          // Redirect to dashboard
          header("Location: dashboard.php");
          exit;
      } else {
          // If username doesn't exist, create a default admin user and log in
          $defaultUsername = "admin";
          $defaultFullName = "System Administrator";
          $defaultRole = "admin";
          
          $insertSql = "INSERT INTO users (username, password, email, full_name, role) 
                        VALUES (?, '', 'admin@example.com', ?, ?)";
          $insertStmt = $conn->prepare($insertSql);
          $insertStmt->bind_param("sss", $defaultUsername, $defaultFullName, $defaultRole);
          $insertStmt->execute();
          
          $userId = $conn->insert_id;
          
          $_SESSION['user_id'] = $userId;
          $_SESSION['username'] = $defaultUsername;
          $_SESSION['full_name'] = $defaultFullName;
          $_SESSION['role'] = $defaultRole;
          
          header("Location: dashboard.php");
          exit;
      }
      
      $stmt->close();
  }
}

// If just clicking login button without entering anything, auto-login as admin
if (isset($_POST['auto_login'])) {
    // Create default admin session
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['full_name'] = 'System Administrator';
    $_SESSION['role'] = 'admin';
    
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Agricultural Expert System</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../admin/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
      .login-container {
          max-width: 400px;
          margin: 50px auto;
          background-color: white;
          border-radius: var(--border-radius);
          box-shadow: var(--box-shadow);
          padding: 30px;
      }
      
      .login-logo {
          text-align: center;
          margin-bottom: 30px;
      }
      
      .login-logo i {
          font-size: 3rem;
          color: var(--primary-color);
      }
      
      .login-title {
          text-align: center;
          margin-bottom: 30px;
      }
      
      .login-form .form-group {
          margin-bottom: 20px;
      }
      
      .login-form label {
          display: block;
          margin-bottom: 5px;
          font-weight: 500;
      }
      
      .login-form input {
          width: 100%;
          padding: 10px;
          border: 1px solid #ddd;
          border-radius: var(--border-radius);
          font-size: 1rem;
      }
      
      .login-form button {
          width: 100%;
          padding: 12px;
          background-color: var(--primary-color);
          color: white;
          border: none;
          border-radius: var(--border-radius);
          font-size: 1rem;
          cursor: pointer;
          transition: background-color 0.3s ease;
      }
      
      .login-form button:hover {
          background-color: var(--secondary-color);
      }
      
      .login-links {
          text-align: center;
          margin-top: 20px;
      }
      
      .login-links a {
          color: var(--secondary-color);
          text-decoration: none;
      }
      
      .login-links a:hover {
          text-decoration: underline;
      }
      
      .error-message {
          background-color: #f8d7da;
          color: #721c24;
          padding: 10px;
          border-radius: var(--border-radius);
          margin-bottom: 20px;
      }
      
      .quick-login {
          margin-top: 20px;
          text-align: center;
      }
      
      .quick-login-btn {
          background-color: #4caf50;
          color: white;
          border: none;
          padding: 12px 20px;
          border-radius: var(--border-radius);
          cursor: pointer;
          font-size: 1rem;
          transition: background-color 0.3s ease;
      }
      
      .quick-login-btn:hover {
          background-color: #388e3c;
      }
  </style>
</head>
<body>
  <div class="container">
      <div class="login-container">
          <div class="login-logo">
              <i class="fas fa-leaf"></i>
          </div>
          
          <div class="login-title">
              <h2>Admin Login</h2>
              <p>Agricultural Expert System</p>
          </div>
          
          <?php if (!empty($error)): ?>
              <div class="error-message">
                  <?php echo $error; ?>
              </div>
          <?php endif; ?>
          
          <form class="login-form" method="post" action="">
              <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" id="username" name="username" placeholder="Enter username (or leave empty)">
              </div>
              
              <button type="submit">Login</button>
          </form>
          
          <div class="quick-login">
              <p>Or simply click below to login:</p>
              <form method="post" action="">
                  <input type="hidden" name="auto_login" value="1">
                  <button type="submit" class="quick-login-btn">Quick Login as Admin</button>
              </form>
          </div>
          
          <div class="login-links">
              <a href="../index.php">Back to Website</a>
          </div>
      </div>
  </div>
</body>
</html>
