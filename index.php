<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$pageSlug = 'home';
$page = fetch_page($conn, 'home');
$about = fetch_section($conn, 'home', 'about_intro');
$services = fetch_services($conn, 'home');
$blogs = fetch_blogs($conn, 6);
$portfolioImages = fetch_images($conn, 'home', 'home_portfolio');

// Fetch new sections for home page
$hero = fetch_section($conn, 'home', 'hero');
$whatWeDo = fetch_section($conn, 'home', 'what_we_do');
$whyChooseUs = fetch_section($conn, 'home', 'why_choose_us');

if (!$portfolioImages) {
    $portfolioImages = [];
    for ($i = 1; $i <= 15; $i++) {
        $portfolioImages[] = ['image' => 'assets/images/resources/c' . $i . '.jpg'];
    }
}

$metaTitle = value($page, 'meta_title', 'Best Branding & Advertising Agency in Hyderabad | RIO AD Agency');
$metaDescription = value($page, 'meta_description', 'RIO is Hyderabad’s leading branding and advertising agency, delivering powerful creative strategies, digital marketing solutions, and campaigns that build brands and drive growth across India.');
$extraStyles = '<style>
.video-layer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1; }
.video-layer video { position: absolute; top: 70%; left: 50%; width: 100%; height: auto; object-fit: cover; transform: translate(-50%, -50%); }
#content-desktop { display: block; } #content-mobile { display: none; }
@media screen and (max-width: 991px) { 
    #content-desktop { display: none; } #content-mobile { display: block; } 
    .image-layer, .swiper-slide { position: relative; width: 100vw; height: 150vh; margin: 0; padding: 0; overflow: hidden; } 
    .video-layer video { width: 100%; height: auto; object-fit: contain; top: 0; left: 0; transform: none; } 
    .main-header-two { padding: 15px 0; }
    .main-menu-two-wrapper__logo { position: relative; z-index: 10; }
}

.portfolio-one__single { padding: 10px; }
.portfolio-one__img img { width: 100%; border-radius: 12px; transition: transform .3s ease; }
.portfolio-one__img img:hover { transform: scale(1.03); }
</style>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/includes/head-common.php'; ?>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-3WE6KZ6F4K"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-3WE6KZ6F4K');
</script>
</head>
<body>
    <div class="page-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <div id="content-desktop">
            <video autoplay muted loop playsinline width="100%" height="auto">
                <source src="<?= h(media_url(value($hero, 'image', 'assets/desktop.mp4'))) ?>" type="video/mp4">
            </video>
        </div>
        <div class="video-layer" id="content-mobile">
            <video autoplay muted loop playsinline>
                <source src="<?= h(media_url(value($hero, 'button_link', 'assets/mobile.mp4'))) ?>" type="video/mp4">
            </video>
        </div>

        <br><br>
        <section class="build-business">
            <div id="content-mobile"><br><br><br><br><br><br><br><br><br><br><br><br></div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="build-business__left">
                            <div class="build-business__left-bg"></div>
                            <div class="build-business__img wow fadeInLeft" data-wow-duration="1500ms">
                                <img src="<?= h(media_url(value($about, 'image', 'assets/images/resources/2_ABOUT US.jpg'))) ?>" alt="">
                                <div class="build-business__img-shape-1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="build-business__right">
                            <div class="section-title text-left">
                                <span class="section-title__tagline">Welcome To RIO AD Agency</span>
                                <h2 class="section-title__title"><?= render_content(value($about, 'sub_heading', 'About Us')) ?></h2>
                            </div>
                            <div>
                                <p align="justify"><?= render_content(value($about, 'content')) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="areas-of-practice">
            <div class="container">
                <div class="areas-of-practice__top">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="areas-of-practice__top-left">
                                <div class="section-title text-left">
                                    <h2 class="section-title__title" align="center">Our Services</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="areas-of-practice__bottom">
                    <div class="row">
                        <?php foreach ($services as $service) { ?>
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="areas-of-practice__single">
                                    <div class="areas-of-practice__icon-box">
                                        <div class="areas-of-practice__icon">
                                            <span class="<?= h(value($service, 'icon_class', 'fa fa-star')) ?>"></span>
                                        </div>
                                        <div class="areas-of-practice__title">
                                            <h3><a href="<?= h(value($service, 'link', '#')) ?>"><?= render_content(value($service, 'service_name')) ?></a></h3>
                                        </div>
                                    </div>
                                    <span class="areas-of-practice__text no-hyphen" style="font-size: 15px;"><?= render_content(value($service, 'content')) ?></span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- What We Do -->
        <section class="web-solutions">
            <div class="web-solutions-bg" style="background-image: url(<?= h(media_url(value($whatWeDo, 'image', 'assets/images/backgrounds/web-solutions-bg.jpg'))) ?>)">
            </div>
            <div class="container">
                <div class="section-title text-center">
                    <h2 class="section-title__title"><?= render_content(value($whatWeDo, 'sub_heading', 'What We Do')) ?></h2>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="web-solutions__box tabs-box">
                            <div class="tabs-content">
                                <div class="tab active-tab" id="financial">
                                    <div class="web-solutions__content">
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="web-solutions__content-img">
                                                    <img src="<?= h(media_url(value($whatWeDo, 'image', 'assets/images/resources/1_WHAT WE DOO.jpg'))) ?>" alt="">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="web-solutions__content-right">
                                                    <div align="justify"><?= render_content(value($whatWeDo, 'content')) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="core-features">
            <div class="core-features-bg" style="background-image: url(<?= h(media_url(value($whyChooseUs, 'image', 'assets/images/backgrounds/core-features-bg.jpg'))) ?>)">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        <div class="core-features__left">
                            <div class="section-title text-left">
                                <h2 class="section-title__title"><?= render_content(value($whyChooseUs, 'sub_heading', 'Why Choose Us')) ?></h2>
                            </div>
                            <div class="core-features__left-bottom">
                                <div align="justify" style="color:#fff;"><?= render_content(value($whyChooseUs, 'content')) ?></div>
                            </div>
                            <br>
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <img style="border-radius: 10px;" src="<?= h(media_url(value($whyChooseUs, 'button_link', 'assets/images/resources/reasons-one-img-02.jpg'))) ?>" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="core-features__promicess">
                            <ul class="list-unstyled core-features__promicess-list">
                                <?php 
                                $extraText = value($whyChooseUs, 'extra_text');
                                if ($extraText) {
                                    echo render_content($extraText);
                                } else {
                                ?>
                                <li>
                                    <div class="text">
                                        <p><b>Strategic Approach : </b></p>
                                        <p>Every idea is backed by research, insights, and a clear path to results.</p>
                                    </div>
                                    <div class="icon"><span class="icon-tick"></span></div>
                                </li>
                                <li>
                                    <div class="text">
                                        <p><b>End-to-End Solutions : </b></p>
                                        <p>From branding to digital campaigns, we cover every aspect of advertising.</p>
                                    </div>
                                    <div class="icon"><span class="icon-tick"></span></div>
                                </li>
                                <li>
                                    <div class="text">
                                        <p><b>Client-Centric Focus : </b></p>
                                        <p>We listen, adapt, and deliver solutions tailored to your brand’s unique needs.</p>
                                    </div>
                                    <div class="icon"><span class="icon-tick"></span></div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Clients -->
        <section class="similar-work">
            <div class="container">
                <div class="section-title text-center">
                    <h2 class="section-title__title">Our Clients</h2>
                </div>
                <div class="swiper-container thm-swiper__slider" data-swiper-options='{"slidesPerView":7,"spaceBetween":15,"loop":true,"autoHeight":true,"autoplay":{"delay":500},"breakpoints":{"0":{"slidesPerView":2,"spaceBetween":8},"480":{"slidesPerView":3,"spaceBetween":10},"768":{"slidesPerView":4,"spaceBetween":12},"991":{"slidesPerView":5,"spaceBetween":15},"1200":{"slidesPerView":6,"spaceBetween":15},"1400":{"slidesPerView":7,"spaceBetween":15}}}'>
                    <div class="swiper-wrapper">
                        <?php foreach ($portfolioImages as $image) { ?>
                            <div class="swiper-slide">
                                <div class="portfolio-one__single">
                                    <div class="portfolio-one__img">
                                        <img src="<?= h(media_url(value($image, 'image'))) ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>

        <style>
        .similar-work { padding: 50px 0; }
        .portfolio-one__single { text-align: center; }
        .portfolio-one__img img { width: 150px; height: auto; opacity: 0.9; transition: all 0.3s ease; margin: 0 auto; }
        .portfolio-one__img img:hover { opacity:1; transform:scale(1.05); }
        @media (max-width:768px) { .similar-work { padding:25px 0 !important; } .section-title h2 { margin-bottom:10px !important; } .portfolio-one__img img { width:90px !important; } .swiper-container { padding-top:0 !important; padding-bottom:0 !important; } }
        @media (max-width:480px) { .portfolio-one__img img { width:140px !important; } }
        </style>

        <?php render_site_footer(); ?>
    </div>
    <?php include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
