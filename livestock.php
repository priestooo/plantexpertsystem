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

// Get all categories
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
    <title>Livestock Care - Plant Expert System</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/livestock.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Livestock Care Expert System</h1>
            <p>Your guide to livestock care and management</p>
        </header>

        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Plants</a></li>
                <li><a href="livestock.php" class="active">Livestock</a></li>
                <li><a href="admin/">Admin</a></li>
            </ul>
        </nav>

        <div class="livestock-hero">
            <h2>Livestock Management</h2>
            <p>Find comprehensive information about various livestock animals, their care requirements, breeding information, and health management.</p>
        </div>

        <div class="search-section">
            <div class="search-container">
                <form id="livestock-search-form">
                    <input type="text" id="livestock-search" placeholder="Search for livestock...">
                    <button type="submit" id="search-button">Search</button>
                </form>
                <div id="search-results" class="search-results hidden"></div>
            </div>
            <div class="filter-container">
                <label for="sort-by">Sort by:</label>
                <select id="sort-by">
                    <option value="name">Name</option>
                    <option value="category">Category</option>
                </select>
            </div>
        </div>

        <div id="category-filter" class="category-filter">
            <h2>Filter by Category</h2>
            <div class="category-buttons">
                <button class="category-btn active" data-category="all">All</button>
                <?php foreach ($categories as $category): ?>
                <button class="category-btn" data-category="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="livestock-grid" class="livestock-grid"></div>
        <div id="livestock-info" class="livestock-info hidden"></div>

        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p>We provide expert information on plant and livestock care to help you succeed in your agricultural endeavors.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Plants</a></li>
                        <li><a href="livestock.php">Livestock</a></li>
                        <li><a href="admin/">Admin Panel</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: info@plantexpertsystem.com</p>
                    <p>Phone: (123) 456-7890</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Plant & Livestock Expert System</p>
            </div>
        </footer>
    </div>

    <script src="js/livestock.js"></script>
</body>
</html>
