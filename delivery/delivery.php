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
                                <table id="myTable" class="custom-table table table-bordered table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">Supplier</th>
                                            <th class="text-center">Item</th>
                                            <th class="text-center">Category</th>
                                            <th>Price</th>
                                            <th>Batch No.</th>
                                            <th>Delivery Date</th>
                                            <th>Expiration Date</th>
                                            <th>Delivery Man</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td class='text-center'><?php echo htmlspecialchars($row['supplier']); ?></td>
                                                    <td class='text-center'><?php echo htmlspecialchars($row['product_name']); ?></td>
                                                    <td class='text-center'><?php echo htmlspecialchars($row['category_name']); ?></td>
                                                    <td class='text-center'>₱<?php echo $row['price']; ?></td>
                                                    <td class='text-center'><?php echo $row['batch']; ?></td>
                                                    <td class='text-center'><?php echo $row['received']; ?></td>
                                                    <td class='text-center'><?php echo $row['expiration_date']; ?></td>
                                                    <td class='text-center'><?php echo $row['delivery_man']; ?></td>
                                                    <td class='text-center'>
                                                        <a href='update.php?id=<?php echo $row['id']; ?>' class='btn btn-sm btn-warning'>
                                                            <i class='bi bi-pencil-square'></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan='9' class='text-center'>No inventory records found</td></tr>
                                        <?php endif; ?>
                                        <?php $con->close(); ?>
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