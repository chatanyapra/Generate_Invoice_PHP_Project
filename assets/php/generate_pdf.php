<?php
require_once('./include/dbConnection.php');

$invoiceId = $_GET['invoice_id'] ?? null;

if (!$invoiceId) {
    die('Invoice ID is required');
}

// Fetch invoice data
$stmt = $connection->prepare("
    SELECT i.*, 
           b.business_name, b.address as business_address, b.city as business_city, 
           b.state as business_state, b.state_code as business_state_code, b.gstin as business_gstin,
           b.pan_number, b.bank_name, b.account_number, b.branch_ifsc
    FROM invoices i
    JOIN business_profile b ON 1=1
    WHERE i.id = ?
");
$stmt->bind_param("i", $invoiceId);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$invoice) {
    die('Invoice not found');
}

// Fetch line items
$stmt = $connection->prepare("
    SELECT * FROM invoice_line_items 
    WHERE invoice_id = ?
");
$stmt->bind_param("i", $invoiceId);
$stmt->execute();
$lineItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch taxes
$stmt = $connection->prepare("
    SELECT lit.* FROM line_item_taxes lit
    JOIN invoice_line_items ili ON lit.line_item_id = ili.id
    WHERE ili.invoice_id = ?
");
$stmt->bind_param("i", $invoiceId);
$stmt->execute();
$taxes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate totals
$taxableValue = array_sum(array_column($lineItems, 'taxable_value'));
$cgstAmount = $invoice['cgst_amount'];
$sgstAmount = $invoice['sgst_amount'];
$totalInvoice = $invoice['total_invoice_value'];

// Format date
$invoiceDate = new DateTime($invoice['invoice_date']);
$formattedDate = $invoiceDate->format('d-M-y');

// Extract city from buyer address
$addressParts = explode(',', $invoice['buyer_address']);
$city = count($addressParts) > 1 ? trim($addressParts[count($addressParts) - 2]) : '';

// PHP function to convert number to words (Indian format)
function numberToWords($number)
{
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => '',
        '1' => 'One',
        '2' => 'Two',
        '3' => 'Three',
        '4' => 'Four',
        '5' => 'Five',
        '6' => 'Six',
        '7' => 'Seven',
        '8' => 'Eight',
        '9' => 'Nine',
        '10' => 'Ten',
        '11' => 'Eleven',
        '12' => 'Twelve',
        '13' => 'Thirteen',
        '14' => 'Fourteen',
        '15' => 'Fifteen',
        '16' => 'Sixteen',
        '17' => 'Seventeen',
        '18' => 'Eighteen',
        '19' => 'Nineteen',
        '20' => 'Twenty',
        '30' => 'Thirty',
        '40' => 'Forty',
        '50' => 'Fifty',
        '60' => 'Sixty',
        '70' => 'Seventy',
        '80' => 'Eighty',
        '90' => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ? "and " . $words[floor($point / 10) * 10] . " " . $words[$point = $point % 10] . " Paise" : '';
    return trim($result) . "Rupees " . $points;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= $invoice['invoice_number'] ?></title>
    <style>
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11px;
            color: black;
            background: white;
            padding: 2rem;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px 8px;
            vertical-align: top;
        }

        .no-top-border {
            border-top: none;
        }

        .no-top-border th,
        .no-top-border td {
            border-top: none;
        }
    </style>
</head>

<body>
    <div id="invoice-content">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <div class="w-[40%]"></div>
            <div class="flex justify-between items-center w-[60%]">
                <div class="text-center font-bold text-[16px] tracking-wide">Tax Invoice</div>
                <i id="invoice-title" class="text-right text-xs">(ORIGINAL FOR RECIPIENT)</i>
            </div>
        </div>

        <!-- Main Info Block -->
        <div class="grid grid-cols-2 border border-black h-[220px]">
            <!-- Left Column: Seller and Buyer Info -->
            <div class="py-1 border-r border-black text-xs px-0.5">
                <div class="font-bold"><?= $invoice['business_name'] ?></div>
                <div><?= $invoice['business_address'] ?></div>
                <div><?= $invoice['business_city'] ?></div>
                <div class="">GSTIN/UIN: <?= $invoice['business_gstin'] ?></div>
                <div>State Name : <?= $invoice['business_state'] ?>, Code : <?= $invoice['business_state_code'] ?></div>
                <hr class="my-0.5 border-t border-black">
                <div class="font-bold">Buyer (Bill to)</div>
                <div class="font-bold mt-1"><?= $invoice['buyer_name'] ?></div>
                <div class="font-bold"><?= strtoupper($city) ?></div>
                <div class="mt-1">GSTIN/UIN: <?= $invoice['buyer_gstin'] ?? 'N/A' ?></div>
                <div>State Name: <?= $invoice['buyer_state_code'] == '09' ? 'Uttar Pradesh, Code : 09' : 'Maharashtra, Code : 27' ?></div>
            </div>

            <!-- Right Column: Invoice Metadata Table -->
            <div>
                <table class="text-xs">
                    <tbody>
                        <tr class="divide-x divide-black">
                            <td class="w-1/2 border-0 border-b border-black">
                                <p>Invoice No.</p>
                                <strong class="text-sm font-bold"><?= $invoice['invoice_number'] ?></strong>
                            </td>
                            <td class="w-1/2 border-0 border-b border-r-0 border-black">
                                <p>Dated</p>
                                <strong class="text-sm font-bold"><?= $formattedDate ?></strong>
                            </td>
                        </tr>
                        <tr class="divide-x divide-black text-[11px]">
                            <td class="border-b border-black">
                                <p>Delivery Note</p>
                                <div class="h-4"></div>
                            </td>
                            <td class="border-b border-r-0 border-black">
                                <p>Mode/Terms of Payment</p>
                                <div class="h-4"></div>
                            </td>
                        </tr>
                        <tr class="divide-x divide-black text-[11px]">
                            <td class="border-b border-black">
                                <p>Buyer's Order No.</p>
                                <div class="h-4"></div>
                            </td>
                            <td class="border-b border-r-0 border-black">
                                <p>Dated</p>
                                <div class="h-4"></div>
                            </td>
                        </tr>
                        <tr class="divide-x divide-black text-[11px]">
                            <td colspan="2" class="border-b-0 border-r-0">
                                <p>Terms of Delivery</p>
                                <div class="h-4"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Items Table -->
        <table class="border border-black border-t-0">
            <thead>
                <tr class="divide-x divide-black text-center font-bold">
                    <td class="w-[5%]">Sl No.</td>
                    <td class="w-[35%]">Description of Goods</td>
                    <td class="w-[10%]">HSN/SAC</td>
                    <td class="w-[11%]">Quantity</td>
                    <td class="w-[11%]">Rate</td>
                    <td class="w-[8%]">per</td>
                    <td class="w-[15%]">Amount</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lineItems as $index => $item):
                    $itemTaxes = array_filter($taxes, function ($tax) use ($item) {
                        return $tax['line_item_id'] == $item['id'];
                    });

                    $cgstItem = array_filter($itemTaxes, function ($tax) {
                        return $tax['tax_name'] == 'CGST';
                    });
                    $cgstItem = reset($cgstItem);

                    $sgstItem = array_filter($itemTaxes, function ($tax) {
                        return $tax['tax_name'] == 'SGST';
                    });
                    $sgstItem = reset($sgstItem);

                    $igstItem = array_filter($itemTaxes, function ($tax) {
                        return $tax['tax_name'] == 'IGST';
                    });
                    $igstItem = reset($igstItem);
                ?>
                    <tr class="divide-x divide-black h-[150px]">
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td class="">
                            <div class="flex flex-col justify-between h-full">
                                <p class="font-bold mt-1"><?= $item['description'] ?></p>
                                <div>
                                    <div class="text-right mt-1 space-y-px pt-4">
                                        <?php if ($invoice['tax_type'] === 'IGST'): ?>
                                            <p>IGST</p>
                                        <?php else: ?>
                                            <p>CGST</p>
                                            <p>SGST</p>
                                        <?php endif; ?>
                                        <p class="font-bold">ROUNDED OFF</p>
                                    </div>
                                    <p class="mt-[-15px]">Less :</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center"><?= $item['hsn_sac_code'] ?></td>
                        <td class="text-center font-bold"><?= number_format($item['quantity'], 3) ?> <?= $item['unit'] ?></td>
                        <td class="text-right font-bold"><?= number_format($item['rate'], 2) ?></td>
                        <td class="text-center font-bold"><?= $item['unit'] ?></td>
                        <td class="text-right">
                            <div class="flex flex-col justify-between h-full font-bold">
                                <p class="mt-1"><?= number_format($item['taxable_value'], 2) ?></p>
                                <div class="space-y-px">
                                    <?php if ($invoice['tax_type'] === 'IGST'): ?>
                                        <p><?= number_format($igstItem['tax_amount'] ?? 0, 2) ?></p>
                                    <?php else: ?>
                                        <p><?= number_format($cgstItem['tax_amount'] ?? 0, 2) ?></p>
                                        <p><?= number_format($sgstItem['tax_amount'] ?? 0, 2) ?></p>
                                    <?php endif; ?>
                                    <p>(-)<?= number_format($totalInvoice - round($totalInvoice), 2) ?></p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="h-[270px]">
                    <td colspan="7" class="border-0"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="divide-x divide-black font-bold">
                    <td colspan="3" class="text-center">Total</td>
                    <td class="text-center"><?= number_format(array_sum(array_column($lineItems, 'quantity')), 3) ?> <?= $lineItems[0]['unit'] ?? 'KGS' ?></td>
                    <td colspan="2"></td>
                    <td class="text-right">
                        <div class="flex justify-between items-center">
                            <span>₹ <?= number_format(round($totalInvoice), 2) ?></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="font-bold p-2">
                        <div class="flex justify-between">
                            <span>
                                Amount Chargeable (in words)<br>
                                <span class="font-[700] text-[13px]">Indian Rupees <?= numberToWords(round($totalInvoice)) ?> Only</span>
                            </span>
                            <span class="font-normal text-[10px] ml-4">E. & O.E</span>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Tax Summary Table -->
        <table class="no-top-border border border-black border-t-0">
            <thead>
                <tr class="divide-x divide-black text-center font-bold">
                    <td rowspan="2" class="align-middle w-[150px]">HSN/SAC</td>
                    <td rowspan="2" class="align-middle">Taxable Value</td>
                    <?php if ($invoice['tax_type'] === 'IGST'): ?>
                        <td colspan="2">IGST</td>
                    <?php else: ?>
                        <td colspan="2">CGST</td>
                        <td colspan="2">SGST/UTGST</td>
                    <?php endif; ?>
                    <td rowspan="2" class="align-middle">Total Tax Amount</td>
                </tr>
                <tr class="divide-x divide-black text-center font-bold">
                    <?php if ($invoice['tax_type'] === 'IGST'): ?>
                        <td>Rate</td>
                        <td>Amount</td>
                    <?php else: ?>
                        <td>Rate</td>
                        <td>Amount</td>
                        <td>Rate</td>
                        <td>Amount</td>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $groupedItems = [];
                foreach ($lineItems as $item) {
                    $hsn = $item['hsn_sac_code'];
                    if (!isset($groupedItems[$hsn])) {
                        $groupedItems[$hsn] = [
                            'taxable_value' => 0,
                            'cgst_amount' => 0,
                            'sgst_amount' => 0,
                            'igst_amount' => 0
                        ];
                    }
                    $groupedItems[$hsn]['taxable_value'] += $item['taxable_value'];

                    $itemTaxes = array_filter($taxes, function ($tax) use ($item) {
                        return $tax['line_item_id'] == $item['id'];
                    });

                    foreach ($itemTaxes as $tax) {
                        if ($tax['tax_name'] === 'CGST') {
                            $groupedItems[$hsn]['cgst_amount'] += $tax['tax_amount'];
                        } elseif ($tax['tax_name'] === 'SGST') {
                            $groupedItems[$hsn]['sgst_amount'] += $tax['tax_amount'];
                        } elseif ($tax['tax_name'] === 'IGST') {
                            $groupedItems[$hsn]['igst_amount'] += $tax['tax_amount'];
                        }
                    }
                }

                foreach ($groupedItems as $hsn => $item): ?>
                    <tr class="divide-x divide-black">
                        <td class="text-center"><?= $hsn ?></td>
                        <td class="text-right"><?= number_format($item['taxable_value'], 2) ?></td>
                        <?php if ($invoice['tax_type'] === 'IGST'): ?>
                            <td class="text-center"><?= $invoice['cgst_rate'] + $invoice['sgst_rate'] ?>%</td>
                            <td class="text-right"><?= number_format($item['igst_amount'], 2) ?></td>
                            <td class="text-right" colspan="3"><?= number_format($item['igst_amount'], 2) ?></td>
                        <?php else: ?>
                            <td class="text-center"><?= $invoice['cgst_rate'] ?>%</td>
                            <td class="text-right"><?= number_format($item['cgst_amount'], 2) ?></td>
                            <td class="text-center"><?= $invoice['sgst_rate'] ?>%</td>
                            <td class="text-right"><?= number_format($item['sgst_amount'], 2) ?></td>
                            <td class="text-right"><?= number_format($item['cgst_amount'] + $item['sgst_amount'], 2) ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="divide-x divide-black font-[800]">
                    <td class="text-center">Total</td>
                    <td class="text-right"><?= number_format($taxableValue, 2) ?></td>
                    <?php if ($invoice['tax_type'] === 'IGST'): ?>
                        <td class="text-right" colspan="2"><?= number_format($invoice['cgst_amount'] + $invoice['sgst_amount'], 2) ?></td>
                        <td class="text-right" colspan="3"><?= number_format($invoice['cgst_amount'] + $invoice['sgst_amount'], 2) ?></td>
                    <?php else: ?>
                        <td class="text-right" colspan="2"><?= number_format($invoice['cgst_amount'], 2) ?></td>
                        <td class="text-right" colspan="2"><?= number_format($invoice['sgst_amount'], 2) ?></td>
                        <td class="text-right"><?= number_format($invoice['cgst_amount'] + $invoice['sgst_amount'], 2) ?></td>
                    <?php endif; ?>
                </tr>
            </tfoot>
        </table>

        <div class="border border-y-0 border-black p-2">
            <p class="text-[12px]">Tax Amount (in words) : <span class="font-[700] text-[13px]">Indian Rupees <?= numberToWords($invoice['cgst_amount'] + $invoice['sgst_amount']) ?> Only</span></p>
        </div>

        <div class="grid grid-cols-2 border border-t-0 border-black text-[12px] h-[120px]">
            <div class="p-2 border-r border-black">
                <p>Company's PAN: <b><?= $invoice['pan_number'] ?></b></p>
                <br>
                <p class="font-bold underline">Declaration</p>
                <p>We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p>
            </div>
            <div class="flex flex-col justify-between">
                <div>
                    <p class="p-2">Company's Bank Details</p>
                    <p class="px-2">Bank Name: <?= $invoice['bank_name'] ?></p>
                    <p class="px-2">A/c No.: <?= $invoice['account_number'] ?></p>
                    <p class="px-2">Branch & IFS Code: <?= $invoice['branch_ifsc'] ?></p>
                </div>
                <div class="text-end border-t border-black py-0.5 px-2">
                    <p class="font-bold pb-7">for <?= $invoice['business_name'] ?></p>
                    <p class="">Authorised Signatory</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 text-xs font-semibold">This is a Computer Generated Invoice</div>
    </div>

    <!-- Include jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        // Function to convert numbers to words (same as your existing implementation)
        // Number to words function for invoice total
        function numberToWords(num) {
            const single = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            const double = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            const formatTenth = (digit, prev) => {
                return 0 == digit ? '' : ' ' + (1 == digit ? double[prev] : tens[digit]);
            };
            const formatOther = (digit, next, denom) => {
                return (0 != digit && 1 != next ? ' ' + single[digit] : '') + (0 != next || digit > 0 ? ' ' + denom : '');
            };

            let str = '';
            let rupees = Math.floor(num);
            let paise = Math.round((num - rupees) * 100);

            str += rupees > 0 ? formatOther(Math.floor(rupees / 10000000) % 100, 0, 'Crore') : '';
            str += rupees > 0 ? formatOther(Math.floor(rupees / 100000) % 100, 0, 'Lakh') : '';
            str += rupees > 0 ? formatOther(Math.floor(rupees / 1000) % 100, 0, 'Thousand') : '';
            str += rupees > 0 ? formatOther(Math.floor(rupees / 100) % 10, 0, 'Hundred') : '';

            if (rupees > 0) {
                const tensValue = Math.floor(rupees % 100);
                if (tensValue > 0) {
                    str += tensValue < 10 ? ' ' + single[tensValue] :
                        tensValue < 20 ? ' ' + double[tensValue - 10] :
                        ' ' + tens[Math.floor(tensValue / 10)] + (tensValue % 10 > 0 ? ' ' + single[tensValue % 10] : '');
                }
                str += ' Rupees';
            }

            if (paise > 0) {
                if (rupees > 0) str += ' and';
                const tensValue = Math.floor(paise % 100);
                if (tensValue > 0) {
                    str += tensValue < 10 ? ' ' + single[tensValue] :
                        tensValue < 20 ? ' ' + double[tensValue - 10] :
                        ' ' + tens[Math.floor(tensValue / 10)] + (tensValue % 10 > 0 ? ' ' + single[tensValue % 10] : '');
                }
                str += ' Paise';
            }

            return str.trim() || 'Zero Rupees';
        }

        // Generate PDF when page loads
        window.onload = function() {
            const {
                jsPDF
            } = window.jspdf;
            const invoiceContent = document.getElementById('invoice-content');
            const invoiceTitleEl = document.getElementById('invoice-title');
            const originalTitle = invoiceTitleEl.textContent;

            // Check if we should generate multiple copies
            const urlParams = new URLSearchParams(window.location.search);
            const copies = urlParams.get('copies') ? urlParams.get('copies').split(',') : ['original'];

            const copyTitles = {
                'original': '(ORIGINAL FOR RECIPIENT)',
                'duplicate': '(DUPLICATE FOR TRANSPORTER)',
                'triplicate': '(TRIPLICATE FOR SUPPLIER)'
            };

            const pdf = new jsPDF('p', 'mm', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();

            // Generate each selected copy
            copies.forEach((copy, index) => {
                if (index > 0) {
                    pdf.addPage();
                }

                // Update the title for this copy
                invoiceTitleEl.textContent = copyTitles[copy] || copyTitles['original'];

                // Use html2canvas to render the invoice content
                html2canvas(invoiceContent, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: invoiceContent.scrollWidth,
                    windowHeight: invoiceContent.scrollHeight
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const imgProps = pdf.getImageProperties(imgData);
                    const imgHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, imgHeight);

                    // If this is the last copy, save the PDF
                    if (index === copies.length - 1) {
                        pdf.save('<?= $invoice["invoice_number"] ?>.pdf');
                    }
                });
            });

            // Restore original title
            invoiceTitleEl.textContent = originalTitle;
        };
    </script>
</body>

</html>