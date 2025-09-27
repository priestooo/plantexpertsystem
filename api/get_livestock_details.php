<?php
// Include database connection
require_once '../includes/db_connect.php';

// Get livestock ID from request
$livestockId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($livestockId <= 0) {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Invalid livestock ID']);
  exit;
}

// Get livestock details
$animalSql = "SELECT * FROM livestock WHERE livestock_id = ?";
$animalStmt = $conn->prepare($animalSql);
$animalStmt->bind_param("i", $livestockId);
$animalStmt->execute();
$animalResult = $animalStmt->get_result();

if ($animalResult->num_rows === 0) {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Livestock not found']);
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

// Prepare response
$response = [
  'animal' => $animal,
  'care' => $care,
  'breeding' => $breeding,
  'diseases' => $diseases
];

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close connections
$animalStmt->close();
$careStmt->close();
$breedingStmt->close();
$diseasesStmt->close();
$conn->close();
?>
