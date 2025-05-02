<title>DynaCareSIS - sales</title>
<?php
session_start();
include "../db_conn.php";

// Restrict access to Cashiers only
if ($_SESSION['user_role'] !== "Cashier") {
    header("Location: ../403.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure transaction_no is set
    if (!isset($_POST['transaction_no']) || empty($_POST['transaction_no'])) {
        die("Error: Transaction number is missing.");
    }

    $transaction_no = intval($_POST['transaction_no']);
    $cart = json_decode($_POST['cart'], true);
    $discount = floatval($_POST['discount']);
    $order_processed = true; // Flag to check if the order was processed


    foreach ($cart as $item) {
        if (!isset($item['id']) || !isset($item['quantity'])) {
            die("Error: Invalid cart data.");
        }

        $product_id = intval($item['id']);
        $quantity = intval($item['quantity']);
        $total_price = floatval($item['unitPrice']) * $quantity;

        // Check current stock
        $check_stock_query = "SELECT avail_stock FROM inventory WHERE products_id = ?";
        $stmt = $con->prepare($check_stock_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $current_stock = $row['avail_stock'];
            $new_stock = $current_stock - $quantity;

            if ($current_stock >= $quantity) {
                // Update inventory
                $update_stock_query = "UPDATE inventory SET avail_stock = ? WHERE products_id = ?";
                $update_stmt = $con->prepare($update_stock_query);
                $update_stmt->bind_param("ii", $new_stock, $product_id);

                if (!$update_stmt->execute()) {
                    die("Error updating stock: " . $update_stmt->error);
                }

                // Insert transaction
                $insert_transaction_query = "INSERT INTO transaction (products_id, total_price, discount, transaction_no) VALUES (?, ?, ?, ?)";
                $insert_stmt = $con->prepare($insert_transaction_query);
                $insert_stmt->bind_param("idii", $product_id, $total_price, $discount, $transaction_no);

                if (!$insert_stmt->execute()) {
                    die("Error inserting transaction: " . $insert_stmt->error);
                }
            } else {
                echo "Not enough stock for product ID: $product_id<br>";
                $order_processed = false; // If any product doesn't have enough stock, the order fails
            }
        } else {
            echo "No inventory found for product ID: $product_id<br>";
            $order_processed = false; // If inventory not found, the order fails
        }
    }

    // If the order was processed successfully
    if ($order_processed) {
        $_SESSION['success_message'] = 'Order processed successfully!';
    } else {
        $_SESSION['error_message'] = 'Order could not be completed due to insufficient stock or inventory issues.';
    }

    header("Location: sales.php");
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>


<main id="main" class="main">
    <section class="dashboard section">
        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-dismissible alert-success fade show" role="alert">
            <?php echo $_SESSION['success_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
        <?php elseif (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                    <div class="d-flex align-items-center justify-content-between card-header">
                        <h4>Products</h4>
                        <a href="../cashier/reorder_form.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle-fill"></i> Re-Order
                        </a>
                    </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="myTable" class="custom-table table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                // Fetch products with category names and inventory details from the database
                if (isset($_SESSION['branches_id'])) {
                    $branches_id = $_SESSION['branches_id']; // Get branch_id from session
                }
                $sql = "
                SELECT p.id, p.product_name, i.price, c.category_name, i.avail_stock
                FROM products p
                INNER JOIN categories c ON p.categories_id = c.id
                INNER JOIN inventory i ON p.id = i.products_id
                WHERE p.branches_id = ?";  // Filter by branch_id

                // Prepare and execute the query
                $stmt = $con->prepare($sql);
                $stmt->bind_param("i", $branches_id); // Bind the branch_id parameter
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Loop through the fetched data and display it in the table
                    while ($row = $result->fetch_assoc()) {
                        // Check if the avail_stock is zero
                        $disabled = ($row["avail_stock"] == 0) ? "disabled" : "";
                        echo "<tr data-id='" . $row["id"] . "' data-name='" . $row["product_name"] . "' data-category='" . $row["category_name"] . "' data-stock='" . $row["avail_stock"] . "' data-price='" . $row["price"] . "'>";
                        echo "<td>" . $row["product_name"] . "</td>";
                        echo "<td>" . $row["category_name"] . "</td>";
                        echo "<td>" . $row["avail_stock"] . "</td>";
                        echo "<td>₱ " . $row["price"] . "</td>";
                        echo "<td><button class='add-to-cart btn btn-success' data-id='" . $row["id"] . "' $disabled><i class='bi bi-cart-plus'></i></button></td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products found for this branch.</td></tr>";
                }

                // Close the prepared statement
                $stmt->close();
                ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Customer Orders</h4>
                        </div>
                        <div class="card-body">



                            <form method="POST" id="order-form">
                            <div class="form-group row">
        <label for="transaction-id" class="col-form-label col-sm-4" style="margin-right: -50;"><strong>Transaction No.:</strong></label>
        <div class="col-sm-8">
            <span class="form-control-plaintext font-weight-bold" id="transaction-id-display"></span>
            <input type="hidden" id="hidden-transaction-id" name="transaction_no">
        </div>
    </div>
                                <table id="myTable" class="custom-table table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Items</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items" class="text-center">
                                        <!-- Cart items dynamically added here -->
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-end mb-3">
                                    <h5 class="mb-0">Grand Total: ₱<span id="grand-total">0</span></h5>
                                </div>

                                <div class="form-group row">
                                    <label for="vat" class="col-form-label col-sm-4">VAT (%):</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="vat" name="vat" value="12" readonly>
                                    </div>
                                </div>

                                <script>
                                    function applyVAT(price) {
                                        const vatPercentage = 12; // Fixed VAT
                                        return (price * vatPercentage / 100).toFixed(2); // Calculates VAT amount
                                    }
                                </script>


                                <div class="form-group row">
                                    <label for="discount" class="col-form-label col-sm-4">Discount:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="discount" name="discount">
                                            <option value="0">0%</option>
                                            <option value="20">20%</option>
                                            <option value="40">40%</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mt-2 row">
                                    <label for="cashTendered" class="col-form-label col-sm-4">Cash Tendered:</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="cashTendered" value="">
                                        <span class="text-danger d-none" id="insufficient-balance">Insufficient Balance</span>
                                    </div>
                                </div>

                                <div class="mt-2 mb-2 text-right">
                                    <h5>Change: ₱<span id="change">0</span></h5>
                                </div>

                                <div class="col-md-12">
                                    <button type="button" class="btn btn-success" id="process-order">Process
                                        Order</button>
                                </div>

                                
                                <input type="hidden" name="cart" id="cart-data">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                new simpleDatatables.DataTable("#myTable");
            });
        </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        let cartItems = [];
        const vatElement = document.getElementById('vat');
        const discountElement = document.getElementById('discount');
        const cashTenderedElement = document.getElementById('cashTendered');
        const processOrderButton = document.getElementById('process-order');
        const insufficientBalanceSpan = document.getElementById('insufficient-balance');
        const transactionIdDisplay = document.getElementById('transaction-id-display');
        const transactionIdInput = document.getElementById('hidden-transaction-id');
        const grandTotalElement = document.getElementById('grand-total');
        const changeElement = document.getElementById('change');

       function generateTransactionNumber() {
    const number = Math.floor(Math.random() * 100000);
    return number.toString().padStart(5, '0');
}



        function calculateTotalPrice() {
            return cartItems.reduce((total, item) => total + item.quantity * item.unitPrice, 0);
        }

        function calculateGrandTotal(totalPrice) {
            const vat = parseFloat(vatElement.value) / 100;
            const discount = parseFloat(discountElement.value) / 100;
            const vatAmount = totalPrice * vat;
            const discountAmount = totalPrice * discount;
            return totalPrice + vatAmount - discountAmount;
        }

        function updateCartItems() {
            const cartItemsContainer = document.getElementById('cart-items');
            cartItemsContainer.innerHTML = '';

            cartItems.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>
                        <div class="input-group">
                            <button class="btn-outline-secondary btn" type="button" onclick="decreaseQuantity(${index})">−</button>
                            <input type="number" value="${item.quantity}" min="1" class="form-control text-center" data-index="${index}" onchange="updateQuantity(${index}, this.value)">
                            <button class="btn-outline-secondary btn" type="button" onclick="increaseQuantity(${index})">+</button>
                        </div>
                    </td>
                    <td>₱${item.unitPrice.toFixed(2)}</td>
                    <td>₱${(item.quantity * item.unitPrice).toFixed(2)}</td>
                    <td><button class="btn btn-danger" onclick="removeItem(${index})"><i class="bi bi-trash"></i></button></td>
                `;
                cartItemsContainer.appendChild(row);
            });

            updateGrandTotal();
            toggleInputs();
        }

        function updateGrandTotal() {
            const totalPrice = calculateTotalPrice();
            const grandTotal = calculateGrandTotal(totalPrice);
            grandTotalElement.innerText = grandTotal.toFixed(2);
            updateChange();
        }

        function updateChange() {
            const grandTotal = parseFloat(grandTotalElement.innerText);
            const cashTendered = parseFloat(cashTenderedElement.value) || 0;
            const change = cashTendered - grandTotal;

            // Hide change if no cash tendered or cash tendered is less than grand total
            if (cashTendered === 0 || cashTendered < grandTotal) {
                changeElement.innerText = ''; // Hide change amount
            } else {
                changeElement.innerText = change.toFixed(2); // Show change amount
            }

            // Show or hide insufficient balance message
            insufficientBalanceSpan.classList.toggle('d-none', cashTendered >= grandTotal || cashTendered === 0);

            // Disable process order button if cash tendered is insufficient or not inputted
            processOrderButton.disabled = cartItems.length === 0 || cashTendered < grandTotal || cashTendered === 0;
        }

        function toggleInputs() {
            const isCartEmpty = cartItems.length === 0;

            vatElement.disabled = isCartEmpty;
            discountElement.disabled = isCartEmpty;
            cashTenderedElement.disabled = isCartEmpty;

            if (isCartEmpty) {
                grandTotalElement.innerText = '0.00';
                changeElement.innerText = '0.00';
                insufficientBalanceSpan.classList.add('d-none');
                processOrderButton.disabled = true;
            } else {
                updateChange(); // Ensure button status updates correctly
            }
        }

        vatElement.addEventListener('change', updateGrandTotal);
        discountElement.addEventListener('change', updateGrandTotal);
        cashTenderedElement.addEventListener('input', updateChange);

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function () {
                const row = this.closest('tr');
                const id = row.getAttribute('data-id');
                const name = row.getAttribute('data-name');
                const category = row.getAttribute('data-category');
                const stock = parseInt(row.getAttribute('data-stock'));
                const price = parseFloat(row.getAttribute('data-price'));

                const existingItemIndex = cartItems.findIndex(item => item.id === id);

                if (existingItemIndex !== -1) {
                    cartItems[existingItemIndex].quantity += 1;
                } else {
                    cartItems.push({
                        id,
                        name,
                        category,
                        quantity: 1,
                        unitPrice: price
                    });
                }

                row.setAttribute('data-stock', stock - 1);
                row.querySelector('td:nth-child(3)').innerText = stock - 1;
                updateCartItems();
            });
        });

        window.increaseQuantity = function (index) {
            const item = cartItems[index];
            const productRow = document.querySelector(`tr[data-id="${item.id}"]`);
            let stock = parseInt(productRow.getAttribute('data-stock'));

            if (stock > 0) {
                item.quantity += 1;
                stock -= 1;
                productRow.setAttribute('data-stock', stock);
                productRow.querySelector('td:nth-child(3)').innerText = stock;
            } else {
                alert("Not enough stock available.");
            }

            updateCartItems();
        };

        window.decreaseQuantity = function (index) {
            const item = cartItems[index];
            const productRow = document.querySelector(`tr[data-id="${item.id}"]`);
            let stock = parseInt(productRow.getAttribute('data-stock'));

            if (item.quantity > 1) {
                item.quantity -= 1;
                stock += 1;
                productRow.setAttribute('data-stock', stock);
                productRow.querySelector('td:nth-child(3)').innerText = stock;
            }

            updateCartItems();
        };

        window.updateQuantity = function (index, quantity) {
            const item = cartItems[index];
            const productRow = document.querySelector(`tr[data-id="${item.id}"]`);
            let stock = parseInt(productRow.getAttribute('data-stock')) + item.quantity;

            const newQuantity = parseInt(quantity);

            if (newQuantity > stock) {
                alert("Not enough stock available.");
                return;
            }

            productRow.setAttribute('data-stock', stock - newQuantity);
            productRow.querySelector('td:nth-child(3)').innerText = stock - newQuantity;

            item.quantity = newQuantity;
            updateCartItems();
        };

        window.removeItem = function (index) {
            cartItems.splice(index, 1);
            updateCartItems();
        };

        document.getElementById('process-order').addEventListener('click', function () {
            const cartData = JSON.stringify(cartItems);
            document.getElementById('cart-data').value = cartData;

            const transactionNo = generateTransactionNumber();
            transactionIdDisplay.innerText = transactionNo;
            transactionIdInput.value = transactionNo;

            document.getElementById('order-form').submit();
        });

        const transactionNo = generateTransactionNumber();
    transactionIdDisplay.innerText = transactionNo;
    transactionIdInput.value = transactionNo;

    toggleInputs();
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

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .btn i {
                margin-right: 5px;
            }

            .table-responsive {
                max-height: 400px;
                overflow-y: auto;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-control {
                height: calc(2.25rem + 2px);
                padding: .375rem .75rem;
            }

            .btn-primary,
            .btn-danger,
            .btn-success {
                color: #fff;
            }

            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }

            .btn-danger {
                background-color: #dc3545;
                border-color: #dc3545;
            }

            .btn-success {
                background-color: #28a745;
                border-color: #28a745;
            }

            .btn-secondary {
                background-color: #6c757d;
                border-color: #6c757d;
                color: #fff;
            }

            .btn-secondary:hover,
            .btn-secondary:focus {
                background-color: #565e64;
                border-color: #4e555b;
            }
        </style>
    </section>
</main>