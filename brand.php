<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageSlug = 'brand';
$fallback = [
    'page_title' => 'Brand Identity & Logos',
    'meta_title' => 'Brand Identity & Logos | RIO AD Agency',
    'meta_description' => 'Brand identity and logo design by RIO AD Agency.',
    'intro_title' => 'Your Brand’s First Impression, Perfected.',
    'intro_content' => '<p align="justify" class="portfolio-detail__text no-hyphen">Your brand identity is the visible soul of your business. It is the combination of your logo, colours, fonts, and messaging that creates a distinct and memorable impression in the minds of your customers. We go beyond simple logo design to build a cohesive and strategic brand identity that communicates your values and differentiates you from the competition.</p>',
    'advantage' => 'Our process is collaborative and research driven. We take the time to understand your business, your audience, and your vision to create an identity that is strategically sound and built for long-term growth.',
    'image' => 'assets/images/backgrounds/brand.jpg',
    'cards' => [
        ['service_name' => 'Why Brand Identity Matters:', 'subtitle' => 'A clear identity builds recognition and trust', 'content' => '<ul><li><strong>Consistency:</strong> A strong identity keeps every touchpoint aligned.</li><li><strong>Recognition:</strong> Memorable visuals help your brand stand out.</li><li><strong>Trust:</strong> A polished identity signals professionalism and confidence.</li></ul>'],
        ['service_name' => 'Our Branding Services:', 'subtitle' => 'Foundational systems for long-term brand growth', 'content' => '<ul><li><strong>Logo Design:</strong> Distinct visual marks tailored to your brand.</li><li><strong>Brand Colors & Typography:</strong> Cohesive systems built for recognition.</li><li><strong>Brand Guidelines:</strong> Clear usage standards for consistency.</li><li><strong>Visual Direction:</strong> A brand look that feels intentional and scalable.</li></ul>']
    ]
];
include __DIR__ . '/includes/service-template.php';
