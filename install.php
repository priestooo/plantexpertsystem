<?php
// Start session
session_start();

// Include database connection
require_once 'includes/db_connect.php';

$message = '';
$success = false;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
      // Import database schema and data
      $sqlFile = file_get_contents('database/plant_expert.sql');
      
      // Add settings table creation and default values
      $sqlFile .= file_get_contents('database/update_settings.sql');
      
      // Split SQL file into individual queries
      $queries = explode(';', $sqlFile);
      
      // Execute each query
      foreach ($queries as $query) {
          $query = trim($query);
          if (!empty($query)) {
              $conn->query($query);
              
              if ($conn->error) {
                  throw new Exception($conn->error);
              }
          }
      }
      
      $message = 'Database setup completed successfully!';
      $success = true;
  } catch (Exception $e) {
      $message = 'Error setting up database: ' . $e->getMessage();
  }
}

// Check if database is already set up
$checkTablesSql = "SHOW TABLES LIKE 'plants'";
$result = $conn->query($checkTablesSql);
$databaseSetup = $result->num_rows > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Install - Agricultural Expert System</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="container">
      <header>
          <h1>Agricultural Expert System - Installation</h1>
          <p>Set up your agricultural expert system database</p>
      </header>

      <div class="install-container" style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
          <?php if ($databaseSetup): ?>
              <div class="alert success">
                  <p>Database is already set up!</p>
              </div>
              <p>You can now use the Agricultural Expert System.</p>
              <div style="margin-top: 20px;">
                  <a href="index.php" class="btn-primary" style="display: inline-block; padding: 10px 20px; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: var(--border-radius);">Go to Homepage</a>
              </div>
          <?php else: ?>
              <?php if (!empty($message)): ?>
                  <div class="alert <?php echo $success ? 'success' : 'error'; ?>">
                      <p><?php echo $message; ?></p>
                  </div>
                  <?php if ($success): ?>
                      <p>You can now use the Agricultural Expert System.</p>
                      <div style="margin-top: 20px;">
                          <a href="index.php" class="btn-primary" style="display: inline-block; padding: 10px 20px; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: var(--border-radius);">Go to Homepage</a>
                      </div>
                  <?php endif; ?>
              <?php else: ?>
                  <p>Welcome to the Agricultural Expert System installation. This will set up the necessary database tables and sample data.</p>
                  <p>Make sure you have configured the database connection in <code>includes/db_connect.php</code>.</p>
                  
                  <form method="post" style="margin-top: 20px;">
                      <button type="submit" class="btn-primary" style="padding: 10px 20px; background-color: var(--primary-color); color: white; border: none; border-radius: var(--border-radius); cursor: pointer;">Install Database</button>
                  </form>
              <?php endif; ?>
          <?php endif; ?>
      </div>
  </div>
</body>
</html>

