<?php
include("../config/db.php");

echo "<h2>Fixing Blogs Table Schema</h2>";

$columns = [
    'header_image' => "VARCHAR(255) DEFAULT NULL",
    'header_image_alt' => "VARCHAR(255) DEFAULT NULL",
    'image_alt' => "VARCHAR(255) DEFAULT NULL",
    'image2' => "VARCHAR(255) DEFAULT NULL",
    'image2_alt' => "VARCHAR(255) DEFAULT NULL",
    'image3' => "VARCHAR(255) DEFAULT NULL",
    'image3_alt' => "VARCHAR(255) DEFAULT NULL",
    'meta_title' => "VARCHAR(255) DEFAULT NULL",
    'meta_description' => "TEXT DEFAULT NULL",
    'meta_tags' => "TEXT DEFAULT NULL",
    'meta_keywords' => "TEXT DEFAULT NULL",
    'backlinks' => "TEXT DEFAULT NULL"
];

foreach ($columns as $col => $def) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM blogs LIKE '$col'");
    if (mysqli_num_rows($check) == 0) {
        $sql = "ALTER TABLE blogs ADD COLUMN $col $def";
        if (mysqli_query($conn, $sql)) {
            echo "<p style='color:green'>Added column: $col</p>";
        } else {
            echo "<p style='color:red'>Error adding $col: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:blue'>Column $col already exists.</p>";
    }
}

echo "<p><strong>Database update complete.</strong> You can now try adding/updating blogs.</p>";
?>