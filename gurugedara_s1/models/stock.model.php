<?php

class stockModel{

    static public function mdlShowStock($table, $item, $value){

        echo '<script>console.log("StockModel::mdlShowStock method called")</script>';

		if($item != null){

			$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item ORDER BY id ASC");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

            echo '<script>console.log("item is not null")</script>';

			return $stmt -> fetch();

		}else{

			$stmt = Connection::connect()->prepare("SELECT * FROM $table ORDER BY id ASC");

			$stmt -> execute();

            echo '<script>console.log("item is null")</script>';

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

    static public function mdlAddStock($data) {

        echo "<script>console.log('mdlAddStock called')</script>";

        try {
            // Update product stock
            $stmtUpdate = Connection::connect()->prepare("UPDATE products SET stock = :currentStock WHERE code = :productCode");
            $stmtUpdate->bindParam(":currentStock", $data["currentStock"], PDO::PARAM_INT);
            $stmtUpdate->bindParam(":productCode", $data["productCode"], PDO::PARAM_STR);
        
            if (!$stmtUpdate->execute()) {
                return "error";
            }
        
            // Insert into stock history
            $stmtInsert = Connection::connect()->prepare("INSERT INTO stock(productCode, productName, currentStock, previousStock, newStock, date, time) VALUES (:productCode, :productName, :currentStock, :previousStock, :newStock, :date, :time)");
        
            $stmtInsert->bindParam(":productCode", $data["productCode"], PDO::PARAM_STR);
            $stmtInsert->bindParam(":productName", $data["productName"], PDO::PARAM_STR);
            $stmtInsert->bindParam(":currentStock", $data["currentStock"], PDO::PARAM_INT);
            $stmtInsert->bindParam(":previousStock", $data["previousStock"], PDO::PARAM_INT);
            $stmtInsert->bindParam(":newStock", $data["newStock"], PDO::PARAM_INT);
            $stmtInsert->bindParam(":date", $data["date"], PDO::PARAM_STR);
            $stmtInsert->bindParam(":time", $data["time"], PDO::PARAM_STR);
        
            if ($stmtInsert->execute()) {
                return "ok";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            return "error";
        }
        
        // No need to close the statement manually
        $stmtUpdate = null;
        $stmtInsert = null;
        
    }

}

?>