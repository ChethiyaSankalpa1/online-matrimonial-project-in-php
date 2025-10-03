<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location: signup.php");
    exit();
}
include 'includes/header.php'; 
include 'database/db_connect.php';

// Get user data
$email = $_SESSION['user'];
$user_query = $conn->query("SELECT * FROM users WHERE email='$email'");
$user = $user_query->fetch_assoc();
$user_id = $user['id'];

// Get profile data
$profile_query = $conn->query("SELECT * FROM user_data WHERE user_id=$user_id");
$profile = $profile_query->fetch_assoc();
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        display: flex;
        max-width: 1400px;
        margin: 20px auto;
        gap: 0;
    }

    /* Sidebar adjustments for dashboard */
    .sidebar {
        border-radius: 10px 0 0 10px;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-left: none;
        border-radius: 0 10px 10px 0;
        padding: 30px;
    }

    .alert-box {
        background: rgba(255, 215, 0, 0.1);
        border: 2px solid #ffd700;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
        color: #ffd700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tabs-container {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .tab-btn {
        background: rgba(139, 69, 19, 0.4);
        color: #ffd700;
        padding: 12px 20px;
        border: 2px solid #8b4513;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .tab-btn:hover {
        background: rgba(139, 69, 19, 0.6);
        transform: translateY(-2px);
    }

    .tab-btn.active {
        background: rgba(139, 69, 19, 0.8);
        border-color: #ffd700;
    }

    .content-box {
        background: rgba(0, 0, 0, 0.3);
        border: 2px solid #8b4513;
        border-radius: 10px;
        padding: 30px;
        min-height: 300px;
    }

    .content-title {
        color: #ffd700;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    .empty-message {
        color: #999;
        text-align: center;
        padding: 50px;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .main-content {
            border-left: 2px solid #8b4513;
            border-radius: 10px;
        }
    }
</style>

<div class="dashboard-container">
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="tabs-container">
            <a href="chat_conversations.php" class="tab-btn">
                <span>💬</span> Chat Conversations
            </a>
            <a href="incoming_mails.php" class="tab-btn">
                <span>📥</span> Incoming Mails
            </a>
            <a href="sent_mails.php" class="tab-btn">
                <span>📤</span> Sent Mails
            </a>
        </div>

        <div class="content-box">
            <h2 class="content-title">📊 Dashboard</h2>
            <div class="empty-message">
                Welcome to your dashboard! Use the tabs above to access your messages.
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
