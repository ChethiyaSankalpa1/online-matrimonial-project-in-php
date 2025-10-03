<?php 
session_start(); 
include 'includes/header.php'; 
include 'database/db_connect.php';
include 'includes/google_config.php';

// Get Google Sign-In URL
$googleAuthUrl = getGoogleAuthUrl();

// Check if user came from Google Sign-In
$isGoogleSignup = isset($_GET['google']) && isset($_SESSION['google_signup']);
$googleData = $isGoogleSignup ? $_SESSION['google_signup'] : null;
?>

<style>
/* ---------- Your existing CSS ---------- */
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

.registration-container {
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
}

.google-section {
    flex: 1;
    background: rgba(0, 0, 0, 0.3);
    padding: 40px 30px;
    border-radius: 10px;
    border: 2px solid #5a3a2a;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
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
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 14px;
}

.form-input:focus {
    outline: none;
    border-color: #ffd700;
    background: rgba(255, 255, 255, 0.15);
}

.gender-group {
    display: flex;
    gap: 15px;
}

.gender-btn {
    flex: 1;
    padding: 15px;
    background: rgba(100, 100, 100, 0.3);
    border: 2px solid #5a3a2a;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
}

.gender-btn input[type="radio"] {
    display: none;
}

.gender-btn .icon {
    font-size: 32px;
    display: block;
    margin-bottom: 5px;
}

.gender-btn input[type="radio"]:checked + label {
    background: rgba(139, 69, 19, 0.6);
    border-color: #ffd700;
}

.gender-btn label {
    cursor: pointer;
    color: #fff;
    display: block;
}

.dob-group {
    display: flex;
    gap: 10px;
}

.dob-select {
    flex: 1;
    padding: 12px;
    border: 2px solid #5a3a2a;
    border-radius: 5px;
    background: rgba(139, 69, 19, 0.4);
    color: #ffd700;
    font-size: 14px;
    font-weight: bold;
}

.dob-select:focus {
    outline: none;
    border-color: #ffd700;
}

.submit-btn {
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

.submit-btn:hover {
    background: linear-gradient(135deg, #7a9d2a 0%, #6b8e23 100%);
    box-shadow: 0 4px 15px rgba(107, 142, 35, 0.4);
}

.footer-text {
    text-align: center;
    color: #ffd700;
    margin-top: 30px;
    font-size: 13px;
}

.footer-text a {
    color: #ffd700;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .content-wrapper {
        flex-direction: column;
    }
    
    .divider {
        transform: rotate(90deg);
        margin: 20px 0;
    }
}
</style>

<div class="banner">
    <p class="banner-text">Register free on Liyathabara Matrimony and instantly review your matches.</p>
</div>

<div class="registration-container">
    <?php if ($isGoogleSignup): ?>
    <div class="header-banner" style="background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);">
        <p class="header-text">✓ Google Sign-In Successful! Complete your registration below</p>
    </div>
    <?php else: ?>
    <div class="header-banner">
        <p class="header-text">Take the 1st step to your happy marriage...! Register FREE...!</p>
    </div>
    <?php endif; ?>

    <div class="content-wrapper">
        <div class="google-section">
            <a href="<?php echo htmlspecialchars($googleAuthUrl); ?>" class="google-btn">
                <svg class="google-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Sign up with Google
            </a>
        </div>

        <div class="divider">OR</div>

        <div class="form-section">
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Email:<?php if ($isGoogleSignup) echo ' <span style="color: #4caf50;">✓ Verified by Google</span>'; ?></label>
                    <input type="email" name="email" class="form-input" 
                           placeholder="Enter your email" 
                           value="<?php echo $isGoogleSignup ? htmlspecialchars($googleData['email']) : ''; ?>"
                           <?php echo $isGoogleSignup ? 'readonly style="background: rgba(76, 175, 80, 0.1); border-color: #4caf50;"' : ''; ?>
                           required>
                    <?php if ($isGoogleSignup): ?>
                        <input type="hidden" name="google_signup" value="1">
                        <input type="hidden" name="google_name" value="<?php echo htmlspecialchars($googleData['name']); ?>">
                        <input type="hidden" name="google_picture" value="<?php echo htmlspecialchars($googleData['picture']); ?>">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Gender:</label>
                    <div class="gender-group">
                        <div class="gender-btn">
                            <input type="radio" name="gender" value="male" id="male" required>
                            <label for="male">
                                <span class="icon">👨</span>
                                Male
                            </label>
                        </div>
                        <div class="gender-btn">
                            <input type="radio" name="gender" value="female" id="female" required>
                            <label for="female">
                                <span class="icon">👩</span>
                                Female
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Born on:</label>
                    <div class="dob-group">
                        <select name="day" class="dob-select" required>
                            <option value="">Day</option>
                            <?php for($i = 1; $i <= 31; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="month" class="dob-select" required>
                            <option value="">Month</option>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo date('F', mktime(0,0,0,$i,1)); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="year" class="dob-select" required>
                            <option value="">Year</option>
                            <?php for($i = date('Y'); $i >= 1950; $i--): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" name="submit" class="submit-btn">Submit Information</button>
            </form>
        </div>
    </div>
</div>

<script>

// Gender button active state
document.querySelectorAll('.gender-btn input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.gender-btn').forEach(btn => {
            btn.style.background = 'rgba(100, 100, 100, 0.3)';
            btn.style.borderColor = '#5a3a2a';
        });
        if(this.checked) {
            this.closest('.gender-btn').style.background = 'rgba(139, 69, 19, 0.6)';
            this.closest('.gender-btn').style.borderColor = '#ffd700';
        }
    });
});
</script>

<?php
if(isset($_POST['submit'])){
    $email = $conn->real_escape_string($_POST['email']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $day = (int)$_POST['day'];
    $month = (int)$_POST['month'];
    $year = (int)$_POST['year'];
    
    $isGoogleSignupSubmit = isset($_POST['google_signup']) && $_POST['google_signup'] == '1';

    // Check if user already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
    if($check->num_rows > 0){
        echo "<script>alert('Email already registered!');</script>";
    } else {
        // Generate password (random for Google users)
        if ($isGoogleSignupSubmit) {
            $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
            $username = $conn->real_escape_string($_POST['google_name']);
        } else {
            $password = ''; // Will be set in complete_profile
            $username = '';
        }
        
        $sql = "INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen) 
                VALUES ('$username', '$email', '$password', '$gender', $day, $month, $year, 0, NOW())";
        
        if($conn->query($sql) === TRUE){
            $userId = $conn->insert_id;
            
            // Save Google profile picture if available
            if ($isGoogleSignupSubmit && !empty($_POST['google_picture'])) {
                $picturePath = $conn->real_escape_string($_POST['google_picture']);
                $conn->query("INSERT INTO user_photos (user_id, photo_path) VALUES ($userId, '$picturePath')");
            }
            
            $_SESSION['user'] = $email;
            $_SESSION['user_id'] = $userId;
            
            // Clear Google signup data
            unset($_SESSION['google_signup']);
            
            // Redirect to complete_profile.php
            echo "<script>window.location='complete_profile.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}
$conn->close();
?>

<?php include 'includes/footer.php'; ?>
