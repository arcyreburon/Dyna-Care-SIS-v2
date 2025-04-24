<?php
session_start();
include "../db_conn.php";

// Check if user is authenticated
if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Super Admin or Admin only
if ($_SESSION['user_role'] !== 'Super Admin' && $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}

$user_role = $_SESSION['user_role']; // Fetch the role of the logged-in user

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user data for editing
    $sql = "SELECT u.*, ur.role, u.branches_id FROM users u INNER JOIN users_role ur ON u.users_role_id = ur.id WHERE u.id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Fetch all roles
    $roles_sql = "SELECT role FROM users_role";
    $roles_result = $con->query($roles_sql);
    $roles = [];
    while ($row = $roles_result->fetch_assoc()) {
        $roles[] = $row['role'];
    }

    // Fetch all branches
    $branches_sql = "SELECT id, branch_name FROM branches"; // Assuming you have a branches table
    $branches_result = $con->query($branches_sql);
    $branches = [];
    while ($row = $branches_result->fetch_assoc()) {
        $branches[] = $row;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update user details
        $name = $_POST['name'];
        $email = $_POST['email'];
        $cpnumber = $_POST['cpnumber'];
        $role = $_POST['role'];
        $branch_id = $_POST['branches_id']; // Add branch selection
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password using MD5
        $hashed_password = md5($password);

        $update_sql = "UPDATE users SET name = ?, email = ?, cpnumber = ?, username = ?, password = ?, users_role_id = (SELECT id FROM users_role WHERE role = ?), branches_id = ? WHERE id = ?";
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bind_param("ssssssii", $name, $email, $cpnumber, $username, $hashed_password, $role, $branch_id, $userId);

        if ($update_stmt->execute()) {
            $_SESSION['message'] = "User updated successfully.";
            $_SESSION['message_type'] = 'success'; // Success type
            header("Location: manage_users.php"); // Redirect after success
            exit;
        } else {
            $_SESSION['message'] = "Error updating user: " . $update_stmt->error;
            $_SESSION['message_type'] = 'danger'; // Error type
        }
    }
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
                <div class="col-lg-8">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                    <?php endif; ?>

                    <!-- Form for Editing User -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Update User</h5>
                            <form method="POST" action="">
                                <div class="row">
                                    <!-- First Column: Name, Email, Phone Number -->
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="cpnumber" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="cpnumber" value="<?php echo $user['cpnumber']; ?>" required>
                                    </div>

                                    <!-- Username and Password -->
                                    <div class="mb-3 col-md-6">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>

                                    <!-- Second Column: Role, Branch -->
                                    <div class="mb-3 col-md-6">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select" name="role" required>
                                            <?php foreach ($roles as $role): ?>
                                                <?php if ($user_role == 'Admin' && ($role == 'Admin' || $role == 'Super Admin')): ?>
                                                    <!-- If Admin is logged in, don't allow selecting Admin or Super Admin -->
                                                    <option value="<?php echo $role; ?>" disabled><?php echo $role; ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $role; ?>" <?php if ($user['role'] == $role) echo 'selected'; ?>><?php echo $role; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                                            <div class="mb-3 col-md-6">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select class="form-select" name="branches_id" <?php echo ($user_role !== 'Super Admin') ? 'disabled' : ''; ?> required>
                                <option value="" disabled selected>Select Branch</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?php echo $branch['id']; ?>" <?php if ($user['branches_id'] == $branch['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($branch['branch_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                                </div>
                                <button type="submit" class="btn btn-primary">Update User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
