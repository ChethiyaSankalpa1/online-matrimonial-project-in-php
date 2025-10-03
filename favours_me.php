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

// Get users who added me to their favourites
$favours_query = "SELECT u.*, ud.* 
                  FROM user_favourites uf
                  JOIN users u ON uf.user_id = u.id
                  LEFT JOIN user_data ud ON u.id = ud.user_id
                  WHERE uf.favourite_id = $current_user_id
                  ORDER BY uf.created_at DESC";
$favours_result = $conn->query($favours_query);

$favours = [];
if($favours_result) {
    while($row = $favours_result->fetch_assoc()) {
        $photo_query = $conn->query("SELECT photo_path FROM user_photos WHERE user_id={$row['id']} LIMIT 1");
        $photo = $photo_query && $photo_query->num_rows > 0 ? $photo_query->fetch_assoc()['photo_path'] : null;
        $row['photo'] = $photo;
        $favours[] = $row;
    }
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .favours-container {
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

    .favours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 20px;
    }

    .favour-card {
        position: relative;
        transition: transform 0.3s;
    }

    .favour-card:hover {
        transform: scale(1.05);
    }

    .favour-photo {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 20px;
        overflow: hidden;
        border: 4px solid #ff1493;
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        position: relative;
        cursor: pointer;
    }

    .favour-photo img {
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

    .favour-name {
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

    .favour-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 20, 147, 0.9);
        color: white;
        padding: 5px;
        border-radius: 50%;
        font-size: 16px;
        z-index: 5;
    }

    .no-favours {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
    }

    .no-favours-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .no-favours-text {
        color: #ffd700;
        font-size: 20px;
        margin-bottom: 20px;
    }
</style>

<div class="favours-container">
    <div class="page-header">
        <h1 class="page-title">💖 Favours Me</h1>
    </div>

    <?php if(count($favours) > 0): ?>
        <div class="section-banner">
            <p class="banner-text"><?php echo count($favours); ?> user<?php echo count($favours) > 1 ? 's' : ''; ?> added you to favourites!</p>
        </div>

        <div class="favours-grid">
            <?php foreach($favours as $favour): 
                $username = $favour['username'] ?? 'User' . $favour['id'];
            ?>
                <div class="favour-card">
                    <div class="favour-photo" onclick="window.location='view_profile.php?id=<?php echo $favour['id']; ?>'">
                        <?php if($favour['photo']): ?>
                            <img src="<?php echo $favour['photo']; ?>" alt="<?php echo $username; ?>">
                        <?php else: ?>
                            <div class="no-photo">
                                <?php echo $favour['gender'] == 'female' ? '👩' : '👨'; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="favour-badge">💖</div>
                        <div class="favour-name"><?php echo htmlspecialchars($username); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-favours">
            <div class="no-favours-icon">💖</div>
            <p class="no-favours-text">No one has added you to favourites yet!</p>
            <p style="color: #999;">Complete your profile and add a photo to get more attention.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
