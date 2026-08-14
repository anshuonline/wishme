<?php
/**
 * Automated Deployment Script
 * 
 * You can trigger this script via a webhook from GitHub/GitLab or a simple cron/SSH curl request.
 * Example: http://yourdomain.com/deploy.php?token=YOUR_SECRET_TOKEN
 */

// Define a secret token to prevent unauthorized access
$secretToken = 'wishme_deploy_secret_2026';

// 1. Verify the token
if (!isset($_GET['token']) || $_GET['token'] !== $secretToken) {
    // Optional: Also check for GitHub Webhook signature if you're using GitHub Webhooks
    $payload = file_get_contents('php://input');
    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
    
    if ($signature) {
        $hash = 'sha256=' . hash_hmac('sha256', $payload, $secretToken);
        if (!hash_equals($hash, $signature)) {
            header('HTTP/1.1 403 Forbidden');
            die('Access denied: Invalid signature');
        }
    } else {
        header('HTTP/1.1 403 Forbidden');
        die('Access denied: Invalid token');
    }
}

// 2. Deployment commands
// You can add more commands here like clearing cache, running migrations, etc.
$commands = [
    'git pull origin main', // Change 'main' to 'master' if your default branch is master
    // 'composer install --no-dev --optimize-autoloader', // If you use composer
];

// 3. Execute commands and log output
$outputLog = '';
foreach ($commands as $command) {
    // 2>&1 redirects stderr to stdout so we capture errors as well
    exec($command . ' 2>&1', $output, $return_var);
    $outputLog .= "$ $command\n";
    $outputLog .= implode("\n", $output) . "\n";
    $outputLog .= "Return code: $return_var\n\n";
    
    // Clear output array for the next command
    $output = [];
}

// 4. Output the result
header('Content-Type: text/plain');
echo "Deployment started at: " . date('Y-m-d H:i:s') . "\n\n";
echo "Output Log:\n";
echo "--------------------------------------------------\n";
echo $outputLog;
echo "--------------------------------------------------\n";
echo "Deployment finished at: " . date('Y-m-d H:i:s') . "\n";
?>
