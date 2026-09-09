<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prove Your Skills - Gamora Gaming Cafe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-dark: #0a0a0f;
            --accent-red: #ff2a5f;
            --accent-purple: #8b5cf6;
            --gold-glow: #eab308;
            --card-bg: #12121a;
            --text-muted: #a1a1aa;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Rajdhani', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            /* Ambient glow spots in background for glassmorphism */
            background: 
                radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.25) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(255, 42, 95, 0.2) 0%, transparent 40%),
                #0b0b12; 
            background-attachment: fixed;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column; /* Stack back button above card */
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        /* BACK BUTTON STYLES */
        .back-nav-btn {
            align-self: flex-start;
            max-width: 1100px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: rgba(18, 18, 26, 0.6);
            border: 1px solid rgba(139, 92, 246, 0.4);
            border-radius: 8px;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 1px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .back-nav-btn i {
            color: var(--accent-purple);
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .back-nav-btn:hover {
            background: rgba(139, 92, 246, 0.2);
            border-color: rgba(139, 92, 246, 0.8);
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.4);
        }

        .back-nav-btn:hover i {
            transform: translateX(-4px);
        }

        /* MAIN HERO WRAPPER - BLURRED GLASSMORPHISM */
        .tournament-hero-section {
            width: 100%;
            max-width: 1100px;
            background: rgba(18, 18, 26, 0.55); 
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            position: relative;
            
            /* Matched blur filters */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6),
                        0 0 30px rgba(139, 92, 246, 0.15);
        }

        /* CONTENT LAYOUT */
        .hero-content {
            flex: 1.2;
            padding: 60px 40px 60px 60px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(135deg, rgba(10, 10, 15, 0.7) 0%, rgba(18, 18, 26, 0.4) 100%);
        }

        .category-tag {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 12px;
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .hero-title span {
            color: var(--accent-red);
            text-shadow: 0 0 15px rgba(255, 42, 95, 0.4);
        }

        .hero-description {
            color: var(--text-muted);
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 35px;
            max-width: 580px;
        }

        .badges-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .badge-gold {
            background: linear-gradient(90deg, rgba(234, 179, 8, 0.25), rgba(202, 138, 4, 0.15));
            border: 1px solid rgba(234, 179, 8, 0.4);
            color: #fef08a;
        }

        .badge-gold i { color: #eab308; }
        .badge-red i { color: var(--accent-red); }
        .badge-purple i { color: var(--accent-purple); }

        .hero-image-wrapper {
            flex: 1;
            position: relative;
            min-height: 380px;
            background: transparent;
            overflow: hidden;
        }

        .hero-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            -webkit-mask-image: linear-gradient(to right, transparent 0%, rgba(0, 0, 0, 0.5) 20%, black 100%);
            mask-image: linear-gradient(to right, transparent 0%, rgba(0, 0, 0, 0.5) 20%, black 100%);
        }

        @media (max-width: 900px) {
            .tournament-hero-section { flex-direction: column; }
            .hero-content { padding: 40px 30px; }
            .hero-image-wrapper { height: 250px; }
            .hero-image-wrapper img {
                -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 50%);
                mask-image: linear-gradient(to bottom, transparent 0%, black 50%);
            }
        }
    </style>
</head>
<body>

    <!-- BACK BUTTON (PLACED CORRECTLY IN BODY) -->
  <a href="../index.php" class="back-nav-btn">
    <i class="fa-solid fa-arrow-left"></i>
    <span>BACK TO HOME</span>
</a>

    <!-- MAIN HERO CONTAINER -->
    <div class="tournament-hero-section">
        <div class="hero-content">
            <div class="category-tag">TOURNAMENT</div>
            <h1 class="hero-title">Welcome to the <span>Main Stage</span></h1>
            <p class="hero-description">
                Step into the arena, claim ultimate bragging rights, and turn raw skill into glory. Every showdown brings intense rivalries, elite plays, and built-in prizes for competitive champions.
            </p>
            <div class="badges-container">
                <div class="badge badge-gold">
                    <i class="fa-solid fa-trophy"></i>
                    <span>5,000+ Monthly Prize Pool</span>
                </div>
                <div class="badge badge-red">
                    <i class="fa-solid fa-fire"></i>
                    <span>Only at Gamora's Gaming Cafe</span>
                </div>
                <div class="badge badge-purple">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Every Sunday @ 6:00 PM</span>
                </div>
            </div>
        </div>

        <div class="hero-image-wrapper">
            <img src="../image_GamingCafe/gameevent.webp" alt="Gamora Tournament">
        </div>
    </div>

</body>
</html>