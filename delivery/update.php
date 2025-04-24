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

// Initialize variables
$productId = isset($_GET['id']) ? $_GET['id'] : null; // Changed code to id
$productName = $availStock = $damageStock = $expirationDate = $categoryId = $batch = $supplier = $oldPrice = $brand = $dosage = $delivery_date = $received = "";
$categories = [];

// Function to fetch old price
function fetchOldPrice($con, $productId) {
    $sql = "SELECT old_price FROM products WHERE id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['old_price'];
        }
        $stmt->close();
    }
    return null;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $productId) {
    // Get the form data from POST
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $delivery = $_POST['delivery_price'];
    $availStock = $_POST['avail_stock'];
    $damageStock = $_POST['damage_stock'];
    $expirationDate = $_POST['expiration_date'];
    $categoryId = $_POST['category_id'];
    $batch = $_POST['batch'];
    $supplier = $_POST['supplier'];
    $oldPrice = $_POST['old_price'];
    $brand = $_POST['brand'];
    $dosage = $_POST['dosage'];
    $received = $_POST['received'];
    $delivery_man = $_POST['delivery_man'];

    $sql = "UPDATE inventory i
    INNER JOIN products p ON i.products_id = p.id
    INNER JOIN others o ON p.id = o.products_id
    SET i.avail_stock = ?, i.price = ?, i.delivery_price = ?, i.damage_stock = ?, 
        p.product_name = ?, p.expiration_date = ?, p.categories_id = ?, 
        o.batch = ?, o.supplier = ?, o.old_price = ?, o.brand = ?, o.dosage = ?, o.received = ?, o.delivery_man = ?
    WHERE i.id = ?";

    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ididssisdisisis", 
            $availStock, 
            $price, 
            $delivery, 
            $damageStock, 
            $productName, 
            $expirationDate, 
            $categoryId, 
            $batch, 
            $supplier, 
            $oldPrice, 
            $brand, 
            $dosage,
            $received,
            $delivery_man,
            $productId
        );
        $stmt->execute();

        // Add error handling for SQL execution
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Inventory updated successfully.";
            $_SESSION['message_type'] = "success";
            header("Location: inventory_table.php"); // Redirect to inventory list after successful update
            exit;
        } else {
            $_SESSION['message'] = "Update failed or no changes were made.";
            $_SESSION['message_type'] = "warning";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Failed to prepare SQL statement.";
        $_SESSION['message_type'] = "danger";
    }
}

// Fetch product details for updating
if ($productId) {
    $sql = "SELECT i.id, i.avail_stock, i.price, i.delivery_price, i.damage_stock, 
                   p.product_name, p.expiration_date, p.categories_id, 
                   o.batch, o.supplier, o.old_price, o.brand, o.dosage, o.received, o.delivery_man,
                   c.category_name
            FROM inventory i
            INNER JOIN products p ON i.products_id = p.id
            INNER JOIN categories c ON p.categories_id = c.id
            INNER JOIN others o ON p.id = o.products_id
            WHERE i.id = ?";

    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $productName = $row['product_name'];
            $availStock = $row['avail_stock'];
            $damageStock = $row['damage_stock'];
            $expirationDate = $row['expiration_date'];
            $categoryId = $row['categories_id'];
            $batch = $row['batch'];
            $supplier = $row['supplier'];
            $price = $row['price'];
            $delivery = $row['delivery_price'];
            $oldPrice = $row['old_price'];
            $brand = $row['brand'];
            $dosage = $row['dosage'];
            $received = $row['received'];
            $delivery_man = $_POST['delivery_man'];
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Failed to prepare SQL statement.";
        $_SESSION['message_type'] = "danger";
    }
}

// Fetch categories for the category dropdown
$sqlCategories = "SELECT id, category_name FROM categories";
$resultCategories = $con->query($sqlCategories);

if ($resultCategories->num_rows > 0) {
    while ($row = $resultCategories->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>



<main id="main" class="main">
    <section class="dashboard section">
        <div class="container" style="margin-top: -30px;">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Update Inventory</h5>

                            <!-- Display alert message -->
                            <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                                    <?= $_SESSION['message'] ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <!-- Form to update inventory -->
                            <form class="g-3 needs-validation row" method="POST" action="update_inventory.php?id=<?= $productId ?>">

                                <!-- Product Name -->
                                <div class="col-md-6">
                                    <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="productName" name="product_name" value="<?= htmlspecialchars($productName) ?>" required>
                                </div>

                                <!-- Category Dropdown -->
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category" name="category_id" required>
                                        <option value="" disabled>Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" <?= ($category['id'] == $categoryId) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($category['category_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                                                <!-- Delivery Price -->
                                <div class="col-md-6">
                                    <label for="deliveryPrice" class="form-label">Price<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="deliveryPrice" name="delivery_price" value="<?= $delivery ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="deliveryPrice" class="form-label">Quantity<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="deliveryPrice" name="delivery_price" value="<?= $delivery ?>" required>
                                </div>

                                <!-- Brand -->
                                <div class="mb-3 col-md-6">
                                    <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="brand" name="brand" value="<?= $brand ?>" required>
                                </div>

                                 <!-- Brand -->
                                 <div class="mb-3 col-md-6">
                                    <label for="brand" class="form-label">Delivery Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="brand" name="received" value="<?= $received ?>" required>
                                </div>

                                <!-- Expiration Date -->
                                <div class="col-md-6">
                                    <label for="expiration_date" class="form-label">Expiration Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="<?= htmlspecialchars($expirationDate) ?>">
                                </div>

                    
                                <!-- Batch -->
                                <div class="mb-3 col-md-6">
                                    <label for="batch" class="form-label">Batch <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="batch" name="batch" value="<?= $batch ?>" required>
                                </div>

                                <!-- Supplier -->
                                <div class="mb-3 col-md-6">
                                    <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" value="<?= htmlspecialchars($supplier) ?>" required>
                                </div>
                                
                                <div class="mb-3 col-md-6">
                                    <label for="delivery_man" class="form-label">Delivery Man <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="delivery_man" name="delivery_man" value="<?= $delivery_man ?>" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="delivery_man" class="form-label">Contact Number of Delivery Man<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="delivery_man" name="delivery_man" value="<?= $delivery_man ?>" required>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Update Inventory</button>
                                    <a href="inventory_table.php" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<!-- JavaScript for Validation -->
<script>
document.getElementById("price").addEventListener("input", validatePrice);
document.getElementById("oldPrice").addEventListener("input", validatePrice);

function validatePrice() {
    var sellingPrice = parseFloat(document.getElementById("price").value);
    var oldPrice = parseFloat(document.getElementById("oldPrice").value);
    var priceErrorMessage = document.getElementById("price-error-message");

    // Hide the error message initially
    priceErrorMessage.style.display = "none";

    // Check if the selling price is lower than the old price
    if (!isNaN(sellingPrice) && !isNaN(oldPrice) && sellingPrice < oldPrice) {
        priceErrorMessage.innerText = "Warning: The Selling Price is lower than the Old Price.";
        priceErrorMessage.style.display = "block"; // Show the error message
    }
}
</script>

