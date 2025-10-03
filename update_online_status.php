<?php
session_start();
include 'database/db_connect.php';

// Update user's last_seen timestamp when they're active
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $sql = "UPDATE users SET last_seen = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(['status' => 'success', 'message' => 'Online status updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
}

$conn->close();
?>
