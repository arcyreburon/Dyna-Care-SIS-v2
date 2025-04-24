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

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $branch_id = 1; // Branch 1

    // Query to get available stock for the selected product in branch 1
    $sql = "SELECT avail_stock FROM inventory WHERE products_id = ? AND branches_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $product_id, $branch_id);
    $stmt->execute();
    $stmt->bind_result($avail_stock);
    $stmt->fetch();
    echo $avail_stock;
    $stmt->close();
}

$con->close();
?>
