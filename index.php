<?php
require_once 'db.php';

// Fetch recent creations (wishes & thoughts combined)
try {
    $stmt = $pdo->query("
        (SELECT 'wish' AS type, unique_id, user_name, user_image, created_at FROM wishes WHERE is_private = 0)
        UNION ALL
        (SELECT 'thought' AS type, unique_id, user_name, user_image, created_at FROM thoughts WHERE is_private = 0)
        ORDER BY created_at DESC LIMIT 8
    ");
    $recentWishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $galleryStmt = $pdo->query("
        (SELECT 'wish' AS type, unique_id, user_name, user_image, created_at FROM wishes WHERE is_private = 0)
        UNION ALL
        (SELECT 'thought' AS type, unique_id, user_name, user_image, created_at FROM thoughts WHERE is_private = 0)
        ORDER BY created_at DESC LIMIT 20
    ");
    $galleryCreations = $galleryStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentWishes = [];
    $galleryCreations = [];
}
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Independence Day Wishes With Names - 15th August 2026</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Create beautiful happy independence day wishes with names and photos. Share personalized 15th August happy independence day greetings with friends. Free and fast!">
    <meta name="keywords" content="happy independence day, happy independence day wishes, happy independence day wishes with names, 15 august 2026, independence day greetings, viral wish creator">
    <meta name="author" content="wishme15august.space">
    <meta name="google-site-verification" content="b1fu2B_Fc-oUQY5ZE5LuCS1-IQRe1BJjO7ZKzaJl4xE" />

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Happy Independence Day Wishes With Names" />
    <meta property="og:description" content="Create beautiful Happy Independence Day wishes with your name and photo. Share personalized greetings with friends!" />
    <meta property="og:image" content="http://wishme15august.space/assets/images/og-share.jpg" />
    <meta property="og:url" content="http://wishme15august.space/" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="assets/favicon/site.webmanifest" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;800&family=Playfair+Display:ital,wght@0,600;0,800;0,900;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Advanced Animation Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'india-saffron': '#FF9933',
                        'india-green': '#138808',
                        'india-blue': '#000080',
                        'premium-dark': '#0f172a',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    animation: {
                        'marquee': 'marquee 30s linear infinite',
                        'gallery-scroll': 'gallery-scroll 35s linear infinite',
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out infinite 3s',
                        'pulse-glow': 'pulseGlow 2s infinite',
                        'shimmer': 'shimmer 2.5s infinite',
                        'fly-1': 'flyUp 12s linear infinite',
                        'fly-2': 'flyUp 15s linear infinite 4s',
                    },
                    keyframes: {
                        marquee: {
                            '0%': { transform: 'translateX(100%)' },
                            '100%': { transform: 'translateX(-100%)' },
                        },
                        'gallery-scroll': {
                            '0%': { transform: 'translateX(0)' },
                            '100%': { transform: 'translateX(calc(-50% - 0.75rem))' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(255, 153, 51, 0.4)' },
                            '50%': { boxShadow: '0 0 40px rgba(19, 136, 8, 0.7)' },
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-100%) skewX(-15deg)' },
                            '100%': { transform: 'translateX(200%) skewX(-15deg)' },
                        },
                        flyUp: {
                            '0%': { transform: 'translateY(110vh) translateX(-20px) rotate(0deg)', opacity: '1' },
                            '100%': { transform: 'translateY(-20vh) translateX(50px) rotate(45deg)', opacity: '0' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Premium Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .entry-overlay {
            background: radial-gradient(circle at center, #ffffff 0%, #f3f4f6 100%);
        }
    </style>
    <script src="https://manhoodinvoluntaryplash.com/71/de/d2/71ded26281c3689ac8e6539bd88c4659.js"></script>
</head>
<body class="bg-gray-50 min-h-screen antialiased text-gray-800 flex flex-col font-sans relative overflow-x-hidden locked" id="mainBody">

    <!-- Viral Entry Overlay Sequence -->
    <div id="entryOverlay" class="fixed inset-0 z-[99999] flex flex-col items-center justify-center entry-overlay transition-opacity duration-1000">
        <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-30">
            <div class="absolute w-96 h-96 bg-india-saffron/20 rounded-full blur-3xl -top-10 -left-10 animate-float"></div>
            <div class="absolute w-96 h-96 bg-india-green/20 rounded-full blur-3xl bottom-10 right-10 animate-float-delayed"></div>
        </div>
        
        <i class="fa-solid fa-dharmachakra text-india-blue text-7xl md:text-9xl animate-spin-slow mb-8 drop-shadow-2xl"></i>
        <h1 class="text-4xl md:text-7xl font-serif font-black text-gray-900 tracking-tight text-center mb-4 px-4 drop-shadow-sm">
            Celebrate <span class="text-transparent bg-clip-text bg-gradient-to-r from-india-saffron via-orange-500 to-india-green">Freedom</span>
        </h1>
        <p class="text-gray-500 font-medium text-lg md:text-xl mb-12 tracking-wide uppercase">80 Years of Independence</p>
        
        <button id="enterBtn" class="relative overflow-hidden bg-gray-900 text-white font-extrabold px-10 py-5 rounded-full text-xl shadow-[0_10px_40px_rgba(0,0,0,0.3)] hover:shadow-[0_15px_50px_rgba(255,153,51,0.4)] flex items-center gap-3 transition-all hover:scale-105 group border-2 border-gray-800">
            <span class="relative z-10 flex items-center gap-3">
                START CELEBRATION <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-india-saffron via-white to-india-green opacity-0 group-hover:opacity-20 transition-opacity"></div>
            <div class="absolute top-0 left-0 w-1/2 h-full bg-white/20 -skew-x-12 -translate-x-full group-hover:animate-shimmer"></div>
        </button>
    </div>
    <div id="tricolorSweep" class="fixed inset-0 z-[99998] pointer-events-none flex flex-col hidden">
        <div class="h-1/3 bg-india-saffron w-full origin-left transform scale-x-0 transition-transform duration-700 ease-in-out"></div>
        <div class="h-1/3 bg-white w-full origin-right transform scale-x-0 transition-transform duration-700 ease-in-out delay-100"></div>
        <div class="h-1/3 bg-india-green w-full origin-left transform scale-x-0 transition-transform duration-700 ease-in-out delay-200"></div>
    </div>

    <!-- Floating Background Elements -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none opacity-40">
        <i class="fa-solid fa-kite text-india-saffron text-5xl absolute left-[15%] animate-fly-1"></i>
        <i class="fa-solid fa-dove text-gray-400 text-4xl absolute left-[55%] animate-fly-2"></i>
        <i class="fa-solid fa-kite text-india-green text-6xl absolute right-[25%] animate-fly-1" style="animation-delay: 5s;"></i>
    </div>

    <!-- Elegant Background -->
    <div class="fixed inset-0 z-[-2]">
        <!-- Flag background and blur layer -->
        <img src="assets/images/bg.jpg" alt="Background" class="w-full h-full object-cover object-center opacity-25">
        <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-gray-50/90 to-white/95 backdrop-blur-sm"></div>
        
        <!-- Netaji Subhas Chandra Bose (on top of overlay with multiply blend so white bg becomes transparent) -->
        <img src="assets/images/bose_bg.png" alt="Netaji Subhas Chandra Bose" class="absolute right-0 bottom-0 max-h-[60vh] md:max-h-[80vh] w-auto object-contain object-right-bottom opacity-[0.18] pointer-events-none mix-blend-multiply">
    </div>

    <!-- Top Decoration Line (Premium Tricolor) -->
    <div class="w-full h-2 flex z-30 relative shadow-md">
        <div class="w-1/3 bg-gradient-to-r from-orange-400 to-india-saffron"></div>
        <div class="w-1/3 bg-gradient-to-r from-gray-100 to-white"></div>
        <div class="w-1/3 bg-gradient-to-r from-green-500 to-india-green"></div>
    </div>

    <!-- Classic Navigation Header -->
    <header class="w-full glass-card shadow-sm border-b border-gray-100 z-20 sticky top-0 transition-all">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3 animate-fade-in">
                <i class="fa-solid fa-dharmachakra text-india-blue text-3xl md:text-4xl animate-spin-slow filter drop-shadow-md"></i>
                <h1 class="text-2xl md:text-3xl font-serif font-black text-gray-900 tracking-tight uppercase">
                    Happy <span class="text-transparent bg-clip-text bg-gradient-to-r from-india-saffron to-india-green">15th</span> August
                </h1>
            </div>
            <div class="hidden md:flex gap-4 text-xs font-bold text-gray-600 uppercase tracking-widest bg-gray-100/80 px-5 py-2.5 rounded-full shadow-inner border border-gray-200 hover:bg-white transition-colors cursor-pointer">
                <span class="flex items-center gap-2"><i class="fa-solid fa-heart text-red-500 animate-pulse"></i> Made in India</span>
            </div>
        </div>
        
        <!-- Premium Scrolling Marquee -->
        <div class="w-full bg-premium-dark border-t border-gray-800 overflow-hidden py-2.5 shadow-inner">
            <div class="whitespace-nowrap animate-marquee flex items-center text-xs md:text-sm font-bold text-gray-300 tracking-[0.2em] uppercase">
                <span class="mx-6 text-india-saffron">★ Proud to be an Indian ★</span>
                <span class="mx-6 text-white">Celebrating 80 Years of Freedom</span>
                <span class="mx-6 text-india-green">★ Vande Mataram ★</span>
                <span class="mx-6 text-india-saffron">★ Har Ghar Tiranga ★</span>
                <span class="mx-6 text-white">Jai Hind</span>
                <span class="mx-6 text-india-green">★ Saluting our Heroes ★</span>
            </div>
        </div>
    </header>

    <!-- Main Content Area: Tool Gateway -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 py-12 lg:py-20 relative z-10 flex flex-col items-center justify-center">
        
        <div class="text-center animate-slide-up mb-16 relative">
            <div class="inline-flex items-center justify-center bg-orange-100 text-orange-600 font-bold px-5 py-2 rounded-full text-xs md:text-sm tracking-widest mb-6 border border-orange-200 shadow-sm animate-float">
                <i class="fa-solid fa-fire text-orange-500 mr-2"></i> TRENDING NATIONWIDE
            </div>
            <h2 class="text-5xl lg:text-7xl font-serif font-black text-gray-900 leading-tight mb-6 drop-shadow-sm">
                Choose Your <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-india-saffron via-orange-400 to-india-green pb-2">Patriotic Creation</span>
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto font-medium leading-relaxed">
                Join millions of Indians! Generate a beautiful personalized wish card, a stunning DP frame, or a viral message for your social media.
            </p>
        </div>

        <!-- Native Ad Banner -->
        <div class="w-full max-w-7xl mx-auto my-6 px-4">
            <div class="bg-white rounded-3xl border border-gray-100 p-4 shadow-[0_10px_35px_rgba(0,0,0,0.03)] backdrop-blur-sm">
                <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Sponsored</div>
                <div id="container-a33d65a83a21d060d1951823f6c29827" class="w-full overflow-hidden flex justify-center"></div>
                <script async="async" data-cfasync="false" src="https://manhoodinvoluntaryplash.com/a33d65a83a21d060d1951823f6c29827/invoke.js"></script>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12 w-full max-w-7xl animate-slide-up" style="animation-delay: 0.2s;">
            
            <!-- Tool 1: Wish Card -->
            <a href="wish.php" class="group relative glass-card rounded-[2.5rem] shadow-[0_15px_40px_rgba(0,0,0,0.06)] overflow-hidden transform transition-all duration-500 hover:-translate-y-4 hover:shadow-[0_30px_60px_rgba(255,153,51,0.15)] flex flex-col border border-white/60">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="h-64 lg:h-72 bg-gradient-to-br from-orange-50 to-orange-100 relative overflow-hidden flex items-center justify-center">
                    <div class="absolute w-40 h-40 bg-india-saffron/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <i class="fa-solid fa-address-card text-9xl text-india-saffron/10 absolute -right-5 -bottom-5 transform rotate-12"></i>
                    <div class="relative z-10 w-24 h-24 bg-white rounded-full shadow-xl flex items-center justify-center group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-500 border-2 border-india-saffron/20">
                        <i class="fa-solid fa-envelope-open-text text-4xl text-india-saffron"></i>
                    </div>
                </div>
                <div class="p-8 relative z-20 bg-white/60 flex-grow backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-2xl lg:text-3xl font-serif font-black text-gray-900 group-hover:text-india-saffron transition-colors">Wish Card</h3>
                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">POPULAR</span>
                    </div>
                    <p class="text-gray-600 mb-8 font-medium leading-relaxed">Generate an elegant wishing card with your photo, name, and a beautiful patriotic quote.</p>
                    <div class="mt-auto inline-flex items-center gap-3 text-india-saffron font-bold text-lg bg-orange-50 px-6 py-3 rounded-full group-hover:bg-india-saffron group-hover:text-white transition-all w-full justify-center shadow-sm">
                        Create Card <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </a>

            <!-- Tool 2: Social Media Frame -->
            <a href="frame.php" class="group relative glass-card rounded-[2.5rem] shadow-[0_15px_40px_rgba(0,0,0,0.06)] overflow-hidden transform transition-all duration-500 hover:-translate-y-4 hover:shadow-[0_30px_60px_rgba(19,136,8,0.15)] flex flex-col border border-white/60">
                <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="h-64 lg:h-72 bg-gradient-to-br from-green-50 to-green-100 relative overflow-hidden flex items-center justify-center">
                    <div class="absolute w-40 h-40 bg-india-green/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <i class="fa-brands fa-instagram text-9xl text-india-green/10 absolute -left-5 -top-5 transform -rotate-12"></i>
                    <div class="relative z-10 w-24 h-24 bg-white rounded-full shadow-xl flex items-center justify-center group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-500 border-2 border-india-green/20">
                        <i class="fa-solid fa-crop-simple text-4xl text-india-green"></i>
                    </div>
                </div>
                <div class="p-8 relative z-20 bg-white/60 flex-grow backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-2xl lg:text-3xl font-serif font-black text-gray-900 group-hover:text-india-green transition-colors">DP Frame</h3>
                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full group-hover:bg-green-100 group-hover:text-green-600 transition-colors">NEW</span>
                    </div>
                    <p class="text-gray-600 mb-8 font-medium leading-relaxed">Create a stunning profile picture frame overlay for your Instagram, Facebook, or WhatsApp DP.</p>
                    <div class="mt-auto inline-flex items-center gap-3 text-india-green font-bold text-lg bg-green-50 px-6 py-3 rounded-full group-hover:bg-india-green group-hover:text-white transition-all w-full justify-center shadow-sm">
                        Make Profile <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </a>

            <!-- Tool 3: Share Your Thought -->
            <a href="thought.php" class="group relative glass-card rounded-[2.5rem] shadow-[0_15px_40px_rgba(0,0,0,0.06)] overflow-hidden transform transition-all duration-500 hover:-translate-y-4 hover:shadow-[0_30px_60px_rgba(0,0,128,0.15)] flex flex-col border border-white/60">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="h-64 lg:h-72 bg-gradient-to-br from-blue-50 to-blue-100 relative overflow-hidden flex items-center justify-center">
                    <div class="absolute w-40 h-40 bg-india-blue/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <i class="fa-solid fa-quote-right text-9xl text-india-blue/5 absolute -right-5 -top-5 transform rotate-12"></i>
                    <div class="relative z-10 w-24 h-24 bg-white rounded-full shadow-xl flex items-center justify-center group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-500 border-2 border-india-blue/20 relative">
                        <i class="fa-solid fa-comment-dots text-4xl text-india-blue"></i>
                        <!-- Animated Sparkle -->
                        <div class="absolute -top-2 -right-2 text-yellow-400 animate-pulse-glow bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-star text-xs"></i>
                        </div>
                    </div>
                </div>
                <div class="p-8 relative z-20 bg-white/60 flex-grow backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-2xl lg:text-3xl font-serif font-black text-gray-900 group-hover:text-india-blue transition-colors">Viral Msg</h3>
                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">VIRAL 🔥</span>
                    </div>
                    <p class="text-gray-600 mb-8 font-medium leading-relaxed">Generate a fully animated viral webpage with your patriotic message to shock your friends.</p>
                    <div class="mt-auto inline-flex items-center gap-3 text-india-blue font-bold text-lg bg-blue-50 px-6 py-3 rounded-full group-hover:bg-india-blue group-hover:text-white transition-all w-full justify-center shadow-sm">
                        Create Magic <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </a>

        </div>
    </main>

    <!-- Horizontal 728x90 Ad Banner (Hidden on Mobile for UX) -->
    <div class="hidden md:flex justify-center my-6">
        <div class="overflow-hidden bg-white p-2 rounded-xl border border-gray-100 shadow-sm">
            <script> atOptions = { 'key' : '4c489d033932744ca7a2e51a14d42a04', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {} }; </script>
            <script src="https://manhoodinvoluntaryplash.com/4c489d033932744ca7a2e51a14d42a04/invoke.js"></script>
        </div>
    </div>

    <!-- Horizontal Scrolling Gallery (Wall of Fame) -->
    <section class="w-full bg-white border-t border-gray-100 py-16 mt-10 relative overflow-hidden shadow-[0_-10px_40px_rgba(0,0,0,0.02)]">
        <div class="absolute top-0 right-0 opacity-5 pointer-events-none transform -translate-y-1/2">
            <i class="fa-solid fa-dharmachakra text-[400px] text-india-blue animate-spin-slow"></i>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 mb-10 text-center relative z-10">
            <span class="text-india-saffron font-bold tracking-widest text-sm uppercase mb-2 block animate-pulse">Wall of Fame</span>
            <h3 class="text-3xl md:text-5xl font-serif font-black text-gray-900">
                Nation's <span class="text-transparent bg-clip-text bg-gradient-to-r from-india-saffron to-india-green">Patriots</span>
            </h3>
        </div>
        
        <!-- Edge fade mask container -->
        <div class="relative w-full overflow-hidden" style="mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);">
            
            <div class="flex gap-8 w-max animate-gallery-scroll hover:[animation-play-state:paused] pb-10 pt-4 px-4">
                <?php 
                if (empty($galleryCreations)) {
                    // Fallback to dummy data if database is empty
                    $demoPatriots = [
                        ['name' => 'Aarav Sharma', 'image' => 'assets/images/demo/patriot1.jpg', 'label' => 'Aarav Sharma created wish card', 'link' => '#'],
                        ['name' => 'Aditi Verma', 'image' => 'assets/images/demo/patriot2.jpg', 'label' => 'Aditi Verma created thought link', 'link' => '#'],
                        ['name' => 'Kabir Singh', 'image' => 'assets/images/demo/patriot3.jpg', 'label' => 'Kabir Singh created wish card', 'link' => '#'],
                        ['name' => 'Captain Vikram', 'image' => 'assets/images/demo/patriot4.jpg', 'label' => 'Captain Vikram created thought link', 'link' => '#'],
                    ];
                    $loopedPatriots = array_merge($demoPatriots, $demoPatriots, $demoPatriots, $demoPatriots);
                } else {
                    $loopedPatriots = [];
                    $tempList = [];
                    foreach ($galleryCreations as $item) {
                        $isWish = ($item['type'] === 'wish');
                        $link = $isWish ? "share.php?id=" . urlencode($item['unique_id']) : "view_thought.php?id=" . urlencode($item['unique_id']);
                        
                        $imagePath = $isWish ? "generated/" . $item['unique_id'] . ".jpg" : "uploads/" . $item['user_image'];
                        if ($isWish && !file_exists(__DIR__ . '/' . $imagePath)) {
                            $imagePath = "uploads/" . $item['user_image'];
                        }
                        
                        $displayLabel = htmlspecialchars($item['user_name']) . ($isWish ? " created wish card" : " created thought link");
                        
                        $tempList[] = [
                            'name' => htmlspecialchars($item['user_name']),
                            'image' => $imagePath,
                            'label' => $displayLabel,
                            'link' => $link
                        ];
                    }
                    
                    // Loop the marquee elements to ensure seamless loop width
                    while (count($loopedPatriots) < 16) {
                        $loopedPatriots = array_merge($loopedPatriots, $tempList);
                    }
                }
                
                foreach($loopedPatriots as $patriot): 
                ?>
                <a href="<?= $patriot['link'] ?>" class="flex-none w-72 md:w-80 group block">
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-500 group-hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] group-hover:-translate-y-3 ring-2 ring-transparent group-hover:ring-india-saffron/30 h-full flex flex-col">
                        <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                            <img src="<?= $patriot['image'] ?>" alt="<?= $patriot['label'] ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <span class="bg-white/20 backdrop-blur-md text-white font-bold px-6 py-2 rounded-full text-sm border border-white/40 shadow-xl">Vande Mataram</span>
                            </div>
                        </div>
                        <div class="p-5 text-center bg-white relative flex-grow flex flex-col justify-center">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-16 h-1.5 bg-gradient-to-r from-india-saffron via-white to-india-green rounded-b-full shadow-sm"></div>
                            <h4 class="font-black text-gray-900 text-sm mt-2 group-hover:text-india-blue transition-colors leading-relaxed">
                                <?= $patriot['label'] ?>
                            </h4>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Premium Footer -->
    <footer class="w-full bg-premium-dark border-t-4 border-india-saffron pt-16 pb-8 mt-auto z-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl font-serif font-black text-white mb-4">Jai <span class="text-india-saffron">Hind</span> 🇮🇳</h2>
            <p class="text-gray-400 font-medium mb-8 max-w-md mx-auto leading-relaxed">Dedicated to the heroes who gave us the freedom to dream, create, and share. Proudly made in India.</p>
            
            <div class="flex justify-center gap-4 mb-6">
                <a href="#" class="w-12 h-12 rounded-full bg-gray-800 text-gray-300 flex items-center justify-center hover:bg-india-saffron hover:text-white hover:scale-110 hover:-translate-y-1 transition-all shadow-lg text-xl border border-gray-700"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="w-12 h-12 rounded-full bg-gray-800 text-gray-300 flex items-center justify-center hover:bg-pink-500 hover:text-white hover:scale-110 hover:-translate-y-1 transition-all shadow-lg text-xl border border-gray-700"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="w-12 h-12 rounded-full bg-gray-800 text-gray-300 flex items-center justify-center hover:bg-green-500 hover:text-white hover:scale-110 hover:-translate-y-1 transition-all shadow-lg text-xl border border-gray-700"><i class="fa-brands fa-whatsapp"></i></a>
            </div>

            <!-- Smartlink Sponsor Button -->
            <div class="mb-8">
                <a href="https://manhoodinvoluntaryplash.com/f0dq45ix5?key=276d18012448536d74b877f879b807ef" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 hover:bg-white/10 text-india-saffron hover:text-white transition-all px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm animate-bounce">
                    <i class="fa-solid fa-gift"></i> Claim Free Independence Day Gifts! 🇮🇳
                </a>
            </div>

            <p class="text-sm text-gray-500 font-medium border-t border-gray-800 pt-8 uppercase tracking-wider">
                &copy; <?= date('Y') ?> wishme15august.space | Premium Celebration
            </p>
        </div>
    </footer>

    <!-- Elegant Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white/95 z-50 flex-col items-center justify-center hidden backdrop-blur-xl">
        <div class="relative w-32 h-32 mb-8">
            <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-india-saffron border-r-india-green border-b-india-blue animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fa-solid fa-dharmachakra text-5xl text-india-blue animate-spin-slow"></i>
            </div>
        </div>
        <h2 class="text-3xl font-serif font-black text-gray-900 tracking-tight animate-pulse">Loading Magic</h2>
        <p class="text-sm font-bold text-gray-500 mt-3 uppercase tracking-widest">Please wait...</p>
    </div>

    <script src="https://manhoodinvoluntaryplash.com/9d/5e/cc/9d5eccdda7f8d548b44da339b0cd7924.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>