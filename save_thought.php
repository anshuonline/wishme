<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = trim($_POST['user_name'] ?? '');
    $messageType = $_POST['message_type'] ?? 'predefined_en';
    $base64Image = $_POST['user_image_cropped'] ?? '';

    if (empty($userName) || empty($base64Image)) {
        die("Error: Name and Photo are required.");
    }

    // Determine the message text
    $messageText = "";
    if ($messageType === 'predefined_en') {
        $messageText = $_POST['msg_en'] ?? '';
    } elseif ($messageType === 'predefined_hi') {
        $messageText = $_POST['msg_hi'] ?? '';
    } elseif ($messageType === 'custom') {
        $messageText = trim($_POST['msg_custom'] ?? '');
    }

    if (empty($messageText)) {
        $messageText = "Happy Independence Day!";
    }

    // Process Base64 Image
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $imageParts = explode(";base64,", $base64Image);
    if (count($imageParts) === 2) {
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1] ?? 'jpeg';
        
        // Security Check: Whitelist allowed image extensions
        $allowedTypes = ['jpeg', 'jpg', 'png', 'webp'];
        if (!in_array(strtolower($imageType), $allowedTypes)) {
            die("Security Error: Invalid image extension. Upload blocked.");
        }

        $imageBase64 = base64_decode($imageParts[1]);

        // Security Check: Verify it's actually an image
        if (!@getimagesizefromstring($imageBase64)) {
            die("Security Error: Invalid image payload. Upload blocked.");
        }

        $uniqueId = uniqid() . bin2hex(random_bytes(4));
        $fileName = $uniqueId . '.' . $imageType;
        $filePath = $uploadDir . $fileName;

        if (file_put_contents($filePath, $imageBase64)) {
            // Insert into database
            try {
                $stmt = $pdo->prepare("INSERT INTO thoughts (unique_id, user_name, user_image, message_text) VALUES (?, ?, ?, ?)");
                $stmt->execute([$uniqueId, $userName, $fileName, $messageText]);

                // Redirect to the viral view page
                header("Location: view_thought.php?id=" . $uniqueId);
                exit;
            } catch (PDOException $e) {
                die("Database Error: " . $e->getMessage());
            }
        } else {
            die("Error saving the image.");
        }
    } else {
        die("Invalid image data.");
    }
} else {
    header("Location: index.php");
    exit;
}
