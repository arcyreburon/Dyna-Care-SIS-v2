<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Check if user is authorized to release stock (Super Admin or Admin)
if ($_SESSION['user_role'] !== 'Super Admin' && $_SESSION['user_role'] !== 'Admin' && $_SESSION['user_role'] !== 'Inventory Clerk') {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $release_quantity = $_POST['release_quantity'];
    $branch_from = 1; // Branch 1 for release stock
    $branch_to = 2; // Branch 2 for receiving stock

    // Start transaction
    $con->begin_transaction();

    try {
        // Deduct stock from branch 1
        $sql_deduct = "UPDATE inventory i
                       INNER JOIN products p ON i.products_id = p.id
                       SET i.avail_stock = i.avail_stock - ?
                       WHERE p.product_name = ? AND i.branches_id = ?";
        $stmt_deduct = $con->prepare($sql_deduct);
        $stmt_deduct->bind_param("isi", $release_quantity, $product_name, $branch_from);
        $stmt_deduct->execute();

        if ($stmt_deduct->affected_rows == 0) {
            throw new Exception("Error: Insufficient stock or invalid product.");
        }

        // Add stock to branch 2
        $sql_add = "UPDATE inventory i
                    INNER JOIN products p ON i.products_id = p.id
                    SET i.avail_stock = i.avail_stock + ?
                    WHERE p.product_name = ? AND i.branches_id = ?";
        $stmt_add = $con->prepare($sql_add);
        $stmt_add->bind_param("isi", $release_quantity, $product_name, $branch_to);
        $stmt_add->execute();

        // Commit the transaction
        $con->commit();

        $_SESSION['message'] = "Stock released and updated successfully.";
        $_SESSION['message_type'] = "success";

    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $con->rollback();
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    $stmt_deduct->close();
    $stmt_add->close();

    header("Location: release_stock.php");
    exit;
}

// Fetch product details for the dropdown
$sql_products = "SELECT p.product_name, i.avail_stock 
                 FROM products p 
                 INNER JOIN inventory i ON p.id = i.products_id 
                 WHERE i.branches_id = 1"; // Fetching from branch 1
$result_products = $con->query($sql_products);
$products = [];
while ($row = $result_products->fetch_assoc()) {
    $products[] = $row;
}

?>

<!-- Include layout components -->
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php include '../includes/footer.php'; ?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                    <?php endif; ?>

                    <!-- Form for Releasing Stock -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Release Stock</h5>
                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="product_name" class="form-label">Product Name</label>
                                        <select class="form-select" name="product_name" id="product_name" required onchange="updateAvailStock()">
                                            <option value="" disabled selected>Select Product</option>
                                            <?php foreach ($products as $product): ?>
                                                <option value="<?php echo htmlspecialchars($product['product_name']); ?>" data-avail-stock="<?php echo $product['avail_stock']; ?>">
                                                    <?php echo htmlspecialchars($product['product_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="avail_stock" class="form-label">Available Stock</label>
                                        <input type="number" class="form-control" name="avail_stock" id="avail_stock" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="release_quantity" class="form-label">Release Quantity</label>
                                        <input type="number" class="form-control" name="release_quantity" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Release Stock</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function updateAvailStock() {
        const productSelect = document.getElementById('product_name');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const availStock = selectedOption.getAttribute('data-avail-stock');
        document.getElementById('avail_stock').value = availStock;
    }
</script>
