<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

cms_run_schema_updates($conn);
$blog = null;
if (isset($_GET['slug'])) {
    $blog = fetch_blog($conn, $_GET['slug']);
}

if (!$blog) {
    header("Location: " . page_file('blogs'));
    exit();
}

$socialLinks = cms_get_social_links($conn);
$canonicalUrl = cms_canonical_url($blog, $conn);
$titleHeadingTag = cms_heading_tag(value($blog, 'title_heading_tag', 'h1'), 'h1');
$subtitleHeadingTag = cms_heading_tag(value($blog, 'subtitle_heading_tag', 'h2'), 'h2');

$pageSlug = "blogs";
$page = fetch_page($conn, $pageSlug);
if (!$page) {
    $page = ['page_name' => 'Blogs', 'banner_title' => 'Our Blogs'];
}

$metaTitle = value($blog, 'meta_title', value($blog, 'title', 'Blog | RIO AD Agency'));
$metaDescription = value($blog, 'meta_description', '');
$bannerTitle = value($blog, 'title', 'Blog');
$bannerImage = media_url(value($blog, 'header_image', 'assets/images/backgrounds/main-slider-1-1.jpg'));
$extraStyles = '<link rel="canonical" href="' . cms_e($canonicalUrl) . '">';

$date = date('d M', strtotime(value($blog, 'created_at', 'now')));
$day = date('d', strtotime(value($blog, 'created_at', 'now')));
$month = date('M', strtotime(value($blog, 'created_at', 'now')));
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
                        <li><a href="<?= h(page_file('home')) ?>">Home</a></li>
                        <li><span>.</span></li>
                        <li><a href="<?= h(page_file('blogs')) ?>">Blogs</a></li>
                        <li><span>.</span></li>
                        <li><?= h($bannerTitle) ?></li>
                    </ul>
                    <h2><?= h($bannerTitle) ?></h2>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <!--Blog Details Start-->
        <section class="blog-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="blog-details__left">
                            <div class="blog-details__img">
                                <img src="<?= h(media_url(value($blog, 'image'))) ?>" alt="<?= cms_e(!empty($blog['image_alt']) ? $blog['image_alt'] : $blog['title']) ?>">
                                <div class="blog-details__date">
                                    <p><span><?= h($day) ?></span> <?= h($month) ?></p>
                                </div>
                            </div>
                            <div class="blog-details__content">
                                <ul class="list-unstyled blog-details__meta">
                                    <li><a href="#"><i class="far fa-user-circle"></i> By admin</a></li>
                                </ul>
                                <<?= $titleHeadingTag ?> class="blog-details__title"><?= h($blog['title']) ?></<?= $titleHeadingTag ?>>
                                <?php if (!empty($blog['subtitle'])) { ?>
                                    <<?= $subtitleHeadingTag ?>><?= h($blog['subtitle']) ?></<?= $subtitleHeadingTag ?>>
                                <?php } ?>
                                
                                <?php if (!empty($blog['intro_text'])) { ?>
                                <p><?= nl2br($blog['intro_text']) ?></p>
                                <?php } ?>
                                
                                <div class="blog-details__text-box">
                                    <?= $blog['content'] ?>
                                </div>

                                <?php if (!empty($blog['conclusion'])) { ?>
                                <blockquote class="blockquote">
                                    <p><?= nl2br($blog['conclusion']) ?></p>
                                </blockquote>
                                <?php } ?>

                                <?php if (!empty($blog['backlinks'])) { ?>
                                <div class="blog-backlinks mt-5">
                                    <h5>References & Backlinks</h5>
                                    <div class="backlinks-content">
                                        <?= $blog['backlinks'] ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="sidebar">
                            <div class="sidebar__single sidebar__post">
                                <h3 class="sidebar__title">Recent Blogs</h3>
                                <ul class="sidebar__post-list list-unstyled">
                                    <?php
                                    $recentBlogs = fetch_blogs($conn, 5);
                                    foreach ($recentBlogs as $recentBlog) {
                                        if (value($recentBlog, 'id') == value($blog, 'id')) continue;
                                    ?>
                                    <li>
                                        <div class="sidebar__post-image">
                                            <img src="<?= h(media_url(value($recentBlog, 'image'))) ?>" alt="<?= cms_e(!empty($recentBlog['image_alt']) ? $recentBlog['image_alt'] : $recentBlog['title']) ?>">
                                        </div>
                                        <div class="sidebar__post-content">
                                            <h3>
                                                <a href="<?= h(blog_file(value($recentBlog, 'slug'))) ?>"><?= h($recentBlog['title']) ?></a>
                                            </h3>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Blog Details End-->

        <?php render_site_footer(); ?>
    </div><!-- /.page-wrapper -->
    
    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"></span>

            <div class="logo-box">
                <a href="<?= h(page_file('home')) ?>" aria-label="logo image"><img src="<?= h(media_url('assets/images/resources/rio-ad.png')) ?>" width="155" alt="" /></a>
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
