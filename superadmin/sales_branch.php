<?php
// sales_report.php
session_start();
require "../db_conn.php";

// Authentication check
$allowed_roles = ["Super Admin", "Admin", "Cashier"];
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
    header("Location: " . ($_SESSION['user_role'] ? "../403.php" : "../index.php"));
    exit;
}

// Get filter parameters
$product_id = $_GET['products_id'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Build filter conditions
$filter = "";
if ($product_id) {
    $filter .= " AND p.id = " . intval($product_id);
}
if ($start_date && $end_date) {
    $filter .= " AND t.transaction_date BETWEEN '" . $con->real_escape_string($start_date) . "' AND '" . $con->real_escape_string($end_date) . "'";
}

// Fetch sales data
$sales_product = $con->query("
    SELECT p.product_name, SUM(t.total_price) AS total, COUNT(t.id) AS transactions
    FROM transaction t
    JOIN products p ON t.products_id = p.id
    WHERE 1=1 $filter
    GROUP BY p.product_name
")->fetch_all(MYSQLI_ASSOC);

$sales_branch = $con->query("
    SELECT b.branch_name, SUM(t.total_price) AS total, COUNT(t.id) AS transactions
    FROM transaction t
    JOIN products p ON t.products_id = p.id
    JOIN branches b ON p.branches_id = b.id
    WHERE 1=1 $filter
    GROUP BY b.branch_name
")->fetch_all(MYSQLI_ASSOC);

// Calculate totals
$product_total = array_sum(array_column($sales_product, 'total'));
$branch_total = array_sum(array_column($sales_branch, 'total'));
$total_transactions = array_sum(array_column($sales_product, 'transactions'));

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<style>
    .receipt-style {
        font-family: 'Courier New', monospace;
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
    }
    .receipt-header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 1px dashed #000;
        padding-bottom: 10px;
    }
    .receipt-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .receipt-subtitle {
        font-size: 0.9rem;
    }
    .receipt-table {
        width: 100%;
        border-collapse: collapse;
    }
    .receipt-table th {
        text-align: left;
        padding: 5px 0;
        border-bottom: 1px dashed #ddd;
    }
    .receipt-table td {
        padding: 5px 0;
    }
    .receipt-table .text-right {
        text-align: right;
    }
    .receipt-total {
        font-weight: bold;
        border-top: 1px dashed #000;
        border-bottom: 1px dashed #000;
        padding: 5px 0;
    }
    .receipt-footer {
        text-align: center;
        margin-top: 15px;
        font-size: 0.8rem;
        border-top: 1px dashed #000;
        padding-top: 10px;
    }
</style>

<main id="main" class="main">
    <div class="container py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Sales Report</h3>
                <div>
                    <button class="btn btn-success me-2" onclick="exportReceipt()">
                        <i class="bi bi-receipt me-1"></i> Export Receipt
                    </button>
                    <button class="btn btn-primary" onclick="exportAll()">
                        <i class="bi bi-file-excel me-1"></i> Export Full Report
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?> mb-4">
                        <?= $_SESSION['message'] ?>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">By Product</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th class="text-end">Sales</th>
                                                <th class="text-end">Transactions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sales_product as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                                <td class="text-end">₱<?= number_format($row['total'], 2) ?></td>
                                                <td class="text-end"><?= $row['transactions'] ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-active">
                                                <td class="fw-bold">Total</td>
                                                <td class="text-end fw-bold">₱<?= number_format($product_total, 2) ?></td>
                                                <td class="text-end fw-bold"><?= $total_transactions ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">By Branch</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Branch</th>
                                                <th class="text-end">Sales</th>
                                                <th class="text-end">Transactions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sales_branch as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['branch_name']) ?></td>
                                                <td class="text-end">₱<?= number_format($row['total'], 2) ?></td>
                                                <td class="text-end"><?= $row['transactions'] ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-active">
                                                <td class="fw-bold">Total</td>
                                                <td class="text-end fw-bold">₱<?= number_format($branch_total, 2) ?></td>
                                                <td class="text-end fw-bold"><?= $total_transactions ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
<script>
    function exportReceipt() {
        const wb = XLSX.utils.book_new();
        
        // Create receipt-style worksheet
        const receiptData = [
            ["COMPANY NAME"], // A1
            ["Sales Receipt"], // A2
            ["Generated on: " + new Date().toLocaleString()], // A3
            [""], // A4 (empty row)
            ["Product", "Amount", "Qty"], // A5 header
        ];
        
        // Add product sales
        <?php foreach ($sales_product as $row): ?>
            receiptData.push([
                "<?= htmlspecialchars($row['product_name']) ?>",
                <?= $row['total'] ?>,
                <?= $row['transactions'] ?>
            ]);
        <?php endforeach; ?>
        
        // Add totals
        receiptData.push([""]); // Empty row
        receiptData.push(["TOTAL", <?= $product_total ?>, <?= $total_transactions ?>]);
        receiptData.push([""]); // Empty row
        receiptData.push(["Thank you for your business!"]);
        receiptData.push(["Generated by: <?= htmlspecialchars($_SESSION['user_name'] ?? 'System') ?>"]);
        
        const ws = XLSX.utils.aoa_to_sheet(receiptData);
        
        // Apply receipt styling
        if (!ws['!cols']) ws['!cols'] = [];
        ws['!cols'][0] = { width: 30 }; // Product column
        ws['!cols'][1] = { width: 15 }; // Amount column
        ws['!cols'][2] = { width: 10 }; // Qty column
        
        // Style header (A1)
        ws["A1"].s = {
            font: { bold: true, size: 16 },
            alignment: { horizontal: "center" }
        };
        
        // Style title (A2)
        ws["A2"].s = {
            font: { bold: true, size: 14 },
            alignment: { horizontal: "center" }
        };
        
        // Style date (A3)
        ws["A3"].s = {
            font: { italic: true },
            alignment: { horizontal: "center" }
        };
        
        // Style header row (A5:C5)
        for (let c = 0; c <= 2; c++) {
            const cell = XLSX.utils.encode_cell({r:4, c:c});
            ws[cell].s = {
                font: { bold: true },
                fill: { fgColor: { rgb: "F0F0F0" } }
            };
        }
        
        // Style total row
        const totalRow = receiptData.length - 5;
        for (let c = 0; c <= 2; c++) {
            const cell = XLSX.utils.encode_cell({r:totalRow, c:c});
            if (!ws[cell]) continue;
            ws[cell].s = {
                font: { bold: true },
                border: { top: { style: "medium" }, bottom: { style: "medium" } }
            };
        }
        
        // Format currency cells
        for (let r = 5; r < receiptData.length; r++) {
            const cell = XLSX.utils.encode_cell({r:r, c:1});
            if (!ws[cell]) continue;
            ws[cell].z = '"₱"#,##0.00';
        }
        
        // Merge company name and title cells
        ws['!merges'] = [
            { s: {r:0, c:0}, e: {r:0, c:2} }, // COMPANY NAME
            { s: {r:1, c:0}, e: {r:1, c:2} }, // Sales Receipt
            { s: {r:2, c:0}, e: {r:2, c:2} }, // Generated on
            { s: {r:receiptData.length-2, c:0}, e: {r:receiptData.length-2, c:2} }, // Thank you
            { s: {r:receiptData.length-1, c:0}, e: {r:receiptData.length-1, c:2} } // Generated by
        ];
        
        XLSX.utils.book_append_sheet(wb, ws, "Sales Receipt");
        XLSX.writeFile(wb, `Sales_Receipt_${new Date().toISOString().slice(0,10)}.xlsx`);
    }
    
    function exportAll() {
        const wb = XLSX.utils.book_new();
        
        // Product Sales Sheet
        const productWs = XLSX.utils.table_to_sheet(document.getElementById('productTable'));
        productWs['!cols'] = [{ width: 30 }, { width: 15 }, { width: 15 }];
        XLSX.utils.book_append_sheet(wb, productWs, 'Product Sales');
        
        // Branch Sales Sheet
        const branchWs = XLSX.utils.table_to_sheet(document.getElementById('branchTable'));
        branchWs['!cols'] = [{ width: 30 }, { width: 15 }, { width: 15 }];
        XLSX.utils.book_append_sheet(wb, branchWs, 'Branch Sales');
        
        // Summary Sheet
        const summaryData = [
            ["SALES REPORT SUMMARY"],
            ["Generated on: " + new Date().toLocaleString()],
            ["Generated by: <?= htmlspecialchars($_SESSION['user_name'] ?? 'System') ?>"],
            [""],
            ["Total Product Sales", <?= $product_total ?>],
            ["Total Branch Sales", <?= $branch_total ?>],
            ["Total Transactions", <?= $total_transactions ?>],
            [""],
            ["Note:", "This report was generated automatically"]
        ];
        
        const summaryWs = XLSX.utils.aoa_to_sheet(summaryData);
        summaryWs['!cols'] = [{ width: 25 }, { width: 20 }];
        
        // Style summary sheet
        summaryWs["A1"].s = { font: { bold: true, size: 16 } };
        summaryWs["A5"].s = { font: { bold: true } };
        summaryWs["A6"].s = { font: { bold: true } };
        summaryWs["A7"].s = { font: { bold: true } };
        summaryWs["B5"].z = '"₱"#,##0.00';
        summaryWs["B6"].z = '"₱"#,##0.00';
        
        // Merge title cells
        summaryWs['!merges'] = [
            { s: {r:0, c:0}, e: {r:0, c:1} },
            { s: {r:1, c:0}, e: {r:1, c:1} },
            { s: {r:2, c:0}, e: {r:2, c:1} }
        ];
        
        XLSX.utils.book_append_sheet(wb, summaryWs, 'Summary');
        
        XLSX.writeFile(wb, `Complete_Sales_Report_${new Date().toISOString().slice(0,10)}.xlsx`);
    }
</script>