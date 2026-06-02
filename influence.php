<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageSlug = 'influence';
$fallback = [
    'page_title' => 'Influencer Marketing',
    'meta_title' => 'Influencer Marketing | RIO AD Agency',
    'meta_description' => 'Influencer marketing services by RIO AD Agency.',
    'intro_title' => 'Harness the Power of Authentic Voices.',
    'intro_content' => '<p align="justify" class="portfolio-detail__text">In an age of ad blockers and banner blindness, influencer marketing cuts through the noise. It is a powerful form of social proof that leverages the credibility and reach of trusted voices to introduce your brand to a highly engaged and relevant audience. We connect you with the right influencers to tell your brand\'s story authentically and drive meaningful results.</p>',
    'advantage' => 'We believe in partnerships, not just posts. We focus on building genuine, long-term relationships between brands and influencers to foster authenticity that leads to more impactful and believable campaigns.',
    'image' => 'assets/images/backgrounds/influence.jpg',
    'cards' => [
        ['service_name' => 'Why Influencer Marketing Works:', 'subtitle' => 'Trust-led promotion that feels organic', 'content' => '<ul><li><strong>Credibility:</strong> Recommendations feel more authentic than ads.</li><li><strong>Targeted Reach:</strong> Connect with communities that already care.</li><li><strong>Storytelling:</strong> Showcase your brand through relatable voices.</li></ul>'],
        ['service_name' => 'Our Influencer Marketing Services:', 'subtitle' => 'From shortlists to campaign execution', 'content' => '<ul><li><strong>Influencer Matching:</strong> Find creators who fit your audience and brand tone.</li><li><strong>Campaign Planning:</strong> Clear content direction and deliverables.</li><li><strong>Coordination:</strong> Communication, approvals, and publishing support.</li><li><strong>Performance Review:</strong> Measure reach, engagement, and campaign impact.</li></ul>']
    ]
];
include __DIR__ . '/includes/service-template.php';
