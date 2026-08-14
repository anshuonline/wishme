<?php
require_once 'db.php';
require_once 'image_processor.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : 'generate';

    if ($action === 'change_frame') {
        $uniqueId = isset($_POST['unique_id']) ? $_POST['unique_id'] : '';
        $frameTemplate = isset($_POST['frame_template']) ? $_POST['frame_template'] : 'frame1.jpg';
        
        if (empty($uniqueId)) die("Missing ID");
        
        $stmt = $pdo->prepare("SELECT * FROM wishes WHERE unique_id = ?");
        $stmt->execute([$uniqueId]);
        $wish = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$wish) die("Wish not found");
        
        $targetFilePath = __DIR__ . '/uploads/' . $wish['user_image'];
        $outputFileName = $uniqueId . '.jpg';
        $userName = $wish['user_name'];

        $imgX = isset($_POST['img_x']) && $_POST['img_x'] !== '' ? floatval($_POST['img_x']) : -1;
        $imgY = isset($_POST['img_y']) && $_POST['img_y'] !== '' ? floatval($_POST['img_y']) : -1;
        $imgScale = isset($_POST['img_scale']) && $_POST['img_scale'] !== '' ? floatval($_POST['img_scale']) : -1;
        
        try {
            ImageProcessor::createSocialFrame($userName, $targetFilePath, $outputFileName, $frameTemplate, $imgX, $imgY, $imgScale);
            
            // Update message to reflect frame
            $stmt = $pdo->prepare("UPDATE wishes SET message = ? WHERE unique_id = ?");
            $stmt->execute(["Created a proud Independence Day Profile Frame!", $uniqueId]);
            
            header("Location: share.php?id=" . $uniqueId);
            exit();
        } catch (Exception $e) {
            die("Image processing error: " . $e->getMessage());
        }
    }

    $userName = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $languageKey = isset($_POST['language']) ? $_POST['language'] : 'msg1';
    $croppedImageBase64 = isset($_POST['user_image_cropped']) ? $_POST['user_image_cropped'] : '';
    $generationType = isset($_POST['generation_type']) ? $_POST['generation_type'] : 'wish';
    $fontStyle = isset($_POST['font_style']) ? $_POST['font_style'] : 'poppins';

    // Basic validation
    if (empty($userName) || empty($croppedImageBase64)) {
        die("Please provide your name and a cropped image.");
    }

    // Decode base64 image
    list($type, $croppedImageBase64) = explode(';', $croppedImageBase64);
    
    // Security Check: Whitelist allowed image extensions
    $imageTypeAux = explode("image/", $type);
    $imageType = $imageTypeAux[1] ?? 'jpeg';
    $allowedTypes = ['jpeg', 'jpg', 'png', 'webp'];
    if (!in_array(strtolower($imageType), $allowedTypes)) {
        die("Security Error: Invalid image extension. Upload blocked.");
    }

    list(, $croppedImageBase64)      = explode(',', $croppedImageBase64);
    $imageData = base64_decode($croppedImageBase64);

    if ($imageData === false) {
        die("Failed to decode cropped image.");
    }

    // Security Check: Verify it's actually an image
    if (!@getimagesizefromstring($imageData)) {
        die("Security Error: Invalid image payload. Upload blocked.");
    }

    // Ensure uploads directory exists
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Create unique file name
    $uniqueId = uniqid();
    $fileName = time() . '_' . $uniqueId . '.jpg';
    $targetFilePath = $uploadDir . $fileName;

    // Save image to disk
    if (file_put_contents($targetFilePath, $imageData) === false) {
        die("Failed to save image file.");
    }

    // Ensure generated directory exists
    $generatedDir = __DIR__ . '/generated/';
    if (!is_dir($generatedDir)) {
        mkdir($generatedDir, 0755, true);
    }

    $imgX = isset($_POST['img_x']) && $_POST['img_x'] !== '' ? floatval($_POST['img_x']) : -1;
    $imgY = isset($_POST['img_y']) && $_POST['img_y'] !== '' ? floatval($_POST['img_y']) : -1;
    $imgScale = isset($_POST['img_scale']) && $_POST['img_scale'] !== '' ? floatval($_POST['img_scale']) : -1;

    // Process the image
    $outputFileName = $uniqueId . '.jpg';
    
    try {
        if ($generationType === 'frame') {
            $frameTemplate = isset($_POST['frame_template']) ? $_POST['frame_template'] : 'frame1.jpg';
            $finalImage = ImageProcessor::createSocialFrame($userName, $targetFilePath, $outputFileName, $frameTemplate, $imgX, $imgY, $imgScale);
            $message = "Created a proud Independence Day Profile Frame!";
        } else {
            $finalImage = ImageProcessor::createWishCard($userName, $languageKey, $targetFilePath, $outputFileName, $fontStyle);
            $message = "Generated a beautiful Independence Day Wish.";
        }
        
        // Save to Database
        $stmt = $pdo->prepare("INSERT INTO wishes (unique_id, user_name, user_image, language, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$uniqueId, $userName, $fileName, $languageKey, $message])) {
            header("Location: share.php?id=" . $uniqueId);
            exit();
        } else {
            echo "Failed to save record to database.";
        }
    } catch (Exception $e) {
        die("Image processing error: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
