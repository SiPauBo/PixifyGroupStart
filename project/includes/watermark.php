<?php
function watermarkImage($sourceImage, $outputImage, $watermarkText = 'PIXIFY')
{
    try {
        // Load the image
        $image = new Imagick($sourceImage);
        $draw = new ImagickDraw();
        $pixel = new ImagickPixel('white');

        // Set watermark properties
        $draw->setFont('Arial');
        $draw->setFontSize(50);
        $draw->setFillColor($pixel);
        $draw->setGravity(Imagick::GRAVITY_SOUTHEAST);
        $draw->setStrokeColor('black');
        $draw->setStrokeWidth(1);
        $draw->setTextAntialias(true);

        // Add the watermark text
        $image->annotateImage($draw, 10, 10, 0, $watermarkText);

        // Adjust transparency
        $image->evaluateImage(Imagick::EVALUATE_MULTIPLY, 0.8, Imagick::CHANNEL_ALPHA);

        // Save the output image
        $image->writeImage($outputImage);

        // Clear resources
        $image->destroy();

        echo "Watermark added successfully to {$outputImage}";
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Example usage
$sourceImage = 'input.jpg';  // Replace with your input image
$outputImage = 'output.jpg'; // Output filename
watermarkImage($sourceImage, $outputImage);
?>
