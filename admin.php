<?php
// --- Security: Secure session settings ---
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

session_start();

require_once 'db.php';

// --- 1. Database Migrations (Run Automatically) ---
try {
    try { $pdo->query("SELECT is_private FROM wishes LIMIT 1"); } catch (PDOException $e) { $pdo->exec("ALTER TABLE wishes ADD COLUMN is_private TINYINT(1) DEFAULT 0"); }
    try { $pdo->query("SELECT is_private FROM thoughts LIMIT 1"); } catch (PDOException $e) { $pdo->exec("ALTER TABLE thoughts ADD COLUMN is_private TINYINT(1) DEFAULT 0"); }

    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(32) NOT NULL
    )");

    $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
    if ($stmt->fetchColumn() == 0) {
        $defaultPass = md5('admin123');
        $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)")->execute(['admin', $defaultPass]);
    }
} catch (PDOException $e) {
    die("Database migration error: " . htmlspecialchars($e->getMessage()));
}

// --- CSRF Token Helper ---
function generateCsrf() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verifyCsrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// --- 2. Action Handlers ---
$error = '';
$success = '';

// Rate-limit login: max 5 attempts per 5 minutes
if (isset($_POST['login'])) {
    if (!isset($_SESSION['login_attempts'])) { $_SESSION['login_attempts'] = 0; $_SESSION['login_lockout'] = 0; }
    
    if ($_SESSION['login_attempts'] >= 5 && time() - $_SESSION['login_lockout'] < 300) {
        $error = "Too many failed attempts. Please try again after 5 minutes.";
    } else {
        // Verify CSRF
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            $error = "Security token expired. Please refresh and try again.";
        } else {
            $username = trim($_POST['username']);
            $password = md5(trim($_POST['password']));

            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
            $stmt->execute([$username, $password]);
            $admin = $stmt->fetch();

            if ($admin) {
                // Reset login attempts on success
                $_SESSION['login_attempts'] = 0;
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                // Regenerate session ID to prevent fixation
                session_regenerate_id(true);
                header("Location: admin.php");
                exit;
            } else {
                $_SESSION['login_attempts']++;
                $_SESSION['login_lockout'] = time();
                $remaining = 5 - $_SESSION['login_attempts'];
                $error = "Invalid username or password! ($remaining attempts remaining)";
            }
        }
    }
}

// Handle Logout (CSRF protected)
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $_SESSION = [];
    session_destroy();
    header("Location: admin.php");
    exit;
}

$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// --- Helper: Delete a single creation ---
function deleteCreation($pdo, $type, $id) {
    if ($type == 'wish') {
        $stmt = $pdo->prepare("SELECT user_image, unique_id FROM wishes WHERE unique_id = ?");
        $stmt->execute([$id]);
        $wish = $stmt->fetch();
        if ($wish) {
            $gen = __DIR__ . '/generated/' . $wish['unique_id'] . '.jpg';
            $upl = __DIR__ . '/uploads/' . $wish['user_image'];
            if (file_exists($gen)) @unlink($gen);
            if (file_exists($upl)) @unlink($upl);
            $pdo->prepare("DELETE FROM wishes WHERE unique_id = ?")->execute([$id]);
            return true;
        }
    } elseif ($type == 'thought') {
        $stmt = $pdo->prepare("SELECT user_image FROM thoughts WHERE unique_id = ?");
        $stmt->execute([$id]);
        $thought = $stmt->fetch();
        if ($thought) {
            $upl = __DIR__ . '/uploads/' . $thought['user_image'];
            if (file_exists($upl)) @unlink($upl);
            $pdo->prepare("DELETE FROM thoughts WHERE unique_id = ?")->execute([$id]);
            return true;
        }
    }
    return false;
}

if ($isLoggedIn) {
    // --- Bulk Actions (POST with CSRF) ---
    if (isset($_POST['bulk_action']) && verifyCsrf($_POST['csrf_token'] ?? '')) {
        $action = $_POST['bulk_action'];
        $selectedItems = $_POST['selected'] ?? [];
        $count = 0;

        foreach ($selectedItems as $item) {
            // Format: "type:unique_id"
            $parts = explode(':', $item, 2);
            if (count($parts) !== 2) continue;
            $type = $parts[0];
            $uid = $parts[1];

            if ($action === 'delete') {
                if (deleteCreation($pdo, $type, $uid)) $count++;
            } elseif ($action === 'make_private') {
                $table = ($type === 'wish') ? 'wishes' : 'thoughts';
                $pdo->prepare("UPDATE $table SET is_private = 1 WHERE unique_id = ?")->execute([$uid]);
                $count++;
            } elseif ($action === 'make_public') {
                $table = ($type === 'wish') ? 'wishes' : 'thoughts';
                $pdo->prepare("UPDATE $table SET is_private = 0 WHERE unique_id = ?")->execute([$uid]);
                $count++;
            }
        }

        if ($count > 0) {
            $actionName = ($action === 'delete') ? 'deleted' : (($action === 'make_private') ? 'made private' : 'made public');
            $success = "$count creation(s) $actionName successfully!";
        }
    }

    // Single Delete (GET — with CSRF token)
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['csrf']) && verifyCsrf($_GET['csrf'])) {
        $type = $_GET['type'] ?? '';
        $id = $_GET['id'] ?? '';
        if (deleteCreation($pdo, $type, $id)) {
            $success = ($type == 'wish') ? "Wish card deleted!" : "Thought deleted!";
        }
    }

    // Single Toggle Privacy (GET — with CSRF token)
    if (isset($_GET['action']) && $_GET['action'] == 'toggle_private' && isset($_GET['csrf']) && verifyCsrf($_GET['csrf'])) {
        $type = $_GET['type'] ?? '';
        $id = $_GET['id'] ?? '';
        $table = ($type === 'wish') ? 'wishes' : 'thoughts';
        $pdo->prepare("UPDATE $table SET is_private = 1 - is_private WHERE unique_id = ?")->execute([$id]);
        $success = "Visibility toggled!";
    }
}

// --- 3. Dashboard Stats & Data ---
$totalWishes = 0; $totalThoughts = 0; $totalPrivate = 0;
$creations = []; $search = ''; $filterType = '';

if ($isLoggedIn) {
    $totalWishes = $pdo->query("SELECT COUNT(*) FROM wishes")->fetchColumn();
    $totalThoughts = $pdo->query("SELECT COUNT(*) FROM thoughts")->fetchColumn();
    $totalPrivate = $pdo->query("SELECT COUNT(*) FROM wishes WHERE is_private=1")->fetchColumn() + $pdo->query("SELECT COUNT(*) FROM thoughts WHERE is_private=1")->fetchColumn();

    $search = isset($_GET['q']) ? trim($_GET['q']) : '';
    $filterType = isset($_GET['type_filter']) ? $_GET['type_filter'] : '';

    // Build query dynamically
    $wishQuery = "SELECT 'wish' AS type, unique_id, user_name, message AS content, created_at, is_private FROM wishes";
    $thoughtQuery = "SELECT 'thought' AS type, unique_id, user_name, message_text AS content, created_at, is_private FROM thoughts";
    $params = [];

    if ($search !== '') {
        $qParam = '%' . $search . '%';
        $wishQuery .= " WHERE (user_name LIKE :wq1 OR unique_id LIKE :wq2)";
        $thoughtQuery .= " WHERE (user_name LIKE :tq1 OR unique_id LIKE :tq2)";
    }

    if ($filterType === 'wish') {
        $queryStr = $wishQuery . " ORDER BY created_at DESC LIMIT 100";
    } elseif ($filterType === 'thought') {
        $queryStr = $thoughtQuery . " ORDER BY created_at DESC LIMIT 100";
    } else {
        $queryStr = "($wishQuery) UNION ALL ($thoughtQuery) ORDER BY created_at DESC LIMIT 100";
    }

    if ($search !== '') {
        $stmt = $pdo->prepare($queryStr);
        if ($filterType === 'wish') {
            $stmt->execute(['wq1' => $qParam, 'wq2' => $qParam]);
        } elseif ($filterType === 'thought') {
            $stmt->execute(['tq1' => $qParam, 'tq2' => $qParam]);
        } else {
            $stmt->execute(['wq1' => $qParam, 'wq2' => $qParam, 'tq1' => $qParam, 'tq2' => $qParam]);
        }
        $creations = $stmt->fetchAll();
    } else {
        $creations = $pdo->query($queryStr)->fetchAll();
    }
}

$csrf = generateCsrf();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - wishme15august.space</title>
    <!-- Security: Prevent search engines from indexing admin panel -->
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Playfair+Display:ital,wght@0,900;1,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #030712; color: #f3f4f6; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between">

    <!-- Top Header -->
    <header class="w-full bg-gray-950 border-b border-gray-800 py-5 px-6 md:px-8 flex justify-between items-center z-10 relative">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-screwdriver-wrench text-2xl text-orange-500 animate-pulse"></i>
            <h1 class="text-lg md:text-xl font-black tracking-wider text-white">WISHME <span class="text-orange-500 font-serif">15 AUG</span> ADMIN</h1>
        </div>
        <?php if ($isLoggedIn): ?>
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-gray-400 hidden md:inline">Logged in: <strong class="text-white"><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
                <a href="admin.php?action=logout" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all">
                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                </a>
            </div>
        <?php endif; ?>
    </header>

    <main class="flex-grow flex flex-col items-center justify-center p-4 md:p-10 max-w-7xl mx-auto w-full">
        
        <?php if (!$isLoggedIn): ?>
            <!-- LOGIN -->
            <div class="w-full max-w-md bg-gray-900 border border-gray-800 p-8 rounded-3xl shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 flex">
                    <div class="w-1/3 bg-orange-500"></div>
                    <div class="w-1/3 bg-white"></div>
                    <div class="w-1/3 bg-green-600"></div>
                </div>
                
                <h2 class="text-3xl font-serif font-black text-center text-white mb-2 mt-2">Admin Login</h2>
                <p class="text-gray-400 text-sm text-center mb-8">Enter your credentials to manage the platform</p>
                
                <?php if ($error): ?>
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-sm font-semibold flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="admin.php" method="POST" class="space-y-6" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-400">Username</label>
                        <input type="text" name="username" required placeholder="e.g. admin" autocomplete="off" class="w-full px-4 py-3 bg-gray-950 border border-gray-800 rounded-xl text-white font-medium outline-none focus:border-orange-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-400">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" autocomplete="off" class="w-full px-4 py-3 bg-gray-950 border border-gray-800 rounded-xl text-white font-medium outline-none focus:border-orange-500 transition-colors">
                    </div>
                    <button type="submit" name="login" class="w-full py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-black text-lg rounded-xl shadow-lg hover:shadow-orange-500/10 hover:-translate-y-0.5 transition-all">
                        Sign In <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>
                </form>
            </div>

        <?php else: ?>
            <!-- DASHBOARD -->
            
            <?php if ($success): ?>
                <div class="w-full mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-2xl text-green-400 text-sm font-semibold flex items-center gap-3">
                    <i class="fa-solid fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 w-full mb-10">
                <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Wish Cards</span>
                        <h3 class="text-2xl md:text-3xl font-serif font-black text-white mt-1"><?= $totalWishes ?></h3>
                    </div>
                    <div class="w-10 h-10 bg-orange-500/10 text-orange-400 rounded-xl flex items-center justify-center text-lg border border-orange-500/20">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                </div>
                <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Thoughts</span>
                        <h3 class="text-2xl md:text-3xl font-serif font-black text-white mt-1"><?= $totalThoughts ?></h3>
                    </div>
                    <div class="w-10 h-10 bg-green-500/10 text-green-400 rounded-xl flex items-center justify-center text-lg border border-green-500/20">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                </div>
                <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Private</span>
                        <h3 class="text-2xl md:text-3xl font-serif font-black text-white mt-1"><?= $totalPrivate ?></h3>
                    </div>
                    <div class="w-10 h-10 bg-red-500/10 text-red-400 rounded-xl flex items-center justify-center text-lg border border-red-500/20">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>
                <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total</span>
                        <h3 class="text-2xl md:text-3xl font-serif font-black text-white mt-1"><?= $totalWishes + $totalThoughts ?></h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center text-lg border border-blue-500/20">
                        <i class="fa-solid fa-chart-bar"></i>
                    </div>
                </div>
            </div>

            <!-- Management Panel -->
            <div class="w-full bg-gray-900 border border-gray-800 p-6 md:p-8 rounded-3xl shadow-xl">
                
                <!-- Filters Row -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl md:text-2xl font-serif font-black text-white">Manage Creations</h2>
                        <p class="text-gray-500 text-xs mt-1">Showing up to 100 results. Use filters and checkboxes for bulk actions.</p>
                    </div>
                    <form action="admin.php" method="GET" class="w-full md:w-auto flex flex-wrap gap-2">
                        <div class="relative flex-grow md:w-64">
                            <i class="fa-solid fa-search text-gray-500 absolute left-3 top-1/2 transform -translate-y-1/2 text-xs"></i>
                            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search name or ID..." class="w-full pl-9 pr-3 py-2.5 bg-gray-950 border border-gray-800 rounded-xl text-white text-sm font-medium outline-none focus:border-orange-500 transition-colors">
                        </div>
                        <select name="type_filter" class="bg-gray-950 border border-gray-800 rounded-xl text-white text-sm font-medium px-3 py-2.5 outline-none focus:border-orange-500">
                            <option value="" <?= $filterType === '' ? 'selected' : '' ?>>All Types</option>
                            <option value="wish" <?= $filterType === 'wish' ? 'selected' : '' ?>>Wishes Only</option>
                            <option value="thought" <?= $filterType === 'thought' ? 'selected' : '' ?>>Thoughts Only</option>
                        </select>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition-colors">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        <?php if ($search !== '' || $filterType !== ''): ?>
                            <a href="admin.php" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Bulk Actions Form wraps the entire table -->
                <form method="POST" action="admin.php" id="bulkForm">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    
                    <!-- Bulk Actions Bar -->
                    <div class="flex items-center gap-3 mb-4 p-3 bg-gray-950 rounded-xl border border-gray-800">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Bulk Action:</span>
                        <select name="bulk_action" class="bg-gray-900 border border-gray-700 rounded-lg text-white text-sm font-medium px-3 py-2 outline-none focus:border-orange-500 flex-grow md:flex-grow-0 md:w-48">
                            <option value="">-- Select Action --</option>
                            <option value="delete">🗑️ Delete Selected</option>
                            <option value="make_private">🔒 Make Private</option>
                            <option value="make_public">🌐 Make Public</option>
                        </select>
                        <button type="submit" onclick="return confirmBulk()" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-bold shadow transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-bolt mr-1"></i> Apply
                        </button>
                        <span class="text-xs text-gray-500 hidden md:inline" id="selectedCount">0 selected</span>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    <th class="pb-3 pl-1"><input type="checkbox" id="selectAll" class="accent-orange-500 w-4 h-4 cursor-pointer rounded"></th>
                                    <th class="pb-3">Type</th>
                                    <th class="pb-3">Unique ID</th>
                                    <th class="pb-3">User Name</th>
                                    <th class="pb-3">Content</th>
                                    <th class="pb-3">Created</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/50 text-sm">
                                <?php if (count($creations) == 0): ?>
                                    <tr>
                                        <td colspan="8" class="py-10 text-center text-gray-500 font-semibold">
                                            <i class="fa-solid fa-inbox text-3xl mb-3 block text-gray-600"></i>
                                            No creations found.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($creations as $c): ?>
                                        <tr class="hover:bg-white/[0.02] transition-colors">
                                            <td class="py-3 pl-1">
                                                <input type="checkbox" name="selected[]" value="<?= $c['type'] ?>:<?= htmlspecialchars($c['unique_id']) ?>" class="row-checkbox accent-orange-500 w-4 h-4 cursor-pointer rounded">
                                            </td>
                                            <td class="py-3">
                                                <?php if ($c['type'] == 'wish'): ?>
                                                    <span class="bg-orange-500/10 text-orange-400 border border-orange-500/20 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Wish</span>
                                                <?php else: ?>
                                                    <span class="bg-green-500/10 text-green-400 border border-green-500/20 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Thought</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 font-mono text-[11px] text-gray-500"><?= htmlspecialchars(substr($c['unique_id'], 0, 16)) ?>…</td>
                                            <td class="py-3 font-bold text-white text-sm"><?= htmlspecialchars($c['user_name']) ?></td>
                                            <td class="py-3 max-w-[200px] truncate text-gray-400 text-xs" title="<?= htmlspecialchars($c['content']) ?>"><?= htmlspecialchars(mb_substr($c['content'], 0, 60)) ?></td>
                                            <td class="py-3 text-gray-500 text-xs whitespace-nowrap"><?= date('d M, H:i', strtotime($c['created_at'])) ?></td>
                                            <td class="py-3">
                                                <?php if ($c['is_private'] == 1): ?>
                                                    <span class="text-red-400 font-bold text-[10px] flex items-center gap-1"><i class="fa-solid fa-lock"></i> Private</span>
                                                <?php else: ?>
                                                    <span class="text-green-400 font-bold text-[10px] flex items-center gap-1"><i class="fa-solid fa-globe"></i> Public</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <?php $viewLink = ($c['type'] == 'wish') ? "share.php?id=" . urlencode($c['unique_id']) : "view_thought.php?id=" . urlencode($c['unique_id']); ?>
                                                    <a href="<?= $viewLink ?>" target="_blank" class="w-7 h-7 rounded-md bg-gray-800 hover:bg-gray-700 text-gray-300 flex items-center justify-center text-[10px] transition-colors" title="View">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <a href="admin.php?action=toggle_private&type=<?= $c['type'] ?>&id=<?= urlencode($c['unique_id']) ?>&csrf=<?= $csrf ?><?= $search !== '' ? '&q='.urlencode($search) : '' ?><?= $filterType !== '' ? '&type_filter='.urlencode($filterType) : '' ?>" 
                                                       class="w-7 h-7 rounded-md flex items-center justify-center text-[10px] transition-colors <?= $c['is_private'] == 1 ? 'bg-green-500/10 hover:bg-green-500/20 text-green-400' : 'bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400' ?>" 
                                                       title="<?= $c['is_private'] == 1 ? 'Make Public' : 'Make Private' ?>">
                                                        <i class="fa-solid <?= $c['is_private'] == 1 ? 'fa-unlock' : 'fa-lock' ?>"></i>
                                                    </a>
                                                    <a href="admin.php?action=delete&type=<?= $c['type'] ?>&id=<?= urlencode($c['unique_id']) ?>&csrf=<?= $csrf ?><?= $search !== '' ? '&q='.urlencode($search) : '' ?><?= $filterType !== '' ? '&type_filter='.urlencode($filterType) : '' ?>" 
                                                       onclick="return confirm('Permanently delete this? Files will be removed from disk.')" 
                                                       class="w-7 h-7 rounded-md bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center text-[10px] transition-colors" title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-xs text-gray-500 text-right">
                        Showing <?= count($creations) ?> of <?= ($totalWishes + $totalThoughts) ?> total creations
                    </div>
                </form>
            </div>
        <?php endif; ?>

    </main>

    <footer class="w-full bg-gray-950 border-t border-gray-800 py-5 text-center text-xs font-semibold text-gray-600">
        &copy; <?= date('Y') ?> wishme15august.space — Admin Dashboard
    </footer>

    <script>
        // Select All checkbox
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const countDisplay = document.getElementById('selectedCount');

        function updateCount() {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            if (countDisplay) countDisplay.textContent = checked + ' selected';
        }

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                updateCount();
            });
            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    selectAll.checked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                    updateCount();
                });
            });
        }

        function confirmBulk() {
            const action = document.querySelector('[name="bulk_action"]').value;
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            if (!action) { alert('Please select a bulk action first.'); return false; }
            if (checked === 0) { alert('Please select at least one item.'); return false; }
            if (action === 'delete') {
                return confirm('Are you sure you want to permanently DELETE ' + checked + ' selected item(s)? This cannot be undone.');
            }
            return confirm('Apply "' + action.replace('_', ' ') + '" to ' + checked + ' selected item(s)?');
        }
    </script>

</body>
</html>
