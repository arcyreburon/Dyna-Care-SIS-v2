<title>DynaCareSIS - products</title>
<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Inventory Clerk, Super Admin, and Admin only
if ($_SESSION['user_role'] !== "Inventory Clerk" && $_SESSION['user_role'] !== "Admin") {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}


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

// Apply branch filter if selected
$branch_filter = "";
if ($selectedBranch) {
    $branch_filter = "AND b.id = $selectedBranch";
}

// Apply category filter if selected
$category_filter = "";
if ($selectedCategory) {
    $category_filter = "AND c.id = $selectedCategory";
}

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
                            <h3 class="mb-0">Product List</h3>
                            <a href="add_products.php" class="mt-4 btn btn-primary">
                                <i class="bi bi-plus-circle-fill"></i> Add Products
                            </a>
                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($_GET['success'])) {
                                echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['success']) . "</div>";
                            }
                            ?>

                            <div class="col-lg-12 row">
                                <form method="GET" action="" class="d-flex">
                                    <!-- Branch filter -->
                                    <!-- <div class="me-3 mt-3 mb-3 col-md-4">
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
                                    </div> -->

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

                            <!-- Product Table -->
                            <div class="table-responsive">
                                <table id="myTable" class="custom-table table table-bordered table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">Branch</th>
                                            <th class="text-center">Item</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Available Stock</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
    // Ensure the session has started and branch_id is set
    if (!isset($_SESSION['branches_id'])) {
        die("Error: Unauthorized access. Branch ID not set.");
    }

    // Get the branch ID from the session
    $branch_id = $_SESSION['branches_id'];

    // Modified SQL query with branch join and filters
    $sql = "SELECT p.id, p.product_name, p.price, c.category_name, b.branch_name, i.avail_stock
            FROM products p
            INNER JOIN categories c ON p.categories_id = c.id
            INNER JOIN inventory i ON p.id = i.products_id
            INNER JOIN branches b ON i.branches_id = b.id
            WHERE b.id = ? $branch_filter $category_filter";

    // Prepare and execute the SQL statement with the branch filter
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Iterate through all rows and display the product details
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td class='text-center'>" . htmlspecialchars($row['branch_name']) . "</td>
                <td class='text-center'>" . htmlspecialchars($row['product_name']) . "</td>
                <td class='text-center'>" . htmlspecialchars($row['category_name']) . "</td>
                <td class='text-center'>₱" . number_format($row['price'], 2) . "</td>
                <td class='text-center'>" . htmlspecialchars($row['avail_stock']) . "</td>
                <td class='text-center'>
                    <a href='update_products.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>
                        <i class='bi bi-pencil-square'></i>
                    </a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No products found</td></tr>";
    }

    // Close the prepared statement and the database connection
    $stmt->close();
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