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
                            <h3 class="mb-0">Reports List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- Dropdown filters for branches and categories -->
                                <div class="col-lg-12 row">
                                    <form method="GET" action="" class="d-flex">

                                        <!-- Branch filter -->
                                        <div class="mt-3 mb-3 col-md-4 me-3">
                                            <label for="branch_id" class="form-label">Branch</label>
                                            <select class="form-select" id="branch_id" name="branch_id"
                                                onchange="this.form.submit()">
                                                <option value="">All Branches</option>
                                                <?php foreach ($branches as $branch): ?>
                                                    <option value="<?php echo $branch['id']; ?>" <?php if ($selectedBranch == $branch['id'])
                                                           echo 'selected'; ?>>
                                                        <?php echo htmlspecialchars($branch['branch_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Category filter -->
                                        <div class="mt-3 mb-3 col-md-4">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-select" id="category_id" name="category_id"
                                                onchange="this.form.submit()">
                                                <option value="">All Categories</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php if ($selectedCategory == $category['id'])
                                                           echo 'selected'; ?>>
                                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <button onclick="exportToExcel()" class="btn btn-success"
                                    style="margin-bottom: 10px;">Export to Excel</button>
                                <!-- Table displaying the inventory data -->
                                <table id="myTable" class="custom-table table-bordered table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">Branch</th>
                                            <th class="text-center">Brand</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Available Stock</th>
                                            <th class="text-center">Damaged Stock</th>
                                            <th class="text-center">Expiration Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                <td class='text-center'>" . htmlspecialchars($row['branch_name']) . "</td>
                                <td class='text-center'>" . htmlspecialchars($row['brand']) . "</td>
                                <td class='text-center'>" . htmlspecialchars($row['product_name']) . "</td>
                                <td class='text-center'>" . htmlspecialchars($row['category_name']) . "</td>
                                <td class='text-center'>" . $row['avail_stock'] . "</td>
                                <td class='text-center'>" . $row['damage_stock'] . "</td>
                                <td class='text-center'>" . $row['expiration_date'] . "</td>
                            </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>No inventory records found</td></tr>";
                                        }

                                        $con->close();
                                        ?>
                                    </tbody>
                                </table>
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