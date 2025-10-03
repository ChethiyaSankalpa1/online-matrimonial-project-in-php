<?php
// Base URL for your XAMPP project
$base_url = '/weding/';

// Detect current page without extension
$current_page = basename($_SERVER['PHP_SELF'], ".php");

// Check if user is logged in
$is_logged_in = isset($_SESSION['user']);

// Navigation items - different for logged in vs logged out users
if(!$is_logged_in) {
    $nav_items = [
        'index'   => ['title' => 'Welcome', 'url' => $base_url . 'index.php', 'icon' => '🏠'],
        'search'  => ['title' => 'Search', 'url' => $base_url . 'search.php', 'icon' => '🔍'],
        'success' => ['title' => 'Success', 'url' => $base_url . 'success.php', 'icon' => '❤️'],
        'about'   => ['title' => 'About', 'url' => $base_url . 'about.php', 'icon' => 'ℹ️'],
        'safety'  => ['title' => 'Safety', 'url' => $base_url . 'safety.php', 'icon' => '🛡️'],
        'signup'  => ['title' => 'Sign up', 'url' => $base_url . 'signin.php', 'icon' => '👤'],
        'signin'  => ['title' => 'Sign in', 'url' => $base_url . 'signup.php', 'icon' => '🔐'],
        'contact' => ['title' => 'Contact', 'url' => $base_url . 'contact.php', 'icon' => '📧']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo ucfirst($current_page); ?></title>
<style>
/* Hide header only on complete profile page */
<?php if($current_page == 'complete_profile'): ?>
.header, .header-public {
    display: none !important;
}
<?php endif; ?>

/* Reset */
* {margin:0; padding:0; box-sizing:border-box;}
body {font-family: Arial, sans-serif; background:#f4f4f4; color:#333;}

/* Header - Logged Out */
.header-public {
    background:#3a1a1a;
    color:#fff;
    padding:10px 0;
    box-shadow:0 2px 5px rgba(0,0,0,.2);
    position: relative;
}

.container {
    max-width:1200px;
    margin:0 auto;
    padding:0 20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

/* Brand + Logo */
.nav-brand {
    display:flex;
    align-items:center;
    gap: 10px;
}

.logo {
    font-size:24px;
    text-decoration:none;
    color:#fff;
}

.hamburger {
    display:flex;
    flex-direction:column;
    cursor:pointer;
    padding: 5px;
}

.hamburger span {
    width:25px;
    height:3px;
    background:#fff;
    margin:2px 0;
    transition:0.4s;
}

/* Desktop Nav Menu */
.nav-menu {
    list-style:none;
    display:flex;
    gap:20px;
}

.nav-item {}

.nav-link {
    display:flex;
    align-items:center;
    gap:6px;
    text-decoration:none;
    color:#fff;
    font-weight:bold;
    padding:6px 10px;
    border-radius:6px;
    transition:.3s;
}

.nav-link:hover {
    background:#5a2a2a;
}

.nav-link.active {
    background:#fff;
    color:#3a1a1a;
}

/* Icons */
.nav-icon {
    font-size:18px;
}

/* Mobile Dropdown Menu */
.mobile-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 20px;
    width: 280px;
    background: rgba(26, 0, 0, 0.95);
    backdrop-filter: blur(15px);
    box-shadow: 0 8px 32px rgba(220, 20, 60, 0.4);
    z-index: 1000;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 12px;
    border: 1px solid rgba(220, 20, 60, 0.3);
}

.mobile-dropdown.active {
    display: block;
    max-height: 600px;
}

.mobile-dropdown .nav-item {
    border-bottom: 1px solid rgba(220, 20, 60, 0.2);
    animation: slideIn 0.3s ease forwards;
    opacity: 0;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.mobile-dropdown .nav-item:nth-child(1) { animation-delay: 0.05s; }
.mobile-dropdown .nav-item:nth-child(2) { animation-delay: 0.1s; }
.mobile-dropdown .nav-item:nth-child(3) { animation-delay: 0.15s; }
.mobile-dropdown .nav-item:nth-child(4) { animation-delay: 0.2s; }
.mobile-dropdown .nav-item:nth-child(5) { animation-delay: 0.25s; }
.mobile-dropdown .nav-item:nth-child(6) { animation-delay: 0.3s; }
.mobile-dropdown .nav-item:nth-child(7) { animation-delay: 0.35s; }
.mobile-dropdown .nav-item:nth-child(8) { animation-delay: 0.4s; }

.mobile-dropdown .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    color: #ffd700;
    text-decoration: none;
    font-size: 15px;
    font-weight: 600;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.mobile-dropdown .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #dc143c 0%, #ff6b6b 100%);
    transform: scaleY(0);
    transition: transform 0.3s;
}

.mobile-dropdown .nav-link:hover::before {
    transform: scaleY(1);
}

.mobile-dropdown .nav-link:hover {
    background: linear-gradient(90deg, rgba(220, 20, 60, 0.3) 0%, rgba(220, 20, 60, 0.1) 100%);
    padding-left: 30px;
    color: #fff;
}

.mobile-dropdown .nav-icon {
    font-size: 24px;
    filter: drop-shadow(0 2px 5px rgba(220, 20, 60, 0.5));
}

/* Header - Logged In */
.header {
    background: linear-gradient(135deg, #2a1a0a 0%, #1a1a0a 100%);
    color: #ffd700;
    padding: 15px 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,.3);
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 20px;
}

.back-button {
    background: rgba(139, 69, 19, 0.6);
    color: #ffd700;
    padding: 8px 16px;
    border: 2px solid #8b4513;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 5px;
}

.back-button:hover {
    background: rgba(139, 69, 19, 0.8);
    border-color: #ffd700;
}

.header-title {
    flex: 1;
    font-size: 24px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}

.logout-btn {
    background: rgba(139, 69, 19, 0.6);
    color: #ffd700;
    padding: 8px 16px;
    border: 2px solid #8b4513;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
}

.logout-btn:hover {
    background: rgba(139, 69, 19, 0.8);
    border-color: #ffd700;
}

/* Sidebar Styles */
.sidebar {
    width: 250px;
    background: linear-gradient(135deg, #2a1a0a 0%, #1a1a0a 100%);
    border: 2px solid #8b4513;
    border-radius: 10px;
    padding: 0;
}

.sidebar-section {
    border-bottom: 1px solid #8b4513;
    padding: 15px 0;
}

.sidebar-section:last-child {
    border-bottom: none;
}

.sidebar-title {
    color: #ffd700;
    font-size: 16px;
    font-weight: bold;
    padding: 10px 20px;
    margin: 0;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    padding: 0;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    color: #ffd700;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s;
}

.sidebar-menu a:hover {
    background: rgba(139, 69, 19, 0.3);
}

.sidebar-menu a.active {
    background: rgba(139, 69, 19, 0.5);
    border-left: 3px solid #ffd700;
}

.menu-icon {
    font-size: 16px;
}

@media(max-width:768px){
    .nav-menu {display:none;}
    .header-title {
        font-size: 18px;
    }
    .back-button, .logout-btn {
        padding: 6px 12px;
        font-size: 14px;
    }
}
</style>
</head>
<body>
<?php if($is_logged_in): ?>
    <!-- Logged In Header -->
    <header class="header">
        <div class="header-container">
            <a href="<?php echo $base_url; ?>dashboard.php" class="back-button">
                ← Main Menu
            </a>
            <div class="header-title">
                <span>❤️</span>
                <span>Marriage Site</span>
            </div>
            <a href="<?php echo $base_url; ?>logout.php" class="logout-btn">
                🚪 Logout
            </a>
        </div>
    </header>
<?php else: ?>
    <!-- Logged Out Header -->
    <header class="header-public">
        <nav class="navbar">
            <div class="container">
                <div class="nav-brand">
                    <div class="hamburger" onclick="toggleMenu()">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <a href="<?php echo $base_url; ?>index.php" class="logo">❤️</a>
                </div>
                <ul class="nav-menu" id="navMenu">
                    <?php foreach ($nav_items as $key => $item): ?>
                        <li class="nav-item">
                            <a href="<?php echo $item['url']; ?>" 
                               class="nav-link <?php echo ($current_page == $key) ? 'active' : ''; ?>">
                                <span class="nav-icon"><?php echo $item['icon']; ?></span>
                                <span class="nav-text"><?php echo $item['title']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!-- Mobile Dropdown -->
            <div class="mobile-dropdown" id="mobileDropdown">
                <?php foreach ($nav_items as $key => $item): ?>
                    <div class="nav-item">
                        <a href="<?php echo $item['url']; ?>" class="nav-link">
                            <span class="nav-icon"><?php echo $item['icon']; ?></span>
                            <span class="nav-text"><?php echo $item['title']; ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </nav>
    </header>
<?php endif; ?>

<script>
function toggleMenu() {
    document.getElementById('mobileDropdown').classList.toggle('active');
}
</script>
