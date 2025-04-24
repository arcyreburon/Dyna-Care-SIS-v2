<title>DynaCareSIS - update products</title>
<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Inventory Clerk, Super Admin, and Admin only
if (!in_array($_SESSION['user_role'], ["Inventory Clerk", "Admin"])) {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}

// Fetch product details to update
if (isset($_GET['id'])) { // Changed from 'code' to 'id'
    $id = $_GET['id'];
    $branch_id = $_SESSION['branches_id']; // Get user's branch ID

    // SQL query to fetch product details for the logged-in user's branch
    $sql = "SELECT p.id, p.product_name, p.price, 
                   c.id AS category_id, c.category_name, 
                   i.avail_stock
            FROM products p
            INNER JOIN categories c ON p.categories_id = c.id
            INNER JOIN inventory i ON p.id = i.products_id
            WHERE p.id = ? AND p.branches_id = ?"; // Ensures user can only edit products from their branch

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $con->error); // Debug message
    }
    $stmt->bind_param("ii", $id, $branch_id); // Bind as integers
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found or not in your branch!";
        exit;
    }
} else {
    echo "Product ID is missing!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handling form submission
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $id = $_POST['id']; // Fetch the hidden input value
    $branch_id = $_SESSION['branches_id'];

    // SQL query to update the product details
    $update_sql = "UPDATE products p
                   INNER JOIN inventory i ON p.id = i.products_id
                   SET p.product_name = ?, p.categories_id = ?
                   WHERE p.id = ? AND p.branches_id = ?"; // Restrict update to user's branch

    $update_stmt = $con->prepare($update_sql);
    if (!$update_stmt) {
        die("SQL error: " . $con->error); // Debug message
    }
    $update_stmt->bind_param("siii", $product_name, $category_id, $id, $branch_id);

    if ($update_stmt->execute()) {
        header("Location: products_table.php?success=Product updated successfully!");
        exit;
    } else {
        echo "Error updating product: " . $update_stmt->error;
    }
}

// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>




<!-- HTML Update Form -->
<main id="main" class="main">
    <section class="dashboard section">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="shadow-sm card">
                        <div class="text-black card-header">
                            <h3 class="mb-0">Update Product</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id=<?= htmlspecialchars($product['id']) ?>">
                                <!-- Hidden input for the product ID -->
                                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

                                <div class="row">
                                    <!-- First Column -->
                                    <div class="mb-3 col-md-6">
                                        <label for="product_name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-control" id="category_id" name="category_id" required>
                                            <option value="<?= $product['category_id'] ?>" selected><?= htmlspecialchars($product['category_name']) ?></option>
                                            <?php
                                            // Fetch categories
                                            $category_sql = "SELECT * FROM categories";
                                            $category_result = $con->query($category_sql);
                                            while ($category = $category_result->fetch_assoc()) {
                                                echo "<option value='{$category['id']}'>{$category['category_name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Product</button>
                                <a href="products_table.php" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

