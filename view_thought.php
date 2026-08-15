<?php
require_once 'db.php';

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM thoughts WHERE unique_id = ?");
    $stmt->execute([$id]);
    $thought = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$thought) {
        die("Thought not found. The link might be invalid or expired.");
    }

    if (isset($thought['is_private']) && $thought['is_private'] == 1) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Private Page - wishme15august.space</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        </head>
        <body class="bg-[#020617] text-white min-h-screen flex flex-col items-center justify-center p-6 text-center">
            <div class="max-w-md w-full bg-white/5 border border-white/10 p-8 rounded-3xl backdrop-blur-md">
                <i class="fa-solid fa-lock text-6xl text-orange-500 mb-6"></i>
                <h1 class="text-2xl font-bold mb-4">This page is private</h1>
                <p class="text-gray-400 text-sm mb-6">This viral message has been set to private by the administrator.</p>
                <a href="index.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-green-600 text-white font-bold px-6 py-3 rounded-xl hover:scale-105 transition-transform text-sm">
                    Create Your Own Message 🇮🇳
                </a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
} catch (PDOException $e) {
    die("Database Error.");
}

$pageUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$userName = htmlspecialchars($thought['user_name']);
$messageText = htmlspecialchars($thought['message_text']);
$userImagePath = 'uploads/' . htmlspecialchars($thought['user_image']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RNY0WN1344"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-RNY0WN1344');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $userName ?> sent you a surprise!</title>
    
    <?php
    $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['REQUEST_URI']), '/');
    $ogImageUrl = $baseUrl . '/' . $userImagePath;
    ?>
    <meta property="og:title" content="<?= $userName ?> sent you a surprise!" />
    <meta property="og:description" content="Open to see this magical surprise message from <?= $userName ?>" />
    <meta property="og:image" content="<?= $ogImageUrl ?>" />
    <meta property="og:url" content="<?= $pageUrl ?>" />
    <meta property="og:type" content="website" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= $userName ?> sent you a surprise!" />
    <meta name="twitter:description" content="Open to see this magical surprise message from <?= $userName ?>" />
    <meta name="twitter:image" content="<?= $ogImageUrl ?>" />

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= $userName ?> has sent you a magical surprise. Open to see this viral message!">
    <meta name="keywords" content="happy independence day, happy independence day wishes, happy independence day wishes with names, 15 august 2026, magical independence day surprise, viral message">
    <meta name="author" content="wishme15august.space">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="assets/favicon/site.webmanifest" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- GSAP & Confetti -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'india-saffron': '#FF9933',
                        'india-green': '#138808',
                        'india-blue': '#000080',
                        'dark-bg': '#040814',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Cinzel', 'serif'],
                    },
                    animation: {
                        'spin-slow': 'spin 8s linear infinite',
                        'pulse-glow': 'pulseGlow 2s infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(255, 153, 51, 0.4)' },
                            '50%': { boxShadow: '0 0 40px rgba(19, 136, 8, 0.6)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #040814;
            color: #f8fafc;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .bg-flag-layer {
            position: fixed;
            inset: -5%;
            z-index: -2;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(255, 153, 51, 0.15), transparent 40%),
                radial-gradient(circle at 85% 30%, rgba(19, 136, 8, 0.15), transparent 40%),
                url('assets/images/flag_bg.jpg');
            background-color: #040814;
            background-size: cover, cover, cover;
            background-position: center;
            background-blend-mode: screen, screen, overlay;
            animation: slowPan 30s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes slowPan {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.06) translate(-1%, 1%); }
        }

        /* Cinematic Orbs */
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
            animation: orbFloat 25s infinite ease-in-out alternate;
        }
        .orb-saffron { background: #FF9933; width: 70vw; height: 70vw; top: -20%; left: -20%; }
        .orb-green { background: #138808; width: 70vw; height: 70vw; bottom: -20%; right: -20%; animation-delay: -12s; }
        .orb-blue { background: #000080; width: 50vw; height: 50vw; top: 40%; left: 25%; animation-delay: -5s; opacity: 0.1; }
        
        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(15vw, 15vh) scale(1.3); }
        }

        /* Magic Entry Screen */
        #entry-screen {
            position: fixed; inset: 0; z-index: 99999;
            background: rgba(4, 8, 20, 0.98);
            backdrop-filter: blur(15px);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .magic-btn {
            background: linear-gradient(135deg, rgba(255,153,51,0.2), rgba(19,136,8,0.2));
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            padding: 20px 45px;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 0 40px rgba(255, 153, 51, 0.3), inset 0 0 20px rgba(255,255,255,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        .magic-btn::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: all 0.6s;
        }
        .magic-btn:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 15px 50px rgba(19, 136, 8, 0.5), inset 0 0 30px rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.5);
        }
        .magic-btn:hover::before { left: 100%; }

        /* Main Content */
        #main-content {
            display: none;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px 140px 20px;
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Shimmering & Gradient Text */
        .shimmer-text {
            background: linear-gradient(90deg, #FF9933 0%, #ffffff 50%, #138808 100%);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            animation: shimmer 4s linear infinite;
        }
        @keyframes shimmer {
            to { background-position: 200% center; }
        }

        /* Premium Profile Ring */
        .profile-container {
            position: relative;
            width: 160px; height: 160px;
            margin: 0 auto;
        }
        .profile-ring {
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, #FF9933, transparent 120deg, #ffffff, transparent 240deg, #138808);
            animation: spin 4s linear infinite;
            box-shadow: 0 0 30px rgba(255, 153, 51, 0.3);
        }
        .profile-img-wrapper {
            position: absolute;
            inset: 3px;
            border-radius: 50%;
            background: #040814;
            padding: 4px;
            z-index: 10;
        }
        .profile-img {
            width: 100%; height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.1);
        }

        /* Holographic Glass Card */
        .holographic-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.01) 100%);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 35px 25px;
            width: 100%;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 0 20px rgba(255,255,255,0.02);
            position: relative;
            overflow: hidden;
            margin-top: 40px;
        }
        .holographic-card::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.05), transparent);
            transform: skewX(-20deg);
            animation: holo-shine 6s infinite;
        }
        @keyframes holo-shine {
            0%, 100% { left: -100%; }
            20% { left: 200%; }
        }

        /* Premium Greeting Container */
        .premium-greeting-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0.01) 100%);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 2rem;
            padding: 40px 25px 30px 25px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4), inset 0 0 20px rgba(255,255,255,0.02);
            position: relative;
            overflow: hidden;
        }
        .premium-greeting-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FF9933, #ffffff, #138808);
            box-shadow: 0 2px 10px rgba(255,255,255,0.3);
        }

        /* Message Quote Area */
        .quote-box {
            position: relative;
            padding: 30px 20px;
            margin: 20px 0;
        }
        .quote-icon {
            font-size: 3rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.2), transparent);
            -webkit-background-clip: text;
            color: transparent;
            position: absolute;
        }

        /* Viral Bottom CTA */
        .viral-cta-wrapper {
            position: fixed;
            bottom: 0; left: 0; width: 100%;
            background: linear-gradient(to top, rgba(4,8,20,1) 40%, rgba(4,8,20,0.8) 80%, transparent 100%);
            padding: 20px 15px 25px 15px;
            z-index: 100;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .btn-viral {
            background: linear-gradient(90deg, #FF9933, #e67e22, #138808);
            background-size: 200% auto;
            color: white;
            padding: 16px 35px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 1px;
            box-shadow: 0 10px 30px rgba(255, 153, 51, 0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            text-transform: uppercase;
            animation: gradientMove 3s linear infinite, pulseGlow 2s infinite;
            border: 2px solid rgba(255,255,255,0.2);
            width: 90%;
            max-width: 400px;
            justify-content: center;
        }
        .btn-viral:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 40px rgba(19, 136, 8, 0.6);
        }
        @keyframes gradientMove {
            to { background-position: 200% center; }
        }
        
        .social-btn {
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s;
        }
        .social-btn:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.15);
        }
    </style>
    <script src="https://manhoodinvoluntaryplash.com/71/de/d2/71ded26281c3689ac8e6539bd88c4659.js"></script>
</head>
<body>
    <!-- Animated Flag Background Layer -->
    <div class="bg-flag-layer"></div>

    <!-- Background Orbs -->
    <div class="bg-orb orb-saffron"></div>
    <div class="bg-orb orb-blue"></div>
    <div class="bg-orb orb-green"></div>

    <!-- Audio Element -->
    <audio id="bg-audio" loop preload="auto">
        <source src="assets/audio/Vande Mataram Vande Mataram - Maa Tujhe Salaam _ Ar Rahman1.mp3" type="audio/mpeg">
    </audio>

    <!-- Magic Entry Screen -->
    <div id="entry-screen">
        <div class="text-center px-4 animate-float">
            <div class="relative w-32 h-32 mx-auto mb-8">
                <div class="absolute inset-0 border-4 border-india-saffron/30 rounded-full animate-ping"></div>
                <div class="absolute inset-2 border-4 border-india-green/50 rounded-full animate-spin-slow"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fa-solid fa-gift text-6xl text-white drop-shadow-[0_0_15px_rgba(255,255,255,0.8)]"></i>
                </div>
            </div>
            
            <h2 class="text-2xl font-sans font-medium text-gray-300 mb-1 tracking-widest uppercase text-sm">A Surprise From</h2>
            <h1 class="text-4xl md:text-6xl font-serif font-black text-transparent bg-clip-text bg-gradient-to-r from-india-saffron via-white to-india-green drop-shadow-lg mb-8" style="line-height: 1.2;">
                <?= $userName ?>
            </h1>
            
            <button class="magic-btn" id="magic-btn">
                Tap To Open <i class="fa-solid fa-unlock-keyhole ml-2"></i>
            </button>
            <p class="mt-6 text-xs text-gray-500 uppercase tracking-[0.2em] animate-pulse">Turn on volume for best experience</p>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content">
        
        <!-- Premium Greeting Card Container -->
        <div class="premium-greeting-card w-full max-w-2xl mx-auto mt-6 mb-8 flex flex-col items-center" id="greeting-card">
            
            <!-- Header text -->
            <div class="bg-black/20 backdrop-blur-md rounded-full px-8 py-2 border border-white/10 shadow-inner mb-8">
                <h2 class="text-xs md:text-sm font-serif font-bold tracking-[0.4em] text-center uppercase shimmer-text m-0">
                    <i class="fa-solid fa-dharmachakra text-india-blue text-[10px] mr-2 animate-spin-slow"></i>
                    Happy 15th August
                    <i class="fa-solid fa-dharmachakra text-india-blue text-[10px] ml-2 animate-spin-slow"></i>
                </h2>
            </div>

            <!-- Profile Section -->
            <div class="profile-container">
                <div class="profile-ring"></div>
                <div class="profile-img-wrapper">
                    <img src="<?= $userImagePath ?>" alt="<?= $userName ?>" class="profile-img">
                </div>
                <!-- Floating badge -->
                <div class="absolute -bottom-2 -right-2 bg-gradient-to-r from-india-saffron to-india-green p-[2px] rounded-full shadow-lg z-20 animate-bounce">
                    <div class="bg-dark-bg rounded-full p-1.5">
                        <i class="fa-solid fa-check text-white text-xs"></i>
                    </div>
                </div>
            </div>

            <h1 class="text-4xl md:text-6xl font-serif font-black text-white mt-8 mb-2 text-center drop-shadow-[0_0_15px_rgba(255,255,255,0.2)]" id="anim-name">
                <?= $userName ?>
            </h1>
            
            <!-- User Quote -->
            <div class="quote-box w-full text-center mt-2 relative">
                <i class="fa-solid fa-quote-left quote-icon -top-4 -left-2 opacity-30 text-4xl"></i>
                <p class="text-xl md:text-2xl text-gray-100 font-sans font-light leading-relaxed italic relative z-10" id="user-quote" style="min-height: 90px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                    <!-- Typewriter fills here -->
                </p>
                <i class="fa-solid fa-quote-right quote-icon -bottom-6 -right-2 opacity-30 text-4xl"></i>
            </div>
        </div>

        <!-- Facts Holographic Card -->
        <div class="w-full mt-6">
            <div class="flex items-center gap-4 mb-6 justify-center opacity-70">
                <span class="h-px w-16 bg-gradient-to-r from-transparent to-india-saffron"></span>
                <span class="text-india-saffron font-bold uppercase tracking-[0.2em] text-xs"><i class="fa-solid fa-bolt text-yellow-400 mr-1"></i> Did You Know?</span>
                <span class="h-px w-16 bg-gradient-to-l from-transparent to-india-green"></span>
            </div>
            
            <div class="holographic-card" id="fact-card">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-india-saffron via-white to-india-green"></div>
                <h3 class="text-2xl md:text-3xl font-serif font-bold text-white mb-4 drop-shadow-lg" id="fact-title">
                    Loading...
                </h3>
                <p class="text-lg text-gray-300 leading-relaxed font-sans font-light" id="fact-text" style="min-height: 100px;">
                    Discovering the hidden gems of our freedom struggle...
                </p>
            </div>
        </div>

        <!-- Native Ad Banner -->
        <div class="w-full max-w-lg mx-auto my-8 px-4">
            <div class="bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 p-4 shadow-lg">
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 text-center">Sponsored Content</div>
                <div id="container-a33d65a83a21d060d1951823f6c29827" class="w-full overflow-hidden flex justify-center"></div>
                <script async="async" data-cfasync="false" src="https://manhoodinvoluntaryplash.com/a33d65a83a21d060d1951823f6c29827/invoke.js"></script>
            </div>
        </div>

        <!-- Horizontal 728x90 Ad Banner (Hidden on Mobile for UX) -->
        <div class="hidden md:flex justify-center my-6">
            <div class="overflow-hidden bg-white/5 p-2 rounded-xl border border-white/10 shadow-lg">
                <script> atOptions = { 'key' : '4c489d033932744ca7a2e51a14d42a04', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {} }; </script>
                <script src="https://manhoodinvoluntaryplash.com/4c489d033932744ca7a2e51a14d42a04/invoke.js"></script>
            </div>
        </div>

        <!-- Social Share Buttons -->
        <div class="mt-14 flex flex-col items-center w-full">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-5 font-bold">Share This With Friends</p>
            <div class="flex gap-5" id="social-buttons">
                <a href="https://api.whatsapp.com/send?text=Look%20what%20<?= urlencode($userName) ?>%20made%20for%20you!%20%F0%9F%87%AE%F0%9F%87%B3%20<?= urlencode($pageUrl) ?>" target="_blank" 
                   class="social-btn w-14 h-14 rounded-full text-[#25D366] flex items-center justify-center text-2xl shadow-[0_0_15px_rgba(37,211,102,0.1)]">
                    <i class="fa-brands fa-whatsapp drop-shadow-md"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($pageUrl) ?>" target="_blank" 
                   class="social-btn w-14 h-14 rounded-full text-[#1877F2] flex items-center justify-center text-2xl shadow-[0_0_15px_rgba(24,119,242,0.1)]">
                    <i class="fa-brands fa-facebook-f drop-shadow-md"></i>
                </a>
                <button onclick="copyLink()" 
                   class="social-btn w-14 h-14 rounded-full text-white flex items-center justify-center text-xl shadow-[0_0_15px_rgba(255,255,255,0.1)]">
                    <i class="fa-solid fa-link drop-shadow-md"></i>
                </button>
            </div>
            
            <!-- Smartlink Sponsor Button -->
            <div class="mt-6 text-center">
                <a href="https://manhoodinvoluntaryplash.com/f0dq45ix5?key=276d18012448536d74b877f879b807ef" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 hover:bg-white/10 text-india-saffron hover:text-white transition-all px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm animate-bounce">
                    <i class="fa-solid fa-gift"></i> Claim Free Independence Day Gifts! 🇮🇳
                </a>
            </div>
        </div>
    </div>

    <!-- Viral Fixed Bottom CTA -->
    <div class="viral-cta-wrapper" id="cta-button">
        <p class="text-white text-xs font-bold uppercase tracking-widest mb-3 animate-pulse">Want to surprise someone?</p>
        <a href="thought.php" class="btn-viral">
            <i class="fa-solid fa-wand-magic-sparkles"></i> CREATE YOUR OWN - 100% FREE
        </a>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-white/90 backdrop-blur-md text-gray-900 px-6 py-3 rounded-full shadow-2xl transition-all duration-300 opacity-0 translate-y-[-20px] pointer-events-none z-[100] font-bold flex items-center gap-3 border border-gray-200">
        <div class="w-6 h-6 rounded-full bg-india-green flex items-center justify-center text-white text-sm"><i class="fa-solid fa-check"></i></div>
        Link Copied Successfully!
    </div>

    <script>
        gsap.registerPlugin(TextPlugin);

        const userMessage = <?= json_encode($messageText) ?>;

        // The Massive Facts Array
        const facts = [
            { t: "Bagha Jatin", f: "Fought a fierce frontline battle against British forces on the banks of the Buribalam river in 1915 with only a handful of associates." },
            { t: "Surya Sen (Masterda)", f: "The mastermind behind the 1930 Chittagong armoury raid, which shook the foundations of the British Empire in Bengal." },
            { t: "Batukeshwar Dutt", f: "Threw non-lethal bombs in the Central Legislative Assembly with Bhagat Singh in 1929. Survived Kalapani but died in extreme poverty." },
            { t: "Hemu Kalani", f: "Hanged at the age of 19 for his revolutionary activities during the Quit India Movement in the Sindh province in 1942." },
            { t: "Bina Das", f: "Fired at the Governor of Bengal, Stanley Jackson, from point-blank range during the Calcutta University convocation in 1932." },
            { t: "Pritilata Waddedar", f: "Led the attack on the Pahartali European Club (which had a sign reading 'Dogs and Indians not allowed') and consumed cyanide to avoid arrest." },
            { t: "Kalpana Datta", f: "She joined Surya Sen’s revolutionary group in 1931 and later participated in several armed activities, including plans connected with the European Club attack." },
            { t: "Basanta Kumar Biswas", f: "A key revolutionary in the Delhi-Lahore Conspiracy who threw a bomb at the Viceroy of India, Lord Hardinge, in 1912." },
            { t: "Matangini Hazra", f: "Shot dead by police at the age of 73 during the Quit India Movement, but did not let the tricolor fall from her hands until her dying breath." },
            { t: "Kanaklata Barua", f: "A 16-year-old girl from Assam who was shot dead by police while leading a procession holding the national flag in 1942." },
            { t: "Rani Gaidinliu", f: "A Naga leader who joined the anti-British movement at just 13. The British sentenced her to life imprisonment; she was released in 1947 after 14 years." },
            { t: "Velu Nachiyar", f: "She was one of the earliest Indian queens to wage an armed war against the East India Company, successfully reclaiming Sivaganga in 1780." },
            { t: "Kuyili", f: "She is traditionally credited with carrying out an early human-bomb-style attack by setting herself ablaze and destroying a British ammunition store." },
            { t: "Jhalkari Bai", f: "According to popular accounts, she disguised herself to confuse the British, allowing Rani Lakshmibai to escape." },
            { t: "Uda Devi", f: "Popular accounts credit her with killing 32 British soldiers from a tree; the exact number is disputed." },
            { t: "Aruna Asaf Ali", f: "Hoisted the national flag at Gowalia Tank Maidan in 1942, taking charge of the Quit India Movement after top Congress leaders were arrested." },
            { t: "Tilka Majhi", f: "Led one of the earliest recorded tribal armed resistances against the East India Company." },
            { t: "Birsa Munda", f: "Spearheaded the Munda Rebellion in the late 19th century against the agrarian system. He died in Ranchi jail in 1900 at the age of 25." },
            { t: "Alluri Sitarama Raju", f: "Leader of the Rampa Rebellion (1922) who fought fiercely for tribal rights (the blockbuster movie 'RRR' was loosely inspired by him)." },
            { t: "Komaram Bheem", f: "Led a prolonged tribal resistance against the Nizam's Hyderabad State and its feudal/forest administration." },
            { t: "Yusuf Meherally", f: "A socialist leader who actually coined the iconic slogans 'Quit India' and 'Simon Go Back'." },
            { t: "Hasrat Mohani", f: "The first to demand 'Poorna Swaraj' in 1921 and the creator of the immortal slogan 'Inquilab Zindabad' (Long Live the Revolution)." },
            { t: "Chempakaraman Pillai", f: "Chempakaraman Pillai is credited by several sources with early use of ‘Jai Hind’, although the attribution is disputed and Abid Hasan Safrani is also widely credited." },
            { t: "Pingali Venkayya", f: "He designed the 1921 Swaraj flag that became an important foundation in the evolution of India's national flag." }
        ];

        // Magic Entry Logic
        document.getElementById('magic-btn').addEventListener('click', function() {
            // Audio Logic
            const audio = document.getElementById('bg-audio');
            if (audio) {
                audio.volume = 0.5;
                audio.play().catch(e => console.log("Audio play failed/blocked"));
            }

            const entryScreen = document.getElementById('entry-screen');
            entryScreen.style.opacity = '0';
            
            setTimeout(() => {
                entryScreen.style.display = 'none';
                document.getElementById('main-content').style.display = 'flex';
                
                // Advanced Confetti Blast
                fireConfetti();
                
                // GSAP Premium Entry Animations
                const tl = gsap.timeline();
                tl.from('#greeting-card', { y: 60, opacity: 0, duration: 1, ease: 'power3.out' })
                  .from('.profile-container', { scale: 0, rotation: 180, duration: 1.2, ease: 'elastic.out(1, 0.5)' }, '-=0.5')
                  .from('#anim-name', { y: 30, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.8')
                  .to('#user-quote', { text: `"${userMessage}"`, duration: Math.min(userMessage.length * 0.05, 3.5), ease: 'none' }, '-=0.2')
                  .from('.holographic-card', { y: 50, opacity: 0, rotationX: 20, duration: 1, ease: 'power3.out' }, '-=0.5')
                  .fromTo('#social-buttons .social-btn', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.6, stagger: 0.15, ease: 'back.out(1.5)' }, '-=0.5')
                  .fromTo('#cta-button', { y: '100%' }, { y: '0%', duration: 1, ease: 'power4.out' }, '-=0.5');

                // Start Facts Loop
                startFactsCarousel();
            }, 1000);
        });

        // Advanced Confetti Array
        function fireConfetti() {
            const duration = 6000;
            const end = Date.now() + duration;

            (function frame() {
                // Saffron
                confetti({ particleCount: 3, angle: 60, spread: 60, origin: { x: 0, y: 0.8 }, colors: ['#FF9933'], zIndex: 999 });
                // Green
                confetti({ particleCount: 3, angle: 120, spread: 60, origin: { x: 1, y: 0.8 }, colors: ['#138808'], zIndex: 999 });
                
                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                } else {
                    // Final center burst
                    confetti({ particleCount: 100, spread: 100, origin: { y: 0.5 }, colors: ['#FF9933', '#FFFFFF', '#138808'], zIndex: 999 });
                }
            }());
        }

        // Facts Carousel Logic using GSAP
        function startFactsCarousel() {
            const titleEl = document.getElementById('fact-title');
            const textEl = document.getElementById('fact-text');
            let currentIndex = 0;

            function showNextFact() {
                const fact = facts[currentIndex];
                
                // Animate out
                gsap.to([titleEl, textEl], {
                    opacity: 0,
                    x: -20,
                    duration: 0.5,
                    onComplete: () => {
                        titleEl.innerText = fact.t;
                        textEl.innerText = "";
                        
                        // Animate title in
                        gsap.fromTo(titleEl, 
                            { opacity: 0, x: 20 },
                            { opacity: 1, x: 0, duration: 0.6, ease: "power2.out" }
                        );

                        // Typewriter text in
                        gsap.fromTo(textEl, 
                            { opacity: 1, x: 0 },
                            { text: fact.f, duration: Math.min(fact.f.length * 0.04, 3.5), ease: 'none', delay: 0.2 }
                        );
                    }
                });

                currentIndex = (currentIndex + 1) % facts.length;
                setTimeout(showNextFact, 8000); 
            }

            setTimeout(showNextFact, 2500);
        }

        // Copy Link with nice Toast
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const toast = document.getElementById('toast');
                toast.classList.remove('opacity-0', 'translate-y-[-20px]');
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-[-20px]');
                }, 3000);
            });
        }
    </script>
    <script src="https://manhoodinvoluntaryplash.com/9d/5e/cc/9d5eccdda7f8d548b44da339b0cd7924.js"></script>
</body>
</html>