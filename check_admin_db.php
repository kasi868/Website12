<?php
require_once __DIR__ . '/config/db.php';

echo "<h2>Checking Database...</h2>";

echo "<h3>1. All Pages:</h3>";
$pages = mysqli_query($conn, "SELECT * FROM pages");
if(mysqli_num_rows($pages) > 0) {
    echo "<table border='1' cellpadding='10'><tr><th>ID</th><th>Slug</th><th>Page Name</th></tr>";
    while($p = mysqli_fetch_assoc($pages)) {
        echo "<tr><td>{$p['id']}</td><td>{$p['slug']}</td><td>{$p['page_name']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No pages found!</p>";
}

echo "<br><h3>2. All Services:</h3>";
$services = mysqli_query($conn, "SELECT * FROM service_list");
if(mysqli_num_rows($services) > 0) {
    echo "<table border='1' cellpadding='10'><tr><th>ID</th><th>Page Slug</th><th>Service Name</th></tr>";
    while($s = mysqli_fetch_assoc($services)) {
        echo "<tr><td>{$s['id']}</td><td>{$s['page_slug']}</td><td>{$s['service_name']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No services found!</p>";
}
?>
