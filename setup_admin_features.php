<?php
require_once __DIR__ . '/config/db.php';

echo "<h2>Setting up admin features...</h2>";

// Add default social links if they don't exist
$socialLinks = [
    ['label' => 'YouTube', 'icon_class' => 'fab fa-youtube', 'url' => 'https://youtube.com/', 'sort_order' => 1],
    ['label' => 'Facebook', 'icon_class' => 'fab fa-facebook', 'url' => 'https://facebook.com/', 'sort_order' => 2],
    ['label' => 'Pinterest', 'icon_class' => 'fab fa-pinterest', 'url' => 'https://pinterest.com/', 'sort_order' => 3],
    ['label' => 'Instagram', 'icon_class' => 'fab fa-instagram', 'url' => 'https://instagram.com/', 'sort_order' => 4],
];

foreach ($socialLinks as $link) {
    $label = mysqli_real_escape_string($conn, $link['label']);
    $iconClass = mysqli_real_escape_string($conn, $link['icon_class']);
    $url = mysqli_real_escape_string($conn, $link['url']);
    $sortOrder = (int) $link['sort_order'];
    
    $check = mysqli_query($conn, "SELECT id FROM social_links WHERE label='$label'");
    if (mysqli_num_rows($check) === 0) {
        mysqli_query($conn, "INSERT INTO social_links (label, icon_class, url, sort_order) 
                VALUES ('$label', '$iconClass', '$url', $sortOrder)");
        echo "<p style='color:green;'>✓ Added social link: $label</p>";
    } else {
        echo "<p style='color:orange;'>- Social link already exists: $label</p>";
    }
}

// Generate initial sitemap.xml and robots.txt
cms_generate_sitemap($conn);
cms_write_robots_txt($conn);

echo "<br><p style='font-size:18px; color:blue;'><b>✓ All features set up successfully!</b></p>";
echo "<p>Now you can use the admin panel!</p>";
?>
