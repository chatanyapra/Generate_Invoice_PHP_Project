<?php
include("../include/dbConnection.php");

$aColumns = array("invoice_number", "invoice_date", "buyer_name", "transaction_type", "total_invoice_value", "action");
$sIndexColumn = "id";
$sTable = "
    (
        SELECT 
            invoices.id,
            invoices.invoice_number,
            DATE_FORMAT(invoices.invoice_date, '%d-%m-%Y') AS invoice_date,
            invoices.buyer_name,
            invoices.transaction_type,
            FORMAT(invoices.total_invoice_value, 2) AS total_invoice_value,
            CONCAT(
                '<a title=\"Edit\" href=\"edit_invoice.php?id=', invoices.id, '\" class=\"btn btn-sm btn-primary\"><i class=\"fa fa-edit\"></i></a> ',
                '<button title=\"Delete\" onclick=\"deleteFunction(', invoices.id, ', this)\" class=\"btn btn-sm btn-danger\"><i class=\"fa fa-trash\"></i></button>'
            ) AS action
        FROM invoices
        WHERE 1 = 1
    ) AS invoices
";

// Paging
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
}

// Ordering
if (isset($_GET['iSortCol_0'])) {
    $sOrder = "ORDER BY ";
    $colIndex = intval($_GET['iSortCol_0']);
    $dir = mysqli_real_escape_string($connection, $_GET['sSortDir_0']);
    $sOrder .= $aColumns[$colIndex] . " " . $dir;
}

// Filtering
$sWhere = "";
if ($_GET['sSearch'] != "") {
    $sWhere = "WHERE (";
    foreach ($aColumns as $column) {
        if ($column != 'action') {
            $sWhere .= $column . " LIKE '%" . mysqli_real_escape_string($connection, $_GET['sSearch']) . "%' OR ";
        }
    }
    $sWhere = rtrim($sWhere, " OR ") . ")";
}

// Individual column filtering
for ($i = 0; $i < count($aColumns); $i++) {
    if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
        if ($sWhere == "") {
            $sWhere = "WHERE ";
        } else {
            $sWhere .= " AND ";
        }
        $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($connection, $_GET['sSearch_' . $i]) . "%' ";
    }
}

// Final query
$sQuery = "
    SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $aColumns) . "
    FROM $sTable
    $sWhere
    $sOrder
    $sLimit
";

$rResult = mysqli_query($connection, $sQuery) or die(mysqli_error($connection));

// Get total filtered records
$sQuery = "SELECT FOUND_ROWS()";
$rResultFilterTotal = mysqli_query($connection, $sQuery);
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

// Get total records
$sQuery = "SELECT COUNT($sIndexColumn) FROM $sTable";
$rResultTotal = mysqli_query($connection, $sQuery);
$aResultTotal = mysqli_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

// Output
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

// Format data
while ($aRow = mysqli_fetch_array($rResult)) {
    $row = array();
    foreach ($aColumns as $col) {
        $row[] = $aRow[$col];
    }
    $output['aaData'][] = $row;
}

echo json_encode($output);
