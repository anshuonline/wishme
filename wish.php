<?php
require_once 'db.php';

// Fetch recent wishes for the sidebar
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
    <title>Create Happy Independence Day Wishes With Names - 15th August</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Generate and download happy independence day wishes with names and photos. Wish your family and friends a very happy independence day 2026.">
    <meta name="keywords" content="happy independence day, happy independence day wishes, happy independence day wishes with names, create independence day card, 15 august 2026">
    <meta name="author" content="wishme15august.space">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Create Happy Independence Day Wishes With Names" />
    <meta property="og:description" content="Generate and download happy independence day wishes with names and photos." />
    <meta property="og:image" content="http://wishme15august.space/assets/images/og-share.jpg" />
    <meta property="og:url" content="http://wishme15august.space/wish.php" />
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;800&family=Playfair+Display:ital,wght@0,600;0,800;1,600;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

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
                        'pulse-glow': 'pulseGlow 2s infinite',
                        'fly-1': 'flyUp 12s linear infinite',
                        'fly-2': 'flyUp 15s linear infinite 2s',
                        'fly-3': 'flyUp 18s linear infinite 5s',
                        'float': 'float 6s ease-in-out infinite',
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
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 15px rgba(255, 153, 51, 0.5)' },
                            '50%': { boxShadow: '0 0 30px rgba(19, 136, 8, 0.8)' },
                        },
                        flyUp: {
                            '0%': { transform: 'translateY(110vh) translateX(-20px) rotate(0deg)', opacity: '1' },
                            '100%': { transform: 'translateY(-20vh) translateX(50px) rotate(45deg)', opacity: '0' },
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
    <script src="https://manhoodinvoluntaryplash.com/71/de/d2/71ded26281c3689ac8e6539bd88c4659.js"></script>
</head>
<body class="bg-classic-bg min-h-screen antialiased text-gray-800 flex flex-col font-sans relative overflow-x-hidden">

    <!-- Elegant Background: Subtly visible image -->
    <div class="fixed inset-0 z-[-3]">
        <img src="assets/images/bg.jpg" alt="Background" class="w-full h-full object-cover object-center opacity-20">
        <div class="absolute inset-0 bg-white/90 backdrop-blur-sm"></div>
    </div>

    <!-- Viral Floating Elements (Kites, Balloons, Flags) -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none opacity-40">
        <i class="fa-solid fa-kite text-india-saffron text-5xl absolute left-[10%] animate-fly-1"></i>
        <i class="fa-solid fa-dove text-gray-400 text-4xl absolute left-[50%] animate-fly-2"></i>
        <i class="fa-solid fa-kite text-india-green text-6xl absolute right-[20%] animate-fly-3"></i>
        <i class="fa-solid fa-star text-india-blue text-2xl absolute left-[80%] animate-fly-1" style="animation-delay: 4s;"></i>
    </div>

    <!-- Top Decoration Line (Tricolor) -->
    <div class="w-full h-2 flex z-30 relative shadow-md">
        <div class="w-1/3 bg-india-saffron"></div>
        <div class="w-1/3 bg-white"></div>
        <div class="w-1/3 bg-india-green"></div>
    </div>

    <!-- Classic Navigation Header -->
    <header class="w-full bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 z-20 sticky top-0">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3 animate-fade-in">
                <i class="fa-solid fa-dharmachakra text-india-blue text-4xl animate-spin-slow drop-shadow-md"></i>
                <h1 class="text-2xl md:text-3xl font-serif font-black text-gray-900 tracking-tight uppercase">
                    Happy <span class="text-transparent bg-clip-text bg-gradient-to-r from-india-saffron via-india-blue to-india-green">15th</span> August
                </h1>
            </div>
            <div class="flex gap-4 text-sm font-bold text-gray-600 hover:text-india-blue transition-colors bg-gray-100 px-4 py-2 rounded-full cursor-pointer hover:bg-gray-200 shadow-inner">
                <a href="index.php" class="flex items-center gap-2"><i class="fa-solid fa-home"></i> Home</a>
            </div>
        </div>
        
        <!-- Elegant Scrolling Marquee -->
        <div class="w-full bg-gray-900 overflow-hidden py-2 shadow-inner">
            <div class="whitespace-nowrap animate-marquee flex items-center text-xs md:text-sm font-bold text-white tracking-[0.2em] uppercase">
                <span class="mx-6 text-india-saffron">★ Har Ghar Tiranga ★</span>
                <span class="mx-6 text-white">Proud to be an Indian</span>
                <span class="mx-6 text-india-green">★ Vande Mataram ★</span>
                <span class="mx-6 text-india-saffron">★ Har Ghar Tiranga ★</span>
                <span class="mx-6 text-white">Celebrating 80 Years of Freedom</span>
                <span class="mx-6 text-india-green">★ Jai Hind ★</span>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 py-8 lg:py-12 grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        
        <!-- Left Column: The Generator (Wider) -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            
            <!-- Hero Text with Viral Badge -->
            <div class="text-left animate-slide-up relative">
                <div class="inline-block bg-red-100 text-red-600 font-bold px-4 py-1.5 rounded-full text-xs tracking-wider mb-4 animate-float shadow-sm border border-red-200">
                    <i class="fa-solid fa-fire text-red-500 mr-1"></i> Trending: 1M+ Indians created their wishes!
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-7xl font-serif font-black text-gray-900 leading-tight mb-4 drop-shadow-sm">
                    Show Your <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-india-saffron via-orange-500 to-india-green pb-2">Patriotic Spirit</span>
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl font-medium leading-relaxed">
                    Don't just send a boring text this Independence Day! Upload your photo and generate a stunning, tricolor personalized greeting card in just 5 seconds. 
                </p>
            </div>

            <!-- Native Ad Banner -->
            <div class="w-full my-4">
                <div class="bg-white rounded-3xl border border-gray-100 p-4 shadow-[0_10px_35px_rgba(0,0,0,0.03)] backdrop-blur-sm">
                    <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Sponsored</div>
                    <div id="container-a33d65a83a21d060d1951823f6c29827" class="w-full overflow-hidden flex justify-center"></div>
                    <script async="async" data-cfasync="false" src="https://manhoodinvoluntaryplash.com/a33d65a83a21d060d1951823f6c29827/invoke.js"></script>
                </div>
            </div>

            <!-- Classic Form Card -->
            <div class="bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-gray-100 p-6 md:p-10 relative overflow-hidden animate-slide-up" style="animation-delay: 0.2s;">
                
                <!-- Decorative Corner Element -->
                <div class="absolute -top-16 -right-16 text-india-saffron opacity-10 rotate-45 pointer-events-none">
                    <i class="fa-solid fa-certificate text-[250px]"></i>
                </div>

                <form action="generate.php" method="POST" enctype="multipart/form-data" id="wishForm" class="space-y-6 relative z-10">
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Name Input -->
                        <div class="relative group">
                            <label for="user_name" class="block text-sm font-bold mb-2 text-gray-800 uppercase tracking-wide">Enter Your Name</label>
                            <input type="text" name="user_name" id="user_name" required placeholder="e.g., Virat Kohli" 
                                class="w-full px-5 py-4 rounded-xl bg-gray-50 border-2 border-gray-200 text-gray-900 text-lg font-semibold focus:bg-white focus:ring-4 focus:ring-india-saffron/20 focus:border-india-saffron transition-all outline-none placeholder-gray-400">
                        </div>

                        <!-- Language/Wish Selection -->
                        <div class="relative group">
                            <label class="block text-sm font-bold mb-2 text-gray-800 uppercase tracking-wide" for="language">
                                Select Your Message
                            </label>
                            <div class="relative">
                                <select class="appearance-none border-2 border-gray-200 rounded-xl w-full py-4 px-5 text-gray-800 font-medium bg-gray-50 leading-tight focus:outline-none focus:bg-white focus:border-india-green focus:ring-4 focus:ring-india-green/20 transition duration-300 shadow-sm" 
                                        id="language" name="language" required>
                                    <option value="msg1">🔥 May the tricolor always fly high! Jai Hind!</option>
                                    <option value="msg2">✨ Tiranga hamesha uncha rahe! Happy 15th August!</option>
                                    <option value="msg3">🕊️ Sare jahan se accha Hindustan hamara!</option>
                                    <option value="msg4">❤️ Celebrating the spirit of free India. Vande Mataram!</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-gray-500">
                                    <i class="fa-solid fa-chevron-down text-lg"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Font Style Selection -->
                        <div class="relative group">
                            <label class="block text-sm font-bold mb-2 text-gray-800 uppercase tracking-wide" for="font_style">
                                Select Font Style
                            </label>
                            <div class="relative">
                                <select class="appearance-none border-2 border-gray-200 rounded-xl w-full py-4 px-5 text-gray-800 font-medium bg-gray-50 leading-tight focus:outline-none focus:bg-white focus:border-india-saffron focus:ring-4 focus:ring-india-saffron/20 transition duration-300 shadow-sm" 
                                        id="font_style" name="font_style" required>
                                    <option value="poppins">✍️ Poppins (Clean Modern - Default)</option>
                                    <option value="playfair">🇮🇳 Playfair & Lora (Elegant Patriotic Serif)</option>
                                    <option value="greatvibes">✨ Great Vibes (Beautiful Royal Cursive)</option>
                                    <option value="dancingscript">🎉 Dancing Script (Lively Calligraphy)</option>
                                    <option value="cinzel">🏛️ Cinzel & Lora (Classic Imperial Serif)</option>
                                    <option value="montserrat">🚀 Montserrat (Sleek Geometric Bold)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-gray-500">
                                    <i class="fa-solid fa-chevron-down text-lg"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Picture Upload - Viral Design -->
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-800 uppercase tracking-wide">Add Your Best Photo</label>
                            <div class="relative flex flex-col items-center justify-center px-6 py-12 border-2 border-dashed border-india-saffron/50 rounded-2xl bg-orange-50 hover:bg-orange-100 transition-all cursor-pointer group" id="drop-zone" onclick="document.getElementById('user_image').click()">
                                
                                <div class="space-y-4 text-center" id="upload-content">
                                    <div class="w-20 h-20 mx-auto bg-white rounded-full shadow-lg border-2 border-india-saffron flex items-center justify-center group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300">
                                        <i class="fa-solid fa-camera-retro text-3xl text-india-saffron animate-float"></i>
                                    </div>
                                    <div>
                                        <div class="relative cursor-pointer text-gray-900 font-black text-xl hover:text-india-saffron focus-within:outline-none block">
                                            <span>Click here to upload photo</span>
                                            <input id="user_image" name="user_image_raw" type="file" class="sr-only" accept="image/jpeg, image/png, image/webp" onclick="event.stopPropagation()">
                                            <input type="hidden" name="user_image_cropped" id="user_image_cropped" required>
                                            <input type="hidden" name="generation_type" value="wish">
                                        </div>
                                        <span class="text-sm font-medium text-gray-500 mt-2 block">Make sure your face is visible clearly!</span>
                                    </div>
                                </div>

                                <!-- Preview Image -->
                                <div id="image-preview-container" class="hidden absolute inset-0 w-full h-full p-2 bg-white rounded-2xl flex items-center justify-center z-20">
                                    <img id="image-preview" src="" alt="Preview" class="max-h-full max-w-full rounded-xl object-contain shadow-sm border border-gray-100">
                                    <button type="button" id="remove-image" class="absolute top-4 right-4 bg-red-500 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-red-600 hover:scale-110 transition-all border-2 border-white">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Giant Viral Submit Button -->
                    <button type="submit" id="submitBtn" class="w-full relative overflow-hidden bg-gradient-to-r from-india-saffron via-orange-400 to-india-green text-white font-extrabold text-xl py-5 rounded-2xl shadow-xl hover:shadow-2xl animate-pulse-glow transform transition-all hover:-translate-y-1 focus:outline-none group mt-4">
                        <span class="relative z-10 flex items-center justify-center gap-3">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> CREATE MY WISH NOW 
                        </span>
                        <!-- Shimmer Effect -->
                        <div class="absolute inset-0 -translate-x-full bg-white/30 group-hover:animate-[shimmer_1.5s_infinite] skew-x-12"></div>
                    </button>
                </form>
            </div>
            
            <!-- Engaging Viral Text Content -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mt-2 animate-slide-up" style="animation-delay: 0.3s;">
                <h3 class="text-2xl font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-heart text-india-saffron"></i> Why create a wish?
                </h3>
                <p class="text-gray-600 leading-relaxed font-medium">
                    This Independence Day, let's paint social media with the colors of our Tiranga. By creating and sharing your personalized card, you join millions of Indians in a digital celebration of our 80 years of freedom. Share it on WhatsApp status, Instagram stories, or Facebook and spread the patriotism! 🇮🇳
                </p>
            </div>
        </div>

        <!-- Cropper Modal -->
        <div id="cropperModal" class="fixed inset-0 z-[99999] bg-black/80 hidden flex-col items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl flex flex-col">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-black text-gray-900 text-lg uppercase tracking-wide">Crop Your Photo</h3>
                    <button type="button" id="closeCropperBtn" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-times text-2xl"></i></button>
                </div>
                <div class="p-4 bg-gray-900 flex-grow relative" style="height: 400px;">
                    <img id="cropperImage" src="" class="max-w-full block absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                </div>
                <div class="p-5 border-t border-gray-100 flex justify-end gap-4 bg-gray-50">
                    <button type="button" id="cancelCropBtn" class="px-6 py-2.5 rounded-xl font-bold text-gray-600 bg-gray-200 hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="button" id="applyCropBtn" class="px-8 py-2.5 rounded-xl font-bold text-white bg-gradient-to-r from-india-saffron to-india-green hover:shadow-lg transition-all transform hover:-translate-y-0.5">Apply Crop</button>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="lg:col-span-4 flex flex-col gap-6 animate-slide-up" style="animation-delay: 0.4s;">
            
            <!-- Live Feed / Recent Wishes -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-india-green/10 rounded-bl-full"></div>
                
                <h3 class="text-xl font-serif font-black text-gray-900 border-b-2 border-gray-100 pb-4 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-500 animate-pulse"></i> Live Wishes
                </h3>
                
                <div class="flex flex-col gap-3">
                    <?php if(empty($recentWishes)): ?>
                        <div class="text-center py-8">
                            <p class="text-sm font-medium text-gray-500">No wishes yet. Be the first!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($recentWishes as $wish): ?>
                            <?php 
                            $isWish = ($wish['type'] === 'wish');
                            $link = $isWish ? "share.php?id=" . urlencode($wish['unique_id']) : "view_thought.php?id=" . urlencode($wish['unique_id']);
                            ?>
                            <a href="<?= $link ?>" class="group flex items-center gap-4 p-3 rounded-2xl hover:bg-gradient-to-r hover:from-gray-50 hover:to-orange-50 transition-all border border-transparent hover:border-orange-100 shadow-sm">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-india-saffron to-orange-300 p-[2px] shadow-sm group-hover:scale-110 transition-transform">
                                    <div class="w-full h-full bg-white rounded-full flex items-center justify-center text-gray-800 font-bold text-lg font-serif">
                                        <?= strtoupper(substr(htmlspecialchars($wish['user_name']), 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <h4 class="font-bold text-gray-900 text-sm truncate group-hover:text-india-saffron transition-colors">
                                        <?= htmlspecialchars($wish['user_name']) ?>
                                    </h4>
                                    <p class="text-[11px] font-medium text-gray-500 mt-0.5 flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i> <?= date('h:i A', strtotime($wish['created_at'])) ?>
                                        <span class="ml-1 text-[9px] px-1.5 py-0.5 rounded bg-gray-100 font-bold uppercase text-gray-400 group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                            <?= $isWish ? 'Card' : 'Thought' ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="text-india-green opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="mt-6 pt-5 border-t border-gray-100 text-center bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Cards Created</p>
                    <p class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-india-blue to-india-saffron drop-shadow-sm animate-pulse">
                        <?= count($recentWishes) * 45 + 12450 ?>+
                    </p>
                </div>
            </div>

            <!-- Quote Card -->
            <div class="bg-gray-900 rounded-3xl p-8 text-center shadow-2xl relative overflow-hidden group hover:scale-[1.02] transition-transform cursor-pointer">
                <div class="absolute inset-0 bg-[url('assets/images/bg.jpg')] opacity-20 object-cover"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/80 to-transparent"></div>
                
                <i class="fa-solid fa-quote-left text-5xl text-india-saffron/50 absolute top-4 left-4"></i>
                <p class="text-white font-serif italic relative z-10 mt-6 leading-relaxed text-lg font-medium">
                    "Swaraj is my birthright and I shall have it!"
                </p>
                <div class="w-12 h-1 bg-india-saffron mx-auto mt-6 relative z-10 rounded-full"></div>
                <p class="text-sm font-black text-gray-300 uppercase mt-4 tracking-widest relative z-10">- Bal Gangadhar Tilak</p>
            </div>
        </div>
    </main>

    <!-- Horizontal 728x90 Ad Banner (Hidden on Mobile for UX) -->
    <div class="hidden md:flex justify-center my-6">
        <div class="overflow-hidden bg-white p-2 rounded-xl border border-gray-100 shadow-sm">
            <script> atOptions = { 'key' : '4c489d033932744ca7a2e51a14d42a04', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {} }; </script>
            <script src="https://manhoodinvoluntaryplash.com/4c489d033932744ca7a2e51a14d42a04/invoke.js"></script>
        </div>
    </div>

    <!-- Horizontal Scrolling Gallery (More visually striking) -->
    <section class="w-full bg-white border-t border-gray-100 py-16 mt-10 relative shadow-[0_-10px_30px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 mb-10 text-center">
            <span class="text-india-saffron font-bold tracking-widest text-sm uppercase mb-2 block">Inspiration</span>
            <h3 class="text-3xl md:text-4xl font-serif font-black text-gray-900">
                Wall of <span class="text-india-green">Patriots</span>
            </h3>
        </div>
        
        <div class="relative w-full overflow-hidden" style="mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);">
            
            <div class="flex gap-6 w-max animate-gallery-scroll hover:[animation-play-state:paused] pb-8 pt-4">
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
                <a href="<?= $patriot['link'] ?>" class="flex-none w-72 md:w-80 group block animate-slide-up">
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-500 group-hover:shadow-2xl group-hover:-translate-y-4 h-full flex flex-col">
                        <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                            <img src="<?= $patriot['image'] ?>" alt="<?= $patriot['label'] ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                                <span class="bg-white/20 backdrop-blur-md text-white font-bold px-4 py-1.5 rounded-full text-sm border border-white/40">Vande Mataram</span>
                            </div>
                        </div>
                        <div class="p-5 text-center bg-white relative flex-grow flex flex-col justify-center">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-16 h-1.5 bg-gradient-to-r from-india-saffron via-white to-india-green rounded-b-full"></div>
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

    <!-- Modern Footer -->
    <footer class="w-full bg-gray-900 pt-16 pb-8 border-t-4 border-india-saffron z-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
            <i class="fa-solid fa-dharmachakra text-[300px] text-white animate-spin-slow"></i>
        </div>
        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-2xl font-serif font-black text-white mb-6">Celebrate <span class="text-india-saffron">India</span></h2>
            <p class="text-gray-400 font-medium mb-8 max-w-md mx-auto">Made with ❤️ for the people of India. Create, share, and celebrate our glorious Independence.</p>
            
            <div class="flex justify-center gap-6 mb-6">
                <a href="#" class="w-12 h-12 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-india-blue hover:scale-110 transition-all shadow-lg text-xl"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="w-12 h-12 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-pink-500 hover:scale-110 transition-all shadow-lg text-xl"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="w-12 h-12 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-green-500 hover:scale-110 transition-all shadow-lg text-xl"><i class="fa-brands fa-whatsapp"></i></a>
            </div>

            <!-- Smartlink Sponsor Button -->
            <div class="mb-8">
                <a href="https://manhoodinvoluntaryplash.com/f0dq45ix5?key=276d18012448536d74b877f879b807ef" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 hover:bg-white/10 text-india-saffron hover:text-white transition-all px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm animate-bounce">
                    <i class="fa-solid fa-gift"></i> Claim Free Independence Day Gifts! 🇮🇳
                </a>
            </div>

            <p class="text-sm text-gray-500 font-medium border-t border-gray-800 pt-8">
                &copy; <?= date('Y') ?> wishme15august.space | Jai Hind 🇮🇳
            </p>
        </div>
    </footer>

    <!-- Elegant Loading Overlay (Updated) -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white/95 z-[999] flex-col items-center justify-center hidden backdrop-blur-xl">
        <div class="relative w-32 h-32 mb-8">
            <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-india-saffron border-r-india-green border-b-india-blue animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fa-solid fa-dharmachakra text-5xl text-india-blue animate-spin-slow"></i>
            </div>
        </div>
        <h2 class="text-3xl font-serif font-black text-gray-900 tracking-tight animate-pulse">Crafting Your Masterpiece</h2>
        <p class="text-md text-gray-500 mt-3 font-medium font-sans">Mixing saffron, white, and green... please wait 🇮🇳</p>
    </div>

    <script src="https://manhoodinvoluntaryplash.com/9d/5e/cc/9d5eccdda7f8d548b44da339b0cd7924.js"></script>
    <script src="assets/js/script.js"></script>
    
    <!-- Inline JS for Shimmer keyframe missing in config -->
    <style>
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
    </style>
</body>
</html>