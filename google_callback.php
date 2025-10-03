<?php
session_start();
include 'includes/google_config.php';
include 'database/db_connect.php';

// Check if we have an authorization code
if (!isset($_GET['code'])) {
    $_SESSION['error'] = 'Google Sign-In failed. No authorization code received.';
    header('Location: signin.php');
    exit();
}

try {
    // Exchange authorization code for access token
    $tokenData = getGoogleAccessToken($_GET['code']);
    
    if (!isset($tokenData['access_token'])) {
        throw new Exception('Failed to get access token from Google');
    }
    
    // Get user information from Google
    $userInfo = getGoogleUserInfo($tokenData['access_token']);
    
    if (!isset($userInfo['email'])) {
        throw new Exception('Failed to get user information from Google');
    }
    
    // Extract user data
    $email = $conn->real_escape_string($userInfo['email']);
    $name = isset($userInfo['name']) ? $conn->real_escape_string($userInfo['name']) : '';
    $googleId = isset($userInfo['id']) ? $conn->real_escape_string($userInfo['id']) : '';
    $picture = isset($userInfo['picture']) ? $userInfo['picture'] : '';
    
    // Check if user already exists
    $checkUser = $conn->query("SELECT * FROM users WHERE email = '$email' LIMIT 1");
    
    if ($checkUser->num_rows > 0) {
        // User exists - log them in
        $user = $checkUser->fetch_assoc();
        
        // Check if profile is completed
        if ($user['profile_completed'] == 0 || empty($user['username'])) {
            // Redirect to complete profile
            $_SESSION['user'] = $email;
            $_SESSION['user_id'] = $user['id'];
            header('Location: complete_profile.php');
            exit();
        }
        
        // User exists and profile is complete - log them in
        $_SESSION['user'] = $email;
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        
        // Update last_seen
        $conn->query("UPDATE users SET last_seen = NOW() WHERE id = " . $user['id']);
        
        header('Location: dashboard.php');
        exit();
        
    } else {
        // New user - redirect to registration page with Google data
        // Store Google info in session for registration process
        $_SESSION['google_signup'] = [
            'email' => $userInfo['email'],
            'name' => $userInfo['name'] ?? '',
            'picture' => $userInfo['picture'] ?? '',
            'google_id' => $userInfo['id'] ?? ''
        ];
        
        $_SESSION['success'] = 'Google Sign-In successful! Please complete your registration.';
        
        // Redirect to registration page (signin.php) with Google data
        header('Location: signin.php?google=1');
        exit();
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Google Sign-In error: ' . $e->getMessage();
    header('Location: signin.php');
    exit();
}

$conn->close();
?>
