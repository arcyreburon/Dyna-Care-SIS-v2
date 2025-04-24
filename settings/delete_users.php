<title>Users</title>
<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Super Admin or Admin only
if ($_SESSION['user_role'] !== 'Super Admin' && $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    
    // Delete user from the database
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $con->prepare($delete_sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting user: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "danger";
}

$con->close();

// Redirect back to the users list page
header("Location: manage_users.php");
exit;
?>
