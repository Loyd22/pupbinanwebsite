<?php
include "db.php";

// Visitors today
$today = $conn->query("SELECT COUNT(*) AS count FROM visitors WHERE DATE(visit_time) = CURDATE()")->fetch_assoc()['count'];

// Visitors this week
$week = $conn->query("SELECT COUNT(*) AS count FROM visitors WHERE YEARWEEK(visit_time, 1) = YEARWEEK(CURDATE(), 1)")->fetch_assoc()['count'];

// Visitors this month
$month = $conn->query("SELECT COUNT(*) AS count FROM visitors WHERE YEAR(visit_time) = YEAR(CURDATE()) AND MONTH(visit_time) = MONTH(CURDATE())")->fetch_assoc()['count'];

// Total visitors
$total = $conn->query("SELECT COUNT(*) AS count FROM visitors")->fetch_assoc()['count'];

echo json_encode([
  "today" => $today,
  "week"  => $week,
  "month" => $month,
  "total" => $total
]);
?>
