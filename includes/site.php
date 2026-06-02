<?php
require_once __DIR__ . '/../config/bootstrap.php';

$sitePage = fetch_page($conn, 'site-settings');
$siteIdentity = fetch_section($conn, 'site-settings', 'identity');
$siteContact = fetch_section($conn, 'site-settings', 'contact');
$siteFooter = fetch_section($conn, 'site-settings', 'footer');
$siteSocialLinks = cms_get_social_links($conn);

function nav_items()
{
    return [
        ['slug' => 'home', 'label' => 'Home'],
        ['slug' => 'about', 'label' => 'About Us'],
        ['slug' => 'portfolio', 'label' => 'Portfolio'],
        ['slug' => 'blog', 'label' => 'Blogs'],
        ['slug' => 'contact', 'label' => 'Contact Us'],
    ];
}

function service_nav_items()
{
    return [
        ['slug' => 'web', 'label' => 'Website Development'],
        ['slug' => 'seo', 'label' => 'SEO (Search Engine Optimization)'],
        ['slug' => 'social', 'label' => 'Social Media Management'],
        ['slug' => 'influence', 'label' => 'Influencer Marketing'],
        ['slug' => 'google', 'label' => 'Google AdWords'],
        ['slug' => 'product', 'label' => 'Product Photo & Videography'],
        ['slug' => 'industrial', 'label' => 'Industrial Documentaries'],
        ['slug' => 'brand', 'label' => 'Brand Identity & Logos'],
        ['slug' => 'portfolio', 'label' => 'Portfolio'],
    ];
}
