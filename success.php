<?php include 'includes/header.php'; ?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
        min-height: 100vh;
        padding: 0;
    }

    .hero-section {
        position: relative;
        height: 350px;
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

    .section-header {
        text-align: center;
        padding: 30px 20px 20px;
        color: #ffd700;
        font-size: 20px;
        font-style: italic;
    }

    .content-container {
        max-width: 900px;
        margin: 0 auto 40px;
        padding: 0 20px;
    }

    .content-box {
        background: linear-gradient(135deg, #4a1a1a 0%, #2d1810 100%);
        border: 3px solid #8b4513;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    }

    .story-item {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 2px solid rgba(139, 69, 19, 0.3);
    }

    .story-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .story-sidebar {
        flex-shrink: 0;
        width: 150px;
    }

    .story-photo {
        width: 120px;
        height: 140px;
        border: 3px solid #8b4513;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
        background: #333;
    }

    .story-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .story-category {
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 8px;
        text-align: center;
        font-size: 12px;
        font-weight: bold;
        border-radius: 5px;
        text-transform: uppercase;
        line-height: 1.3;
    }

    .story-content {
        flex: 1;
    }

    .story-title {
        color: #ffd700;
        font-size: 16px;
        font-style: italic;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .story-text {
        color: #fff;
        font-size: 13px;
        line-height: 1.7;
        margin-bottom: 10px;
        text-align: justify;
    }

    .story-author {
        color: #ffd700;
        font-size: 12px;
        text-align: right;
        font-style: italic;
        margin-top: 10px;
    }

    .highlight-message {
        background: rgba(0, 150, 0, 0.15);
        border-left: 4px solid #4caf50;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
        color: #4caf50;
        font-size: 14px;
        line-height: 1.7;
        text-align: center;
    }

    .highlight-message-red {
        background: rgba(255, 107, 107, 0.15);
        border-left: 4px solid #ff6b6b;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
        color: #ff6b6b;
        font-size: 14px;
        line-height: 1.7;
        text-align: center;
    }

    .highlight-message-blue {
        background: rgba(0, 150, 255, 0.15);
        border-left: 4px solid #2196f3;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
        color: #2196f3;
        font-size: 14px;
        line-height: 1.7;
        text-align: center;
    }

    .cta-footer {
        background: rgba(0, 150, 0, 0.15);
        border: 2px solid #4caf50;
        padding: 20px;
        margin: 30px 0;
        border-radius: 10px;
        color: #4caf50;
        font-size: 16px;
        text-align: center;
        font-weight: bold;
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

<div class="section-header">
    Success Stories - Many Happy Stories Everyday - Start your search for a perfect match
</div>

<div class="content-container">
    <div class="content-box">
        
        <div class="story-item">
            <div class="story-sidebar">
                <div class="story-photo">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200" alt="Success Story">
                </div>
                <div class="story-category">TRUST &<br>PRIVACY</div>
            </div>
            <div class="story-content">
                <div class="story-title">The responses that I got, made me realize that I should have tried this a long time ago.</div>
                <p class="story-text">
                    Thank you so much for your lovely service given to thousands of registered bachelors who are waiting to find their soul mates. Keep it up. I am really glad that I came across your website. I am really happy that it is indeed in a money minded society your service is great, but it is marvelous. Once again we both are thankful to you all very much for giving this opportunity to make our marriage a reality.
                </p>
                <div class="story-author">- Deepika, 26, Journalist</div>
            </div>
        </div>

        <div class="highlight-message-red">
            Your privacy is utmost important to Liyathabara. Only you will decide when to disclose personal details to the right person at right time.
        </div>

        <div class="story-item">
            <div class="story-sidebar">
                <div class="story-photo">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200" alt="Success Story">
                </div>
                <div class="story-category">GENUINE<br>PEOPLE</div>
            </div>
            <div class="story-content">
                <div class="story-title">Unknown to me, my special someone was just a few miles away...</div>
                <p class="story-text">
                    Really pleased at how respectful the members were on this website. They take time seriously! I would happily recommend this site to any one seeking a matrimonial relationship. I didn't have to travel the world to find Mr Right. He was within the same state! I found out his profile. Now Mohammed and I have known each other for few months and soon we will be inseparable.
                </p>
                <div class="story-author">- Priyanthi 26, Maharagama</div>
            </div>
        </div>

        <div class="highlight-message">
            Globally, we are the preferred matrimony service for many aspiring brides and grooms to find their perfect partner, especially for migrant communities spread over UK, USA, Canada, Australia, Singapore, UAE, Malaysia, Saudi Arabia, Qatar, Oman, and so on.
        </div>

        <div class="story-item">
            <div class="story-sidebar">
                <div class="story-photo">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200" alt="Success Story">
                </div>
                <div class="story-category">USEFUL<br>TOOLS</div>
            </div>
            <div class="story-content">
                <div class="story-title">We chatted for months and decided to go ahead before his parents spoke to my father.</div>
                <p class="story-text">
                    At first we felt like we've known each other for ages. For a successful match-making and a happy marriage, both partners should be compatible. We had to meet each other's family and set expectations right before making a decision. We (Me and Abdu) think that Liyathabara is a great matchmaking site.
                </p>
                <div class="story-author">- Geya, 26, Teacher</div>
            </div>
        </div>

        <div class="highlight-message">
            Thanks to Liyathabara's secure Email and Chat facility, you can communicate and discuss matters confidentially. Site is really easy to use and mobile friendly. A site that cares about the lifestyle of community in Sri Lanka and really takes the effort to guide people properly.
        </div>

        <div class="story-item">
            <div class="story-sidebar">
                <div class="story-photo">
                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200" alt="Success Story">
                </div>
                <div class="story-category">STRICT<br>SCREENING</div>
            </div>
            <div class="story-content">
                <div class="story-title">I looked all over the world, but I found my man in Dehiwala.</div>
                <p class="story-text">
                    Within a month of adding my profile to Liyathabara, I had so many great proposals I didn't know how to choose. So when I read Ash's email I wasn't sure how to respond. We decided to meet up the next time he came to Melbourne and we soon knew we were perfect for each other!
                </p>
                <div class="story-author">- Yasmitha 28, Accountant</div>
            </div>
        </div>

        <div class="highlight-message-blue">
            Liyathabara's profile verification methods ensure that every profile is validated for genuinely & those who are serious about marriage.
        </div>

        <div class="story-item">
            <div class="story-sidebar">
                <div class="story-photo">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200" alt="Success Story">
                </div>
                <div class="story-category">TARGET<br>REACH</div>
            </div>
            <div class="story-content">
                <div class="story-title">We found a match for my sister who lives in LA sitting here in Colombo.</div>
                <p class="story-text">
                    My sister is very busy, and I knew she would only agree to someone she clicked with... and that too, with a permanent citizenship. Fortunately I set her profile on Liyathabara and found a LA based community member. She is now happily married and they plan to get married by year end ...
                </p>
                <div class="story-author">- Indra, 33, Research Analyst</div>
            </div>
        </div>

        <div class="highlight-message-red">
            With thousands of members spread across all Sri Lankan communities and languages you'll never run short of choices.
        </div>

        <div class="story-item">
            <div class="story-sidebar">
                <div class="story-photo">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200" alt="Success Story">
                </div>
                <div class="story-category">QUALITY<br>PEOPLE</div>
            </div>
            <div class="story-content">
                <div class="story-title">I was unsure about the kind of people I would find online... till I came here.</div>
                <p class="story-text">
                    The quality of the proposals was a pleasant surprise. I like how money is the main focus of the site. I feel comfortable as all my personal data is safe and private and users need authorization before seeing them. I can also browse profiles for compatibility without revealing myself and all these were very helpful. I really serious about marriage. Though I received several decent proposals and met some of them, I clicked for the whom I am with now and my life. He was working in Boston while I was with my parents in Colombo.
                </p>
                <div class="story-author">- Saundra 26, Doctor</div>
            </div>
        </div>

        <div class="cta-footer">
            Marry me for a reason, Choose the smart way to get married... Its your turn now...
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
