<title>Reports</title>
<?php
session_start();
include "../db_conn.php";

// Fetch all branches
$branches_sql = "SELECT id, branch_name FROM branches"; 
$branches_result = $con->query($branches_sql);
$branches = [];
while ($row = $branches_result->fetch_assoc()) {
    $branches[] = $row;
}

// Fetch all categories
$categories_sql = "SELECT id, category_name FROM categories";
$categories_result = $con->query($categories_sql);
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

// Handle the selected filters
$selectedBranch = isset($_GET['branch_id']) ? $_GET['branch_id'] : '';
$selectedCategory = isset($_GET['category_id']) ? $_GET['category_id'] : '';

$sql = "SELECT i.id, i.avail_stock, i.damage_stock, p.product_name, p.expiration_date, 
               b.branch_name, c.category_name, o.brand
        FROM inventory i
        INNER JOIN products p ON i.products_id = p.id
        INNER JOIN branches b ON p.branches_id = b.id
        INNER JOIN categories c ON p.categories_id = c.id
        INNER JOIN others o ON p.id = o.products_id
        WHERE 1=1";

// Apply branch filter if selected
if ($selectedBranch) {
    $sql .= " AND b.id = $selectedBranch";
}

// Apply category filter if selected
if ($selectedCategory) {
    $sql .= " AND c.id = $selectedCategory";
}

$result = $con->query($sql);

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
                            <h3 class="mb-0">Re-Order Lists</h3>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive">
   
                        <div class="mt-3 col-md-4">
                            <form method="GET" action="" class="d-flex">
                                <select name="type_filter" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Types</option>
                                    <option value="medicine" <?php echo (isset($_GET['type_filter']) && $_GET['type_filter'] == 'medicine') ? 'selected' : ''; ?>>Medicine</option>
                                    <option value="supplies" <?php echo (isset($_GET['type_filter']) && $_GET['type_filter'] == 'supplies') ? 'selected' : ''; ?>>Supplies</option>
                                </select>
                            </form>
                        </div>

                        <button type="button" onclick="exportToExcel()" class="btn btn-success" style="margin-left: 10px;">Export to Excel</button>
                            

                            <!-- Table displaying the orders data -->
                            <table id="myTable" class="custom-table table-bordered table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Product Name</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Recipient Email</th>
                                        <th class="text-center">Order Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Include database connection
                                    include "../db_conn.php";

                                    // Handle type filter
                                    $typeFilter = isset($_GET['type_filter']) ? $_GET['type_filter'] : '';

                                    // SQL query to fetch data from orders table with type filter
                                    $sql = "SELECT * FROM orders";
                                    if (!empty($typeFilter)) {
                                        $sql .= " WHERE type = ?";
                                    }

                                    $stmt = $con->prepare($sql);
                                    if (!empty($typeFilter)) {
                                        $stmt->bind_param("s", $typeFilter);
                                    }
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $type = ucfirst($row['type']); // Capitalize the first letter of the type
                                            echo "<tr>
                                            <td class='text-center'>" . htmlspecialchars($row['product_name']) . "</td>
                                            <td class='text-center'>" . $row['quantity'] . "</td>
                                            <td class='text-center'>" . htmlspecialchars($type) . "</td>
                                            <td class='text-center'>" . htmlspecialchars($row['recipient_email']) . "</td>
                                            <td class='text-center'>" . $row['order_date'] . "</td>
                                        </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No orders found</td></tr>";
                                    }

                                    $stmt->close();
                                    $con->close();
                                    ?>
                                </tbody>
                            </table>
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
        new simpleDatatables.DataTable("#myTable");
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
    function exportToExcel() {
        // Get the table element
        var table = document.getElementById("myTable");

        // Convert the table to a worksheet
        var ws = XLSX.utils.table_to_sheet(table);

        // Create a new workbook and append the worksheet
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Reports");

        // Write the workbook and save it
        var wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
        saveAs(new Blob([wbout], { type: "application/octet-stream" }), "Reports.xlsx");
    }
</script>
<!-- Custom CSS for Table Borders -->
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