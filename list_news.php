<?php
require __DIR__ . '/pupbcwebsite-main/pupbc-website/DATAANALYTICS/db.php';
$result = $conn->query('SELECT id, title, publish_date, is_published, created_at, created_by FROM news ORDER BY id DESC');
while ($row = $result->fetch_assoc()) {
    echo implode(' | ', $row) . "\n";
}
?>
