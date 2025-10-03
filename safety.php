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

    .intro-text {
        color: #fff;
        line-height: 1.8;
        font-size: 14px;
        margin-bottom: 20px;
        text-align: justify;
    }

    .safety-item {
        margin-bottom: 30px;
    }

    .safety-number {
        color: #ffd700;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .safety-title {
        color: #fff;
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .safety-text {
        color: #fff;
        line-height: 1.8;
        font-size: 14px;
        margin-bottom: 10px;
        text-align: justify;
    }

    .do-section {
        background: rgba(255, 215, 0, 0.1);
        padding: 15px;
        border-left: 3px solid #ffd700;
        margin: 15px 0;
        border-radius: 5px;
    }

    .do-title {
        color: #ffd700;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .dont-section {
        background: rgba(255, 107, 107, 0.1);
        padding: 15px;
        border-left: 3px solid #ff6b6b;
        margin: 15px 0;
        border-radius: 5px;
    }

    .dont-title {
        color: #ff6b6b;
        font-weight: bold;
        margin-bottom: 8px;
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
    Privacy and Security Tips - Your safety is our utmost priority all the time
</div>

<div class="content-container">
    <div class="content-box">
        <p class="intro-text">
            Our administration ensures that every profile put up at Liyathabara is screened for instance and/or inappropriate content. We also have strict abuse-prevention and reporting systems for those that try to do get through our screening systems.
        </p>

        <p class="intro-text">
            However, ensuring your safety & privacy, we're limited to actions that are within our control. Therefore, it is necessary for you to exercise some simple precautions for your privacy and to secure experience.
        </p>

        <p class="intro-text">
            Here are some simple guidelines YOU can follow to protect your privacy.
        </p>

        <div class="safety-item">
            <div class="safety-number">1. Guard your anonymity</div>
            <p class="safety-text">
                Our system of anonymous contacting (expressing interest, accepting/declining), and private messaging (communicate section) is to ensure that your identity is protected until YOU decide to reveal it.
            </p>
            <p class="safety-text">
                Remember that you are in control of your online experience at all times. You can remain completely anonymous until YOU choose not to.
            </p>
            <div class="dont-section">
                <div class="dont-title">Don't</div>
                <p class="safety-text">
                    Don't include your personal contact information like email address, home address, telephone numbers, place of work or any other identifying information in your initial message.
                </p>
                <p class="safety-text">
                    Don't share this information until your instincts tell you that this is someone you can trust. It's okay to take your time.
                </p>
            </div>
        </div>

        <div class="safety-item">
            <div class="safety-number">2. Start slow – Use emails initially</div>
            <p class="safety-text">
                Trust your instincts and start by sharing important information and communicating solely via our mail system.
            </p>
            <div class="do-section">
                <div class="do-title">Do</div>
                <p class="safety-text">
                    Look for odd behavior or inconsistencies. Serious people will respect your space and allow you to take your time.
                </p>
                <p class="safety-text">
                    Ask a friend to read the emails you receive - an unbiased observer can spot warning signs you missed.
                </p>
                <p class="safety-text">
                    Stop communicating with anyone who pressures you for personal information or attempts in any way to trick you into revealing it.
                </p>
            </div>
        </div>

        <div class="safety-item">
            <div class="safety-number">3. Request for a photo</div>
            <p class="safety-text">
                A photo will give you a good idea of the person's appearance, which may prove helpful in achieving a gut feeling.
            </p>
            <div class="do-section">
                <div class="do-title">Do</div>
                <p class="safety-text">
                    You can use the Photo Request option on Liyathabara. Since Liyathabara offers free upload services to its members, there's no reason someone shouldn't be able to provide you a photo.
                </p>
                <p class="safety-text">
                    In fact, do best to view several images of someone in various settings: casual, formal, indoor and outdoors. If you hear an excuse about why you can't see a photo, consider that he or she has something to hide.
                </p>
            </div>
        </div>

        <div class="safety-item">
            <div class="safety-number">4. Meet only after you tell your parents</div>
            <p class="safety-text">
                When you choose to meet offline it is a good idea to try and include either or both of your families. But if you trust the person enough to meet them one-on-one (even if you or your family where you are going and when you will return.
            </p>
            <div class="do-section">
                <div class="do-title">Do</div>
                <p class="safety-text">
                    When you decide to meet face to face with someone for the first time, choose a public place (such as a restaurant / cafe) at a time when many people are around and ensure your own transportation to and fro.
                </p>
                <p class="safety-text">
                    For the first meeting it is always good not to meet the other person alone. Take a friend or relative along and tell him/her to do the same.
                </p>
                <p class="safety-text">
                    In case you decide to meet him/her alone then leave the name, address and telephone number of the person you are going to meet with your friend or family member. Take a cellular phone along with you.
                </p>
            </div>
            <div class="dont-section">
                <div class="dont-title">Don't</div>
                <p class="safety-text">
                    Never arrange for your prospective match to pick you up or drop you at home. Do not go to a secluded place or a movie alone at the first meeting.
                </p>
            </div>
        </div>

        <div class="safety-item">
            <div class="safety-number">5. Watch for warning signs</div>
            <p class="safety-text">
                Watching for warning signs and acting upon it is the surest way to avoid an uncomfortable situation.
            </p>
            <div class="do-section">
                <div class="do-title">Do</div>
                <p class="safety-text">
                    Pay a lot of questions and watch for inconsistencies. This will help you detect liars & cons, and it will help you find out if you're compatible.
                </p>
                <p class="safety-text">
                    Pay attention to displays of anger, intense frustration or attempts to pressure or control you. Acting in a resentful manner, making demeaning or disrespectful comments, or any physically inappropriate behavior are all warning signals.
                </p>
                <p class="safety-text">
                    Involve your family or your close friends in your search for a life partner and do not take a decision unilaterally.
                </p>
            </div>
            <div class="dont-section">
                <div class="dont-title">Don't</div>
                <p class="safety-text">
                    Don't ignore the following behavior especially if it is without an acceptable explanation.
                </p>
                <p class="safety-text">
                    Provides inconsistent information about age, interests, appearance, marital status, profession, employment etc.
                </p>
                <p class="safety-text">
                    Evades your attempts to answer to direct questions.
                </p>
                <p class="safety-text">
                    Appears significantly different in person from his or her online persona.
                </p>
                <p class="safety-text">
                    Never introduces you to friends, professional associates or family members.
                </p>
            </div>
        </div>

        <div class="safety-item">
            <div class="safety-number">6. Beware of money scams</div>
            <p class="safety-text">
                Watch out for money scams. There are just too many con artists and scam artists around the world, and they are everywhere.
            </p>
            <div class="do-section">
                <div class="do-title">Do</div>
                <p class="safety-text">
                    Be wary of those who try to ask money from you for whatever reason. But it would be safer to cut off all the communication. Remember that most scams will not ask for money directly but will ask you to send money for them for some reason or the other. Use your common sense and never give in to such requests.
                </p>
                <p class="safety-text">
                    In case someone asks you for money report the situation to us.
                </p>
            </div>
            <div class="dont-section">
                <div class="dont-title">Don't</div>
                <p class="safety-text">
                    Take all the time you need to decide on a trustworthy person and pay careful attention to what they say. Be responsible about your financial decisions. The oldest con tricks of people who shower love and affection at the first instance and disappear later.
                </p>
                <p class="safety-text">
                    Don't become prematurely close to someone, even if that intimacy only occurs online.
                </p>
                <p class="safety-text">
                    Don't send money to someone in your best but because ultimately you are responsible for your personal experience. Trust your instincts and choose to interact with the right person.
                </p>
            </div>
        </div>

        <p class="intro-text">
            If you do have an unpleasant experience you can always report it to the authorities. We serve only for the people who are serious about the marriage. All your actions including IM messages, Mail messages are recorded in our system, we will fully support Police / Cyber Crime Investigation Cell.
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
