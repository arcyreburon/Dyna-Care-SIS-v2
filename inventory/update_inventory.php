<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['user_role'] !== "Inventory Clerk" && $_SESSION['user_role'] !== "Super Admin" && $_SESSION['user_role'] !== "Admin") {
    header("Location: ../403.php");
    exit;
}

$userRole = $_SESSION['user_role'];
$branchId = $_SESSION['branches_id'];
$productId = isset($_GET['id']) ? $_GET['id'] : null;

// Initialize all possible fields
$productName = $availStock = $damageStock = $expirationDate = $categoryId = $batch = $oldPrice = $brand = $dosage = $received = "";
$strength = $generic_name = $supply_type = $size = $model_number = $warranty = "";
$categories = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $productId) {
    // Common fields
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $delivery = $_POST['delivery_price'];
    $availStock = $_POST['avail_stock'];
    $damageStock = $_POST['damage_stock'];
    $categoryId = $_POST['category_id'];
    $oldPrice = $_POST['old_price'];
    $brand = $_POST['brand'];
    $received = $_POST['received'];
    
    // Medicine-specific fields
    $expirationDate = $_POST['expiration_date'] ?? null;
    $batch = $_POST['batch'] ?? null;
    $dosage = $_POST['dosage'] ?? null;
    $strength = $_POST['strength'] ?? null;
    $generic_name = $_POST['generic_name'] ?? null;
    
    // Supply-specific fields
    $supply_type = $_POST['supply_type'] ?? null;
    $size = $_POST['size'] ?? null;
    $model_number = $_POST['model_number'] ?? null;
    $warranty = $_POST['warranty'] ?? null;

    $sql = "UPDATE inventory i
            INNER JOIN products p ON i.products_id = p.id
            SET i.avail_stock = ?, i.price = ?, i.delivery_price = ?, i.damage_stock = ?, 
                p.product_name = ?, p.expiration_date = ?, p.categories_id = ?, 
                i.batch = ?, i.old_price = ?, i.brand = ?, i.dosage = ?, i.received = ?,
                i.strength = ?, i.generic_name = ?, i.supply_type = ?, i.size = ?,
                i.model_number = ?, i.warranty = ?";

    if ($userRole !== "Super Admin") {
        $sql .= " WHERE i.id = ? AND i.branches_id = ?";
    } else {
        $sql .= " WHERE i.id = ?";
    }

    $stmt = $con->prepare($sql);
    if ($stmt) {
        if ($userRole !== "Super Admin") {
            $stmt->bind_param("ididssidisisssssssii", 
                $availStock, $price, $delivery, $damageStock, 
                $productName, $expirationDate, $categoryId, 
                $batch, $oldPrice, $brand, $dosage, $received,
                $strength, $generic_name, $supply_type, $size,
                $model_number, $warranty,
                $productId, $branchId
            );
        } else {
            $stmt->bind_param("ididssidisisssssssi", 
                $availStock, $price, $delivery, $damageStock, 
                $productName, $expirationDate, $categoryId, 
                $batch, $oldPrice, $brand, $dosage, $received,
                $strength, $generic_name, $supply_type, $size,
                $model_number, $warranty,
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

if ($productId) {
    $sql = "SELECT i.id, i.avail_stock, i.price, i.delivery_price, i.damage_stock, 
                   p.product_name, p.expiration_date, p.categories_id, 
                   i.batch, i.old_price, i.brand, i.dosage, i.received, 
                   i.strength, i.generic_name, i.supply_type, i.size,
                   i.model_number, i.warranty,
                   c.category_name
            FROM inventory i
            INNER JOIN products p ON i.products_id = p.id
            INNER JOIN categories c ON p.categories_id = c.id
            WHERE i.id = ?";

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
            $strength = $row['strength'];
            $generic_name = $row['generic_name'];
            $supply_type = $row['supply_type'];
            $size = $row['size'];
            $model_number = $row['model_number'];
            $warranty = $row['warranty'];
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Failed to prepare SQL statement.";
        $_SESSION['message_type'] = "danger";
    }
}

$sqlCategories = "SELECT id, category_name FROM categories";
$resultCategories = $con->query($sqlCategories);

if ($resultCategories->num_rows > 0) {
    while ($row = $resultCategories->fetch_assoc()) {
        $categories[] = $row;
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

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

.medicine-field {
    transition: all 0.3s ease;
}
</style>



<main id="main" class="main">
    <section class="dashboard section">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Update Inventory</h5>
                            <span class="text-danger">* Indicates required question</span>
                            <br>
                            <br>

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
                                <!-- Common Fields -->
                                <div class="col-md-6">
                                    <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="productName" name="product_name" value="<?= htmlspecialchars($productName) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category" name="category_id" required onchange="toggleFieldsByCategory()">
                                        <option value="" disabled>Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" <?= ($category['id'] == $categoryId) ? 'selected' : ''; ?> data-category-type="<?= strtolower($category['category_name']) ?>">
                                                <?= htmlspecialchars($category['category_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <!-- Pricing Fields -->
                                <div class="mb-3 col-md-6 input-container">
                                    <label for="price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                                    <div class="input-wrapper"><span class="peso-sign">₱</span><input type="text" class="form-control currency-input" id="price" name="price" value="<?= $price ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required></div>
                                    <span id="price-error-message" style="color: red; display: none;"></span>
                                </div>
                                
                                <div class="mb-3 col-md-6 input-container">
                                    <label for="oldPrice" class="form-label">Old Price</label>
                                    <div class="input-wrapper">
                                        <span class="peso-sign">₱</span>
                                        <input type="text" class="form-control currency-input" id="oldPrice" name="old_price" value="<?= $oldPrice ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="deliveryPrice" class="form-label">Delivery Price <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="deliveryPrice" name="delivery_price" value="<?= $delivery ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required>
                                </div>
                                
                                <!-- Delivery Date -->
                                <div class="mb-3 col-md-6">
                                    <label for="received" class="form-label">Delivery Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="received" name="received" value="<?= $received ?>" required>
                                </div>

                                <!-- Medicine-specific Fields -->
                                <div class="mb-3 col-md-6 medicine-field">
                                    <label for="dosage" class="form-label">Dosage Form</label>
                                    <select class="form-select" id="dosage" name="dosage">
                                        <option value="">Select Dosage Form</option>
                                        <option value="Tablet" <?= ($dosage == 'Tablet') ? 'selected' : '' ?>>Tablet</option>
                                        <option value="Capsule" <?= ($dosage == 'Capsule') ? 'selected' : '' ?>>Capsule</option>
                                        <option value="Syrup" <?= ($dosage == 'Syrup') ? 'selected' : '' ?>>Syrup</option>
                                        <option value="Injection" <?= ($dosage == 'Injection') ? 'selected' : '' ?>>Injection</option>
                                        <option value="Ointment" <?= ($dosage == 'Ointment') ? 'selected' : '' ?>>Ointment</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6 medicine-field">
                                    <label for="strength" class="form-label">Strength</label>
                                    <input type="text" class="form-control" id="strength" name="strength" value="<?= $strength ?? '' ?>" placeholder="e.g., 500mg, 10mg/mL">
                                </div>

                                <div class="mb-3 col-md-6 medicine-field">
                                    <label for="generic_name" class="form-label">Generic Name</label>
                                    <input type="text" class="form-control" id="generic_name" name="generic_name" value="<?= $generic_name ?? '' ?>">
                                </div>

                                <!-- Supply-specific Fields -->
                                <div class="mb-3 col-md-6 supply-field" style="display: none;">
                                    <label for="supply_type" class="form-label">Supply Type</label>
                                    <select class="form-select" id="supply_type" name="supply_type">
                                        <option value="">Select Type</option>
                                        <option value="Medical Equipment" <?= ($supply_type == 'Medical Equipment') ? 'selected' : '' ?>>Medical Equipment</option>
                                        <option value="Disposable" <?= ($supply_type == 'Disposable') ? 'selected' : '' ?>>Disposable</option>
                                        <option value="Surgical" <?= ($supply_type == 'Surgical') ? 'selected' : '' ?>>Surgical</option>
                                        <option value="Diagnostic" <?= ($supply_type == 'Diagnostic') ? 'selected' : '' ?>>Diagnostic</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6 supply-field" style="display: none;">
                                    <label for="size" class="form-label">Size/Dimensions</label>
                                    <input type="text" class="form-control" id="size" name="size" value="<?= $size ?? '' ?>" placeholder="e.g., Large, 10x10cm">
                                </div>

                                <!-- Common Stock Fields -->
                                <div class="col-md-6">
                                    <label for="availStock" class="form-label">Available Stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="availStock" name="avail_stock" value="<?= $availStock ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="damageStock" class="form-label">Damaged Stock</label>
                                    <input type="number" class="form-control" id="damageStock" name="damage_stock" value="<?= $damageStock ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>

                                <!-- Medicine-specific Fields -->
                                <div class="col-md-6 medicine-field">
                                    <label for="expiration_date" class="form-label">Expiration Date</label>
                                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="<?= htmlspecialchars($expirationDate) ?>">
                                </div>

                                <div class="mb-3 col-md-6 medicine-field">
                                    <label for="batch" class="form-label">Batch No. / Lot No.</label>
                                    <input type="text" class="form-control" id="batch" name="batch" value="<?= $batch ?>" required>
                                </div>

                                <!-- Supply-specific Fields -->
                                <div class="mb-3 col-md-6 supply-field" style="display: none;">
                                    <label for="model_number" class="form-label">Model/Part Number</label>
                                    <input type="text" class="form-control" id="model_number" name="model_number" value="<?= $model_number ?? '' ?>">
                                </div>

                                <div class="mb-3 col-md-6 supply-field" style="display: none;">
                                    <label for="batch" class="form-label">Batch No. / Lot No.</label>
                                    <input type="text" class="form-control" id="batch" name="batch" value="<?= $batch ?>">
                                </div>

                                <div class="mb-3 col-md-6 supply-field" style="display: none;">
                                    <label for="warranty" class="form-label">Warranty Period</label>
                                    <input type="text" class="form-control" id="warranty" name="warranty" value="<?= $warranty ?? '' ?>" placeholder="e.g., 1 year, 6 months">
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Update Inventory</button>
                                    <a href="inventory_table.php" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>

                            <script>
                            function toggleFieldsByCategory() {
                                const categorySelect = document.getElementById('category');
                                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                                const categoryType = selectedOption.getAttribute('data-category-type');
                                
                                // Medicine fields
                                const medicineFields = document.querySelectorAll('.medicine-field');
                                // Supply fields
                                const supplyFields = document.querySelectorAll('.supply-field');
                                
                                if (categoryType.includes('medicine')) {
                                    // Show medicine fields and hide supply fields
                                    medicineFields.forEach(field => field.style.display = 'block');
                                    supplyFields.forEach(field => field.style.display = 'none');
                                    
                                    // Set required fields for medicine
                                    document.getElementById('expiration_date').required = true;
                                    document.getElementById('batch').required = true;
                                    document.getElementById('dosage').required = true;
                                } else {
                                    // Show supply fields and hide medicine fields
                                    medicineFields.forEach(field => field.style.display = 'none');
                                    supplyFields.forEach(field => field.style.display = 'block');
                                    
                                    // Remove required from medicine fields
                                    document.getElementById('expiration_date').required = false;
                                    document.getElementById('batch').required = false;
                                    document.getElementById('dosage').required = false;
                                    
                                    // Set required fields for supplies
                                    document.getElementById('supply_type').required = true;
                                }
                            }

                            // Initialize on page load
                            document.addEventListener('DOMContentLoaded', function() {
                                toggleFieldsByCategory();
                            });
                            </script>
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

