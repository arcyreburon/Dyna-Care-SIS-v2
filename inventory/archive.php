<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../../index.php"); 
    exit;
}

// Function to reset archive table
function resetArchiveTable($con)
{
    $currentDate = new DateTime();
    $currentDay = $currentDate->format('d');
    $currentHour = $currentDate->format('H');
    $currentMinute = $currentDate->format('i');

    if ($currentDay == '01' && $currentHour == '00' && $currentMinute == '00') {
        $sqlTruncateArchive = "TRUNCATE TABLE archive";
        if ($con->query($sqlTruncateArchive) === TRUE) {
            echo "Archive table truncated successfully.";
        } else {
            echo "Error truncating archive table: " . $con->error;
        }
    }
}

// Call function to reset archive table if necessary
resetArchiveTable($con);

// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="shadow-sm card">
                        <div class="text-black card-header">
                            <h3 class="mb-0">Archived List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="myTable" class="custom-table table table-bordered table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Archived Date</th>
                                        </tr>
                                    </thead>

                                    <?php
                                    $userBranchId = $_SESSION['branches_id']; // Get user's branch ID
                                    $sql = "SELECT * FROM archive WHERE branches_id = ?";
                                    $stmt = $con->prepare($sql);
                                    $stmt->bind_param("i", $userBranchId);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>

                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['product_name']}</td>
                                                    <td>{$row['category_name']}</td>
                                                    <td>{$row['archive_date']}</td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' class='text-center'>No archived records found</td></tr>";
                                        }
                                        ?>
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
    document.addEventListener("DOMContentLoaded", function () {
        new simpleDatatables.DataTable("#myTable");
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

<?php include '../includes/footer.php'; ?>
