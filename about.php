<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$page = fetch_page($conn, 'about');
$intro = fetch_section($conn, 'about', 'intro');
$mission = fetch_section($conn, 'about', 'mission');
$vision = fetch_section($conn, 'about', 'vision');
$why = fetch_section($conn, 'about', 'why_choose_us_intro');
$faqs = fetch_faqs($conn, 'about');

$metaTitle = value($page, 'meta_title', 'About Us | RIO AD Agency');
$metaDescription = value($page, 'meta_description', 'Learn more about RIO AD Agency in Hyderabad.');
$extraStyles = '<style>
.mission-vision { padding: 60px 20px; background: #f9fbfc; }
.mission-vision .container { display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; max-width: 1100px; margin: auto; }
.mv-box { flex: 1 1 45%; background: #fff; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform .3s ease, box-shadow .3s ease; }
.mv-box:hover { transform: translateY(-8px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.mv-icon { font-size: 40px; color: #ff5722; margin-bottom: 15px; }
@media (max-width: 768px) { .mv-box { flex: 1 1 100%; } }
</style>';
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include __DIR__ . '/includes/head-common.php'; ?></head>
<body>
<div class="page-wrapper">
   
    <?php include __DIR__ . '/includes/header.php'; ?>
    <?php 
    $bannerTitle = value($page, 'banner_title', 'About us');
    $bannerImage = media_url(value($page, 'banner_image', 'assets/images/backgrounds/main-slider-1-10.jpg'));
    ?>
    <section class="page-header" style="background-image: url(<?= h($bannerImage) ?>);">
        <div class="page-header-shape-1"></div>
        <div class="page-header-shape-2"></div>
        <div class="container">
            <div class="page-header__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="<?= h(get_home_url()) ?>">Home</a></li>
                    <li><span>.</span></li>
                    <li><?= h($bannerTitle) ?></li>
                </ul>
                <h2><?= h($bannerTitle) ?></h2>
            </div>
        </div>
    </section>

    <section class="reasons">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-6">
                    <div class="reasons__left">
                        <div class="reasons__img">
                            <img src="<?= h(media_url(value($intro, 'image', 'assets/images/resources/1_ABOUT US.jpg'))) ?>" alt="">
                            <div class="reasons__img-shape-1"></div>
                            <div class="reasons__img-shape-2"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6">
                    <div class="reasons__right">
                        <div class="section-title text-left">We are more than just an ad agency
                            <h2 class="section-title__title"><?= render_content(value($intro, 'sub_heading', 'About Us')) ?></h2>
                        </div>
                        <p align="justify" class="no-hyphen"><?= render_content(value($intro, 'content')) ?></p>
                    </div>
                </div>
                <div style="padding-top: 15px"><p align="justify" class="no-hyphen"><?= render_content(value($intro, 'extra_text')) ?></p></div>
            </div>
        </div>
    </section>

    <section class="mission-vision">
        <div class="container">
            <div class="mv-box">
                <div class="mv-icon"><i class="fas fa-bullseye"></i></div>
                <h3><?= render_content(value($mission, 'sub_heading', 'Our Mission')) ?></h3>
                <p><?= render_content(value($mission, 'content')) ?></p>
            </div>
            <div class="mv-box">
                <div class="mv-icon"><i class="fas fa-eye"></i></div>
                <h3><?= render_content(value($vision, 'sub_heading', 'Our Vision')) ?></h3>
                <p><?= render_content(value($vision, 'content')) ?></p>
            </div>
        </div>
    </section>

    <section class="we-change">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="we-change__left-faqs">
                        <div class="section-title text-left">
                            <h2 class="section-title__title"><?= render_content(value($why, 'sub_heading', 'Why Choose Us')) ?></h2>
                        </div>
                        <div class="we-change__faqs">
                            <div class="accrodion-grp" data-grp-name="faq-one-accrodion">
                                <div class="accrodion active">
                                    <div class="accrodion-content"><div class="inner"><p align="justify" class="web-solutions__content-desc no-hyphen" style="font-size:15px;"><?= render_content(value($why, 'content')) ?></p></div></div>
                                </div>
                                <?php foreach ($faqs as $faq) { ?>
                                    <div class="accrodion">
                                        <div class="accrodion-title"><h4><?= render_content($faq['question']) ?></h4></div>
                                        <div class="accrodion-content"><div class="inner"><p align="justify" class="web-solutions__content-desc no-hyphen" style="font-size:16px;"><?= render_content($faq['answer']) ?></p></div></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="we-change__right">
                        <div class="we-change__right-img">
                            <img src="<?= h(media_url(value($why, 'image', 'assets/images/resources/we-change-right-img-10.jpg'))) ?>" alt="">
                            <div class="we-change__agency">
                                <div class="we-change__agency-icon"><span class="icon-development"></span></div>
                                <div class="we-change__agency-text"><h3>Our agency is one of the most <br> successful agency.</h3></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php render_site_footer(); ?>
</div>
<?php include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
