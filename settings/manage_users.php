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

// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="shadow-sm card">
                        <div class="text-black card-header">
                            <h3 class="mb-0">Users List</h3>
                            <div class="d-flex justify-content-start mt-4">
                                <!-- Add User Button -->
                                <a href="add_users.php" class="btn btn-primary">
                                    <i class="bi bi-person-plus-fill"></i> Add User
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $_SESSION['message']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table id="myTable" class="custom-table table table-bordered table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Number</th>
                                            <th class="text-center">Role</th>
                                            <th class="text-center">Branch</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch data from the database (restricting by branch)
                                        $sql = "SELECT u.*, ur.role, b.branch_name
                                                FROM users u
                                                INNER JOIN users_role ur ON u.users_role_id = ur.id
                                                INNER JOIN branches b ON u.branches_id = b.id";

                                        if ($_SESSION['user_role'] !== 'Super Admin') {
                                            $sql .= " WHERE u.branches_id = " . intval($_SESSION['branches_id']);

                                            // Restrict Admin from seeing Super Admin and Admin users
                                            if ($_SESSION['user_role'] == 'Admin') {
                                                $sql .= " AND ur.role NOT IN ('Admin', 'Super Admin')";
                                            }
                                        }

                                        $result = $con->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td class='text-center'>" . htmlspecialchars($row['name']) . "</td>
                                                    <td class='text-center'>" . htmlspecialchars($row['email']) . "</td>
                                                    <td class='text-center'>" . htmlspecialchars($row['cpnumber']) . "</td>
                                                    <td class='text-center'>" . htmlspecialchars($row['role']) . "</td>
                                                    <td class='text-center'>" . htmlspecialchars($row['branch_name']) . "</td>
                                                    <td class='text-center'>
                                                        <a href='update_users.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>
                                                            <i class='bi bi-pencil-square'></i> Edit
                                                        </a>
                                                        <a href='#' class='btn btn-danger btn-sm' onclick='setDeleteUserId(" . $row['id'] . ")' data-bs-toggle='modal' data-bs-target='#deleteUserModal'>
                                                            <i class='bi bi-trash'></i> Delete
                                                        </a>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
                                        }

                                        $con->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this user?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            let deleteUserId;

                            function setDeleteUserId(id) {
                                deleteUserId = id;
                            }

                            document.getElementById('confirmDeleteButton').addEventListener('click', function () {
                                window.location.href = 'delete_users.php?id=' + deleteUserId;
                            });
                        </script>

                    </div>
                </div>
            </div> <!-- End Card -->
        </div> <!-- End col-lg-12 -->
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

<!-- Bootstrap Icons CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css">
