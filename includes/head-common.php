<?php
if (!isset($metaTitle)) {
    $metaTitle = 'RIO AD Agency';
}
if (!isset($metaDescription)) {
    $metaDescription = '';
}
?>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= h($metaTitle) ?></title>
<meta name="description" content="<?= h($metaDescription) ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/images/favicons/rio.png" />
<link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/images/favicons/rio.png" />
<link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/images/favicons/rio.png" />
<link rel="manifest" href="<?= BASE_URL ?>assets/images/favicons/site.webmanifest" />
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@300;400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/animate/animate.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/fontawesome/css/all.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/jarallax/jarallax.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/nouislider/nouislider.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/nouislider/nouislider.pips.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/odometer/odometer.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/swiper/swiper.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/moniz-icons/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/tiny-slider/tiny-slider.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/reey-font/stylesheet.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/owl-carousel/owl.carousel.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/owl-carousel/owl.theme.default.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/twentytwenty/twentytwenty.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/bxslider/jquery.bxslider.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/bootstrap-select/css/bootstrap-select.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/vegas/vegas.min.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendors/timepicker/timePicker.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/moniz.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/moniz-responsive.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/moniz-rtl.css">
<link rel="stylesheet" id="jssDefault" href="<?= BASE_URL ?>assets/css/colors/color-default.css">
<link rel="stylesheet" id="jssMode" href="<?= BASE_URL ?>assets/css/modes/moniz-normal.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/moniz-toolbar.css">
<?php if (!empty($extraStyles)) { echo $extraStyles; } ?>
