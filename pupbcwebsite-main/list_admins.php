<?php
require 'pupbcwebsite-main/pupbc-website/DATAANALYTICS/db.php';
$res = $conn->query("SELECT id, username FROM admins");
while ($row = $res->fetch_assoc()) {
    echo $row['id'] . ':' . $row['username'] . PHP_EOL;
}
$res->close();
