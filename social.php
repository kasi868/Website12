<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageSlug = 'social';
$fallback = [
    'page_title' => 'Social Media Marketing',
    'meta_title' => 'Social Media Marketing | RIO AD Agency',
    'meta_description' => 'Social media management and marketing services by RIO AD Agency.',
    'intro_title' => 'Engage, Influence, and Grow Your Brand. We Turn Your Social Media Followers into Customers.',
    'intro_content' => '<p align="justify" class="portfolio-detail__text no-hyphen">Social media is where conversations happen, communities are built, and brands come to life. Effective social media management is not just about posting content; it is about building a genuine connection with your audience. We create and execute dynamic social media strategies that elevate your brand voice, foster engagement, and turn passive followers into active, loyal customers.</p>',
    'advantage' => 'We focus on creating content that drives meaningful conversations, not just one-way messaging. Our approach is rooted in understanding your audience deeply and delivering what they want to see, leading to authentic growth and measurable ROI.',
    'image' => 'assets/images/backgrounds/social.jpg',
    'cards' => [
        ['service_name' => 'Why Social Media Matters:', 'subtitle' => 'Build communities, not just follower counts', 'content' => '<ul><li><strong>Brand Visibility:</strong> Stay present where your audience spends time.</li><li><strong>Engagement:</strong> Turn conversations into customer relationships.</li><li><strong>Trust:</strong> Consistent content helps your brand feel active and reliable.</li></ul>'],
        ['service_name' => 'Our Social Media Services:', 'subtitle' => 'Creative planning backed by strategy', 'content' => '<ul><li><strong>Content Calendars:</strong> Structured posting plans aligned to your goals.</li><li><strong>Creative Design:</strong> Posts, reels, and campaigns that match your brand.</li><li><strong>Community Management:</strong> Replies, engagement, and relationship building.</li><li><strong>Performance Tracking:</strong> Insights that help improve results month after month.</li></ul>']
    ]
];
include __DIR__ . '/includes/service-template.php';
