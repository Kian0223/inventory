<?php
require_once('tcpdf/tcpdf.php');
// Extend the TCPDF class to create custom Header and Footer
class NEWTCPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = 'phplogin/kishii_logo.png';
        $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'Kishii Total Sales', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$creationDate = date('Y-m-d'); // Get the current date in 'YYYY-MM-DD' format

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Kishii');
$pdf->SetTitle('Santos Group of Companies- ' . $creationDate);
$pdf->SetSubject('Sales');
$pdf->SetKeywords('');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('dejavusans', '', 9);

// add a page
$pdf->AddPage();

// Retrieve the sold items from the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$summarySql = "SELECT sku, brand, model, size, price, quantity FROM sold_items1 GROUP BY sku";
$summaryResult = $conn->query($summarySql);

if ($summaryResult->num_rows > 0) {
    // Create a table to display the sold items
    $html = '<h2>Kishii Total Sales</h2>'; // Add the heading
    $html .= '<p>Creation Date: ' . $creationDate . '</p>';
    $html .= '<table>
                <tr>
                    <th>SKU</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>';

    while ($summaryRow = $summaryResult->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $summaryRow['sku'] . '</td>';
        $html .= '<td>' . $summaryRow['brand'] . '</td>';
        $html .= '<td>' . $summaryRow['model'] . '</td>';
        $html .= '<td>' . $summaryRow['size'] . '</td>';
        $html .= '<td>' . $summaryRow['price'] . '</td>';
        $html .= '<td>' . $summaryRow['quantity'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
} else {
    $pdf->writeHTML('<p>No sold items yet</p>', true, false, true, false, '');
}

// Close the database connection
$conn->close();

// Close and output the PDF
$pdf->Output('sold_items_kishii.pdf', 'D');
?>
