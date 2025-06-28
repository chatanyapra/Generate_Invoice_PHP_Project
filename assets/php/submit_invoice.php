<?php
header('Content-Type: application/json');

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Basic validation
if (empty($data['invoice_date'])) {
    echo json_encode(['success' => false, 'message' => 'Invoice date is required']);
    exit;
}

if (empty($data['invoice_number'])) {
    echo json_encode(['success' => false, 'message' => 'Invoice number is required']);
    exit;
}

if (empty($data['buyer_name'])) {
    echo json_encode(['success' => false, 'message' => 'Buyer name is required']);
    exit;
}

if (empty($data['buyer_address'])) {
    echo json_encode(['success' => false, 'message' => 'Buyer address is required']);
    exit;
}

if (empty($data['line_items']) || count($data['line_items']) === 0) {
    echo json_encode(['success' => false, 'message' => 'At least one line item is required']);
    exit;
}

// Include database connection
require_once('../../include/dbConnection.php');

try {
    // Start transaction
    $connection->begin_transaction();

    // Calculate total invoice value
    $taxableValue = 0;
    foreach ($data['line_items'] as $item) {
        $taxableValue += $item['taxableValue'];
    }

    $cgstAmount = $taxableValue * ($data['cgst_rate'] / 100);
    $sgstAmount = $taxableValue * ($data['sgst_rate'] / 100);
    $totalInvoiceValue = $taxableValue + $cgstAmount + $sgstAmount;

    // Insert invoice
    $stmt = $connection->prepare("
        INSERT INTO invoices (
            invoice_number, invoice_date, transaction_type, input_mode, eway_bill,
            buyer_id, buyer_name, buyer_address, buyer_gstin, buyer_state_code,
            tax_type, total_invoice_value
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $taxType = $data['type'] === 'inter-city' ? 'IGST' : 'CGST+SGST';

    $stmt->bind_param(
        "sssssisssisd",
        $data['invoice_number'],
        $data['invoice_date'],
        $data['type'],
        $data['mode'],
        $data['eway_bill'],
        $data['customer_id'],
        $data['buyer_name'],
        $data['buyer_address'],
        $data['buyer_gstin'],
        $data['buyer_state_code'],
        $taxType,
        $totalInvoiceValue
    );


    $stmt->execute();
    $invoiceId = $connection->insert_id;
    $stmt->close();

    // Insert line items
    foreach ($data['line_items'] as $item) {
        $stmt = $connection->prepare("
            INSERT INTO invoice_line_items (
                invoice_id, hsn_sac_code, description, quantity, unit, 
                rate, taxable_value
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "issdsdd",
            $invoiceId,
            $item['hsn_sac_code'],
            $item['description'],
            $item['quantity'],
            $item['unit'],
            $item['rate'],
            $item['taxableValue']
        );

        $stmt->execute();
        $lineItemId = $connection->insert_id;
        $stmt->close();

        // Insert taxes for line item
        if ($data['type'] === 'inter-city') {
            // IGST
            $stmt = $connection->prepare("
                INSERT INTO line_item_taxes (
                    line_item_id, tax_name, tax_rate, tax_amount
                ) VALUES (?, 'IGST', ?, ?)
            ");
            $igstRate = $data['cgst_rate'] + $data['sgst_rate'];
            $igstAmount = $item['taxableValue'] * ($igstRate / 100);
            $stmt->bind_param("idd", $lineItemId, $igstRate, $igstAmount);
            $stmt->execute();
            $stmt->close();
        } else {
            // CGST + SGST
            $stmt = $connection->prepare("
                INSERT INTO line_item_taxes (
                    line_item_id, tax_name, tax_rate, tax_amount
                ) VALUES (?, 'CGST', ?, ?), (?, 'SGST', ?, ?)
            ");
            $cgstAmount = $item['taxableValue'] * ($data['cgst_rate'] / 100);
            $sgstAmount = $item['taxableValue'] * ($data['sgst_rate'] / 100);
            $stmt->bind_param(
                "iddidd",
                $lineItemId,
                $data['cgst_rate'],
                $cgstAmount,
                $lineItemId,
                $data['sgst_rate'],
                $sgstAmount
            );
            $stmt->execute();
            $stmt->close();
        }
    }

    // Commit transaction
    $connection->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Invoice created successfully',
        'invoice_id' => $invoiceId,
        'invoice_number' => $data['invoice_number']
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $connection->rollback();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
