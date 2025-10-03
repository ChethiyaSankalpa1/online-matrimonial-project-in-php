<?php
require_once 'includes/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $day = intval($_POST['day']);
    $month = intval($_POST['month']);
    $year = intval($_POST['year']);
    
    // Generate a default password (user should change later)
    $default_password = password_hash('password123', PASSWORD_DEFAULT);
    
    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($check_query);
    
    if ($result->num_rows > 0) {
        header("Location: signin.php?error=email_exists");
        exit();
    }
    
    // Insert user
    $insert_query = "INSERT INTO users (email, password, gender, birth_day, birth_month, birth_year) 
                     VALUES ('$email', '$default_password', '$gender', $day, $month, $year)";
    
    if ($conn->query($insert_query)) {
        $user_id = $conn->insert_id;
        
        // Create empty profile
        $profile_query = "INSERT INTO user_profiles (user_id) VALUES ($user_id)";
        $conn->query($profile_query);
        
        // Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        
        // Redirect to profile completion
        header("Location: complete_profile.php");
        exit();
    } else {
        header("Location: signin.php?error=registration_failed");
        exit();
    }
}

$conn->close();
?>
