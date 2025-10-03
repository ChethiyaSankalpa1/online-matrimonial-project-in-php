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
$profile = $profile_query ? $profile_query->fetch_assoc() : null;

// Create photos table if not exists
$create_photos_table = "CREATE TABLE IF NOT EXISTS user_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_photos_table);

// Handle photo upload
if(isset($_POST['upload_photo']) && isset($_FILES['photo'])) {
    $upload_dir = 'uploads/photos/';
    
    // Create directory if it doesn't exist
    if(!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file = $_FILES['photo'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Get file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    
    if(in_array($file_ext, $allowed)) {
        if($file_error === 0) {
            if($file_size < 5000000) { // 5MB max
                // Check if user already has 1 photo
                $count_query = $conn->query("SELECT COUNT(*) as count FROM user_photos WHERE user_id=$user_id");
                $count = $count_query->fetch_assoc()['count'];
                
                if($count < 1) {
                    $new_name = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
                    $file_destination = $upload_dir . $new_name;
                    
                    if(move_uploaded_file($file_tmp, $file_destination)) {
                        $insert_photo = "INSERT INTO user_photos (user_id, photo_path) VALUES ($user_id, '$file_destination')";
                        if($conn->query($insert_photo)) {
                            echo "<script>alert('Photo uploaded successfully!');</script>";
                        }
                    }
                } else {
                    echo "<script>alert('You can only upload 1 photo!');</script>";
                }
            } else {
                echo "<script>alert('File size too large! Maximum 5MB allowed.');</script>";
            }
        } else {
            echo "<script>alert('Error uploading file!');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type! Only JPG, JPEG, PNG, and GIF allowed.');</script>";
    }
}

// Handle photo deletion
if(isset($_GET['delete_photo'])) {
    $photo_id = intval($_GET['delete_photo']);
    $photo_query = $conn->query("SELECT * FROM user_photos WHERE id=$photo_id AND user_id=$user_id");
    
    if($photo_query && $photo_query->num_rows > 0) {
        $photo = $photo_query->fetch_assoc();
        if(file_exists($photo['photo_path'])) {
            unlink($photo['photo_path']);
        }
        $conn->query("DELETE FROM user_photos WHERE id=$photo_id");
        echo "<script>alert('Photo deleted successfully!'); window.location='my_profile.php';</script>";
    }
}

// Get user photos
$photos_query = $conn->query("SELECT * FROM user_photos WHERE user_id=$user_id ORDER BY uploaded_at DESC");
$photos = [];
if($photos_query) {
    while($row = $photos_query->fetch_assoc()) {
        $photos[] = $row;
    }
}

// Calculate profile completion percentage
function calculateProfileCompletion($user, $profile) {
    $fields = [
        'username' => $user['username'] ?? '',
        'gender' => $user['gender'] ?? '',
        'day' => $user['day'] ?? '',
        'height' => $profile['height'] ?? '',
        'weight' => $profile['weight'] ?? '',
        'figure' => $profile['figure'] ?? '',
        'appearance' => $profile['appearance'] ?? '',
        'complexion' => $profile['complexion'] ?? '',
        'status' => $profile['status'] ?? '',
        'education' => $profile['education'] ?? '',
        'career' => $profile['career'] ?? '',
        'religion' => $profile['religion'] ?? '',
        'ethnicity' => $profile['ethnicity'] ?? '',
        'residency' => $profile['residency'] ?? '',
        'smoking' => $profile['smoking'] ?? '',
        'drinking' => $profile['drinking'] ?? '',
    ];
    
    $filled = 0;
    $total = count($fields);
    
    foreach($fields as $value) {
        if(!empty($value)) $filled++;
    }
    
    return round(($filled / $total) * 100);
}

// Generate automatic "About Me" description
function generateAboutMe($user, $profile) {
    if(!$profile) return "Complete your profile to generate an automatic description!";
    
    $age = date('Y') - ($user['year'] ?? 2000);
    $gender = $user['gender'] ?? 'person';
    $ethnicity = $profile['ethnicity'] ?? 'Sri Lankan';
    $education = $profile['education'] ?? '';
    $career = $profile['career'] ?? '';
    $religion = $profile['religion'] ?? '';
    $status = $profile['status'] ?? '';
    $personality = $profile['personality'] ?? '';
    $smoking = $profile['smoking'] ?? '';
    $drinking = $profile['drinking'] ?? '';
    $residency = $profile['residency'] ?? '';
    
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

$completion = calculateProfileCompletion($user, $profile);
$about_me = generateAboutMe($user, $profile);

// Calculate section completions
$profile_info_completion = (!empty($user['username']) && !empty($user['gender']) && !empty($user['day'])) ? 100 : 50;
$personal_info_completion = ($profile && !empty($profile['height']) && !empty($profile['education']) && !empty($profile['career'])) ? 100 : 30;
$partner_info_completion = 0; // Not implemented yet
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
        gap: 15px;
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

    .alert-box {
        background: rgba(255, 235, 205, 0.95);
        border: 2px solid #d4a574;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
        color: #8b4513;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .content-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .profile-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 20px;
    }
    
    .info-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .main-section {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.15) 0%, rgba(139, 0, 0, 0.15) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 20, 60, 0.4);
        border-radius: 15px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(220, 20, 60, 0.3);
    }

    .section-header {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.3) 0%, rgba(220, 20, 60, 0.2) 100%);
        padding: 18px 25px;
        border-bottom: 1px solid rgba(220, 20, 60, 0.5);
    }

    .section-title {
        color: #ffd700;
        font-size: 18px;
        font-weight: bold;
        margin: 0;
    }

    .about-content {
        padding: 30px;
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(139, 0, 0, 0.1) 100%);
        border: 1px solid rgba(220, 20, 60, 0.3);
        border-radius: 15px;
        margin: 20px;
        box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .about-text {
        color: #ffd700;
        font-size: 16px;
        line-height: 2;
        font-style: italic;
        text-align: justify;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .completion-box {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.2) 0%, rgba(139, 0, 0, 0.2) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 20, 60, 0.5);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 8px 32px rgba(220, 20, 60, 0.3);
        position: relative;
        overflow: hidden;
    }

    .completion-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(220, 20, 60, 0.1), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
    }

    .completion-title {
        color: #ffd700;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
        text-shadow: 0 2px 10px rgba(255, 215, 0, 0.5);
        position: relative;
        z-index: 1;
    }

    .completion-item {
        margin-bottom: 15px;
    }

    .completion-label {
        color: #fff;
        font-size: 14px;
        margin-bottom: 5px;
        display: flex;
        justify-content: space-between;
    }

    .progress-bar {
        width: 100%;
        height: 20px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #8b4513;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #ff0000 0%, #ff6b6b 50%, #ff0000 100%);
        transition: width 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 11px;
        font-weight: bold;
    }

    .progress-fill.high {
        background: linear-gradient(90deg, #00ff00 0%, #66ff66 50%, #00ff00 100%);
    }

    .progress-fill.medium {
        background: linear-gradient(90deg, #ffaa00 0%, #ffcc66 50%, #ffaa00 100%);
    }

    .tip-box {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.25) 0%, rgba(139, 0, 0, 0.25) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 20, 60, 0.6);
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 8px 32px rgba(220, 20, 60, 0.4);
        position: relative;
        overflow: hidden;
    }

    .tip-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(220, 20, 60, 0.15), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
    }

    .tip-text {
        color: #ffd700;
        font-size: 15px;
        line-height: 1.8;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .upload-btn {
        background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
        color: #fff;
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(220, 20, 60, 0.5);
    }

    .upload-btn:hover {
        background: linear-gradient(135deg, #ff1744 0%, #dc143c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 20, 60, 0.7);
    }

    .photos-section {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.15) 0%, rgba(139, 0, 0, 0.15) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 20, 60, 0.4);
        border-radius: 15px;
        padding: 0;
        overflow: hidden;
        margin-top: 20px;
        box-shadow: 0 8px 32px rgba(220, 20, 60, 0.3);
    }

    .photo-gallery {
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .photo-item {
        position: relative;
        width: 100%;
        height: 350px;
        border-radius: 15px;
        overflow: hidden;
        border: 4px solid #dc143c;
        background: rgba(220, 20, 60, 0.1);
        box-shadow: 0 10px 40px rgba(220, 20, 60, 0.5), inset 0 0 20px rgba(220, 20, 60, 0.2);
    }

    .photo-item::after {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border-radius: 15px;
        border: 2px solid rgba(220, 20, 60, 0.4);
        z-index: -1;
    }
    
    .photo-box {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.2) 0%, rgba(139, 0, 0, 0.2) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 20, 60, 0.5);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 8px 32px rgba(220, 20, 60, 0.3);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .photo-box::before {
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
    
    .photo-box-title {
        color: #ffd700;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
        text-align: center;
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-item.empty {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.2) 0%, rgba(220, 20, 60, 0.1) 100%);
        border: 3px dashed rgba(220, 20, 60, 0.5);
    }

    .empty-icon {
        font-size: 80px;
        color: rgba(220, 20, 60, 0.5);
    }

    .delete-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 0, 0, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .delete-btn:hover {
        background: rgba(255, 0, 0, 1);
        transform: scale(1.1);
    }

    .upload-form {
        padding: 20px;
        background: rgba(0, 0, 0, 0.2);
        border-top: 2px solid #8b4513;
    }

    .file-input-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .file-input {
        flex: 1;
        padding: 10px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid #8b4513;
        border-radius: 5px;
        color: #ffd700;
    }

    .upload-submit-btn {
        background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
        color: #fff;
        padding: 12px 30px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(220, 20, 60, 0.5);
    }

    .upload-submit-btn:hover {
        background: linear-gradient(135deg, #ff1744 0%, #dc143c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 20, 60, 0.7);
    }

    .user-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(220, 20, 60, 0.3);
        position: relative;
        z-index: 1;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        color: #dc143c;
        font-size: 24px;
        font-weight: bold;
        display: block;
        text-shadow: 0 2px 10px rgba(220, 20, 60, 0.5);
    }

    .stat-label {
        color: #ffd700;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    @media (max-width: 968px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
        
        .content-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <span style="font-size: 32px;">✏️</span>
        <h1 class="profile-title">My Profile</h1>
    </div>

    <div class="content-wrapper">
        <!-- About Me Section - Full Width at Top -->
        <div class="main-section">
            <div class="section-header">
                <h2 class="section-title">About Me/Proposal</h2>
            </div>
            <div class="about-content">
                <p class="about-text"><?php echo $about_me; ?></p>
            </div>
        </div>

        <!-- Profile Layout: Status Info (Left) + Photo (Right) -->
        <div class="profile-layout">
            <div class="sidebar">
            <div class="completion-box">
                <h3 class="completion-title">Profile Completion</h3>
                
                <div class="completion-item">
                    <div class="completion-label">
                        <span>Your Profile is <?php echo $completion; ?>% completed:</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill <?php echo $completion >= 70 ? 'high' : ($completion >= 40 ? 'medium' : ''); ?>" 
                             style="width: <?php echo $completion; ?>%">
                        </div>
                    </div>
                </div>

                <div class="completion-item">
                    <div class="completion-label">
                        <span>Profile Info</span>
                        <span><?php echo $profile_info_completion; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill <?php echo $profile_info_completion >= 70 ? 'high' : 'medium'; ?>" 
                             style="width: <?php echo $profile_info_completion; ?>%">
                        </div>
                    </div>
                </div>

                <div class="completion-item">
                    <div class="completion-label">
                        <span>Personal Info</span>
                        <span><?php echo $personal_info_completion; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill <?php echo $personal_info_completion >= 70 ? 'high' : 'medium'; ?>" 
                             style="width: <?php echo $personal_info_completion; ?>%">
                        </div>
                    </div>
                </div>

                <div class="completion-item">
                    <div class="completion-label">
                        <span>Partner Info</span>
                        <span><?php echo $partner_info_completion; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $partner_info_completion; ?>%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="tip-box">
                <p class="tip-text">
                    <strong>✨ A great profile has a great picture</strong><br>
                    Get 10x more interests and mails<br>
                    Upload Your Photo Now
                </p>
                <form method="POST" enctype="multipart/form-data" style="margin-top: 15px; position: relative; z-index: 1;">
                    <input type="file" name="photo" accept="image/*" required style="display:none;" id="photoInput" onchange="this.form.submit()">
                    <label for="photoInput" class="upload-btn" style="cursor: pointer;">📸 Upload Photo</label>
                    <input type="hidden" name="upload_photo" value="1">
                </form>
                <p style="color: #999; font-size: 11px; margin-top: 15px; position: relative; z-index: 1;">
                    Photos: <?php echo count($photos); ?>/1 | Max 5MB
                </p>
                
                <div class="user-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?php echo $completion; ?>%</span>
                        <span class="stat-label">Complete</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo count($photos); ?></span>
                        <span class="stat-label">Photos</span>
                    </div>
                </div>
            </div>
            </div>
            
            <!-- Right side - Photo Display -->
            <div class="info-sidebar">
                <div class="photo-box">
                    <h3 class="photo-box-title">My Photo</h3>
                    <?php if(count($photos) > 0): ?>
                        <?php foreach($photos as $photo): ?>
                            <div class="photo-item">
                                <img src="<?php echo $photo['photo_path']; ?>" alt="User Photo">
                                <a href="?delete_photo=<?php echo $photo['id']; ?>" 
                                   class="delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete this photo?')">×</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="photo-item empty">
                            <span class="empty-icon">📷</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
