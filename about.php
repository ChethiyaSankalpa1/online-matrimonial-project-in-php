<?php include 'includes/header.php'; ?>

<style>
    body {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
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
        font-size: 20px;
        margin-bottom: 20px;
        color: #ffd700;
    }

    .cta-button {
        background: linear-gradient(135deg, #c62828 0%, #8b0000 100%);
        color: white;
        padding: 15px 40px;
        border: none;
        border-radius: 25px;
        font-size: 16px;
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
        padding: 40px 20px 20px;
        color: #ffd700;
        font-size: 24px;
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
        margin-bottom: 30px;
    }

    .content-title {
        color: #ff6b6b;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .content-text {
        color: #fff;
        line-height: 1.8;
        font-size: 14px;
        margin-bottom: 15px;
        text-align: justify;
    }

    .video-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        margin: 20px 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .highlight-box {
        background: rgba(255, 107, 107, 0.1);
        border-left: 4px solid #ff6b6b;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
    }

    .success-section {
        background: rgba(0, 0, 0, 0.3);
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .success-title {
        color: #ff6b6b;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .goal-section {
        background: rgba(255, 215, 0, 0.1);
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .goal-title {
        color: #ffd700;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .list-item {
        color: #fff;
        margin-bottom: 10px;
        padding-left: 20px;
        position: relative;
    }

    .list-item:before {
        content: "•";
        color: #ffd700;
        font-weight: bold;
        position: absolute;
        left: 0;
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
    About us - Years of Unprecedented Service
</div>

<div class="content-container">
    <div class="content-box">
        <h2 class="content-title">Sri Lanka Matrimony Website for Sinhalese Worldwide</h2>
        
        <p class="content-text">
            We know everyone's marriage is made in heaven, it binds you with your soul mate for the rest of your life. We stand as the premier matrimonial service, dedicated to helping you discover your ideal life partner by fostering genuine connections, based on love and mutual affection.
        </p>

        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                title="Marry Me for a Reason - Liyathabara.com" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>

        <p class="content-text">
            Whether you are looking for a bride or groom for yourself, your eligible children, sibling or friend, Liyathabara is here to serve the purpose for everyone and help you find the best match.
        </p>

        <p class="content-text">
            We don't deal in any kind of casual "hook-ups", instead, at Liyathabara our sole intention is to help the person who is seriously looking for a bride or groom who is looking for relationship with their partner.
        </p>

        <div class="highlight-box">
            <p class="content-text">
                We don't believe in any kind of continental boundaries and help our members to meet their partners from any corner of the world whether it is United States, United Kingdom, Australia, Asian countries, etc., irrespective of whatever part of the globe you belong to. We believe in helping you find your soul mate through our advanced online mail that helps you to get to know each other better. Once you start knowing the other person you can talk whether you want to settle and the person who is miles away from you. Thanks to the advanced technology that has made this possible by overcoming all the physical boundaries.
            </p>
        </div>

        <p class="content-text">
            At Liyathabara, we aim at offering unparalleled service and ensure that we stand out from any other matrimonial site. This is the reason why we are keen on providing you unique and high standard services. With us, you will also get continuous customer support that will make your experience even better.
        </p>

        <p class="content-text">
            We assure you that our matrimonial site has been designed taking ultimate care that your personal data remains fully secured. Also, even as a first time user you can smoothly navigate through the site and discover the profiles that match your area of interest.
        </p>

        <p class="content-text">
            The sole purpose of our website is to help our members hunt for their life partners (sinhala/sri lanka). The best part of being a part of Liyathabara is that you can add a profile for your family members, allows you to search for your life partner for free and if you want, the other person should be able to contact you. All the other services that can be done against paid and premium membership fees are available at a very reasonable rate that almost everyone can afford.
        </p>

        <p class="content-text">
            Choose which Matches can contact you and which can't. You'll have complete control over who you want to talk to.
        </p>

        <p class="content-text">
            Website and the Apps help keep the dating process Safe ?. Instead of meeting up with multiple people to see if they're the right one, you can make these decisions quicker and smarter with a click of a button.
        </p>

        <div class="success-section">
            <h3 class="success-title">Success Stories</h3>
            <p class="content-text">
                If you need any proof that our site works, feel free to take a look at our huge collection of happy testimonials. It's not just us everyday, written with love and joy by members who has kept us in the loop of us helping them find the special person they'd been searching for.
            </p>
        </div>

        <div class="goal-section">
            <h3 class="goal-title">Our Goal</h3>
            <p class="content-text">
                Many me for reason, Even marriage proposals can begin with love. Matchmaking Service has been created with a simple objective - to help people find their soul mates. We are different in design, approach and specializing in matchmaking we not just another matrimonial service provider. We know what we are doing, we have touched more than 650000+ members and we have helped more than 100000+ members to find their life partner through our revolutionized approach. By redefining the way brides and grooms meet for the first time, we have created a world-renowned platform that has made finding a life partner.
            </p>
        </div>

        <div class="highlight-box">
            <h3 class="success-title">Why Liyathabara Marriage Proposals?</h3>
            <p class="content-text">We don't promise you the "Sun and the Moon" like other matrimonial websites;</p>
            
            <div class="list-item">No, you won't get married within 1 month with us (to divorced next month), you may be married for life.</div>
            <div class="list-item">No, we can't do 1000+ marriages per month. But we have made for people happy, there is no obscure stat reading our members, real and loving testimonials send to us every day.</div>
            <div class="list-item">No, we don't need to pretend we are the no 1, but we know we are the best with 650000+ visits per month, over 60,000+ members and over 100000+ proposals.</div>
            <div class="list-item">No, we don't show 1000+ proposals at office and ask you to pay for telephone numbers, we don't treat our members as a marketing tool to make money for us.</div>
            <div class="list-item">No, we don't offer any personalized services. If you want to get married you should know the other person to get married better than us.</div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
