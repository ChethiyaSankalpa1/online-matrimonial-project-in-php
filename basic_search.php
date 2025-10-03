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

// Handle search
$search_results = [];
$search_query = '';
if(isset($_GET['search']) && !empty($_GET['search'])){
    $search_query = $conn->real_escape_string($_GET['search']);
    
    // Search by username or email
    $sql = "SELECT u.*, ud.* 
            FROM users u 
            LEFT JOIN user_data ud ON u.id = ud.user_id 
            WHERE (u.username LIKE '%$search_query%' OR u.email LIKE '%$search_query%')
            AND u.id != $user_id
            ORDER BY u.username ASC";
    
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            // Get user photo
            $photo_query = $conn->query("SELECT photo_path FROM user_photos WHERE user_id=" . $row['id'] . " LIMIT 1");
            $row['photo'] = ($photo_query && $photo_query->num_rows > 0) ? $photo_query->fetch_assoc()['photo_path'] : null;
            $search_results[] = $row;
        }
    }
}
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

    /* Sidebar adjustments */
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

    .search-container {
        background: rgba(0, 0, 0, 0.3);
        border: 2px solid #8b4513;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .search-title {
        color: #ffd700;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    .search-form {
        display: flex;
        gap: 10px;
        max-width: 600px;
        margin: 0 auto;
    }

    .search-input {
        flex: 1;
        padding: 12px 20px;
        background: rgba(0, 0, 0, 0.5);
        border: 2px solid #8b4513;
        border-radius: 8px;
        color: #ffd700;
        font-size: 16px;
    }

    .search-input::placeholder {
        color: #999;
    }

    .search-input:focus {
        outline: none;
        border-color: #ffd700;
    }

    .search-btn {
        padding: 12px 30px;
        background: rgba(139, 69, 19, 0.6);
        border: 2px solid #8b4513;
        border-radius: 8px;
        color: #ffd700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search-btn:hover {
        background: rgba(139, 69, 19, 0.8);
        border-color: #ffd700;
    }

    .results-container {
        background: rgba(0, 0, 0, 0.3);
        border: 2px solid #8b4513;
        border-radius: 10px;
        padding: 30px;
    }

    .results-title {
        color: #ffd700;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .user-card {
        background: rgba(0, 0, 0, 0.4);
        border: 2px solid #8b4513;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        gap: 20px;
        align-items: center;
        transition: all 0.3s;
    }

    .user-card:hover {
        border-color: #ffd700;
        transform: translateX(5px);
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(139, 69, 19, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #ffd700;
        border: 2px solid #8b4513;
        overflow: hidden;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        color: #ffd700;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .user-details {
        color: #999;
        font-size: 14px;
        margin-bottom: 3px;
    }

    .user-actions {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        padding: 8px 16px;
        background: rgba(139, 69, 19, 0.6);
        border: 2px solid #8b4513;
        border-radius: 6px;
        color: #ffd700;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
        display: inline-block;
    }

    .action-btn:hover {
        background: rgba(139, 69, 19, 0.8);
        border-color: #ffd700;
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

        .user-card {
            flex-direction: column;
            text-align: center;
        }

        .user-actions {
            flex-direction: column;
            width: 100%;
        }

        .action-btn {
            width: 100%;
        }
    }
</style>

<div class="dashboard-container">
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Search Form -->
        <div class="search-container">
            <h2 class="search-title">⚡ Quick Search - Find Friends by Name</h2>
            <form method="GET" class="search-form">
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Enter name or email to search..." 
                    value="<?php echo htmlspecialchars($search_query); ?>"
                    required
                >
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>

        <!-- Search Results -->
        <?php if(isset($_GET['search'])): ?>
        <div class="results-container">
            <h3 class="results-title">
                <?php 
                if(count($search_results) > 0){
                    echo "Found " . count($search_results) . " result(s) for '" . htmlspecialchars($search_query) . "'";
                } else {
                    echo "No results found for '" . htmlspecialchars($search_query) . "'";
                }
                ?>
            </h3>

            <?php if(count($search_results) > 0): ?>
                <?php foreach($search_results as $result): ?>
                    <div class="user-card">
                        <div class="user-avatar">
                            <?php if(isset($result['photo']) && $result['photo']): ?>
                                <img src="<?php echo $result['photo']; ?>" alt="<?php echo htmlspecialchars($result['username'] ?? 'User'); ?>">
                            <?php else: ?>
                                <div style="font-size: 40px;">
                                    <?php echo $result['gender'] == 'female' ? '👩' : '👨'; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($result['username'] ?? 'Unknown'); ?></div>
                            <div class="user-details">
                                <?php if(isset($result['gender'])): ?>
                                    <span><?php echo ucfirst($result['gender']); ?></span>
                                <?php endif; ?>
                                <?php if(isset($result['age'])): ?>
                                    <span> • <?php echo $result['age']; ?> years</span>
                                <?php endif; ?>
                                <?php if(isset($result['religion'])): ?>
                                    <span> • <?php echo $result['religion']; ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if(isset($result['education'])): ?>
                                <div class="user-details">Education: <?php echo $result['education']; ?></div>
                            <?php endif; ?>
                            <?php if(isset($result['career'])): ?>
                                <div class="user-details">Career: <?php echo $result['career']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="user-actions">
                            <a href="view_profile.php?id=<?php echo $result['id']; ?>" class="action-btn">View Profile</a>
                            <a href="send_message.php?to=<?php echo $result['id']; ?>" class="action-btn">Send Message</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-message">
                    No users found matching "<?php echo htmlspecialchars($search_query); ?>". Try a different search term.
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
