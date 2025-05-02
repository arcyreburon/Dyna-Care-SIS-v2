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

// Initialize all variables with default values
$productId = isset($_GET['id']) ? $_GET['id'] : null;
$productName = $availStock = $damageStock = $expirationDate = $categoryId = $batch = $supplier = "";
$oldPrice = $brand = $dosage = $delivery_date = $received = $delivery_man = $contact_number = "";
$price = $delivery = $quantity = 0;
$categories = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $productId) {
    $productName = $_POST['product_name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $delivery = $_POST['delivery_price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    $availStock = $_POST['avail_stock'] ?? 0;
    $damageStock = $_POST['damage_stock'] ?? 0;
    $expirationDate = $_POST['expiration_date'] ?? '';
    $categoryId = $_POST['category_id'] ?? '';
    $batch = $_POST['batch'] ?? '';
    $supplier = $_POST['supplier'] ?? '';
    $oldPrice = $_POST['old_price'] ?? 0;
    $brand = $_POST['brand'] ?? '';
    $dosage = $_POST['dosage'] ?? '';
    $received = $_POST['received'] ?? '';
    $delivery_man = $_POST['delivery_man'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';

    $sql = "UPDATE inventory i
            INNER JOIN products p ON i.products_id = p.id
            INNER JOIN others o ON p.id = o.products_id
            SET i.avail_stock = ?, i.price = ?, i.delivery_price = ?, i.damage_stock = ?, 
                p.product_name = ?, p.expiration_date = ?, p.categories_id = ?, 
                o.batch = ?, o.supplier = ?, o.old_price = ?, o.brand = ?, o.dosage = ?,
                o.received = ?, o.delivery_man = ?, o.contact_number = ?
            WHERE i.id = ?";

    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ididssisssdsssssi", 
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
            $contact_number,
            $productId
        );
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

// Fetch product details
if ($productId) {
    $sql = "SELECT i.id, i.avail_stock, i.price, i.delivery_price, i.damage_stock, 
                   p.product_name, p.expiration_date, p.categories_id, 
                   o.batch, o.supplier, o.old_price, o.brand, o.dosage, 
                   o.received, o.delivery_man, o.contact_number
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
            $delivery_man = $row['delivery_man'];
            $contact_number = $row['contact_number'];
        }
        $stmt->close();
    }
}

// Fetch categories
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
?>

<main id="main" class="main">
    <section class="dashboard section">
        <div class="container" style="margin-top: -30px;">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Update Inventory</h5>

                            <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                                    <?= $_SESSION['message'] ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <form class="g-3 needs-validation row" method="POST" action="update_inventory.php?id=<?= $productId ?>">

                                <div class="col-md-6">
                                    <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="productName" name="product_name" value="<?= htmlspecialchars($productName) ?>" required>
                                </div>

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

                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price<span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= $price ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Quantity<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= $availStock ?>" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="brand" name="brand" value="<?= $brand ?>" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="received" class="form-label">Delivery Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="received" name="received" value="<?= $received ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="expiration_date" class="form-label">Expiration Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="<?= htmlspecialchars($expirationDate) ?>" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="batch" class="form-label">Batch <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="batch" name="batch" value="<?= $batch ?>" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" value="<?= htmlspecialchars($supplier) ?>" required>
                                </div>
                                
                                <div class="mb-3 col-md-6">
                                    <label for="delivery_man" class="form-label">Delivery Man <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="delivery_man" name="delivery_man" value="<?= $delivery_man ?>" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?= htmlspecialchars($contact_number) ?>" maxlength="11" oninput="formatPhoneNumber(this)" required>
                                </div>

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

<script>
    function formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        input.value = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
    }
</script>

<?php include '../includes/footer.php'; ?>
