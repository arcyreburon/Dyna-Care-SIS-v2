<style>
    * {
        font-family: 'Poppins', sans-serif !important;
    }
    
    :root {
        --pastel-pink: #ffd6e0;
        --pastel-pink-dark: #ffb3c6;
        --pastel-blue: #c1e0ff;
        --pastel-blue-dark: #99c2ff;
        --text-dark: #4a4a4a;
        --text-light: #6c757d;
        --card-bg: #ffffff;
    }
    
    body {
        background-color: #f8f9fa;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        background-color: var(--card-bg);
    }
    
    .card-title {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-light);
    }
    
    .card-text {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .bi {
        color: var(--text-light);
    }
    
    /* Chart containers */
    .chart-container {
        position: relative;
        height: 300px;
    }
    
    /* Custom dropdown styling */
    .dropdown-toggle::after {
        display: none;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    
</style>

<title>Dashboard</title>
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
include '../includes/footer.php';
?>

<main id="main" class="main">
    <section class="dashboard section">
        <div class="container">
            <div class="row">
                <!-- Sales Card -->
                <div class="mb-4 col-lg-4 col-md-6">
                    <div class="card" id="salesCard">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-cart-fill me-2"></i> Sales
                                <div class="d-inline-block float-end dropdown">
                                    <button class="btn btn-link dropdown-toggle p-0" type="button" id="salesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="salesDropdown">
                                        <li><a class="dropdown-item" href="?time_period=all">All</a></li>
                                        <li><a class="dropdown-item" href="?time_period=today">Today</a></li>
                                        <li><a class="dropdown-item" href="?time_period=month">This Month</a></li>
                                        <li><a class="dropdown-item" href="?time_period=year">This Year</a></li>
                                        <li><a class="dropdown-item" href="?time_period=week">This Week</a></li>
                                    </ul>
                                </div>
                            </h5>
                            <h4 class="card-text">
                                <?php
                                $timePeriod = isset($_GET['time_period']) ? $_GET['time_period'] : 'all';
                                $sql = "SELECT total_price, discount, date FROM transaction WHERE ";
                                
                                if ($timePeriod == 'today') {
                                    $sql .= "DATE(date) = CURDATE()";
                                } elseif ($timePeriod == 'month') {
                                    $sql .= "YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE())";
                                } elseif ($timePeriod == 'year') {
                                    $sql .= "YEAR(date) = YEAR(CURDATE())";
                                } elseif ($timePeriod == 'week') {
                                    $sql .= "YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
                                } elseif ($timePeriod == 'all') {
                                    $sql .= "1";
                                }
                                
                                $result = $con->query($sql);
                                $totalSales = 0;
                                
                                while ($row = $result->fetch_assoc()) {
                                    $totalPrice = $row['total_price'];
                                    $discount = $row['discount'];
                                    $discountedPrice = $totalPrice - ($totalPrice * ($discount / 100));
                                    $totalSales += $discountedPrice;
                                }
                                
                                echo "<span>₱</span> " . number_format($totalSales, 2);
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="mb-4 col-lg-4 col-md-6">
                    <div class="card" id="productsCard">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-box me-2"></i> Products
                            </h5>
                            <h4 class="card-text">
                                <?php
                                $sql = "SELECT COUNT(DISTINCT product_name) AS total_products FROM products";
                                $result = $con->query($sql);
                                
                                if ($row = $result->fetch_assoc()) {
                                    echo $row['total_products'];
                                } else {
                                    echo "0";
                                }
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="mb-4 col-lg-4 col-md-6">
                    <div class="card" id="usersCard">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-person-fill me-2"></i> Users
                            </h5>
                            <h4 class="card-text">
                                <?php
                                $sql = "SELECT COUNT(*) AS total_users FROM users";
                                $result = $con->query($sql);
                                
                                if ($row = $result->fetch_assoc()) {
                                    echo $row['total_users'];
                                } else {
                                    echo "0";
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
                            <div class="chart-container">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bar Graph (Most Purchased Medicine) -->
                <div class="mb-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Most Purchased Medicine</h5>
                            <div class="chart-container">
                                <canvas id="barChartMedicine"></canvas>
                            </div>
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
                            <div class="chart-container">
                                <canvas id="barChartSupplies"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pie Chart (Sales by Branch) -->
                <div class="mb-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Sales by Branch</h5>
                            <div class="chart-container">
                                <canvas id="pieChartBranch"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Set global chart styles
            Chart.defaults.font.family = "'Poppins', sans-serif";
            Chart.defaults.color = '#6c757d';
            Chart.defaults.borderColor = 'rgba(0, 0, 0, 0.05)';
            
            // Pastel color palette
            const pastelPink = '#ffb3c6';
            const pastelPinkLight = '#ffd6e0';
            const pastelBlue = '#99c2ff';
            const pastelBlueLight = '#c1e0ff';
            
            // Line Chart (Sales Over Time)
            <?php
            $sql = "SELECT DATE(date) AS sale_date, SUM(total_price - (total_price * (discount / 100))) AS total_sales 
                    FROM transaction 
                    GROUP BY sale_date 
                    ORDER BY sale_date";
            $result = $con->query($sql);
            
            $labels_sales = [];
            $data_sales = [];
            
            while ($row = $result->fetch_assoc()) {
                $labels_sales[] = $row['sale_date'];
                $data_sales[] = $row['total_sales'];
            }
            ?>
            
            const lineChart = new Chart(document.getElementById('lineChart'), {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($labels_sales); ?>,
                    datasets: [{
                        label: 'Total Sales',
                        data: <?php echo json_encode($data_sales); ?>,
                        borderColor: pastelPink,
                        backgroundColor: pastelPinkLight,
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true,
                        pointBackgroundColor: pastelPink,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
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
                                display: false,
                                drawBorder: false
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.03)',
                                drawBorder: false
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Bar Graph (Most Purchased Medicine)
            <?php
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
            
            const barChartMedicine = new Chart(document.getElementById('barChartMedicine'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels_medicine); ?>,
                    datasets: [{
                        label: 'Medicine Sales Count',
                        data: <?php echo json_encode($data_medicine); ?>,
                        backgroundColor: pastelBlueLight,
                        borderColor: pastelBlue,
                        borderWidth: 1
                    }]
                },
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
                                display: false,
                                drawBorder: false
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.03)',
                                drawBorder: false
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Bar Graph (Most Purchased Supplies)
            <?php
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
            
            const barChartSupplies = new Chart(document.getElementById('barChartSupplies'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels_supplies); ?>,
                    datasets: [{
                        label: 'Supplies Sales Count',
                        data: <?php echo json_encode($data_supplies); ?>,
                        backgroundColor: pastelPinkLight,
                        borderColor: pastelPink,
                        borderWidth: 1
                    }]
                },
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
                                display: false,
                                drawBorder: false
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.03)',
                                drawBorder: false
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Pie Chart (Sales by Branch)
            <?php
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
            
            const pieChartBranch = new Chart(document.getElementById('pieChartBranch'), {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($labels_branches); ?>,
                    datasets: [{
                        data: <?php echo json_encode($data_branches); ?>,
                        backgroundColor: [
                            pastelPinkLight,
                            pastelBlueLight,
                            '#e2f0cb',
                            '#ffecb8',
                            '#d4e4f7'
                        ],
                        borderColor: [
                            pastelPink,
                            pastelBlue,
                            '#c8e0a8',
                            '#ffe699',
                            '#b8d4f7'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return "₱" + tooltipItem.raw.toFixed(2); 
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </section>
</main>