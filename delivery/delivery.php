<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php");
    exit;
}

// Restrict access to Inventory Clerk, Super Admin, and Admin only
if ($_SESSION['user_role'] !== "Inventory Clerk" && $_SESSION['user_role'] !== "Super Admin" && $_SESSION['user_role'] !== "Admin") {
    header("Location: ../403.php");
    exit;
}

// Fetch branches
$sql_branches = "SELECT id, branch_name FROM branches";
$result_branches = $con->query($sql_branches);

// Fetch categories
$sql_categories = "SELECT id, category_name FROM categories";
$result_categories = $con->query($sql_categories);

// Handle filtering
$branch_id = isset($_GET['branch_id']) ? intval($_GET['branch_id']) : '';
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : '';

$sql = "SELECT d.*, c.category_name 
        FROM delivery d 
        LEFT JOIN categories c ON d.categories_id = c.id";

$conditions = [];
if ($branch_id) {
    $conditions[] = "d.branch_id = $branch_id";
}
if ($category_id) {
    $conditions[] = "d.categories_id = $category_id";
}
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = $con->query($sql);
if (!$result) {
    die("SQL error: " . $con->error);
}

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
                            <h3 class="mb-0">Delivery List</h3>
                            <a href="release_stock.php" class="mt-4 btn btn-primary">
                            <i class="bi-arrow-bar-up bi"></i> Release Stock
                            </a>
                            <?php if ($_SESSION['user_role'] === 'Super Admin' || $_SESSION['user_role'] === 'Inventory Clerk') : ?>
                                <a href="add_delivery.php" class="mt-4 btn btn-primary">
                                    <i class="bi bi-plus-lg"></i> Add Delivery
                                </a>
                            <?php endif; ?>

                        </div>
                        <div class="card-body">
                            <form method="GET" action="" class="col-md-12 row">
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

                           <!-- Replace the table section with this minimal version -->
                            <div class="table-responsive">
                                <table id="myTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Supplier</th>
                                            <th>Item</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Batch</th>
                                            <th>Delivered</th>
                                            <th>Expires</th>
                                            <th>Delivery Man</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($row['product_name']); ?></strong>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                                    <td style="text-align: right;">₱<?php echo number_format($row['price'], 2); ?></td>
                                                    <td><span class="badge bg-light text-dark"><?php echo $row['batch']; ?></span></td>
                                                    <td><small><?php echo date('M d, Y', strtotime($row['received'])); ?></small></td>
                                                    <td>
                                                        <?php if ($row['expiration_date']): ?>
                                                            <span class="badge bg-<?php echo (strtotime($row['expiration_date']) < strtotime('+30 days') ? 'warning' : 'light'); ?> text-dark">
                                                                <?php echo date('M Y', strtotime($row['expiration_date'])); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><small><?php echo $row['delivery_man'] ? htmlspecialchars($row['delivery_man']) : '-'; ?></small></td>
                                                    <td>
                                                        <a href='update.php?id=<?php echo $row['id']; ?>' class='btn btn-sm btn-outline-primary'>
                                                            <i class='bi bi-pencil'></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan='9' class='text-center py-4 text-muted'>
                                                    No delivery records found
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php $con->close(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <style>
                                /* Minimal table styling */
                                #myTable {
                                    --bs-table-bg: transparent;
                                    --bs-table-striped-bg: rgba(0,0,0,0.02);
                                    --bs-table-hover-bg: rgba(0,0,0,0.04);
                                    font-size: 0.9rem;
                                    border-collapse: separate;
                                    border-spacing: 0;
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
                                
                                /* Compact badges */
                                .badge {
                                    font-weight: 500;
                                    padding: 0.35em 0.5em;
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

                            <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                    const dataTable = new simpleDatatables.DataTable("#myTable", {
                                        perPage: 5,
                                        perPageSelect: [5, 10, 15, 25, 50, 100],
                                        labels: {
                                            placeholder: "Search deliveries...",
                                            perPage: "Select per page",
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
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        new simpleDatatables.DataTable("#myTable");
    });
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

