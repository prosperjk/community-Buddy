<?php
require_once 'config.php';

// Get filter parameters
$type_filter = isset($_GET['type']) ? sanitize_input($_GET['type']) : 'all';
$category_filter = isset($_GET['category']) ? sanitize_input($_GET['category']) : 'all';

// Build SQL query based on filters
$sql = "SELECT h.*, u.username FROM help_listings h JOIN users u ON h.user_id = u.id WHERE 1=1";

if ($type_filter != 'all') {
    $sql .= " AND h.type = '$type_filter'";
}

if ($category_filter != 'all') {
    $sql .= " AND h.category = '$category_filter'";
}

$sql .= " ORDER BY h.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Help - Community Help Platform</title>
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
                    <?php if (is_logged_in()): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
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
                    <?php if (is_logged_in()): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
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
            <h1 class="section-title">Browse Help Listings</h1>
            
            <div class="filters">
                <form id="filter-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                    <div class="filter-form">
                        <div>
                            <label for="type-filter">Type:</label>
                            <select id="type-filter" name="type" class="form-control">
                                <option value="all" <?php echo $type_filter == 'all' ? 'selected' : ''; ?>>All Types</option>
                                <option value="request" <?php echo $type_filter == 'request' ? 'selected' : ''; ?>>Requests</option>
                                <option value="offer" <?php echo $type_filter == 'offer' ? 'selected' : ''; ?>>Offers</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="category-filter">Category:</label>
                            <select id="category-filter" name="category" class="form-control">
                                <option value="all" <?php echo $category_filter == 'all' ? 'selected' : ''; ?>>All Categories</option>
                                <option value="errands" <?php echo $category_filter == 'errands' ? 'selected' : ''; ?>>Errands</option>
                                <option value="transportation" <?php echo $category_filter == 'transportation' ? 'selected' : ''; ?>>Transportation</option>
                                <option value="household" <?php echo $category_filter == 'household' ? 'selected' : ''; ?>>Household Tasks</option>
                                <option value="childcare" <?php echo $category_filter == 'childcare' ? 'selected' : ''; ?>>Childcare</option>
                                <option value="petcare" <?php echo $category_filter == 'petcare' ? 'selected' : ''; ?>>Pet Care</option>
                                <option value="technology" <?php echo $category_filter == 'technology' ? 'selected' : ''; ?>>Technology</option>
                                <option value="education" <?php echo $category_filter == 'education' ? 'selected' : ''; ?>>Education</option>
                                <option value="other" <?php echo $category_filter == 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn">Apply Filters</button>
                    </div>
                </form>
            </div>
            
            <div class="listings-container">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <div class="help-listing" data-type="<?php echo $row['type']; ?>" data-category="<?php echo $row['category']; ?>">
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
                            <div class="listing-contact">
                                <p>Posted by: <?php echo $row['username']; ?></p>
                                <a href="helpdetails.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No help listings found matching your criteria.</p>
                <?php endif; ?>
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