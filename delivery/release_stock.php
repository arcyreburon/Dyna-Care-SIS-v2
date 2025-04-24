<title>Sales</title>
<?php
session_start();
include "../db_conn.php";

// Restrict access to authorized roles only
if (!in_array($_SESSION['user_role'], ["Super Admin", "Admin", "Inventory Clerk"])) {
    header("Location: ../403.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart = json_decode($_POST['cart'], true);
    $order_processed = true; // Flag to check if the order was processed
    $branches_id = intval($_POST['branch_id']); // Fetch the branch ID from the form

    foreach ($cart as $item) {
        if (!isset($item['id']) || !isset($item['quantity'])) {
            die("Error: Invalid cart data.");
        }

        $product_id = intval($item['id']);
        $quantity = intval($item['quantity']);

        // Check current stock
        $check_stock_query = "SELECT quantity FROM delivery WHERE id = ?";
        $stmt = $con->prepare($check_stock_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $current_stock = $row['quantity'];
            $new_stock = $current_stock - $quantity;

            if ($current_stock >= $quantity) {
                // Update inventory
                $update_stock_query = "UPDATE delivery SET quantity = ? WHERE id = ?";
                $update_stmt = $con->prepare($update_stock_query);
                $update_stmt->bind_param("ii", $new_stock, $product_id);

                if (!$update_stmt->execute()) {
                    die("Error updating stock: " . $update_stmt->error);
                }

                // Insert into release_stock table
                $insert_release_stock_query = "INSERT INTO release_stock (delivery_id, branches_id, quantity) VALUES (?, ?, ?)";
                $insert_release_stmt = $con->prepare($insert_release_stock_query);
                $insert_release_stmt->bind_param("iii", $product_id, $branches_id, $quantity);

                if (!$insert_release_stmt->execute()) {
                    die("Error inserting into release_stock: " . $insert_release_stmt->error);
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

    // Incomplete
    // If the order was processed successfully
    if ($order_processed) {
        $_SESSION['success_message'] = 'You successfully release product to *branch*';
    } else {
        $_SESSION['error_message'] = 'Release could not be completed due to insufficient stock or inventory issues.';
    }

    header("Location: release_stock.php");
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
                        <div class="card-header">
                            <h4>Available Stock</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="productTable" class="table table-bordered table-hover table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT id, product_name, quantity FROM delivery";
                                        $result = $con->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $disabled = ($row["quantity"] == 0) ? "disabled" : "";
                                                echo "<tr data-id='" . $row["id"] . "' data-name='" . $row["product_name"] . "' data-stock='" . $row["quantity"] . "'>";
                                                echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                                                echo "<td class='stock-display'>" . htmlspecialchars($row["quantity"]) . "</td>";
                                                echo "<td><button class='release-to-order btn btn-primary' data-id='" . $row["id"] . "' $disabled>➕</button></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>No products Found.</td></tr>";
                                        }
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
                            <h4>Releasing Stock</h4>
                        </div>
                        <div class="card-body">
                            <form id="releaseForm" method="POST" action="release_stock.php">
                                <table id="orderTable" class="table table-bordered table-hover table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Release items dynamically added here -->
                                    </tbody>
                                </table>
                                <div class="form-group mt-3">
                                    <label for="branchSelect">Select Branch:</label>
                                    <select id="branchSelect" name="branch_id" class="form-control" required>
                                        <option value="" disabled selected>Select Branch</option>
                                        <?php
                                        $branch_sql = "SELECT id, branch_name FROM branches";
                                        $branch_result = $con->query($branch_sql);
                                        if ($branch_result->num_rows > 0) {
                                            while ($branch = $branch_result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($branch["id"]) . "'>" . htmlspecialchars($branch["branch_name"]) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <input type="hidden" name="cart" id="cartData">
                                <button id="processOrder" class="mt-2 btn btn-success">Release Stocks</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
    <div class="table-responsive">
    <h4>Release History</h4>
        <table id="myTable" class="custom-table table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Branch</th>
                    <th class="text-center">Product Name</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Release Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
        $query = "
        SELECT rs.*, d.product_name, b.branch_name
        FROM release_stock rs
        INNER JOIN delivery d ON rs.delivery_id = d.id
        INNER JOIN branches b ON rs.branches_id = b.id
        WHERE rs.delivery_id IS NOT NULL AND rs.branches_id IS NOT NULL";

        $result = $con->query($query);






                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td class='text-center'>" . htmlspecialchars($row['branch_name']) . "</td>
                            <td class='text-center'>" . htmlspecialchars($row['product_name']) . "</td>
                            <td class='text-center'>" . htmlspecialchars($row['quantity']) . "</td> 
                            <td class='text-center'>" . htmlspecialchars($row['release_date']) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No Release records found</td></tr>";
                }

                $con->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

        <script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderTable = document.querySelector('#orderTable tbody');

        document.querySelector('#productTable').addEventListener('click', function (e) {
            if (e.target.classList.contains('release-to-order')) {
                const row = e.target.closest('tr');
                const id = row.getAttribute('data-id');
                const name = row.getAttribute('data-name');
                let stock = parseInt(row.querySelector('.stock-display').innerText);

                if (stock > 0) {
                    stock -= 1;
                    row.querySelector('.stock-display').innerText = stock;

                    let orderRow = orderTable.querySelector(`tr[data-id='${id}']`);
                    if (orderRow) {
                        const quantityInput = orderRow.querySelector('.order-quantity');
                        quantityInput.value = parseInt(quantityInput.value) + 1;
                    } else {
                        orderTable.innerHTML += `
                            <tr data-id='${id}'>
                                <td>${name}</td>
                                <td>
                                    <div class="input-group">
                                        <button class="btn-outline-secondary decrease-quantity btn" type="button">−</button>
                                        <input type="number" class="order-quantity form-control" value="1" min="1">
                                        <button class="btn-outline-secondary increase-quantity btn" type="button">+</button>
                                    </div>
                                </td>
                                <td><button class='btn btn-danger remove-item'>Remove</button></td>
                            </tr>
                        `;
                    }
                }
            }
        });

        orderTable.addEventListener('click', function (e) {
            const orderRow = e.target.closest('tr');
            const productId = orderRow.getAttribute('data-id');
            const stockDisplay = document.querySelector(`#productTable tr[data-id='${productId}'] .stock-display`);

            if (e.target.classList.contains('increase-quantity')) {
                let stock = parseInt(stockDisplay.innerText);
                if (stock > 0) {
                    stock--;
                    stockDisplay.innerText = stock;
                    const quantityInput = orderRow.querySelector('.order-quantity');
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                }
            }

            if (e.target.classList.contains('decrease-quantity')) {
                const quantityInput = orderRow.querySelector('.order-quantity');
                if (parseInt(quantityInput.value) > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                    stockDisplay.innerText = parseInt(stockDisplay.innerText) + 1;
                }
            }

            if (e.target.classList.contains('remove-item')) {
                const quantity = parseInt(orderRow.querySelector('.order-quantity').value);
                stockDisplay.innerText = parseInt(stockDisplay.innerText) + quantity;
                orderRow.remove();
            }
        });

        orderTable.addEventListener('input', function (e) {
            if (e.target.classList.contains('order-quantity')) {
                const orderRow = e.target.closest('tr');
                const productId = orderRow.getAttribute('data-id');
                const stockDisplay = document.querySelector(`#productTable tr[data-id='${productId}'] .stock-display`);
                let stock = parseInt(stockDisplay.innerText);
                let newQuantity = parseInt(e.target.value);
                let oldQuantity = parseInt(e.target.dataset.oldValue || e.target.defaultValue);

                if (newQuantity > oldQuantity && stock >= (newQuantity - oldQuantity)) {
                    stock -= (newQuantity - oldQuantity);
                    stockDisplay.innerText = stock;
                    e.target.dataset.oldValue = newQuantity;
                } else if (newQuantity < oldQuantity) {
                    stock += (oldQuantity - newQuantity);
                    stockDisplay.innerText = stock;
                    e.target.dataset.oldValue = newQuantity;
                } else {
                    e.target.value = oldQuantity; // Revert to previous value if invalid
                }
            }
        });

        document.querySelector('#processOrder').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent form submission to collect data first
            const cart = [];
            orderTable.querySelectorAll('tr').forEach(row => {
                const id = row.getAttribute('data-id');
                const quantity = row.querySelector('.order-quantity').value;
                const name = row.querySelector('td:first-child').innerText;
                cart.push({ id, quantity, name });
            });
            document.querySelector('#cartData').value = JSON.stringify(cart);
            document.querySelector('#releaseForm').submit();
        });
    });
</script>

    </section>
</main>
