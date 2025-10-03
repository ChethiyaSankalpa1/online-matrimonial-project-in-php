<?php 
session_start();
include 'includes/header.php'; 
include 'database/db_connect.php';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user']);

// Fetch users from database with profile photos
$sql = "SELECT u.*, ud.height, ud.status, ud.education, ud.career, ud.religion, ud.ethnicity,
        up.photo_path,
        TIMESTAMPDIFF(MINUTE, u.last_seen, NOW()) as minutes_ago
        FROM users u 
        LEFT JOIN user_data ud ON u.id = ud.user_id 
        LEFT JOIN user_photos up ON u.id = up.user_id
        WHERE u.profile_completed = 1 
        GROUP BY u.id
        ORDER BY u.last_seen DESC 
        LIMIT 10";
$result = $conn->query($sql);

// Function to calculate age from birth date
function calculateAge($day, $month, $year) {
    $birthDate = new DateTime("$year-$month-$day");
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}

// Function to get online status text
function getOnlineStatus($minutes_ago) {
    if ($minutes_ago === null) {
        return ['text' => 'offline', 'class' => 'offline'];
    } elseif ($minutes_ago < 5) {
        return ['text' => 'just now', 'class' => 'online'];
    } elseif ($minutes_ago < 60) {
        return ['text' => $minutes_ago . ' minutes ago', 'class' => 'recent'];
    } elseif ($minutes_ago < 1440) { // less than 24 hours
        $hours = floor($minutes_ago / 60);
        return ['text' => $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago', 'class' => 'recent'];
    } else {
        $days = floor($minutes_ago / 1440);
        return ['text' => $days . ' day' . ($days > 1 ? 's' : '') . ' ago', 'class' => 'offline'];
    }
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a3a1a 0%, #2d4a2d 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .search-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .header-box {
        background: linear-gradient(135deg, #8b4513 0%, #a0522d 100%);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .header-title {
        color: #ffd700;
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .header-subtitle {
        color: #fff;
        font-size: 14px;
        line-height: 1.6;
    }

    .header-cta {
        color: #ffd700;
        font-size: 13px;
        margin-top: 5px;
    }

    .filter-bar {
        background: rgba(139, 0, 0, 0.8);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filter-item {
        color: #fff;
        font-size: 14px;
    }

    .profile-card {
        background: linear-gradient(135deg, #4a1a1a 0%, #6b2020 100%);
        border: 3px solid #8b4513;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        gap: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .profile-left {
        flex-shrink: 0;
        width: 140px;
    }

    .profile-photo {
        width: 120px;
        height: 140px;
        background: #ddd;
        border: 3px solid #8b4513;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        overflow: hidden;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-photo-placeholder {
        font-size: 60px;
        color: #999;
    }

    .last-seen {
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        padding: 5px;
        border-radius: 5px;
        font-size: 11px;
        text-align: center;
    }

    .online-indicator {
        color: #ffd700;
        font-size: 12px;
    }

    .online-indicator.online {
        color: #4caf50;
        font-weight: bold;
    }

    .online-indicator.recent {
        color: #ffd700;
    }

    .online-indicator.offline {
        color: #999;
    }

    .profile-middle {
        flex: 1;
    }

    .profile-name {
        color: #ffd700;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .profile-name .verified {
        color: #4caf50;
        font-size: 16px;
    }

    .profile-details {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 8px 15px;
        font-size: 13px;
    }

    .detail-label {
        color: #ffd700;
        font-weight: bold;
    }

    .detail-value {
        color: #fff;
    }

    .profile-right {
        flex-shrink: 0;
        width: 150px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .action-btn {
        background: rgba(139, 69, 19, 0.6);
        color: #ffd700;
        padding: 8px 12px;
        border: 2px solid #8b4513;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .action-btn:hover {
        background: rgba(139, 69, 19, 0.8);
        border-color: #ffd700;
    }

    .action-icon {
        font-size: 14px;
    }

    .pagination {
        background: linear-gradient(135deg, #4a1a1a 0%, #6b2020 100%);
        border: 3px solid #8b4513;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin-top: 20px;
    }

    .pagination-text {
        color: #fff;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .pagination-buttons {
        display: flex;
        justify-content: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .page-btn {
        background: rgba(139, 69, 19, 0.6);
        color: #ffd700;
        padding: 8px 12px;
        border: 2px solid #8b4513;
        border-radius: 5px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 35px;
    }

    .page-btn:hover,
    .page-btn.active {
        background: rgba(139, 69, 19, 0.9);
        border-color: #ffd700;
    }


    @media (max-width: 768px) {
        .profile-card {
            flex-direction: column;
        }

        .profile-right {
            width: 100%;
        }
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: linear-gradient(135deg, #4a1a1a 0%, #6b2020 100%);
        border: 3px solid #8b4513;
        border-radius: 15px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-icon {
        font-size: 60px;
        margin-bottom: 20px;
    }

    .modal-title {
        color: #ffd700;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .modal-message {
        color: #fff;
        font-size: 16px;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .modal-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .modal-btn {
        padding: 12px 30px;
        border: 2px solid #8b4513;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .modal-btn-yes {
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: #fff;
    }

    .modal-btn-yes:hover {
        background: linear-gradient(135deg, #7a9d2a 0%, #6b8e23 100%);
        box-shadow: 0 4px 15px rgba(107, 142, 35, 0.4);
    }

    .modal-btn-no {
        background: rgba(139, 69, 19, 0.6);
        color: #ffd700;
    }

    .modal-btn-no:hover {
        background: rgba(139, 69, 19, 0.8);
        border-color: #ffd700;
    }

    .action-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .login-required-badge {
        background: rgba(255, 215, 0, 0.2);
        color: #ffd700;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 12px;
        margin-bottom: 10px;
        border: 1px solid #ffd700;
    }
</style>

<div class="search-container">
    <div class="header-box">
        <h1 class="header-title">Sri Lanka Marriage Proposals</h1>
        <p class="header-subtitle">
            Sri Lanka's largest collection of Sinhalese Matrimony. 33121+ sinhala singles actively looking for lifetime partners.
        </p>
        <?php if (!$is_logged_in): ?>
        <p class="header-cta">Sign up to Review Proposals and It's 100% Free..!</p>
        <?php endif; ?>
    </div>

    <div class="filter-bar">
        <div class="filter-item">Showing: <strong><?php echo $result->num_rows; ?> Active Profiles</strong></div>
    </div>

    <?php 
    if ($result && $result->num_rows > 0) {
        while($user = $result->fetch_assoc()) {
            $age = calculateAge($user['day'], $user['month'], $user['year']);
            $onlineStatus = getOnlineStatus($user['minutes_ago']);
            $location = ($user['city'] ? $user['city'] : 'Unknown') . ' in ' . ($user['country'] ? $user['country'] : 'Unknown');
    ?>
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-left">
            <div class="profile-photo">
                <?php if (!empty($user['photo_path']) && file_exists($user['photo_path'])): ?>
                    <img src="<?php echo htmlspecialchars($user['photo_path']); ?>" alt="Profile Photo">
                <?php else: ?>
                    <span class="profile-photo-placeholder">
                        <?php echo $user['gender'] == 'male' ? '👨' : '👩'; ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="last-seen">
                Last seen<br>
                <span class="online-indicator <?php echo $onlineStatus['class']; ?>">⏰ <?php echo $onlineStatus['text']; ?></span>
            </div>
        </div>
        <div class="profile-middle">
            <div class="profile-name"><?php echo htmlspecialchars($user['username']); ?> <span class="verified">▲</span></div>
            <div class="profile-details">
                <span class="detail-label">Age:</span>
                <span class="detail-value"><?php echo $age . ' - ' . ($user['height'] ?? 'N/A'); ?></span>
                
                <span class="detail-label">City:</span>
                <span class="detail-value"><?php echo htmlspecialchars($location); ?></span>
                
                <span class="detail-label">Education:</span>
                <span class="detail-value"><?php echo htmlspecialchars($user['education'] ?? 'Not specified'); ?></span>
                
                <span class="detail-label">Community:</span>
                <span class="detail-value"><?php echo htmlspecialchars($user['ethnicity'] ?? 'Not specified'); ?></span>
                
                <span class="detail-label">Career:</span>
                <span class="detail-value"><?php echo htmlspecialchars($user['career'] ?? 'Not specified'); ?></span>
                
                <span class="detail-label">Marital Status:</span>
                <span class="detail-value"><?php echo htmlspecialchars($user['status'] ?? 'Not specified'); ?></span>
            </div>
        </div>
        <div class="profile-right">
            <?php if (!$is_logged_in): ?>
                <div class="login-required-badge">🔒 Sign up to unlock all features</div>
            <?php endif; ?>
            <a href="<?php echo $is_logged_in ? 'view_profile.php?id=' . $user['id'] : '#'; ?>" class="action-btn <?php echo !$is_logged_in ? 'disabled' : ''; ?>" <?php echo !$is_logged_in ? 'onclick="showSignupModal(event)"' : ''; ?>><span class="action-icon">👁️</span> View Profile</a>
            <?php if ($is_logged_in): ?>
            <a href="#" class="action-btn"><span class="action-icon">💬</span> Chat Message</a>
            <a href="#" class="action-btn"><span class="action-icon">✉️</span> Send Mail</a>
            <a href="#" class="action-btn"><span class="action-icon">👋</span> Show Interest</a>
            <a href="#" class="action-btn"><span class="action-icon">⭐</span> Add Favourite</a>
            <?php else: ?>
            <a href="#" class="action-btn disabled" onclick="showSignupModal(event)"><span class="action-icon">💬</span> Chat Message</a>
            <a href="#" class="action-btn disabled" onclick="showSignupModal(event)"><span class="action-icon">✉️</span> Send Mail</a>
            <a href="#" class="action-btn disabled" onclick="showSignupModal(event)"><span class="action-icon">👋</span> Show Interest</a>
            <a href="#" class="action-btn disabled" onclick="showSignupModal(event)"><span class="action-icon">⭐</span> Add Favourite</a>
            <?php endif; ?>
        </div>
    </div>
    <?php 
        } // End while loop
    } else {
        echo '<p style="color: #ffd700; text-align: center; padding: 40px;">No users found.</p>';
    }
    ?>


    <!-- Pagination -->
    <div class="pagination">
        <p class="pagination-text"><?php echo $result->num_rows; ?> active profiles found</p>
        <div class="pagination-buttons">
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">4</button>
            <button class="page-btn">5</button>
            <button class="page-btn">6</button>
            <button class="page-btn">7</button>
            <button class="page-btn">8</button>
            <button class="page-btn">9</button>
            <button class="page-btn">10</button>
            <button class="page-btn">Next →</button>
        </div>
    </div>
</div>

<!-- Sign Up Modal -->
<div class="modal-overlay" id="signupModal">
    <div class="modal-content">
        <div class="modal-icon">🔒</div>
        <h2 class="modal-title">Sign Up Required!</h2>
        <p class="modal-message">
            To view profiles, chat, send messages, and access all features, you need to create a free account.
            <br><br>
            <strong>Would you like to sign up now?</strong>
        </p>
        <div class="modal-buttons">
            <a href="signin.php" class="modal-btn modal-btn-yes">Yes, Sign Up</a>
            <button class="modal-btn modal-btn-no" onclick="closeSignupModal()">No, Maybe Later</button>
        </div>
    </div>
</div>

<script>
function showSignupModal(event) {
    event.preventDefault();
    document.getElementById('signupModal').classList.add('active');
}

function closeSignupModal() {
    document.getElementById('signupModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('signupModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSignupModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSignupModal();
    }
});

<?php if ($is_logged_in): ?>
// Update online status every 2 minutes for logged-in users
setInterval(function() {
    fetch('update_online_status.php')
        .then(response => response.json())
        .then(data => console.log('Online status updated:', data))
        .catch(error => console.error('Error updating status:', error));
}, 120000); // 2 minutes

// Update immediately on page load
fetch('update_online_status.php')
    .then(response => response.json())
    .then(data => console.log('Initial status update:', data))
    .catch(error => console.error('Error:', error));
<?php endif; ?>

// Auto-refresh page every 5 minutes to show updated online statuses
setInterval(function() {
    location.reload();
}, 300000); // 5 minutes
</script>

<?php include 'includes/footer.php'; ?>
