<?php
// Include database connection
require_once '../includes/db_connect.php';

// Get search term from request
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
$exactMatch = isset($_GET['exact']) ? (bool)$_GET['exact'] : false;

if (empty($searchTerm)) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

// Prepare query for plants
if ($exactMatch) {
    $plantSql = "SELECT plant_id, plant_name, 'plant' as type FROM plants WHERE plant_name = ?";
} else {
    $plantSql = "SELECT plant_id, plant_name, 'plant' as type FROM plants WHERE plant_name LIKE ?";
    $searchParam = "%$searchTerm%";
}

// Prepare and execute statement for plants
$plantStmt = $conn->prepare($plantSql);
if ($exactMatch) {
    $plantStmt->bind_param("s", $searchTerm);
} else {
    $plantStmt->bind_param("s", $searchParam);
}
$plantStmt->execute();
$plantResult = $plantStmt->get_result();

// Prepare query for livestock
if ($exactMatch) {
    $livestockSql = "SELECT livestock_id, animal_name as name, 'livestock' as type FROM livestock WHERE animal_name = ?";
} else {
    $livestockSql = "SELECT livestock_id, animal_name as name, 'livestock' as type FROM livestock WHERE animal_name LIKE ?";
}

// Prepare and execute statement for livestock
$livestockStmt = $conn->prepare($livestockSql);
if ($exactMatch) {
    $livestockStmt->bind_param("s", $searchTerm);
} else {
    $livestockStmt->bind_param("s", $searchParam);
}
$livestockStmt->execute();
$livestockResult = $livestockStmt->get_result();

// Fetch results
$results = [];

// Add plants to results
while ($row = $plantResult->fetch_assoc()) {
    $results[] = [
        'id' => $row['plant_id'],
        'name' => $row['plant_name'],
        'type' => 'plant'
    ];
}

// Add livestock to results
while ($row = $livestockResult->fetch_assoc()) {
    $results[] = [
        'id' => $row['livestock_id'],
        'name' => $row['name'],
        'type' => 'livestock'
    ];
}

// Return results as JSON
header('Content-Type: application/json');
echo json_encode($results);

// Close connections
$plantStmt->close();
$livestockStmt->close();
$conn->close();
?>
