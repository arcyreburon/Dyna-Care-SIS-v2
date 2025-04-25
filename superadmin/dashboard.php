<style>
    * {
        font-family: 'Poppins'; !important
    }
</style>
<title>Dashboard</title>
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
    <section class="dashboard section">
        <di class="container">
        <div class="row">
    <!-- Sales Card -->
    <div class="mb-4 col-lg-4 col-md-6">
    <div class="shadow-sm card">
        <div class="card-body">
            <h5 class="card-title">
                <i class="bi bi-cart-fill me-2"></i> Sales
                <!-- Dropdown for time period selection -->
                <div class="d-inline-block float-end dropdown">
    <button class="btn btn-link dropdown-toggle" type="button" id="salesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="salesDropdown">
        <li><a class="dropdown-item" href="?time_period=all">All</a></li>
        <li><a class="dropdown-item" href="?time_period=today">Today</a></li>
        <li><a class="dropdown-item" href="?time_period=month">This Month</a></li>
        <li><a class="dropdown-item" href="?time_period=year">This Year</a></li>
        <li><a class="dropdown-item" href="?time_period=week">This Week</a></li>
    </ul>
</div>

<style>
    .bi.bi-three-dots {
        font-size: 24px; /* Increase the size as needed */
        color: black; !important
    }
</style>

            </h5>

            <h4 class="card-text">
                <?php
// Get the selected time period or default to "all"
$timePeriod = isset($_GET['time_period']) ? $_GET['time_period'] : 'all';

// Initialize the SQL query
$sql = "SELECT total_price, discount, date FROM transaction WHERE ";

// Modify the query based on the selected time period
if ($timePeriod == 'today') {
    $sql .= "DATE(date) = CURDATE()"; // Today's date
} elseif ($timePeriod == 'month') {
    $sql .= "YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE())"; // This month
} elseif ($timePeriod == 'year') {
    $sql .= "YEAR(date) = YEAR(CURDATE())"; // This year
} elseif ($timePeriod == 'week') {
    $sql .= "YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)"; // This week
} elseif ($timePeriod == 'all') {
    // No filter for 'all', so select all data
    $sql .= "1"; // Just a condition that always evaluates to true (i.e., no filtering)
}

// Execute the query
$result = $con->query($sql);

// Initialize total sales variable
$totalSales = 0;

// Loop through the transactions and compute the total price after discount
while ($row = $result->fetch_assoc()) {
    $totalPrice = $row['total_price'];
    $discount = $row['discount'];

    // Apply the discount to the total price
    $discountedPrice = $totalPrice - ($totalPrice * ($discount / 100));

    // Add the discounted price to total sales
    $totalSales += $discountedPrice;
}

// Display the total sales with the peso sign
echo "<span>₱</span> " . number_format($totalSales, 2);

                ?>
            </h4>
        </div>
    </div>
</div>

<!-- Products Card -->
<div class="mb-4 col-lg-4 col-md-6">
    <div class="shadow-sm card">
        <div class="card-body">
            <h5 class="card-title">
                <i class="bi bi-box me-2"></i> Products
            </h5>
            <h4 class="card-text">
                <?php
                // Query to count unique product names
                $sql = "SELECT COUNT(DISTINCT product_name) AS total_products FROM products";
                $result = $con->query($sql);

                // Fetch the result and display the total number of unique products
                if ($row = $result->fetch_assoc()) {
                    echo $row['total_products'];
                } else {
                    echo "0"; // If there are no products
                }
                ?>
            </h4>
        </div>
    </div>
</div>

    <!-- Users Card -->
    <div class="mb-4 col-lg-4 col-md-6">
    <div class="shadow-sm card">
    <div class="card-body">
        <h5 class="card-title">
            <i class="bi bi-person-fill me-2"></i> Users
        </h5>
        <h4 class="card-text">
            <?php
            // Query to count the number of users
            $sql = "SELECT COUNT(*) AS total_users FROM users";
            $result = $con->query($sql);

            // Fetch the result and display the total number of users
            if ($row = $result->fetch_assoc()) {
                echo $row['total_users'];
            } else {
                echo "0"; // In case there are no users in the table
            }
            ?>
        </h4>
    </div>
</div>

    </div>
</div>


<div class="row">
    <!-- Line Chart (Sales Over Time) -->
    <div class="mb-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Sales Over Time</h5>
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Bar Graph (Most Purchased Medicine) -->
    <div class="mb-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Most Purchased Medicine</h5>
                <canvas id="barChartMedicine"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Bar Graph (Most Purchased Supplies) -->
    <div class="mb-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Most Purchased Supplies</h5>
                <canvas id="barChartSupplies"></canvas>
            </div>
        </div>
    </div>
    <!-- Pie Chart (Sales by Branch) -->
    <div class="mb-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Sales by Branch</h5>
                <canvas id="pieChartBranch"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for Line Chart (Sales Over Time)
    <?php
    // Query to fetch total sales by date
    $sql = "SELECT DATE(date) AS sale_date, SUM(total_price - (total_price * (discount / 100))) AS total_sales 
            FROM transaction 
            GROUP BY sale_date 
            ORDER BY sale_date";
    $result = $con->query($sql);

    $labels_sales = [];
    $data_sales = [];

    while ($row = $result->fetch_assoc()) {
        $labels_sales[] = $row['sale_date']; // Store sale dates
        $data_sales[] = $row['total_sales']; // Store total sales per date
    }
    ?>

const lineChartData = {
    labels: <?php echo json_encode($labels_sales); ?>,
    datasets: [{
        label: 'Total Sales',
        data: <?php echo json_encode($data_sales); ?>,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        fill: true,
        font: {
            family: 'Poppins',
            size: 14,
            weight: 'bold'
        }
    }]
};

    const lineChartConfig = {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return "₱" + tooltipItem.raw.toFixed(2); 
                        }
                    }
                }
            },
            scales: {
                x: {
                    type: 'category',
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Sales Amount (₱)'
                    }
                }
            }
        }
    };

    const lineChart = new Chart(document.getElementById('lineChart'), lineChartConfig);

    // Data for Bar Graph (Most Purchased Medicine)
    <?php
    // Query to fetch Medicine product sales count
    $sql = "SELECT p.product_name, COUNT(*) AS sales_count
            FROM transaction t 
            INNER JOIN products p ON t.products_id = p.id
            WHERE p.categories_id = (SELECT id FROM categories WHERE category_name = 'Medicine')
            GROUP BY p.product_name 
            ORDER BY sales_count DESC";
    $result = $con->query($sql);

    $labels_medicine = [];
    $data_medicine = [];

    while ($row = $result->fetch_assoc()) {
        $labels_medicine[] = $row['product_name'];
        $data_medicine[] = $row['sales_count'];
    }
    ?>

    const barChartMedicineData = {
        labels: <?php echo json_encode($labels_medicine); ?>,
        datasets: [{
            label: 'Medicine Sales Count',
            data: <?php echo json_encode($data_medicine); ?>,
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    };

    const barChartMedicineConfig = {
        type: 'bar',
        data: barChartMedicineData,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Product'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Sales Count'
                    }
                }
            }
        }
    };

    const barChartMedicine = new Chart(document.getElementById('barChartMedicine'), barChartMedicineConfig);

    // Data for Bar Graph (Most Purchased Supplies)
    <?php
    // Query to fetch Supplies product sales count
    $sql = "SELECT p.product_name, COUNT(*) AS sales_count
            FROM transaction t 
            INNER JOIN products p ON t.products_id = p.id
            WHERE p.categories_id = (SELECT id FROM categories WHERE category_name = 'Supplies')
            GROUP BY p.product_name 
            ORDER BY sales_count DESC";
    $result = $con->query($sql);

    $labels_supplies = [];
    $data_supplies = [];

    while ($row = $result->fetch_assoc()) {
        $labels_supplies[] = $row['product_name'];
        $data_supplies[] = $row['sales_count'];
    }
    ?>

    const barChartSuppliesData = {
        labels: <?php echo json_encode($labels_supplies); ?>,
        datasets: [{
            label: 'Supplies Sales Count',
            data: <?php echo json_encode($data_supplies); ?>,
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    };

    const barChartSuppliesConfig = {
        type: 'bar',
        data: barChartSuppliesData,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Product'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Sales Count'
                    }
                }
            }
        }
    };

    const barChartSupplies = new Chart(document.getElementById('barChartSupplies'), barChartSuppliesConfig);

    // Data for Pie Chart (Sales by Branch)
    <?php
    // Query to fetch sales by branch
    $sql = "SELECT b.branch_name, SUM(t.total_price - (t.total_price * (t.discount / 100))) AS total_sales
            FROM transaction t
            INNER JOIN products p ON t.products_id = p.id
            INNER JOIN branches b ON p.branches_id = b.id
            GROUP BY b.branch_name";
    $result = $con->query($sql);

    $labels_branches = [];
    $data_branches = [];

    while ($row = $result->fetch_assoc()) {
        $labels_branches[] = $row['branch_name'];
        $data_branches[] = $row['total_sales'];
    }
    ?>

    const pieChartBranchData = {
        labels: <?php echo json_encode($labels_branches); ?>,
        datasets: [{
            label: 'Sales by Branch',
            data: <?php echo json_encode($data_branches); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    };

    const pieChartBranchConfig = {
        type: 'pie',
        data: pieChartBranchData,
        options: {
            responsive: true,
        }
    };

    const pieChartBranch = new Chart(document.getElementById('pieChartBranch'), pieChartBranchConfig);
</script>
</section>
</main>

