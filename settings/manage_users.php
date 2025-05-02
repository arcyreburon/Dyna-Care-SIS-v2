<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['user_role'] !== 'Super Admin' && $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../403.php");
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 style="margin-top: 1rem; font-size:34px; color: black;" class="card-">Users List</h5>
                            <a href="add_users.php" class="btn btn-primary btn-sm">
                                <i class="bi bi-person-plus me-1"></i>Add New
                            </a>
                        </div>

                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                                <?= $_SESSION['message'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-hover datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Branch</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT u.*, ur.role, b.branch_name
                                            FROM users u
                                            INNER JOIN users_role ur ON u.users_role_id = ur.id
                                            INNER JOIN branches b ON u.branches_id = b.id";

                                    if ($_SESSION['user_role'] !== 'Super Admin') {
                                        $sql .= " WHERE u.branches_id = " . intval($_SESSION['branches_id']);
                                        if ($_SESSION['user_role'] == 'Admin') {
                                            $sql .= " AND ur.role NOT IN ('Admin', 'Super Admin')";
                                        }
                                    }

                                    $result = $con->query($sql);
                                    $count = 1;

                                    if ($result->num_rows > 0):
                                        while ($row = $result->fetch_assoc()):
                                    ?>
                                            <tr>
                                                <th scope="row"><?= $count++ ?></th>
                                                <td><?= htmlspecialchars($row['name']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td><?= htmlspecialchars($row['cpnumber']) ?></td>
                                                <td><span class="badge bg-<?= getRoleBadgeColor($row['role']) ?>"><?= htmlspecialchars($row['role']) ?></span></td>
                                                <td><?= htmlspecialchars($row['branch_name']) ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="update_users.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="setDeleteUserId(<?= $row['id'] ?>)" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        endwhile;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No users found</td>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    // Delete Confirmation
    let deleteUserId;
    function setDeleteUserId(id) {
        deleteUserId = id;
    }
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        window.location.href = 'delete_users.php?id=' + deleteUserId;
    });

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<?php
// Helper function for role badge colors
function getRoleBadgeColor($role) {
    switch ($role) {
        case 'Super Admin': return 'danger';
        case 'Admin': return 'warning';
        case 'Manager': return 'info';
        default: return 'primary';
    }
}
?>