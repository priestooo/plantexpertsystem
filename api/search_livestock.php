<?php
// Include database connection
require_once '../includes/db_connect.php';

// Set proper headers for faster response
header('Content-Type: application/json');
header('Cache-Control: max-age=300'); // Cache for 5 minutes

// Add error handling
try {
  // Get search parameters
  $searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
  $exactMatch = isset($_GET['exact']) ? (bool)$_GET['exact'] : false;
  $category = isset($_GET['category']) ? $_GET['category'] : '';

  // Prepare query based on search parameters
  if (!empty($searchTerm)) {
    if ($exactMatch) {
      $sql = "SELECT livestock_id, animal_name FROM livestock WHERE animal_name = ?";
      $param = $searchTerm;
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $param);
    } else {
      $sql = "SELECT livestock_id, animal_name FROM livestock WHERE animal_name LIKE ?";
      $param = "%$searchTerm%";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $param);
    }
  } elseif (!empty($category) && $category !== 'all') {
    $sql = "SELECT livestock_id, animal_name, category, description, image_url FROM livestock WHERE category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
  } else {
    // Get all livestock
    $sql = "SELECT livestock_id, animal_name, category, description, image_url FROM livestock";
    $stmt = $conn->prepare($sql);
  }

  // Execute query
  $stmt->execute();
  $result = $stmt->get_result();

  // Fetch results
  $livestock = [];
  while ($row = $result->fetch_assoc()) {
    $livestock[] = $row;
  }

  // Return results as JSON
  echo json_encode($livestock);

  // Close connection
  $stmt->close();
  $conn->close();
} catch (Exception $e) {
  // Return error response
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
