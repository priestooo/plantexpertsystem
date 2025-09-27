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

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
  $livestockId = (int)$_GET['id'];
  
  // Delete livestock and related data
  $conn->begin_transaction();
  
  try {
    // Delete diseases
    $deleteDiseases = $conn->prepare("DELETE FROM livestock_diseases WHERE livestock_id = ?");
    $deleteDiseases->bind_param("i", $livestockId);
    $deleteDiseases->execute();
    
    // Delete breeding
    $deleteBreeding = $conn->prepare("DELETE FROM livestock_breeding WHERE livestock_id = ?");
    $deleteBreeding->bind_param("i", $livestockId);
    $deleteBreeding->execute();
    
    // Delete care
    $deleteCare = $conn->prepare("DELETE FROM livestock_care WHERE livestock_id = ?");
    $deleteCare->bind_param("i", $livestockId);
    $deleteCare->execute();
    
    // Delete livestock
    $deleteLivestock = $conn->prepare("DELETE FROM livestock WHERE livestock_id = ?");
    $deleteLivestock->bind_param("i", $livestockId);
    $deleteLivestock->execute();
    
    $conn->commit();
    $deleteMessage = "Livestock deleted successfully!";
  } catch (Exception $e) {
    $conn->rollback();
    $deleteError = "Error deleting livestock: " . $e->getMessage();
  }
}

// Get livestock with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Get search term if provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// Build query based on filters
$whereClause = "";
$params = [];
$types = "";

if (!empty($searchTerm)) {
  $whereClause = "WHERE (animal_name LIKE ? OR description LIKE ?)";
  $searchParam = "%$searchTerm%";
  $params[] = $searchParam;
  $params[] = $searchParam;
  $types .= "ss";
}

if (!empty($categoryFilter)) {
  if (empty($whereClause)) {
    $whereClause = "WHERE category = ?";
  } else {
    $whereClause .= " AND category = ?";
  }
  $params[] = $categoryFilter;
  $types .= "s";
}

// Get total count for pagination
$countSql = "SELECT COUNT(*) as total FROM livestock $whereClause";
if (!empty($params)) {
  $countStmt = $conn->prepare($countSql);
  $countStmt->bind_param($types, ...$params);
  $countStmt->execute();
  $totalResult = $countStmt->get_result();
} else {
  $totalResult = $conn->query($countSql);
}
$totalLivestock = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalLivestock / $perPage);

// Get livestock
$livestockSql = "SELECT * FROM livestock $whereClause ORDER BY animal_name LIMIT ? OFFSET ?";
if (!empty($params)) {
  $livestockStmt = $conn->prepare($livestockSql);
  $limitTypes = $types . "ii";
  $limitParams = array_merge($params, [$perPage, $offset]);
  $livestockStmt->bind_param($limitTypes, ...$limitParams);
  $livestockStmt->execute();
  $livestockResult = $livestockStmt->get_result();
} else {
  $livestockStmt = $conn->prepare($livestockSql);
  $livestockStmt->bind_param("ii", $perPage, $offset);
  $livestockStmt->execute();
  $livestockResult = $livestockStmt->get_result();
}

// Get categories for filter
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
  <title>Manage Livestock - Agricultural Expert System</title>
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
          <h2>Manage Livestock</h2>
          
          <?php if (isset($deleteMessage)): ?>
              <div class="alert success">
                  <?php echo $deleteMessage; ?>
              </div>
          <?php endif; ?>
          
          <?php if (isset($deleteError)): ?>
              <div class="alert error">
                  <?php echo $deleteError; ?>
              </div>
          <?php endif; ?>
          
          <div class="admin-actions">
              <a href="add_livestock.php" class="btn-primary"><i class="fas fa-plus"></i> Add New Livestock</a>
          </div>
          
          <div class="filter-section">
              <form method="get" action="" class="filter-form">
                  <div class="filter-group">
                      <input type="text" name="search" placeholder="Search livestock..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                  </div>
                  <div class="filter-group">
                      <select name="category">
                          <option value="">All Categories</option>
                          <?php foreach ($categories as $category): ?>
                          <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $category === $categoryFilter ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($category); ?>
                          </option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                  <button type="submit" class="btn-primary">Filter</button>
                  <a href="livestock.php" class="btn-secondary">Reset</a>
              </form>
          </div>
          
          <div class="table-responsive">
              <table class="admin-table">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Category</th>
                          <th>Origin</th>
                          <th>Lifespan</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if ($livestockResult->num_rows === 0): ?>
                      <tr>
                          <td colspan="6" class="no-data">No livestock found</td>
                      </tr>
                      <?php else: ?>
                          <?php while ($animal = $livestockResult->fetch_assoc()): ?>
                          <tr>
                              <td><?php echo $animal['livestock_id']; ?></td>
                              <td><?php echo htmlspecialchars($animal['animal_name']); ?></td>
                              <td><?php echo htmlspecialchars($animal['category']); ?></td>
                              <td><?php echo htmlspecialchars($animal['origin']); ?></td>
                              <td><?php echo htmlspecialchars($animal['lifespan']); ?></td>
                              <td>
                                  <a href="view_livestock.php?id=<?php echo $animal['livestock_id']; ?>" class="action-btn view" title="View"><i class="fas fa-eye"></i></a>
                                  <a href="edit_livestock.php?id=<?php echo $animal['livestock_id']; ?>" class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
                                  <a href="livestock.php?action=delete&id=<?php echo $animal['livestock_id']; ?>" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this livestock?');"><i class="fas fa-trash"></i></a>
                              </td>
                          </tr>
                          <?php endwhile; ?>
                      <?php endif; ?>
                  </tbody>
              </table>
          </div>
          
          <?php if ($totalPages > 1): ?>
          <div class="pagination">
              <?php if ($page > 1): ?>
                  <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($searchTerm); ?>&category=<?php echo urlencode($categoryFilter); ?>" class="pagination-link">&laquo; Previous</a>
              <?php endif; ?>
              
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>&category=<?php echo urlencode($categoryFilter); ?>" class="pagination-link <?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
              <?php endfor; ?>
              
              <?php if ($page < $totalPages): ?>
                  <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($searchTerm); ?>&category=<?php echo urlencode($categoryFilter); ?>" class="pagination-link">Next &raquo;</a>
              <?php endif; ?>
          </div>
          <?php endif; ?>
      </div>
  </div>
</body>
</html>

