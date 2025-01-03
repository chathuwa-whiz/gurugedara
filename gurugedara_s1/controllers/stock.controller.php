<?php

class controllerStocks{

    static public function ctrShowStock($item, $value){

        echo '<script>console.log("ControllerStock::ctrShowStock method called")</script>';

		$table = "stock";

		$answer = stockModel::mdlShowStock($table, $item, $value);

		return $answer;
	}

    static public function ctrAddStock() {

        if(isset($_POST["productCode"])) {

            $productName = isset($_POST["productName"]) ? $_POST["productName"] : '';
            $productCode = isset($_POST["productCode"]) ? $_POST["productCode"] : '';
            $currentStock = isset($_POST["currentStock"]) ? (int)$_POST["currentStock"] : 0;
            $newStock = isset($_POST["newStock"]) ? (int)$_POST["newStock"] : 0;

            $data = array(
                "productName" => $productName,
                "productCode" => $productCode,
                "previousStock" => $currentStock,  // current stock
                "newStock" => $newStock,            // newly added stock
                "currentStock" => $currentStock + $newStock,    // updated stock value
                "date" => date('Y-m-d'),
                "time" => date('H:i:s')
            );

            // console log prints
            $calculatedStock = $currentStock + $newStock;

            echo "<script>console.log('".$productName."')</script>";
            echo "<script>console.log('".$productCode."')</script>";
            echo "<script>console.log('".$currentStock."')</script>";
            echo "<script>console.log('".$newStock."')</script>";
            echo "<script>console.log('".$calculatedStock."')</script>";
            echo "<script>console.log('".date('Y-m-d')."')</script>";
            echo "<script>console.log('".date('H:i:s')."')</script>";

		    $answer = stockModel::mdlAddStock($data);

            echo "<script>console.log('ctrAddStock called')</script>";

        }

        else {
            echo "<script>console.log('ctrAddStock not called')</script>";
        }
    }

}

?>