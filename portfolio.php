<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$pageSlug = 'portfolio';
$page = fetch_page($conn, 'portfolio');
$intro = fetch_section($conn, 'portfolio', 'intro');
$why = fetch_section($conn, 'portfolio', 'why_choose_us_intro');
$faqs = fetch_faqs($conn, 'portfolio');
$portfolioImages = fetch_images($conn, 'home', 'home_portfolio');
if (!$portfolioImages) {
    $portfolioImages = [];
    for ($i = 1; $i <= 15; $i++) {
        $portfolioImages[] = ['image' => 'assets/images/resources/c' . $i . '.jpg'];
    }
}

$metaTitle = value($page, 'meta_title', 'Portfolio | RIO AD Agency');
$metaDescription = value($page, 'meta_description', 'Explore the portfolio of RIO AD Agency.');
$heroTitle = value($page, 'banner_title', 'Portfolio');
$bannerImage = value($page, 'banner_image', 'assets/images/backgrounds/main-slider-1-1.jpg');

$extraStyles = '<style>
 .portfolio-detail__text,
.portfolio-detail__text p,
.service-card p,
.service-card ul li,
.portfolio-detail__right p {
  
  /* absolutely disable hyphenation */
  -webkit-hyphens: none;
  -moz-hyphens: none;
  hyphens: none;

  /* DO NOT use anywhere */
  overflow-wrap: break-word;
  word-break: normal;

  text-align-last: auto;

   
}
@media (max-width: 480px) {

  .we-change__left-faqs,
  .we-change__left-faqs p,
  .we-change__left-faqs .inner p {
    text-align: left !important;
    text-justify: auto !important;

    /* prevent Safari spacing bugs */
    word-spacing: normal !important;
    letter-spacing: normal !important;
    hyphens: none !important;
  }

}
</style>';
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
        <section class="page-header" style="background-image: url(<?= h(media_url($bannerImage)) ?>);">
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

        <!--Reasons Start-->
        <section class="reasons">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-6">
                        <div class="reasons__left">
                            <div class="reasons__img">
                                <img src="<?= h(media_url(value($intro, 'image', 'assets/images/resources/we-change-right-img-1.jpg'))) ?>" alt="">
                                <div class="reasons__img-shape-1"></div>
                                <div class="reasons__img-shape-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6">
                        <div class="reasons__right">
                            <div class="section-title text-justify">
                                
                            </div>
                            <p align="justify" class="web-solutions__content-desc no-hyphen"><?= render_content(value($intro, 'content', 'At RIO AD Agency in Hyderabad, we specialize in delivering creative advertising solutions with that help businesses grow, stand out, and succeed in the digital era. As a leading advertising agency in Hyderabad, we focus on combining creativity, strategy, and innovation to transform ideas into impactful brand experiences. Whether it’s building a strong online presence, shaping a memorable brand identity, or creating campaigns that engage the right audience, RIO AD Agency is dedicated to making your business shine.')) ?></p>
                            <p><?= render_content(value($intro, 'extra_text', 'We are more than just an ad agency , we are your trusted advertising partner in Hyderabad.')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Reasons End-->

        <!--We Change Start-->
        <section class="we-change">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="we-change__left-faqs" style="text-align: justify;">
                            <div class="section-title text-justify">
                                <h2 class="section-title__title">Why Choose Us</h2>
                            </div>
                            <div class="we-change__faqs">
                                <div class="accrodion-grp" data-grp-name="faq-one-accrodion">
                                    <div class="accrodion active">
                                        <div class="accrodion-content">
                                            <div class="inner">
                                                <p class="no-hyphen"><?= render_content(value($why, 'content', 'At RIO AD Agency, we go beyond the traditional with advertising by blending a creativity with strategy to deliver an impactful brand experiences. Our goal is to help the businesses in Hyderabad and beyond grow, that stand out, and succeed in the digital era.')) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                    if (count($faqs) > 0) {
                                        foreach ($faqs as $faq) {
                                    ?>
                                    <div class="accrodion">
                                        <div class="accrodion-title">
                                            <h4><?= render_content($faq['question']) ?></h4>
                                        </div>
                                        <div class="accrodion-content">
                                            <div class="inner">
                                                <p class="no-hyphen"><?= render_content($faq['answer']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                    } else {
                                    ?>
                                    <div class="accrodion">
                                        <div class="accrodion-title">
                                            <h4>1. Creative, Strategic Approach</h4>
                                        </div>
                                        <div class="accrodion-content">
                                            <div class="inner">
                                                <p class="no-hyphen">We combine fresh ideas with the data-driven strategies that build campaigns that truly connect with your audience.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accrodion last-chiled">
                                        <div class="accrodion-title">
                                            <h4>2. Proven Results & Client Focus</h4>
                                        </div>
                                        <div class="accrodion-content">
                                            <div class="inner">
                                                <p class="no-hyphen" align="justify">From brand identity to digital presence, we focus on the measurable a results while keeping with client success at the heart of everything we do.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="we-change__right">
                            <div class="we-change__right-img">
                                <img src="<?= h(media_url(value($why, 'image', 'assets/images/resources/we-change-right-img-1.jpg'))) ?>" alt="">
                                <div class="we-change__agency">
                                    <div class="we-change__agency-icon">
                                        <span class="icon-development"></span>
                                    </div>
                                    <div class="we-change__agency-text">
                                        <h3>Our agency is one of the most <br> successful agency.</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--We Change End-->

        <?php render_site_footer(); ?>
    </div><!-- /.page-wrapper -->
    
    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"></span>

            <div class="logo-box">
                <a href="<?= h(page_url('home')) ?>" aria-label="logo image"><img src="<?= h(media_url('assets/images/resources/rio-ad.png')) ?>" width="155" alt="" /></a>
            </div>
            <div class="mobile-nav__container"></div>
        </div>
    </div>
    
    <div class="search-popup">
        <div class="search-popup__overlay search-toggler"></div>
        <div class="search-popup__content">
            <form action="#">
                <label for="search" class="sr-only">search here</label>
                <input type="text" id="search" placeholder="Search Here..." />
                <button type="submit" aria-label="search submit" class="thm-btn">
                    <i class="icon-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>
    
    <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>
    
    <?php include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
