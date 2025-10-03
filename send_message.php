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

// Get recipient ID from URL
if(!isset($_GET['to'])) {
    header("Location: dashboard.php");
    exit();
}

$recipient_id = intval($_GET['to']);

// Get recipient data
$recipient_query = $conn->query("SELECT * FROM users WHERE id=$recipient_id");
if(!$recipient_query || $recipient_query->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}
$recipient = $recipient_query->fetch_assoc();

// Create chat messages table if not exists
$create_chat_table = "CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_chat_table);

// Handle message sending
$message_sent = false;
$error_message = '';

if(isset($_POST['send_message'])) {
    $message = trim($_POST['message']);
    
    if(!empty($message)) {
        $message = $conn->real_escape_string($message);
        $insert = "INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES ($current_user_id, $recipient_id, '$message')";
        
        if($conn->query($insert)) {
            $message_sent = true;
        } else {
            $error_message = "Failed to send message. Please try again.";
        }
    } else {
        $error_message = "Message cannot be empty.";
    }
}

// Get recipient photo
$photo_query = $conn->query("SELECT photo_path FROM user_photos WHERE user_id=$recipient_id LIMIT 1");
$photo = null;
if($photo_query && $photo_query->num_rows > 0) {
    $photo = $photo_query->fetch_assoc()['photo_path'];
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .message-container {
        max-width: 800px;
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
        align-items: center;
        justify-content: space-between;
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

    .recipient-card {
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .recipient-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid #ffd700;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .recipient-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .recipient-photo .placeholder {
        font-size: 40px;
    }

    .recipient-info {
        flex: 1;
    }

    .recipient-name {
        color: #ffd700;
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .recipient-details {
        color: #999;
        font-size: 14px;
    }

    .message-form-card {
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
        overflow: hidden;
    }

    .form-header {
        background: rgba(0, 0, 0, 0.3);
        padding: 15px 20px;
        border-bottom: 2px solid #8b4513;
    }

    .form-title {
        color: #ffd700;
        font-size: 18px;
        font-weight: bold;
        margin: 0;
    }

    .form-content {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        color: #ffd700;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
        display: block;
    }

    .message-textarea {
        width: 100%;
        min-height: 200px;
        padding: 15px;
        background: rgba(0, 0, 0, 0.3);
        border: 2px solid #8b4513;
        border-radius: 8px;
        color: #ffd700;
        font-size: 16px;
        font-family: inherit;
        resize: vertical;
    }

    .message-textarea:focus {
        outline: none;
        border-color: #ffd700;
    }

    .message-textarea::placeholder {
        color: #666;
    }

    .char-counter {
        color: #999;
        font-size: 12px;
        text-align: right;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn {
        padding: 12px 30px;
        border: 2px solid;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: white;
        border-color: #8b7d3a;
    }

    .btn-primary:hover {
        transform: scale(1.05);
    }

    .btn-secondary {
        background: rgba(139, 69, 19, 0.6);
        color: #ffd700;
        border-color: #8b4513;
    }

    .btn-secondary:hover {
        background: rgba(139, 69, 19, 0.8);
        border-color: #ffd700;
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

    .success-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    @media (max-width: 768px) {
        .recipient-card {
            flex-direction: column;
            text-align: center;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="message-container">
    <div class="page-header">
        <h1 class="page-title">
            <span>💬</span>
            Send Message
        </h1>
        <a href="javascript:history.back()" class="back-btn">← Back</a>
    </div>

    <?php if($message_sent): ?>
        <div class="alert alert-success">
            <span>✓</span>
            <div>
                <strong>Message sent successfully!</strong>
                <div class="success-actions">
                    <a href="send_message.php?to=<?php echo $recipient_id; ?>" class="btn btn-primary">Send Another Message</a>
                    <a href="chat_conversations.php?user=<?php echo $recipient_id; ?>" class="btn btn-secondary">View Conversation</a>
                    <a href="basic_search.php" class="btn btn-secondary">Back to Search</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($error_message): ?>
        <div class="alert alert-error">
            <span>✗</span>
            <strong><?php echo $error_message; ?></strong>
        </div>
    <?php endif; ?>

    <div class="recipient-card">
        <div class="recipient-photo">
            <?php if($photo): ?>
                <img src="<?php echo $photo; ?>" alt="<?php echo htmlspecialchars($recipient['username'] ?? 'User'); ?>">
            <?php else: ?>
                <div class="placeholder">
                    <?php echo $recipient['gender'] == 'female' ? '👩' : '👨'; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="recipient-info">
            <div class="recipient-name"><?php echo htmlspecialchars($recipient['username'] ?? 'User ' . $recipient['id']); ?></div>
            <div class="recipient-details">
                <?php if(isset($recipient['gender'])): ?>
                    <?php echo ucfirst($recipient['gender']); ?>
                <?php endif; ?>
                <?php if(isset($recipient['year'])): ?>
                    • <?php echo (date('Y') - $recipient['year']); ?> years
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="message-form-card">
        <div class="form-header">
            <h2 class="form-title">Compose Message</h2>
        </div>
        <div class="form-content">
            <form method="POST" id="messageForm">
                <div class="form-group">
                    <label class="form-label">Your Message</label>
                    <textarea 
                        name="message" 
                        class="message-textarea" 
                        placeholder="Type your message here..."
                        maxlength="1000"
                        id="messageText"
                        required
                    ><?php echo isset($_POST['message']) && !$message_sent ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span> / 1000 characters
                    </div>
                </div>

                <div class="form-actions">
                    <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="send_message" class="btn btn-primary">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Character counter
const messageText = document.getElementById('messageText');
const charCount = document.getElementById('charCount');

function updateCharCount() {
    charCount.textContent = messageText.value.length;
}

messageText.addEventListener('input', updateCharCount);
updateCharCount();

// Form validation
document.getElementById('messageForm').addEventListener('submit', function(e) {
    const message = messageText.value.trim();
    if(message.length === 0) {
        e.preventDefault();
        alert('Please enter a message before sending.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>
