<?php

require_once "../../../controllers/sales.controller.php";
require_once "../../../models/sales.model.php";

require_once "../../../controllers/customers.controller.php";
require_once "../../../models/customers.model.php";

require_once "../../../controllers/users.controller.php";
require_once "../../../models/users.model.php";

require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

class printBill{

public $code;

public function getBillPrinting(){

//WE BRING THE INFORMATION OF THE SALE

$itemSale = "code";
$valueSale = $this->code;

$answerSale = ControllerSales::ctrShowSales($itemSale, $valueSale);

$saledate = substr($answerSale["saledate"],0,-8);
$products = json_decode($answerSale["products"], true);
$discount = number_format($answerSale["discount"],2);
$discountPercentage = number_format($answerSale["discountPercentage"],2);
$totalPrice = number_format($answerSale["totalPrice"],2);
$netPrice = number_format($answerSale["netItemsPrice"],2);
$cashin = number_format($answerSale["cashin"],2);
$balance = number_format($answerSale["balance"],2);  

//TRAEMOS LA INFORMACIÓN DEL Customer

$itemCustomer = "id";
$valueCustomer = $answerSale["idCustomer"];

$answerCustomer = ControllerCustomers::ctrShowCustomers($itemCustomer, $valueCustomer);

//TRAEMOS LA INFORMACIÓN DEL Seller

$itemSeller = "id";
$valueSeller = $answerSale["idSeller"];

$answerSeller = ControllerUsers::ctrShowUsers($itemSeller, $valueSeller);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage('P', 'A6');

//---------------------------------------------------------


// ---------------------------------------------------------
// ...

// Calculate the height needed for product details
$productHeight = count($products) * 15; // Adjust the height based on your content and styling

// Set the maximum height for products on a page
$maxProductHeightPerPage = 500; // Adjust this based on your layout

// Define $block1 and $block3 variables
$block1 = '';
$block3 = '';

// Check if the product height exceeds the maximum height per page
if ($productHeight > $maxProductHeightPerPage) {
    // If the product height exceeds the maximum, you may want to handle this differently
    // (e.g., display a message, split into multiple pages, etc.)
    // Currently, it will still try to fit all products on a single page
}

// Add a new page
// $pdf->AddPage('P', 'A7');

// Header: Guru Gedara Publication and Bookshop
$blockHeader = <<<HTML
    <table style="font-size:12px; text-align:center; width:100%;">
        <tr>
            <td><b>Guru Gedara Publication and Bookshop</b></td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockHeader, false, false, false, false, '');

// Logo (Assuming you have an image file named 'logo.png' in the same directory as your script)
/*$logoPath = 'logo.png';
$blockLogo = <<<HTML
    <table style="width:100%;">
        <tr>
            <td style="text-align:center;"><img src="$logoPath" alt="Logo" style="width:100px; height:100px;"></td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockLogo, false, false, false, false, ''); */

// Address: Negombo rd, Dambadeniya
$blockAddress = <<<HTML
    <table style="font-size:10px; text-align:center; width:100%;">
        <tr>
            <td>Negombo rd, Dambadeniya</td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockAddress, false, false, false, false, '');

// Main Branch Polgahawela
$blockBranch = <<<HTML
    <table style="font-size:10px; text-align:center; width:100%;">
        <tr>
            <td>Main Branch Polgahawela</td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockBranch, false, false, false, false, '');

// Contact: 070 3 273 747 / 077 2 213793
$blockContact = <<<HTML
    <table style="font-size:10px; text-align:center; width:100%;">
        <tr>
            <td>070 3 273 747 / 077 2 213793 <br></td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockContact, false, false, false, false, '');

// Customer Names: Replace with actual variables
$blockCustomerNames = <<<HTML
    <table style="font-size:8px;  width:100%;">
        <tr>
            <td>Customer name: {$answerCustomer['name']} &nbsp;</td>
            <td>Seller: {$answerSeller['name']}<br><br></td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockCustomerNames, false, false, false, false, '');

// Item details header
$blockItemHeader = <<<HTML
    <table style="font-size:10px; width:100%;">
        <tr>
            <td>Itmcode</td>
            <td>Qty</td>
            <td>Unit Price</td>
            <td>Net Amount</td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockItemHeader, false, false, false, false, '');

// Initialize an empty variable to store item details
$itemDetailsBlock = '';

// Loop through products and display details
foreach ($products as $key => $item) {
    // Check if the keys exist before accessing them
    $itemcode = isset($item['id']) ? $item['id'] : '';
    $qty = isset($item['quantity']) ? $item['quantity'] : '';

    $unitValue = number_format($item["price"], 2);
    $unitTotalValue = number_format($item["totalPrice"], 2);

    $itemDetailsBlock .= <<<HTML
        <table style="font-size:10px; width:100%; border-collapse: collapse; margin-bottom: 5px;">
            <tr>
                <td style="border: 1px solid #ddd; padding: 3px; text-align: center; width: 20%;">{$itemcode}</td>
                <td style="border: 1px solid #ddd; padding: 3px; text-align: center; width: 20%;">{$qty}</td>
                <td style="border: 1px solid #ddd; padding: 3px; text-align: center; width: 30%;">{$unitValue}</td>
                <td style="border: 1px solid #ddd; padding: 3px; text-align: center; width: 30%;">{$unitTotalValue}</td>
            </tr>
        </table>
HTML;
}

// Display total amount, discount, net amount, cash, balance
$blockAmountDetails = <<<HTML
    <table style="font-size:10px; text-align:right; width:100%; margin-top: 10px;"><hr>
        <tr>
            <td style="width:30%;">>Item Value:</td>
            <td style="width:50%;">{$netPrice}</td>
        </tr>
        <tr>
            <td style="width:30%;">Discount:</td>
            <td style="width:50%;">{$discount} ({$discountPercentage}%)</td>
        </tr>
        <tr>
            <td style="width:30%;">Total Amount:</td>
            <td style="width:50%;">{$totalPrice}</td>
        </tr><br><br><br><hr>
        <tr>
            <td style="width:30%;">Cash:</td>
            <td style="width:50%;">{$cashin}</td>
        </tr>
        <tr>
            <td style="width:30%;">Balance:</td>
            <td style="width:50%;">{$balance}</td>
        </tr><hr>
    </table>
HTML;

// Combine the item details and additional details blocks
$combinedBlock = $itemDetailsBlock . $blockAmountDetails;

// Write the combined block to the PDF
$pdf->writeHTML($combinedBlock, false, false, false, false, '');

// Footer: Thank you

// Footer: Thank you come again!
$blockFooter = <<<EOF
<table style="font-size:9px; text-align:center; width:100%;">
    <tr>
        <td><br><br>Thank you come again!</td>
    </tr>
</table>
EOF;

$pdf->writeHTML($blockFooter, false, false, false, false, '');

// Output the PDF
$pdf->Output('bill.pdf');
}

}

$bill = new printBill();
$bill -> code = $_GET["code"];
$bill -> getBillPrinting();

?>