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
      // Insert plant
      $plantSql = "INSERT INTO plants (plant_name, scientific_name, category, description, origin, difficulty_level, image_url) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
      $plantStmt = $conn->prepare($plantSql);
      $plantStmt->bind_param("sssssss", $plantName, $scientificName, $category, $description, $origin, $difficultyLevel, $imageUrl);
      $plantStmt->execute();
      
      $plantId = $conn->insert_id;
      
      // Insert planting conditions
      $conditionsSql = "INSERT INTO planting_conditions (plant_id, soil_type, light_requirements, temperature_range, humidity_level, planting_season, planting_depth, spacing) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $conditionsStmt = $conn->prepare($conditionsSql);
      $conditionsStmt->bind_param("isssssss", $plantId, $soilType, $lightRequirements, $temperatureRange, $humidityLevel, $plantingSeason, $plantingDepth, $spacing);
      $conditionsStmt->execute();
      
      // Insert watering schedule
      $wateringSql = "INSERT INTO watering_schedule (plant_id, frequency, amount, special_instructions) 
                      VALUES (?, ?, ?, ?)";
      $wateringStmt = $conn->prepare($wateringSql);
      $wateringStmt->bind_param("isss", $plantId, $frequency, $amount, $specialInstructions);
      $wateringStmt->execute();
      
      // Insert care instructions
      $careSql = "INSERT INTO care_instructions (plant_id, fertilizing, pruning, pest_control, disease_prevention, special_care) 
                  VALUES (?, ?, ?, ?, ?, ?)";
      $careStmt = $conn->prepare($careSql);
      $careStmt->bind_param("isssss", $plantId, $fertilizing, $pruning, $pestControl, $diseasePrevention, $specialCare);
      $careStmt->execute();
      
      // Commit transaction
      $conn->commit();
      
      $message = "Plant added successfully!";
      $messageType = "success";
      
      // Redirect to plant list after successful addition
      header("Location: plants.php");
      exit;
    } catch (Exception $e) {
      // Rollback transaction on error
      $conn->rollback();
      
      $message = "Error adding plant: " . $e->getMessage();
      $messageType = "error";
    }
  }
}

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
  <title>Add Plant - Agricultural Expert System</title>
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
          <h2>Add New Plant</h2>
          
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
                          <input type="text" id="plant_name" name="plant_name" required>
                      </div>
                      <div class="form-group">
                          <label for="scientific_name">Scientific Name <span class="required">*</span></label>
                          <input type="text" id="scientific_name" name="scientific_name" required>
                      </div>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="category">Category <span class="required">*</span></label>
                          <select id="category" name="category" required>
                              <option value="">Select Category</option>
                              <?php foreach ($categories as $cat): ?>
                              <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
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
                              <option value="Easy">Easy</option>
                              <option value="Moderate">Moderate</option>
                              <option value="Difficult">Difficult</option>
                          </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label for="description">Description <span class="required">*</span></label>
                      <textarea id="description" name="description" rows="4" required></textarea>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="origin">Origin</label>
                          <input type="text" id="origin" name="origin" value="Nigeria">
                      </div>
                      <div class="form-group">
                          <label for="image_url">Image URL</label>
                          <input type="text" id="image_url" name="image_url" placeholder="Enter image URL or leave blank for default">
                      </div>
                  </div>
              </div>
              
              <div class="form-section">
                  <h3>Planting Conditions</h3>
                  <div class="form-row">
                      <div class="form-group">
                          <label for="soil_type">Soil Type <span class="required">*</span></label>
                          <input type="text" id="soil_type" name="soil_type" required>
                      </div>
                      <div class="form-group">
                          <label for="light_requirements">Light Requirements <span class="required">*</span></label>
                          <input type="text" id="light_requirements" name="light_requirements" required>
                      </div>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="temperature_range">Temperature Range <span class="required">*</span></label>
                          <input type="text" id="temperature_range" name="temperature_range" required>
                      </div>
                      <div class="form-group">
                          <label for="humidity_level">Humidity Level <span class="required">*</span></label>
                          <input type="text" id="humidity_level" name="humidity_level" required>
                      </div>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="planting_season">Planting Season <span class="required">*</span></label>
                          <input type="text" id="planting_season" name="planting_season" required>
                      </div>
                      <div class="form-group">
                          <label for="planting_depth">Planting Depth <span class="required">*</span></label>
                          <input type="text" id="planting_depth" name="planting_depth" required>
                      </div>
                      <div class="form-group">
                          <label for="spacing">Spacing <span class="required">*</span></label>
                          <input type="text" id="spacing" name="spacing" required>
                      </div>
                  </div>
              </div>
              
              <div class="form-section">
                  <h3>Watering Schedule</h3>
                  <div class="form-row">
                      <div class="form-group">
                          <label for="frequency">Frequency <span class="required">*</span></label>
                          <input type="text" id="frequency" name="frequency" required>
                      </div>
                      <div class="form-group">
                          <label for="amount">Amount <span class="required">*</span></label>
                          <input type="text" id="amount" name="amount" required>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label for="special_instructions">Special Instructions</label>
                      <textarea id="special_instructions" name="special_instructions" rows="3"></textarea>
                  </div>
              </div>
              
              <div class="form-section">
                  <h3>Care Instructions</h3>
                  <div class="form-group">
                      <label for="fertilizing">Fertilizing <span class="required">*</span></label>
                      <textarea id="fertilizing" name="fertilizing" rows="3" required></textarea>
                  </div>
                  
                  <div class="form-group">
                      <label for="pruning">Pruning <span class="required">*</span></label>
                      <textarea id="pruning" name="pruning" rows="3" required></textarea>
                  </div>
                  
                  <div class="form-group">
                      <label for="pest_control">Pest Control <span class="required">*</span></label>
                      <textarea id="pest_control" name="pest_control" rows="3" required></textarea>
                  </div>
                  
                  <div class="form-group">
                      <label for="disease_prevention">Disease Prevention <span class="required">*</span></label>
                      <textarea id="disease_prevention" name="disease_prevention" rows="3" required></textarea>
                  </div>
                  
                  <div class="form-group">
                      <label for="special_care">Special Care</label>
                      <textarea id="special_care" name="special_care" rows="3"></textarea>
                  </div>
              </div>
              
              <div class="form-actions">
                  <button type="submit" class="btn-primary">Add Plant</button>
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

