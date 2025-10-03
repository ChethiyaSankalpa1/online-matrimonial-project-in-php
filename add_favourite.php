<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location: signup.php");
    exit();
}
include 'database/db_connect.php';

// Get current user
$email = $_SESSION['user'];
$user_query = $conn->query("SELECT * FROM users WHERE email='$email'");
$current_user = $user_query->fetch_assoc();
$current_user_id = $current_user['id'];

// Get favourite ID from URL
if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$favourite_id = intval($_GET['id']);

// Don't allow adding yourself
if($favourite_id == $current_user_id) {
    $_SESSION['error'] = "You cannot add yourself to favourites!";
    header("Location: view_profile.php?id=" . $favourite_id);
    exit();
}

// Create favourites table if not exists
$create_favourites_table = "CREATE TABLE IF NOT EXISTS user_favourites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    favourite_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favourite (user_id, favourite_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (favourite_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_favourites_table);

// Check if already in favourites
$check_query = "SELECT * FROM user_favourites WHERE user_id=$current_user_id AND favourite_id=$favourite_id";
$check_result = $conn->query($check_query);

if($check_result && $check_result->num_rows > 0) {
    $_SESSION['info'] = "This user is already in your favourites!";
    header("Location: view_profile.php?id=" . $favourite_id);
    exit();
}

// Add to favourites
$insert_query = "INSERT INTO user_favourites (user_id, favourite_id) VALUES ($current_user_id, $favourite_id)";

if($conn->query($insert_query)) {
    $_SESSION['success'] = "Added to favourites successfully!";
} else {
    $_SESSION['error'] = "Failed to add to favourites. Please try again.";
}

header("Location: view_profile.php?id=" . $favourite_id);
exit();
?>
