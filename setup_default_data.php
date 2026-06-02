<?php
require_once __DIR__ . '/config/db.php';

echo "<h2>Setting up default admin data...</h2>";

// 1. Check if pages exist, if not add them
$pagesCheck = mysqli_query($conn, "SELECT * FROM pages");
if (mysqli_num_rows($pagesCheck) == 0) {
    $defaultPages = [
        ['home', 'Home'],
        ['about', 'About Us'],
        ['contact', 'Contact Us'],
        ['blog', 'Blogs'],
        ['portfolio', 'Portfolio'],
        ['terms-conditions', 'Terms & Conditions'],
        ['privacy', 'Privacy Policy'],
        ['web', 'Website Development'],
        ['seo', 'SEO'],
        ['social', 'Social Media Marketing'],
        ['influence', 'Influencer Marketing'],
        ['google', 'Google AdWords'],
        ['product', 'Product Photo & Videography'],
        ['industrial', 'Industrial Documentaries'],
        ['brand', 'Brand Identity & Logos'],
        ['graphic', 'Graphic Design'],
    ];
    
    foreach ($defaultPages as $page) {
        $slug = $page[0];
        $name = $page[1];
        $sql = "INSERT INTO pages (slug, page_name, meta_title, meta_description) VALUES 
                ('$slug', '$name', '$name | RIO AD Agency', '$name services by RIO AD Agency')";
        mysqli_query($conn, $sql);
    }
    echo "<p style='color:green;'>✓ Added default pages!</p>";
} else {
    echo "<p style='color:orange;'>Pages already exist!</p>";
}

// 2. Check if home services exist, if not add them
$homeServicesCheck = mysqli_query($conn, "SELECT * FROM service_list WHERE page_slug='home'");
if (mysqli_num_rows($homeServicesCheck) == 0) {
    $defaultServices = [
        ['Website Development', 'Custom websites that convert', 'We build high-performance websites tailored to your business goals, designed to attract visitors and turn them into customers.', 'fa fa-laptop-code', 'web.html', 0],
        ['SEO', 'Climb the search ranks', 'Our SEO strategies get your business found by the customers who are actively searching for what you offer.', 'fa fa-search', 'seo.html', 1],
        ['Social Media Marketing', 'Engage your audience', 'We create compelling social media campaigns that build communities and drive meaningful engagement with your brand.', 'fa fa-users', 'social.html', 2],
        ['Influencer Marketing', 'Amplify your reach', 'Connect with trusted voices in your industry to build authentic relationships with new audiences.', 'fa fa-bullhorn', 'influence.html', 3],
        ['Google Ads', 'Instant visibility', 'Precision-targeted paid campaigns that put your business in front of high-intent customers right when they are searching.', 'fa fa-adversal', 'google.html', 4],
        ['Brand Identity', 'Stand out from the crowd', 'We craft memorable brand identities that communicate your values and make a lasting impression.', 'fa fa-paint-brush', 'brand.html', 5],
    ];
    
    foreach ($defaultServices as $service) {
        $name = mysqli_real_escape_string($conn, $service[0]);
        $subtitle = mysqli_real_escape_string($conn, $service[1]);
        $content = mysqli_real_escape_string($conn, $service[2]);
        $icon = $service[3];
        $link = $service[4];
        $pos = $service[5];
        
        $sql = "INSERT INTO service_list (page_slug, service_name, subtitle, content, icon_class, link, position) VALUES 
                ('home', '$name', '$subtitle', '$content', '$icon', '$link', $pos)";
        mysqli_query($conn, $sql);
    }
    echo "<p style='color:green;'>✓ Added default home page services!</p>";
} else {
    echo "<p style='color:orange;'>Home services already exist!</p>";
}

echo "<br><p style='font-size:18px;'><b>Done! Now go to admin/services.php, select 'Home' from the dropdown, and you'll see your services!</b></p>";
?>
