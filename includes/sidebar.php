<?php
// Get user role from session (Super Admin, Admin, Inventory Clerk, Cashier)
$user_role = $_SESSION['user_role']; 

// Get the current page filename to determine active link
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard (Super Admin, Admin only) -->
    <?php if ($user_role === 'Super Admin' || $user_role === 'Admin') { ?>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="../superadmin/dashboard.php">
          <i class="bi-grid bi"></i>
          <span>Dashboard</span>
        </a>
      </li>
    <?php } ?>

    <?php if ($user_role === 'Inventory Clerk' || $user_role === 'Admin') { ?>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'products_table.php') ? 'active' : '' ?>"  href="../products/products_table.php">
          <i class="bi bi-boxes"></i>
          <span>Products</span>
        </a>
      </li>
    <?php } ?>

    <!-- Inventory (Inventory Clerk only) -->
    <?php if ($user_role === 'Inventory Clerk' || $user_role === 'Super Admin' || $user_role === 'Admin') { ?>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'inventory.php') ? 'active' : '' ?>" href="../inventory/inventory_table.php">
        <i class="bi bi-box-seam"></i>
          <span>Inventory</span>
        </a>
      </li>
    <?php } ?>

    <?php if ($user_role === 'Inventory Clerk') { ?>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'archive.php') ? 'active' : '' ?>" href="..//inventory/archive.php">
          <i class="bi bi-archive"></i>
          <span>Archive</span>
        </a>
      </li>
    <?php } ?>

    <?php if ($user_role === 'Inventory Clerk' || $user_role === 'Super Admin' || $user_role === 'Admin') { ?>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'delivery.php') ? 'active' : '' ?>" href="../delivery/delivery.php">
        <i class="bi bi-box-seam"></i>
          <span>Delivery</span>
        </a>
      </li>
    <?php } ?>

    <!-- Sales (Admin, Cashier only) -->
    <?php if ($user_role === 'Cashier') { ?>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'sales_report.php') ? 'active' : '' ?>" href="../cashier/sales_report.php">
        <i class="bi bi-bar-chart-line"></i> 
          <span>Sales Report</span>
        </a>
      </li>
    <?php } ?>

    <!-- Sales (Admin, Cashier only) -->
    <?php if ($user_role === 'Cashier') { ?>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'sales.php') ? 'active' : '' ?>" href="../cashier/sales.php">
          <i class="bi-cash-stack bi"></i>
          <span>Sales Invoice</span>
        </a>
      </li>
    <?php } ?>

    <!-- Reports (Admin, Super Admin, Cashier) -->
    <?php if ($user_role === 'Super Admin' || $user_role === 'Admin') { ?>
      <li class="nav-item">
        <a class="nav-link <?= $settings_link_active ?>" data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart-line"></i><span>Reports</span><i class="ms-auto bi bi-chevron-down"></i>
        </a>
        <ul id="reports-nav" class="nav-content collapse <?= $settings_active ?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="../superadmin/audit_trail.php" class="<?= ($current_page == 'audit_trail.php') ? 'active' : '' ?>">
              <i class="bi bi-shield-lock"></i><span>Transactions</span>
            </a>
          </li>
          <li>
            <a href="../superadmin/sales_branch.php" class="<?= ($current_page == 'sales_report.php') ? 'active' : '' ?>">
              <i class="bi bi-bar-chart-line"></i><span>Sales Report</span>
            </a>
          </li>
          <li>
            <a href="../superadmin/order.php" class="<?= ($current_page == 'order.php') ? 'active' : '' ?>">
              <i class="bi bi-exclamation-triangle"></i><span>Re-Order</span>
            </a>
          </li>
          <li>
            <a href="../superadmin/reports.php" class="<?= ($current_page == 'expiring_medicines.php') ? 'active' : '' ?>">
              <i class="bi bi-exclamation-triangle"></i><span>Reports</span>
            </a>
          </li>
        </ul>
      </li>
    <?php } ?>

    <!-- Transaction (Cashier only) -->
    <?php if ($user_role === 'Cashier') { ?>
      <li class="nav-item">
        <a class="nav-link <?= ($current_page == 'transaction.php') ? 'active' : '' ?>" href="../cashier/transaction.php">
          <i class="bi bi-receipt"></i>
          <span>Transaction</span>
        </a>
      </li>
    <?php } ?>

    <!-- Settings (Super Admin, Admin only) -->
    <?php if ($user_role === 'Super Admin' || $user_role === 'Admin') { ?>
      <li class="nav-item">
        <a class="nav-link <?= $settings_link_active ?>" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear"></i><span>Settings</span><i class="ms-auto bi bi-chevron-down"></i>
        </a>
        <ul id="settings-nav" class="nav-content collapse <?= $settings_active ?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="../settings/manage_users.php" class="<?= ($current_page == 'manage_users.php') ? 'active' : '' ?>">
              <i class="bi bi-people"></i><span>Manage Users</span>
            </a>
          </li>
        </ul>
      </li>
    <?php } ?>

  </ul>
</aside><!-- End Sidebar -->
<style>
  /* Default Sidebar Links (White Background) */
.sidebar .nav-link {
  background: #ffffff; /* Default white */
  color: #333; /* Dark text for contrast */
  padding: 10px 15px;
  display: flex;
  align-items: center;
  border-radius: 5px;
  transition: background 0.3s ease, color 0.3s ease;
}

/* Active Sidebar Link (Gray Background) */
.sidebar .nav-link.active,
.sidebar .nav-content a.active {
  background: #d6d6d6; /* Light gray */
  color: #000; /* Black text */
  font-weight: bold;
}

/* Ensure Icons Follow Text Color */
.sidebar .nav-link i {
  color: inherit; /* Makes icons match text color */
}

/* Sidebar Hover Effect */
.sidebar .nav-link:hover {
  background: #f0f0f0; /* Light hover effect */
  color: #000;
}

/* Dropdown Menu Styles */
.sidebar .nav-content {
  background: #ffffff; /* White background for submenus */
  padding-left: 20px;
}

/* Active Dropdown Item */
.sidebar .nav-content a.active {
  background: #d6d6d6;
  font-weight: bold;
}

/* Ensure the dropdown stays highlighted when active */
.sidebar .nav-link.active {
  background: #d6d6d6; /* Light gray */
  font-weight: bold;
}

/* Active submenu items */
.sidebar .nav-content a.active {
  background: #d6d6d6;
  font-weight: bold;
}

/* Hover effect */
.sidebar .nav-link:hover, 
.sidebar .nav-content a:hover {
  background: #f0f0f0;
}

</style>
