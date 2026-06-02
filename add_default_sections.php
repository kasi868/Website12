<?php
require_once __DIR__ . '/config/db.php';

echo "<h2>Adding default home page sections...</h2>";

// Check if hero section exists
$heroCheck = mysqli_query($conn, "SELECT * FROM sections WHERE page_slug='home' AND section_key='hero'");
if (mysqli_num_rows($heroCheck) == 0) {
    $sql0 = "INSERT INTO sections (page_slug, section_key, image, button_link) VALUES 
            ('home', 'hero', 'assets/desktop.mp4', 'assets/mobile.mp4')";
    mysqli_query($conn, $sql0);
    echo "<p style='color: green;'>✓ Added 'hero' section</p>";
} else {
    echo "<p style='color: orange;'>'hero' section already exists!</p>";
}

// Check if what_we_do and why_choose_us exist
$check = mysqli_query($conn, "SELECT * FROM sections WHERE page_slug='home' AND section_key IN ('what_we_do', 'why_choose_us')");
if (mysqli_num_rows($check) < 2) {
    // Insert What We Do section
    $whatWeDoContent = '<p align="justify">RIO Ad Agency is your fully integrated growth partner, the first to combine bold creative approach with ruthless online implementation. Your brand We carefully design each campaign to act as an engine of great growth. Through the creation of holistic and interactive experiences, we not only reach your audience but we engage them, taking a firm hold on their attention at every point of contact.<br><br>This is about converting passive consumers into vocal advocates who amplify your brand and fortify your market dominance. Every strategy we craft, every campaign we launch, is aimed at building unwavering loyalty and creating a ripple effect of influence. After all, our singular mission is to architect the ascendancy of your brand through a unified plan that delivers a resounding, measurable impact.</p>';
    
    $whatWeDoCheck = mysqli_query($conn, "SELECT * FROM sections WHERE page_slug='home' AND section_key='what_we_do'");
    if (mysqli_num_rows($whatWeDoCheck) == 0) {
        $sql1 = "INSERT INTO sections (page_slug, section_key, sub_heading, content, image) VALUES 
                ('home', 'what_we_do', 'What We Do', '".mysqli_real_escape_string($conn, $whatWeDoContent)."', 'assets/images/resources/1_WHAT WE DOO.jpg')";
        mysqli_query($conn, $sql1);
        echo "<p style='color: green;'>✓ Added 'what_we_do' section</p>";
    } else {
        echo "<p style='color: orange;'>'what_we_do' section already exists!</p>";
    }

    // Insert Why Choose Us section
    $whyChooseUsContent = '<p class="no-hyphen" align="justify" style="color:#fff;">At RIO AD Agency, we stand out for our ability to merge creativity, strategy, and innovation into and every project we take on. We don’t just create ads we craft experiences that connect with his audiences and deliver measurable results.</p>';
    
    $whyChooseUsExtra = '<li>
        <div class="text">
            <p><b>Strategic Approach : </b></p>
            <p>Every idea is backed by research, insights, and a clear path to results.</p>
        </div>
        <div class="icon"><span class="icon-tick"></span></div>
    </li>
    <li>
        <div class="text">
            <p><b>End-to-End Solutions : </b></p>
            <p>From branding to digital campaigns, we cover every aspect of advertising.</p>
        </div>
        <div class="icon"><span class="icon-tick"></span></div>
    </li>
    <li>
        <div class="text">
            <p><b>Client-Centric Focus : </b></p>
            <p>We listen, adapt, and deliver solutions tailored to your brand’s unique needs.</p>
        </div>
        <div class="icon"><span class="icon-tick"></span></div>
    </li>';
    
    $whyChooseUsCheck = mysqli_query($conn, "SELECT * FROM sections WHERE page_slug='home' AND section_key='why_choose_us'");
    if (mysqli_num_rows($whyChooseUsCheck) == 0) {
        $sql2 = "INSERT INTO sections (page_slug, section_key, sub_heading, content, extra_text, button_link, image) VALUES 
                ('home', 'why_choose_us', 'Why Choose Us', '".mysqli_real_escape_string($conn, $whyChooseUsContent)."', '".mysqli_real_escape_string($conn, $whyChooseUsExtra)."', 'assets/images/resources/reasons-one-img-02.jpg', 'assets/images/backgrounds/core-features-bg.jpg')";
        mysqli_query($conn, $sql2);
        echo "<p style='color: green;'>✓ Added 'why_choose_us' section</p>";
    } else {
        echo "<p style='color: orange;'>'why_choose_us' section already exists!</p>";
    }
} else {
    echo "<p style='color: orange;'>Sections already exist! Skipping insert.</p>";
}

echo "<br><p style='font-size: 18px;'><b>Done! Now go to admin/sections.php and select 'Home' page to edit these sections!</b></p>";
?>
