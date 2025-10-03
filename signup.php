<?php
session_start();
include 'includes/header.php';
include 'database/db_connect.php';
include 'includes/google_config.php';

// Get Google Sign-In URL
$googleAuthUrl = getGoogleAuthUrl();

// Login processing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    // Get and sanitize input
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    // Store email in session for form persistence
    $_SESSION['login_email'] = $email;
    
    // Check if user exists in the users table
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        // User exists
        $user = $result->fetch_assoc();
        
        // Check if user has completed profile setup (has username and password)
        if (empty($user['username']) || empty($user['password'])) {
            // User registered but hasn't completed profile
            $_SESSION['error'] = "Please complete your profile first! You registered but didn't finish setting up your account.";
            $_SESSION['user'] = $email;
            // Redirect to complete profile
            echo "<script>alert('Please complete your profile setup!'); window.location='complete_profile.php';</script>";
            exit();
        }
        
        // Check if password is correct
        if (password_verify($password, $user['password'])) {
            // Password is correct - login successful
            $_SESSION['user'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            
            // Clear stored email
            unset($_SESSION['login_email']);
            
            // Redirect to dashboard.php
            echo "<script>window.location='dashboard.php';</script>";
            exit();
        } else {
            // Password is incorrect
            $_SESSION['error'] = "Invalid password! Please try again.";
        }
    } else {
        // User not found
        $_SESSION['error'] = "No account found with this email address! <a href='signin.php' style='color:#ffd700; text-decoration:underline;'>Click here to register</a>";
    }
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .banner {
        background: linear-gradient(90deg, #1a3a52 0%, #2d5a3a 100%);
        text-align: center;
        padding: 15px;
        margin: 20px auto;
        max-width: 1200px;
        border-radius: 8px;
    }

    .banner-text {
        color: #ffd700;
        font-size: 20px;
        font-style: italic;
    }

    .login-container {
        max-width: 900px;
        margin: 30px auto;
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 3px solid #8b4513;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    }

    .header-banner {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
        padding: 15px;
        border-radius: 25px;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
    }

    .header-text {
        color: #8b4513;
        font-size: 16px;
        font-weight: bold;
        margin: 0;
    }

    .content-wrapper {
        display: flex;
        gap: 30px;
        align-items: center;
        margin-bottom: 30px;
    }

    .google-section {
        flex: 1;
        background: rgba(0, 0, 0, 0.3);
        padding: 60px 30px;
        border-radius: 10px;
        border: 2px solid #5a3a2a;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 250px;
    }

    .google-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        color: #444;
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: box-shadow 0.3s;
    }

    .google-btn:hover {
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
    }

    .google-icon {
        width: 20px;
        height: 20px;
    }

    .divider {
        color: #ffd700;
        font-size: 24px;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .form-section {
        flex: 1;
        background: rgba(0, 0, 0, 0.3);
        padding: 30px;
        border-radius: 10px;
        border: 2px solid #5a3a2a;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        color: #fff;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        border: 2px solid #5a3a2a;
        border-radius: 5px;
        background: rgba(100, 100, 100, 0.4);
        color: #ccc;
        font-size: 14px;
        font-style: italic;
    }

    .form-input:focus {
        outline: none;
        border-color: #ffd700;
        background: rgba(100, 100, 100, 0.5);
        color: #fff;
    }

    .login-btn {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: #fff;
        border: 2px solid #8b7d3a;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
    }

    .login-btn:hover {
        background: linear-gradient(135deg, #7a9d2a 0%, #6b8e23 100%);
        box-shadow: 0 4px 15px rgba(107, 142, 35, 0.4);
    }

    .forgot-link {
        text-align: right;
        margin-top: 10px;
    }

    .forgot-link a {
        color: #ffd700;
        text-decoration: none;
        font-size: 14px;
    }

    .forgot-link a:hover {
        text-decoration: underline;
    }

    .app-section {
        border-top: 2px solid #5a3a2a;
        padding-top: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }

    .app-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .app-btn {
        display: block;
    }

    .app-btn img {
        height: 50px;
        border-radius: 8px;
        transition: transform 0.3s;
    }
    .app-btn:hover img {
        transform: scale(1.05);
    }

    /* Error message styles */
    .error-message {
        background: #ff4444;
        color: white;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
        border: 1px solid #cc0000;
        font-weight: bold;
    }
    
    .success-message {
        background: #00C851;
        color: white;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
        border: 1px solid #007E33;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }
        
        .app-section {
            flex-direction: column;
        }
    }
</style>

<div class="banner">
    <p class="banner-text">Welcome Back to Liyathabara Matrimony – Log In to Your Account.</p>
</div>

<div class="login-container">
    <!-- Display error/success messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message">
            <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <div class="header-banner">
        <p class="header-text">Already a Member? Sign In Continue Your Search</p>
    </div>

    <div class="content-wrapper">
        <div class="google-section">
            <a href="<?php echo htmlspecialchars($googleAuthUrl); ?>" class="google-btn">
                <svg class="google-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Sign in with Google
            </a>
        </div>

        <div class="divider">OR</div>

        <div class="form-section">
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Login Email</label>
                    <input type="email" name="email" class="form-input" placeholder="Enter Email address" required value="<?php echo isset($_SESSION['login_email']) ? htmlspecialchars($_SESSION['login_email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Enter Password" required>
                </div>

                <button type="submit" class="login-btn">Login</button>

                <div class="forgot-link">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>

    <div class="app-section">
        <div class="app-buttons">
            <a href="#" class="app-btn">
                <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="Download on App Store">
            </a>
            <a href="#" class="app-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Get it on Google Play">
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>