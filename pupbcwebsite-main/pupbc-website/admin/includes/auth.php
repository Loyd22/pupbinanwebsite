<?php
declare(strict_types=1);

require_once __DIR__ . '/init.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
