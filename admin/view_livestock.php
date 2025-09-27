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

// Get livestock ID from request
$livestockId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($livestockId <= 0) {
  header("Location: livestock.php");
  exit;
}

// Get livestock details
$animalSql = "SELECT * FROM livestock WHERE livestock_id = ?";
$animalStmt = $conn->prepare($animalSql);
$animalStmt->bind_param("i", $livestockId);
$animalStmt->execute();
$animalResult = $animalStmt->get_result();

if ($animalResult->num_rows === 0) {
  header("Location: livestock.php");
  exit;
}

$animal = $animalResult->fetch_assoc();

// Get care information
$careSql = "SELECT * FROM livestock_care WHERE livestock_id = ?";
$careStmt = $conn->prepare($careSql);
$careStmt->bind_param("i", $livestockId);
$careStmt->execute();
$careResult = $careStmt->get_result();

// Check if care data exists
if ($careResult->num_rows === 0) {
  // Create default care data
  $care = [
    'daily_routine' => ["Provide fresh water daily", "Check for signs of illness", "Clean feeding areas"],
    'housing' => ["Provide shelter from rain and sun", "Ensure good ventilation", "Maintain clean bedding"],
    'feeding' => ["Feed high-quality feed", "Provide appropriate amounts based on age and weight", "Ensure access to clean water"]
  ];
} else {
  $careData = $careResult->fetch_assoc();
  // Process care data
  $care = [
    'daily_routine' => json_decode($careData['daily_routine']),
    'housing' => json_decode($careData['housing']),
    'feeding' => json_decode($careData['feeding'])
  ];
}

// Get breeding information
$breedingSql = "SELECT * FROM livestock_breeding WHERE livestock_id = ?";
$breedingStmt = $conn->prepare($breedingSql);
$breedingStmt->bind_param("i", $livestockId);
$breedingStmt->execute();
$breedingResult = $breedingStmt->get_result();

// Check if breeding data exists
if ($breedingResult->num_rows === 0) {
  // Create default breeding data
  $breeding = [
    'season' => 'Year-round',
    'gestation_period' => 'Varies by species',
    'litter_size' => 'Varies by species',
    'description' => 'Breeding information for this livestock is not fully documented yet.'
  ];
} else {
  $breeding = $breedingResult->fetch_assoc();
}

// Get diseases information
$diseasesSql = "SELECT * FROM livestock_diseases WHERE livestock_id = ?";
$diseasesStmt = $conn->prepare($diseasesSql);
$diseasesStmt->bind_param("i", $livestockId);
$diseasesStmt->execute();
$diseasesResult = $diseasesStmt->get_result();

$diseases = [];
if ($diseasesResult->num_rows === 0) {
  // Create a default disease entry
  $diseases[] = [
    'name' => "Common Health Issues",
    'description' => "This livestock may be susceptible to various health issues. Regular health checks are recommended.",
    'prevention' => ["Maintain clean living conditions", "Provide proper nutrition", "Implement regular health checks", "Consult with a veterinarian for vaccination schedules"]
  ];
} else {
  while ($disease = $diseasesResult->fetch_assoc()) {
    $diseases[] = [
      'name' => $disease['disease_name'],
      'description' => $disease['description'],
      'prevention' => json_decode($disease['prevention'])
    ];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Livestock - Agricultural Expert System</title>
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
              <li class="active"><a href="livestock.php"><i class="fas fa-horse"></i> Manage Livestock</a></li>
              <?php if ($_SESSION['role'] === 'admin'): ?>
              <li><a href="users.php"><i class="fas fa-users"></i> Manage Users</a></li>
              <?php endif; ?>
              <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
              <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
          </ul>
      </div>
      
      <div class="admin-content">
          <h2>View Livestock: <?php echo htmlspecialchars($animal['animal_name']); ?></h2>
          
          <div class="admin-actions">
              <a href="edit_livestock.php?id=<?php echo $livestockId; ?>" class="btn-primary"><i class="fas fa-edit"></i> Edit Livestock</a>
              <a href="livestock.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back to Livestock</a>
          </div>
          
          <div class="view-container">
              <div class="view-section">
                  <h3>Basic Information</h3>
                  <div class="view-row">
                      <div class="view-image">
                          <?php if (!empty($animal['image_url'])): ?>
                          <img src="<?php echo htmlspecialchars($animal['image_url']); ?>" alt="<?php echo htmlspecialchars($animal['animal_name']); ?>">
                          <?php else: ?>
                          <img src="https://via.placeholder.com/300x300?text=No+Image" alt="No Image">
                          <?php endif; ?>
                      </div>
                      <div class="view-details">
                          <div class="detail-item">
                              <span class="detail-label">Animal Name:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($animal['animal_name']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Category:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($animal['category']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Origin:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($animal['origin']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Lifespan:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($animal['lifespan']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Size:</span>
                              <span class="detail-value"><?php echo htmlspecialchars($animal['size']); ?></span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Added On:</span>
                              <span class="detail-value"><?php echo date('F j, Y', strtotime($animal['created_at'])); ?></span>
                          </div>
                          <?php if ($animal['updated_at']): ?>
                          <div class="detail-item">
                              <span class="detail-label">Last Updated:</span>
                              <span class="detail-value"><?php echo date('F j, Y', strtotime($animal['updated_at'])); ?></span>
                          </div>
                          <?php endif; ?>
                      </div>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Description:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($animal['description'])); ?></div>
                  </div>
              </div>
              
              <div class="view-section">
                  <h3>Care Information</h3>
                  <div class="detail-item full-width">
                      <span class="detail-label">Daily Routine:</span>
                      <ul class="detail-list">
                          <?php foreach ($care['daily_routine'] as $routine): ?>
                          <li><?php echo htmlspecialchars($routine); ?></li>
                          <?php endforeach; ?>
                      </ul>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Housing Requirements:</span>
                      <ul class="detail-list">
                          <?php foreach ($care['housing'] as $housing): ?>
                          <li><?php echo htmlspecialchars($housing); ?></li>
                          <?php endforeach; ?>
                      </ul>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Feeding Guide:</span>
                      <ul class="detail-list">
                          <?php foreach ($care['feeding'] as $feeding): ?>
                          <li><?php echo htmlspecialchars($feeding); ?></li>
                          <?php endforeach; ?>
                      </ul>
                  </div>
              </div>
              
              <div class="view-section">
                  <h3>Breeding Information</h3>
                  <div class="view-grid">
                      <div class="detail-item">
                          <span class="detail-label">Breeding Season:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($breeding['season']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Gestation Period:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($breeding['gestation_period']); ?></span>
                      </div>
                      <div class="detail-item">
                          <span class="detail-label">Litter Size:</span>
                          <span class="detail-value"><?php echo htmlspecialchars($breeding['litter_size']); ?></span>
                      </div>
                  </div>
                  <div class="detail-item full-width">
                      <span class="detail-label">Description:</span>
                      <div class="detail-text"><?php echo nl2br(htmlspecialchars($breeding['description'])); ?></div>
                  </div>
              </div>
              
              <div class="view-section">
                  <h3>Health & Diseases</h3>
                  <?php foreach ($diseases as $disease): ?>
                  <div class="disease-item">
                      <h4><?php echo htmlspecialchars($disease['name']); ?></h4>
                      <p><?php echo nl2br(htmlspecialchars($disease['description'])); ?></p>
                      <div class="prevention-tips">
                          <h5>Prevention Tips:</h5>
                          <ul>
                              <?php foreach ($disease['prevention'] as $tip): ?>
                              <li><?php echo htmlspecialchars($tip); ?></li>
                              <?php endforeach; ?>
                          </ul>
                      </div>
                  </div>
                  <?php endforeach; ?>
              </div>
          </div>
      </div>
  </div>
</body>
</html>
