<title>DynaCareSIS - sales</title>
<?php
session_start();
include "../db_conn.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['user_role'] !== 'Cashier') {
    header("Location: ../403.php");
    exit;
}

$branchId = $_SESSION['branches_id'];

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/footer.php';
?>

<style>
    :root {
        --soft-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    body {
        background-color: #fafafa;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--soft-shadow);
        transition: transform 0.2s ease;
        background-color: white;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .card-title {
        color: #5a5a5a;
        font-weight: 500;
    }
    
    .card-text {
        color: #333;
        font-weight: 600;
    }
    
    #salesDropdown {
        color: #888;
    }
    
    .dropdown-menu {
        border-radius: 8px;
        box-shadow: var(--soft-shadow);
        border: none;
    }
    
    .dropdown-item:hover {
        background-color: var(--pastel-pink);
    }
    
    /* Custom chart colors */
    .chart-container {
        position: relative;
        height: 250px;
    }
</style>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="container">
            <div class="row">
                <!-- Sales Card -->
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card" style="background-color: var(--pastel-pink);">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-cart-fill me-2"></i> Sales
                                <div class="dropdown d-inline-block float-end">
                                    <button class="btn btn-link dropdown-toggle" type="button" id="salesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="salesDropdown">
                                        <li><a class="dropdown-item" href="?time_period=today">Today</a></li>
                                        <li><a class="dropdown-item" href="?time_period=week">This Week</a></li>
                                        <li><a class="dropdown-item" href="?time_period=month">This Month</a></li>
                                        <li><a class="dropdown-item" href="?time_period=year">This Year</a></li>
                                    </ul>
                                </div>
                            </h5>
                            <h4 class="card-text">
                            <?php
                            $timePeriod = isset($_GET['time_period']) ? $_GET['time_period'] : 'all';
                            $sql = "SELECT total_price, discount, date FROM transaction t
                                    JOIN products p ON t.products_id = p.id
                                    WHERE p.branches_id = ? ";
                            
                            if ($timePeriod == 'today') {
                                $sql .= "AND DATE(t.date) = CURDATE()";
                            } elseif ($timePeriod == 'month') {
                                $sql .= "AND YEAR(t.date) = YEAR(CURDATE()) AND MONTH(t.date) = MONTH(CURDATE())";
                            } elseif ($timePeriod == 'year') {
                                $sql .= "AND YEAR(t.date) = YEAR(CURDATE())";
                            } elseif ($timePeriod == 'week') {
                                $sql .= "AND YEARWEEK(t.date, 1) = YEARWEEK(CURDATE(), 1)";
                            }
                            
                            if ($stmt = $con->prepare($sql)) {
                                $stmt->bind_param("i", $branchId);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $totalSales = 0;
                                
                                while ($row = $result->fetch_assoc()) {
                                    $totalPrice = $row['total_price'];
                                    $discount = $row['discount'];
                                    $discountedPrice = $totalPrice - ($totalPrice * ($discount / 100));
                                    $totalSales += $discountedPrice;
                                }
                                
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
                    <div class="card" style="background-color: var(--pastel-blue);">
                        <div class="card-body">
                            <h5 class="card-title">Sales Over Time</h5>
                            <div class="chart-container">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Bar Graph (Most Purchased Medicine) -->
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Most Purchased Medicine</h5>
                            <div class="chart-container">
                                <canvas id="barChartMedicine"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bar Graph (Most Purchased Supplies) -->
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Most Purchased Supplies</h5>
                            <div class="chart-container">
                                <canvas id="barChartSupplies"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Line Chart (Sales Over Time)
    <?php
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
            $labels_sales[] = $row['sale_date'];
            $data_sales[] = $row['total_sales'];
        }
    }
    ?>

    const lineChartData = {
        labels: <?php echo json_encode($labels_sales); ?>,
        datasets: [{
            label: 'Total Sales',
            data: <?php echo json_encode($data_sales); ?>,
            borderColor: '#8a89c0',
            backgroundColor: 'rgba(138, 137, 192, 0.2)',
            fill: true,
            tension: 0.3
        }]
    };

    const lineChartConfig = {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
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
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            }
        }
    };

    new Chart(document.getElementById('lineChart'), lineChartConfig);

    // Bar Chart (Most Purchased Medicine)
    <?php
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
            backgroundColor: 'rgba(255, 182, 193, 0.6)',
            borderColor: 'rgba(255, 182, 193, 1)',
            borderWidth: 1
        }]
    };

    const barChartMedicineConfig = {
        type: 'bar',
        data: barChartMedicineData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            }
        }
    };

    new Chart(document.getElementById('barChartMedicine'), barChartMedicineConfig);

    // Bar Chart (Most Purchased Supplies)
    <?php
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
            backgroundColor: 'rgba(173, 216, 230, 0.6)',
            borderColor: 'rgba(173, 216, 230, 1)',
            borderWidth: 1
        }]
    };

    const barChartSuppliesConfig = {
        type: 'bar',
        data: barChartSuppliesData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            }
        }
    };

    new Chart(document.getElementById('barChartSupplies'), barChartSuppliesConfig);
</script>