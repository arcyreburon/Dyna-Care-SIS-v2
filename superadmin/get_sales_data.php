<?php
require_once "../db_conn.php";

header('Content-Type: application/json');

$period = $_GET['period'] ?? 'all';

try {
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
    
    echo json_encode(['totalSales' => $totalSales]);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>