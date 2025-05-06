<?php
require_once "../db_conn.php";

header('Content-Type: application/json');

// Check authentication
session_start();
if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'Super Admin' && $_SESSION['user_role'] !== 'Admin')) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$action = $_GET['action'] ?? '';

try {
    $con->set_charset("utf8mb4");
    
    switch ($action) {
        case 'sales_over_time':
            $sql = "SELECT DATE(date) AS sale_date, SUM(total_price - (total_price * (discount / 100))) AS total_sales 
                    FROM transaction 
                    GROUP BY sale_date 
                    ORDER BY sale_date";
            $result = $con->query($sql);
            
            $data = ['labels' => [], 'values' => []];
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['sale_date'];
                $data['values'][] = (float)$row['total_sales'];
            }
            echo json_encode($data);
            break;
            
        case 'top_medicines':
            $sql = "SELECT p.product_name, COUNT(*) AS sales_count
                    FROM transaction t 
                    INNER JOIN products p ON t.products_id = p.id
                    WHERE p.categories_id = (SELECT id FROM categories WHERE category_name = 'Medicine')
                    GROUP BY p.product_name 
                    ORDER BY sales_count DESC
                    LIMIT 10";
            $result = $con->query($sql);
            
            $data = ['labels' => [], 'values' => []];
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['product_name'];
                $data['values'][] = (int)$row['sales_count'];
            }
            echo json_encode($data);
            break;
            
        case 'top_supplies':
            $sql = "SELECT p.product_name, COUNT(*) AS sales_count
                    FROM transaction t 
                    INNER JOIN products p ON t.products_id = p.id
                    WHERE p.categories_id = (SELECT id FROM categories WHERE category_name = 'Supplies')
                    GROUP BY p.product_name 
                    ORDER BY sales_count DESC
                    LIMIT 10";
            $result = $con->query($sql);
            
            $data = ['labels' => [], 'values' => []];
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['product_name'];
                $data['values'][] = (int)$row['sales_count'];
            }
            echo json_encode($data);
            break;
            
        case 'sales_by_branch':
            $sql = "SELECT b.branch_name, SUM(t.total_price - (t.total_price * (t.discount / 100))) AS total_sales
                    FROM transaction t
                    INNER JOIN products p ON t.products_id = p.id
                    INNER JOIN branches b ON p.branches_id = b.id
                    GROUP BY b.branch_name";
            $result = $con->query($sql);
            
            $data = ['labels' => [], 'values' => []];
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['branch_name'];
                $data['values'][] = (float)$row['total_sales'];
            }
            echo json_encode($data);
            break;
            
        case 'sales_by_period':
            $period = $_GET['period'] ?? 'all';
            $sql = "SELECT SUM(total_price - (total_price * (discount / 100))) AS total_sales FROM transaction WHERE ";
            
            switch ($period) {
                case 'today':
                    $sql .= "DATE(date) = CURDATE()";
                    break;
                case 'week':
                    $sql .= "YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
                    break;
                case 'month':
                    $sql .= "YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE())";
                    break;
                case 'year':
                    $sql .= "YEAR(date) = YEAR(CURDATE())";
                    break;
                default:
                    $sql .= "1";
            }
            
            $result = $con->query($sql);
            $totalSales = $result->fetch_assoc()['total_sales'] ?? 0;
            
            echo json_encode(['totalSales' => (float)$totalSales]);
            break;

        // Add this case to your switch statement in dashboard_data.php
        case 'sales_by_branch_period':
            $branchId = intval($_GET['branch_id']);
            $period = $_GET['period'] ?? 'all';
            
            $sql = "SELECT DATE(date) AS sale_date, 
                        SUM(total_price - (total_price * (discount / 100))) AS total_sales 
                    FROM transaction t
                    INNER JOIN products p ON t.products_id = p.id
                    WHERE p.branches_id = $branchId AND ";
            
            switch ($period) {
                case 'today': $sql .= "DATE(t.date) = CURDATE()"; break;
                case 'week': $sql .= "YEARWEEK(t.date, 1) = YEARWEEK(CURDATE(), 1)"; break;
                case 'month': $sql .= "YEAR(t.date) = YEAR(CURDATE()) AND MONTH(t.date) = MONTH(CURDATE())"; break;
                case 'year': $sql .= "YEAR(t.date) = YEAR(CURDATE())"; break;
                default: $sql .= "1=1";
            }
            
            $sql .= " GROUP BY sale_date ORDER BY sale_date";
            
            $result = $con->query($sql);
            $data = ['labels' => [], 'values' => []];
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['sale_date'];
                $data['values'][] = (float)$row['total_sales'];
            }
            echo json_encode($data);
            break;
                    
                default:
                    echo json_encode(['error' => 'Invalid action']);
            }
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($con)) {
        $con->close();
    }
}
?>  