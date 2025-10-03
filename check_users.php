<?php
include 'database/db_connect.php';

echo "<h2>Users Table Check</h2>";

$result = $conn->query("SELECT id, email, username, password, gender, day, month, year, profile_completed FROM users");

if($result && $result->num_rows > 0){
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Email</th><th>Username</th><th>Password (hashed)</th><th>Gender</th><th>Birth</th><th>Profile Complete</th></tr>";
    
    while($row = $result->fetch_assoc()){
        $password_status = !empty($row['password']) ? "✓ SET (" . substr($row['password'], 0, 20) . "...)" : "✗ NOT SET";
        $username_status = !empty($row['username']) ? $row['username'] : "✗ NOT SET";
        $birth = $row['day'] . "/" . $row['month'] . "/" . $row['year'];
        
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$username_status}</td>";
        echo "<td>{$password_status}</td>";
        echo "<td>{$row['gender']}</td>";
        echo "<td>{$birth}</td>";
        echo "<td>" . ($row['profile_completed'] ? "Yes" : "No") . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No users found in database.</p>";
}

$conn->close();
?>
