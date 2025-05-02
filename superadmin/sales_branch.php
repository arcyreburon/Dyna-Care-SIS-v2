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

// Get filter parameter
$product_id = $_GET['products_id'] ?? '';
$filter = $product_id ? "WHERE p.id = " . intval($product_id) : "";

// Fetch sales data
$sales_product = $con->query("
    SELECT p.product_name, SUM(t.total_price) AS total 
    FROM transaction t
    JOIN products p ON t.products_id = p.id $filter
    GROUP BY p.product_name
")->fetch_all(MYSQLI_ASSOC);

$sales_branch = $con->query("
    SELECT b.branch_name, SUM(t.total_price) AS total 
    FROM transaction t
    JOIN products p ON t.products_id = p.id
    JOIN branches b ON p.branches_id = b.id $filter
    GROUP BY b.branch_name
")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<style>
    /* Minimal table styling */
    .report-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    
    .report-table th {
        text-align: left;
        padding: 0.75rem;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 500;
    }
    
    .report-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .report-table tr:last-child td {
        border-bottom: none;
    }
    
    .total-cell {
        font-weight: 500;
    }
    
    /* Minimal card styling */
    .report-card {
        background: white;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .card-header {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }
    
    /* Minimal button styling */
    .btn {
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }
</style>

<main id="main" class="main">
    <div class="container py-3">
        <div class="report-card">
            <div class="card-header">
                <h3>Sales Report</h3>
            </div>
            
            <div class="p-3">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?> mb-3">
                        <?= $_SESSION['message'] ?>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>By Product</h5>
                            <button class="btn btn-sm btn-outline-secondary" onclick="exportTable('product')">
                                Export
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="productTable" class="report-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sales_product as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                                        <td class="text-end total-cell">₱<?= number_format($row['total'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>By Branch</h5>
                            <button class="btn btn-sm btn-outline-secondary" onclick="exportTable('branch')">
                                Export
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="branchTable" class="report-table">
                                <thead>
                                    <tr>
                                        <th>Branch</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sales_branch as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['branch_name']) ?></td>
                                        <td class="text-end total-cell">₱<?= number_format($row['total'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 text-end">
                    <button class="btn btn-primary" onclick="exportAll()">
                        Export All to Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
<script>
    function exportTable(type) {
        const table = document.getElementById(type + 'Table');
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, XLSX.utils.table_to_sheet(table), type);
        XLSX.writeFile(wb, `${type}_sales.xlsx`);
    }
    
    function exportAll() {
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, XLSX.utils.table_to_sheet(document.getElementById('productTable')), 'Products');
        XLSX.utils.book_append_sheet(wb, XLSX.utils.table_to_sheet(document.getElementById('branchTable')), 'Branches');
        XLSX.writeFile(wb, 'Complete_Sales_Report.xlsx');
    }
</script>