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

// Handle delete mail
if(isset($_GET['delete'])) {
    $mail_id = intval($_GET['delete']);
    $conn->query("DELETE FROM user_mails WHERE id=$mail_id AND sender_id=$current_user_id");
    echo "<script>alert('Mail deleted!'); window.location='sent_mails.php';</script>";
}

// Get sent mails
$mails_query = "SELECT m.*, u.username, u.gender 
                FROM user_mails m
                JOIN users u ON m.receiver_id = u.id
                WHERE m.sender_id = $current_user_id
                ORDER BY m.created_at DESC";
$mails_result = $conn->query($mails_query);

$mails = [];
if($mails_result) {
    while($row = $mails_result->fetch_assoc()) {
        $photo_query = $conn->query("SELECT photo_path FROM user_photos WHERE user_id={$row['receiver_id']} LIMIT 1");
        $photo = $photo_query && $photo_query->num_rows > 0 ? $photo_query->fetch_assoc()['photo_path'] : null;
        $row['photo'] = $photo;
        $mails[] = $row;
    }
}

// Get selected mail
$selected_mail = null;
if(isset($_GET['id'])) {
    $mail_id = intval($_GET['id']);
    foreach($mails as $mail) {
        if($mail['id'] == $mail_id) {
            $selected_mail = $mail;
            break;
        }
    }
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .mail-container {
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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        color: #ffd700;
        font-size: 28px;
        font-weight: bold;
        margin: 0;
    }

    .compose-btn {
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: white;
        padding: 10px 20px;
        border: 2px solid #8b7d3a;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
    }

    .mail-layout {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 20px;
    }

    .mails-list {
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
        max-height: 700px;
        overflow-y: auto;
    }

    .mail-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-bottom: 1px solid #8b4513;
        cursor: pointer;
        transition: background 0.3s;
    }

    .mail-item:hover {
        background: rgba(139, 69, 19, 0.3);
    }

    .mail-item.active {
        background: rgba(139, 69, 19, 0.5);
    }

    .mail-photo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid #ffd700;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.3);
        flex-shrink: 0;
    }

    .mail-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .mail-info {
        flex: 1;
        min-width: 0;
    }

    .mail-sender {
        color: #ffd700;
        font-weight: bold;
        font-size: 14px;
    }

    .mail-subject {
        color: #fff;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mail-time {
        color: #999;
        font-size: 11px;
    }

    .mail-viewer {
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
        padding: 0;
        overflow: hidden;
    }

    .mail-header {
        background: rgba(0, 0, 0, 0.3);
        padding: 20px;
        border-bottom: 2px solid #8b4513;
    }

    .mail-title {
        color: #ffd700;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .mail-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 15px;
    }

    .receiver-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .receiver-photo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #ffd700;
        overflow: hidden;
    }

    .receiver-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .receiver-name {
        color: #ffd700;
        font-weight: bold;
    }

    .mail-date {
        color: #999;
        font-size: 12px;
    }

    .mail-body {
        padding: 30px;
        color: #fff;
        line-height: 1.8;
        font-size: 15px;
    }

    .mail-actions {
        padding: 20px;
        border-top: 2px solid #8b4513;
        display: flex;
        gap: 10px;
    }

    .action-btn {
        padding: 10px 20px;
        border: 2px solid #8b4513;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
    }

    .delete-btn {
        background: linear-gradient(135deg, #8b0000 0%, #660000 100%);
        color: white;
    }

    .no-selection {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #999;
        font-size: 18px;
        text-align: center;
        min-height: 400px;
    }

    .empty-mails {
        text-align: center;
        padding: 40px;
        color: #999;
    }
</style>

<div class="mail-container">
    <div class="page-header">
        <h1 class="page-title">📤 Sent Mails</h1>
        <a href="send_mail.php" class="compose-btn">✉️ Compose New</a>
    </div>

    <div class="mail-layout">
        <!-- Mails List -->
        <div class="mails-list">
            <?php if(count($mails) > 0): ?>
                <?php foreach($mails as $mail): 
                    $username = $mail['username'] ?? 'User' . $mail['receiver_id'];
                    $is_active = isset($_GET['id']) && $_GET['id'] == $mail['id'];
                ?>
                    <div class="mail-item <?php echo $is_active ? 'active' : ''; ?>" 
                         onclick="window.location='?id=<?php echo $mail['id']; ?>'">
                        <div class="mail-photo">
                            <?php if($mail['photo']): ?>
                                <img src="<?php echo $mail['photo']; ?>" alt="<?php echo $username; ?>">
                            <?php else: ?>
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:24px;">
                                    <?php echo $mail['gender'] == 'female' ? '👩' : '👨'; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mail-info">
                            <div class="mail-sender">To: <?php echo htmlspecialchars($username); ?></div>
                            <div class="mail-subject"><?php echo htmlspecialchars($mail['subject']); ?></div>
                            <div class="mail-time"><?php echo date('M d, Y h:i A', strtotime($mail['created_at'])); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-mails">
                    <p style="font-size: 48px;">📭</p>
                    <p>No sent mails</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Mail Viewer -->
        <div class="mail-viewer">
            <?php if($selected_mail): ?>
                <div class="mail-header">
                    <div class="mail-title"><?php echo htmlspecialchars($selected_mail['subject']); ?></div>
                    <div class="mail-meta">
                        <div class="receiver-info">
                            <div class="receiver-photo">
                                <?php if($selected_mail['photo']): ?>
                                    <img src="<?php echo $selected_mail['photo']; ?>" alt="Receiver">
                                <?php else: ?>
                                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:20px;">
                                        <?php echo $selected_mail['gender'] == 'female' ? '👩' : '👨'; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="receiver-name">To: <?php echo htmlspecialchars($selected_mail['username'] ?? 'User' . $selected_mail['receiver_id']); ?></div>
                                <div class="mail-date"><?php echo date('F d, Y \a\t h:i A', strtotime($selected_mail['created_at'])); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mail-body">
                    <?php echo nl2br(htmlspecialchars($selected_mail['message'])); ?>
                </div>
                
                <div class="mail-actions">
                    <a href="?delete=<?php echo $selected_mail['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Delete this mail?')">Delete</a>
                </div>
            <?php else: ?>
                <div class="no-selection">
                    <p>Select a mail to view</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
