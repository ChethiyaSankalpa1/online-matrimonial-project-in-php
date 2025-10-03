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

// Handle remove favourite
if(isset($_GET['remove'])) {
    $favourite_id = intval($_GET['remove']);
    $conn->query("DELETE FROM user_favourites WHERE user_id=$current_user_id AND favourite_id=$favourite_id");
    echo "<script>alert('Removed from favourites!'); window.location='my_favourites.php';</script>";
}

// Get all favourites
$favourites_query = "SELECT u.*, ud.* 
                     FROM user_favourites uf
                     JOIN users u ON uf.favourite_id = u.id
                     LEFT JOIN user_data ud ON u.id = ud.user_id
                     WHERE uf.user_id = $current_user_id
                     ORDER BY uf.created_at DESC";
$favourites_result = $conn->query($favourites_query);

$favourites = [];
if($favourites_result) {
    while($row = $favourites_result->fetch_assoc()) {
        $photo_query = $conn->query("SELECT photo_path FROM user_photos WHERE user_id={$row['id']} LIMIT 1");
        $photo = $photo_query && $photo_query->num_rows > 0 ? $photo_query->fetch_assoc()['photo_path'] : null;
        $row['photo'] = $photo;
        $favourites[] = $row;
    }
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .favourites-container {
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

    .favourites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 20px;
    }

    .favourite-card {
        position: relative;
        transition: transform 0.3s;
    }

    .favourite-card:hover {
        transform: scale(1.05);
    }

    .favourite-photo {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 20px;
        overflow: hidden;
        border: 4px solid #ff69b4;
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        position: relative;
        cursor: pointer;
    }

    .favourite-photo img {
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

    .favourite-name {
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

    .favourite-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 105, 180, 0.9);
        color: white;
        padding: 5px;
        border-radius: 50%;
        font-size: 16px;
        z-index: 5;
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        left: 10px;
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

    .no-favourites {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
    }

    .no-favourites-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .no-favourites-text {
        color: #ffd700;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .find-matches-btn {
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

    .find-matches-btn:hover {
        transform: scale(1.05);
    }
</style>

<div class="favourites-container">
    <div class="page-header">
        <h1 class="page-title">⭐ My Favourites</h1>
    </div>

    <?php if(count($favourites) > 0): ?>
        <div class="section-banner">
            <p class="banner-text">You have <?php echo count($favourites); ?> favourite<?php echo count($favourites) > 1 ? 's' : ''; ?></p>
        </div>

        <div class="favourites-grid">
            <?php foreach($favourites as $favourite): 
                $username = $favourite['username'] ?? 'User' . $favourite['id'];
            ?>
                <div class="favourite-card">
                    <div class="favourite-photo" onclick="window.location='view_profile.php?id=<?php echo $favourite['id']; ?>'">
                        <?php if($favourite['photo']): ?>
                            <img src="<?php echo $favourite['photo']; ?>" alt="<?php echo $username; ?>">
                        <?php else: ?>
                            <div class="no-photo">
                                <?php echo $favourite['gender'] == 'female' ? '👩' : '👨'; ?>
                            </div>
                        <?php endif; ?>
                        
                        <button class="remove-btn" onclick="event.stopPropagation(); if(confirm('Remove from favourites?')) window.location='?remove=<?php echo $favourite['id']; ?>'">×</button>
                        <div class="favourite-badge">⭐</div>
                        <div class="favourite-name"><?php echo htmlspecialchars($username); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-favourites">
            <div class="no-favourites-icon">⭐</div>
            <p class="no-favourites-text">You don't have any favourites yet!</p>
            <p style="color: #999;">Start adding favourites from your matches.</p>
            <a href="my_matches.php" class="find-matches-btn">Find Matches</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
