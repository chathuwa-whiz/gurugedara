<?php

require_once "../../../controllers/sales.controller.php";
require_once "../../../models/sales.model.php";

require_once "../../../controllers/customers.controller.php";
require_once "../../../models/customers.model.php";

require_once "../../../controllers/users.controller.php";
require_once "../../../models/users.model.php";

require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

require_once('tcpdf_include.php');

class printBill
{
    public $code;

    public function getBillPrinting()
    {
        //WE BRING THE INFORMATION OF THE SALE
        $itemSale = "code";
        $valueSale = $this->code;
        $answerSale = ControllerSales::ctrShowSales($itemSale, $valueSale);

        $saledate = substr($answerSale["saledate"], 0, -8);
        $saletime = substr($answerSale["saledate"], 10);
        $products = json_decode($answerSale["products"], true);
        $discount = number_format($answerSale["discount"], 2);
        $discountPercentage = number_format($answerSale["discountPercentage"], 2);
        $totalPrice = number_format($answerSale["totalPrice"], 2);
        $netPrice = number_format($answerSale["netItemsPrice"], 2);
        $cashin = number_format($answerSale["cashin"], 2);
        $balance = number_format($answerSale["balance"], 2);

        //TRAEMOS LA INFORMACIÓN DEL Customer
        $itemCustomer = "id";
        $valueCustomer = $answerSale["idCustomer"];
        $answerCustomer = ControllerCustomers::ctrShowCustomers($itemCustomer, $valueCustomer);

        //TRAEMOS LA INFORMACIÓN DEL Seller
        $itemSeller = "id";
        $valueSeller = $answerSale["idSeller"];
        $answerSeller = ControllerUsers::ctrShowUsers($itemSeller, $valueSeller);

        //REQUERIMOS LA CLASE TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(76.2, 101.6), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', '');
        $pdf->SetAutoPageBreak(true); // Disable auto page break

        // Header
        $blockHeader = <<<HTML
            <table style="font-size:8px; text-align:center; width:100%; ">
                <tr>
                    <td><b>Guru Gedara Publication and Bookshop</b></td>
                </tr>
                <tr>
                    <td>Negombo rd, Dambadeniya</td>
                </tr>
                <tr>
                    <td>Main Branch Polgahawela</td>
                </tr>
                <tr>
                    <td>070 3 273 747 / 077 2 213793 <br>Date: $saledate &nbsp; &nbsp;  Time: $saletime <br> Bill No: {$valueSale}</td>
                </tr>
                <tr>
                    <td>Customer name: {$answerCustomer['name']} &nbsp;&nbsp;&nbsp; Seller: {$answerSeller['name']}<br></td>
                </tr>
            </table>
HTML;
       $pdf->writeHTML($blockHeader, false, false, false, false, '');

        // All details in a single table
        $blockAllDetails = <<<HTML
            <table style="font-size:6px; width:100%; border-collapse: collapse; margin-bottom: 2px;">
                <tr>
                    <td style="width:15px"><b>#</b></td>
                    <td style="width:40px"><b>Name</b></td>
                    <td style="width:20px"><b>Qty</b></td>
                    <td><b>Unit Price</b></td>
                    <td><b>Our Price</b></td>
                    <td style="width:30px"><b>Amount</b></td>
                </tr>
HTML;

        // Loop products and display details
        foreach ($products as $key => $item) {
            // Check if the keys exist before accessing them
            $itemcode = isset($item['code']) ? $item['code'] : '';
            $qty = isset($item['quantity']) ? $item['quantity'] : '';
            $description = $item['description'];

            $unitValue = number_format($item["price"], 2);
            $ourPrice = number_format($item["ourPrice"], 2);
            $unitTotalValue = number_format($item["totalPrice"], 2);
            $index = number_format($item["index"]);

            // Add the product row to the PDF
            $blockAllDetails .= <<<HTML
                <tr>
                    <td style="border: 1px solid #ddd; padding: 1px;">{$index}</td> 
                    <td style="border: 1px solid #ddd; padding: 1px;">{$description}</td>
                    <td style="border: 1px solid #ddd; padding: 1px; text-align: center;">{$qty}</td>
                    <td style="border: 1px solid #ddd; padding: 1px; text-align: center;">{$unitValue}</td>
                    <td style="border: 1px solid #ddd; padding: 1px; text-align: center;">{$ourPrice}</td>
                    <td style="border: 1px solid #ddd; padding: 1px; text-align: center;">{$unitTotalValue}</td>
                </tr>
HTML;
        }

        // Close the table
        $blockAllDetails .= '</table>';

       $pdf->writeHTML($blockAllDetails, false, false, false, false, '');

       // Amount details block
$blockAmountDetails = <<<HTML
<table style="font-size:8px; text-align:center; width:100%; margin-top: 1px; border-collapse: collapse; border-spacing: 0;">
    <tr>
        <td colspan="2" style="font-weight: bold; padding-bottom: 1px;"></td>
    </tr>
    <tr>
        <td style="padding: 1px; text-align: right;">Items Value:</td>
        <td style="padding: 1px; text-align: right;">{$netPrice}</td>
    </tr>
    <tr>
        <td style="padding: 1px; text-align: right;"><b>Discount: </b></td>
        <td style="padding: 1px; text-align: right;"><b>{$discount}</b></td>
    </tr>
    <tr><td colspan="2"><hr></td></tr> 
    <tr>
    <td style="padding: 1px; text-align: right;"><b>Total Amount: </b></td>
        <td style="padding: 1px; text-align: right;"><b>{$totalPrice}</b></td>
    </tr>
    <tr>
        <td colspan="2" style="height: 3px;"></td>
    </tr>
    <tr>
        <td style="padding: 1px; text-align: right;">Cash:</td>
        <td style="padding: 1px; text-align: right;">{$cashin}</td>
    </tr>
    <tr>
        <td style="padding: 1px; text-align: right;">Balance:</td>
        <td style="padding: 1px; text-align: right;">{$balance}</td>
    </tr>
</table>
HTML;

$pdf->writeHTML($blockAmountDetails, false, false, false, false, '');

// Footer: Thank you come again!
$blockFooter = <<<EOF
<table style="font-size:8px; text-align:center; width:100%; height: 100%;">
    <tr>
        <td>
            <hr>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: middle; height: 100%;">
            <b style="display: inline-block; vertical-align: middle; color: black;">THANK YOU <br> COME AGAIN! <br> POWERD BY AVCEDITS </b>
        </td>
    </tr>
</table>
EOF;

$pdf->writeHTML($blockFooter, false, false, false, false, '');
        // Output 

        $pdf->Output('bill.pdf');
    }
}

$bill = new printBill();
$bill->code = $_GET["code"];
$bill->getBillPrinting();
?>
