<?php

require 'vendor/autoload.php';

use setasign\Fpdi\Fpdi;

class PDFWithWatermark extends FPDI
{
    function Header()
    {
        // Ensure no header is printed
    }

    function Footer()
    {
        // Ensure no footer is printed
    }
}

// Initialize FPDI
$pdf = new PDFWithWatermark();

// Path to the existing PDF and the watermark image
$pdfPath = 'src/assets/Gallarde_Jasmin-BSIT.pdf';
$watermarkText = 'CVSU PROPERTY'; // Example text watermark

// Import the PDF
$pageCount = $pdf->setSourceFile($pdfPath);

// Set font for watermark text
$pdf->SetFont('Arial', 'B', 80);

// Set a light gray color for the watermark text to simulate opacity
$pdf->SetTextColor(119, 189, 135); // Light gray color

// Loop through each page
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $templateId = $pdf->importPage($pageNo);
    $size = $pdf->getTemplateSize($templateId);

    $pdf->AddPage();
    $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);

    // Position for the text watermark
    $pdf->SetXY(30, $size['height'] / 2);
    $pdf->Cell(0, 0, $watermarkText, 0, 0, 'C', 0);
}

// Output the new PDF with watermark
$pdf->Output('I', 'watermarked.pdf');
