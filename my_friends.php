<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location: signup.php");
    exit();
}
include 'includes/header.php'; 
include 'database/db_connect.php';

// Get current user
$email = $_SESSION['user'];
$user_query = $conn->query("SELECT * FROM users WHERE email='$email'");
$current_user = $user_query->fetch_assoc();
$current_user_id = $current_user['id'];

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

// Handle remove friend
if(isset($_GET['remove'])) {
    $friend_id = intval($_GET['remove']);
    $conn->query("DELETE FROM user_friends WHERE user_id=$current_user_id AND friend_id=$friend_id");
    echo "<script>alert('Friend removed!'); window.location='my_friends.php';</script>";
}

// Get all friends
$friends_query = "SELECT u.*, ud.* 
                  FROM user_friends uf
                  JOIN users u ON uf.friend_id = u.id
                  LEFT JOIN user_data ud ON u.id = ud.user_id
                  WHERE uf.user_id = $current_user_id
                  ORDER BY uf.created_at DESC";
$friends_result = $conn->query($friends_query);

$friends = [];
if($friends_result) {
    while($row = $friends_result->fetch_assoc()) {
        $photo_query = $conn->query("SELECT photo_path FROM user_photos WHERE user_id={$row['id']} LIMIT 1");
        $photo = $photo_query && $photo_query->num_rows > 0 ? $photo_query->fetch_assoc()['photo_path'] : null;
        $row['photo'] = $photo;
        $friends[] = $row;
    }
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .friends-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .page-header {
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        padding: 20px 30px;
        border-radius: 10px;
        border: 2px solid #8b4513;
        margin-bottom: 20px;
    }

    .page-title {
        color: #ffd700;
        font-size: 28px;
        font-weight: bold;
        margin: 0;
    }

    .section-banner {
        background: linear-gradient(90deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
        padding: 15px 30px;
        border-radius: 25px;
        margin-bottom: 30px;
        text-align: center;
    }

    .banner-text {
        color: #8b4513;
        font-size: 18px;
        font-weight: bold;
        margin: 0;
    }

    .friends-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 20px;
    }

    .friend-card {
        position: relative;
        transition: transform 0.3s;
    }

    .friend-card:hover {
        transform: scale(1.05);
    }

    .friend-photo {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 20px;
        overflow: hidden;
        border: 4px solid #ffd700;
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        position: relative;
        cursor: pointer;
    }

    .friend-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-photo {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        background: rgba(0, 0, 0, 0.3);
    }

    .friend-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        color: #ffd700;
        padding: 8px;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 0, 0, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 18px;
        z-index: 10;
    }

    .remove-btn:hover {
        background: rgba(255, 0, 0, 1);
    }

    .no-friends {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
    }

    .no-friends-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .no-friends-text {
        color: #ffd700;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .add-friends-btn {
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: #fff;
        padding: 12px 30px;
        border: 2px solid #8b7d3a;
        border-radius: 25px;
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
        margin-top: 20px;
    }

    .add-friends-btn:hover {
        transform: scale(1.05);
    }
</style>

<div class="friends-container">
    <div class="page-header">
        <h1 class="page-title">👥 My Friends</h1>
    </div>

    <?php if(count($friends) > 0): ?>
        <div class="section-banner">
            <p class="banner-text">You have <?php echo count($friends); ?> friend<?php echo count($friends) > 1 ? 's' : ''; ?></p>
        </div>

        <div class="friends-grid">
            <?php foreach($friends as $friend): 
                $username = $friend['username'] ?? 'User' . $friend['id'];
            ?>
                <div class="friend-card">
                    <div class="friend-photo" onclick="window.location='view_profile.php?id=<?php echo $friend['id']; ?>'">
                        <?php if($friend['photo']): ?>
                            <img src="<?php echo $friend['photo']; ?>" alt="<?php echo $username; ?>">
                        <?php else: ?>
                            <div class="no-photo">
                                <?php echo $friend['gender'] == 'female' ? '👩' : '👨'; ?>
                            </div>
                        <?php endif; ?>
                        
                        <button class="remove-btn" onclick="event.stopPropagation(); if(confirm('Remove this friend?')) window.location='?remove=<?php echo $friend['id']; ?>'">×</button>
                        <div class="friend-name"><?php echo htmlspecialchars($username); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-friends">
            <div class="no-friends-icon">👥</div>
            <p class="no-friends-text">You don't have any friends yet!</p>
            <p style="color: #999;">Start adding friends from your matches.</p>
            <a href="my_matches.php" class="add-friends-btn">Find Matches</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
