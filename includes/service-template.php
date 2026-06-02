<?php
require_once __DIR__ . '/layout.php';

$page = fetch_page($conn, $pageSlug);
$introSection = fetch_section($conn, $pageSlug, 'intro');
$advantageSection = fetch_section($conn, $pageSlug, 'advantage');
$cards = fetch_services($conn, $pageSlug);

$metaTitle = value($page, 'meta_title', $fallback['meta_title']);
$metaDescription = value($page, 'meta_description', $fallback['meta_description']);
$heroTitle = value($page, 'banner_title', $fallback['page_title']);
$bannerImage = media_url(value($page, 'banner_image', $fallback['image']));
$introTitle = value($introSection, 'sub_heading', $fallback['intro_title']);
$introContent = value($introSection, 'content', $fallback['intro_content']);
$advantage = value($advantageSection, 'content', $fallback['advantage']);
$image = media_url($fallback['image']);

if (count($cards) < 2) {
    $cards = $fallback['cards'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $extraStyles = '<style>
        .portfolio-detail__text, .portfolio-detail__text p, .portfolio-detail__right p { text-align: justify; }
        .services { padding: 50px 20px; background: #f9fbfc; font-family: "Segoe UI", sans-serif; }
        .service-grid { display: block; max-width: 920px; margin: auto; padding: 0 15px; }
        .service-card { background: transparent; padding: 0; border-radius: 0; text-align: left; box-shadow: none; }
        .service-card:not(:last-child) { margin-bottom: 48px; padding-bottom: 42px; border-bottom: 1px solid rgba(0,0,0,0.1); }
        .service-card i { font-size: 34px; color: #ff5722; margin-bottom: 0; display: inline-flex; vertical-align: middle; margin-right: 10px; }
        .service-card h3 { font-size: 28px; margin: 0 0 12px; color: #222; line-height: 1.2; }
        .service-card .tagline { font-size: 16px; color: #5f6368; margin-bottom: 22px; font-style: normal; line-height: 1.7; }
        .service-card h4 { font-size: 20px; margin: 1.8rem 0 0.9rem; color: #222; }
        .service-card > div, .service-card p { line-height: 1.8; color: #444; margin: 0; }
        .service-card ul { list-style: disc outside; padding-left: 24px; margin: 0 0 18px; text-align: justify; }
        .service-card ul li { margin-bottom: 10px; font-size: 16px; color: #444; position: static; padding-left: 0; }
        .service-card ul li::before { content: none; }
        .service-card h3, .service-card i { display: inline-block; vertical-align: middle; }
        .service-card h3 { margin-bottom: 16px; }
        .service-card .tagline, .service-card ul, .service-card h4 { max-width: 100%; }
        @media (max-width: 768px) {
            .service-grid { padding: 0 10px; }
            .service-card h3 { font-size: 24px; }
            .service-card h4 { font-size: 18px; }
        }
    </style>';
    include __DIR__ . '/head-common.php';
    ?>
</head>
<body>
    <div class="page-wrapper">
        <?php include __DIR__ . '/header.php'; ?>
        
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

        <section class="portfolio-detail">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-5">
                        <div class="portfolio-detail__right">
                            <h2 class="portfolio-detail__title"><?= render_content($introTitle) ?></h2>
                            <div class="portfolio-detail__text"><?= render_content($introContent) ?></div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-7">
                        <div class="portfolio-detail__left">
                            <div class="portfolio-detail__img-box">
                                <div class="portfolio-detail__img">
                                    <img src="<?= h($image) ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="services">
            <div class="container">
                <div class="service-grid">
                    <?php foreach ($cards as $card) { ?>
                        <div class="service-card">
                            <?php if ($icon = value($card, 'icon_class')): ?>
                                <i class="<?= h($icon) ?>" style="font-size: 45px; color: #ff5722; margin-bottom: 15px; display: block;"></i>
                            <?php endif; ?>
                            <h3><?= render_content(value($card, 'service_name')) ?></h3>
                            <p class="tagline"><?= render_content(value($card, 'subtitle')) ?></p>
                            <div><?= render_content(value($card, 'content')) ?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="portfolio-detail__right">
                            <h3 class="portfolio-detail__title">The RIO Ad Agency Advantage</h3>
                            <p class="portfolio-detail__text no-hyphen" align="justify"><?= render_content($advantage) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php render_site_footer(); ?>
    </div>
    <?php include __DIR__ . '/scripts.php'; ?>
</body>
</html>
