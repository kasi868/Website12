<?php
require_once __DIR__ . '/config/db.php';

echo "<h2>Adding default service cards to database...</h2>";

// Define service cards for ALL service pages
$allServiceCards = [
    'web' => [
        ['Why Website Development is Crucial for Your Business:', 'Crafting Digital Experiences That Convert', '<ul><li><strong>Make a Powerful First Impression:</strong> 94% of first impressions relate to your site’s web design.</li><li><strong>Generate High Quality Leads Daily:</strong> A strategically designed website acts as a powerful tool for capturing leads and driving sales around the clock.</li><li><strong>Build Credibility and Trust:</strong> A professional, modern, and secure website signals that your business is trustworthy.</li><li><strong>Reach Customers Everywhere:</strong> Responsive design helps your site look and function perfectly on every device.</li></ul>'],
        ['Our Website Development Services:', 'Our end-to-end development process ensures your website is beautiful, functional, and built for growth.', '<ul><li><strong>Custom Web Design & UX/UI:</strong> Unique, intuitive designs centered around user experience.</li><li><strong>Responsive and Mobile-First Development:</strong> A flawless experience on any device.</li><li><strong>Content Management Systems:</strong> Easy-to-manage CMS platforms like WordPress.</li><li><strong>E-Commerce Solutions:</strong> Secure and scalable online stores that drive sales.</li><li><strong>Website Maintenance & Support:</strong> Ongoing support to keep your site secure and up to date.</li></ul>'],
    ],
    'seo' => [
        ['Why SEO is a Non-Negotiable for Your Business:', 'Crafting Digital Experiences That Convert', '<ul><li><strong>Attract High-Intent Traffic:</strong> SEO connects you with users who are already interested in what you offer, leading to higher conversion rates.</li><li><strong>Build Long-Term Brand Authority:</strong> Ranking high on Google establishes your brand as a credible and trusted leader in your industry.</li><li><strong>Achieve Sustainable Growth:</strong> Unlike paid ads, organic traffic from SEO is a long-term asset that continues to deliver value over time.</li><li><strong>Gain a Competitive Edge:</strong> Outranking your competitors on search engine results pages means you capture the majority of the market share.</li></ul>'],
        ['Our Comprehensive SEO Services:', 'We take a holistic approach, covering all pillars of a successful SEO campaign.', '<ul><li><strong>ON-Page SEO:</strong> We optimize your website\'s content, keywords, meta tags, and structure which make it a perfectly understandable and favourable to search engines.</li><li><strong>OFF-Page SEO:</strong> We build your website\'s authority and credibility through high-quality backlink building, local citations, and digital PR strategies.</li><li><strong>Technical SEO:</strong> We ensure your website\'s backend is flawless, focusing on site speed, mobile-friendliness, crawlability, and security.</li><li><strong>Keyword Research & Strategy:</strong> We identify the most valuable keywords your customers are using to find businesses like yours.</li><li><strong>Local SEO:</strong> We optimize your online presence to attract local customers, putting you on the map—literally.</li></ul>'],
    ],
    'social' => [
        ['Why Social Media Matters:', 'Build communities, not just follower counts', '<ul><li><strong>Brand Visibility:</strong> Stay present where your audience spends time.</li><li><strong>Engagement:</strong> Turn conversations into customer relationships.</li><li><strong>Trust:</strong> Consistent content helps your brand feel active and reliable.</li></ul>'],
        ['Our Social Media Services:', 'Creative planning backed by strategy', '<ul><li><strong>Content Calendars:</strong> Structured posting plans aligned to your goals.</li><li><strong>Creative Design:</strong> Posts, reels, and campaigns that match your brand.</li><li><strong>Community Management:</strong> Replies, engagement, and relationship building.</li><li><strong>Performance Tracking:</strong> Insights that help improve results month after month.</li></ul>'],
    ],
    'influence' => [
        ['Why Influencer Marketing Works:', 'Trust-led promotion that feels organic', '<ul><li><strong>Credibility:</strong> Recommendations feel more authentic than ads.</li><li><strong>Targeted Reach:</strong> Connect with communities that already care.</li><li><strong>Storytelling:</strong> Showcase your brand through relatable voices.</li></ul>'],
        ['Our Influencer Marketing Services:', 'From shortlists to campaign execution', '<ul><li><strong>Influencer Matching:</strong> Find creators who fit your audience and brand tone.</li><li><strong>Campaign Planning:</strong> Clear content direction and deliverables.</li><li><strong>Coordination:</strong> Communication, approvals, and publishing support.</li><li><strong>Performance Review:</strong> Measure reach, engagement, and campaign impact.</li></ul>'],
    ],
    'google' => [
        ['Why Google Ads Matters:', 'Reach buyers when intent is highest', '<ul><li><strong>Fast Visibility:</strong> Appear in front of customers searching now.</li><li><strong>Targeted Reach:</strong> Focus budgets by keyword, location, and audience.</li><li><strong>Scalable Results:</strong> Campaigns can grow with your business goals.</li></ul>'],
        ['Our Google Ads Services:', 'Performance-focused paid campaign management', '<ul><li><strong>Search Campaigns:</strong> Capture high-intent demand.</li><li><strong>Display & Remarketing:</strong> Stay visible across the web.</li><li><strong>Landing Page Guidance:</strong> Improve lead and conversion quality.</li><li><strong>Optimization & Reporting:</strong> Continuous improvements based on real data.</li></ul>'],
    ],
    'product' => [
        ['Why Product Visuals Matter:', 'Make every first impression count', '<ul><li><strong>Higher Conversions:</strong> Better visuals help customers decide faster.</li><li><strong>Brand Perception:</strong> Professional assets elevate your product value.</li><li><strong>Cross-Platform Use:</strong> Strong visuals work across marketplaces, websites, and social media.</li></ul>'],
        ['Our Product Content Services:', 'Photography and video designed for sales', '<ul><li><strong>Studio Product Shoots:</strong> Clean, polished product presentation.</li><li><strong>Lifestyle Visuals:</strong> Show products in real-world use.</li><li><strong>Short Product Videos:</strong> Motion-led storytelling for ads and reels.</li><li><strong>Catalog and Campaign Assets:</strong> Content ready for ecommerce and promotions.</li></ul>'],
    ],
    'industrial' => [
        ['Why Industrial Storytelling Matters:', 'Show your capabilities with clarity and confidence', '<ul><li><strong>Build Trust:</strong> Help clients see your expertise and standards.</li><li><strong>Show Scale:</strong> Present operations in a powerful, credible way.</li><li><strong>Support Sales & Hiring:</strong> Use the same assets across business development and recruitment.</li></ul>'],
        ['Our Industrial Documentary Services:', 'From concept to final production', '<ul><li><strong>Concept Development:</strong> Story structure tailored to your business goals.</li><li><strong>On-Site Production:</strong> Professional filming of teams, equipment, and process.</li><li><strong>Editing & Motion Graphics:</strong> Clear, polished storytelling for technical subjects.</li><li><strong>Multi-Use Delivery:</strong> Versions for presentations, websites, and social channels.</li></ul>'],
    ],
    'brand' => [
        ['Why Brand Identity Matters:', 'A clear identity builds recognition and trust', '<ul><li><strong>Consistency:</strong> A strong identity keeps every touchpoint aligned.</li><li><strong>Recognition:</strong> Memorable visuals help your brand stand out.</li><li><strong>Trust:</strong> A polished identity signals professionalism and confidence.</li></ul>'],
        ['Our Branding Services:', 'Foundational systems for long-term brand growth', '<ul><li><strong>Logo Design:</strong> Distinct visual marks tailored to your brand.</li><li><strong>Brand Colors & Typography:</strong> Cohesive systems built for recognition.</li><li><strong>Brand Guidelines:</strong> Clear usage standards for consistency.</li><li><strong>Visual Direction:</strong> A brand look that feels intentional and scalable.</li></ul>'],
    ],
    'graphic' => [
        ['Why Great Design Matters:', 'Clear visuals shape how your brand is perceived', '<ul><li><strong>Communication:</strong> Good design makes messages easier to understand.</li><li><strong>Attention:</strong> Strong visuals help campaigns stand out quickly.</li><li><strong>Consistency:</strong> Design systems reinforce your brand identity everywhere.</li></ul>'],
        ['Our Graphic Design Services:', 'Creative assets built for both beauty and function', '<ul><li><strong>Social Media Creatives:</strong> Scroll-stopping posts and campaign visuals.</li><li><strong>Print & Marketing Collateral:</strong> Brochures, flyers, and branded materials.</li><li><strong>Ad Creatives:</strong> Designs tailored for digital performance.</li><li><strong>Brand Support Assets:</strong> Templates and visuals aligned to your identity.</li></ul>'],
    ],
];

// Add service cards
foreach ($allServiceCards as $pageSlug => $cards) {
    // Check if cards already exist
    $check = mysqli_query($conn, "SELECT * FROM service_list WHERE page_slug='$pageSlug'");
    if (mysqli_num_rows($check) > 0) {
        echo "<p style='color:orange;'>Service cards already exist for '$pageSlug' - skipping.</p>";
        continue;
    }
    
    foreach ($cards as $pos => $card) {
        $name = mysqli_real_escape_string($conn, $card[0]);
        $subtitle = mysqli_real_escape_string($conn, $card[1]);
        $content = mysqli_real_escape_string($conn, $card[2]);
        $sql = "INSERT INTO service_list (page_slug, service_name, subtitle, content, position) VALUES 
                ('$pageSlug', '$name', '$subtitle', '$content', $pos)";
        mysqli_query($conn, $sql);
    }
    echo "<p style='color:green;'>✓ Added service cards for '$pageSlug'!</p>";
}

echo "<br><p style='font-size:18px;'><b>Done! Now go to admin/services.php, select ANY service page, and you'll see these cards to edit!</b></p>";
?>
