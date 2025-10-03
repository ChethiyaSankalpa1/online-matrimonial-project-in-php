<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location: signup.php");
    exit();
}
include 'includes/header.php'; 
include 'database/db_connect.php';

// Get current user data
$email = $_SESSION['user'];
$user_query = $conn->query("SELECT * FROM users WHERE email='$email'");
$current_user = $user_query->fetch_assoc();
$current_user_id = $current_user['id'];
$current_user_gender = $current_user['gender'];

// Get current user profile
$profile_query = $conn->query("SELECT * FROM user_data WHERE user_id=$current_user_id");
$current_profile = $profile_query ? $profile_query->fetch_assoc() : null;

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

// Handle add friend
if(isset($_GET['add_friend'])) {
    $friend_id = intval($_GET['add_friend']);
    
    // First check if the user exists
    $user_exists = $conn->query("SELECT id FROM users WHERE id=$friend_id");
    if(!$user_exists || $user_exists->num_rows == 0) {
        echo "<script>alert('User not found!'); window.location='my_matches.php';</script>";
        exit();
    }
    
    // Check if already friends
    $check = $conn->query("SELECT * FROM user_friends WHERE user_id=$current_user_id AND friend_id=$friend_id");
    if($check->num_rows == 0) {
        $result = $conn->query("INSERT INTO user_friends (user_id, friend_id) VALUES ($current_user_id, $friend_id)");
        if($result) {
            echo "<script>alert('Friend added successfully!'); window.location='my_matches.php';</script>";
        } else {
            echo "<script>alert('Error adding friend: " . $conn->error . "'); window.location='my_matches.php';</script>";
        }
    } else {
        echo "<script>alert('Already in your friends list!'); window.location='my_matches.php';</script>";
    }
}

// Handle add to favourites
if(isset($_GET['add_favourite'])) {
    $favourite_id = intval($_GET['add_favourite']);
    
    // First check if the user exists
    $user_exists = $conn->query("SELECT id FROM users WHERE id=$favourite_id");
    if(!$user_exists || $user_exists->num_rows == 0) {
        echo "<script>alert('User not found!'); window.location='my_matches.php';</script>";
        exit();
    }
    
    // Check if already in favourites
    $check = $conn->query("SELECT * FROM user_favourites WHERE user_id=$current_user_id AND favourite_id=$favourite_id");
    if($check->num_rows == 0) {
        $result = $conn->query("INSERT INTO user_favourites (user_id, favourite_id) VALUES ($current_user_id, $favourite_id)");
        if($result) {
            echo "<script>alert('Added to favourites!'); window.location='my_matches.php';</script>";
        } else {
            echo "<script>alert('Error adding to favourites: " . $conn->error . "'); window.location='my_matches.php';</script>";
        }
    } else {
        echo "<script>alert('Already in your favourites!'); window.location='my_matches.php';</script>";
    }
}

// Get opposite gender users (potential matches)
$opposite_gender = ($current_user_gender == 'male') ? 'female' : 'male';

// Get all potential matches
$matches_query = "SELECT u.*, ud.* 
                  FROM users u 
                  LEFT JOIN user_data ud ON u.id = ud.user_id 
                  WHERE u.gender = '$opposite_gender' 
                  AND u.id != $current_user_id 
                  AND u.profile_completed = 1
                  ORDER BY u.created_at DESC 
                  LIMIT 50";
$matches_result = $conn->query($matches_query);

$matches = [];
if($matches_result) {
    while($row = $matches_result->fetch_assoc()) {
        // Get user's photo
        $photo_query = $conn->query("SELECT photo_path FROM user_photos WHERE user_id={$row['id']} LIMIT 1");
        $photo = $photo_query && $photo_query->num_rows > 0 ? $photo_query->fetch_assoc()['photo_path'] : null;
        $row['photo'] = $photo;
        $matches[] = $row;
    }
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .matches-container {
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
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-banner {
        background: linear-gradient(90deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
        padding: 15px 30px;
        border-radius: 25px;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
    }

    .banner-text {
        color: #8b4513;
        font-size: 18px;
        font-weight: bold;
        margin: 0;
        font-style: italic;
    }

    .matches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .match-card {
        position: relative;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .match-card:hover {
        transform: scale(1.05);
    }

    .match-photo {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 20px;
        overflow: hidden;
        border: 4px solid #ffd700;
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        position: relative;
    }

    .match-photo img {
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

    .match-name {
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

    .match-age {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 215, 0, 0.9);
        color: #000;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
    }

    .add-friend-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(107, 142, 35, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 18px;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .add-friend-btn:hover {
        background: rgba(107, 142, 35, 1);
        transform: scale(1.1);
    }

    .add-favourite-btn {
        position: absolute;
        bottom: 40px;
        right: 10px;
        background: rgba(255, 105, 180, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 18px;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .add-favourite-btn:hover {
        background: rgba(255, 105, 180, 1);
        transform: scale(1.1);
    }

    .no-matches {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
    }

    .no-matches-text {
        color: #ffd700;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .no-matches-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .matches-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 15px;
        }
    }
</style>

<div class="matches-container">
    <div class="page-header">
        <h1 class="page-title">💕 My Matches</h1>
    </div>

    <?php if(count($matches) > 0): ?>
        <div class="section-banner">
            <p class="banner-text">Exclusively - Just For You</p>
        </div>

        <div class="matches-grid">
            <?php foreach($matches as $match): 
                $age = date('Y') - ($match['year'] ?? 2000);
                $username = $match['username'] ?? 'User' . $match['id'];
            ?>
                <div class="match-card">
                    <div class="match-photo" onclick="window.location='view_profile.php?id=<?php echo $match['id']; ?>'">
                        <?php if($match['photo']): ?>
                            <img src="<?php echo $match['photo']; ?>" alt="<?php echo $username; ?>">
                        <?php else: ?>
                            <div class="no-photo">
                                <?php echo $match['gender'] == 'female' ? '👩' : '👨'; ?>
                            </div>
                        <?php endif; ?>
                        
                        <button class="add-friend-btn" onclick="event.stopPropagation(); window.location='?add_friend=<?php echo $match['id']; ?>'" title="Add Friend">+</button>
                        <button class="add-favourite-btn" onclick="event.stopPropagation(); window.location='?add_favourite=<?php echo $match['id']; ?>'" title="Add to Favourites">⭐</button>
                        <div class="match-age"><?php echo $age; ?></div>
                        <div class="match-name"><?php echo htmlspecialchars($username); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-matches">
            <div class="no-matches-icon">💔</div>
            <p class="no-matches-text">No matches found yet!</p>
            <p style="color: #999;">Complete your profile to get better matches.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
