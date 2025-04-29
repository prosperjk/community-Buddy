<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Help Platform</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <i class="fas fa-hands-helping"></i>
                    <span>Community Buddy</span>
                </div>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="helplistings.php">Browse Help</a></li>
                    <li><a href="login.php" class="btn">Login</a></li>
                    <li><a href="register.php" class="btn btn-secondary">Register</a></li>
                </ul>
            </nav>
            <div class="mobile-menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="helplistings.php">Browse Help</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </header>
    
    <section class="hero">
        <div class="container">
            <h1>Connecting Neighbors, Building Community</h1>
            <p>Our platform makes it easy to find help in your local community or offer your skills to those in need.</p>
            <a href="register.php" class="btn btn-secondary">Join Our Community</a>
        </div>
    </section>
    
    <section class="section">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <div class="card-container">
                <div class="card">
                    <div class="card-content">
                        <i class="fas fa-user-plus fa-3x" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h3>Create an Account</h3>
                        <p>Sign up to become a part of our community help network with a simple registration process.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <i class="fas fa-hand-paper fa-3x" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h3>Request or Offer Help</h3>
                        <p>Post your needs or skills to the community and connect with neighbors.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <i class="fas fa-comments fa-3x" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h3>Connect & Communicate</h3>
                        <p>Connect with matches and arrange how you'll help each other.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section" style="background-color: var(--light-bg);">
        <div class="container">
            <h2 class="section-title">Recent Help Activities</h2>
            <?php
            // Display recent help listings (limited to 3)
            $sql = "SELECT h.*, u.username FROM help_listings h 
                    JOIN users u ON h.user_id = u.id 
                    ORDER BY h.created_at DESC LIMIT 3";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $type_class = $row['type'] == 'request' ? 'request' : 'offer';
                    echo '<div class="help-listing" data-type="' . $row['type'] . '" data-category="' . $row['category'] . '">';
                    echo '<div class="listing-header">';
                    echo '<h3 class="listing-title">' . $row['title'] . '</h3>';
                    echo '<span class="listing-type ' . $type_class . '">' . ucfirst($row['type']) . '</span>';
                    echo '</div>';
                    echo '<div class="listing-details">';
                    echo '<p>' . $row['description'] . '</p>';
                    echo '<p><strong>Category:</strong> ' . ucfirst($row['category']) . '</p>';
                    echo '<p><strong>Location:</strong> ' . $row['location'] . '</p>';
                    echo '</div>';
                    echo '<div class="listing-contact">';
                    echo '<p>Posted by: ' . $row['username'] . '</p>';
                    echo '<a href="helpdetails.php?id=' . $row['id'] . '" class="btn">View Details</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">No help listings available yet. Be the first to post!</p>';
            }
            ?>
            <div class="text-center" style="margin-top: 2rem;">
                <a href="helplistings.php" class="btn">View All Listings</a>
            </div>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Community Help Platform. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="main.js"></script>
</body>
</html>