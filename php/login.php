<?php
// login.php

// Start the session
session_start();

include_once('../db_conn.php');

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize error message
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]); // Don't hash here yet

    // Establish the database connection
    $con = new mysqli('localhost', 'root', '', 'dyna');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Use BINARY for case-sensitive username check
    $sql = "
        SELECT u.*, ur.role, b.id as branches_id, b.branch_name
        FROM users u
        INNER JOIN users_role ur ON u.users_role_id = ur.id
        LEFT JOIN branches b ON u.branches_id = b.id
        WHERE BINARY u.username = ?
    ";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Authentication successful
        $user_data = $result->fetch_assoc();

        // Hash the password using MD5
        $hashed_password = md5($password);

        // Validate password using MD5 hash
        if ($hashed_password === $user_data['password']) {
            $name = $user_data['name']; // User's name
            $_SESSION['name'] = $name; // Store the user's name in the session

            $user_role = $user_data['role']; // Get the role from the 'role' column in users_role table
            $_SESSION['user_role'] = $user_role; // Store the user's role in the session

            $_SESSION['branches_id'] = $user_data['branches_id'];
            $_SESSION['branch_name'] = $user_data['branch_name'];

            // Check the stock levels and generate notifications if needed
            $sql = "SELECT p.product_name, b.branch_name
                    FROM inventory i
                    JOIN products p ON i.products_id = p.id
                    JOIN branches b ON p.branches_id = b.id
                    WHERE i.avail_stock < 10";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                if (!isset($_SESSION['notifications'])) {
                    $_SESSION['notifications'] = array();
                }
                while ($row = $result->fetch_assoc()) {
                    $notification = array(
                        'message' => "Stock is low for " . $row['product_name'],
                        'branch' => $row['branch_name']
                    );
                    $_SESSION['notifications'][] = $notification;
                }
            }

            // Mark notifications as read when clicked
            if (isset($_POST['mark_as_read'])) {
                unset($_SESSION['notifications']);
            }

            // Redirect based on the user's user_role
            if ($_SESSION['user_role'] === 'Super Admin' || $_SESSION['user_role'] === 'Admin') {
                header("Location: ../superadmin/dashboard.php"); // Redirect to dashboard for Super Admin/Admin
            } elseif ($_SESSION['user_role'] === 'Inventory Clerk') {
                header("Location: ../products/products_table.php"); // Redirect to inventory page for Inventory Clerk
            } elseif ($_SESSION['user_role'] === 'Cashier') {
                header("Location: ../cashier/sales_report.php"); // Redirect to sales page for Cashier
            }
            exit();
        } else {
            // Authentication failed, display an error message
            $_SESSION['error'] = "Incorrect username or password!";
            header("Location: ../login.php");
            exit;
        }
    } else {
        // Authentication failed, display an error message
        $_SESSION['error'] = "Incorrect username or password!";
        header("Location: ../login.php");
        exit;
    }
}

// Close the database connection
$con->close();
?>
