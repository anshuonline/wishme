<?php
require_once 'db.php';

// Fetch recent wishes for the sidebar
try {
    $stmt = $pdo->query("SELECT unique_id, user_name, created_at FROM wishes ORDER BY created_at DESC LIMIT 8");
    $recentWishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $galleryStmt = $pdo->query("SELECT unique_id, user_name FROM wishes ORDER BY created_at DESC LIMIT 15");
    $galleryWishes = $galleryStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentWishes = [];
    $galleryWishes = [];
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
    <title>Happy Independence Day Wishes With Names - Profile Frames</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Create beautiful happy independence day wishes with names by making a custom 15th August DP frame. Share your patriotic DP on WhatsApp, Facebook, and Instagram.">
    <meta name="keywords" content="happy independence day, happy independence day wishes, happy independence day wishes with names, independence day dp, 15 august frame maker">
    <meta name="author" content="wishme15august.space">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Happy Independence Day Wishes With Names - DP Frame" />
    <meta property="og:description" content="Create a beautiful 15th August profile frame with your photo and share your Happy Independence Day wishes." />
    <meta property="og:image" content="http://wishme15august.space/assets/images/og-share.jpg" />
    <meta property="og:url" content="http://wishme15august.space/frame.php" />
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,800;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- No Cropper.js needed for frame.php anymore -->

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
                        'classic-bg': '#F9FAFB',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    animation: {
                        'marquee': 'marquee 30s linear infinite',
                        'gallery-scroll': 'gallery-scroll 35s linear infinite',
                        'wave-slow': 'wave 8s ease-in-out infinite',
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
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
                        wave: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://manhoodinvoluntaryplash.com/71/de/d2/71ded26281c3689ac8e6539bd88c4659.js"></script>
</head>
<body class="bg-classic-bg min-h-screen antialiased text-gray-800 flex flex-col font-sans relative overflow-x-hidden">

    <!-- Elegant Background -->
    <div class="fixed inset-0 z-[-2]">
        <img src="assets/images/bg.jpg" alt="Background" class="w-full h-full object-cover object-center opacity-30">
        <div class="absolute inset-0 bg-white/85 backdrop-blur-[2px]"></div>
    </div>

    <!-- Header -->
    <header class="w-full py-6 px-4 sm:px-6 lg:px-8 border-b border-gray-100 bg-white/60 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-dharmachakra text-india-blue text-2xl animate-spin-slow"></i>
                <h1 class="text-2xl font-serif font-bold text-gray-900 tracking-tight">WishMe<span class="text-india-saffron">15Aug</span></h1>
            </div>
            <a href="index.php" class="text-sm font-semibold text-gray-600 hover:text-india-blue transition-colors"><i class="fa-solid fa-home"></i> Back to Home</a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 py-10 lg:py-16 grid grid-cols-1 lg:grid-cols-12 gap-10 relative z-10">
        
        <div class="lg:col-span-8 flex flex-col gap-8">
            <div class="text-left animate-slide-up">
                <h2 class="text-4xl lg:text-5xl font-serif font-extrabold text-gray-900 leading-tight mb-4">
                    Create Social <span class="text-transparent bg-clip-text bg-gradient-to-r from-india-green to-india-blue">DP Frame</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl font-light">
                    Select a stunning frame design, upload your photo, and perfectly crop it to generate a patriotic profile picture for your social media.
                </p>
            </div>

            <!-- Native Ad Banner -->
            <div class="w-full my-4">
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-[0_8px_30px_rgb(0,0,0,0.02)] backdrop-blur-sm">
                    <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Sponsored</div>
                    <div id="container-a33d65a83a21d060d1951823f6c29827" class="w-full overflow-hidden flex justify-center"></div>
                    <script async="async" data-cfasync="false" src="https://manhoodinvoluntaryplash.com/a33d65a83a21d060d1951823f6c29827/invoke.js"></script>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 md:p-10 relative overflow-hidden animate-slide-up">
                <form action="generate.php" method="POST" enctype="multipart/form-data" id="frameForm" class="space-y-8 relative z-10">
                    <input type="hidden" name="generation_type" value="frame">

                    <!-- Select Frame -->
                    <div>
                        <label class="block text-sm font-semibold mb-3 text-gray-700">Select Frame Design</label>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                            <!-- Frame 1 -->
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="frame_template" value="frame1.jpg" class="peer sr-only" checked>
                                <div class="rounded-xl border-2 border-gray-200 overflow-hidden peer-checked:border-india-green peer-checked:ring-4 peer-checked:ring-green-100 transition-all">
                                    <img src="templates/frame1.jpg" alt="Frame 1" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="absolute top-2 right-2 bg-india-green text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            </label>
                            
                            <!-- Frame 2 -->
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="frame_template" value="frame2.jpg" class="peer sr-only">
                                <div class="rounded-xl border-2 border-gray-200 overflow-hidden peer-checked:border-india-green peer-checked:ring-4 peer-checked:ring-green-100 transition-all">
                                    <img src="templates/frame2.jpg" alt="Frame 2" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="absolute top-2 right-2 bg-india-green text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            </label>

                            <!-- Frame 3 -->
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="frame_template" value="frame3.jpg" class="peer sr-only">
                                <div class="rounded-xl border-2 border-gray-200 overflow-hidden peer-checked:border-india-green peer-checked:ring-4 peer-checked:ring-green-100 transition-all">
                                    <img src="templates/frame3.jpg" alt="Frame 3" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="absolute top-2 right-2 bg-india-green text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Profile Picture Upload -->
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Your Photo</label>
                        <div class="relative flex flex-col items-center justify-center px-6 py-12 border-2 border-dashed border-gray-300 rounded-2xl hover:border-india-green bg-gray-50 hover:bg-green-50/30 transition-all cursor-pointer group" id="drop-zone">
                            <div class="space-y-4 text-center" id="upload-content">
                                <div class="w-16 h-16 mx-auto bg-white rounded-full shadow-sm border border-gray-100 flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                                    <i class="fa-regular fa-image text-2xl text-india-green"></i>
                                </div>
                                <div>
                                    <label for="user_image" class="relative cursor-pointer text-india-blue font-semibold">
                                        <span>Click to browse</span>
                                        <input id="user_image" name="user_image_raw" type="file" class="sr-only" accept="image/jpeg, image/png, image/webp">
                                        <input type="hidden" name="user_image_cropped" id="user_image_cropped" required>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-400">Max: 2MB (JPG, PNG)</p>
                            </div>

                            <div id="image-preview-container" class="hidden absolute inset-0 w-full h-full p-2 bg-white rounded-2xl flex items-center justify-center z-20">
                                <img id="image-preview" src="" alt="Preview" class="max-h-full max-w-full rounded-xl object-contain shadow-sm border border-gray-100">
                                <button type="button" id="remove-image" class="absolute top-4 right-4 bg-white text-gray-600 rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:text-red-500 transition-all">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                            <label for="user_name" class="block text-sm font-semibold mb-2 text-gray-700">Name on Frame <span class="text-red-500">*</span></label>
                            <input type="text" name="user_name" id="user_name" required placeholder="e.g., Anjali Sharma" 
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 text-base focus:bg-white focus:ring-2 focus:ring-india-green/20 focus:border-india-green transition-all outline-none">
                        </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full relative overflow-hidden bg-gray-900 text-white font-semibold text-lg py-4 rounded-xl shadow-md hover:shadow-lg transform transition-all hover:-translate-y-0.5 focus:outline-none group">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Generate Frame <i class="fa-solid fa-arrow-right text-sm transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Sidebar placeholder -->
        <div class="lg:col-span-4 hidden lg:block">
            <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl border border-green-200 p-8 h-full flex flex-col justify-center items-center text-center">
                <i class="fa-brands fa-instagram text-6xl text-india-green mb-6 opacity-80"></i>
                <h3 class="text-2xl font-serif font-bold text-gray-900 mb-2">Show Your Pride</h3>
                <p class="text-gray-700">Perfect for WhatsApp, Instagram, and Facebook profile pictures!</p>
            </div>
        </div>
    </main>

    <!-- Visual Editor has been removed -->

    <!-- Horizontal Scrolling Gallery -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 py-12 border-t border-gray-200 mt-10 relative">
        <h3 class="text-2xl font-serif font-bold text-gray-900 mb-6 text-center">
            <i class="fa-solid fa-images text-india-saffron"></i> Discover Beautiful Wishes
        </h3>
        
        <!-- Edge fade mask container -->
        <div class="relative w-full overflow-hidden" style="mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);">
            
            <!-- Continuous Marquee Container -->
            <!-- We hover pause it so users can click links -->
            <div class="flex gap-6 w-max animate-gallery-scroll hover:[animation-play-state:paused] pb-4 pt-2">
                <?php 
                // Duplicate items to create a seamless infinite loop
                $loopedWishes = array_merge($galleryWishes, $galleryWishes);
                foreach($loopedWishes as $galleryWish): 
                ?>
                <a href="<?= htmlspecialchars($galleryWish['unique_id']) ?>" class="flex-none w-64 md:w-80 group">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transform transition-all duration-300 group-hover:shadow-lg group-hover:-translate-y-2 group-hover:border-india-saffron">
                        <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                            <img src="generated/<?= htmlspecialchars($galleryWish['unique_id']) ?>.jpg" alt="<?= htmlspecialchars($galleryWish['user_name']) ?>'s Wish" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <div class="p-4 text-center bg-white relative">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-india-saffron via-white to-india-green rounded-b-full"></div>
                            <h4 class="font-semibold text-gray-800 text-sm mt-1 truncate">
                                <?= htmlspecialchars($galleryWish['user_name']) ?>
                            </h4>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Horizontal 728x90 Ad Banner (Hidden on Mobile for UX) -->
    <div class="hidden md:flex justify-center my-6">
        <div class="overflow-hidden bg-white p-2 rounded-xl border border-gray-100 shadow-sm">
            <script> atOptions = { 'key' : '4c489d033932744ca7a2e51a14d42a04', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {} }; </script>
            <script src="https://manhoodinvoluntaryplash.com/4c489d033932744ca7a2e51a14d42a04/invoke.js"></script>
        </div>
    </div>

    <!-- Horizontal 728x90 Ad Banner (Hidden on Mobile for UX) -->
    <div class="hidden md:flex justify-center my-6">
        <div class="overflow-hidden bg-white p-2 rounded-xl border border-gray-100 shadow-sm">
            <script> atOptions = { 'key' : '4c489d033932744ca7a2e51a14d42a04', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {} }; </script>
            <script src="https://manhoodinvoluntaryplash.com/4c489d033932744ca7a2e51a14d42a04/invoke.js"></script>
        </div>
    </div>

    <!-- Classic Footer -->
    <footer class="w-full bg-white border-t border-gray-200 py-8 mt-auto z-20">
        <div class="max-w-7xl mx-auto px-6 text-center flex flex-col items-center gap-4">
            <!-- Smartlink Sponsor Button -->
            <div>
                <a href="https://manhoodinvoluntaryplash.com/f0dq45ix5?key=276d18012448536d74b877f879b807ef" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-india-saffron hover:text-india-green transition-all px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm animate-bounce border border-gray-200">
                    <i class="fa-solid fa-gift"></i> Claim Free Independence Day Gifts! 🇮🇳
                </a>
            </div>
            <p class="text-sm text-gray-500 font-medium">
                Celebrating the spirit of India. &copy; <?= date('Y') ?> wishme15august.space
            </p>
        </div>
    </footer>

    <!-- Elegant Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white/95 z-50 flex-col items-center justify-center hidden backdrop-blur-md">
        <div class="relative w-24 h-24 mb-6">
            <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-india-blue border-t-transparent animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fa-solid fa-dharmachakra text-3xl text-india-blue animate-spin-slow"></i>
            </div>
        </div>
        <h2 class="text-2xl font-serif font-bold text-gray-900 tracking-tight">Crafting Your Wish</h2>
        <p class="text-sm text-gray-500 mt-2">Please wait a moment...</p>
    </div>

    <script src="https://manhoodinvoluntaryplash.com/9d/5e/cc/9d5eccdda7f8d548b44da339b0cd7924.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
