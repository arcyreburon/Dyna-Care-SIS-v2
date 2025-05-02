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
        background-color: #f8f9fa;
    }
    
    .report-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .report-table tr:last-child td {
        border-bottom: none;
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
    
    /* Filter controls */
    .filter-control {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .filter-select {
        flex: 1;
        min-width: 200px;
    }
    
    .filter-label {
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
</style>

<main id="main" class="main">
    <div class="container py-3">
        <div class="report-card">
            <div class="card-header">
                <h3>Reports List</h3>
            </div>
            
            <div class="p-3">
                <div class="filter-control">
                    <form method="GET" action="" class="d-flex gap-3 flex-wrap">
                        <div>
                            <div class="filter-label">Branch</div>
                            <select class="form-select filter-select" id="branch_id" name="branch_id" onchange="this.form.submit()">
                                <option value="">All Branches</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?php echo $branch['id']; ?>" <?php if ($selectedBranch == $branch['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($branch['branch_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <div class="filter-label">Category</div>
                            <select class="form-select filter-select" id="category_id" name="category_id" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php if ($selectedCategory == $category['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="d-flex align-items-end">
                            <button onclick="exportToExcel()" class="btn btn-primary">Export to Excel</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table id="myTable" class="report-table">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Brand</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Available</th>
                                <th>Damaged</th>
                                <th>Expiration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . htmlspecialchars($row['branch_name']) . "</td>
                                        <td>" . htmlspecialchars($row['brand']) . "</td>
                                        <td>" . htmlspecialchars($row['product_name']) . "</td>
                                        <td>" . htmlspecialchars($row['category_name']) . "</td>
                                        <td>" . $row['avail_stock'] . "</td>
                                        <td>" . $row['damage_stock'] . "</td>
                                        <td>" . $row['expiration_date'] . "</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center py-3'>No inventory records found</td></tr>";
                            }

                            $con->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- DataTable Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new simpleDatatables.DataTable("#myTable", {
            perPage: 10,
            labels: {
                placeholder: "Search...",
                perPage: "{select} entries per page",
                noRows: "No entries found",
                info: "Showing {start} to {end} of {rows} entries"
            }
        });
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