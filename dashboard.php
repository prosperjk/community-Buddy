<?php
require_once 'config.php';

// Get user info from URL parameters
$user_id = isset($_GET['user_id']) ? sanitize_input($_GET['user_id']) : 0;
$username = isset($_GET['username']) ? sanitize_input($_GET['username']) : '';

// Redirect to login if no user ID provided
if ($user_id == 0) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$title = $description = $type = $category = $location = "";
$errors = [];

// Process form submission for new help listing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $type = sanitize_input($_POST['type']);
    $category = sanitize_input($_POST['category']);
    $location = sanitize_input($_POST['location']);
    
    // Simple validation
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    
    if (empty($description)) {
        $errors[] = "Description is required";
    }
    
    if (empty($type) || ($type != 'request' && $type != 'offer')) {
        $errors[] = "Valid type is required";
    }
    
    if (empty($category)) {
        $errors[] = "Category is required";
    }
    
    if (empty($location)) {
        $errors[] = "Location is required";
    }
    
    // If no errors, save to database
    if (empty($errors)) {
        $sql = "INSERT INTO help_listings (user_id, title, description, type, category, location, created_at) 
                VALUES ('$user_id', '$title', '$description', '$type', '$category', '$location', NOW())";
                
        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            $params = "user_id=$user_id&username=$username&" . create_message_params("Your help listing has been posted successfully!");
            header("Location: dashboard.php?$params");
            exit;
        } else {
            $errors[] = "Error: " . mysqli_error($conn);
        }
    }
}

// Get user's help listings
$sql = "SELECT * FROM help_listings WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Community Help Platform</title>
    <link rel="stylesheet" href="main.css">
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
                    <li><a href="dashboard.php?user_id=<?php echo $user_id; ?>&username=<?php echo urlencode($username); ?>">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
            <div class="mobile-menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="helplistings.php">Browse Help</a></li>
                    <li><a href="dashboard.php?user_id=<?php echo $user_id; ?>&username=<?php echo urlencode($username); ?>">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <h1 class="section-title">Welcome, <?php echo $username; ?>!</h1>
            
            <?php display_message(); ?>
            
            <div class="dashboard-content" style="display: flex; flex-wrap: wrap; gap: 2rem;">
                <div style="flex: 1; min-width: 300px;">
                    <div class="form-container" style="margin-top: 0;">
                        <h2 class="form-title">Post a New Help Listing</h2>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="error-message">
                                <?php foreach($errors as $error): ?>
                                    <p><?php echo $error; ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- The form to create new help listings -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?user_id=" . $user_id . "&username=" . urlencode($username)); ?>" method="post" novalidate>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Type</label>
                                <div style="display: flex; gap: 1rem;">
                                    <div>
                                        <input type="radio" id="request" name="type" value="request" <?php echo ($type == 'request') ? 'checked' : ''; ?> required>
                                        <label for="request">Request Help</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="offer" name="type" value="offer" <?php echo ($type == 'offer') ? 'checked' : ''; ?> required>
                                        <label for="offer">Offer Help</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <option value="errands" <?php echo ($category == 'errands') ? 'selected' : ''; ?>>Errands</option>
                                    <option value="transportation" <?php echo ($category == 'transportation') ? 'selected' : ''; ?>>Transportation</option>
                                    <option value="household" <?php echo ($category == 'household') ? 'selected' : ''; ?>>Household Tasks</option>
                                    <option value="childcare" <?php echo ($category == 'childcare') ? 'selected' : ''; ?>>Childcare</option>
                                    <option value="petcare" <?php echo ($category == 'petcare') ? 'selected' : ''; ?>>Pet Care</option>
                                    <option value="technology" <?php echo ($category == 'technology') ? 'selected' : ''; ?>>Technology</option>
                                    <option value="education" <?php echo ($category == 'education') ? 'selected' : ''; ?>>Education</option>
                                    <option value="other" <?php echo ($category == 'other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="location" value="<?php echo $location; ?>" required>
                                <small>e.g., Neighborhood, City</small>
                            </div>
                            
                            <button type="submit" class="btn" style="width: 100%;">Post Listing</button>
                        </form>
                    </div>
                </div>
                
                <div style="flex: 1; min-width: 300px;">
                    <h2 style="margin-bottom: 1rem;">Your Listings</h2>
                    
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <div class="help-listing">
                                <div class="listing-header">
                                    <h3 class="listing-title"><?php echo $row['title']; ?></h3>
                                    <span class="listing-type <?php echo $row['type']; ?>"><?php echo ucfirst($row['type']); ?></span>
                                </div>
                                <div class="listing-details">
                                    <p><?php echo $row['description']; ?></p>
                                    <p><strong>Category:</strong> <?php echo ucfirst($row['category']); ?></p>
                                    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                                    <p><strong>Posted:</strong> <?php echo date('M j, Y', strtotime($row['created_at'])); ?></p>
                                </div>
                                <!-- No Edit and Delete buttons -->
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>You haven't posted any help listings yet.</p>
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