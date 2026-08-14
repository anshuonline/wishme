<?php
/**
 * Utility to process and merge images for the 15th August Wish
 */

class ImageProcessor {

    public static function createWishCard($userName, $language, $userImagePath, $outputFileName, $fontStyle = 'poppins') {
        // Define paths
        $templatePath = __DIR__ . '/templates/template.png';
        $outputPath = __DIR__ . '/generated/' . $outputFileName;

        // Default fonts
        $fontPathName = __DIR__ . '/assets/fonts/Poppins-Bold.ttf';
        $fontPathMsg = __DIR__ . '/assets/fonts/Poppins-Medium.ttf';
        
        $fontSizeFactorName = 1.0;
        $fontSizeFactorMsg = 1.0;
        $msgLineHeightFactor = 2.0;

        switch ($fontStyle) {
            case 'playfair':
                $fontPathName = __DIR__ . '/assets/fonts/PlayfairDisplay-Variable.ttf';
                $fontPathMsg = __DIR__ . '/assets/fonts/Lora-Variable.ttf';
                $fontSizeFactorName = 1.1;
                $fontSizeFactorMsg = 1.1;
                break;
            case 'greatvibes':
                $fontPathName = __DIR__ . '/assets/fonts/PlayfairDisplay-Variable.ttf';
                $fontPathMsg = __DIR__ . '/assets/fonts/GreatVibes-Regular.ttf';
                $fontSizeFactorName = 1.1;
                $fontSizeFactorMsg = 1.6; // Great Vibes is very thin/small
                $msgLineHeightFactor = 1.5;
                break;
            case 'dancingscript':
                $fontPathName = __DIR__ . '/assets/fonts/Montserrat-Variable.ttf';
                $fontPathMsg = __DIR__ . '/assets/fonts/DancingScript-Variable.ttf';
                $fontSizeFactorName = 1.0;
                $fontSizeFactorMsg = 1.4; // Dancing Script is script
                $msgLineHeightFactor = 1.8;
                break;
            case 'cinzel':
                $fontPathName = __DIR__ . '/assets/fonts/Cinzel-Variable.ttf';
                $fontPathMsg = __DIR__ . '/assets/fonts/Lora-Variable.ttf';
                $fontSizeFactorName = 1.0;
                $fontSizeFactorMsg = 1.1;
                break;
            case 'montserrat':
                $fontPathName = __DIR__ . '/assets/fonts/Montserrat-Variable.ttf';
                $fontPathMsg = __DIR__ . '/assets/fonts/Montserrat-Variable.ttf';
                $fontSizeFactorName = 1.0;
                $fontSizeFactorMsg = 0.95;
                break;
            case 'poppins':
            default:
                $fontPathName = __DIR__ . '/assets/fonts/Poppins-Bold.ttf';
                $fontPathMsg = __DIR__ . '/assets/fonts/Poppins-Medium.ttf';
                break;
        }

        // If template doesn't exist, we will create a basic one for fallback
        if (!file_exists($templatePath)) {
            self::createFallbackTemplate($templatePath);
        }
        
        // Create image resources
        $templateImg = imagecreatefrompng($templatePath);
        if (!$templateImg) {
            return false;
        }

        // Convert PNG template to truecolor if it's not, to avoid blending issues when saving as JPEG
        $trueColorTemplate = imagecreatetruecolor(imagesx($templateImg), imagesy($templateImg));
        $white = imagecolorallocate($trueColorTemplate, 255, 255, 255);
        imagefill($trueColorTemplate, 0, 0, $white);
        imagecopy($trueColorTemplate, $templateImg, 0, 0, 0, 0, imagesx($templateImg), imagesy($templateImg));
        imagedestroy($templateImg);
        $templateImg = $trueColorTemplate;

        $templateWidth = imagesx($templateImg);
        $templateHeight = imagesy($templateImg);

        // Load User Image (already square cropped)
        $userImg = imagecreatefromjpeg($userImagePath);

        if ($userImg) {
            $userOrigWidth = imagesx($userImg);
            $userOrigHeight = imagesy($userImg);

            // --- Configuration for User Image Placement (DYNAMIC) ---
            $boxStartX = intval($templateWidth * 0.48);
            $boxWidth = intval($templateWidth * 0.46);
            $boxStartY = intval($templateHeight * 0.22); // Start a bit lower

            $targetWidth = intval($boxWidth * 0.38); // Make image smaller: 38% of the box width
            $targetHeight = $targetWidth;
            
            $destX = intval($boxStartX + ($boxWidth - $targetWidth) / 2); 
            $destY = $boxStartY; 

            // Resize the cropped user image to target dimensions
            $squareUserImg = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled($squareUserImg, $userImg, 0, 0, 0, 0, $targetWidth, $targetHeight, $userOrigWidth, $userOrigHeight);
            
            // 2. Create circular mask
            $circularUserImg = imagecreatetruecolor($targetWidth, $targetHeight);
            imagealphablending($circularUserImg, false);
            $transparent = imagecolorallocatealpha($circularUserImg, 0, 0, 0, 127);
            imagefill($circularUserImg, 0, 0, $transparent);
            imagesavealpha($circularUserImg, true);
            imagealphablending($circularUserImg, true);

            $radius = $targetWidth / 2;
            for ($x = 0; $x < $targetWidth; $x++) {
                for ($y = 0; $y < $targetHeight; $y++) {
                    $dist = sqrt(pow($x - $radius, 2) + pow($y - $radius, 2));
                    if ($dist <= $radius) {
                        $color = imagecolorat($squareUserImg, $x, $y);
                        imagesetpixel($circularUserImg, $x, $y, $color);
                    }
                }
            }
            
            // 3. Draw a neat circular border around the profile pic
            $borderColor = imagecolorallocate($templateImg, 255, 128, 0); // Deeper Saffron border
            $borderThickness = max(3, intval($templateWidth * 0.006));
            for ($i=0; $i<$borderThickness; $i++) {
                imagearc($templateImg, $destX + $radius, $destY + $radius, $targetWidth + $i*2, $targetHeight + $i*2, 0, 360, $borderColor);
            }

            // Merge circular user image onto template
            imagecopy($templateImg, $circularUserImg, $destX, $destY, 0, 0, $targetWidth, $targetHeight);
            
            imagedestroy($userImg);
            imagedestroy($squareUserImg);
            imagedestroy($circularUserImg);
        }

        // --- Configuration for Text Placement ---
        $textColor = imagecolorallocate($templateImg, 12, 35, 90); // Richer Navy Blue
        $saffronColor = imagecolorallocate($templateImg, 230, 85, 10); // Richer Orange
        
        $nameFontSize = max(16, intval($templateWidth * 0.026 * $fontSizeFactorName)); 
        $msgFontSize = max(12, intval($templateWidth * 0.017 * $fontSizeFactorMsg)); 

        $nameY = $destY + $targetHeight + ($templateHeight * 0.06); 
        
        // Calculate bounding box for center alignment of NAME
        if (file_exists($fontPathName)) {
            $nameBox = imagettfbbox($nameFontSize, 0, $fontPathName, strtoupper($userName));
            $nameWidth = $nameBox[2] - $nameBox[0];
            $nameX = $boxStartX + ($boxWidth - $nameWidth) / 2;
            
            imagettftext($templateImg, $nameFontSize, 0, $nameX, $nameY, $textColor, $fontPathName, strtoupper($userName));
        } else {
            $nameX = $boxStartX + ($boxWidth * 0.1);
            imagestring($templateImg, 5, $nameX, $nameY, strtoupper($userName), $textColor);
        }

        // Add longer predefined motivated message
        $messages = [
            'msg1' => "May the tricolor always fly high! Let's honor our heroes by building a united, prosperous, and self-reliant India. Happy Independence Day!",
            'msg2' => "Tiranga hamesha uncha rahe! Aao hum sab milkar ek atmanirbhar, mazboot aur shantipurna Bharat ka nirmaan karein.",
            'msg3' => "Sare jahan se accha Hindustan hamara! Wishing you a very Happy Independence Day full of pride and joy.",
            'msg4' => "Let's celebrate the freedom we enjoy and remember the sacrifices of our brave freedom fighters. Happy 80th Independence Day!"
        ];

        $rawMsg = isset($messages[$language]) ? $messages[$language] : $messages['msg1'];
        
        $charsDivisor = 1.1;
        if ($fontStyle === 'greatvibes') {
            $charsDivisor = 0.55; // cursive is narrow, fit more characters per line
        } elseif ($fontStyle === 'dancingscript') {
            $charsDivisor = 0.75;
        }
        $charsPerLine = max(15, intval($boxWidth / ($msgFontSize * $charsDivisor))); 
        $msgWrapped = wordwrap($rawMsg, $charsPerLine, "\n");
        
        $msgY = $nameY + ($templateHeight * 0.045);

        if (file_exists($fontPathMsg)) {
            $lines = explode("\n", $msgWrapped);
            foreach($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $lineBox = imagettfbbox($msgFontSize, 0, $fontPathMsg, $line);
                $lineWidth = $lineBox[2] - $lineBox[0];
                $lineX = $boxStartX + ($boxWidth - $lineWidth) / 2;
                imagettftext($templateImg, $msgFontSize, 0, $lineX, $msgY, $saffronColor, $fontPathMsg, $line);
                $msgY += ($msgFontSize * $msgLineHeightFactor);
            }
        } else {
            $lines = explode("\n", $msgWrapped);
            foreach($lines as $line) {
                $line = trim($line);
                $lineX = $boxStartX + ($boxWidth * 0.05);
                imagestring($templateImg, 3, $lineX, $msgY, $line, $saffronColor);
                $msgY += 15;
            }
        }

        // Save the final image
        imagejpeg($templateImg, $outputPath, 90);
        imagedestroy($templateImg);

        return $outputFileName;
    }

    public static function createSocialFrame($userName, $userImagePath, $outputFileName, $frameTemplate, $imgX = -1, $imgY = -1, $imgScale = -1) {
        $fontPathName = __DIR__ . '/assets/fonts/Poppins-Bold.ttf';
        $templatePath = __DIR__ . '/templates/' . basename($frameTemplate);
        
        if (!file_exists($templatePath)) {
            $templatePath = __DIR__ . '/templates/frame1.jpg'; // Fallback
        }

        // Load the frame (we need the original frame with transparency for compositing)
        $ext = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION));
        if ($ext === 'png') {
            $frame = imagecreatefrompng($templatePath);
            // We KEEP transparency! Do not flatten to white here.
            imagealphablending($frame, true);
            imagesavealpha($frame, true);
        } else {
            $frame = imagecreatefromjpeg($templatePath);
        }

        if (!$frame) {
            throw new Exception("Could not load frame template.");
        }
        $frameWidth = imagesx($frame);
        $frameHeight = imagesy($frame);

        // Load and process user image
        $userImage = imagecreatefromjpeg($userImagePath);
        $srcW = imagesx($userImage);
        $srcH = imagesy($userImage);

        // Manual visual dragging mode
        if ($imgX != -1 && $imgY != -1 && $imgScale != -1) {
            $targetSize = intval($frameWidth * $imgScale); // imgScale is the diameter ratio
            $targetX = intval($frameWidth * $imgX);
            $targetY = intval($frameHeight * $imgY);
        } else {
            // Fallback Automatic Mode
            $frameBasename = basename($frameTemplate);
            $targetSize = intval($frameWidth * 0.48);
            $targetY = intval(($frameHeight - $targetSize) / 2) + 20;

            if ($frameBasename === 'frame4.png' || $frameBasename === 'frame5.png') {
                $targetSize = intval($frameWidth * 0.60);
                $targetY = intval(($frameHeight - $targetSize) / 2) - 30;
            }
            $targetX = intval(($frameWidth - $targetSize) / 2);
        }

        // 1. Crop original user image to a perfect square (simulating object-fit: cover)
        $srcAspect = $srcW / $srcH;
        if ($srcAspect > 1) { // Landscape
            $cropH = $srcH;
            $cropW = $srcH;
            $cropX = intval(($srcW - $cropW) / 2);
            $cropY = 0;
        } else { // Portrait or square
            $cropW = $srcW;
            $cropH = $srcW;
            $cropX = 0;
            $cropY = intval(($srcH - $cropH) / 2);
        }

        $squareUserImage = imagecreatetruecolor($targetSize, $targetSize);
        imagecopyresampled($squareUserImage, $userImage, 0, 0, $cropX, $cropY, $targetSize, $targetSize, $cropW, $cropH);

        // 2. Create circular mask
        $circularUserImage = imagecreatetruecolor($targetSize, $targetSize);
        imagealphablending($circularUserImage, false);
        imagesavealpha($circularUserImage, true);
        $transparent = imagecolorallocatealpha($circularUserImage, 0, 0, 0, 127);
        imagefill($circularUserImage, 0, 0, $transparent);

        for ($x = 0; $x < $targetSize; $x++) {
            for ($y = 0; $y < $targetSize; $y++) {
                $dx = $x - ($targetSize / 2);
                $dy = $y - ($targetSize / 2);
                if (($dx * $dx) + ($dy * $dy) <= ($targetSize / 2) * ($targetSize / 2)) {
                    $color = imagecolorat($squareUserImage, $x, $y);
                    imagesetpixel($circularUserImage, $x, $y, $color);
                }
            }
        }

        // Draw the circular user image ON TOP of the frame
        imagealphablending($frame, true);
        imagecopy($frame, $circularUserImage, $targetX, $targetY, 0, 0, $targetSize, $targetSize);

        // Save output
        $outputPath = __DIR__ . '/generated/' . $outputFileName;
        imagejpeg($frame, $outputPath, 95);

        // Cleanup
        imagedestroy($frame);
        imagedestroy($userImage);
        imagedestroy($squareUserImage);
        imagedestroy($circularUserImage);

        return $outputFileName;
    }

    private static function createFallbackTemplate($path) {
        $img = imagecreatetruecolor(1200, 800);
        $white = imagecolorallocate($img, 255, 255, 255);
        $saffron = imagecolorallocate($img, 255, 153, 51);
        $green = imagecolorallocate($img, 19, 136, 8);
        $blue = imagecolorallocate($img, 0, 0, 128);

        imagefill($img, 0, 0, $white);
        imagefilledrectangle($img, 0, 0, 600, 266, $saffron);
        imagefilledrectangle($img, 0, 266, 600, 533, $white);
        imagefilledrectangle($img, 0, 533, 600, 800, $green);
        imagearc($img, 300, 400, 200, 200, 0, 360, $blue);
        imagestring($img, 5, 50, 50, "HAPPY INDEPENDENCE DAY", $white);
        
        imagejpeg($img, $path, 90);
        imagedestroy($img);
    }
}
?>
