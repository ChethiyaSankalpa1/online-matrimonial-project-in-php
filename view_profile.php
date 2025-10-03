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

// Get profile ID from URL
if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$profile_id = intval($_GET['id']);

// Get user data
$user_query = $conn->query("SELECT * FROM users WHERE id=$profile_id");
if(!$user_query) {
    // Query failed - show error for debugging
    die("Database error: " . $conn->error);
}
if($user_query->num_rows == 0) {
    // User not found
    die("Profile not found. User ID: $profile_id does not exist in the database.");
}
$user = $user_query->fetch_assoc();

// Get profile data
$profile_query = $conn->query("SELECT * FROM user_data WHERE user_id=$profile_id");
$profile = $profile_query ? $profile_query->fetch_assoc() : null;

// Get user photos
$photos_query = $conn->query("SELECT * FROM user_photos WHERE user_id=$profile_id ORDER BY uploaded_at DESC LIMIT 1");
$photo = null;
if($photos_query && $photos_query->num_rows > 0) {
    $photo = $photos_query->fetch_assoc();
}

// Get online status
$minutes_ago = null;
if(isset($user['last_seen'])) {
    $last_seen = new DateTime($user['last_seen']);
    $now = new DateTime();
    $minutes_ago = $now->getTimestamp() - $last_seen->getTimestamp();
    $minutes_ago = floor($minutes_ago / 60);
}

function getOnlineStatus($minutes_ago) {
    if ($minutes_ago === null) {
        return ['text' => 'offline', 'class' => 'offline', 'color' => '#999'];
    } elseif ($minutes_ago < 5) {
        return ['text' => 'Online now', 'class' => 'online', 'color' => '#4caf50'];
    } elseif ($minutes_ago < 60) {
        return ['text' => 'Active ' . $minutes_ago . ' min ago', 'class' => 'recent', 'color' => '#ffd700'];
    } elseif ($minutes_ago < 1440) {
        $hours = floor($minutes_ago / 60);
        return ['text' => 'Active ' . $hours . 'h ago', 'class' => 'recent', 'color' => '#ffd700'];
    } else {
        $days = floor($minutes_ago / 1440);
        return ['text' => 'Active ' . $days . 'd ago', 'class' => 'offline', 'color' => '#999'];
    }
}

$onlineStatus = getOnlineStatus($minutes_ago);

// Calculate age
$age = isset($user['year']) ? (date('Y') - $user['year']) : 'N/A';

// Get location
$location = [];
if(!empty($user['city'])) $location[] = $user['city'];
if(!empty($user['country'])) $location[] = $user['country'];
$location_str = !empty($location) ? implode(', ', $location) : 'Not specified';

// Check if already friends
$is_friend = false;
$friend_check = $conn->query("SELECT * FROM user_friends WHERE user_id=$current_user_id AND friend_id=$profile_id");
if($friend_check && $friend_check->num_rows > 0) {
    $is_friend = true;
}

// Check if already in favourites
$is_favourite = false;
$fav_check = $conn->query("SELECT * FROM user_favourites WHERE user_id=$current_user_id AND favourite_id=$profile_id");
if($fav_check && $fav_check->num_rows > 0) {
    $is_favourite = true;
}

// Generate automatic "About Me" description
function generateAboutMe($user, $profile) {
    if(!$profile) return "This user hasn't completed their profile yet.";
    
    $age = isset($user['year']) ? (date('Y') - $user['year']) : 'N/A';
    $gender = $user['gender'] ?? 'person';
    $ethnicity = $profile['ethnicity'] ?? 'Sri Lankan';
    $education = $profile['education'] ?? '';
    $career = $profile['career'] ?? '';
    $religion = $profile['religion'] ?? '';
    $personality = $profile['personality'] ?? '';
    $smoking = $profile['smoking'] ?? '';
    $drinking = $profile['drinking'] ?? '';
    
    $description = "Hello! I'm a {$age}-year-old {$ethnicity} who is young and ambitious, balancing tradition with a modern outlook. ";
    
    if(!empty($education) && !empty($career)) {
        $description .= "I have completed {$education} and currently working in {$career}. ";
    }
    
    $description .= "I'm on the lookout for a friendship that could blossom into something more serious, potentially leading to marriage. ";
    
    if(!empty($personality)) {
        $traits = strtolower($personality);
        $description .= "I appreciate creativity in a partner and love engaging with those who have a mix of {$traits} traits. ";
    }
    
    $description .= "Kindness and empathy are qualities that I find incredibly attractive, as I believe they form the foundation of meaningful connections. ";
    
    if(!empty($religion)) {
        $description .= "While I don't place a heavy emphasis on sharing the same religious or spiritual beliefs, I value open-mindedness and respect for different perspectives. ";
    }
    
    $description .= "In my downtime, I enjoy various activities that keep me engaged and entertained. ";
    
    if($smoking == 'Non-smoker' && $drinking == 'Non-drinker') {
        $description .= "I maintain a healthy lifestyle as a non-smoker and non-drinker. ";
    }
    
    $description .= "Overall, I keep life simple yet meaningful, chasing my dreams with curiosity while carrying a touch of mystery. If you share similar interests and values, let's connect!";
    
    return $description;
}

$about_me = generateAboutMe($user, $profile);

include 'includes/header.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #1a0000 0%, #4a0000 50%, #2d0000 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .profile-header {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.2) 0%, rgba(139, 0, 0, 0.2) 100%);
        backdrop-filter: blur(10px);
        padding: 25px 35px;
        border-radius: 15px;
        border: 1px solid rgba(220, 20, 60, 0.5);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(220, 20, 60, 0.3);
    }

    .profile-title {
        color: #ffd700;
        font-size: 28px;
        font-weight: bold;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .back-btn {
        background: rgba(139, 69, 19, 0.6);
        color: #ffd700;
        padding: 10px 20px;
        border: 2px solid #8b4513;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .back-btn:hover {
        background: rgba(139, 69, 19, 0.8);
        border-color: #ffd700;
    }

    .profile-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 20px;
    }

    .profile-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .photo-card {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.2) 0%, rgba(139, 0, 0, 0.2) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 20, 60, 0.5);
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 8px 32px rgba(220, 20, 60, 0.3);
        position: relative;
        overflow: hidden;
    }

    .photo-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(220, 20, 60, 0.2), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .profile-photo {
        width: 250px;
        height: 250px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #dc143c;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.3) 0%, rgba(220, 20, 60, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 40px rgba(220, 20, 60, 0.5), inset 0 0 20px rgba(220, 20, 60, 0.2);
        position: relative;
        z-index: 1;
    }

    .profile-photo::after {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border-radius: 50%;
        border: 2px solid rgba(220, 20, 60, 0.4);
        z-index: -1;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-photo .placeholder {
        font-size: 80px;
        color: #8b4513;
    }

    .user-name {
        color: #ffd700;
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 8px;
        text-shadow: 0 2px 10px rgba(255, 215, 0, 0.5);
        position: relative;
        z-index: 1;
    }

    .online-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 15px;
        backdrop-filter: blur(5px);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .user-basic-info {
        color: #999;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .action-btn {
        background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
        color: white;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(220, 20, 60, 0.5);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .action-btn:hover::before {
        left: 100%;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 20, 60, 0.7);
    }

    .action-btn.secondary {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.3) 0%, rgba(220, 20, 60, 0.2) 100%);
        border: 1px solid rgba(220, 20, 60, 0.6);
        color: #ff6b6b;
        box-shadow: 0 4px 15px rgba(220, 20, 60, 0.3);
    }

    .action-btn.secondary:hover {
        box-shadow: 0 6px 20px rgba(220, 20, 60, 0.5);
    }

    .profile-main {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .info-card {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.15) 0%, rgba(139, 0, 0, 0.15) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 20, 60, 0.4);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(220, 20, 60, 0.3);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 48px rgba(220, 20, 60, 0.4);
    }

    .card-header {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.3) 0%, rgba(220, 20, 60, 0.2) 100%);
        padding: 18px 25px;
        border-bottom: 1px solid rgba(220, 20, 60, 0.5);
    }

    .card-title {
        color: #ffd700;
        font-size: 18px;
        font-weight: bold;
        margin: 0;
    }

    .card-content {
        padding: 25px;
    }

    .about-text {
        color: #ffd700;
        font-size: 15px;
        line-height: 1.8;
        font-style: italic;
        text-align: justify;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        color: #999;
        font-size: 12px;
        text-transform: uppercase;
    }

    .info-value {
        color: #ffd700;
        font-size: 16px;
        font-weight: bold;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: rgba(107, 142, 35, 0.2);
        border: 2px solid #6b8e23;
        color: #90ee90;
    }

    .alert-error {
        background: rgba(255, 0, 0, 0.2);
        border: 2px solid #ff0000;
        color: #ff6b6b;
    }

    .alert-info {
        background: rgba(255, 215, 0, 0.2);
        border: 2px solid #ffd700;
        color: #ffd700;
    }

    .action-btn.disabled {
        background: rgba(100, 100, 100, 0.4);
        border-color: #666;
        color: #999;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .action-btn.disabled:hover {
        transform: none;
    }

    @media (max-width: 968px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <h1 class="profile-title">
            <span>👤</span>
            User Profile
        </h1>
        <a href="javascript:history.back()" class="back-btn">← Back</a>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <span>✓</span>
            <strong><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></strong>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <span>✗</span>
            <strong><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></strong>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['info'])): ?>
        <div class="alert alert-info">
            <span>ℹ</span>
            <strong><?php echo $_SESSION['info']; unset($_SESSION['info']); ?></strong>
        </div>
    <?php endif; ?>

    <div class="profile-layout">
        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="photo-card">
                <div class="profile-photo">
                    <?php if($photo): ?>
                        <img src="<?php echo $photo['photo_path']; ?>" alt="<?php echo htmlspecialchars($user['username'] ?? 'User'); ?>">
                    <?php else: ?>
                        <div class="placeholder">
                            <?php echo $user['gender'] == 'female' ? '👩' : '👨'; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="user-name"><?php echo htmlspecialchars($user['username'] ?? 'User ' . $user['id']); ?></div>
                <div class="online-status" style="background: <?php echo $onlineStatus['color']; ?>20; border: 1px solid <?php echo $onlineStatus['color']; ?>;">
                    <span class="status-dot" style="background: <?php echo $onlineStatus['color']; ?>;"></span>
                    <span style="color: <?php echo $onlineStatus['color']; ?>;"><?php echo $onlineStatus['text']; ?></span>
                </div>
                <div class="user-basic-info">
                    <?php if(isset($user['gender'])): ?>
                        <?php echo ucfirst($user['gender']); ?>
                    <?php endif; ?>
                    <?php if($age != 'N/A'): ?>
                        • <?php echo $age; ?> years
                    <?php endif; ?>
                    <br>
                    📍 <?php echo htmlspecialchars($location_str); ?>
                </div>

                <div class="action-buttons">
                    <a href="send_message.php?to=<?php echo $profile_id; ?>" class="action-btn">
                        <span>💬</span> Send Message
                    </a>
                    <?php if($is_friend): ?>
                        <a href="#" class="action-btn secondary disabled" onclick="return false;">
                            <span>✓</span> Already Friends
                        </a>
                    <?php else: ?>
                        <a href="add_friend.php?id=<?php echo $profile_id; ?>" class="action-btn secondary">
                            <span>👥</span> Add Friend
                        </a>
                    <?php endif; ?>
                    
                    <?php if($is_favourite): ?>
                        <a href="#" class="action-btn secondary disabled" onclick="return false;">
                            <span>⭐</span> In Favourites
                        </a>
                    <?php else: ?>
                        <a href="add_favourite.php?id=<?php echo $profile_id; ?>" class="action-btn secondary">
                            <span>⭐</span> Add to Favourites
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="profile-main">
            <!-- About Section -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">About Me</h2>
                </div>
                <div class="card-content">
                    <p class="about-text"><?php echo $about_me; ?></p>
                </div>
            </div>

            <!-- Personal Information -->
            <?php if($profile): ?>
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">Personal Information</h2>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <?php if(isset($profile['height'])): ?>
                        <div class="info-item">
                            <div class="info-label">Height</div>
                            <div class="info-value"><?php echo $profile['height']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['weight'])): ?>
                        <div class="info-item">
                            <div class="info-label">Weight</div>
                            <div class="info-value"><?php echo $profile['weight']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['figure'])): ?>
                        <div class="info-item">
                            <div class="info-label">Figure</div>
                            <div class="info-value"><?php echo $profile['figure']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['complexion'])): ?>
                        <div class="info-item">
                            <div class="info-label">Complexion</div>
                            <div class="info-value"><?php echo $profile['complexion']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['status'])): ?>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value"><?php echo $profile['status']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['education'])): ?>
                        <div class="info-item">
                            <div class="info-label">Education</div>
                            <div class="info-value"><?php echo $profile['education']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['career'])): ?>
                        <div class="info-item">
                            <div class="info-label">Career</div>
                            <div class="info-value"><?php echo $profile['career']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['religion'])): ?>
                        <div class="info-item">
                            <div class="info-label">Religion</div>
                            <div class="info-value"><?php echo $profile['religion']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['ethnicity'])): ?>
                        <div class="info-item">
                            <div class="info-label">Ethnicity</div>
                            <div class="info-value"><?php echo $profile['ethnicity']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['caste'])): ?>
                        <div class="info-item">
                            <div class="info-label">Caste</div>
                            <div class="info-value"><?php echo $profile['caste']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['residency'])): ?>
                        <div class="info-item">
                            <div class="info-label">Residency</div>
                            <div class="info-value"><?php echo $profile['residency']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['smoking'])): ?>
                        <div class="info-item">
                            <div class="info-label">Smoking</div>
                            <div class="info-value"><?php echo $profile['smoking']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['drinking'])): ?>
                        <div class="info-item">
                            <div class="info-label">Drinking</div>
                            <div class="info-value"><?php echo $profile['drinking']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['children'])): ?>
                        <div class="info-item">
                            <div class="info-label">Children</div>
                            <div class="info-value"><?php echo $profile['children']; ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($profile['personality'])): ?>
                        <div class="info-item">
                            <div class="info-label">Personality</div>
                            <div class="info-value"><?php echo $profile['personality']; ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
