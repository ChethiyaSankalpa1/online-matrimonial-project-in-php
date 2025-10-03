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

// Get friend ID from URL
if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$friend_id = intval($_GET['id']);

// Don't allow adding yourself
if($friend_id == $current_user_id) {
    $_SESSION['error'] = "You cannot add yourself as a friend!";
    header("Location: view_profile.php?id=" . $friend_id);
    exit();
}

// Create friends table if not exists
$create_friends_table = "CREATE TABLE IF NOT EXISTS user_friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    friend_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_friendship (user_id, friend_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (friend_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_friends_table);

// Check if already friends
$check_query = "SELECT * FROM user_friends WHERE user_id=$current_user_id AND friend_id=$friend_id";
$check_result = $conn->query($check_query);

if($check_result && $check_result->num_rows > 0) {
    $_SESSION['info'] = "This user is already in your friends list!";
    header("Location: view_profile.php?id=" . $friend_id);
    exit();
}

// Add friend
$insert_query = "INSERT INTO user_friends (user_id, friend_id) VALUES ($current_user_id, $friend_id)";

if($conn->query($insert_query)) {
    $_SESSION['success'] = "Friend added successfully!";
} else {
    $_SESSION['error'] = "Failed to add friend. Please try again.";
}

header("Location: view_profile.php?id=" . $friend_id);
exit();
?>
