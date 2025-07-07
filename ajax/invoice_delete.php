<?php
include("../include/dbConnection.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // First delete line items and their taxes
    $deleteLineItems = mysqli_query($connection, "DELETE FROM invoice_line_items WHERE invoice_id = $id");
    $deleteTaxes = mysqli_query($connection, "DELETE FROM line_item_taxes WHERE line_item_id IN (SELECT id FROM invoice_line_items WHERE invoice_id = $id)");

    // Then delete the invoice
    $deleteInvoice = mysqli_query($connection, "DELETE FROM invoices WHERE id = $id");

    if ($deleteInvoice) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($connection)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
}
