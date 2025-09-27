<?php
// Start session
session_start();

// Include database connection
require_once 'includes/db_connect.php';

// Check if database is set up
$checkTablesSql = "SHOW TABLES LIKE 'plants'";
$result = $conn->query($checkTablesSql);
$databaseSetup = $result->num_rows > 0;

// Redirect to install page if database is not set up
if (!$databaseSetup) {
    header("Location: install.php");
    exit;
}

// Get plant ID from URL
$plantId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If no ID provided, redirect to homepage
if ($plantId <= 0) {
    header("Location: index.php");
    exit;
}

// Get plant categories for breadcrumb navigation
$categoriesSql = "SELECT DISTINCT category FROM plants ORDER BY category";
$categoriesResult = $conn->query($categoriesSql);
$plantCategories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $plantCategories[] = $row['category'];
}

// Get plant details
$plantSql = "SELECT * FROM plants WHERE plant_id = ?";
$plantStmt = $conn->prepare($plantSql);
$plantStmt->bind_param("i", $plantId);
$plantStmt->execute();
$plantResult = $plantStmt->get_result();

// If plant not found, redirect to homepage
if ($plantResult->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$plant = $plantResult->fetch_assoc();

// Get planting conditions
$conditionsSql = "SELECT * FROM planting_conditions WHERE plant_id = ?";
$conditionsStmt = $conn->prepare($conditionsSql);
$conditionsStmt->bind_param("i", $plantId);
$conditionsStmt->execute();
$conditionsResult = $conditionsStmt->get_result();

// Check if conditions data exists
if ($conditionsResult->num_rows === 0) {
    // Create default conditions data
    $conditions = [
        'soil_type' => 'Information not available',
        'light_requirements' => 'Information not available',
        'temperature_range' => 'Information not available',
        'humidity_level' => 'Information not available',
        'planting_season' => 'Information not available',
        'planting_depth' => 'Information not available',
        'spacing' => 'Information not available'
    ];
} else {
    $conditions = $conditionsResult->fetch_assoc();
}

// Get watering schedule
$wateringSql = "SELECT * FROM watering_schedule WHERE plant_id = ?";
$wateringStmt = $conn->prepare($wateringSql);
$wateringStmt->bind_param("i", $plantId);
$wateringStmt->execute();
$wateringResult = $wateringStmt->get_result();

// Check if watering data exists
if ($wateringResult->num_rows === 0) {
    // Create default watering data
    $watering = [
        'frequency' => 'Information not available',
        'amount' => 'Information not available',
        'special_instructions' => 'Information not available'
    ];
} else {
    $watering = $wateringResult->fetch_assoc();
}

// Get care instructions
$careSql = "SELECT * FROM care_instructions WHERE plant_id = ?";
$careStmt = $conn->prepare($careSql);
$careStmt->bind_param("i", $plantId);
$careStmt->execute();
$careResult = $careStmt->get_result();

// Check if care data exists
if ($careResult->num_rows === 0) {
    // Create default care data
    $care = [
        'fertilizing' => 'Information not available',
        'pruning' => 'Information not available',
        'pest_control' => 'Information not available',
        'disease_prevention' => 'Information not available',
        'special_care' => 'Information not available'
    ];
} else {
    $care = $careResult->fetch_assoc();
}

// Get related plants (same category)
$relatedSql = "SELECT plant_id, plant_name, image_url FROM plants WHERE category = ? AND plant_id != ? LIMIT 4";
$relatedStmt = $conn->prepare($relatedSql);
$relatedStmt->bind_param("si", $plant['category'], $plantId);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();
$relatedPlants = [];
while ($row = $relatedResult->fetch_assoc()) {
    $relatedPlants[] = $row;
}

// Generate difficulty class
$difficultyClass = $plant['difficulty_level'] ? strtolower($plant['difficulty_level']) : "moderate";

// Function to generate to-do list
function generateTodoList($plant, $conditions, $watering, $care) {
    $currentMonth = date('F');
    $currentSeason = getSeason();
    
    $todoItems = '';
    
    // Planting tasks
    if (isset($conditions['planting_season']) && strpos($conditions['planting_season'], $currentSeason) !== false) {
        $todoItems .= createTodoItem(
            "seedling",
            "Plant Your " . $plant['plant_name'],
            "Now is a great time to plant your {$plant['plant_name']}! Use {$conditions['soil_type']} and plant at a depth of {$conditions['planting_depth']}."
        );
    }
    
    // Watering tasks
    $todoItems .= createTodoItem(
        "tint",
        "Watering Schedule",
        "Water your {$plant['plant_name']} " . (isset($watering['frequency']) ? strtolower($watering['frequency']) : "regularly") . ". " . ($watering['special_instructions'] ?? "")
    );
    
    // Fertilizing tasks
    if (isset($care['fertilizing']) && stripos($care['fertilizing'], $currentSeason) !== false) {
        $todoItems .= createTodoItem(
            "leaf",
            "Fertilize Your Plant",
            "It's time to fertilize your {$plant['plant_name']}. {$care['fertilizing']}"
        );
    }
    
    // Pruning tasks
    if (isset($care['pruning']) && (stripos($care['pruning'], $currentSeason) !== false || stripos($care['pruning'], $currentMonth) !== false)) {
        $todoItems .= createTodoItem("cut", "Prune Your Plant", "{$care['pruning']}");
    }
    
    // Pest control tasks
    $todoItems .= createTodoItem(
        "bug",
        "Monitor for Pests",
        "For {$plant['plant_name']}: " . ($care['pest_control'] ?? "Regularly check for pests and treat as needed.")
    );
    
    // Special care tasks
    $todoItems .= createTodoItem(
        "heart",
        "Special Care",
        $care['special_care'] ?? "Give your plant extra attention during extreme weather conditions."
    );
    
    return $todoItems;
}

// Helper function to create a to-do item
function createTodoItem($icon, $title, $description) {
    return "
        <div class=\"todo-item\">
            <div class=\"todo-icon\">
                <i class=\"fas fa-{$icon}\"></i>
            </div>
            <div class=\"todo-content\">
                <h4>{$title}</h4>
                <p>{$description}</p>
            </div>
        </div>
    ";
}

// Helper function to get current season
function getSeason() {
    $month = date('n');
    
    if ($month >= 3 && $month <= 5) return "Spring";
    if ($month >= 6 && $month <= 8) return "Summer";
    if ($month >= 9 && $month <= 11) return "Fall";
    return "Winter";
}

// Generate to-do list
$todoItems = generateTodoList($plant, $conditions, $watering, $care);

// Close database connections
$plantStmt->close();
$conditionsStmt->close();
$wateringStmt->close();
$careStmt->close();
$relatedStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($plant['plant_name']); ?> - Plant Expert System</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta property="og:title" content="<?php echo htmlspecialchars($plant['plant_name']); ?> - Plant Expert System">
    <meta property="og:description" content="<?php echo htmlspecialchars(substr($plant['description'], 0, 150) . '...'); ?>">
    <?php if (!empty($plant['image_url'])): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($plant['image_url']); ?>">
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-leaf"></i>
                <h1>Agricultural Expert System</h1>
            </div>
            <p>Your comprehensive guide to plant and livestock care in Nigeria</p>
        </header>

        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#plants" class="active">Plants</a></li>
                <li><a href="index.php#livestock">Livestock</a></li>
                <li><a href="admin/">Admin</a></li>
            </ul>
        </nav>

        <div class="breadcrumb">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#plants">Plants</a></li>
                <?php if (!empty($plant['category'])): ?>
                <li><a href="index.php?plant_category=<?php echo urlencode(strtolower($plant['category'])); ?>"><?php echo htmlspecialchars($plant['category']); ?></a></li>
                <?php endif; ?>
                <li class="active"><?php echo htmlspecialchars($plant['plant_name']); ?></li>
            </ul>
        </div>

        <div class="details-container">
            <div class="plant-details-page">
                <div class="plant-header">
                    <div class="plant-image-container">
                        <img src="<?php echo !empty($plant['image_url']) ? htmlspecialchars($plant['image_url']) : 'images/placeholder-plant.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($plant['plant_name']); ?>" 
                             class="plant-image" 
                             onerror="this.src='https://source.unsplash.com/featured/?<?php echo urlencode($plant['plant_name'] . " plant"); ?>'">
                    </div>
                    <div class="plant-details">
                        <h1><?php echo htmlspecialchars($plant['plant_name']); ?></h1>
                        <p class="scientific-name"><?php echo htmlspecialchars($plant['scientific_name']); ?></p>
                        
                        <div class="plant-meta">
                            <span class="difficulty <?php echo $difficultyClass; ?>">
                                <?php echo htmlspecialchars($plant['difficulty_level'] ?? 'Moderate'); ?> Care
                            </span>
                            <?php if (!empty($plant['category'])): ?>
                            <span class="category">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($plant['category']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="plant-description">
                            <p><?php echo nl2br(htmlspecialchars($plant['description'])); ?></p>
                        </div>
                        
                        <div class="share-buttons">
                            <a href="https://wa.me/?text=<?php echo urlencode('Check out this plant: ' . $plant['plant_name'] . ' - ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="share-btn whatsapp" target="_blank">
                                <i class="fab fa-whatsapp"></i> Share
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="share-btn facebook" target="_blank">
                                <i class="fab fa-facebook-f"></i> Share
                            </a>
                            <a href="#" class="share-btn print" onclick="window.print(); return false;">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="details-tabs">
                    <div class="tabs-nav">
                        <button class="tab-button active" data-tab="care">Care Guide</button>
                        <button class="tab-button" data-tab="planting">Planting</button>
                        <button class="tab-button" data-tab="watering">Watering</button>
                        <button class="tab-button" data-tab="todo">To-Do List</button>
                    </div>
                    
                    <div id="care-tab" class="tab-content active">
                        <div class="care-section">
                            <h3><i class="fas fa-hand-holding-heart"></i> Care Instructions</h3>
                            <ul>
                                <li><strong>Fertilizing:</strong> <?php echo htmlspecialchars($care['fertilizing']); ?></li>
                                <li><strong>Pruning:</strong> <?php echo htmlspecialchars($care['pruning']); ?></li>
                                <li><strong>Pest Control:</strong> <?php echo htmlspecialchars($care['pest_control']); ?></li>
                                <li><strong>Disease Prevention:</strong> <?php echo htmlspecialchars($care['disease_prevention']); ?></li>
                                <li><strong>Special Care:</strong> <?php echo htmlspecialchars($care['special_care']); ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div id="planting-tab" class="tab-content">
                        <div class="care-section">
                            <h3><i class="fas fa-seedling"></i> Planting Conditions</h3>
                            <ul>
                                <li><strong>Soil Type:</strong> <?php echo htmlspecialchars($conditions['soil_type']); ?></li>
                                <li><strong>Light:</strong> <?php echo htmlspecialchars($conditions['light_requirements']); ?></li>
                                <li><strong>Temperature:</strong> <?php echo htmlspecialchars($conditions['temperature_range']); ?></li>
                                <li><strong>Humidity:</strong> <?php echo htmlspecialchars($conditions['humidity_level']); ?></li>
                                <li><strong>Planting Season:</strong> <?php echo htmlspecialchars($conditions['planting_season']); ?></li>
                                <li><strong>Planting Depth:</strong> <?php echo htmlspecialchars($conditions['planting_depth']); ?></li>
                                <li><strong>Spacing:</strong> <?php echo htmlspecialchars($conditions['spacing']); ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div id="watering-tab" class="tab-content">
                        <div class="care-section">
                            <h3><i class="fas fa-tint"></i> Watering Schedule</h3>
                            <ul>
                                <li><strong>Frequency:</strong> <?php echo htmlspecialchars($watering['frequency']); ?></li>
                                <li><strong>Amount:</strong> <?php echo htmlspecialchars($watering['amount']); ?></li>
                                <li><strong>Special Instructions:</strong> <?php echo htmlspecialchars($watering['special_instructions']); ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div id="todo-tab" class="tab-content">
                        <div class="todo-list">
                            <h3>Your Customized To-Do List for <?php echo htmlspecialchars($plant['plant_name']); ?></h3>
                            <div class="todo-items">
                                <?php echo $todoItems; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($relatedPlants)): ?>
                <div class="related-plants">
                    <h3>Related Plants</h3>
                    <div class="related-grid">
                        <?php foreach ($relatedPlants as $relatedPlant): ?>
                        <a href="plant-details.php?id=<?php echo $relatedPlant['plant_id']; ?>" class="related-card">
                            <div class="related-image">
                                <img src="<?php echo !empty($relatedPlant['image_url']) ? htmlspecialchars($relatedPlant['image_url']) : 'images/placeholder-plant.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($relatedPlant['plant_name']); ?>" 
                                     onerror="this.src='https://source.unsplash.com/featured/?<?php echo urlencode($relatedPlant['plant_name'] . " plant"); ?>'">
                            </div>
                            <div class="related-name">
                                <?php echo htmlspecialchars($relatedPlant['plant_name']); ?>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="farm-tools-banner">
                    <h3>Do you need farm tools or workers for your farm?</h3>
                    <p>Agros has got you covered with quality tools and reliable workers.</p>
                    <a href="#" class="farm-tools-btn" target="_blank">Click here now</a>
                </div>
                
                <div class="back-button-container">
                    <a href="javascript:history.back()" class="btn-primary"><i class="fas fa-arrow-left"></i> Back to Results</a>
                </div>
            </div>
        </div>

        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p>We provide expert information on plant and livestock care to help Nigerian farmers succeed in their agricultural endeavors.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php#plants">Plants</a></li>
                        <li><a href="index.php#livestock">Livestock</a></li>
                        <li><a href="admin/">Admin Panel</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p><i class="fas fa-envelope"></i> info@agriexpertsystem.com</p>
                    <p><i class="fas fa-phone"></i> (234) 801-234-5678</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Agricultural Expert System | Designed for Nigerian Farmers</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');
                    
                    // Update active tab button
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    // Update active tab content
                    tabContents.forEach(content => content.classList.remove('active'));
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>

