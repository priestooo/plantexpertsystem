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

// Get counts for dashboard
$plantCountSql = "SELECT COUNT(*) as count FROM plants";
$plantCountResult = $conn->query($plantCountSql);
$plantCount = $plantCountResult->fetch_assoc()['count'];

$livestockCountSql = "SELECT COUNT(*) as count FROM livestock";
$livestockCountResult = $conn->query($livestockCountSql);
$livestockCount = $livestockCountResult->fetch_assoc()['count'];

$userCountSql = "SELECT COUNT(*) as count FROM users";
$userCountResult = $conn->query($userCountSql);
$userCount = $userCountResult->fetch_assoc()['count'];

// Get recent plants
$recentPlantsSql = "SELECT plant_id, plant_name, scientific_name, created_at FROM plants ORDER BY created_at DESC LIMIT 5";
$recentPlantsResult = $conn->query($recentPlantsSql);
$recentPlants = [];
while ($row = $recentPlantsResult->fetch_assoc()) {
  $recentPlants[] = $row;
}

// Get recent livestock
$recentLivestockSql = "SELECT livestock_id, animal_name, category, created_at FROM livestock ORDER BY created_at DESC LIMIT 5";
$recentLivestockResult = $conn->query($recentLivestockSql);
$recentLivestock = [];
while ($row = $recentLivestockResult->fetch_assoc()) {
  $recentLivestock[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Agricultural Expert System</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../admin/admin.css">
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
              <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
              <li><a href="plants.php"><i class="fas fa-seedling"></i> Manage Plants</a></li>
              <li><a href="livestock.php"><i class="fas fa-horse"></i> Manage Livestock</a></li>
              <?php if ($_SESSION['role'] === 'admin'): ?>
              <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
              <?php endif; ?>
              <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
              <?php if ($_SESSION['role'] === 'admin'): ?>
              <li><a href="update_database.php"><i class="fas fa-database"></i> Update Database</a></li>
              <?php endif; ?>
              <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
          </ul>
      </div>
      
      <div class="admin-content">
          <h2>Dashboard</h2>
          
          <div class="dashboard-stats">
              <div class="stat-card">
                  <div class="stat-icon">
                      <i class="fas fa-seedling"></i>
                  </div>
                  <div class="stat-info">
                      <h3>Total Plants</h3>
                      <p><?php echo $plantCount; ?></p>
                  </div>
              </div>
              
              <div class="stat-card">
                  <div class="stat-icon">
                      <i class="fas fa-horse"></i>
                  </div>
                  <div class="stat-info">
                      <h3>Total Livestock</h3>
                      <p><?php echo $livestockCount; ?></p>
                  </div>
              </div>
              
              <div class="stat-card">
                  <div class="stat-icon">
                      <i class="fas fa-users"></i>
                  </div>
                  <div class="stat-info">
                      <h3>Total Users</h3>
                      <p><?php echo $userCount; ?></p>
                  </div>
              </div>
              
              <div class="stat-card">
                  <div class="stat-icon">
                      <i class="fas fa-calendar-alt"></i>
                  </div>
                  <div class="stat-info">
                      <h3>Current Date</h3>
                      <p><?php echo date('d M Y'); ?></p>
                  </div>
              </div>
          </div>
          
          <div class="dashboard-recent">
              <div class="recent-section">
                  <h3>Recent Plants</h3>
                  <table class="admin-table">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Scientific Name</th>
                              <th>Added On</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($recentPlants as $plant): ?>
                          <tr>
                              <td><?php echo $plant['plant_id']; ?></td>
                              <td><?php echo htmlspecialchars($plant['plant_name']); ?></td>
                              <td><em><?php echo htmlspecialchars($plant['scientific_name']); ?></em></td>
                              <td><?php echo date('d M Y', strtotime($plant['created_at'])); ?></td>
                              <td>
                                  <a href="edit_plant.php?id=<?php echo $plant['plant_id']; ?>" class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
                                  <a href="view_plant.php?id=<?php echo $plant['plant_id']; ?>" class="action-btn view" title="View"><i class="fas fa-eye"></i></a>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                          <?php if (empty($recentPlants)): ?>
                          <tr>
                              <td colspan="5" class="no-data">No plants found</td>
                          </tr>
                          <?php endif; ?>
                      </tbody>
                  </table>
                  <a href="plants.php" class="view-all-btn">View All Plants</a>
              </div>
              
              <div class="recent-section">
                  <h3>Recent Livestock</h3>
                  <table class="admin-table">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Category</th>
                              <th>Added On</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($recentLivestock as $animal): ?>
                          <tr>
                              <td><?php echo $animal['livestock_id']; ?></td>
                              <td><?php echo htmlspecialchars($animal['animal_name']); ?></td>
                              <td><?php echo htmlspecialchars($animal['category']); ?></td>
                              <td><?php echo date('d M Y', strtotime($animal['created_at'])); ?></td>
                              <td>
                                  <a href="edit_livestock.php?id=<?php echo $animal['livestock_id']; ?>" class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
                                  <a href="view_livestock.php?id=<?php echo $animal['livestock_id']; ?>" class="action-btn view" title="View"><i class="fas fa-eye"></i></a>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                          <?php if (empty($recentLivestock)): ?>
                          <tr>
                              <td colspan="5" class="no-data">No livestock found</td>
                          </tr>
                          <?php endif; ?>
                      </tbody>
                  </table>
                  <a href="livestock.php" class="view-all-btn">View All Livestock</a>
              </div>
          </div>
          
          <div class="dashboard-actions">
              <a href="add_plant.php" class="action-card">
                  <i class="fas fa-plus-circle"></i>
                  <span>Add New Plant</span>
              </a>
              <a href="add_livestock.php" class="action-card">
                  <i class="fas fa-plus-circle"></i>
                  <span>Add New Livestock</span>
              </a>
              <?php if ($_SESSION['role'] === 'admin'): ?>
              <a href="add_user.php" class="action-card">
                  <i class="fas fa-user-plus"></i>
                  <span>Add New User</span>
              </a>
              <?php endif; ?>
              <?php if ($_SESSION['role'] === 'admin'): ?>
              <a href="update_database.php" class="action-card">
                  <i class="fas fa-database"></i>
                  <span>Update Database</span>
              </a>
              <?php endif; ?>
          </div>
      </div>
  </div>
</body>
</html>

