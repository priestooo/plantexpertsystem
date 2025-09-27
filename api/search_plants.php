<?php
// Include database connection
require_once '../includes/db_connect.php';

// Set proper headers for faster response
header('Content-Type: application/json');
header('Cache-Control: max-age=300'); // Cache for 5 minutes

// Add error handling
try {
  // Get search term from request
  $searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
  $exactMatch = isset($_GET['exact']) ? (bool)$_GET['exact'] : false;
  $category = isset($_GET['category']) ? $_GET['category'] : '';

  // Prepare query based on search parameters
  if (!empty($searchTerm)) {
    if ($exactMatch) {
      $sql = "SELECT plant_id, plant_name FROM plants WHERE plant_name = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $searchTerm);
    } else {
      $sql = "SELECT plant_id, plant_name FROM plants WHERE plant_name LIKE ?";
      $searchParam = "%$searchTerm%";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $searchParam);
    }
  } elseif (!empty($category) && $category !== 'all') {
    $sql = "SELECT plant_id, plant_name, category, description, image_url FROM plants WHERE category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
  } else {
    // Get all plants
    $sql = "SELECT plant_id, plant_name, category, description, image_url FROM plants";
    $stmt = $conn->prepare($sql);
  }

  // Execute query
  $stmt->execute();
  $result = $stmt->get_result();

  // Fetch results
  $plants = [];
  while ($row = $result->fetch_assoc()) {
    $plants[] = $row;
  }

  // Return results as JSON
  echo json_encode($plants);

  // Close connection
  $stmt->close();
  $conn->close();
} catch (Exception $e) {
  // Return error response
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>

