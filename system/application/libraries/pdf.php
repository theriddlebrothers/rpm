<?php
require('html2pdf/html2fpdf.php');

class PDF extends HTML2FPDF {

    function Footer() {

        // Footer
        $this->SetXY(10, 285);
        $this->SetFont('arial', '', 9);
        $this->setTextColor(100, 100, 100);
        $this->Cell(190, 3, '964 Valley View Drive, Winchester VA 22603 - www.theRiddleBrothers.com - contact@theriddlebrothers.com - 410-696-4690', 0, 0, 'C', false);

    }
}