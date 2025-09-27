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

// Get plant categories for display
$categoriesSql = "SELECT DISTINCT category FROM plants ORDER BY category";
$categoriesResult = $conn->query($categoriesSql);
$plantCategories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $plantCategories[] = $row['category'];
}

// Get livestock categories for display
$livestockCategoriesSql = "SELECT DISTINCT category FROM livestock ORDER BY category";
$livestockCategoriesResult = $conn->query($livestockCategoriesSql);
$livestockCategories = [];
while ($row = $livestockCategoriesResult->fetch_assoc()) {
    $livestockCategories[] = $row['category'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agricultural Expert System</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Add loading indicator styles -->

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
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#plants">Plants</a></li>
                <li><a href="#livestock">Livestock</a></li>
                <li><a href="admin/">Admin</a></li>
            </ul>
        </nav>

        <div class="hero-section">
            <div class="hero-content">
                <h2>Expert Agricultural Knowledge</h2>
                <p>Access detailed information about Nigerian plants and livestock to improve your farming practices</p>
                <a href="#search-section" class="cta-button">Get Started</a>
            </div>
        </div>

        <div id="search-section" class="search-section" data-base-url="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
            <div class="search-container">
                <h2>Find Plants or Livestock</h2>
                <form id="unified-search-form">
                    <div class="input-group">
                        <input type="text" id="unified-search" placeholder="Search for plants or livestock...">
                        <button type="submit"><i class="fas fa-search"></i> Search</button>
                    </div>
                </form>
                <div id="search-results" class="search-results hidden"></div>
            </div>
        </div>

        <div id="info-container" class="info-container hidden">
            <div id="plant-info" class="plant-info hidden"></div>
            <div id="livestock-info" class="livestock-info hidden"></div>
        </div>

        <div id="category-sections" class="category-sections">
            <div class="category-section" id="plants">
                <h2>Plant Categories</h2>
                <div class="category-grid">
                    <?php foreach ($plantCategories as $category): ?>
                    <div class="category-card" data-category="<?php echo htmlspecialchars(strtolower($category)); ?>">
                        <div class="category-icon">
                            <?php
                            $icon = 'seedling';
                            switch (strtolower($category)) {
                                case 'vegetables':
                                    $icon = 'carrot';
                                    break;
                                case 'fruits':
                                    $icon = 'apple-alt';
                                    break;
                                case 'grains':
                                    $icon = 'wheat';
                                    break;
                                case 'root crops':
                                    $icon = 'seedling';
                                    break;
                                case 'herbs':
                                    $icon = 'leaf';
                                    break;
                            }
                            ?>
                            <i class="fas fa-<?php echo $icon; ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($category); ?></h3>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="category-section" id="livestock">
                <h2>Livestock Categories</h2>
                <div class="category-grid">
                    <?php foreach ($livestockCategories as $category): ?>
                    <div class="category-card" data-category="<?php echo htmlspecialchars(strtolower($category)); ?>">
                        <div class="category-icon">
                            <?php
                            $icon = 'paw';
                            switch (strtolower($category)) {
                                case 'poultry':
                                    $icon = 'kiwi-bird';
                                    break;
                                case 'cattle':
                                    $icon = 'horse';
                                    break;
                                case 'small ruminants':
                                    $icon = 'paw';
                                    break;
                                case 'fish':
                                    $icon = 'fish';
                                    break;
                            }
                            ?>
                            <i class="fas fa-<?php echo $icon; ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($category); ?></h3>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="features-section">
            <h2>Why Use Our Expert System?</h2>
            <div class="features-grid">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Comprehensive Information</h3>
                    <p>Access detailed information about various Nigerian plants and livestock including care requirements, feeding schedules, and more.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>Customized To-Do Lists</h3>
                    <p>Receive personalized care instructions and to-do lists based on your selected plants and livestock.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Expert Advice</h3>
                    <p>Get expert recommendations for optimal plant growth and livestock health maintenance from Nigerian agricultural specialists.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Friendly</h3>
                    <p>Access the system from any device, making it easy to get information while in the field or on the farm.</p>
                </div>
            </div>
        </div>

        <div class="testimonials-section">
            <h2>What Farmers Say</h2>
            <div class="testimonials-slider">
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"This expert system has transformed my cassava farming. The detailed information on pest control saved my entire crop last season."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/farmer1.jpg" alt="Farmer" onerror="this.src='https://via.placeholder.com/50'">
                        <div>
                            <h4>Adebayo Johnson</h4>
                            <p>Cassava Farmer, Oyo State</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"The livestock care information is incredibly detailed. I've been able to improve my goat breeding program significantly using the advice here."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/farmer2.jpg" alt="Farmer" onerror="this.src='https://via.placeholder.com/50'">
                        <div>
                            <h4>Fatima Ibrahim</h4>
                            <p>Livestock Farmer, Kaduna</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"As a new farmer, this system has been my go-to resource. The customized to-do lists help me stay on track with my farming activities."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/farmer3.jpg" alt="Farmer" onerror="this.src='https://via.placeholder.com/50'">
                        <div>
                            <h4>Chinedu Okafor</h4>
                            <p>Mixed Farmer, Enugu</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
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
                        <li><a href="#plants">Plants</a></li>
                        <li><a href="#livestock">Livestock</a></li>
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

    <script src="js/unified.js"></script>
    <script>
  // Check if we need to scroll to a specific section based on URL parameters
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('plant_category') || urlParams.has('livestock_category')) {
      // Scroll to category sections
      document.getElementById('category-sections').scrollIntoView({ behavior: 'smooth' });
    }
  });
</script>
    <script>
        // Testimonial slider
        document.addEventListener('DOMContentLoaded', function() {
            const testimonials = document.querySelectorAll('.testimonial');
            const dots = document.querySelectorAll('.dot');
            let currentSlide = 0;

            function showSlide(index) {
                testimonials.forEach(testimonial => testimonial.style.display = 'none');
                dots.forEach(dot => dot.classList.remove('active'));
                
                testimonials[index].style.display = 'block';
                dots[index].classList.add('active');
            }

            // Initialize
            showSlide(currentSlide);

            // Auto slide
            setInterval(() => {
                currentSlide = (currentSlide + 1) % testimonials.length;
                showSlide(currentSlide);
            }, 5000);

            // Click on dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                });
            });
        });
    </script>
</body>
</html>
