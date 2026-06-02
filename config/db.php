<?php
require_once __DIR__ . '/functions.php';

$conn = mysqli_connect('162.214.80.164', 'svkniomy_rioagency', 'Rioagency', 'svkniomy_rioagency');

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
cms_run_schema_updates($conn);

