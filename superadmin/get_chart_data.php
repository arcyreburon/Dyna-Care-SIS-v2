<?php
require_once "../db_conn.php";

header('Content-Type: application/json');

try {
    // Sales Over Time
    $sql = "SELECT DATE(date) AS sale_date, SUM(total_price - (total_price * (discount / 100))) AS total_sales 
            FROM transaction 
            GROUP BY sale_date 
            ORDER BY sale_date";
    $result = $con->query($sql);
    
    $salesOverTime = ['labels' => [], 'values' => []];
    while ($row = $result->fetch_assoc()) {
        $salesOverTime['labels'][] = $row['sale_date'];
        $salesOverTime['values'][] = $row['total_sales'];
    }
    
    // Top Medicines
    $sql = "SELECT p.product_name, COUNT(*) AS sales_count
            FROM transaction t 
            INNER JOIN products p ON t.products_id = p.id
            WHERE p.categories_id = (SELECT id FROM categories WHERE category_name = 'Medicine')
            GROUP BY p.product_name 
            ORDER BY sales_count DESC
            LIMIT 10";
    $result = $con->query($sql);
    
    $topMedicines = ['labels' => [], 'values' => []];
    while ($row = $result->fetch_assoc()) {
        $topMedicines['labels'][] = $row['product_name'];
        $topMedicines['values'][] = $row['sales_count'];
    }
    
    // Top Supplies
    $sql = "SELECT p.product_name, COUNT(*) AS sales_count
            FROM transaction t 
            INNER JOIN products p ON t.products_id = p.id
            WHERE p.categories_id = (SELECT id FROM categories WHERE category_name = 'Supplies')
            GROUP BY p.product_name 
            ORDER BY sales_count DESC
            LIMIT 10";
    $result = $con->query($sql);
    
    $topSupplies = ['labels' => [], 'values' => []];
    while ($row = $result->fetch_assoc()) {
        $topSupplies['labels'][] = $row['product_name'];
        $topSupplies['values'][] = $row['sales_count'];
    }
    
    // Sales by Branch
    $sql = "SELECT b.branch_name, SUM(t.total_price - (t.total_price * (t.discount / 100))) AS total_sales
            FROM transaction t
            INNER JOIN products p ON t.products_id = p.id
            INNER JOIN branches b ON p.branches_id = b.id
            GROUP BY b.branch_name";
    $result = $con->query($sql);
    
    $salesByBranch = ['labels' => [], 'values' => []];
    while ($row = $result->fetch_assoc()) {
        $salesByBranch['labels'][] = $row['branch_name'];
        $salesByBranch['values'][] = $row['total_sales'];
    }
    
    echo json_encode([
        'salesOverTime' => $salesOverTime,
        'topMedicines' => $topMedicines,
        'topSupplies' => $topSupplies,
        'salesByBranch' => $salesByBranch
    ]);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>