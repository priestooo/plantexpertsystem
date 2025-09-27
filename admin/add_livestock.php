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
  // Get livestock data
  $animalName = $_POST['animal_name'] ?? '';
  $category = $_POST['category'] ?? '';
  $description = $_POST['description'] ?? '';
  $origin = $_POST['origin'] ?? '';
  $lifespan = $_POST['lifespan'] ?? '';
  $size = $_POST['size'] ?? '';
  $imageUrl = $_POST['image_url'] ?? '';
  
  // Get care information
  $dailyRoutine = isset($_POST['daily_routine']) ? $_POST['daily_routine'] : [];
  $housing = isset($_POST['housing']) ? $_POST['housing'] : [];
  $feeding = isset($_POST['feeding']) ? $_POST['feeding'] : [];
  
  // Get breeding information
  $season = $_POST['season'] ?? '';
  $gestationPeriod = $_POST['gestation_period'] ?? '';
  $litterSize = $_POST['litter_size'] ?? '';
  $breedingDescription = $_POST['breeding_description'] ?? '';
  
  // Get disease information
  $diseaseNames = isset($_POST['disease_name']) ? $_POST['disease_name'] : [];
  $diseaseDescriptions = isset($_POST['disease_description']) ? $_POST['disease_description'] : [];
  $diseasePrevention = isset($_POST['disease_prevention']) ? $_POST['disease_prevention'] : [];
  
  // Validate required fields
  if (empty($animalName) || empty($category)) {
    $message = "Please fill in all required fields.";
    $messageType = "error";
  } else {
    // Start transaction
    $conn->begin_transaction();
    
    try {
      // Insert livestock
      $animalSql = "INSERT INTO livestock (animal_name, category, description, origin, lifespan, size, image_url) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
      $animalStmt = $conn->prepare($animalSql);
      $animalStmt->bind_param("sssssss", $animalName, $category, $description, $origin, $lifespan, $size, $imageUrl);
      $animalStmt->execute();
      
      $livestockId = $conn->insert_id;
      
      // Process daily routine, housing, and feeding arrays
      // Remove empty values
      $dailyRoutine = array_filter($dailyRoutine, function($value) { return !empty(trim($value)); });
      $housing = array_filter($housing, function($value) { return !empty(trim($value)); });
      $feeding = array_filter($feeding, function($value) { return !empty(trim($value)); });
      
      // Convert to JSON
      $dailyRoutineJson = json_encode(array_values($dailyRoutine));
      $housingJson = json_encode(array_values($housing));
      $feedingJson = json_encode(array_values($feeding));
      
      // Insert care information
      $careSql = "INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) 
                 VALUES (?, ?, ?, ?)";
      $careStmt = $conn->prepare($careSql);
      $careStmt->bind_param("isss", $livestockId, $dailyRoutineJson, $housingJson, $feedingJson);
      $careStmt->execute();
      
      // Insert breeding information
      $breedingSql = "INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) 
                     VALUES (?, ?, ?, ?, ?)";
      $breedingStmt = $conn->prepare($breedingSql);
      $breedingStmt->bind_param("issss", $livestockId, $season, $gestationPeriod, $litterSize, $breedingDescription);
      $breedingStmt->execute();
      
      // Insert diseases
      if (!empty($diseaseNames)) {
        $insertDiseaseSql = "INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) 
                            VALUES (?, ?, ?, ?)";
        $insertDiseaseStmt = $conn->prepare($insertDiseaseSql);
        
        for ($i = 0; $i < count($diseaseNames); $i++) {
          if (!empty(trim($diseaseNames[$i]))) {
            // Process prevention tips
            $preventionTips = isset($diseasePrevention[$i]) ? explode("\n", $diseasePrevention[$i]) : [];
            $preventionTips = array_map('trim', $preventionTips);
            $preventionTips = array_filter($preventionTips, function($value) { return !empty($value); });
            $preventionJson = json_encode(array_values($preventionTips));
            
            $insertDiseaseStmt->bind_param("isss", $livestockId, $diseaseNames[$i], $diseaseDescriptions[$i], $preventionJson);
            $insertDiseaseStmt->execute();
          }
        }
      }
      
      // Commit transaction
      $conn->commit();
      
      $message = "Livestock added successfully!";
      $messageType = "success";
      
      // Redirect to livestock list after successful addition
      header("Location: livestock.php");
      exit;
    } catch (Exception $e) {
      // Rollback transaction on error
      $conn->rollback();
      
      $message = "Error adding livestock: " . $e->getMessage();
      $messageType = "error";
    }
  }
}

// Get categories for dropdown
$categoriesSql = "SELECT DISTINCT category FROM livestock ORDER BY category";
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
  <title>Add Livestock - Agricultural Expert System</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    .repeatable-fields {
      margin-bottom: 20px;
    }
    .repeatable-field {
      border: 1px solid #e0e0e0;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 5px;
      background-color: #f9f9f9;
      position: relative;
    }
    .remove-field {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: #f44336;
      color: white;
      border: none;
      border-radius: 50%;
      width: 25px;
      height: 25px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .add-field {
      background-color: #4caf50;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 10px;
    }
  </style>
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
              <li class="active"><a href="livestock.php"><i class="fas fa-horse"></i> Manage Livestock</a></li>
              <?php if ($_SESSION['role'] === 'admin'): ?>
              <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
              <?php endif; ?>
              <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
              <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
          </ul>
      </div>
      
      <div class="admin-content">
          <h2>Add New Livestock</h2>
          
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
                          <label for="animal_name">Animal Name <span class="required">*</span></label>
                          <input type="text" id="animal_name" name="animal_name" required>
                      </div>
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
                  </div>
                  
                  <div class="form-group">
                      <label for="description">Description <span class="required">*</span></label>
                      <textarea id="description" name="description" rows="4" required></textarea>
                  </div>
                  
                  <div class="form-row">
                      <div class="form-group">
                          <label for="origin">Origin <span class="required">*</span></label>
                          <input type="text" id="origin" name="origin" value="Nigeria" required>
                      </div>
                      <div class="form-group">
                          <label for="lifespan">Lifespan <span class="required">*</span></label>
                          <input type="text" id="lifespan" name="lifespan" required>
                      </div>
                      <div class="form-group">
                          <label for="size">Size <span class="required">*</span></label>
                          <input type="text" id="size" name="size" required>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label for="image_url">Image URL</label>
                      <input type="text" id="image_url" name="image_url" placeholder="Enter image URL or leave blank for default">
                  </div>
              </div>
              
              <div class="form-section">
                  <h3>Care Information</h3>
                  
                  <div class="form-group">
                      <label>Daily Routine <span class="required">*</span></label>
                      <div class="repeatable-fields" id="daily-routine-fields">
                          <div class="repeatable-field">
                              <input type="text" name="daily_routine[]" placeholder="e.g., Provide fresh water daily" required>
                          </div>
                          <div class="repeatable-field">
                              <input type="text" name="daily_routine[]" placeholder="e.g., Check for signs of illness" required>
                              <button type="button" class="remove-field">&times;</button>
                          </div>
                      </div>
                      <button type="button" class="add-field" data-target="daily-routine-fields">Add Routine Item</button>
                  </div>
                  
                  <div class="form-group">
                      <label>Housing Requirements <span class="required">*</span></label>
                      <div class="repeatable-fields" id="housing-fields">
                          <div class="repeatable-field">
                              <input type="text" name="housing[]" placeholder="e.g., Provide shelter from rain and sun" required>
                          </div>
                          <div class="repeatable-field">
                              <input type="text" name="housing[]" placeholder="e.g., Ensure good ventilation" required>
                              <button type="button" class="remove-field">&times;</button>
                          </div>
                      </div>
                      <button type="button" class="add-field" data-target="housing-fields">Add Housing Requirement</button>
                  </div>
                  
                  <div class="form-group">
                      <label>Feeding Guide <span class="required">*</span></label>
                      <div class="repeatable-fields" id="feeding-fields">
                          <div class="repeatable-field">
                              <input type="text" name="feeding[]" placeholder="e.g., Feed high-quality feed" required>
                          </div>
                          <div class="repeatable-field">
                              <input type="text" name="feeding[]" placeholder="e.g., Provide appropriate amounts based on age and weight" required>
                              <button type="button" class="remove-field">&times;</button>
                          </div>
                      </div>
                      <button type="button" class="add-field" data-target="feeding-fields">Add Feeding Guideline</button>
                  </div>
              </div>
              
              <div class="form-section">
                  <h3>Breeding Information</h3>
                  <div class="form-row">
                      <div class="form-group">
                          <label for="season">Breeding Season <span class="required">*</span></label>
                          <input type="text" id="season" name="season" required>
                      </div>
                      <div class="form-group">
                          <label for="gestation_period">Gestation Period <span class="required">*</span></label>
                          <input type="text" id="gestation_period" name="gestation_period" required>
                      </div>
                      <div class="form-group">
                          <label for="litter_size">Litter Size <span class="required">*</span></label>
                          <input type="text" id="litter_size" name="litter_size" required>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label for="breeding_description">Breeding Description <span class="required">*</span></label>
                      <textarea id="breeding_description" name="breeding_description" rows="4" required></textarea>
                  </div>
              </div>
              
              <div class="form-section">
                  <h3>Health & Diseases</h3>
                  <div class="repeatable-fields" id="disease-fields">
                      <div class="repeatable-field">
                          <div class="form-group">
                              <label>Disease Name <span class="required">*</span></label>
                              <input type="text" name="disease_name[]" required>
                          </div>
                          <div class="form-group">
                              <label>Description <span class="required">*</span></label>
                              <textarea name="disease_description[]" rows="3" required></textarea>
                          </div>
                          <div class="form-group">
                              <label>Prevention Tips <span class="required">*</span> (One per line)</label>
                              <textarea name="disease_prevention[]" rows="4" placeholder="Enter one prevention tip per line" required></textarea>
                          </div>
                      </div>
                  </div>
                  <button type="button" class="add-field" data-target="disease-fields">Add Disease</button>
              </div>
              
              <div class="form-actions">
                  <button type="submit" class="btn-primary">Add Livestock</button>
                  <a href="livestock.php" class="btn-secondary">Cancel</a>
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
      
      // Handle repeatable fields
      document.addEventListener('click', function(e) {
          // Add new field
          if (e.target.classList.contains('add-field')) {
              const targetId = e.target.getAttribute('data-target');
              const container = document.getElementById(targetId);
              const fields = container.querySelectorAll('.repeatable-field');
              const lastField = fields[fields.length - 1];
              const newField = lastField.cloneNode(true);
              
              // Clear input values
              const inputs = newField.querySelectorAll('input, textarea');
              inputs.forEach(input => input.value = '');
              
              // Add remove button if not present
              if (!newField.querySelector('.remove-field')) {
                  const removeBtn = document.createElement('button');
                  removeBtn.type = 'button';
                  removeBtn.className = 'remove-field';
                  removeBtn.innerHTML = '&times;';
                  newField.appendChild(removeBtn);
              }
              
              container.appendChild(newField);
          }
          
          // Remove field
          if (e.target.classList.contains('remove-field')) {
              const field = e.target.closest('.repeatable-field');
              field.parentNode.removeChild(field);
          }
      });
  </script>
</body>
</html>
