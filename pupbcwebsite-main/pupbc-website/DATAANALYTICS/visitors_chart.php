<?php
header('Content-Type: application/json');
include 'db.php';

// Visits per day (last 7 days)
$sql = "SELECT DATE(visit_date) as label, COUNT(*) as count
        FROM page_visits
        WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(visit_date)
        ORDER BY label ASC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "label" => $row["label"],
        "count" => (int)$row["count"]
    ];
}

echo json_encode($data);
$conn->close();
?>
