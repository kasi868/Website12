<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$pageSlug = isset($_GET['slug']) ? cms_slugify($_GET['slug']) : '';

if ($pageSlug === '') {
    header("Location: " . page_url('home'));
    exit();
}

$page = cms_fetch_page_by_slug($conn, $pageSlug, true);

if (!$page) {
    $redirect = cms_resolve_page_redirect($conn, $pageSlug);
    if ($redirect) {
        header("Location: " . get_page_url_by_slug(value($redirect, 'new_slug'), false, $conn), true, 301);
        exit();
    }
}

if (!$page) {
    http_response_code(404);
    include __DIR__ . '/includes/header.php';
    echo '<div class="container pt-120 pb-120"><h1>Page Not Found</h1><p>The requested page could not be found.</p></div>';
    render_site_footer();
    exit();
}

$metaTitle = value($page, 'meta_title', 'Page | RIO AD Agency');
$metaDescription = value($page, 'meta_description', '');
$canonicalUrl = get_page_url(value($page, 'id'), true, $conn);
$openGraphUrl = $canonicalUrl;

if (in_array(cms_slugify(value($page, 'page_name')), ['blog', 'blogs'], true) || in_array(strtolower(value($page, 'template_name')), ['blog', 'blogs'], true)) {
    include __DIR__ . '/blogs.php';
    exit();
}

$heroTitle = value($page, 'banner_title', value($page, 'page_name'));
$bannerImage = media_url(value($page, 'banner_image', 'assets/images/backgrounds/main-slider-1-1.jpg'));
$bannerHeadingTag = cms_heading_tag(value($page, 'banner_heading_tag', 'h1'), 'h1');
$serviceListHeadingTag = cms_heading_tag(value($page, 'service_list_heading_tag', 'h2'), 'h2');

$services = fetch_section($conn, $pageSlug, 'services');
$serviceList = fetch_services($conn, $pageSlug);
$pricingResult = mysqli_query($conn, "SELECT * FROM pricing_packages WHERE page_slug='" . mysqli_real_escape_string($conn, $pageSlug) . "' ORDER BY id ASC");
$faqs = fetch_faqs($conn, $pageSlug);
$dynamicSections = fetch_sections($conn, $pageSlug);
$socialLinks = cms_get_social_links($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/includes/head-common.php'; ?>
</head>
<body>
    <div class="page-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>
        
        <!--Page Header Start-->
        <section class="page-header" style="background-image: url(<?= h($bannerImage) ?>);">
            <div class="page-header-shape-1"></div>
            <div class="page-header-shape-2"></div>
            <div class="container">
                <div class="page-header__inner">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="<?= h(page_url('home')) ?>">Home</a></li>
                        <li><span>.</span></li>
                        <li><?= h($heroTitle) ?></li>
                    </ul>
                    <h2><?= h($heroTitle) ?></h2>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <div class="portfolio-details-pages pt-120 mb-50">
            <div class="container position-relative">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="row g-4 justify-content-center">
                            <?php 
                            $galleryKey = $pageSlug . "_gallery";
                            $gallery = fetch_images($conn, $pageSlug, $galleryKey);
                            if (count($gallery) > 0) { 
                                foreach ($gallery as $img) { 
                            ?>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <a href="<?= h(media_url('uploads/sections/' . $img['image'])) ?>" class="portfolio-img" data-fancybox="gallery">
                                        <img class="img-fluid" src="<?= h(media_url('uploads/sections/' . $img['image'])) ?>" alt="<?= h(cms_e(value($img, 'alt_text', value($img, 'title', value($page, 'page_name'))))) ?>">
                                    </a>
                                </div>
                            <?php 
                                } 
                            } 
                            ?>
                        </div>
                    </div>
                </div>

                <div class="cell">
                    <div class="social-container" style="margin-top: 20px; overflow-x: hidden;">
                        <div class="section-title" style="display: flex; justify-content: center; flex-wrap: wrap; gap: 35px;">
                            <a href="tel:+917288877718" target="_blank">
                                <i style="font-size: 35px; margin-top:10px;" class="fas fa-phone-alt"></i>
                            </a>
                            <?php foreach ($socialLinks as $socialLink) { ?>
                                <a href="<?= h($socialLink['url']) ?>" <?= (int) value($socialLink, 'open_new_tab', 1) === 1 ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                                    <i class="<?= h(cms_e(value($socialLink, 'icon_class', 'bx bx-link'))) ?>" style="font-size: 35px; margin-top:10px;"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <br><br><br>

                        <?php if (!empty(value($services, 'content'))) { ?>
                            <?= render_content(value($services, 'content')) ?>
                        <?php } ?>

                        <?php if (count($serviceList) > 0) { ?>
                            <div class="col-12 mt-5 mb-5">
                                <<?= $serviceListHeadingTag ?> class="text-center" style="color: #ff7f50; font-family: var(--font-cormorant); margin-bottom: 30px;">
                                    <?= h(value($page, 'page_name')) ?> Services
                                </<?= $serviceListHeadingTag ?>>
                                <div class="d-flex justify-content-center flex-wrap gap-4" style="font-size: 18px;">
                                    <?php foreach ($serviceList as $item) { ?>
                                        <?php $itemHeadingTag = cms_heading_tag(value($item, 'heading_tag', 'h3'), 'h3'); ?>
                                        <div class="service-item text-center" style="max-width: 300px;">
                                            <?php if (!empty($item['image'])) { ?>
                                                <div class="service-img mb-2">
                                                    <img src="<?= h(media_url('uploads/services/' . $item['image'])) ?>" alt="<?= h(cms_e(value($item, 'image_alt', value($item, 'service_name')))) ?>" class="img-fluid" style="border-radius: 5px; height: 150px; object-fit: cover;">
                                                </div>
                                            <?php } ?>
                                            <<?= $itemHeadingTag ?> class="service-title">
                                                <i class="fas fa-angle-double-right" style="font-size: 16px; vertical-align: middle;"></i> <?= h(cms_e($item['service_name'])) ?>
                                            </<?= $itemHeadingTag ?>>
                                            <?php if (!empty($item['content'])) { ?>
                                                <div class="service-desc mt-2" style="font-size: 14px;">
                                                    <?= render_content($item['content']) ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="container">
                    <?php if ($pricingResult && mysqli_num_rows($pricingResult) > 0) { ?>
                        <h2 align="center">Shoot Packages</h2>
                        <div class="card-container">
                            <?php while ($pkg = mysqli_fetch_assoc($pricingResult)) { ?>
                                <div class="card">
                                    <h3><?= h(cms_e($pkg['package_name'])) ?><br>&nbsp;</h3>
                                    <p align="center"><?= h(cms_e($pkg['product_count'])) ?></p>
                                    <p class="price" style="font-size: 40px;"><?= h(cms_e($pkg['price'])) ?></p>
                                    <p align="center"><?= h(cms_e($pkg['unit'])) ?></p>
                                    <ul>
                                        <?php foreach (explode("\n", value($pkg, 'features')) as $feature) {
                                            $feature = trim($feature);
                                            if ($feature !== '') {
                                                echo '<li>' . h(cms_e($feature)) . '</li>';
                                            }
                                        } ?>
                                    </ul>
                                    <a href="<?= h(cms_link_url(value($pkg, 'booking_link', '#'))) ?>" class="book-slot">Book Slot</a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if (count($faqs) > 0) { ?>
                            <h2 class="faq-title">FAQ</h2>
                            <ul class="faq-list">
                                <?php foreach ($faqs as $faq) { ?>
                                    <li>
                                        <span class="star">*</span>
                                        <?= h(cms_e($faq['question'])) ?>
                                        <?php if (!empty($faq['answer'])) { echo '<br>' . render_content($faq['answer']); } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </div>

                    <div class="col-lg-12">
                        <?php if (count($dynamicSections) > 0) { ?>
                            <?php foreach ($dynamicSections as $section) { ?>
                                <?php 
                                $sectionHeadingTag = cms_heading_tag(value($section, 'heading_tag', 'h2'), 'h2');
                                $imagePosition = value($section, 'image_position', 'right');
                                $layoutStyle = value($section, 'layout_style', 'default');
                                ?>
                                <div class="dynamic-section mb-5 <?= $layoutStyle === 'card' ? 'card p-4 shadow-sm' : ($layoutStyle === 'full-width' ? 'w-100' : '') ?>">
                                    <?php if (!empty($section['sub_heading'])) { ?>
                                        <<?= $sectionHeadingTag ?>><?= h(cms_e($section['sub_heading'])) ?></<?= $sectionHeadingTag ?>>
                                    <?php } ?>
                                    <?php if (!empty($section['image'])) { ?>
                                        <?php if ($imagePosition === 'top') { ?>
                                            <div class="section-image mb-3 text-center">
                                                <img src="<?= h(media_url('uploads/sections/' . $section['image'])) ?>" alt="<?= h(cms_e(value($section, 'image_alt', value($section, 'sub_heading')))) ?>" class="img-fluid" style="border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                            </div>
                                        <?php } ?>
                                        <div class="row align-items-center">
                                            <?php if ($imagePosition === 'left') { ?>
                                                <div class="col-md-4 mb-3 mb-md-0">
                                                    <div class="section-image">
                                                        <img src="<?= h(media_url('uploads/sections/' . $section['image'])) ?>" alt="<?= h(cms_e(value($section, 'image_alt', value($section, 'sub_heading')))) ?>" class="img-fluid" style="border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="section-content"><?= render_content($section['content']) ?></div>
                                                </div>
                                            <?php } elseif ($imagePosition === 'right') { ?>
                                                <div class="col-md-8">
                                                    <div class="section-content"><?= render_content($section['content']) ?></div>
                                                </div>
                                                <div class="col-md-4 mb-3 mb-md-0">
                                                    <div class="section-image">
                                                        <img src="<?= h(media_url('uploads/sections/' . $section['image'])) ?>" alt="<?= h(cms_e(value($section, 'image_alt', value($section, 'sub_heading')))) ?>" class="img-fluid" style="border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                                    </div>
                                                </div>
                                            <?php } elseif ($imagePosition === 'bottom') { ?>
                                                <div class="col-12">
                                                    <div class="section-content mb-3"><?= render_content($section['content']) ?></div>
                                                </div>
                                                <div class="col-12 text-center">
                                                    <div class="section-image">
                                                        <img src="<?= h(media_url('uploads/sections/' . $section['image'])) ?>" alt="<?= h(cms_e(value($section, 'image_alt', value($section, 'sub_heading')))) ?>" class="img-fluid" style="border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                                    </div>
                                                </div>
                                            <?php } else { // top ?>
                                                <div class="col-12">
                                                    <div class="section-content"><?= render_content($section['content']) ?></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($imagePosition === 'top' || empty($section['image'])) { ?>
                                            <div class="section-content"><?= render_content($section['content']) ?></div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="section-content"><?= render_content($section['content']) ?></div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php render_site_footer(); ?>
    </div>
    <?php include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
