<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageSlug = 'seo';
$fallback = [
    'page_title' => 'SEO',
    'meta_title' => 'SEO | RIO AD Agency',
    'meta_description' => 'SEO services by RIO AD Agency.',
    'intro_title' => 'Climb the Ranks. Dominate Search Results.<br> Be Seen by the Customers Who Are Actively Searching for You.',
    'intro_content' => '<p align="justify" class="portfolio-detail__text no-hyphen">What\'s the point of having a beautiful website if no one can find it? Search Engine Optimization (SEO) is the art and science of making your website more visible to search engines like Google. A strong SEO strategy places your business directly in front of potential customers at the exact moment they are looking for your products or services, driving valuable organic traffic that converts.</p>',
    'advantage' => 'We are a data driven agency. Our strategies are built on in depth analysis, transparent reporting, and proven white hat techniques that deliver sustainable, long term results without cutting corners.',
    'image' => 'assets/images/backgrounds/seo.jpg',
    'cards' => [
        ['service_name' => 'Why SEO is a Non-Negotiable for Your Business:', 'subtitle' => 'Crafting Digital Experiences That Convert', 'content' => '<ul><li><strong>Attract High-Intent Traffic: </strong>SEO connects you with users who are already interested in what you offer, leading to higher conversion rates.</li><li><strong>Build Long-Term Brand Authority:</strong>  Ranking high on Google establishes your brand as a credible and trusted leader in your industry.</li><li><strong>Achieve Sustainable Growth: </strong>Unlike paid ads, organic traffic from SEO is a long-term asset that continues to deliver value over time.</li><li><strong>Gain a Competitive Edge: </strong> Outranking your competitors on search engine results pages means you capture the majority of the market share.</li></ul>'],
        ['service_name' => 'Our Comprehensive SEO Services:', 'subtitle' => 'We take a holistic approach, covering all pillars of a successful SEO campaign.', 'content' => '<ul><li><strong>ON-Page SEO:</strong> We optimize your website\'s content, keywords, meta tags, and structure which make it a perfectly understandable and favourable to search engines.</li><li><strong>OFF-Page SEO:</strong> We build your website\'s authority and credibility through high-quality backlink building, local citations, and digital PR strategies.</li><li><strong>Technical SEO:</strong> We ensure your website\'s backend is flawless, focusing on site speed, mobile-friendliness, crawlability, and security.</li><li><strong>Keyword Research & Strategy:</strong> We identify the most valuable keywords your customers are using to find businesses like yours.</li><li><strong>Local SEO:</strong>  We optimize your online presence to attract local customers, putting you on the map—literally.</li></ul>']
    ]
];
include __DIR__ . '/includes/service-template.php';
