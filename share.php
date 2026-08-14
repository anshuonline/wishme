<?php
require_once 'db.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM wishes WHERE unique_id = :unique_id");
    $stmt->execute([':unique_id' => $id]);
    $wish = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$wish) {
        die("Wish not found! Create your own at wishme15august.space");
    }

    if (isset($wish['is_private']) && $wish['is_private'] == 1) {
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
                <p class="text-gray-400 text-sm mb-6">This wish card has been set to private by the administrator.</p>
                <a href="index.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-green-600 text-white font-bold px-6 py-3 rounded-xl hover:scale-105 transition-transform text-sm">
                    Create Your Own Card 🇮🇳
                </a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
} catch (PDOException $e) {
    die("Error retrieving wish.");
}

$pageUrl = "https://wishme15august.space/" . $id;
$imageUrl = "https://wishme15august.space/generated/" . $id . ".jpg";
$title = "Happy Independence Day from " . htmlspecialchars($wish['user_name']);
$description = htmlspecialchars($wish['message']);
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
    <title><?= $title ?></title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= $userName ?> wishes you Happy Independence Day!" />
    <meta property="og:description" content="<?= htmlspecialchars(substr($wish['message'], 0, 100)) ?>..." />
    <meta property="og:image" content="<?= "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/generated/" . $id . ".jpg" ?>" />
    <meta property="og:url" content="<?= $pageUrl ?>" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= $userName ?> has sent you happy independence day wishes with names and photos. View their personalized 15th August message now!">
    <meta name="keywords" content="happy independence day, happy independence day wishes, happy independence day wishes with names, 15 august 2026, personalized independence day card">
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
                        'premium-dark': '#020617',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Cinzel', 'serif'],
                    },
                    animation: {
                        'spin-slow': 'spin 8s linear infinite',
                        'pulse-glow': 'pulseGlow 2.5s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'shimmer': 'shimmer 2.5s infinite',
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(255, 153, 51, 0.4)' },
                            '50%': { boxShadow: '0 0 50px rgba(19, 136, 8, 0.6)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-100%) skewX(-15deg)' },
                            '100%': { transform: 'translateX(200%) skewX(-15deg)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #020617; /* Slate 950 */
            color: #f8fafc;
            overflow-x: hidden;
        }

        /* Cinematic Background Orbs */
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
            animation: float 20s infinite ease-in-out alternate;
        }
        .orb-saffron { background: #FF9933; width: 60vw; height: 60vw; top: -10%; left: -10%; }
        .orb-green { background: #138808; width: 60vw; height: 60vw; bottom: -10%; right: -10%; animation-delay: -10s; }

        /* Premium Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-left: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(255,255,255,0.02);
        }

        /* Frame Card Selection */
        .frame-option {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            filter: grayscale(40%) brightness(0.8);
        }
        input:checked + .frame-option {
            filter: grayscale(0%) brightness(1.1);
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 25px rgba(19, 136, 8, 0.4);
            border-color: #138808;
        }

        /* Floating CTA Button */
        .viral-cta {
            background: linear-gradient(90deg, #FF9933, #e67e22, #138808);
            background-size: 200% auto;
            animation: gradientMove 3s linear infinite, pulseGlow 2s infinite;
        }
        @keyframes gradientMove {
            to { background-position: 200% center; }
        }

        /* 3D Image Hover */
        .tilt-image {
            transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.5s;
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        .tilt-image:hover {
            transform: translateY(-10px) scale(1.02) rotateX(2deg) rotateY(-2deg);
            box-shadow: 20px 30px 50px rgba(0,0,0,0.5), 0 0 30px rgba(255,153,51,0.3);
        }
    </style>
    <script src="https://manhoodinvoluntaryplash.com/71/de/d2/71ded26281c3689ac8e6539bd88c4659.js"></script>
</head>
<body class="min-h-screen flex flex-col antialiased font-sans relative">

    <!-- Background Elements -->
    <div class="fixed inset-0 z-[-2] bg-[#020617]">
        <img src="assets/images/bg.jpg" alt="Background" class="w-full h-full object-cover object-center opacity-10 mix-blend-overlay">
    </div>
    <div class="bg-orb orb-saffron"></div>
    <div class="bg-orb orb-green"></div>

    <!-- Top Decoration Line -->
    <div class="w-full h-1.5 flex z-30 relative shadow-[0_0_15px_rgba(255,255,255,0.3)]">
        <div class="w-1/3 bg-india-saffron"></div>
        <div class="w-1/3 bg-white"></div>
        <div class="w-1/3 bg-india-green"></div>
    </div>

    <!-- Glass Header -->
    <header class="w-full bg-white/5 backdrop-blur-xl shadow-sm border-b border-white/10 py-4 px-4 sm:px-6 lg:px-8 z-20 sticky top-0 transition-all" id="header">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="index.php" class="inline-flex items-center gap-3 group">
                <i class="fa-solid fa-dharmachakra text-india-saffron text-2xl animate-spin-slow group-hover:text-white transition-colors"></i>
                <h1 class="text-2xl font-serif font-black text-white tracking-widest uppercase text-shadow-sm">
                    Wish<span class="text-india-green">Me</span>
                </h1>
            </a>
            <a href="index.php" class="text-xs font-bold text-gray-300 hover:text-white transition-colors flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full border border-white/20 hover:bg-white/20">
                <i class="fa-solid fa-wand-magic-sparkles text-india-saffron"></i> <span class="hidden sm:inline">Create Yours</span>
            </a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow flex flex-col items-center justify-start p-4 pt-10 z-10 w-full max-w-5xl mx-auto relative" id="main-container">
        
        <!-- The Glass Card -->
        <div class="w-full glass-card rounded-[2.5rem] p-6 md:p-12 text-center relative overflow-hidden opacity-0 transform translate-y-10" id="wish-card">
            
            <!-- Animated Corner Accents -->
            <div class="absolute -top-16 -left-16 w-32 h-32 bg-india-saffron/20 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-16 -right-16 w-32 h-32 bg-india-green/20 rounded-full blur-2xl"></div>

            <!-- Greeting Badge -->
            <div class="inline-flex items-center justify-center gap-2 mb-8 px-5 py-2 rounded-full bg-white/5 border border-white/10 shadow-sm backdrop-blur-md">
                <i class="fa-solid fa-gift text-india-saffron animate-bounce"></i>
                <span class="text-xs font-bold tracking-[0.2em] uppercase text-gray-300">A Special Wish For You</span>
                <i class="fa-solid fa-star text-india-green animate-pulse"></i>
            </div>

            <!-- Profile Section -->
            <div class="relative inline-block mb-6 group">
                <div class="absolute -inset-2 bg-gradient-to-tr from-india-green via-white to-india-saffron rounded-full animate-spin-slow opacity-70 group-hover:opacity-100 transition-opacity blur-sm"></div>
                <div class="absolute -inset-1 bg-gradient-to-tr from-india-green via-white to-india-saffron rounded-full animate-spin-slow opacity-100"></div>
                
                <div class="relative w-28 h-28 md:w-36 md:h-36 rounded-full bg-[#020617] p-1.5 overflow-hidden shadow-2xl z-10">
                    <img src="uploads/<?= htmlspecialchars($wish['user_image']) ?>" alt="User Profile" class="w-full h-full object-cover rounded-full border border-white/10">
                </div>
                
                <div class="absolute bottom-0 right-0 md:bottom-2 md:right-2 w-10 h-10 bg-white rounded-full p-[2px] shadow-[0_0_15px_rgba(255,255,255,0.5)] z-20 flex items-center justify-center transform group-hover:scale-125 transition-transform duration-500">
                    <img src="https://upload.wikimedia.org/wikipedia/en/4/41/Flag_of_India.svg" alt="Indian Flag" class="w-full h-full object-cover rounded-full">
                </div>
            </div>

            <!-- Name and Message -->
            <h2 class="text-4xl md:text-6xl font-serif font-black text-transparent bg-clip-text bg-gradient-to-r from-india-saffron via-white to-india-green mb-4 drop-shadow-lg" style="line-height: 1.2;">
                <?= htmlspecialchars($wish['user_name']) ?>
            </h2>
            
            <div class="relative max-w-2xl mx-auto mb-12">
                <i class="fa-solid fa-quote-left text-4xl text-white/10 absolute -top-4 -left-4"></i>
                <p class="text-lg md:text-xl text-gray-300 font-medium italic relative z-10 leading-relaxed px-6">
                    "<?= htmlspecialchars($wish['message']) ?>"
                </p>
                <i class="fa-solid fa-quote-right text-4xl text-white/10 absolute -bottom-4 -right-4"></i>
            </div>

            <!-- The Generated Image (Masterpiece) -->
            <div class="mb-14 rounded-2xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.6)] border border-white/20 relative mx-auto max-w-2xl tilt-image group">
                <!-- Glowing Backdrop for Image -->
                <div class="absolute inset-0 bg-gradient-to-r from-india-saffron/20 to-india-green/20 mix-blend-overlay pointer-events-none"></div>
                
                <!-- Download Overlay -->
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 z-10 flex flex-col items-center justify-center">
                    <a href="generated/<?= $id ?>.jpg" download class="bg-white text-gray-900 px-8 py-4 rounded-full font-black text-lg shadow-[0_10px_30px_rgba(255,255,255,0.3)] hover:scale-110 transition-transform flex items-center gap-3">
                        <i class="fa-solid fa-download text-india-blue"></i> SAVE HD IMAGE
                    </a>
                </div>
                <img src="generated/<?= $id ?>.jpg" alt="Independence Day Wish" class="w-full h-auto object-cover relative z-0">
            </div>

            <!-- Native Ad Banner -->
            <div class="w-full max-w-2xl mx-auto my-8 px-4">
                <div class="bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 p-4 shadow-lg">
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 text-center">Sponsored Content</div>
                    <div id="container-a33d65a83a21d060d1951823f6c29827" class="w-full overflow-hidden flex justify-center"></div>
                    <script async="async" data-cfasync="false" src="https://manhoodinvoluntaryplash.com/a33d65a83a21d060d1951823f6c29827/invoke.js"></script>
                </div>
            </div>

            <!-- Change Frame Section -->
            <div class="bg-black/20 rounded-[2rem] border border-white/10 p-6 md:p-8 mb-12 max-w-3xl mx-auto backdrop-blur-md">
                <h3 class="text-xl font-serif font-bold text-white mb-2 flex items-center justify-center gap-3">
                    <i class="fa-solid fa-wand-magic-sparkles text-india-saffron"></i> Change Design Frame
                </h3>
                <p class="text-sm text-gray-400 mb-8">Tap on a frame to instantly switch the design.</p>
                
                <form action="generate.php" method="POST" id="changeFrameForm">
                    <input type="hidden" name="action" value="change_frame">
                    <input type="hidden" name="unique_id" value="<?= $id ?>">
                    
                    <div class="flex flex-wrap justify-center gap-4 mb-2">
                        <!-- Frame 1 -->
                        <label class="cursor-pointer group relative w-24 md:w-32">
                            <input type="radio" name="frame_template" value="frame1.jpg" class="peer sr-only" checked onchange="submitFrameChange()">
                            <div class="frame-option rounded-xl border-2 border-transparent overflow-hidden shadow-lg bg-gray-900">
                                <img src="templates/frame1.jpg" class="w-full aspect-[3/4] object-cover">
                            </div>
                            <div class="absolute -top-2 -right-2 bg-india-green text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity shadow-md z-10 border border-white">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                        </label>
                        
                        <!-- Frame 2 -->
                        <label class="cursor-pointer group relative w-24 md:w-32">
                            <input type="radio" name="frame_template" value="frame2.jpg" class="peer sr-only" onchange="submitFrameChange()">
                            <div class="frame-option rounded-xl border-2 border-transparent overflow-hidden shadow-lg bg-gray-900">
                                <img src="templates/frame2.jpg" class="w-full aspect-[3/4] object-cover">
                            </div>
                            <div class="absolute -top-2 -right-2 bg-india-green text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity shadow-md z-10 border border-white">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                        </label>

                        <!-- Frame 3 -->
                        <label class="cursor-pointer group relative w-24 md:w-32">
                            <input type="radio" name="frame_template" value="frame3.jpg" class="peer sr-only" onchange="submitFrameChange()">
                            <div class="frame-option rounded-xl border-2 border-transparent overflow-hidden shadow-lg bg-gray-900">
                                <img src="templates/frame3.jpg" class="w-full aspect-[3/4] object-cover">
                            </div>
                            <div class="absolute -top-2 -right-2 bg-india-green text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity shadow-md z-10 border border-white">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                        </label>
                    </div>
                </form>
            </div>

            <!-- Share Buttons -->
            <div class="mb-8">
                <p class="text-sm font-bold tracking-[0.2em] text-gray-400 uppercase mb-6 flex items-center justify-center gap-2">
                    <span class="w-10 h-px bg-gray-600"></span> Share The Joy <span class="w-10 h-px bg-gray-600"></span>
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-xl mx-auto">
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode($title . " \n\nSee my beautiful wish here: " . $pageUrl) ?>" target="_blank" 
                       class="flex-1 flex items-center justify-center gap-3 bg-[#25D366]/20 hover:bg-[#25D366] text-[#25D366] hover:text-white border border-[#25D366]/50 py-4 px-6 rounded-2xl font-bold tracking-wide transition-all shadow-lg transform hover:-translate-y-1 backdrop-blur-sm">
                        <i class="fa-brands fa-whatsapp text-2xl"></i> WhatsApp
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($pageUrl) ?>" target="_blank"
                       class="flex-1 flex items-center justify-center gap-3 bg-[#1877F2]/20 hover:bg-[#1877F2] text-[#1877F2] hover:text-white border border-[#1877F2]/50 py-4 px-6 rounded-2xl font-bold tracking-wide transition-all shadow-lg transform hover:-translate-y-1 backdrop-blur-sm">
                        <i class="fa-brands fa-facebook-f text-2xl"></i> Facebook
                    </a>
                </div>

                <!-- Copy Link Button -->
                <div class="mt-5 flex items-center justify-between gap-2 bg-black/40 rounded-2xl p-2 border border-white/10 transition-all max-w-xl mx-auto backdrop-blur-md">
                    <div class="flex items-center pl-4 text-gray-400">
                        <i class="fa-solid fa-link"></i>
                    </div>
                    <input type="text" id="shareLink" value="<?= $pageUrl ?>" readonly class="bg-transparent w-full text-sm text-gray-300 font-medium outline-none px-3 select-all">
                    <button onclick="copyToClipboard(event)" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-6 py-3 rounded-xl text-sm font-bold transition-all whitespace-nowrap shadow-md">
                        Copy
                    </button>
                </div>
            </div>

        </div>
    </main>

    <!-- Horizontal 728x90 Ad Banner (Hidden on Mobile for UX) -->
    <div class="hidden md:flex justify-center my-6 relative z-30">
        <div class="overflow-hidden bg-white/5 p-2 rounded-xl border border-white/10 shadow-lg">
            <script> atOptions = { 'key' : '4c489d033932744ca7a2e51a14d42a04', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {} }; </script>
            <script src="https://manhoodinvoluntaryplash.com/4c489d033932744ca7a2e51a14d42a04/invoke.js"></script>
        </div>
    </div>

    <!-- Massive Viral CTA (Sticky Bottom Mobile / Fixed Footer) -->
    <div class="w-full mt-10 relative z-30 pb-10 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h3 class="text-xl md:text-2xl font-serif font-bold text-white mb-6 drop-shadow-md">
                Want to surprise your friends too?
            </h3>
            <a href="index.php" class="viral-cta inline-flex items-center justify-center gap-4 text-white py-5 px-10 rounded-full font-black text-lg md:text-xl border-2 border-white/30 shadow-[0_15px_40px_rgba(255,153,51,0.3)] hover:scale-105 transition-transform w-full md:w-auto">
                <i class="fa-solid fa-bolt text-yellow-300 animate-pulse"></i> CREATE YOUR OWN - 100% FREE
            </a>
            <p class="text-gray-400 text-xs mt-4 font-semibold uppercase tracking-widest">🔥 Join 1 Million+ Indians Today</p>
            
            <!-- Smartlink Sponsor Button -->
            <div class="mt-8 text-center">
                <a href="https://manhoodinvoluntaryplash.com/f0dq45ix5?key=276d18012448536d74b877f879b807ef" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 hover:bg-white/10 text-india-saffron hover:text-white transition-all px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm animate-bounce">
                    <i class="fa-solid fa-gift"></i> Claim Free Independence Day Gifts! 🇮🇳
                </a>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 px-6 py-3 rounded-full shadow-2xl transition-all duration-300 opacity-0 translate-y-[-20px] pointer-events-none z-[100] font-bold flex items-center gap-3">
        <i class="fa-solid fa-check-circle text-india-green text-xl"></i> Link Copied!
    </div>

    <!-- Loading Overlay for Frame Change -->
    <div id="loadingOverlay" class="fixed inset-0 bg-[#020617]/95 z-[999] flex-col items-center justify-center hidden backdrop-blur-xl">
        <i class="fa-solid fa-dharmachakra text-5xl text-india-saffron animate-spin-slow mb-6"></i>
        <h2 class="text-2xl font-serif font-bold text-white tracking-widest animate-pulse">Updating Design...</h2>
    </div>

    <script>
        // GSAP Entry Animation & Confetti
        document.addEventListener("DOMContentLoaded", () => {
            
            // Fade in card
            gsap.to("#wish-card", {
                opacity: 1,
                y: 0,
                duration: 1.2,
                ease: "power3.out",
                delay: 0.2
            });

            // Fire Confetti on load
            setTimeout(() => {
                fireConfetti();
            }, 800);
        });

        function fireConfetti() {
            const duration = 3000;
            const end = Date.now() + duration;

            (function frame() {
                confetti({ particleCount: 4, angle: 60, spread: 55, origin: { x: 0, y: 0.7 }, colors: ['#FF9933', '#FFFFFF'], zIndex: 100 });
                confetti({ particleCount: 4, angle: 120, spread: 55, origin: { x: 1, y: 0.7 }, colors: ['#138808', '#FFFFFF'], zIndex: 100 });
                if (Date.now() < end) requestAnimationFrame(frame);
            }());
        }

        // Copy Link function
        function copyToClipboard(event) {
            var copyText = document.getElementById("shareLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value).then(() => {
                const btn = event.currentTarget;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied';
                btn.classList.add('bg-india-green', 'text-white', 'border-india-green');
                btn.classList.remove('bg-white/10');
                
                // Show floating Toast
                const toast = document.getElementById('toast');
                toast.classList.remove('opacity-0', 'translate-y-[-20px]');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-india-green', 'text-white', 'border-india-green');
                    btn.classList.add('bg-white/10');
                    toast.classList.add('opacity-0', 'translate-y-[-20px]');
                }, 2500);
            });
        }

        // Loading state for frame change
        function submitFrameChange() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
            document.getElementById('loadingOverlay').classList.add('flex');
            document.getElementById('changeFrameForm').submit();
        }
    </script>
    <script src="https://manhoodinvoluntaryplash.com/9d/5e/cc/9d5eccdda7f8d548b44da339b0cd7924.js"></script>
</body>
</html>