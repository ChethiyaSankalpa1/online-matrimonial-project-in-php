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

// Create chat messages table
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

// Handle send message
if(isset($_POST['send_message'])) {
    $receiver_id = intval($_POST['receiver_id']);
    $message = $conn->real_escape_string($_POST['message']);
    
    $insert = "INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES ($current_user_id, $receiver_id, '$message')";
    if($conn->query($insert)) {
        echo "<script>alert('Message sent!');</script>";
    }
}

// Get chat conversations (users I've chatted with)
$conversations_query = "SELECT DISTINCT 
                        CASE 
                            WHEN sender_id = $current_user_id THEN receiver_id 
                            ELSE sender_id 
                        END as other_user_id,
                        MAX(created_at) as last_message_time
                        FROM chat_messages 
                        WHERE sender_id = $current_user_id OR receiver_id = $current_user_id
                        GROUP BY other_user_id
                        ORDER BY last_message_time DESC";
$conversations_result = $conn->query($conversations_query);

$conversations = [];
if($conversations_result) {
    while($row = $conversations_result->fetch_assoc()) {
        $other_user_id = $row['other_user_id'];
        $user_data = $conn->query("SELECT * FROM users WHERE id=$other_user_id")->fetch_assoc();
        $photo_query = $conn->query("SELECT photo_path FROM user_photos WHERE user_id=$other_user_id LIMIT 1");
        $photo = $photo_query && $photo_query->num_rows > 0 ? $photo_query->fetch_assoc()['photo_path'] : null;
        
        // Get unread count
        $unread = $conn->query("SELECT COUNT(*) as count FROM chat_messages WHERE sender_id=$other_user_id AND receiver_id=$current_user_id AND is_read=0")->fetch_assoc()['count'];
        
        $conversations[] = [
            'user' => $user_data,
            'photo' => $photo,
            'unread' => $unread,
            'last_time' => $row['last_message_time']
        ];
    }
}

// Get selected conversation
$selected_user_id = isset($_GET['user']) ? intval($_GET['user']) : null;
$messages = [];
if($selected_user_id) {
    // Mark messages as read
    $conn->query("UPDATE chat_messages SET is_read=1 WHERE sender_id=$selected_user_id AND receiver_id=$current_user_id");
    
    // Get messages
    $messages_query = "SELECT * FROM chat_messages 
                       WHERE (sender_id=$current_user_id AND receiver_id=$selected_user_id) 
                       OR (sender_id=$selected_user_id AND receiver_id=$current_user_id)
                       ORDER BY created_at ASC";
    $messages_result = $conn->query($messages_query);
    if($messages_result) {
        while($row = $messages_result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    
    $selected_user = $conn->query("SELECT * FROM users WHERE id=$selected_user_id")->fetch_assoc();
}
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .chat-container {
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

    .chat-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 20px;
        height: 600px;
    }

    .conversations-list {
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
        overflow-y: auto;
    }

    .conversation-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-bottom: 1px solid #8b4513;
        cursor: pointer;
        transition: background 0.3s;
    }

    .conversation-item:hover {
        background: rgba(139, 69, 19, 0.3);
    }

    .conversation-item.active {
        background: rgba(139, 69, 19, 0.5);
    }

    .conv-photo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid #ffd700;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.3);
    }

    .conv-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .conv-info {
        flex: 1;
    }

    .conv-name {
        color: #ffd700;
        font-weight: bold;
        font-size: 14px;
    }

    .conv-time {
        color: #999;
        font-size: 11px;
    }

    .unread-badge {
        background: #ff4444;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }

    .chat-window {
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        background: rgba(0, 0, 0, 0.3);
        padding: 15px 20px;
        border-bottom: 2px solid #8b4513;
        color: #ffd700;
        font-weight: bold;
        font-size: 18px;
    }

    .messages-area {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .message {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 15px;
        word-wrap: break-word;
    }

    .message.sent {
        align-self: flex-end;
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: white;
        border: 2px solid #8b7d3a;
    }

    .message.received {
        align-self: flex-start;
        background: rgba(139, 69, 19, 0.4);
        color: #ffd700;
        border: 2px solid #8b4513;
    }

    .message-time {
        font-size: 10px;
        color: #999;
        margin-top: 5px;
    }

    .chat-input-area {
        padding: 15px;
        background: rgba(0, 0, 0, 0.3);
        border-top: 2px solid #8b4513;
    }

    .chat-form {
        display: flex;
        gap: 10px;
    }

    .chat-input {
        flex: 1;
        padding: 12px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid #8b4513;
        border-radius: 25px;
        color: #ffd700;
        font-size: 14px;
    }

    .chat-input:focus {
        outline: none;
        border-color: #ffd700;
    }

    .send-btn {
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: white;
        padding: 12px 30px;
        border: 2px solid #8b7d3a;
        border-radius: 25px;
        cursor: pointer;
        font-weight: bold;
    }

    .send-btn:hover {
        transform: scale(1.05);
    }

    .no-conversation {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #999;
        font-size: 18px;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }
</style>

<div class="chat-container">
    <div class="page-header">
        <h1 class="page-title">💬 Chat Conversations</h1>
    </div>

    <div class="chat-layout">
        <!-- Conversations List -->
        <div class="conversations-list">
            <?php if(count($conversations) > 0): ?>
                <?php foreach($conversations as $conv): 
                    $username = $conv['user']['username'] ?? 'User' . $conv['user']['id'];
                    $is_active = $selected_user_id == $conv['user']['id'];
                ?>
                    <div class="conversation-item <?php echo $is_active ? 'active' : ''; ?>" 
                         onclick="window.location='?user=<?php echo $conv['user']['id']; ?>'">
                        <div class="conv-photo">
                            <?php if($conv['photo']): ?>
                                <img src="<?php echo $conv['photo']; ?>" alt="<?php echo $username; ?>">
                            <?php else: ?>
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:24px;">
                                    <?php echo $conv['user']['gender'] == 'female' ? '👩' : '👨'; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="conv-info">
                            <div class="conv-name"><?php echo htmlspecialchars($username); ?></div>
                            <div class="conv-time"><?php echo date('M d, Y', strtotime($conv['last_time'])); ?></div>
                        </div>
                        <?php if($conv['unread'] > 0): ?>
                            <div class="unread-badge"><?php echo $conv['unread']; ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No conversations yet</p>
                    <p style="font-size: 12px;">Start chatting with your matches!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Chat Window -->
        <div class="chat-window">
            <?php if($selected_user_id && isset($selected_user)): ?>
                <div class="chat-header">
                    💬 <?php echo htmlspecialchars($selected_user['username'] ?? 'User' . $selected_user['id']); ?>
                </div>
                
                <div class="messages-area" id="messagesArea">
                    <?php if(count($messages) > 0): ?>
                        <?php foreach($messages as $msg): ?>
                            <div class="message <?php echo $msg['sender_id'] == $current_user_id ? 'sent' : 'received'; ?>">
                                <div><?php echo htmlspecialchars($msg['message']); ?></div>
                                <div class="message-time"><?php echo date('h:i A', strtotime($msg['created_at'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align:center; color:#999; padding:20px;">No messages yet. Start the conversation!</div>
                    <?php endif; ?>
                </div>
                
                <div class="chat-input-area">
                    <form method="POST" class="chat-form">
                        <input type="hidden" name="receiver_id" value="<?php echo $selected_user_id; ?>">
                        <input type="text" name="message" class="chat-input" placeholder="Type your message..." required>
                        <button type="submit" name="send_message" class="send-btn">Send</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="no-conversation">
                    <p>Select a conversation to start chatting</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Auto-scroll to bottom of messages
const messagesArea = document.getElementById('messagesArea');
if(messagesArea) {
    messagesArea.scrollTop = messagesArea.scrollHeight;
}
</script>

<?php include 'includes/footer.php'; ?>
