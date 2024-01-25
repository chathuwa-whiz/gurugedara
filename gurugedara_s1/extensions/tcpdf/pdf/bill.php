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
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('P', '');
        $pdf->SetAutoPageBreak(true);

        // Header
        $blockHeader = <<<HTML
            <table style="font-size:12px; text-align:center; width:100%; ">
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
                    <td>070 3 273 747 / 077 2 213793 <br>Date: $saledate <br></td>
                </tr>
                <tr>
                    <td>Customer name: {$answerCustomer['name']} &nbsp; Seller: {$answerSeller['name']}<br></td>
                </tr>
            </table>
HTML;
        $pdf->writeHTML($blockHeader, false, false, false, false, '');

        // All details in a single table
        $blockAllDetails = <<<HTML
            <table style="font-size:10px; width:100%; border-collapse: collapse; margin-bottom: 5px;">
                <tr>
                    <td><b>Code</b></td>
                    <td><b>Description</b></td>
                    <td><b>Quantity</b></td>
                    <td><b>Unit Price</b></td>
                    <td><b>Amount</b></td>
                </tr>
HTML;

        // Loop products and display details
        foreach ($products as $key => $item) {
            // Check if the keys exist before accessing them
            $itemcode = isset($item['code']) ? $item['code'] : '';
            $qty = isset($item['quantity']) ? $item['quantity'] : '';
            $description = $item['description'];

            $unitValue = number_format($item["price"], 2);
            $unitTotalValue = number_format($item["totalPrice"], 2);

            // Add the product row to the PDF
            $blockAllDetails .= <<<HTML
                <tr>
                    <td style="border: 1px solid #ddd; padding: 3px;">{$itemcode}</td>
                    <td style="border: 1px solid #ddd; padding: 3px;">{$description}</td>
                    <td style="border: 1px solid #ddd; padding: 3px; text-align: center;">{$qty}</td>
                    <td style="border: 1px solid #ddd; padding: 3px; text-align: center;">{$unitValue}</td>
                    <td style="border: 1px solid #ddd; padding: 3px; text-align: center;">{$unitTotalValue}</td>
                </tr>
HTML;
        }
        // Display the content (this is testing purposes only)
        // echo '<pre>';
        // print_r($products);
        // echo '</pre>';


        // Close the table
        $blockAllDetails .= '</table>';

        $pdf->writeHTML($blockAllDetails, false, false, false, false, '');

        // Amount details block
        $blockAmountDetails = <<<HTML
            <table style="font-size:12px; text-align:center; width:100%; margin-top: 20px; border-collapse: collapse; border-spacing: 0;">
                <tr>
                    <td colspan="2" style="font-weight: bold; padding-bottom: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding: 8px;">Items Value:</td>
                    <td style="padding: 8px;">{$netPrice}</td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><b>Discount: </b></td>
                    <td style="padding: 8px;"><b>{$discount} ({$discountPercentage}%)</b></td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><b>Total Amount: </b></td>
                    <td style="padding: 8px;"><b>{$totalPrice}</b></td>
                </tr>
                <tr>
                    <td colspan="2" style="height: 15px;"></td>
                </tr>
                <tr>
                    <td style="padding: 8px;">Cash:</td>
                    <td style="padding: 8px;">{$cashin}</td>
                </tr>
                <tr>
                    <td style="padding: 8px;">Balance:</td>
                    <td style="padding: 8px;">{$balance}</td>
                </tr>
            </table>
HTML;

        $pdf->writeHTML($blockAmountDetails, false, false, false, false, '');

        // Footer: Thank you come again!
        $blockFooter = <<<EOF
            <table style="font-size:10px; text-align:center; width:100%;">
                <tr>
                    <td><br><br><br><br><br><b>Thank you come again!</b></td>	
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

