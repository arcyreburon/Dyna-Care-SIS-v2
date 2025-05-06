<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Inventory Clerk, Super Admin, and Admin only
if ($_SESSION['user_role'] !== "Inventory Clerk" && $_SESSION['user_role'] !== "Super Admin" && $_SESSION['user_role'] !== "Admin") {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}

// Fetch branches and categories
$sql_branches = "SELECT id, branch_name FROM branches";
$result_branches = $con->query($sql_branches);

$sql_categories = "SELECT id, category_name FROM categories";
$result_categories = $con->query($sql_categories);

// Get filtering values from GET parameters
$user_role = $_SESSION['user_role']; 
$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : ($_SESSION['user_role'] !== "Super Admin" ? $_SESSION['branches_id'] : '');
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

$sql = "SELECT i.id, i.avail_stock, i.price, i.damage_stock, i.delivery_price, 
               p.product_name, p.expiration_date, b.branch_name, c.category_name,
               i.batch, i.dosage, i.old_price, i.brand, i.received
        FROM inventory i
        INNER JOIN products p ON i.products_id = p.id
        INNER JOIN branches b ON i.branches_id = b.id
        INNER JOIN categories c ON p.categories_id = c.id";

// Apply filtering conditions dynamically
$conditions = [];
$params = [];
$types = "";

if (!empty($branch_id) && $user_role === "Super Admin") {
    $conditions[] = "b.id = ?";
    $params[] = $branch_id;
    $types .= "i";
} elseif ($user_role !== "Super Admin") {
    $conditions[] = "b.id = ?";
    $params[] = $_SESSION['branches_id'];
    $types .= "i";
}

if (!empty($category_id)) {
    $conditions[] = "c.id = ?";
    $params[] = $category_id;
    $types .= "i";
}

// Append conditions to SQL query
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $con->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("SQL error: " . $con->error);
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<style>
    /* Minimal table styling */
    #myTable {
        --bs-table-bg: transparent;
        --bs-table-striped-bg: rgba(0,0,0,0.02);
        --bs-table-hover-bg: rgba(0,0,0,0.04);
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }
    
    #myTable th {
        font-weight: 500;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 0.75rem 1rem;
        white-space: nowrap;
    }
    
    #myTable td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }
    
    #myTable tr:last-child td {
        border-bottom: none;
    }
    
    /* Compact action buttons */
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #myTable {
            font-size: 0.85rem;
        }
        
        #myTable th, 
        #myTable td {
            padding: 0.5rem;
        }
    }
</style>

<main id="main" class="main">
    <section class="dashboard section">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="shadow-sm card">
                        <div class="text-black card-header">
                            <h3 class="mb-0">Inventory List</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $_SESSION['message']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <form method="GET" action="" class="col-md-12 row">
                            <?php if ($_SESSION['user_role'] === "Super Admin"): ?>
                                <div class="mt-3 mb-3 col-md-4">
                                    <label for="branchFilter" class="form-label">Filter by Branch</label>
                                    <select id="branchFilter" name="branch_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">Select Branch</option>
                                        <?php while ($branch = $result_branches->fetch_assoc()): ?>
                                            <option value="<?php echo $branch['id']; ?>" <?php echo ($branch_id == $branch['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($branch['branch_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="mt-3 mb-3 col-md-4">
                                <label for="categoryFilter" class="form-label">Filter by Category</label>
                                <select id="categoryFilter" name="category_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Select Category</option>
                                    <?php while ($category = $result_categories->fetch_assoc()): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['category_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </form>

                            <div class="table-responsive">
                                <table id="myTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Branch</th>
                                            <th>Item</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th class="medicine-column">Expiry</th>
                                            <th class="medicine-column">Batch</th>
                                            <th>Updated</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $isMedicine = stripos($row['category_name'], 'medicine') !== false;
                                                
                                                echo "<tr>
                                                    <td>" . htmlspecialchars($row['branch_name']) . "</td>
                                                    <td>
                                                        <strong>" . htmlspecialchars($row['product_name']) . "</strong>
                                                        " . ($row['brand'] ? "<br><small class='text-muted'>" . htmlspecialchars($row['brand']) . "</small>" : "") . "
                                                    </td>
                                                    <td>" . htmlspecialchars($row['category_name']) . "</td>
                                                    <td style='text-align: right;'>
                                                        <strong>₱" . number_format($row['price'], 2) . "</strong>
                                                        " . ($row['old_price'] && $row['old_price'] != $row['price'] ? 
                                                            "<br><small class='text-muted text-decoration-line-through'>₱" . number_format($row['old_price'], 2) . "</small>" 
                                                            : "") . "
                                                    </td>
                                                    <td>
                                                        <span class='badge bg-" . ($row['avail_stock'] > 10 ? 'success' : ($row['avail_stock'] > 0 ? 'warning' : 'danger')) . "'>
                                                            " . $row['avail_stock'] . "
                                                        </span>
                                                        " . ($row['damage_stock'] > 0 ? 
                                                            "<br><small class='text-danger'>Damaged: " . $row['damage_stock'] . "</small>" 
                                                            : "") . "
                                                    </td>
                                                    <td class='medicine-column'>" . ($row['expiration_date'] ?? '-') . "</td>
                                                    <td class='medicine-column'>" . ($row['batch'] ?? '-') . "</td>
                                                    <td><small>" . $row['received'] . "</small></td>
                                                    <td>
                                                        <div class='btn-group btn-group-sm'>
                                                            <a href='update_inventory.php?id=" . $row['id'] . "' class='btn btn-outline-primary'>
                                                                <i class='bi bi-pencil'></i>
                                                            </a>
                                                            <a href='delete.php?id=" . $row['id'] . "' class='btn btn-outline-danger' onclick='return confirm(\"Are you sure?\")'>
                                                                <i class='bi bi-trash'></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>";

                                            }
                                        } else {
                                            echo "<tr><td colspan='9' class='text-center py-4 text-muted'>No inventory records found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- End Card -->
                </div> <!-- End col-lg-12 -->
            </div> <!-- End row -->
        </div> <!-- End container -->
    </section>
</main>

<!-- DataTable Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new simpleDatatables.DataTable("#myTable");
    });
</script>

<!-- JavaScript for dynamic column toggling -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const categoryFilter = document.getElementById('categoryFilter');
        
        // Function to toggle columns based on category
        function toggleColumns() {
            const selectedOption = categoryFilter.options[categoryFilter.selectedIndex];
            const isMedicine = selectedOption.text.toLowerCase().includes('medicine');
            
            // Toggle medicine columns
            document.querySelectorAll('.medicine-column').forEach(col => {
                col.style.display = isMedicine ? 'table-cell' : 'none';
            });
            
            // Toggle supply columns
            document.querySelectorAll('.supply-column').forEach(col => {
                col.style.display = isMedicine ? 'none' : 'table-cell';
            });
            
            // Reinitialize DataTable to adjust column widths
            if (typeof dataTable !== 'undefined') {
                dataTable.refresh();
            }
        }
        
        // Initial toggle based on current selection
        toggleColumns();
        
        // Add event listener for category filter changes
        categoryFilter.addEventListener('change', toggleColumns);
        
        // Initialize DataTable
        const dataTable = new simpleDatatables.DataTable("#myTable", {
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dataTable = new simpleDatatables.DataTable("#myTable", {
            perPage: 15,
            perPageSelect: [10, 15, 25, 50, 100],
            labels: {
                placeholder: "Search inventory...",
                perPage: "{select} per page",
                noRows: "No matching records found",
                info: "Showing {start} to {end} of {rows} items"
            },
            classes: {
                active: "active",
                disabled: "disabled",
                selector: "form-select",
                paginationList: "pagination",
                paginationListItem: "page-item",
                paginationListItemLink: "page-link"
            }
        });
        
        // Toggle medicine-specific columns based on category filter
        function toggleColumns() {
            const categoryFilter = document.getElementById('categoryFilter');
            const selectedOption = categoryFilter?.options[categoryFilter.selectedIndex];
            const isMedicine = selectedOption?.text.toLowerCase().includes('medicine') ?? false;
            
            document.querySelectorAll('.medicine-column').forEach(col => {
                col.style.display = isMedicine ? 'table-cell' : 'none';
            });
            
            if (dataTable) {
                dataTable.refresh();
            }
        }
        
        toggleColumns();
        document.getElementById('categoryFilter')?.addEventListener('change', toggleColumns);
    });
</script>
