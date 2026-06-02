<?php
require_once __DIR__ . '/config/bootstrap.php';

$requestSlug = isset($_GET['slug']) ? cms_slugify($_GET['slug']) : '';

if ($requestSlug === '') {
    $homePage = get_page_by_template('home', true, $conn);
    if ($homePage && value($homePage, 'slug') !== '') {
        $GLOBALS['cmsCurrentPage'] = $homePage;
        $GLOBALS['cmsCurrentSlug'] = value($homePage, 'slug');
        $GLOBALS['cmsTemplateName'] = 'home';
    }

    include __DIR__ . '/index.php';
    exit();
}

$page = get_page_by_slug($requestSlug, true, $conn);

if (!$page) {
    $redirect = cms_resolve_page_redirect($conn, $requestSlug);
    if ($redirect) {
        header('Location: ' . get_page_url_by_slug(value($redirect, 'new_slug'), false, $conn), true, 301);
        exit();
    }
}

if (!$page) {
    http_response_code(404);
    require_once __DIR__ . '/includes/layout.php';
    include __DIR__ . '/includes/header.php';
    echo '<div class="container pt-120 pb-120"><h1>Page Not Found</h1><p>The requested page could not be found.</p></div>';
    render_site_footer();
    exit();
}

$templateName = cms_page_template($page);
$templateFile = cms_template_file($templateName);
$templatePath = __DIR__ . '/' . $templateFile;

$GLOBALS['cmsCurrentPage'] = $page;
$GLOBALS['cmsCurrentSlug'] = value($page, 'slug');
$GLOBALS['cmsTemplateName'] = $templateName;
$_GET['slug'] = value($page, 'slug');
$canonicalUrl = get_page_url(value($page, 'id'), true, $conn);
$openGraphUrl = $canonicalUrl;

if (!is_file($templatePath)) {
    $templatePath = __DIR__ . '/dynamic-page.php';
}

include $templatePath;
