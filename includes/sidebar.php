<?php
// Detect current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF'], ".php");
?>

<!-- Left Sidebar -->
<div class="sidebar">
    <!-- Profile Section -->
    <div class="sidebar-section">
        <h3 class="sidebar-title">Profile</h3>
        <ul class="sidebar-menu">
            <li><a href="my_profile.php" class="<?php echo $current_page == 'my_profile' ? 'active' : ''; ?>"><span class="menu-icon">👤</span> My Profile</a></li>
        </ul>
    </div>

    <!-- Messages Section -->
    <div class="sidebar-section">
        <h3 class="sidebar-title">Messages</h3>
        <ul class="sidebar-menu">
            <li><a href="chat_conversations.php" class="<?php echo $current_page == 'chat_conversations' ? 'active' : ''; ?>"><span class="menu-icon">💬</span> Chat Conversations</a></li>
            <li><a href="incoming_mails.php" class="<?php echo $current_page == 'incoming_mails' ? 'active' : ''; ?>"><span class="menu-icon">📥</span> Incoming Mails</a></li>
            <li><a href="sent_mails.php" class="<?php echo $current_page == 'sent_mails' ? 'active' : ''; ?>"><span class="menu-icon">📤</span> Sent Mails</a></li>
        </ul>
    </div>

    <!-- Prospects Section -->
    <div class="sidebar-section">
        <h3 class="sidebar-title">Prospects</h3>
        <ul class="sidebar-menu">
            <li><a href="my_friends.php" class="<?php echo $current_page == 'my_friends' ? 'active' : ''; ?>"><span class="menu-icon">👥</span> My Friends</a></li>
            <li><a href="my_matches.php" class="<?php echo $current_page == 'my_matches' ? 'active' : ''; ?>"><span class="menu-icon">💕</span> My Matches</a></li>
            <li><a href="my_favourites.php" class="<?php echo $current_page == 'my_favourites' ? 'active' : ''; ?>"><span class="menu-icon">⭐</span> My Favourites</a></li>
            <li><a href="favours_me.php" class="<?php echo $current_page == 'favours_me' ? 'active' : ''; ?>"><span class="menu-icon">💖</span> Favours Me</a></li>
        </ul>
    </div>

    <!-- Search Section -->
    <div class="sidebar-section">
        <h3 class="sidebar-title">Search</h3>
        <ul class="sidebar-menu">
            <li><a href="basic_search.php" class="<?php echo $current_page == 'basic_search' ? 'active' : ''; ?>"><span class="menu-icon">⚡</span> Quick Search</a></li>
        </ul>
    </div>
</div>
