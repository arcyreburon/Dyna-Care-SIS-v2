<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== "Super Admin") {
    header("Location: ../403.php"); // Restrict non-super admins
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['categories_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $brand = $_POST['brand'];
    $received = $_POST['received'];
    $expiration_date = $_POST['expiration_date'];
    $batch = $_POST['batch'];
    $supplier = $_POST['supplier'];
    $delivery_man = $_POST['delivery_man'];
    $contact_number = $_POST['contact_number'];

    // Fetch category name from categories table
    $category_sql = "SELECT category_name FROM categories WHERE id = ?";
    $stmt = $con->prepare($category_sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $category_result = $stmt->get_result();
    $category_name = ($category_result->num_rows > 0) ? $category_result->fetch_assoc()['category_name'] : '';

    // Insert into delivery table
    $insert_sql = "INSERT INTO delivery (product_name, categories_id, price, quantity, brand, received, expiration_date, batch, supplier, delivery_man, contact_number) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($insert_sql);
    $stmt->bind_param("sisisssssis", $product_name, $category_id, $price, $quantity, $brand, $received, $expiration_date, $batch, $supplier, $delivery_man, $contact_number);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Inventory successfully updated!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating inventory!";
        $_SESSION['message_type'] = "danger";
    }

    header("Location: delivery.php");
    exit;
}

// Fetch categories for dropdown
$categories_sql = "SELECT id, category_name FROM categories";
$categories_result = $con->query($categories_sql);
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
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
                                <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type']) ?> alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($_SESSION['message']) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <!-- Form to update inventory -->
                            <form class="g-3 needs-validation row" method="POST" action="add_delivery.php">

                                <!-- Category Dropdown -->
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category" name="categories_id" required>
                                        <option value="" disabled selected>Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['id']) ?>">
                                                <?= htmlspecialchars($category['category_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Product Name -->
                                <div class="col-md-6">
                                    <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="productName" name="product_name" required>
                                </div>

                                <!-- Delivery Price -->
                                <div class="col-md-6">
                                    <label for="deliveryPrice" class="form-label">Price <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="deliveryPrice" name="price" required>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="quantity" name="quantity" required>
                                </div>

                                <!-- Brand -->
                                <div class="mb-3 col-md-6">
                                    <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="brand" name="brand" required>
                                </div>

                                <!-- Delivery Date -->
                                <div class="mb-3 col-md-6">
                                    <label for="received" class="form-label">Delivery Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="received" name="received" required>
                                </div>

                                <!-- Expiration Date -->
                                <div class="col-md-6">
                                    <label for="expiration_date" class="form-label">Expiration Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="expiration_date" name="expiration_date">
                                </div>

                                <!-- Batch -->
                                <div class="mb-3 col-md-6">
                                    <label for="batch" class="form-label">Batch No. / Lot  No.<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="batch" name="batch">
                                </div>

                                <!-- Supplier -->
                                <div class="mb-3 col-md-6">
                                    <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" required>
                                </div>
                                
                                <!-- Delivery Man -->
                                <div class="mb-3 col-md-6">
                                    <label for="delivery_man" class="form-label">Delivery Man <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="delivery_man" name="delivery_man" required>
                                </div>

                                <!-- Contact Number of Delivery Man -->
                                <div class="mb-3 col-md-6">
                                    <label for="contact_number" class="form-label">Contact Number of Delivery Man <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" maxlength="11" oninput="formatPhoneNumber(this)" required>
                                </div>

                                <script>
                                    function formatPhoneNumber(input) {
                                        // Remove non-digits
                                        let value = input.value.replace(/\D/g, '');

                                        // Limit to 11 digits
                                        if (value.length > 11) {
                                            value = value.substring(0, 11);
                                        }

                                        // Format with spacing: '0919 368 6141'
                                        input.value = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
                                    }
                                </script>


                                <!-- Submit Button -->
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Add</button>
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
