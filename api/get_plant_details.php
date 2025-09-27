<?php
// Include database connection
require_once '../includes/db_connect.php';

// Get plant ID from request
$plantId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($plantId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid plant ID']);
    exit;
}

// Get plant details
$plantSql = "SELECT * FROM plants WHERE plant_id = ?";
$plantStmt = $conn->prepare($plantSql);
$plantStmt->bind_param("i", $plantId);
$plantStmt->execute();
$plantResult = $plantStmt->get_result();

if ($plantResult->num_rows === 0) {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Plant not found']);
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

// Prepare response
$response = [
    'plant' => $plant,
    'conditions' => $conditions,
    'watering' => $watering,
    'care' => $care
];

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close connections
$plantStmt->close();
$conditionsStmt->close();
$wateringStmt->close();
$careStmt->close();
$conn->close();
?>
