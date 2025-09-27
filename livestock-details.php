<?php
// Start session
session_start();

// Include database connection
require_once 'includes/db_connect.php';

// Check if database is set up
$checkTablesSql = "SHOW TABLES LIKE 'livestock'";
$result = $conn->query($checkTablesSql);
$databaseSetup = $result->num_rows > 0;

// Redirect to install page if database is not set up
if (!$databaseSetup) {
    header("Location: install.php");
    exit;
}

// Get livestock ID from URL
$livestockId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If no ID provided, redirect to homepage
if ($livestockId <= 0) {
    header("Location: index.php");
    exit;
}

// Get livestock categories for breadcrumb navigation
$categoriesSql = "SELECT DISTINCT category FROM livestock ORDER BY category";
$categoriesResult = $conn->query($categoriesSql);
$livestockCategories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $livestockCategories[] = $row['category'];
}

// Get livestock details
$animalSql = "SELECT * FROM livestock WHERE livestock_id = ?";
$animalStmt = $conn->prepare($animalSql);
$animalStmt->bind_param("i", $livestockId);
$animalStmt->execute();
$animalResult = $animalStmt->get_result();

// If livestock not found, redirect to homepage
if ($animalResult->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$animal = $animalResult->fetch_assoc();

// Get vet WhatsApp number from settings
$whatsappSql = "SELECT setting_value FROM settings WHERE setting_key = 'vet_whatsapp_number'";
$whatsappResult = $conn->query($whatsappSql);
$whatsappNumber = $whatsappResult && $whatsappResult->num_rows > 0 ? 
                $whatsappResult->fetch_assoc()['setting_value'] : 
                '2348012345678';

// Add WhatsApp number to animal data
$animal['vet_whatsapp'] = $whatsappNumber;

// Get care information
$careSql = "SELECT * FROM livestock_care WHERE livestock_id = ?";
$careStmt = $conn->prepare($careSql);
$careStmt->bind_param("i", $livestockId);
$careStmt->execute();
$careResult = $careStmt->get_result();

// Check if care data exists
if ($careResult->num_rows === 0) {
    // Create default care data if none exists
    $defaultDailyRoutine = json_encode(["Provide fresh water daily", "Check for signs of illness", "Clean feeding areas"]);
    $defaultHousing = json_encode(["Provide shelter from rain and sun", "Ensure good ventilation", "Maintain clean bedding"]);
    $defaultFeeding = json_encode(["Feed high-quality feed", "Provide appropriate amounts based on age and weight", "Ensure access to clean water"]);

    $insertCareSql = "INSERT INTO livestock_care (livestock_id, daily_routine, housing, feeding) VALUES (?, ?, ?, ?)";
    $insertCareStmt = $conn->prepare($insertCareSql);
    $insertCareStmt->bind_param("isss", $livestockId, $defaultDailyRoutine, $defaultHousing, $defaultFeeding);
    $insertCareStmt->execute();

    $care = [
        'daily_routine' => json_decode($defaultDailyRoutine),
        'housing' => json_decode($defaultHousing),
        'feeding' => json_decode($defaultFeeding)
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
    $defaultBreeding = [
        'season' => 'Year-round',
        'gestation_period' => 'Varies by species',
        'litter_size' => 'Varies by species',
        'description' => 'Breeding information for this livestock is not fully documented yet. Please consult with a local veterinarian for specific breeding advice.'
    ];

    $insertBreedingSql = "INSERT INTO livestock_breeding (livestock_id, season, gestation_period, litter_size, description) VALUES (?, ?, ?, ?, ?)";
    $insertBreedingStmt = $conn->prepare($insertBreedingSql);
    $insertBreedingStmt->bind_param("issss", $livestockId, $defaultBreeding['season'], $defaultBreeding['gestation_period'], $defaultBreeding['litter_size'], $defaultBreeding['description']);
    $insertBreedingStmt->execute();

    $breeding = $defaultBreeding;
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
    // Create a default disease entry if none exists
    $defaultDiseaseName = "Common Health Issues";
    $defaultDiseaseDesc = "This livestock may be susceptible to various health issues. Regular health checks are recommended.";
    $defaultPrevention = json_encode(["Maintain clean living conditions", "Provide proper nutrition", "Implement regular health checks", "Consult with a veterinarian for vaccination schedules"]);

    $insertDiseaseSql = "INSERT INTO livestock_diseases (livestock_id, disease_name, description, prevention) VALUES (?, ?, ?, ?)";
    $insertDiseaseStmt = $conn->prepare($insertDiseaseSql);
    $insertDiseaseStmt->bind_param("isss", $livestockId, $defaultDiseaseName, $defaultDiseaseDesc, $defaultPrevention);
    $insertDiseaseStmt->execute();

    $diseases[] = [
        'name' => $defaultDiseaseName,
        'description' => $defaultDiseaseDesc,
        'prevention' => json_decode($defaultPrevention)
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

// Get related livestock (same category)
$relatedSql = "SELECT livestock_id, animal_name, image_url FROM livestock WHERE category = ? AND livestock_id != ? LIMIT 4";
$relatedStmt = $conn->prepare($relatedSql);
$relatedStmt->bind_param("si", $animal['category'], $livestockId);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();
$relatedLivestock = [];
while ($row = $relatedResult->fetch_assoc()) {
    $relatedLivestock[] = $row;
}

// Close database connections
$animalStmt->close();
$careStmt->close();
$breedingStmt->close();
$diseasesStmt->close();
$relatedStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($animal['animal_name']); ?> - Livestock Expert System</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">  rel="stylesheet">
    <meta property="og:title" content="<?php echo htmlspecialchars($animal['animal_name']); ?> - Livestock Expert System">
    <meta property="og:description" content="<?php echo htmlspecialchars(substr($animal['description'], 0, 150) . '...'); ?>">
    <?php if (!empty($animal['image_url'])): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($animal['image_url']); ?>">
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
                <li><a href="index.php#plants">Plants</a></li>
                <li><a href="index.php#livestock" class="active">Livestock</a></li>
                <li><a href="admin/">Admin</a></li>
            </ul>
        </nav>

        <div class="breadcrumb">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#livestock">Livestock</a></li>
                <?php if (!empty($animal['category'])): ?>
                <li><a href="index.php?livestock_category=<?php echo urlencode(strtolower($animal['category'])); ?>"><?php echo htmlspecialchars($animal['category']); ?></a></li>
                <?php endif; ?>
                <li class="active"><?php echo htmlspecialchars($animal['animal_name']); ?></li>
            </ul>
        </div>

        <div class="details-container">
            <div class="livestock-details-page">
                <div class="livestock-details-header">
                    <div class="livestock-details-image">
                        <img src="<?php echo !empty($animal['image_url']) ? htmlspecialchars($animal['image_url']) : 'images/placeholder-livestock.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($animal['animal_name']); ?>" 
                             onerror="this.src='https://source.unsplash.com/featured/?<?php echo urlencode($animal['animal_name']); ?>'">
                    </div>
                    <div class="livestock-details-info">
                        <h1><?php echo htmlspecialchars($animal['animal_name']); ?></h1>
                        <span class="livestock-details-category"><?php echo htmlspecialchars($animal['category']); ?></span>
                        
                        <div class="livestock-details-meta">
                            <div class="meta-item"><strong>Origin:</strong> <?php echo htmlspecialchars($animal['origin']); ?></div>
                            <div class="meta-item"><strong>Lifespan:</strong> <?php echo htmlspecialchars($animal['lifespan']); ?></div>
                            <div class="meta-item"><strong>Size:</strong> <?php echo htmlspecialchars($animal['size']); ?></div>
                        </div>
                        
                        <div class="livestock-details-description">
                            <p><?php echo nl2br(htmlspecialchars($animal['description'])); ?></p>
                        </div>
                        
                        <div class="share-buttons">
                            <a href="https://wa.me/?text=<?php echo urlencode('Check out this livestock: ' . $animal['animal_name'] . ' - ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="share-btn whatsapp" target="_blank">
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
                        <button class="tab-button" data-tab="feeding">Feeding</button>
                        <button class="tab-button" data-tab="breeding">Breeding</button>
                        <button class="tab-button" data-tab="health">Health & Diseases</button>
                    </div>
                    
                    <div id="care-tab" class="tab-content active">
                        <div class="care-steps">
                            <h4>Daily Care Routine</h4>
                            <ul class="step-list">
                                <?php foreach ($care['daily_routine'] as $step): ?>
                                <li><?php echo htmlspecialchars($step); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="care-steps">
                            <h4>Housing Requirements</h4>
                            <ul class="step-list">
                                <?php foreach ($care['housing'] as $step): ?>
                                <li><?php echo htmlspecialchars($step); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div id="feeding-tab" class="tab-content">
                        <div class="feeding-steps">
                            <h4>Feeding Guide</h4>
                            <ul class="step-list">
                                <?php foreach ($care['feeding'] as $step): ?>
                                <li><?php echo htmlspecialchars($step); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div id="breeding-tab" class="tab-content">
                        <div class="breeding-info">
                            <h4>Breeding Information</h4>
                            <div class="breeding-meta">
                                <div class="breeding-meta-item"><strong>Breeding Season:</strong> <?php echo htmlspecialchars($breeding['season']); ?></div>
                                <div class="breeding-meta-item"><strong>Gestation Period:</strong> <?php echo htmlspecialchars($breeding['gestation_period']); ?></div>
                                <div class="breeding-meta-item"><strong>Litter Size:</strong> <?php echo htmlspecialchars($breeding['litter_size']); ?></div>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($breeding['description'])); ?></p>
                        </div>
                    </div>
                    
                    <div id="health-tab" class="tab-content">
                        <div class="diseases-list">
                            <?php foreach ($diseases as $disease): ?>
                            <div class="disease-item">
                                <h4><?php echo htmlspecialchars($disease['name']); ?></h4>
                                <p><?php echo htmlspecialchars($disease['description']); ?></p>
                                <div class="prevention-tips">
                                    <h5>Prevention Tips</h5>
                                    <ul>
                                        <?php foreach ($disease['prevention'] as $tip): ?>
                                        <li><?php echo htmlspecialchars($tip); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="vet-contact">
                            <div class="vet-contact-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="vet-contact-info">
                                <h4>Need Veterinary Assistance?</h4>
                                <p>Contact our expert veterinarians for immediate help with any health concerns.</p>
                                <a href="https://wa.me/<?php echo htmlspecialchars($animal['vet_whatsapp']); ?>" class="whatsapp-button" target="_blank">
                                    <i class="fab fa-whatsapp"></i> Contact Vet on WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($relatedLivestock)): ?>
                <div class="related-livestock">
                    <h3>Related Livestock</h3>
                    <div class="related-grid">
                        <?php foreach ($relatedLivestock as $related): ?>
                        <a href="livestock-details.php?id=<?php echo $related['livestock_id']; ?>" class="related-card">
                            <div class="related-image">
                                <img src="<?php echo !empty($related['image_url']) ? htmlspecialchars($related['image_url']) : 'images/placeholder-livestock.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($related['animal_name']); ?>" 
                                     onerror="this.src='https://source.unsplash.com/featured/?<?php echo urlencode($related['animal_name']); ?>'">
                            </div>
                            <div class="related-name">
                                <?php echo htmlspecialchars($related['animal_name']); ?>
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

