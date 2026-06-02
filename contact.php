<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$page = fetch_page($conn, 'contact');
$siteContactLocal = fetch_section($conn, 'site-settings', 'contact');
$contactForm = fetch_section($conn, 'contact', 'google_form');
$metaTitle = value($page, 'meta_title', 'Contact Us | RIO AD Agency');
$metaDescription = value($page, 'meta_description', 'Get in touch with RIO AD Agency in Hyderabad.');
$extraStyles = '<style>
/* Light Section Background */
.contact-section {
    background: #fff;
}

/* Contact Box Styling */
.shadow-box {
    background: #fff;
    border: 1px solid #e9e9e9;
    border-radius: 18px;
    transition: .3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

/* Hover Effect */
.shadow-box:hover {
    transform: translateY(-6px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

/* Icon Color */
.contact-info-box i {
    color: #ff6d1f;   /* Your theme color */
}

/* Text Size */
.contact-info-box p {
    font-size: 18px;
    margin-bottom: 0;
}

/* Link Style */
.contact-link {
    color: #000;
    text-decoration: none;
}
.contact-link:hover {
    color: #ff6d1f;
}
</style>';
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include __DIR__ . '/includes/head-common.php'; ?></head>
<body>
<div class="page-wrapper">
    <?php include __DIR__ . '/includes/header.php'; ?>
    <?php 
    $bannerTitle = value($page, 'banner_title', 'Contact');
    $bannerImage = media_url(value($page, 'banner_image', 'assets/images/backgrounds/main-slider.jpg'));
    ?>
    <!--Page Header Start-->
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
    <!--Page Header End-->
    <br><br>
    <section class="contact-section py-5">
        <div class="container">
            <div class="row mb-4">
                <!-- PHONE -->
                <div class="col-md-4 mb-4">
                    <div class="contact-info-box text-center p-4 shadow-box">
                        <i class="fa fa-phone fa-2x mb-3"></i>
                        <h5>Phone</h5>
                        <span><a href="tel:<?= h(preg_replace('/\D+/', '', value($siteContactLocal, 'sub_heading', '9703636052'))) ?>" class="contact-link"><?= h(value($siteContactLocal, 'sub_heading', '+91 9703636052')) ?></a></span>
                    </div>
                </div>

                <!-- EMAIL -->
                <div class="col-md-4 mb-4">
                    <div class="contact-info-box text-center p-4 shadow-box">
                        <i class="fa fa-envelope fa-2x mb-3"></i>
                        <h5>Email</h5>
                        <span><a href="mailto:<?= h(value($siteContactLocal, 'extra_text', 'rioadagency@gmail.com')) ?>" class="contact-link"><?= h(value($siteContactLocal, 'extra_text', 'rioadagency@gmail.com')) ?></a></span>
                    </div>
                </div>

                <!-- ADDRESS -->
                <div class="col-md-4 mb-4">
                    <div class="contact-info-box text-center p-4 shadow-box">
                        <i class="fa fa-map-marker fa-2x mb-3"></i>
                        <h5>Address</h5>
                        <span><?= render_content(value($siteContactLocal, 'content')) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div style="max-width: 100%; overflow: hidden;">
        <iframe src="<?= h(value($contactForm, 'sub_heading', 'https://docs.google.com/forms/d/e/1FAIpQLSd1HBTiMEP48i8qiI-pKg8FrtuoPnxrTIET3noBUa1CpqK2Dg/viewform')) ?>" width="100%" height="1200" frameborder="0" marginheight="0" marginwidth="0" style="border: none;">Loading...</iframe>
    </div>

    <!--Contact Page Google Map Start-->
    <section class="contact-page-google-map">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <iframe src="<?= h(value($contactForm, 'button_link', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3805.5577264771537!2d78.4152835742117!3d17.480870100107218!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb9188027aa729%3A0x87824a7b2fbb0ff3!2sSRI%20CHAKRA%20RAJA%20NILAYAM!5e0!3m2!1sen!2sin!4v1757608227081!5m2!1sen!2sin')) ?>" width="600" height="450" style="border:0;" loading="lazy" class="contact-page-google-map__box" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>
    <!--Contact Page Google Map End-->

    <?php render_site_footer(); ?>
</div>
<?php include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
