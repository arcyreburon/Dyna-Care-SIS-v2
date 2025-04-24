<title>Sales</title>
<?php
session_start();
include "../db_conn.php";

// Restrict access to Super Admin, Admin, and Cashier only
if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== "Super Admin" && $_SESSION['user_role'] !== "Admin" && $_SESSION['user_role'] !== "Cashier")) {
    header("Location: ../403.php");
    exit;
}

// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';

// Handle filtering by date
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : '';
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : '';

?>

<main id="main" class="main">
    <section class="dashboard section">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="shadow-sm card">
                        <div class="text-black card-header">
                            <h3 class="mb-0">Transaction List</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show"
                                role="alert">
                                <?php echo $_SESSION['message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <!-- Date Filter Form -->
                            <form method="GET" action="" class="mb-4 col-md-12 row">
                                <div class="mb-3 col-md-4">
                                    <label for="date_start" class="form-label">Start Date</label>
                                    <input type="date" id="date_start" name="date_start" class="form-control"
                                        value="<?php echo htmlspecialchars($date_start); ?>">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="date_end" class="form-label">End Date</label>
                                    <input type="date" id="date_end" name="date_end" class="form-control"
                                        value="<?php echo htmlspecialchars($date_end); ?>">
                                </div>
                                <div class="d-flex align-items-end mb-3 col-md-4">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table id="myTable" class="custom-table table-bordered table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Discount</th>
                                            <th class="text-center">Total Price</th>
                                            <th class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        $sql = "
                                            SELECT t.products_id, t.total_price, t.date, t.discount, p.branches_id, p.product_name
                                            FROM transaction t  
                                            INNER JOIN products p ON t.products_id = p.id";

                                        // Check user role
                                        if ($_SESSION['user_role'] === "Cashier") {
                                            if (isset($_SESSION['branches_id'])) {
                                                $branch_id = $_SESSION['branches_id']; // Get branch_id from session
                                                $sql .= " WHERE p.branches_id = ?";
                                            }
                                        }

                                        // Add date filter if dates are provided
                                        if ($date_start && $date_end) {
                                            $sql .= $_SESSION['user_role'] === "Cashier" ? " AND" : " WHERE";
                                            $sql .= " DATE(t.date) BETWEEN ? AND ?";
                                        }

                                        $stmt = $con->prepare($sql);
                                        if ($stmt === false) {
                                            // If the prepare() fails, output the error for debugging
                                            echo "Error preparing the statement: " . $con->error;
                                            exit;
                                        }

                                        // Bind the parameters to the query based on the user role and date filters
                                        if ($_SESSION['user_role'] === "Cashier" && $date_start && $date_end) {
                                            $stmt->bind_param("iss", $branch_id, $date_start, $date_end);
                                        } elseif ($_SESSION['user_role'] === "Cashier") {
                                            $stmt->bind_param("i", $branch_id);
                                        } elseif ($date_start && $date_end) {
                                            $stmt->bind_param("ss", $date_start, $date_end);
                                        }

                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $final_price = $row['total_price'] - ($row['total_price'] * ($row['discount'] / 100)); // Calculate discounted price

                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["discount"]) . "%</td>";
                                                echo "<td>" . number_format($final_price, 2) . "</td>"; // Final price after discount
                                                echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4' class='text-center'>No transactions found</td></tr>";
                                        }

                                        // Close statement
                                        $stmt->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
            </div> 
        </div> 
    </section>
</main>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        new simpleDatatables.DataTable("#myTable");
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
</style>