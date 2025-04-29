<?php
require_once 'config.php';

// Initialize variables
$username = $email = $password = $confirm_password = $location = "";
$errors = [];

// Process register form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $location = sanitize_input($_POST['location']);
    
    // Basic validation
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (empty($location)) {
        $errors[] = "Location is required";
    }
    
    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $row = mysqli_fetch_assoc($check_result);
        if ($row['username'] == $username) {
            $errors[] = "Username already exists";
        }
        if ($row['email'] == $email) {
            $errors[] = "Email already exists";
        }
    }
    
    // If no errors, create account
    if (empty($errors)) {
        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $sql = "INSERT INTO users (username, email, password, location, created_at) 
                VALUES ('$username', '$email', '$hashed_password', '$location', NOW())";
                
        if (mysqli_query($conn, $sql)) {
            // Redirect with success message as URL parameter
            header("Location: login.php?" . create_message_params("Registration successful! Please login."));
            exit;
        } else {
            $errors[] = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Community Help Platform</title>
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
                    <li><a href="login.php">Login</a></li>
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

    <section class="section">
        <div class="container">
            <div class="form-container">
                <h2 class="form-title">Create an Account</h2>
                
                <?php 
                // Show messages
                display_message();
                
                // Show validation errors
                if (!empty($errors)): 
                ?>
                    <div class="error-message">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small>Password must be at least 6 characters long</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo $location; ?>" required>
                        <small>e.g., Neighborhood, City</small>
                    </div>
                    
                    <button type="submit" class="btn" style="width: 100%;">Register</button>
                </form>
                
                <div style="text-align: center; margin-top: 1rem;">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
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