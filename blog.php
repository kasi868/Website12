<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$page = fetch_page($conn, 'blog');
$blogs = fetch_blogs($conn);
$metaTitle = value($page, 'meta_title', 'Blogs | RIO AD Agency');
$metaDescription = value($page, 'meta_description', 'Read the latest branding, marketing, and advertising insights from RIO AD Agency.');
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include __DIR__ . '/includes/head-common.php'; ?></head>
<body>
<div class="page-wrapper">
   
    <?php include __DIR__ . '/includes/header.php'; ?>
    <section class="blog-one blog-grid">
        <div class="container">
            <div class="row">
                <?php foreach ($blogs as $index => $blog) { ?>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="blog-one__single wow fadeInUp" data-wow-delay="<?= h((($index % 6) + 1) * 100) ?>ms">
                            <div class="blog-one__img-box">
                                <div class="blog-one__img">
                                    <img src="<?= h(media_url(value($blog, 'image', 'assets/images/blog/blog-1-1.jpg'))) ?>" alt="">
                                    <a href="<?= h(blog_file($blog['slug'])) ?>"><span class="blog-one__plus"></span></a>
                                </div>
                                <div class="blog-one__date-box">
                                    <p><span><?= date('d', strtotime($blog['created_at'])) ?></span> <?= date('M', strtotime($blog['created_at'])) ?></p>
                                </div>
                            </div>
                            <div class="blog-one__content">
                                <ul class="list-unstyled blog-one__meta">
                                    <li><a href="<?= h(blog_file($blog['slug'])) ?>"><i class="far fa-user-circle"></i> By admin</a></li>
                                </ul>
                                <h3 class="blog-one__title"><a href="<?= h(blog_file($blog['slug'])) ?>"><?= h($blog['title']) ?></a></h3>
                                <p class="blog-one__text"><?= h(excerpt_text(value($blog, 'intro_text', value($blog, 'content')), 120)) ?></p>
                                <div class="blog-one__bottom">
                                    <div class="blog-one__read-btn"><a href="<?= h(blog_file($blog['slug'])) ?>">Read more</a></div>
                                    <div class="blog-one__arrow"><a href="<?= h(blog_file($blog['slug'])) ?>"><span class="icon-right-arrow"></span></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <?php render_site_footer(); ?>
</div>
<?php include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
