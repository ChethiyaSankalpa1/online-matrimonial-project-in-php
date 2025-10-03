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

// Handle send mail
if(isset($_POST['send_mail'])) {
    $receiver_id = intval($_POST['receiver_id']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    
    $insert = "INSERT INTO user_mails (sender_id, receiver_id, subject, message) 
               VALUES ($current_user_id, $receiver_id, '$subject', '$message')";
    
    if($conn->query($insert)) {
        echo "<script>alert('Mail sent successfully!'); window.location='sent_mails.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error sending mail!');</script>";
    }
}

// Get receiver info if specified
$receiver_id = isset($_GET['to']) ? intval($_GET['to']) : null;
$receiver = null;
if($receiver_id) {
    $receiver_query = $conn->query("SELECT * FROM users WHERE id=$receiver_id");
    $receiver = $receiver_query->fetch_assoc();
}

// Get subject if reply
$reply_subject = isset($_GET['subject']) ? $_GET['subject'] : '';
?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .send-mail-container {
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
    }

    .page-title {
        color: #ffd700;
        font-size: 28px;
        font-weight: bold;
        margin: 0;
    }

    .mail-form-box {
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 2px solid #8b4513;
        border-radius: 10px;
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        color: #ffd700;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 12px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid #8b4513;
        border-radius: 5px;
        color: #fff;
        font-size: 14px;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: #ffd700;
        background: rgba(255, 255, 255, 0.15);
    }

    .form-textarea {
        min-height: 200px;
        resize: vertical;
        font-family: Arial, sans-serif;
    }

    .form-select {
        width: 100%;
        padding: 12px;
        background: rgba(100, 100, 100, 0.6);
        border: 2px solid #8b4513;
        border-radius: 5px;
        color: #ffd700;
        font-size: 14px;
    }

    .button-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .send-btn {
        flex: 1;
        padding: 15px;
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: white;
        border: 2px solid #8b7d3a;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 16px;
    }

    .send-btn:hover {
        transform: scale(1.02);
    }

    .cancel-btn {
        padding: 15px 30px;
        background: rgba(100, 100, 100, 0.4);
        color: white;
        border: 2px solid #8b4513;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        text-align: center;
    }
</style>

<div class="send-mail-container">
    <div class="page-header">
        <h1 class="page-title">✉️ Send Mail</h1>
    </div>

    <div class="mail-form-box">
        <form method="POST">
            <div class="form-group">
                <label class="form-label">To:</label>
                <?php if($receiver): ?>
                    <input type="text" class="form-input" value="<?php echo htmlspecialchars($receiver['username'] ?? $receiver['email']); ?>" readonly>
                    <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
                <?php else: ?>
                    <select name="receiver_id" class="form-select" required>
                        <option value="">Select recipient...</option>
                        <?php
                        // Get all users except current user
                        $users_query = $conn->query("SELECT id, username, email FROM users WHERE id != $current_user_id AND profile_completed=1");
                        while($user = $users_query->fetch_assoc()) {
                            $display_name = $user['username'] ?? $user['email'];
                            echo "<option value='{$user['id']}'>" . htmlspecialchars($display_name) . "</option>";
                        }
                        ?>
                    </select>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Subject:</label>
                <input type="text" name="subject" class="form-input" placeholder="Enter subject" value="<?php echo htmlspecialchars($reply_subject); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Message:</label>
                <textarea name="message" class="form-textarea" placeholder="Type your message here..." required></textarea>
            </div>

            <div class="button-group">
                <button type="submit" name="send_mail" class="send-btn">Send Mail</button>
                <a href="incoming_mails.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
