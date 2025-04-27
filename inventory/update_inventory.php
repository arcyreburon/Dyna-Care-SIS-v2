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

$userRole = $_SESSION['user_role'];
$branchId = $_SESSION['branches_id'];
$productId = isset($_GET['id']) ? $_GET['id'] : null;
$productName = $availStock = $damageStock = $expirationDate = $categoryId = $batch = $oldPrice = $brand = $dosage = $received = "";
$categories = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $productId) {
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $delivery = $_POST['delivery_price'];
    $availStock = $_POST['avail_stock'];
    $damageStock = $_POST['damage_stock'];
    $expirationDate = $_POST['expiration_date'];
    $categoryId = $_POST['category_id'];
    $batch = $_POST['batch'];
    $oldPrice = $_POST['old_price'];
    $brand = $_POST['brand'];
    $dosage = $_POST['dosage'];
    $received = $_POST['received'];

    // SQL Update query with conditional branch check
    $sql = "UPDATE inventory i
    INNER JOIN products p ON i.products_id = p.id
    SET i.avail_stock = ?, i.price = ?, i.delivery_price = ?, i.damage_stock = ?, 
        p.product_name = ?, p.expiration_date = ?, p.categories_id = ?, 
        i.batch = ?, i.old_price = ?, i.brand = ?, i.dosage = ?, i.received = ?";

    // If the user is not a Super Admin, restrict updates to their branch
    if ($userRole !== "Super Admin") {
        $sql .= " WHERE i.id = ? AND i.branches_id = ?";
    } else {
        $sql .= " WHERE i.id = ?";
    }

    $stmt = $con->prepare($sql);
    if ($stmt) {
        if ($userRole !== "Super Admin") {
            $stmt->bind_param("ididssidisisii", 
                $availStock, $price, $delivery, $damageStock, 
                $productName, $expirationDate, $categoryId, 
                $batch, $oldPrice, $brand, $dosage, $received, 
                $productId, $branchId
            );
        } else {
            $stmt->bind_param("ididssidisisi", 
                $availStock, $price, $delivery, $damageStock, 
                $productName, $expirationDate, $categoryId, 
                $batch, $oldPrice, $brand, $dosage, $received, 
                $productId
            );
        }
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Inventory updated successfully.";
            $_SESSION['message_type'] = "success";
            header("Location: inventory_table.php");
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
                   i.batch, i.old_price, i.brand, i.dosage, i.received, 
                   c.category_name
            FROM inventory i
            INNER JOIN products p ON i.products_id = p.id
            INNER JOIN categories c ON p.categories_id = c.id
            WHERE i.id = ?";

    // Restrict query to branch if the user is not Super Admin
    if ($userRole !== "Super Admin") {
        $sql .= " AND i.branches_id = ?";
    }

    $stmt = $con->prepare($sql);
    if ($stmt) {
        if ($userRole !== "Super Admin") {
            $stmt->bind_param("ii", $productId, $branchId);
        } else {
            $stmt->bind_param("i", $productId);
        }
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
            $price = $row['price'];
            $delivery = $row['delivery_price'];
            $oldPrice = $row['old_price'];
            $brand = $row['brand'];
            $dosage = $row['dosage'];
            $received = $row['received'];
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
        <div class="container">
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

                                <style>
                                    .input-wrapper {
                                        position: relative;
                                        display: flex;
                                        align-items: center;
                                    }

                                    .peso-sign {
                                        position: absolute;
                                        left: 10px; /* Adjust as needed */
                                        font-size: 16px;
                                        font-weight: bold;
                                    }

                                    .currency-input {
                                        padding-left: 25px; /* Space for peso sign */
                                        text-align: right; /* Align numbers to the right */
                                        font-size: 16px;
                                        width: 100%;
                                    }

                                </style>

                                <!-- Selling Price -->
                                <div class="mb-3 col-md-6 input-container">
                                    <label for="price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                                    <div class="input-wrapper">
                                        <span class="peso-sign">₱</span>
                                        <input type="text" class="form-control currency-input" id="price" name="price" value="<?= $price ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required>
                                    </div>
                                    <span id="price-error-message" style="color: red; display: none;"></span>
                                </div>


                                    <!-- Old Price -->
                                <div class="mb-3 col-md-6 input-container">
                                    <label for="oldPrice" class="form-label">Old Price</label>
                                    <div class="input-wrapper">
                                        <span class="peso-sign">₱</span>
                                        <input type="text" class="form-control currency-input" id="oldPrice" name="old_price" value="<?= $oldPrice ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required>
                                    </div>
                                </div>



                                                                <!-- Delivery Price -->
                                <div class="col-md-6">
                                    <label for="deliveryPrice" class="form-label">Delivery Price <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="deliveryPrice" name="delivery_price" value="<?= $delivery ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required>
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

                                <!-- Dosage -->
                                <div class="mb-3 col-md-6">
                                    <label for="dosage" class="form-label">Dosage <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="dosage" name="dosage" value="<?= $dosage ?>">
                                </div>

                                <!-- Available Stock -->
                                <div class="col-md-6">
                                    <label for="availStock" class="form-label">Available Stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="availStock" name="avail_stock" value="<?= $availStock ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required>
                                </div>

                                <!-- Damaged Stock -->
                                <div class="col-md-6">
                                    <label for="damageStock" class="form-label">Damaged Stock</label>
                                    <input type="number" class="form-control" id="damageStock" name="damage_stock" value="<?= $damageStock ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>

                                <!-- Expiration Date -->
                                <div class="col-md-6">
                                    <label for="expiration_date" class="form-label">Expiration Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="<?= htmlspecialchars($expirationDate) ?>">
                                </div>

                    
                                <!-- Batch -->
                                <div class="mb-3 col-md-6">
                                    <label for="batch" class="form-label">Batch No. / Lot No.<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="batch" name="batch" value="<?= $batch ?>" required>
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

