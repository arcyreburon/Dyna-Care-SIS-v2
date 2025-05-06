<title>Sales Transactions.</title>
<?php
session_start();
include "../db_conn.php";

// Restrict access to Super Admin, Admin, and Cashier only
if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== "Super Admin" && $_SESSION['user_role'] !== "Admin" && $_SESSION['user_role'] !== "Cashier")) {
    header("Location: ../403.php");
    exit;
}

// Handle filtering by date
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : '';
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : '';

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<style>
    /* Consistent styling with inventory page */
    #myTable {
        --bs-table-bg: transparent;
        --bs-table-striped-bg: rgba(0,0,0,0.02);
        --bs-table-hover-bg: rgba(0,0,0,0.04);
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }
    
    #myTable th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 0.75rem 1rem;
    }
    
    #myTable td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }
    
    /* Price styling */
    .price-cell {
        font-weight: 600;
        color: #2a8f2a;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #myTable {
            font-size: 0.85rem;
        }
    }
    
    /* Page header styling */
    .pagetitle {
        margin-bottom: 1.5rem;
    }
    
    .pagetitle h1 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
        font-size: 0.875rem;
    }
    
    /* Filter form styling */
    .filter-form .form-control {
        font-size: 0.9rem;
    }
</style>

<main id="main" class="main">
    <!-- Modern Page Header Section -->
    <div class="pagetitle">
        <h1>Sales Transactions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Reports</a></li>
                <li class="breadcrumb-item active">Transactions</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title">Transaction Records</h5>
                        </div>

                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                                <?= $_SESSION['message'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                        <?php endif; ?>

                        <!-- Date Filter Form -->
                        <form method="GET" action="" class="row g-3 mb-4 filter-form">
                            <div class="col-md-4">
                                <label for="date_start" class="form-label">Start Date</label>
                                <input type="date" id="date_start" name="date_start" class="form-control" 
                                    value="<?= htmlspecialchars($date_start) ?>" max="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="date_end" class="form-label">End Date</label>
                                <input type="date" id="date_end" name="date_end" class="form-control" 
                                    value="<?= htmlspecialchars($date_end) ?>" max="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                                <?php if ($date_start || $date_end): ?>
                                    <a href="sales.php" class="btn btn-outline-secondary ms-2">
                                        <i class="bi bi-x-circle me-1"></i> Clear
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Discount</th>
                                        <th>Total Price</th>
                                        <th>Date</th>
                                        <th>Branch</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT t.products_id, t.total_price, t.date, t.discount, 
                                                   p.branches_id, p.product_name, b.branch_name
                                            FROM transaction t  
                                            INNER JOIN products p ON t.products_id = p.id
                                            INNER JOIN branches b ON p.branches_id = b.id";

                                    // Check user role
                                    if ($_SESSION['user_role'] === "Cashier") {
                                        if (isset($_SESSION['branches_id'])) {
                                            $branch_id = $_SESSION['branches_id'];
                                            $sql .= " WHERE p.branches_id = ?";
                                        }
                                    }

                                    // Add date filter if dates are provided
                                    if ($date_start && $date_end) {
                                        $sql .= $_SESSION['user_role'] === "Cashier" ? " AND" : " WHERE";
                                        $sql .= " DATE(t.date) BETWEEN ? AND ?";
                                    }

                                    $sql .= " ORDER BY t.date DESC";

                                    $stmt = $con->prepare($sql);
                                    if ($stmt === false) {
                                        echo "Error preparing the statement: " . $con->error;
                                        exit;
                                    }

                                    // Bind parameters
                                    if ($_SESSION['user_role'] === "Cashier" && $date_start && $date_end) {
                                        $stmt->bind_param("iss", $branch_id, $date_start, $date_end);
                                    } elseif ($_SESSION['user_role'] === "Cashier") {
                                        $stmt->bind_param("i", $branch_id);
                                    } elseif ($date_start && $date_end) {
                                        $stmt->bind_param("ss", $date_start, $date_end);
                                    }

                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0):
                                        while ($row = $result->fetch_assoc()):
                                            $final_price = $row['total_price'] - ($row['total_price'] * ($row['discount'] / 100));
                                    ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                                <td>
                                                    <?php if ($row['discount'] > 0): ?>
                                                        <span class="badge bg-danger"><?= $row['discount'] ?>% OFF</span>
                                                    <?php else: ?>
                                                        <span class="text-muted">None</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="text-align: right;" class="price-cell">₱<?= number_format($final_price, 2) ?></td>
                                                <td><?= date('M d, Y h:i A', strtotime($row['date'])) ?></td>
                                                <td><?= htmlspecialchars($row['branch_name']) ?></td>
                                            </tr>
                                    <?php
                                        endwhile;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                No transactions found <?= ($date_start || $date_end) ? 'for selected dates' : '' ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize DataTable
        const dataTable = new simpleDatatables.DataTable("#myTable", {
            perPage: 15,
            perPageSelect: [10, 15, 25, 50, 100],
            labels: {
                placeholder: "Search transactions...",
                perPage: " per page",
                noRows: "No matching records found",
                info: "Showing {start} to {end} of {rows} transactions"
            }
        });

        // Set max end date when start date changes
        document.getElementById('date_start').addEventListener('change', function() {
            document.getElementById('date_end').min = this.value;
        });
        
        // Set min start date when end date changes
        document.getElementById('date_end').addEventListener('change', function() {
            document.getElementById('date_start').max = this.value;
        });
    });
</script>