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

// Get plant ID from request
$plantId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($plantId <= 0) {
  header("Location: plants.php");
  exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get plant data
  $plantName = $_POST['plant_name'] ?? '';
  $scientificName = $_POST['scientific_name'] ?? '';
  $category = $_POST['category'] ?? '';
  $description = $_POST['description'] ?? '';
  $origin = $_POST['origin'] ?? '';
  $difficultyLevel = $_POST['difficulty_level'] ?? '';
  $imageUrl = $_POST['image_url'] ?? '';
  
  // Get planting conditions
  $soilType = $_POST['soil_type'] ?? '';
  $lightRequirements = $_POST['light_requirements'] ?? '';
  $temperatureRange = $_POST['temperature_range'] ?? '';
  $humidityLevel = $_POST['humidity_level'] ?? '';
  $plantingSeason = $_POST['planting_season'] ?? '';
  $plantingDepth = $_POST['planting_depth'] ?? '';
  $spacing = $_POST['spacing'] ?? '';
  
  // Get watering schedule
  $frequency = $_POST['frequency'] ?? '';
  $amount = $_POST['amount'] ?? '';
  $specialInstructions = $_POST['special_instructions'] ?? '';
  
  // Get care instructions
  $fertilizing = $_POST['fertilizing'] ?? '';
  $pruning = $_POST['pruning'] ?? '';
  $pestControl = $_POST['pest_control'] ?? '';
  $diseasePrevention = $_POST['disease_prevention'] ?? '';
  $specialCare = $_POST['special_care'] ?? '';
  
  // Validate required fields
  if (empty($plantName) || empty($scientificName) || empty($category)) {
    $message = "Please fill in all required fields.";
    $messageType = "error";
  } else {
    // Start transaction
    $conn->begin_transaction();
    
    try {
      // Update plant
      $plantSql = "UPDATE plants SET plant_name = ?, scientific_name = ?, category = ?, description = ?, 
                  origin = ?, difficulty_level = ?, image_url = ?, updated_at = NOW() 
                  WHERE plant_id = ?";
      $plantStmt = $conn->prepare($plantSql);
      $plantStmt->bind_param("sssssssi", $plantName, $scientificName, $category, $description, $origin, $difficultyLevel, $imageUrl, $plantId);
      $plantStmt->execute();
      
      // Update planting conditions
      $conditionsSql = "UPDATE planting_conditions SET soil_type = ?, light_requirements = ?, temperature_range = ?, 
                        humidity_level = ?, planting_season = ?, planting_depth = ?, spacing = ? 
                        WHERE plant_id = ?";
      $conditionsStmt = $conn->prepare($conditionsSql);
      $conditionsStmt->bind_param("sssssssi", $soilType, $lightRequirements, $temperatureRange, $humidityLevel, $plantingSeason, $plantingDepth, $spacing, $plantId);
      $conditionsStmt->execute();
      
      // Update watering schedule
      $wateringSql = "UPDATE watering_schedule SET frequency = ?, amount = ?, special_instructions = ? 
                      WHERE plant_id = ?";
      $wateringStmt = $conn->prepare($wateringSql);
      $wateringStmt->bind_param("sssi", $frequency, $amount, $specialInstructions, $plantId);
      $wateringStmt->execute();
      
      // Update care instructions
      $careSql = "UPDATE care_instructions SET fertilizing = ?, pruning = ?, pest_control = ?, 
                  disease_prevention = ?, special_care = ? 
                  WHERE plant_id = ?";
      $careStmt = $conn->prepare($careSql);
      $careStmt->bind_param("sssssi", $fertilizing, $pruning, $pestControl, $diseasePrevention, $specialCare, $plantId);
      $careStmt->execute();
      
      // Commit transaction
      $conn->commit();
      
      $message = "Plant updated successfully!";
      $messageType = "success";
    } catch (Exception $e) {
      // Rollback transaction on error
      $conn->rollback();
      
      $message = "Error updating plant: " . $e->getMessage();
      $messageType = "error";
    }
  }
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

// Get categories for dropdown
$categoriesSql = "SELECT DISTINCT category FROM plants ORDER BY category";
$categoriesResult = $conn->query($categoriesSql);
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
  $categories[] = $row['category'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Plant - Agricultural Expert System</title>
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
          <h2>Edit Plant: <?php echo htmlspecialchars($plant['plant_name']); ?></h2>
          
          <?php if (!empty($message)): ?>
              <div class="alert <?php echo $messageType; ?>">
                  <?php echo $message; ?>
              </div>
          <?php endif; ?>
          
          <form class="admin-form" method="post" action="">
              <div class="form-section">
                  <h3>Basic Information</h3>
                  <div class="form-row">
                      <div class="form-group">
                          <label for="plant_name">Plant Name <span class="required">*</span></label>
                          <input type="text" id="plant_name" name="plant_name" value="<?php echo htmlspecialchars($plant['plant_name']); ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="scientific_name">Scientific Name <span class="required">*</span></label>
                          <input type="text" id="scientific_name" name="scientific_name" value="<?php echo htmlspecialchars($plant['scientific_name']); ?>" required>
                      </div>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="category">Category <span class="required">*</span></label>
                          <select id="category" name="category" required>
                              <?php foreach ($categories as $cat): ?>
                              <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $cat === $plant['category'] ? 'selected' : ''; ?>>
                                  <?php echo htmlspecialchars($cat); ?>
                              </option>
                              <?php endforeach; ?>
                              <option value="other">Other (New Category)</option>
                          </select>
                      </div>
                      <div class="form-group" id="new-category-group" style="display: none;">
                          <label for="new_category">New Category <span class="required">*</span></label>
                          <input type="text" id="new_category" name="new_category">
                      </div>
                      <div class="form-group">
                          <label for="difficulty_level">Difficulty Level <span class="required">*</span></label>
                          <select id="difficulty_level" name="difficulty_level" required>
                              <option value="Easy" <?php echo $plant['difficulty_level'] === 'Easy' ? 'selected' : ''; ?>>Easy</option>
                              <option value="Moderate" <?php echo $plant['difficulty_level'] === 'Moderate' ? 'selected' : ''; ?>>Moderate</option>
                              <option value="Difficult" <?php echo $plant['difficulty_level'] === 'Difficult' ? 'selected' : ''; ?>>Difficult</option>
                          </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label for="description">Description <span class="required">*</span></label>
                      <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($plant['description']); ?></textarea>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="origin">Origin</label>
                          <input type="text" id="origin" name="origin" value="<?php echo htmlspecialchars($plant['origin']); ?>">
                      </div>
                      <div class="form-group">
                          <label for="image_url">Image URL</label>
                          <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($plant['image_url']); ?>" placeholder="Enter image URL or leave blank for default">
                      </div>
                  </div>
                  
                  <?php if (!empty($plant['image_url'])): ?>
                  <div class="form-group">
                      <label>Current Image:</label>
                      <div class="image-preview">
                          <img src="<?php echo htmlspecialchars($plant['image_url']); ?>" alt="<?php echo htmlspecialchars($plant['plant_name']); ?>">
                      </div>
                  </div>
                  <?php endif; ?>
              </div>
              
              <div class="form-section">
                  <h3>Planting Conditions</h3>
                  <div class="form-row">
                      <div class="form-group">
                          <label for="soil_type">Soil Type <span class="required">*</span></label>
                          <input type="text" id="soil_type" name="soil_type" value="<?php echo htmlspecialchars($conditions['soil_type']); ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="light_requirements">Light Requirements <span class="required">*</span></label>
                          <input type="text" id="light_requirements" name="light_requirements" value="<?php echo htmlspecialchars($conditions['light_requirements']); ?>" required>
                      </div>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="temperature_range">Temperature Range <span class="required">*</span></label>
                          <input type="text" id="temperature_range" name="temperature_range" value="<?php echo htmlspecialchars($conditions['temperature_range']); ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="humidity_level">Humidity Level <span class="required">*</span></label>
                          <input type="text" id="humidity_level" name="humidity_level" value="<?php echo htmlspecialchars($conditions['humidity_level']); ?>" required>
                      </div>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="planting_season">Planting Season <span class="required">*</span></label>
                          <input type="text" id="planting_season" name="planting_season" value="<?php echo htmlspecialchars($conditions['planting_season']); ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="planting_depth">Planting Depth <span class="required">*</span></label>
                          <input type="text" id="planting_depth" name="planting_depth" value="<?php echo htmlspecialchars($conditions['planting_depth']); ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="spacing">Spacing <span class="required">*</span></label>
                          <input type="text" id="spacing" name="spacing" value="<?php echo htmlspecialchars($conditions['spacing']); ?>" required>
                      </div>
                  </div>
              </div>
              
              <div class="form-section">
                  <h3>Watering Schedule</h3>
                  <div class="form-row">
                      <div class="form-group">
                          <label for="frequency">Frequency <span class="required">*</span></label>
                          <input type="text" id="frequency" name="frequency" value="<?php echo htmlspecialchars($watering['frequency']); ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="amount">Amount <span class="required">*</span></label>
                          <input type="text" id="amount" name="amount" value="<?php echo htmlspecialchars($watering['amount']); ?>" required>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label for="special_instructions">Special Instructions</label>
                      <textarea id="special_instructions" name="special_instructions" rows="3"><?php echo htmlspecialchars($watering['special_instructions']); ?></textarea>
                  </div>
              </div>
              
              <div class="form-section">
                  <h3>Care Instructions</h3>
                  <div class="form-group">
                      <label for="fertilizing">Fertilizing <span class="required">*</span></label>
                      <textarea id="fertilizing" name="fertilizing" rows="3" required><?php echo htmlspecialchars($care['fertilizing']); ?></textarea>
                  </div>
                  
                  <div class="form-group">
                      <label for="pruning">Pruning <span class="required">*</span></label>
                      <textarea id="pruning" name="pruning" rows="3" required><?php echo htmlspecialchars($care['pruning']); ?></textarea>
                  </div>
                  
                  <div class="form-group">
                      <label for="pest_control">Pest Control <span class="required">*</span></label>
                      <textarea id="pest_control" name="pest_control" rows="3" required><?php echo htmlspecialchars($care['pest_control']); ?></textarea>
                  </div>
                  
                  <div class="form-group">
                      <label for="disease_prevention">Disease Prevention <span class="required">*</span></label>
                      <textarea id="disease_prevention" name="disease_prevention" rows="3" required><?php echo htmlspecialchars($care['disease_prevention']); ?></textarea>
                  </div>
                  
                  <div class="form-group">
                      <label for="special_care">Special Care</label>
                      <textarea id="special_care" name="special_care" rows="3"><?php echo htmlspecialchars($care['special_care']); ?></textarea>
                  </div>
              </div>
              
              <div class="form-actions">
                  <button type="submit" class="btn-primary">Update Plant</button>
                  <a href="plants.php" class="btn-secondary">Cancel</a>
              </div>
          </form>
      </div>
  </div>
  
  <script>
      // Show/hide new category field based on selection
      document.getElementById('category').addEventListener('change', function() {
          const newCategoryGroup = document.getElementById('new-category-group');
          if (this.value === 'other') {
              newCategoryGroup.style.display = 'block';
              document.getElementById('new_category').setAttribute('required', 'required');
          } else {
              newCategoryGroup.style.display = 'none';
              document.getElementById('new_category').removeAttribute('required');
          }
      });
      
      // Form submission handling for new category
      document.querySelector('.admin-form').addEventListener('submit', function(e) {
          const categorySelect = document.getElementById('category');
          if (categorySelect.value === 'other') {
              const newCategory = document.getElementById('new_category').value.trim();
              if (newCategory) {
                  // Set the new category as the value
                  categorySelect.value = newCategory;
              }
          }
      });
  </script>
</body>
</html>

