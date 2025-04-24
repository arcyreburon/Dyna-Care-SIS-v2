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
                            <table id="myTable" class="custom-table table table-bordered table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <th class="text-center">Branch</th>
            <th class="text-center">Item</th>
            <th class="text-center">Category</th> <!-- New column for category -->
            <th>Old Price</th>
            <th>Selling Price</th>
            <th class="text-center">Available Stock</th>
            <th>Batch</th>
            <th>Delivery Date</th>
            <th>Expiration Date</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                  <td class='text-center'>" . htmlspecialchars($row['branch_name']) . "</td>
                    <td class='text-center'>" . htmlspecialchars($row['product_name']) . "</td>
                    <td class='text-center'>" . htmlspecialchars($row['category_name']) . "</td> 
                    <td class='text-center'>₱" . $row['old_price'] . "</td>
                    <td class='text-center'>₱" . $row['price'] . "</td>
                    <td class='text-center'>" . $row['avail_stock'] . "</td>
                    <td class='text-center'>" . $row['batch'] . "</td>
                    <td class='text-center'>" . $row['received'] . "</td>
                    <td class='text-center'>" . $row['expiration_date'] . "</td>
                    <td class='text-center'>
                        <a href='update_inventory.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>
                            <i class='bi bi-pencil-square'></i> 
                        </a>
                        <a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>
                            <i class='bi bi-trash'></i>
                        </a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='9' class='text-center'>No inventory records found</td></tr>"; // Adjusted colspan to 9
        }

        $con->close();
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
