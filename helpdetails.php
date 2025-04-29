<?php
require_once 'config.php';

// Get listing ID
$id = isset($_GET['id']) ? sanitize_input($_GET['id']) : 0;

// Query the database for the listing
$sql = "SELECT h.*, u.username, u.email FROM help_listings h 
        JOIN users u ON h.user_id = u.id 
        WHERE h.id = '$id'";
$result = mysqli_query($conn, $sql);

// Check if listing exists
if (mysqli_num_rows($result) == 0) {
    header("Location: helplistings.php");
    exit;
}

// Get listing data
$listing = mysqli_fetch_assoc($result);

// Check if user is logged in (simplified)
$user_id = isset($_GET['user_id']) ? sanitize_input($_GET['user_id']) : 0;
$is_logged_in = ($user_id > 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $listing['title']; ?> - Community Help Platform</title>
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
                    <?php if ($is_logged_in): ?>
                        <li><a href="dashboard.php?user_id=<?php echo $user_id; ?>">Dashboard</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn">Login</a></li>
                        <li><a href="register.php" class="btn btn-secondary">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="mobile-menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="helplistings.php">Browse Help</a></li>
                    <?php if ($is_logged_in): ?>
                        <li><a href="dashboard.php?user_id=<?php echo $user_id; ?>">Dashboard</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <a href="helplistings.php" class="btn" style="margin-bottom: 1rem;">
                <i class="fas fa-arrow-left"></i> Back to Listings
            </a>
            
            <div class="help-listing" style="margin-bottom: 2rem;">
                <div class="listing-header">
                    <h1 class="listing-title"><?php echo $listing['title']; ?></h1>
                    <span class="listing-type <?php echo $listing['type']; ?>"><?php echo ucfirst($listing['type']); ?></span>
                </div>
                
                <div class="listing-details">
                    <p style="margin-bottom: 1rem;"><?php echo $listing['description']; ?></p>
                    
                    <div style="background-color: var(--light-bg); padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                        <p><strong>Category:</strong> <?php echo ucfirst($listing['category']); ?></p>
                        <p><strong>Location:</strong> <?php echo $listing['location']; ?></p>
                        <p><strong>Posted by:</strong> <?php echo $listing['username']; ?></p>
                        <p><strong>Posted on:</strong> <?php echo date('F j, Y', strtotime($listing['created_at'])); ?></p>
                    </div>
                </div>
                
                <div class="contact-info" style="background-color: var(--white); padding: 1.5rem; border-radius: 5px; box-shadow: var(--box-shadow); margin-top: 2rem;">
                    <h3>Contact Information</h3>
                    
                    <?php if ($is_logged_in): ?>
                        <p style="margin-top: 1rem;">
                            <strong>Contact:</strong> <?php echo $listing['username']; ?><br>
                            <strong>Email:</strong> <?php echo $listing['email']; ?>
                        </p>
                        <a href="mailto:<?php echo $listing['email']; ?>" class="btn" style="margin-top: 1rem;">
                            <i class="fas fa-envelope"></i> Send Email
                        </a>
                    <?php else: ?>
                        <p style="margin-top: 1rem;">Please <a href="login.php">login</a> or <a href="register.php">register</a> to see contact information.</p>
                    <?php endif; ?>
                </div>
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
