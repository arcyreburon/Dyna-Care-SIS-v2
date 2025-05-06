<?php
// Get user role from session
$user_role = $_SESSION['user_role'] ?? 'Undefined';

// Get the current page filename to determine active link
$current_page = basename($_SERVER['PHP_SELF']);

// Function to check if user has access to a menu item
function hasAccess($requiredRoles, $userRole) {
    return in_array($userRole, $requiredRoles);
}
?>

<!-- ======= Sidebar - Original Width ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Super Admin', 'Admin'], $user_role); ?>
      <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         href="<?= $hasAccess ? '../superadmin/dashboard.php' : '#' ?>" 
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Products -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Inventory Clerk', 'Admin'], $user_role); ?>
      <a class="nav-link <?= ($current_page == 'products_table.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         href="<?= $hasAccess ? '../products/products_table.php' : '#' ?>" 
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-boxes"></i>
        <span>Products</span>
      </a>
    </li>

    <!-- Inventory -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Inventory Clerk', 'Super Admin', 'Admin'], $user_role); ?>
      <a class="nav-link <?= ($current_page == 'inventory.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         href="<?= $hasAccess ? '../inventory/inventory_table.php' : '#' ?>" 
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-box-seam"></i>
        <span>Inventory</span>
      </a>
    </li>

    <!-- Archive -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Inventory Clerk'], $user_role); ?>
      <a class="nav-link <?= ($current_page == 'archive.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         href="<?= $hasAccess ? '../inventory/archive.php' : '#' ?>" 
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-archive"></i>
        <span>Archive</span>
      </a>
    </li>

    <!-- Delivery -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Inventory Clerk', 'Super Admin', 'Admin'], $user_role); ?>
      <a class="nav-link <?= ($current_page == 'delivery.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         href="<?= $hasAccess ? '../delivery/delivery.php' : '#' ?>" 
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-truck"></i>
        <span>Delivery</span>
      </a>
    </li>

    <!-- Sales Report -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Cashier'], $user_role); ?>
      <a class="nav-link <?= ($current_page == 'sales_report.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         href="<?= $hasAccess ? '../cashier/sales_report.php' : '#' ?>" 
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-bar-chart-line"></i>
        <span>Sales Report</span>
      </a>
    </li>

    <!-- Sales Invoice -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Cashier'], $user_role); ?>
      <a class="nav-link <?= ($current_page == 'sales.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         href="<?= $hasAccess ? '../cashier/sales.php' : '#' ?>" 
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-receipt"></i>
        <span>Sales Invoice</span>
      </a>
    </li>

    <!-- Transaction -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Cashier'], $user_role); ?>
      <a class="nav-link <?= ($current_page == 'transaction.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         href="<?= $hasAccess ? '../cashier/transaction.php' : '#' ?>" 
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-cash-stack"></i>
        <span>Transaction</span>
      </a>
    </li>

    <!-- Reports Dropdown -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Super Admin', 'Admin'], $user_role); ?>
      <a class="nav-link collapsed <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         data-bs-target="#reports-nav" 
         data-bs-toggle="<?= $hasAccess ? 'collapse' : 'none' ?>" 
         href="#"
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-bar-chart-line"></i>
        <span>Reports</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="reports-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?= $hasAccess ? '../superadmin/audit_trail.php' : '#' ?>" 
             class="<?= ($current_page == 'audit_trail.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>"
             <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
            <i class="bi bi-circle"></i><span>Transactions</span>
          </a>
        </li>
        <li>
          <a href="<?= $hasAccess ? '../superadmin/sales_branch.php' : '#' ?>" 
             class="<?= ($current_page == 'sales_report.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>"
             <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
            <i class="bi bi-circle"></i><span>Sales Report</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Settings Dropdown -->
    <li class="nav-item">
      <?php $hasAccess = hasAccess(['Super Admin', 'Admin'], $user_role); ?>
      <a class="nav-link collapsed <?= !$hasAccess ? 'disabled-item' : '' ?>" 
         data-bs-target="#settings-nav" 
         data-bs-toggle="<?= $hasAccess ? 'collapse' : 'none' ?>" 
         href="#"
         <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
        <i class="bi bi-gear"></i>
        <span>Settings</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?= $hasAccess ? '../settings/manage_users.php' : '#' ?>" 
             class="<?= ($current_page == 'manage_users.php') ? 'active' : '' ?> <?= !$hasAccess ? 'disabled-item' : '' ?>"
             <?= !$hasAccess ? 'onclick="return false;"' : '' ?>>
            <i class="bi bi-circle"></i><span>Manage Users</span>
          </a>
        </li>
      </ul>
    </li>

  </ul>
</aside>

<style>
/* Original Sidebar Styling */
:root {
  --sidebar-bg: #f8f9fa;
  --sidebar-width: 250px;
  --sidebar-item-padding: 0.75rem 1.25rem;
  --sidebar-icon-size: 1.25rem;
  --sidebar-text-size: 0.9rem;
}

.sidebar {
  width: var(--sidebar-width);
  background: var(--sidebar-bg);
  min-height: 100vh;
}

.nav-link {
  padding: var(--sidebar-item-padding);
}

.nav-link i {
  font-size: var(--sidebar-icon-size);
}

.nav-link span {
  font-size: var(--sidebar-text-size);
}

/* Disabled Items */
.disabled-item {
  color: #adb5bd !important;
  pointer-events: none;
  cursor: default;
}

.disabled-item i {
  color: #adb5bd !important;
}

/* Active State */
.nav-link.active {
  background-color: #e9ecef !important;
  color: #000 !important;
}

.nav-link.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: #0d6efd;
}

/* Dropdown Items */
.nav-content .nav-link {
  padding: 0.5rem 1rem 0.5rem 2.5rem;
  font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 1200px) {
  .sidebar {
    width: 80px;
  }
  .sidebar .nav-link span {
    display: none;
  }
  .sidebar:hover {
    width: var(--sidebar-width);
  }
  .sidebar:hover .nav-link span {
    display: inline-block;
  }
}
</style>

<style>
:root {
  --sidebar-bg: #f8f9fa;
  --sidebar-width: 300px; /* Increased from 250px */
  --sidebar-item-padding: 1rem 1.25rem; /* Increased padding */
  --sidebar-icon-size: 1.5rem; /* Larger icons */
  --sidebar-text-size: 1.1rem; /* Larger text */
  --sidebar-icon-color: #495057;
  --sidebar-text-color: #495057;
  --sidebar-hover-bg: #e9ecef;
  --sidebar-active-bg: #e9ecef;
  --sidebar-active-color: #000;
  --sidebar-border-color: #dee2e6;
  --sidebar-transition: all 0.3s ease; /* Slower transition */
  --sidebar-border-radius: 8px; /* Added rounded corners */
  --sidebar-item-spacing: 0.5rem; /* More space between items */
}

.sidebar {
  width: var(--sidebar-width);
  background: var(--sidebar-bg);
  border-right: 1px solid var(--sidebar-border-color);
  transition: var(--sidebar-transition);
  min-height: 100vh;
  padding: 1.5rem 0; /* More padding */
  text-decoration: none;
  box-shadow: 2px 0 10px rgba(0,0,0,0.05); /* Added subtle shadow */
}

.sidebar-nav {
  padding: 0 1.5rem; /* More padding */
  list-style: none;
}

.nav-item {
  margin-bottom: var(--sidebar-item-spacing);
}

.nav-link {
  display: flex;
  align-items: center;
  padding: var(--sidebar-item-padding);
  color: var(--sidebar-text-color);
  text-decoration: none;
  border-radius: var(--sidebar-border-radius);
  transition: var(--sidebar-transition);
  font-weight: 500; /* Slightly bolder text */
}

.nav-link i {
  font-size: var(--sidebar-icon-size);
  margin-right: 1rem; /* More space between icon and text */
  color: var(--sidebar-icon-color);
  width: 1.5rem; /* Larger icon container */
  text-align: center;
}

.nav-link span {
  font-size: var(--sidebar-text-size);
  letter-spacing: 0.3px; /* Slightly more spacing between letters */
}

.nav-link:hover {
  background: var(--sidebar-hover-bg);
  color: var(--sidebar-active-color);
  transform: translateX(5px); /* Slight movement on hover */
}

.nav-link.active {
  background: var(--sidebar-active-bg);
  color: var(--sidebar-active-color);
  font-weight: 600; /* Bolder for active item */
  border-left: 4px solid #0d6efd; /* Blue accent for active item */
}

.nav-link.active i {
  color: var(--sidebar-active-color);
}

/* Collapsible items */
.nav-link[data-bs-toggle="collapse"]::after {
  display: inline-block;
  margin-left: auto;
  transition: transform 0.3s ease; /* Slower rotation */
  font-size: 1.1rem; /* Larger chevron */
}

.nav-link[data-bs-toggle="collapse"].collapsed::after {
  transform: rotate(-90deg);
}

/* Nested nav items */
.nav-content {
  padding: 0.75rem 0 0 3rem; /* More padding and indentation */
  list-style: none;
}

.nav-content .nav-link {
  padding: 0.75rem 1.25rem; /* More padding */
  font-size: 1rem; /* Slightly smaller than top level */
}

.nav-content .nav-link i {
  font-size: 1rem; /* Larger than before */
}

/* Responsive adjustments */
@media (max-width: 1200px) {
  .sidebar {
    width: 90px;
    overflow: hidden;
  }
  
  .sidebar:hover {
    width: var(--sidebar-width);
    z-index: 1000; /* Ensure it appears above content */
  }
  
  .sidebar .nav-link span {
    display: none;
  }
  
  .sidebar:hover .nav-link span {
    display: inline;
  }
  
  .sidebar .nav-link[data-bs-toggle="collapse"]::after {
    display: none;
  }
  
  .sidebar:hover .nav-link[data-bs-toggle="collapse"]::after {
    display: inline-block;
  }
  
  .sidebar .nav-content {
    display: none;
  }
  
  .sidebar:hover .nav-content {
    display: block;
  }
}

/* Animation for sidebar items */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.nav-item {
  animation: slideIn 0.3s ease forwards;
  opacity: 0;
}

/* Add delay for each item */
.nav-item:nth-child(1) { animation-delay: 0.1s; }
.nav-item:nth-child(2) { animation-delay: 0.15s; }
.nav-item:nth-child(3) { animation-delay: 0.2s; }
.nav-item:nth-child(4) { animation-delay: 0.25s; }
.nav-item:nth-child(5) { animation-delay: 0.3s; }
.nav-item:nth-child(6) { animation-delay: 0.35s; }
.nav-item:nth-child(7) { animation-delay: 0.4s; }
.nav-item:nth-child(8) { animation-delay: 0.45s; }
.nav-item:nth-child(9) { animation-delay: 0.5s; }
.nav-item:nth-child(10) { animation-delay: 0.55s; }
</style>