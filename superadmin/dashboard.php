<?php
session_start();
include "../db_conn.php";

// Authentication check
if (!isset($_SESSION['user_role'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['user_role'] !== 'Super Admin' && $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../403.php");
    exit;
}

// Include components
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<style>
    /* Your existing CSS styles */
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
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
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
    
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 1rem;
    }
    
    .chart-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
    
    @media (max-width: 768px) {
        .chart-container {
            height: 250px;
        }
    }
</style>

<main id="main" class="main">
    <section class="dashboard section">
        <div class="container">
            <!-- Dashboard Cards Row -->
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
                                        <li><a class="dropdown-item time-period" href="#" data-period="all">All</a></li>
                                        <li><a class="dropdown-item time-period" href="#" data-period="today">Today</a></li>
                                        <li><a class="dropdown-item time-period" href="#" data-period="week">This Week</a></li>
                                        <li><a class="dropdown-item time-period" href="#" data-period="month">This Month</a></li>
                                        <li><a class="dropdown-item time-period" href="#" data-period="year">This Year</a></li>
                                    </ul>
                                </div>
                            </h5>
                            <h4 class="card-text" id="salesAmount">
                                <?php
                                try {
                                    $sql = "SELECT SUM(total_price - (total_price * (discount / 100))) AS total_sales FROM transaction";
                                    $result = $con->query($sql);
                                    $totalSales = $result->fetch_assoc()['total_sales'] ?? 0;
                                    echo "<span>₱</span>" . number_format($totalSales, 2);
                                } catch (Exception $e) {
                                    echo "<span>₱</span>0.00";
                                }
                                ?>
                            </h4>
                            <small class="text-muted" id="salesPeriodText">All time</small>
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
                                try {
                                    $sql = "SELECT COUNT(DISTINCT product_name) AS total_products FROM products";
                                    $result = $con->query($sql);
                                    echo $result->fetch_assoc()['total_products'] ?? 0;
                                } catch (Exception $e) {
                                    echo "0";
                                }
                                ?>
                            </h4>
                            <small class="text-muted">Active products</small>
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
                                try {
                                    $sql = "SELECT COUNT(*) AS total_users FROM users";
                                    $result = $con->query($sql);
                                    echo $result->fetch_assoc()['total_users'] ?? 0;
                                } catch (Exception $e) {
                                    echo "0";
                                }
                                ?>
                            </h4>
                            <small class="text-muted">Registered users</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="row">
                <!-- Line Chart (Sales Over Time) -->
                <div class="mb-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Sales Over Time</h5>
                            <div class="chart-container">
                                <div class="chart-loading">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
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
                                <div class="chart-loading">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <canvas id="barChartMedicine"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row 2 -->
            <div class="row">
                <!-- Bar Graph (Most Purchased Supplies) -->
                <div class="mb-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Most Purchased Supplies</h5>
                            <div class="chart-container">
                                <div class="chart-loading">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
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
                                <div class="chart-loading">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
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
            
            // Color palette
            const pastelPink = '#ffb3c6';
            const pastelPinkLight = '#ffd6e0';
            const pastelBlue = '#99c2ff';
            const pastelBlueLight = '#c1e0ff';
            const pastelGreen = '#c1f0c1';
            const pastelYellow = '#fff2b8';
            const pastelPurple = '#d4b8ff';
            
            // Format currency
            const formatCurrency = (value) => {
                return '₱' + parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            };
            
            // Initialize charts when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Load initial data
                loadChartData();
                
                // Set up time period selector
                document.querySelectorAll('.time-period').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const period = this.getAttribute('data-period');
                        updateSalesData(period);
                    });
                });
            });
            
            // Load chart data from server
            function loadChartData() {
                // Show loading spinners
                document.querySelectorAll('.chart-loading').forEach(el => el.style.display = 'flex');
                
                // Fetch data for each chart
                Promise.all([
                    fetchData('sales_over_time'),
                    fetchData('top_medicines'),
                    fetchData('top_supplies'),
                    fetchData('sales_by_branch')
                ])
                .then(([salesData, medicineData, suppliesData, branchData]) => {
                    initLineChart(salesData);
                    initMedicineChart(medicineData);
                    initSuppliesChart(suppliesData);
                    initBranchChart(branchData);
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                    // Hide loading spinners
                    document.querySelectorAll('.chart-loading').forEach(el => el.style.display = 'none');
                });
            }
            
            // Fetch data from server
            function fetchData(endpoint) {
                return fetch(`dashboard_data.php?action=${endpoint}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    });
            }
            
            // Update sales data based on time period
            function updateSalesData(period) {
                fetch(`dashboard_data.php?action=sales_by_period&period=${period}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('salesAmount').innerHTML = `<span>₱</span>${data.totalSales.toFixed(2)}`;
                        
                        // Update period text
                        const periodText = {
                            'all': 'All time',
                            'today': 'Today',
                            'week': 'This week',
                            'month': 'This month',
                            'year': 'This year'
                        };
                        document.getElementById('salesPeriodText').textContent = periodText[period];
                    })
                    .catch(error => {
                        console.error('Error updating sales data:', error);
                    });
            }
            
            // Initialize Line Chart
            function initLineChart(data) {
                const ctx = document.getElementById('lineChart').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Total Sales',
                            data: data.values,
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
                                        return formatCurrency(tooltipItem.raw);
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
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return formatCurrency(value);
                                    }
                                }
                            }
                        }
                    }
                });
                
                // Hide loading spinner
                document.querySelector('#lineChart').closest('.chart-container').querySelector('.chart-loading').style.display = 'none';
            }
            
            // Initialize Medicine Chart
            function initMedicineChart(data) {
                const ctx = document.getElementById('barChartMedicine').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Medicine Sales Count',
                            data: data.values,
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
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return `${tooltipItem.raw} sales`;
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
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
                
                // Hide loading spinner
                document.querySelector('#barChartMedicine').closest('.chart-container').querySelector('.chart-loading').style.display = 'none';
            }
            
            // Initialize Supplies Chart
            function initSuppliesChart(data) {
                const ctx = document.getElementById('barChartSupplies').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Supplies Sales Count',
                            data: data.values,
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
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return `${tooltipItem.raw} sales`;
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
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
                
                // Hide loading spinner
                document.querySelector('#barChartSupplies').closest('.chart-container').querySelector('.chart-loading').style.display = 'none';
            }
            
            // Initialize Branch Chart
            function initBranchChart(data) {
                const ctx = document.getElementById('pieChartBranch').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: [
                                pastelPinkLight,
                                pastelBlueLight,
                                pastelGreen,
                                pastelYellow,
                                pastelPurple
                            ],
                            borderColor: [
                                pastelPink,
                                pastelBlue,
                                '#a8e0a8',
                                '#ffe699',
                                '#c4a8ff'
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
                                        return `${tooltipItem.label}: ${formatCurrency(tooltipItem.raw)} (${Math.round(tooltipItem.percent)}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                
                // Hide loading spinner
                document.querySelector('#pieChartBranch').closest('.chart-container').querySelector('.chart-loading').style.display = 'none';
            }
        </script>
    </section>
</main>

<?php
include '../includes/footer.php';
?>