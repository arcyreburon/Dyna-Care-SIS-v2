<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    // User is not logged in, redirect to 404
    header("Location: 404.php");
    exit;
} 

unset($_SESSION['low_stock_notifications']);
// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header text-black">
                            <h3 class="mb-0">Notification List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <button id="clearTableButton" class="btn btn-info mb-3 mt-3">Clear Table</button>
                                <table id="myTable" class="table table-bordered table-striped table-hover custom-table">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Branch</th>
                                            <th>Available Stocks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($_SESSION['low_stock_notifications']) && count($_SESSION['low_stock_notifications']) > 0): ?>
                                            <?php foreach ($_SESSION['low_stock_notifications'] as $index => $notification): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($notification['product_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($notification['branch_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($notification['avail_stock']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No notifications available</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- End Card -->
                </div> <!-- End col-lg-12 -->
            </div> <!-- End row -->
        </div> <!-- End container -->
    </section>
</main>

<!-- DataTable Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new simpleDatatables.DataTable("#myTable");
    });

    // Clear table data and session notifications on button click
    document.getElementById("clearTableButton").addEventListener("click", function() {
        // Send request to clear notifications in session
        fetch('clear_notifications.php').then(() => {
            // Get table body
            var tbody = document.querySelector("#myTable tbody");
            // Remove all child nodes (table rows)
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }
            // Optionally, you can display a message
            var noNotificationsRow = document.createElement("tr");
            noNotificationsRow.innerHTML = '<td colspan="3" class="text-center text-muted">No notifications available</td>';
            tbody.appendChild(noNotificationsRow);
        });
    });
</script>

<!-- Custom CSS for Table Borders -->
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
</style>
