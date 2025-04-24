<title>Dashboard</title>
<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Super Admin or Admin only
if ($_SESSION['user_role'] !== 'Cashier') {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
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
                            <h2 class="mt-3 mb-4 text-center">Order Form - Medicine and Supplies</h2>

                            <form action="../send_email.php" method="POST">
                                <!-- Recipient Email -->
                                <div class="form-group">
                                    <label for="recipient">Recipient's Email:</label>
                                    <input type="email" class="form-control" id="recipient" name="recipient" required>
                                </div>

                                <div class="mt-3 row">
                                    <!-- Medicine Section -->
                                    <div id="medicine-section" class="col-md-6">
                                        <h3>Medicine Orders</h3>
                                        <div class="form-group" id="medicine-order-1">
                                            <label for="medicine_name_1">Product Name:</label>
                                            <input type="text" class="form-control" id="medicine_name_1" name="medicine_name_1">
                                            <label for="medicine_quantity_1">Quantity:</label>
                                            <input type="number" class="form-control" id="medicine_quantity_1" name="medicine_quantity_1">
                                            <div class="d-flex justify-content-start">
                                                <button type="button" class="me-2 mt-2 btn btn-primary" onclick="addMedicineOrder()">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                                <button type="button" class="mt-2 btn btn-danger" onclick="removeOrder('medicine-order-1')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Supplies Section -->
                                    <div id="supplies-section" class="col-md-6">
                                        <h3>Supplies Orders</h3>
                                        <div class="form-group" id="supplies-order-1">
                                            <label for="supplies_name_1">Product Name:</label>
                                            <input type="text" class="form-control" id="supplies_name_1" name="supplies_name_1" >
                                            <label for="supplies_quantity_1">Quantity:</label>
                                            <input type="number" class="form-control" id="supplies_quantity_1" name="supplies_quantity_1" >
                                            <div class="d-flex justify-content-start">
                                                <button type="button" class="me-2 mt-2 btn btn-primary" onclick="addSuppliesOrder()">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                                <button type="button" class="mt-2 btn btn-danger" onclick="removeOrder('supplies-order-1')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br><br>

                                <div class="form-group text-start">
                                    <input type="submit" class="btn btn-success" value="Submit Order">
                                    <input type="reset" class="btn btn-secondary" value="Cancel">
                                </div>
                            </form>

                            <script>
                                let medicineCount = 1;
                                let suppliesCount = 1;

                                // Function to add more medicine orders
                                function addMedicineOrder() {
                                    medicineCount++;
                                    const medicineSection = document.getElementById('medicine-section');
                                    const newOrder = document.createElement('div');
                                    newOrder.classList.add('form-group');
                                    newOrder.setAttribute('id', `medicine-order-${medicineCount}`);
                                    newOrder.innerHTML = `
                                        <h3>Medicine Order ${medicineCount}</h3>
                                        <label for="medicine_name_${medicineCount}">Product Name:</label>
                                        <input type="text" class="form-control" id="medicine_name_${medicineCount}" name="medicine_name_${medicineCount}" required><br>
                                        <label for="medicine_quantity_${medicineCount}">Quantity:</label>
                                        <input type="number" class="form-control" id="medicine_quantity_${medicineCount}" name="medicine_quantity_${medicineCount}" required><br>
                                        <div class="d-flex justify-content-start">
                                            <button type="button" class="me-2 btn btn-primary" onclick="addMedicineOrder()">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="removeOrder('medicine-order-${medicineCount}')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </div>
                                    `;
                                    medicineSection.appendChild(newOrder);
                                }

                                // Function to add more supplies orders
                                function addSuppliesOrder() {
                                    suppliesCount++;
                                    const suppliesSection = document.getElementById('supplies-section');
                                    const newOrder = document.createElement('div');
                                    newOrder.classList.add('form-group');
                                    newOrder.setAttribute('id', `supplies-order-${suppliesCount}`);
                                    newOrder.innerHTML = `
                                        <h3>Supplies Order ${suppliesCount}</h3>
                                        <label for="supplies_name_${suppliesCount}">Product Name:</label>
                                        <input type="text" class="form-control" id="supplies_name_${suppliesCount}" name="supplies_name_${suppliesCount}" required><br>
                                        <label for="supplies_quantity_${suppliesCount}">Quantity:</label>
                                        <input type="number" class="form-control" id="supplies_quantity_${suppliesCount}" name="supplies_quantity_${suppliesCount}" required><br>
                                        <div class="d-flex justify-content-start">
                                            <button type="button" class="me-2 btn btn-primary" onclick="addSuppliesOrder()">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="removeOrder('supplies-order-${suppliesCount}')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </div>
                                    `;
                                    suppliesSection.appendChild(newOrder);
                                }

                                // Function to remove orders
                                function removeOrder(orderId) {
                                    const orderElement = document.getElementById(orderId);
                                    if (orderElement) {
                                        orderElement.remove();
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
