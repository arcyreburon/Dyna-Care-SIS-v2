<title>DynaCareSIS - sales</title>
<?php
session_start();
include "../db_conn.php";


if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['user_role'] !== "Cashier") {
    header("Location: ../403.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart = json_decode(file_get_contents('php://input'), true);
    $discount = floatval($cart['discount']);

    foreach ($cart['cart'] as $item) {
        if (!isset($item['code']) || !isset($item['quantity'])) {
            die("Error: 'code' or 'quantity' key missing in cart item.");
        }

        $code = intval($item['code']); 
        $quantity = intval($item['quantity']);
        $total_price = floatval($item['total']);

        // Query to get current stock using JOIN
        $check_stock_query = "
            SELECT p.code, p.product_name, i.avail_stock
            FROM products p
            JOIN inventory i ON p.code = i.products_code
            WHERE p.code = ?
        ";
        $stmt = $con->prepare($check_stock_query);
        $stmt->bind_param("i", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $current_stock = $row['avail_stock'];

            if ($current_stock >= $quantity) {
                $new_stock = $current_stock - $quantity;
                $update_stock_query = "
                    UPDATE inventory i
                    JOIN products p ON i.products_code = p.code
                    SET i.avail_stock = ?
                    WHERE p.code = ?
                ";
                $update_stmt = $con->prepare($update_stock_query);
                $update_stmt->bind_param("ii", $new_stock, $code);
                if (!$update_stmt->execute()) {
                    die("Error updating stock: " . $update_stmt->error);
                }

                $insert_transaction_query = "
                    INSERT INTO transaction (codes, total_price, discount)
                    VALUES (?, ?, ?)
                ";
                $insert_stmt = $con->prepare($insert_transaction_query);
                $insert_stmt->bind_param("idi", $code, $total_price, $discount);
                if (!$insert_stmt->execute()) {
                    die("Error inserting transaction: " . $insert_stmt->error);
                }
            } else {
                echo "Not enough stock for code: $code. Available: $current_stock, Requested: $quantity<br>";
            }
        } else {
            echo "No inventory found for code: $code<br>";
        }
    }

    echo "Stock updated successfully!";
}
?>
