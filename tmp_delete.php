<?php
require __DIR__ . '/pupbcwebsite-main/pupbc-website/DATAANALYTICS/db.php';
$conn->query("DELETE FROM news WHERE title = 'From Script'");
