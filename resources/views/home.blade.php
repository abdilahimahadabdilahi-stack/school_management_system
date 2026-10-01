<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Al-Huda Primary and Intermediate School: building knowledge, shaping character, and creating a brighter future.">
    <title>Al-Huda Primary and Intermediate School</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --green: #087443;
            --green-dark: #075b37;
            --green-soft: #e7f5ee;
            --ink: #102a2a;
            --muted: #5d6d6b;
            --orange: #f39a1f;
            --line: #e5eeeb;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background: #ffffff;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            margin: 0;
        }
        a { color: inherit; text-decoration: none; }
        .site-header {
            align-items: center;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid rgba(16, 42, 42, 0.07);
            display: flex;
            justify-content: space-between;
            min-height: 76px;
            padding: 8px clamp(20px, 5vw, 72px);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .brand img { display: block; height: 60px; object-fit: contain; width: 190px; }
        .main-nav { align-items: center; display: flex; gap: clamp(14px, 2.4vw, 34px); }
        .main-nav a { color: #263d3b; font-size: 0.84rem; font-weight: 600; transition: color .2s ease; }
        .main-nav a:hover, .main-nav a.active { color: var(--green); }
        .main-nav .nav-cta {
            background: var(--green);
            border-radius: 999px;
            color: #fff;
            padding: 12px 19px;
        }
        .main-nav .nav-cta:hover { background: var(--green-dark); color: #fff; }
        .menu-toggle { background: transparent; border: 0; color: var(--green); display: none; font-size: 1.4rem; }

        .hero {
            align-items: center;
            background: linear-gradient(90deg, rgba(235, 249, 240, .99) 0%, rgba(235, 249, 240, .86) 35%, rgba(235, 249, 240, .16) 68%), url('https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=2200&q=85') center/cover;
            display: flex;
            min-height: 500px;
            padding: 62px clamp(20px, 5vw, 72px);
        }
        .hero-copy { max-width: 600px; }
        .eyebrow { color: var(--green); font-size: 1.25rem; font-weight: 800; letter-spacing: .01em; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        h1 { color: #102d2d; font-size: clamp(2.7rem, 5.3vw, 5rem); letter-spacing: -.05em; line-height: .98; margin: 10px 0 20px; }
        .hero-copy p { color: #244541; font-size: 1rem; line-height: 1.7; margin: 0 0 26px; max-width: 500px; }
        .button { align-items: center; background: var(--green); border-radius: 999px; color: #fff; display: inline-flex; font-size: .9rem; font-weight: 700; gap: 12px; padding: 14px 21px; transition: background .2s ease, transform .2s ease; }
        .button:hover { background: var(--green-dark); color: #fff; transform: translateY(-2px); }
        .button.light { background: #fff; color: var(--green); }

        .pillars { border-bottom: 1px solid var(--line); border-top: 1px solid var(--line); display: grid; grid-template-columns: repeat(4, 1fr); padding: 20px clamp(20px, 7vw, 100px); }
        .pillar { align-items: center; border-right: 1px solid var(--line); display: flex; gap: 15px; padding: 3px clamp(12px, 2.2vw, 34px); }
        .pillar:last-child { border-right: 0; }
        .pillar-icon { align-items: center; background: var(--green); border-radius: 50%; color: #fff; display: flex; flex: 0 0 52px; font-size: 1.2rem; height: 52px; justify-content: center; }
        .pillar:nth-child(2) .pillar-icon { background: #f04444; }
        .pillar:nth-child(3) .pillar-icon { background: #1682d4; }
        .pillar:nth-child(4) .pillar-icon { background: var(--orange); }
        .pillar strong { display: block; font-size: .86rem; margin-bottom: 4px; }
        .pillar span { color: var(--muted); font-size: .72rem; }

        .about { align-items: center; background: linear-gradient(130deg, #f5fbf8 0%, #ffffff 72%); display: grid; gap: clamp(30px, 6vw, 88px); grid-template-columns: minmax(280px, 1fr) minmax(280px, .95fr); padding: 74px clamp(20px, 7vw, 100px) 90px; }
        .about-image { background: linear-gradient(145deg, rgba(8, 116, 67, .15), rgba(8, 116, 67, .02)), url('https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=85') center/cover; border-radius: 12px; box-shadow: 20px 20px 0 #d7eee2; min-height: 310px; }
        .section-kicker { color: var(--green); font-size: .86rem; font-weight: 800; }
        .about h2 { font-size: clamp(2rem, 3.2vw, 3rem); letter-spacing: -.045em; line-height: 1.05; margin: 10px 0 18px; }
        .about p { color: var(--muted); line-height: 1.75; max-width: 540px; }
        .quote { border-left: 2px solid var(--green); color: var(--green-dark); font-family: 'Manrope', sans-serif; font-size: 1.05rem; margin: 25px 0; padding-left: 16px; }
        .contact-band { align-items: center; background: var(--green-dark); color: #fff; display: flex; justify-content: space-between; padding: 32px clamp(20px, 7vw, 100px); }
        .contact-band h2 { font-size: 1.6rem; margin: 0 0 5px; }
        .contact-band p { color: rgba(255,255,255,.75); margin: 0; }
        footer { background: #063d27; color: rgba(255,255,255,.7); font-size: .78rem; padding: 22px clamp(20px, 7vw, 100px); text-align: center; }

        @media (max-width: 800px) {
            .main-nav { background: #fff; box-shadow: 0 15px 25px rgba(16, 42, 42, .1); display: none; flex-direction: column; left: 0; padding: 20px; position: absolute; right: 0; top: 76px; }
            .main-nav.open { display: flex; }
            .menu-toggle { display: block; }
            .hero { background-position: 65% center; min-height: 560px; }
            .hero-copy { background: rgba(239, 250, 244, .82); border-radius: 14px; padding: 24px; }
            .pillars { grid-template-columns: repeat(2, 1fr); }
            .pillar { border-bottom: 1px solid var(--line); padding: 16px 8px; }
            .pillar:nth-child(2) { border-right: 0; }
            .pillar:nth-child(3), .pillar:nth-child(4) { border-bottom: 0; }
            .about { grid-template-columns: 1fr; padding-bottom: 65px; }
            .about-image { min-height: 240px; }
            .contact-band { align-items: flex-start; flex-direction: column; gap: 18px; }
        }
        @media (max-width: 460px) {
            .brand img { height: 52px; width: 165px; }
            .hero { padding: 35px 16px; }
            .hero-copy { padding: 18px; }
            .pillar { align-items: flex-start; flex-direction: column; gap: 8px; }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}" aria-label="Al-Huda School home">
            <img src="{{ asset('images/alhuda-logo.svg') }}" alt="Al-Huda Primary and Intermediate School">
        </a>
        <button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" onclick="toggleMenu(this)"><i class="fa-solid fa-bars"></i></button>
        <nav class="main-nav" id="mainNav" aria-label="Main navigation">
            <a class="active" href="{{ route('home') }}">Home</a>
            <a href="#about">About Us</a>
            <a href="#academics">Academics</a>
            <a href="#admissions">Admissions</a>
            <a href="#gallery">Gallery</a>
            <a href="#contact">Contact</a>
            <a class="nav-cta" href="{{ route('login') }}"><i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Staff login</a>
        </nav>
    </header>

    <main>
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-copy">
                <div class="eyebrow">Welcome to</div>
                <h1 id="hero-title">AL-HUDA<br>Primary and Intermediate School</h1>
                <p>Building knowledge <b>·</b> Shaping character <b>·</b> Creating a brighter future</p>
                <a class="button" href="#about">Discover our school <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </section>

        <section class="pillars" id="academics" aria-label="School strengths">
            <div class="pillar"><div class="pillar-icon"><i class="fa-solid fa-book-open"></i></div><div><strong>Quality Education</strong><span>For a better tomorrow</span></div></div>
            <div class="pillar"><div class="pillar-icon"><i class="fa-solid fa-users"></i></div><div><strong>Experienced Teachers</strong><span>Guiding every step</span></div></div>
            <div class="pillar"><div class="pillar-icon"><i class="fa-solid fa-graduation-cap"></i></div><div><strong>Safe &amp; Supportive</strong><span>Learning environment</span></div></div>
            <div class="pillar"><div class="pillar-icon"><i class="fa-regular fa-lightbulb"></i></div><div><strong>Holistic Development</strong><span>Mind, body &amp; character</span></div></div>
        </section>

        <section class="about" id="about">
            <div class="about-image" role="img" aria-label="Al-Huda school campus"></div>
            <div>
                <div class="section-kicker">About Our School</div>
                <h2>A place to learn, grow and achieve</h2>
                <p>Al-Huda Primary and Intermediate School is committed to providing high-quality education in a safe, supportive and nurturing environment. We aim to build confident, responsible and successful learners who make a positive difference in their communities and the world.</p>
                <div class="quote">“Education is the key to a brighter future.”</div>
                <a class="button" href="#admissions">Learn more <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </section>

        <section class="contact-band" id="admissions">
            <div><h2>Start your child’s journey with us</h2><p>Discover a supportive school community built for curious, capable learners.</p></div>
            <a class="button light" href="#contact">Admissions enquiry <i class="fa-solid fa-arrow-right"></i></a>
        </section>
        <section id="gallery" aria-hidden="true"></section>
        <section class="contact-band" id="contact">
            <div><h2>We would love to hear from you</h2><p>Contact the Al-Huda school office for admissions and school information.</p></div>
            <a class="button light" href="mailto:info@alhuda-school.com"><i class="fa-solid fa-envelope"></i> Email the school</a>
        </section>
    </main>

    <footer>© {{ date('Y') }} Al-Huda Primary and Intermediate School. All rights reserved.</footer>
    <script>
        function toggleMenu(button) {
            const navigation = document.getElementById('mainNav');
            const isOpen = navigation.classList.toggle('open');
            button.setAttribute('aria-expanded', isOpen);
            button.innerHTML = `<i class="fa-solid fa-${isOpen ? 'xmark' : 'bars'}"></i>`;
        }

        document.querySelectorAll('#mainNav a').forEach((link) => {
            link.addEventListener('click', () => document.getElementById('mainNav').classList.remove('open'));
        });
    </script>
</body>
</html>