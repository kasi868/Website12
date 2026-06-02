<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
$page = fetch_page($conn, 'terms-conditions');
$contentSection = fetch_section($conn, 'terms-conditions', 'content');
$metaTitle = value($page, 'meta_title', 'Terms & Conditions | RIO AD Agency');
$metaDescription = value($page, 'meta_description', 'Terms and conditions for RIO AD Agency.');
$bannerTitle = value($page, 'banner_title', 'Terms & Conditions');
$bannerImage = media_url(value($page, 'banner_image', 'assets/images/backgrounds/main-slider.jpg'));
$fallback = '<p>By using the RIO AD Agency website, you agree to use the content and services for lawful purposes only. Project scope, pricing, delivery timelines, revisions, and asset ownership are governed by separate client agreements or proposals shared during the project process.</p><p>We reserve the right to update website content, service details, and policies at any time without prior notice.</p>';
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include __DIR__ . '/includes/head-common.php'; ?></head>
<body>
<div class="page-wrapper">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <!-- ========== Hero Area Start============= -->
    <div class="inner-page-banner" style="background-image: url(<?= h($bannerImage) ?>);">
        <div class="container">
            <div class="row justify-content-center align-items-center text-center">
                <div class="col-md-6">
                    <div class="banner-content">
                        <h1><?= h($bannerTitle) ?></h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb gap-3">
                                <li class="breadcrumb-item"><a href="<?= h(page_file('home')) ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?= h($bannerTitle) ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== Hero Area end============= -->
    
    <section class="blog-details"><div class="container"><div class="row"><div class="col-xl-12"><div class="blog-details__content"><?= render_content(value($contentSection, 'content', $fallback)) ?></div></div></div></div></section>
    <?php render_site_footer(); ?>
</div>
<?php include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
