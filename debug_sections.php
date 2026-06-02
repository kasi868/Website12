<?php
require_once __DIR__ . '/config/db.php';

echo "<h2>Checking Database Sections...</h2>";

// Check sections for home page
echo "<h3>Sections for 'home' page:</h3>";
$result = mysqli_query($conn, "SELECT * FROM sections WHERE page_slug='home'");
if(mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Section Key</th><th>Sub Heading</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['section_key']}</td>";
        echo "<td>{$row['sub_heading']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No sections found for 'home' page!</p>";
}

// Check all pages
echo "<br><h3>All Pages:</h3>";
$pages = mysqli_query($conn, "SELECT * FROM pages");
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Slug</th><th>Page Name</th></tr>";
while($p = mysqli_fetch_assoc($pages)) {
    echo "<tr>";
    echo "<td>{$p['id']}</td>";
    echo "<td>{$p['slug']}</td>";
    echo "<td>{$p['page_name']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
