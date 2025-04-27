<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../login.php"); // Redirect to login if not authenticated
    exit;
}

// Fetch all roles
$roles_sql = "SELECT role FROM users_role";
$roles_result = $con->query($roles_sql);
$roles = [];
while ($row = $roles_result->fetch_assoc()) {
    $roles[] = $row['role'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new user
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $cpnumber = $_POST['cpnumber'];
    $user_role = $_POST['user_role'];
    $branch_id = $_POST['branches_id'];  // Get branch_id from the form
    $password = md5($_POST['password']); // MD5 password hashing

    // Insert new user into the database
    $insert_sql = "INSERT INTO users (name, username, email, cpnumber, users_role_id, branches_id, password) 
                   VALUES (?, ?, ?, ?, (SELECT id FROM users_role WHERE role = ?), ?, ?)";
    $stmt = $con->prepare($insert_sql);
    $stmt->bind_param("sssssis", $name, $username, $email, $cpnumber, $user_role, $branch_id, $password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User added successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error adding user: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();

    // Redirect back to the users list page
    header("Location: manage_users.php");
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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add User</h5>
                            <!-- Form for Adding User -->
                            <form method="POST" action="">
                                <div class="row">
                                    <!-- Name and Username in one row -->
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" name="username" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Email and Phone Number in one row -->
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="cpnumber" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="cpnumber" id="cpnumber" required maxlength="13" pattern="[0-9\s]{13}" title="Phone number should be in the format: 0912 345 6890" oninput="formatPhoneNumber(this)" required>
                                    </div>

                                    <!-- Remove non-numeric characters -->
                                    <!-- Formatting (XXX XXXX XXXX) -->
                                    <script>
                                    function formatPhoneNumber(input) {
                                        let value = input.value.replace(/\D/g, '');
                                        if (value.length > 11) value = value.substring(0, 11);
                                        
                                        let formattedValue = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
                                        input.value = formattedValue;
                                    }
                                    </script>

                                </div>

                                <div class="row">
                                    <!-- Role and Branch in one row -->
                                    <div class="mb-3 col-md-6">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select" name="user_role" required>
                                            <option value="" disabled selected>Select Role</option>
                                            <?php foreach ($roles as $role): ?>
                                                <?php if ($_SESSION['user_role'] == 'Admin' && ($role == 'Admin' || $role == 'Super Admin')): ?>
                                                    <!-- If Admin is logged in, don't allow selecting Admin or Super Admin -->
                                                    <option value="<?php echo $role; ?>" disabled><?php echo $role; ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                    <label for="branch" class="form-label">Select Branch</label>
                                    <select class="form-select" name="branches_id" required>
                                        <option value="" disabled selected>Select Branch</option>
                                        <?php
                                

                                        $user_role = $_SESSION['user_role']; // Assuming you store user role in session
                                        $user_branch_id = $_SESSION['branches_id']; // Assuming user branch ID is stored in session

                                        if ($user_role === 'Super Admin') {
                                            // Fetch all branches if user is Super Admin
                                            $branches_sql = "SELECT id, branch_name FROM branches";
                                        } else {
                                            // Fetch only the branch assigned to the logged-in user
                                            $branches_sql = "SELECT id, branch_name FROM branches WHERE id = '$user_branch_id'";
                                        }

                                        $branches_result = $con->query($branches_sql);
                                        while ($branch = $branches_result->fetch_assoc()):
                                        ?>
                                            <option value="<?php echo $branch['id']; ?>"><?php echo $branch['branch_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                </div>

                                <div class="row">
                                    <!-- Password in one row -->
                                    <div class="mb-3 col-md-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Add User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
