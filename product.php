<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageSlug = 'product';
$fallback = [
    'page_title' => 'Product Photo & Videography',
    'meta_title' => 'Product Photo & Videography | RIO AD Agency',
    'meta_description' => 'Product photography and videography by RIO AD Agency.',
    'intro_title' => 'Showcase Your Products in Their Best Light.',
    'intro_content' => '<p align="justify" class="portfolio-detail__text no-hyphen">In e-commerce and digital marketing, your product visuals are its first handshake, sales pitch, and closing argument all in one. Professional, high-quality photography and videography are no longer a luxury; they are essential. We create crisp, compelling visual assets that stop customers in their tracks, build desire, and dramatically increase conversion rates.</p>',
    'advantage' => 'Our team combines artistic vision with a deep understanding of marketing. We do not just take pictures; we create strategic visual assets designed to meet specific business goals.',
    'image' => 'assets/images/backgrounds/product.jpg',
    'cards' => [
        ['service_name' => 'Why Product Visuals Matter:', 'subtitle' => 'Make every first impression count', 'content' => '<ul><li><strong>Higher Conversions:</strong> Better visuals help customers decide faster.</li><li><strong>Brand Perception:</strong> Professional assets elevate your product value.</li><li><strong>Cross-Platform Use:</strong> Strong visuals work across marketplaces, websites, and social media.</li></ul>'],
        ['service_name' => 'Our Product Content Services:', 'subtitle' => 'Photography and video designed for sales', 'content' => '<ul><li><strong>Studio Product Shoots:</strong> Clean, polished product presentation.</li><li><strong>Lifestyle Visuals:</strong> Show products in real-world use.</li><li><strong>Short Product Videos:</strong> Motion-led storytelling for ads and reels.</li><li><strong>Catalog and Campaign Assets:</strong> Content ready for ecommerce and promotions.</li></ul>']
    ]
];
include __DIR__ . '/includes/service-template.php';
