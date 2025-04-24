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

if (isset($_GET['id'])) {
    $inventoryId = $_GET['id'];
    $userBranchId = $_SESSION['branches_id']; // Get logged-in user's branch ID
    $userRole = $_SESSION['user_role']; // Get user role

    // Start database transaction
    $con->begin_transaction();

    try {
        // Step 1: Fetch product details
        $sqlGetProduct = "SELECT p.id AS product_id, p.product_name, c.category_name, i.branches_id 
                          FROM inventory i
                          JOIN products p ON i.products_id = p.id
                          JOIN categories c ON p.categories_id = c.id
                          WHERE i.id = ?";
        
        if ($userRole !== "Super Admin") {
            $sqlGetProduct .= " AND i.branches_id = ?"; // Restrict non-Super Admin users
        }

        $stmtGetProduct = $con->prepare($sqlGetProduct);
        if (!$stmtGetProduct) {
            throw new Exception("Failed to prepare statement: " . $con->error);
        }

        if ($userRole === "Super Admin") {
            $stmtGetProduct->bind_param("i", $inventoryId);
        } else {
            $stmtGetProduct->bind_param("ii", $inventoryId, $userBranchId);
        }

        $stmtGetProduct->execute();
        $stmtGetProduct->bind_result($productId, $productName, $categoryName, $branchId);
        $stmtGetProduct->fetch();
        $stmtGetProduct->close();

        // If no matching record found, deny deletion
        if (!$branchId) {
            throw new Exception("Unauthorized action or record not found.");
        }

        // Step 2: Archive the deleted product details
        $archiveDate = date("Y-m-d H:i:s");
        $sqlArchive = "INSERT INTO archive (product_name, category_name, archive_date, branches_id) 
                       VALUES (?, ?, ?, ?)";
        $stmtArchive = $con->prepare($sqlArchive);
        $stmtArchive->bind_param("sssi", $productName, $categoryName, $archiveDate, $branchId);
        $stmtArchive->execute();
        $stmtArchive->close();

        // Step 3: Delete the inventory record
        $sqlInventory = "DELETE FROM inventory WHERE id = ?";
        if ($userRole !== "Super Admin") {
            $sqlInventory .= " AND branches_id = ?";
        }

        $stmtInventory = $con->prepare($sqlInventory);
        if ($userRole === "Super Admin") {
            $stmtInventory->bind_param("i", $inventoryId);
        } else {
            $stmtInventory->bind_param("ii", $inventoryId, $userBranchId);
        }

        $stmtInventory->execute();
        $stmtInventory->close();

        // Step 4: Delete the product if it is no longer in any inventory
        $sqlCheckInventory = "SELECT COUNT(*) FROM inventory WHERE products_id = ?";
        $stmtCheckInventory = $con->prepare($sqlCheckInventory);
        $stmtCheckInventory->bind_param("i", $productId);
        $stmtCheckInventory->execute();
        $stmtCheckInventory->bind_result($inventoryCount);
        $stmtCheckInventory->fetch();
        $stmtCheckInventory->close();

        if ($inventoryCount == 0) {
            $sqlDeleteProduct = "DELETE FROM products WHERE id = ?";
            $stmtDeleteProduct = $con->prepare($sqlDeleteProduct);
            $stmtDeleteProduct->bind_param("i", $productId);
            $stmtDeleteProduct->execute();
            $stmtDeleteProduct->close();
        }

        // Commit the transaction
        $con->commit();

        $_SESSION['message'] = "Inventory and associated product deleted successfully.";
        $_SESSION['message_type'] = "success";
        header("Location: inventory_table.php");
        exit;
    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
        header("Location: inventory_table.php");
        exit;
    }
}
?>
