<title>Sales Report</title>
<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Super Admin, Admin, and Cashier only
if ($_SESSION['user_role'] !== "Super Admin" && $_SESSION['user_role'] !== "Admin" && $_SESSION['user_role'] !== "Cashier") {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}

// Fetch products
$sql_products = "SELECT id, product_name FROM products";
$result_products = $con->query($sql_products);

// Fetch sales by product with filter
$product_id = isset($_GET['products_id']) ? $_GET['products_id'] : '';

// Fetch branches
$sql_branches = "SELECT id, branch_name FROM branches";
$result_branches = $con->query($sql_branches);

// Fetch sales by product
$sql_sales_by_product = "SELECT p.product_name, SUM(t.total_price) AS total_sales
                         FROM transaction t
                         INNER JOIN products p ON t.products_id = p.id";
if ($product_id) {
    $sql_sales_by_product .= " WHERE p.id = " . intval($product_id);
}
$sql_sales_by_product .= " GROUP BY p.product_name";
$result_sales_by_product = $con->query($sql_sales_by_product);

// Fetch total sales by branch
$sql_sales_by_branch = "SELECT b.branch_name, SUM(t.total_price) AS total_sales
                        FROM transaction t
                        INNER JOIN products p ON t.products_id = p.id
                        INNER JOIN branches b ON p.branches_id = b.id";
if ($product_id) {
    $sql_sales_by_branch .= " WHERE p.id = " . intval($product_id);
}
$sql_sales_by_branch .= " GROUP BY b.branch_name";
$result_sales_by_branch = $con->query($sql_sales_by_branch);

// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<main id="main" class="main">
    <section class="dashboard section">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="shadow-sm card">
                        <div class="text-black card-header">
                            <h3 class="mb-0">Sales Report</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $_SESSION['message']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <!-- Product Filter Form -->
                            <!-- <form method="GET" action="" class="mb-3 col-md-12 row">
                                <div class="mb-3 col-md-6">
                                    <label for="productFilter" class="form-label">Filter by Product</label>
                                    <select id="productFilter" name="product_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">Select Product</option>
                                        <?php while ($product = $result_products->fetch_assoc()): ?>
                                            <option value="<?php echo $product['id']; ?>" <?php echo ($product_id == $product['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($product['product_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </form> -->

                

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <h4>Sales by Product</h4>
                                        <button onclick="exportToExcel()" class="btn btn-success"
                                        style="margin-bottom: 10px;">Export to Excel</button>
                                        <table id="salesByProductTable" class="custom-table table-bordered table table-hover table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th class="text-center">Product</th>
                                                    <th class="text-center">Total Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result_sales_by_product->num_rows > 0) {
                                                    while ($row = $result_sales_by_product->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td class='text-center'>" . htmlspecialchars($row['product_name']) . "</td>
                                                            <td class='text-center'>₱" . number_format($row['total_sales'], 2) . "</td>
                                                        </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='2' class='text-center'>No sales records found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        
                                        <h4>Sales by Branch</h4>
                                        <button onclick="exportToExcel()" class="btn btn-success"
                                        style="margin-bottom: 10px;">Export to Excel</button>
                                        <table id="salesByBranchTable" class="custom-table table-bordered table table-hover table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th class="text-center">Branch</th>
                                                    <th class="text-center">Total Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result_sales_by_branch->num_rows > 0) {
                                                    while ($row = $result_sales_by_branch->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td class='text-center'>" . htmlspecialchars($row['branch_name']) . "</td>
                                                            <td class='text-center'>₱" . number_format($row['total_sales'], 2) . "</td>
                                                        </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='2' class='text-center'>No sales records found</td></tr>";
                                                }
                                                ?>
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
    </section>
</main>

<!-- DataTable Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new simpleDatatables.DataTable("#salesByProductTable");
        new simpleDatatables.DataTable("#salesByBranchTable");
    });
</script>

<script>
    function exportToExcel() {
        // Get the salesByProductTable element
        var table1 = document.getElementById("salesByProductTable");

        // Convert the table to a worksheet
        var ws1 = XLSX.utils.table_to_sheet(table1);

        // Create a new workbook and append the first worksheet
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws1, "Sales By Product");

        // Get the salesByBranchTable element
        var table2 = document.getElementById("salesByBranchTable");

        // Convert the table to a worksheet
        var ws2 = XLSX.utils.table_to_sheet(table2);

        // Append the second worksheet
        XLSX.utils.book_append_sheet(wb, ws2, "Sales By Branch");

        // Write the workbook and save it
        var wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
        saveAs(new Blob([wbout], { type: "application/octet-stream" }), "Sales_Report.xlsx");
    }
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>


<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
    }

    .custom-table th,
    .custom-table td {
        border: 1px solid #dee2e6 !important;
        padding: 10px;
        text-align: center;
    }

    .custom-table thead th {
        background-color: rgb(168, 168, 168);
        color: white;
    }

    .custom-table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
