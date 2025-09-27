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

// Get plant ID from request
$plantId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($plantId <= 0) {
  header("Location: plants.php");
  exit;
}

// Get plant details
$plantSql = "SELECT * FROM plants WHERE plant_id = ?";
$plantStmt = $conn->prepare($plantSql);
$plantStmt->bind_param("i", $plantId);
$plantStmt->execute();
$plantResult = $plantStmt->get_result();

if ($plantResult->num_rows === 0) {
  header("Location: plants.php");
  exit;
}

$plant = $plantResult->fetch_assoc();

// Get planting conditions
$conditionsSql = "SELECT * FROM planting_conditions WHERE plant_id = ?";
$conditionsStmt = $conn->prepare($conditionsSql);
$conditionsStmt->bind_param("i", $plantId);
$conditionsStmt->execute();
$conditionsResult = $conditionsStmt->get_result();
$conditions = $conditionsResult->fetch_assoc();

// Get watering schedule
$wateringSql = "SELECT * FROM watering_schedule WHERE plant_id = ?";
$wateringStmt = $conn->prepare($wateringSql);
$wateringStmt->bind_param("i", $plantId);
$wateringStmt->execute();
$wateringResult = $wateringStmt->get_result();
$watering = $wateringResult->fetch_assoc();

// Get care instructions
$careSql = "SELECT * FROM care_instructions WHERE plant_id = ?";
$careStmt = $conn->prepare($careSql);
$careStmt->bind_param("i", $plantId);
$careStmt->execute();
$careResult = $careStmt->get_result();
$care = $careResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Plant - Agricultural Expert System</title>
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
              <li class="active"><a href="plants.php"><i class="fas fa-seedling"></i> Manage Plants</a></li>
              <li><a href="livestock.php"><i class="fas fa-horse"></i> Manage Livestock</a></li>
              <?php if ($_SESSION['role'] === 'admin'): ?>
              <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
              <?php endif; ?>
              <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
              <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
          </ul>
      </div>
      
      <div class="admin-content">
          <h2>View Plant: <?php echo htmlspecialchars($plant['plant_name']); ?></h2>
          
          <div class="admin-actions">
              <a href="edit_plant.php?id=<?php echo $plantId; ?>" class="btn-primary"><i class="fas fa-edit"></i> Edit Plant</a>
              <a href="plants.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back to Plants</a>
          </div>
          
          <div class="view-container">
              <div class="view-section">
                  <h3>Basic Information</h3>
                  <div class="view-row">
                      <div class="view-image">
                          <?php if (!empty($plant['image_url'])): ?>
                          <img src="<?php echo htmlspecialchars($plant['image_url']); ?>" alt="<?php echo htmlspecialchars($plant['plant_name']); ?>">
                          <?php else: ?>
                          <img src="https://via.placeholder.com/300x300?text=No+Image" alt="No Image">
                          <?php endif; ?>
                      </div>
                      <div class="view-details">
                          <div class="detail-item">
                              <span class="detail-label">Plant Name:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($plant['plant_name']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Scientific Name:</span>
                              <span class="detail-value"><em><?php echo htmlspecialchars($plant['scientific_name']); ?></em></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Category:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($plant['category']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Origin:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($plant['origin']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Difficulty Level:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($plant['difficulty_level']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Added On:</span>
                              <span class="detail-value"><?php echo date('F j, Y', strtotime($plant['created_at'])); ?></span>
                          </div>
                          <?php if ($plant['updated_at']): ?>
                          <div class="detail-item">
                              <span class="detail-label">Last Updated:</span>
                              <span class="detail-value"><?php echo date('F j, Y', strtotime($plant['updated_at'])); ?></span>
                          </div>
                          <?php endif; ?>
                      </div>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Description:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($plant['description'])); ?></div>
                  </div>
              </div>
              
              <div class="view-section">
                  <h3>Planting Conditions</h3>
                  <div class="view-grid">
                      <div class="detail-item">
                          <span class="detail-label">Soil Type:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($conditions['soil_type']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Light Requirements:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($conditions['light_requirements']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Temperature Range:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($conditions['temperature_range']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Humidity Level:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($conditions['humidity_level']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Planting Season:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($conditions['planting_season']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Planting Depth:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($conditions['planting_depth']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Spacing:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($conditions['spacing']); ?></span>
                      </div>
                  </div>
              </div>
              
              <div class="view-section">
                  <h3>Watering Schedule</h3>
                  <div class="view-grid">
                      <div class="detail-item">
                          <span class="detail-label">Frequency:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($watering['frequency']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Amount:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($watering['amount']); ?></span>
                      </div>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Special Instructions:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($watering['special_instructions'])); ?></div>
                  </div>
              </div>
              
              <div class="view-section">
                  <h3>Care Instructions</h3>
                  <div class="detail-item full-width">
                      <span class="detail-label">Fertilizing:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($care['fertilizing'])); ?></div>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Pruning:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($care['pruning'])); ?></div>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Pest Control:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($care['pest_control'])); ?></div>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Disease Prevention:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($care['disease_prevention'])); ?></div>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Special Care:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($care['special_care'])); ?></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</body>
</html>

