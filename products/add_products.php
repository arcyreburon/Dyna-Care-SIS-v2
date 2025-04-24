<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Inventory Clerk and Admin only
if ($_SESSION['user_role'] !== "Inventory Clerk" && $_SESSION['user_role'] !== "Admin") {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission to add product
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];

    // Get the logged-in user's branch ID from the session
    $branch_id = $_SESSION['branches_id']; // Assuming branch ID is stored in session

    // Step 1: Insert new product into the products table
    $insert_product_sql = "INSERT INTO products (product_name, categories_id, branches_id) 
                           VALUES (?, ?, ?)";
    $stmt = $con->prepare($insert_product_sql);
    $stmt->bind_param("sii", $product_name, $category_id, $branch_id);

    if ($stmt->execute()) {
        // Step 2: Get the auto-incremented product id (primary key)
        $product_id = $stmt->insert_id;

        // Step 3: Insert into inventory table for the same branch
        $insert_inventory_sql = "INSERT INTO inventory (products_id, damage_stock, branches_id) 
                                 VALUES (?, ?, ?)";
        $stmt_inventory = $con->prepare($insert_inventory_sql);
        $damage_stock = 0; // Default damage_stock value
        $stmt_inventory->bind_param("iii", $product_id, $damage_stock, $branch_id);

        if (!$stmt_inventory->execute()) {
            echo "Error adding product to inventory: " . $stmt_inventory->error;
        }

        // Redirect after successful insertion
        header("Location: products_table.php?success=Product added successfully to branch $branch_id!");
        exit;
    } else {
        echo "Error adding product: " . $stmt->error;
    }
}

// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>




<!-- HTML Add Product Form -->
<main id="main" class="main">
    <section class="dashboard section">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="shadow-sm card">
                        <div class="text-black card-header">
                            <h3 class="mb-0">Add Product</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="add_products.php">
                                <!-- Row with two columns: Product Name and Price -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-control" id="category" name="category_id" required>
                                                <option value="">Select Category</option>
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
                                </div>


                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-success">Add Product</button>
                                <a href="products_table.php" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
