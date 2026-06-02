<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$pageSlug = isset($pageSlug) && $pageSlug !== '' ? $pageSlug : 'blogs';
$page = isset($page) && is_array($page) ? $page : fetch_page($conn, $pageSlug);
if (!$page) {
    $page = ['page_name' => 'Blogs', 'banner_title' => 'Our Blogs', 'meta_title' => 'Blogs | RIO AD Agency', 'meta_description' => 'Read our latest blogs about advertising and marketing.'];
}

$metaTitle = value($page, 'meta_title', 'Blogs | RIO AD Agency');
$metaDescription = value($page, 'meta_description', '');
$bannerTitle = value($page, 'banner_title', 'Blogs');
$bannerImage = media_url(value($page, 'banner_image', 'assets/images/backgrounds/main-slider-1-1.jpg'));
$blogs = fetch_blogs($conn);
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
                        <li><a href="<?= h(get_home_url()) ?>">Home</a></li>
                        <li><span>.</span></li>
                        <li><?= h($bannerTitle) ?></li>
                    </ul>
                    <h2><?= h($bannerTitle) ?></h2>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <!--Blog Page Start-->
        <section class="blog-one blog-grid">
            <div class="container">
                <div class="row">
                    <?php 
                    $count = 0;
                    foreach ($blogs as $blog) { 
                        $count++;
                        $delay = $count * 100;
                        $date = date('d M', strtotime(value($blog, 'created_at', 'now')));
                        $day = date('d', strtotime(value($blog, 'created_at', 'now')));
                        $month = date('M', strtotime(value($blog, 'created_at', 'now')));
                    ?>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <!--Blog One Single-->
                        <div class="blog-one__single wow fadeInUp" data-wow-delay="<?= $delay ?>ms">
                            <div class="blog-one__img-box">
                                <div class="blog-one__img">
                                    <img src="<?= h(media_url(value($blog, 'image'))) ?>" alt="<?= cms_e(!empty($blog['image_alt']) ? $blog['image_alt'] : $blog['title']) ?>">
                                    <a href="<?= h(blog_file(value($blog, 'slug'))) ?>">
                                        <span class="blog-one__plus"></span>
                                    </a>
                                </div>
                                <div class="blog-one__date-box">
                                    <p><span><?= h($day) ?></span> <?= h($month) ?></p>
                                </div>
                            </div>
                            <div class="blog-one__content">
                                <ul class="list-unstyled blog-one__meta">
                                    <li><a href="<?= h(blog_file(value($blog, 'slug'))) ?>"><i class="far fa-user-circle"></i> By admin</a></li>
                                </ul>
                                <h3 class="blog-one__title">
                                    <a href="<?= h(blog_file(value($blog, 'slug'))) ?>"><?= h($blog['title']) ?></a>
                                </h3>
                                <p class="blog-one__text"><?= excerpt_text($blog['content'], 100) ?></p>
                                <div class="blog-one__bottom">
                                    <div class="blog-one__read-btn">
                                        <a href="<?= h(blog_file(value($blog, 'slug'))) ?>">Read more</a>
                                    </div>
                                    <div class="blog-one__arrow">
                                        <a href="<?= h(blog_file(value($blog, 'slug'))) ?>"><span class="icon-right-arrow"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
            </div>
        </section>
        <!--Blog Page End-->

        <?php render_site_footer(); ?>
    </div><!-- /.page-wrapper -->
    
    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"></span>

            <div class="logo-box">
                <a href="<?= h(get_home_url()) ?>" aria-label="logo image"><img src="<?= h(media_url('assets/images/resources/rio-ad.png')) ?>" width="155" alt="" /></a>
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
