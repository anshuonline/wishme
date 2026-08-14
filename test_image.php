<?php
require_once 'image_processor.php';

// Create a dummy image for testing
$dummyImagePath = __DIR__ . '/uploads/dummy.jpg';
if (!file_exists($dummyImagePath)) {
    $img = imagecreatetruecolor(400, 400);
    $red = imagecolorallocate($img, 255, 0, 0);
    imagefill($img, 0, 0, $red);
    imagejpeg($img, $dummyImagePath);
    imagedestroy($img);
}

$uniqueId = 'test_' . time();
$result = generateWishImage('Test User', $dummyImagePath, 'English', $uniqueId);

if ($result && file_exists($result)) {
    echo "Success! Image generated at: " . $result;
} else {
    echo "Failed to generate image.";
}
?>
