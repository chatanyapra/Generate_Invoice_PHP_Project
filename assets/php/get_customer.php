<?php
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Customer ID required']);
    exit;
}

require_once('../../include/db_connection.php');

$customerId = (int)$_GET['id'];

try {
    $stmt = $connection->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $customer]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Customer not found']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>