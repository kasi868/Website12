<?php
require_once __DIR__ . '/../config/bootstrap.php';

$sitePage = fetch_page($conn, 'site-settings');
$siteIdentity = fetch_section($conn, 'site-settings', 'identity');
$siteContact = fetch_section($conn, 'site-settings', 'contact');
$siteFooter = fetch_section($conn, 'site-settings', 'footer');
$siteSocialLinks = cms_get_social_links($conn);

function nav_items()
{
    global $conn;
    return cms_navigation_pages($conn, 'main');
}

function service_nav_items()
{
    global $conn;
    return cms_navigation_pages($conn, 'service');
}

function get_main_navigation() { return nav_items(); }
function get_service_navigation() { return service_nav_items(); }
