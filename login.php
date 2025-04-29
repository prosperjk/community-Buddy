<?php
require_once 'config.php';

// Initialize variables
$username = $password = "";
$errors = [];

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username/email and password
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    // Basic validation
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If no errors, try to login
    if (empty($errors)) {
        // Look up the user
        $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Check password
            if (password_verify($password, $user['password'])) {
                // Go to dashboard with user ID as query parameter
                header("Location: dashboard.php?user_id=" . $user['id'] . "&username=" . urlencode($user['username']));
                exit;
            } else {
                $errors[] = "Wrong username or password";
            }
        } else {
            $errors[] = "Wrong username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Community Help Platform</title>
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

    <section class="section">
        <div class="container">
            <div class="form-container">
                <h2 class="form-title">Login to Your Account</h2>
                
                <?php 
                // Show success/error messages
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
                        <label for="username">Username or Email</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn" style="width: 100%;">Login</button>
                </form>
                
                <div style="text-align: center; margin-top: 1rem;">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
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
