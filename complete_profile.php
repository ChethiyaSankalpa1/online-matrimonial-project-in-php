<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location: signup.php");
    exit();
}
include 'includes/header.php'; 
include 'database/db_connect.php';

// Create tables if they don't exist
$create_users_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) DEFAULT NULL,
    gender ENUM('male', 'female') DEFAULT NULL,
    day INT DEFAULT NULL,
    month INT DEFAULT NULL,
    year INT DEFAULT NULL,
    profile_completed TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$create_user_profiles_table = "CREATE TABLE IF NOT EXISTS user_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    height VARCHAR(50),
    weight VARCHAR(50),
    figure VARCHAR(50),
    appearance VARCHAR(50),
    complexion VARCHAR(50),
    status VARCHAR(50),
    education VARCHAR(100),
    career VARCHAR(100),
    religion VARCHAR(50),
    ethnicity VARCHAR(50),
    caste VARCHAR(50),
    social_class VARCHAR(50),
    residency VARCHAR(100),
    family VARCHAR(50),
    smoking VARCHAR(50),
    drinking VARCHAR(50),
    children VARCHAR(50),
    personality VARCHAR(50),
    first_date_preference VARCHAR(100),
    living_arrangements VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

// Execute table creation
if (!$conn->query($create_users_table)) {
    die("Error creating users table: " . $conn->error);
}

if (!$conn->query($create_user_profiles_table)) {
    die("Error creating user_data table: " . $conn->error);
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .profile-header {
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        padding: 30px;
        border-radius: 15px 15px 0 0;
        max-width: 1000px;
        margin: 20px auto 0;
        border: 3px solid #8b4513;
        border-bottom: none;
    }

    .profile-title {
        color: #ffd700;
        font-size: 32px;
        font-weight: bold;
        font-style: italic;
        margin: 0;
    }

    .profile-container {
        max-width: 1000px;
        margin: 0 auto 40px;
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 3px solid #8b4513;
        border-radius: 0 0 15px 15px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    }

    .intro-box {
        background: rgba(255, 255, 255, 0.95);
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        color: #333;
        line-height: 1.8;
        font-size: 15px;
    }

    .section-title {
        color: #ff6b6b;
        font-size: 24px;
        font-weight: bold;
        margin: 30px 0 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #8b4513;
    }

    .form-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 20px;
        margin-bottom: 20px;
        align-items: center;
    }

    .form-label {
        color: #fff;
        font-size: 16px;
        font-weight: 500;
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        background: rgba(100, 100, 100, 0.6);
        border: 2px solid #8b7d3a;
        border-radius: 8px;
        color: #ffd700;
        font-size: 15px;
        font-style: italic;
        transition: all 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #ffd700;
        background: rgba(100, 100, 100, 0.8);
    }

    .form-select {
        width: 100%;
        padding: 12px 15px;
        background: rgba(100, 100, 100, 0.6);
        border: 2px solid #8b7d3a;
        border-radius: 8px;
        color: #ffd700;
        font-size: 15px;
        font-style: italic;
        cursor: pointer;
        transition: all 0.3s;
    }

    .form-select:focus {
        outline: none;
        border-color: #ffd700;
        background: rgba(100, 100, 100, 0.8);
    }

    .form-select option {
        background: #3a3a3a;
        color: #ffd700;
    }

    .submit-section {
        text-align: center;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #8b4513;
    }

    .submit-btn {
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: #fff;
        padding: 15px 50px;
        border: 2px solid #8b7d3a;
        border-radius: 25px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .submit-btn:hover {
        background: linear-gradient(135deg, #7a9d2a 0%, #6b8e23 100%);
        box-shadow: 0 4px 20px rgba(107, 142, 35, 0.5);
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .profile-title {
            font-size: 24px;
        }
    }
</style>

<div class="profile-header">
    <h1 class="profile-title">My Profile - Let's Get to Know You</h1>
</div>

<div class="profile-container">
    <div class="intro-box">
        Your journey to meaningful connections begins with a complete profile! Before you get started, we kindly ask you to complete your profile by filling in your details. This will help you to find best potential matches and enhance your overall experience.
    </div>

    <form method="POST" action="">
        <!-- Basic Information -->
        <h2 class="section-title">Basic Information</h2>
        
        <div class="form-row">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user'] : ''; ?>" required readonly>
        </div>

        <div class="form-row">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" placeholder="Enter new password (leave blank to keep current)" >
        </div>

        <div class="form-row">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-input" placeholder="Enter your username" required>
        </div>

        <!-- What makes you unique? -->
        <h2 class="section-title">What makes you unique?</h2>
        
        <div class="form-row">
            <label class="form-label">Height</label>
            <select name="height" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="4'0 - 122cm">4'0" - 122cm</option>
                <option value="4'6 - 137cm">4'6" - 137cm</option>
                <option value="5'0 - 152cm">5'0" - 152cm</option>
                <option value="5'3 - 160cm">5'3" - 160cm</option>
                <option value="5'6 - 168cm">5'6" - 168cm</option>
                <option value="5'9 - 175cm">5'9" - 175cm</option>
                <option value="6'0 - 183cm">6'0" - 183cm</option>
                <option value="6'3 - 191cm">6'3" - 191cm</option>
                <option value="6'6+ - 198cm+">6'6"+ - 198cm+</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Weight</label>
            <select name="weight" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="40-50 kg">40-50 kg</option>
                <option value="50-60 kg">50-60 kg</option>
                <option value="60-70 kg">60-70 kg</option>
                <option value="70-80 kg">70-80 kg</option>
                <option value="80-90 kg">80-90 kg</option>
                <option value="90-100 kg">90-100 kg</option>
                <option value="100+ kg">100+ kg</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Figure</label>
            <select name="figure" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Slim">Slim</option>
                <option value="Athletic">Athletic</option>
                <option value="Average">Average</option>
                <option value="Few extra pounds">Few extra pounds</option>
                <option value="Full figured">Full figured</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Appearance</label>
            <select name="appearance" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Very attractive">Very attractive</option>
                <option value="Attractive">Attractive</option>
                <option value="Average">Average</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Complexion</label>
            <select name="complexion" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Fair">Fair</option>
                <option value="Wheatish">Wheatish</option>
                <option value="Dark">Dark</option>
            </select>
        </div>

        <!-- Your Social Background -->
        <h2 class="section-title">Your Social Background ?</h2>

        <div class="form-row">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Never Married">Never Married</option>
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Education</label>
            <select name="education" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="High School">High School</option>
                <option value="Bachelors degree">Bachelors degree</option>
                <option value="Masters degree">Masters degree</option>
                <option value="PhD">PhD</option>
                <option value="Professional qualification">Professional qualification</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Career</label>
            <select name="career" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Engineering/Architecture">Engineering/Architecture</option>
                <option value="Doctor/Medical">Doctor/Medical</option>
                <option value="Banking/Finance">Banking/Finance</option>
                <option value="IT/Software">IT/Software</option>
                <option value="Teacher/Education">Teacher/Education</option>
                <option value="Business/Self Employed">Business/Self Employed</option>
                <option value="Government Employee">Government Employee</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Religion</label>
            <select name="religion" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Buddhist">Buddhist</option>
                <option value="Hindu">Hindu</option>
                <option value="Christian">Christian</option>
                <option value="Catholic">Catholic</option>
                <option value="Islam">Islam</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Ethnicity</label>
            <select name="ethnicity" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Sinhalese">Sinhalese</option>
                <option value="Tamil">Tamil</option>
                <option value="Muslim">Muslim</option>
                <option value="Burgher">Burgher</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Caste</label>
            <select name="caste" class="form-select">
                <option value="">Please Choose</option>
                <option value="Govi">Govi</option>
                <option value="Karawa">Karawa</option>
                <option value="Salagama">Salagama</option>
                <option value="Durava">Durava</option>
                <option value="Caste no bar">Caste no bar</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Social Class</label>
            <select name="social_class" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Upper class">Upper class</option>
                <option value="Middle class">Middle class</option>
                <option value="Working class">Working class</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Residency</label>
            <select name="residency" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
                <option value="Canada">Canada</option>
                <option value="Australia">Australia</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Family</label>
            <select name="family" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Nuclear family">Nuclear family</option>
                <option value="Joint family">Joint family</option>
                <option value="Single parent">Single parent</option>
            </select>
        </div>

        <!-- Your Lifestyle -->
        <h2 class="section-title">Your Lifestyle ?</h2>

        <div class="form-row">
            <label class="form-label">Smoking</label>
            <select name="smoking" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Non-smoker">Non-smoker</option>
                <option value="Occasional smoker">Occasional smoker</option>
                <option value="Regular smoker">Regular smoker</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Drinking</label>
            <select name="drinking" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Non-drinker">Non-drinker</option>
                <option value="Social drinker">Social drinker</option>
                <option value="Regular drinker">Regular drinker</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Children</label>
            <select name="children" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="No children">No children</option>
                <option value="Have children">Have children</option>
                <option value="Want children">Want children</option>
                <option value="Don't want children">Don't want children</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Personality</label>
            <select name="personality" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Outgoing">Outgoing</option>
                <option value="Introvert">Introvert</option>
                <option value="Ambivert">Ambivert</option>
                <option value="Friendly">Friendly</option>
                <option value="Reserved">Reserved</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">First Date Preference</label>
            <select name="first_date_preference" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Coffee/Tea">Coffee/Tea</option>
                <option value="Dinner">Dinner</option>
                <option value="Movie">Movie</option>
                <option value="Walk in park">Walk in park</option>
                <option value="Activity/Adventure">Activity/Adventure</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label">Living Arrangements</label>
            <select name="living_arrangements" class="form-select" required>
                <option value="">Please Choose</option>
                <option value="Live alone">Live alone</option>
                <option value="Live with parents">Live with parents</option>
                <option value="Live with roommates">Live with roommates</option>
                <option value="Own house">Own house</option>
            </select>
        </div>

        <div class="submit-section">
            <button type="submit" name="save_profile" class="submit-btn">Save Personal Profile</button>
        </div>
    </form>
</div>

<?php
if(isset($_POST['save_profile'])){
    // Get all input values and escape
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    
    // Handle password
    $password_update = "";
    if(!empty($_POST['password'])){
        $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $password_update = ", password='$password_hash'";
    }

    $height = $conn->real_escape_string($_POST['height']);
    $weight = $conn->real_escape_string($_POST['weight']);
    $figure = $conn->real_escape_string($_POST['figure']);
    $appearance = $conn->real_escape_string($_POST['appearance']);
    $complexion = $conn->real_escape_string($_POST['complexion']);
    $status = $conn->real_escape_string($_POST['status']);
    $education = $conn->real_escape_string($_POST['education']);
    $career = $conn->real_escape_string($_POST['career']);
    $religion = $conn->real_escape_string($_POST['religion']);
    $ethnicity = $conn->real_escape_string($_POST['ethnicity']);
    $caste = $conn->real_escape_string($_POST['caste']);
    $social_class = $conn->real_escape_string($_POST['social_class']);
    $residency = $conn->real_escape_string($_POST['residency']);
    $family = $conn->real_escape_string($_POST['family']);
    $smoking = $conn->real_escape_string($_POST['smoking']);
    $drinking = $conn->real_escape_string($_POST['drinking']);
    $children = $conn->real_escape_string($_POST['children']);
    $personality = $conn->real_escape_string($_POST['personality']);
    $first_date = $conn->real_escape_string($_POST['first_date_preference']);
    $living = $conn->real_escape_string($_POST['living_arrangements']);

    // First, check if user exists
    $user_result = $conn->query("SELECT id FROM users WHERE email='$email'");
    
    if($user_result && $user_result->num_rows > 0){
        $user_data = $user_result->fetch_assoc();
        $user_id = $user_data['id'];
        
        // Update user table with username and password if provided
        $update_user_sql = "UPDATE users SET username='$username' $password_update WHERE id=$user_id";
        
        if($conn->query($update_user_sql)){
            
            // Check if profile exists
            $check = $conn->query("SELECT * FROM user_data WHERE user_id=$user_id");
            
            if($check && $check->num_rows > 0){
                // Update existing profile
                $sql = "UPDATE user_data SET 
                        height='$height', weight='$weight', figure='$figure', 
                        appearance='$appearance', complexion='$complexion', status='$status',
                        education='$education', career='$career', religion='$religion',
                        ethnicity='$ethnicity', caste='$caste', social_class='$social_class',
                        residency='$residency', family='$family', smoking='$smoking',
                        drinking='$drinking', children='$children', personality='$personality',
                        first_date_preference='$first_date', living_arrangements='$living'
                        WHERE user_id=$user_id";
            } else {
                // Insert new profile
                $sql = "INSERT INTO user_data 
                        (user_id, height, weight, figure, appearance, complexion, status, education, career, 
                        religion, ethnicity, caste, social_class, residency, family, smoking, drinking, 
                        children, personality, first_date_preference, living_arrangements)
                        VALUES 
                        ($user_id, '$height', '$weight', '$figure', '$appearance', '$complexion', '$status', 
                        '$education', '$career', '$religion', '$ethnicity', '$caste', '$social_class', 
                        '$residency', '$family', '$smoking', '$drinking', '$children', '$personality', 
                        '$first_date', '$living')";
            }
            
            if($conn->query($sql)){
                // Update profile_completed flag
                $conn->query("UPDATE users SET profile_completed=1 WHERE id=$user_id");
                echo "<script>alert('Profile saved successfully!'); window.location='dashboard.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error saving profile details: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error updating user: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('User not found! Please sign up first.');</script>";
    }
}
?>

<?php include 'includes/footer.php'; ?>