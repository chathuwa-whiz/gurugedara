<?php
// Include necessary files
require_once '../models/stockModel.php';  // Adjust the path as needed
require_once '../controllers/stockController.php';  // Adjust the path as needed

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $idProduct = isset($_POST['idProduct']) ? $_POST['idProduct'] : '';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

    // echo "<script>console.log('idProduct = ". $_POST['idProduct'] ." quantity = ". $_POST['quantity'] ."')</script>";

    // Validate input
    if (empty($idProduct) || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit();
    }

    // Create an instance of the stockController
    $stockController = new StockController();

    // Call the method to update stock
    $result = $stockController->updateStock($idProduct, $quantity);

    // Send response
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Stock updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update stock']);
    }
}
