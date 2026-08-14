<?php
require_once 'db.php';
require_once 'image_processor.php';

$sampleImages = [
    'C:\Users\hello\.gemini\antigravity\brain\6dc28669-6d6d-4052-9fd9-fadaa53157e9\sample_indian_man_1_1786739498538.jpg',
    'C:\Users\hello\.gemini\antigravity\brain\6dc28669-6d6d-4052-9fd9-fadaa53157e9\sample_indian_man_2_1786739544660.jpg'
];

$names = ['Aarav Patel', 'Diya Sharma', 'Rohan Iyer', 'Neha Gupta', 'Kabir Singh', 'Aditi Verma', 'Rahul Desai', 'Sneha Reddy', 'Amit Joshi', 'Kriti Menon', 'Vikram Kumar', 'Pooja Chatterjee', 'Siddharth Rao', 'Anjali Nair', 'Kunal Malhotra'];
$languages = ['msg1', 'msg2', 'msg3', 'msg4'];

for ($i = 0; $i < 15; $i++) {
    $uniqueId = uniqid('wish_');
    $userName = $names[$i];
    $language = $languages[array_rand($languages)];
    $sourceImage = $sampleImages[$i % 2];
    
    // Copy image to uploads
    $newFileName = $uniqueId . '_profile.jpg';
    $destFilePath = __DIR__ . '/uploads/' . $newFileName;
    copy($sourceImage, $destFilePath);
    
    // Generate Final Image
    $generatedImagePath = generateWishImage($userName, $destFilePath, $language, $uniqueId);
    
    if ($generatedImagePath) {
        $messages = [
            'msg1' => "May the tricolor always fly high! Let's honor our heroes by building a united, prosperous, and self-reliant India. Happy Independence Day!",
            'msg2' => "Tiranga hamesha uncha rahe! Aao hum sab milkar ek atmanirbhar, mazboot aur shantipurna Bharat ka nirmaan karein.",
            'msg3' => "Sare jahan se accha Hindustan hamara! Wishing you a very Happy Independence Day full of pride and joy.",
            'msg4' => "Let's celebrate the freedom we enjoy and remember the sacrifices of our brave freedom fighters. Happy 80th Independence Day!"
        ];
        $message = $messages[$language];
        
        $stmt = $pdo->prepare("INSERT INTO wishes (unique_id, user_name, user_image, language, message) VALUES (:unique_id, :user_name, :user_image, :language, :message)");
        $stmt->execute([
            ':unique_id' => $uniqueId,
            ':user_name' => $userName,
            ':user_image' => $newFileName,
            ':language' => $language,
            ':message' => $message
        ]);
        echo "Inserted $userName\n";
    } else {
        echo "Failed for $userName\n";
    }
}
echo "Done inserting 15 dummy wishes.\n";
?>
