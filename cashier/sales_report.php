<title>DynaCareSIS - sales</title>
<?php
session_start();
include "../db_conn.php";


if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit;
}

// Restrict access to Cashier role only
if ($_SESSION['user_role'] !== 'Cashier') {
    header("Location: ../403.php"); // Redirect unauthorized users
    exit;
}

$branchId = $_SESSION['branches_id']; // Get the logged-in user's branch ID

// Include layout components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="container">
        <div class="row">
    <!-- Sales Card -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-cart-fill me-2"></i> Sales
                    <!-- Dropdown for time period selection -->
                    <div class="dropdown d-inline-block float-end">
                        <button class="btn btn-link dropdown-toggle" type="button" id="salesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="salesDropdown">
                            <li><a class="dropdown-item" href="?time_period=today">Today</a></li>
                            <li><a class="dropdown-item" href="?time_period=week">This Week</a></li>
                            <li><a class="dropdown-item" href="?time_period=month">This Month</a></li>
                            <li><a class="dropdown-item" href="?time_period=year">This Year</a></li> <!-- Added Weekly Filter -->
                        </ul>
                    </div>
                </h5>

                <h4 class="card-text">
                <?php
                // Get the selected time period or default to "all"
                $timePeriod = isset($_GET['time_period']) ? $_GET['time_period'] : 'all';

                // Initialize the SQL query
                $sql = "SELECT total_price, discount, date FROM transaction t
                        JOIN products p ON t.products_id = p.id
                        WHERE p.branches_id = ? ";

                // Add conditions based on the selected time period
                if ($timePeriod == 'today') {
                    $sql .= "AND DATE(t.date) = CURDATE()"; // Today's date
                } elseif ($timePeriod == 'month') {
                    $sql .= "AND YEAR(t.date) = YEAR(CURDATE()) AND MONTH(t.date) = MONTH(CURDATE())"; // This month
                } elseif ($timePeriod == 'year') {
                    $sql .= "AND YEAR(t.date) = YEAR(CURDATE())"; // This year
                } elseif ($timePeriod == 'week') {
                    $sql .= "AND YEARWEEK(t.date, 1) = YEARWEEK(CURDATE(), 1)"; // This week
                } elseif ($timePeriod == 'all') {
                    // No additional condition for "all"
                }

                // Prepare the statement to prevent SQL injection
                if ($stmt = $con->prepare($sql)) {
                    $stmt->bind_param("i", $branchId);
                    $stmt->execute();
                    $result = $stmt->get_result();

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
                } else {
                    echo "Error: Unable to fetch sales data.";
                }
                ?>
                </h4>
            </div>
        </div>
    </div>

    <!-- Line Chart (Sales Over Time) -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Sales Over Time</h5>
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
<div class="row">
    <!-- Bar Graph (Most Purchased Medicine) -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Most Purchased Medicine</h5>
                <canvas id="barChartMedicine"></canvas>
            </div>
        </div>
    </div>
    <!-- Bar Graph (Most Purchased Supplies) -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Most Purchased Supplies</h5>
                <canvas id="barChartSupplies"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for Line Chart (Sales Over Time)
    <?php
    // Query to fetch total sales by date for the logged-in branch
    $sql = "SELECT DATE(t.date) AS sale_date, SUM(t.total_price - (t.total_price * (t.discount / 100))) AS total_sales
            FROM transaction t
            JOIN products p ON t.products_id = p.id
            WHERE p.branches_id = ?
            GROUP BY sale_date
            ORDER BY sale_date";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $result = $stmt->get_result();

        $labels_sales = [];
        $data_sales = [];

        while ($row = $result->fetch_assoc()) {
            $labels_sales[] = $row['sale_date']; // Store sale dates
            $data_sales[] = $row['total_sales']; // Store total sales per date
        }
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
                            return "₱" + tooltipItem.raw.toFixed(2); // Format the sales with peso sign
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
    // Query to fetch Medicine product sales count for the logged-in branch
    $sql = "SELECT p.product_name, COUNT(*) AS sales_count
            FROM transaction t 
            INNER JOIN products p ON t.products_id = p.id
            WHERE p.branches_id = ? AND p.categories_id = (SELECT id FROM categories WHERE category_name = 'Medicine')
            GROUP BY p.product_name 
            ORDER BY sales_count DESC";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $result = $stmt->get_result();

        $labels_medicine = [];
        $data_medicine = [];

        while ($row = $result->fetch_assoc()) {
            $labels_medicine[] = $row['product_name'];
            $data_medicine[] = $row['sales_count'];
        }
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
    // Query to fetch Supplies product sales count for the logged-in branch
    $sql = "SELECT p.product_name, COUNT(*) AS sales_count
            FROM transaction t 
            INNER JOIN products p ON t.products_id = p.id
            WHERE p.branches_id = ? AND p.categories_id = (SELECT id FROM categories WHERE category_name = 'Supplies')
            GROUP BY p.product_name 
            ORDER BY sales_count DESC";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $branchId);
        $stmt->execute();
        $result = $stmt->get_result();

        $labels_supplies = [];
        $data_supplies = [];

        while ($row = $result->fetch_assoc()) {
            $labels_supplies[] = $row['product_name'];
            $data_supplies[] = $row['sales_count'];
        }
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
</script>

