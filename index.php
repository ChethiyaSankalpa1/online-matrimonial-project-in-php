<?php include 'includes/header.php'; ?>

<style>
    body {
        background: linear-gradient(135deg, #1a3a1a 0%, #2d4a2d 100%);
        min-height: 100vh;
        padding: 0;
    }

    .hero-section {
        position: relative;
        height: 400px;
        background: url('https://images.unsplash.com/photo-1519741497674-611481863552?w=1200') center/cover;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        overflow: hidden;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        padding: 20px;
    }

    .hero-title {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
    }

    .hero-subtitle {
        font-size: 18px;
        margin-bottom: 20px;
    }

    .cta-button {
        background: linear-gradient(135deg, #c62828 0%, #8b0000 100%);
        color: white;
        padding: 12px 35px;
        border: none;
        border-radius: 25px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .cta-button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(198, 40, 40, 0.5);
    }

    .content-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .section-box {
        background: linear-gradient(135deg, #4a1a1a 0%, #6b2020 100%);
        border: 3px solid #8b4513;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    }

    .section-header {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
        padding: 12px 20px;
        border-radius: 25px;
        margin-bottom: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
    }

    .section-title {
        color: #8b4513;
        font-size: 18px;
        font-weight: bold;
        margin: 0;
    }

    .member-photos {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin: 20px 0;
    }

    .member-photo {
        width: 100px;
        height: 120px;
        border: 3px solid #8b4513;
        border-radius: 10px;
        overflow: hidden;
        background: #ddd;
    }

    .member-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .section-text {
        color: #fff;
        font-size: 14px;
        line-height: 1.8;
        text-align: center;
        margin: 15px 0;
    }

    .register-btn {
        background: linear-gradient(135deg, #6b8e23 0%, #556b2f 100%);
        color: #fff;
        padding: 12px 30px;
        border: 2px solid #8b7d3a;
        border-radius: 25px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .register-btn:hover {
        background: linear-gradient(135deg, #7a9d2a 0%, #6b8e23 100%);
        box-shadow: 0 4px 15px rgba(107, 142, 35, 0.4);
    }

    .quote-text {
        color: #ffd700;
        font-size: 16px;
        font-style: italic;
        text-align: center;
        margin: 20px 0;
    }

    .testimonial-photos {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 15px 0;
    }

    .testimonial-photo {
        width: 80px;
        height: 100px;
        border: 3px solid #8b4513;
        border-radius: 8px;
        overflow: hidden;
        background: #ddd;
    }

    .testimonial-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .social-icons {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 20px 0;
        border-top: 2px solid rgba(139, 69, 19, 0.3);
        margin-top: 20px;
    }

    .social-icon {
        width: 40px;
        height: 40px;
        background: rgba(139, 69, 19, 0.6);
        border: 2px solid #8b4513;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .social-icon:hover {
        background: rgba(139, 69, 19, 0.9);
        transform: scale(1.1);
    }

    .app-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin: 20px 0;
    }

    .app-btn img {
        height: 50px;
        border-radius: 8px;
        transition: transform 0.3s;
    }

    .app-btn:hover img {
        transform: scale(1.05);
    }

    .success-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
        margin: 20px 0;
    }

    .success-photo {
        width: 100%;
        aspect-ratio: 3/4;
        border: 2px solid #8b4513;
        border-radius: 8px;
        overflow: hidden;
        background: #ddd;
    }

    .success-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

</style>

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Welcome to me for a reason</h1>
        <p class="hero-subtitle">Welcome to the largest Matrimonial Website in Sri Lanka. 15+ years of Service!</p>
        <a href="signin.php" class="cta-button">Signup to Explore Matches Free</a>
    </div>
</div>

<div class="content-container">
    <!-- Register Section -->
    <div class="section-box">
        <div class="section-header">
            <h2 class="section-title">Register & Join Now...!</h2>
        </div>
        
        <div class="member-photos">
            <div class="member-photo">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200" alt="Member">
            </div>
            <div class="member-photo">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200" alt="Member">
            </div>
            <div class="member-photo">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200" alt="Member">
            </div>
        </div>

        <p class="section-text">
            Our Sri Lankan-based, dedicated matrimonial website serves tens of thousands of like-minded singles from Sri Lanka all in search of genuine love.
        </p>

        <p class="section-text">
            Safety & Privacy is our top priority. We check each and every profile to ensure the right people are on our site.
        </p>

        <div style="text-align: center; margin-top: 20px;">
            <a href="signin.php" class="register-btn">Register Today to Discover</a>
        </div>
    </div>

    <!-- Featured Members Section -->
    <div class="section-box">
        <div class="section-header">
            <h2 class="section-title">Featured Members</h2>
        </div>

        <div class="member-photos">
            <div class="member-photo">
                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200" alt="Featured">
            </div>
            <div class="member-photo">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200" alt="Featured">
            </div>
        </div>

        <p class="quote-text">"Even marriage proposals ends with love,loyalty,lure,lust and life."</p>

        <div class="testimonial-photos">
            <div class="testimonial-photo">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150" alt="Couple">
            </div>
            <div class="testimonial-photo">
                <img src="https://images.unsplash.com/photo-1522529599102-193c0d76b5b6?w=150" alt="Couple">
            </div>
            <div class="testimonial-photo">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=150" alt="Couple">
            </div>
        </div>

        <p class="section-text">"Yes, it was a matrimony proposal but we fell in love before we got married."</p>

        <div class="social-icons">
            <a href="#" class="social-icon">📘</a>
            <a href="#" class="social-icon">💬</a>
            <a href="#" class="social-icon">🐦</a>
            <a href="#" class="social-icon">📷</a>
            <a href="#" class="social-icon">📺</a>
            <a href="#" class="social-icon">💼</a>
        </div>
    </div>

    <!-- Always Stay Connected Section -->
    <div class="section-box">
        <div class="section-header">
            <h2 class="section-title">Always Stay Connected</h2>
        </div>

        <div class="app-buttons">
            <a href="#" class="app-btn">
                <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="App Store">
            </a>
            <a href="#" class="app-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play">
            </a>
        </div>

        <div class="member-photos">
            <div class="member-photo">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200" alt="Member">
            </div>
            <div class="member-photo">
                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=200" alt="Member">
            </div>
            <div class="member-photo">
                <img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=200" alt="Member">
            </div>
        </div>

        <p class="section-text">
            "Sri Lankan by Heart, Sri Lankan by Nature, From One Sri Lankan Heart to Another, True Love can be Meant to Be, Family, Sri Lankan, Eternally Yours."
        </p>
    </div>

    <!-- Success Stories Section -->
    <div class="section-box">
        <div class="section-header">
            <h2 class="section-title">Trusted by many happy members since 2007...!</h2>
        </div>

        <div class="success-grid">
            <div class="success-photo"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100" alt="Success"></div>
            <div class="success-photo"><img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100" alt="Success"></div>
            <div class="success-photo"><img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=100" alt="Success"></div>
            <div class="success-photo"><img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100" alt="Success"></div>
            <div class="success-photo"><img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100" alt="Success"></div>
            <div class="success-photo"><img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100" alt="Success"></div>
            <div class="success-photo"><img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100" alt="Success"></div>
            <div class="success-photo"><img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=100" alt="Success"></div>
        </div>

        <p class="section-text" style="margin-top: 30px;">
            May we see you united with your dream partner in the near future ...!
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
