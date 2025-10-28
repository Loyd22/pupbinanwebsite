<?php
require __DIR__ . '/pupbcwebsite-main/pupbc-website/DATAANALYTICS/db.php';

$result = $conn->query('SELECT id, title FROM news ORDER BY id ASC');
while ($row = $result->fetch_assoc()) {
    echo $row['id'], ' | ', $row['title'], PHP_EOL;
}
