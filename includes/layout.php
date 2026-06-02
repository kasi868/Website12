<?php
require_once __DIR__ . '/site.php';

function render_site_header($currentSlug, $isHome = false)
{
    global $siteIdentity, $siteSocialLinks, $siteContact;

    $headerClass = $isHome ? 'main-header-two clearfix' : 'main-header-two clearfix';
    $menuClass = $isHome ? 'main-menu main-menu-two clearfix' : 'main-menu main-menu-two clearfix';
    $stickyClass = $isHome ? 'stricky-header stricked-menu main-menu main-menu-two' : 'stricky-header stricked-menu main-menu';
    $logo = media_url(value($siteIdentity, 'image', 'assets/images/resources/rio-ad.png'));
    ?>
    <style>
        .main-menu.main-menu-two { 
            width: 100% !important; 
            padding: 0 !important; 
            margin: 0 !important;
        }
        .main-header-two,
        .main-header-two .main-menu-two,
        .main-header-two .main-menu-two-wrapper { 
            background: #1f1f25 !important; 
        }

        .main-menu-two-wrapper { 
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 24px !important;
            width: 100% !important;
            min-height: 100px !important;
            padding: 10px 40px !important;
            box-sizing: border-box !important;
            position: relative !important;
            z-index: 10 !important;
        }
        .main-menu-two-wrapper__logo { 
            flex: 0 0 auto !important; 
            margin: 0 !important; 
            padding: 0 !important;
            position: relative !important;
            z-index: 20 !important;
        }
        .main-menu-two-wrapper__logo img { 
            max-width: 180px !important; 
            height: auto !important; 
            display: block !important; 
        }

        .main-menu-two-wrapper__main-menu { 
            flex: 1 1 auto !important; 
            min-width: 0 !important; 
            display: flex !important; 
            justify-content: center !important;
            position: relative !important;
            z-index: 15 !important;
        }
        .main-menu__list { 
            display:flex !important; 
            align-items:center !important; 
            justify-content:center !important; 
            gap:40px !important; 
            flex-wrap:nowrap !important; 
            white-space:nowrap !important; 
            margin:0 !important; 
            padding:0 !important; 
            list-style:none !important; 
        }
        .main-menu__list > li { 
            flex:0 0 auto !important; 
            position: relative !important;
            float: none !important;
        }
        .main-menu__list > li > a { 
            display:inline-flex !important; 
            align-items:center !important; 
            padding:10px 0 !important; 
            line-height:1 !important; 
            font-size:18px !important; 
            font-weight:500 !important; 
            color:#ffffff !important; 
            text-decoration:none !important; 
            font-family: 'Poppins', sans-serif !important;
            position: relative !important;
            float: none !important;
        }
        .main-menu__list > li > a:hover, 
        .main-menu__list > li.current > a { 
            color:#ff7a18 !important; 
        }

        .divider {
            width: 1px !important;
            height: 30px !important;
            background: rgba(255,255,255,0.3) !important;
            margin: 0 20px !important;
            flex-shrink: 0 !important;
        }

        .main-menu-two-wrapper__right { 
            display:flex !important; 
            align-items:center !important; 
            flex:0 0 auto !important;
            position: relative !important;
            z-index: 15 !important;
        }
        .main-menu-two-wrapper__social { 
            display:flex !important; 
            align-items:center !important; 
            gap:15px !important; 
        }
        .main-menu-two-wrapper__social a { 
            display:inline-flex !important; 
            align-items:center !important; 
            justify-content:center !important; 
            width:40px !important; 
            height:40px !important; 
            color:#fff !important; 
            font-size:20px !important; 
            transition:all .25s ease !important; 
            text-decoration: none !important;
            padding: 0 !important;
        }
        .main-menu-two-wrapper__social a:hover { 
            color:#ff7a18 !important; 
        }

        /* Override any theme-specific positioning */
        .main-menu-two-wrapper__left {
            display: none !important;
        }

        @media (max-width:1199px) {
            .main-menu-two-wrapper { flex-wrap:wrap !important; min-height:auto !important; padding:12px 20px !important; }
            .main-menu-two-wrapper__logo { margin-bottom:8px !important; }
            .main-menu-two-wrapper__main-menu { order:3 !important; width:100% !important; margin-top:8px !important; }
            .main-menu__list { gap:16px !important; flex-wrap:wrap !important; justify-content:center !important; }
            .main-menu__list > li > a { font-size:16px !important; padding:10px 6px !important; }
            .divider { display: none !important; }
            .main-menu-two-wrapper__right { order:2 !important; width:100% !important; justify-content:center !important; margin-top:8px !important; }
            .mobile-nav__toggler { display:inline-flex !important; }
            .mobile-nav__wrapper { display: none; }
            .mobile-nav__wrapper.open { display: block; position: fixed; top: 0; right: 0; width: 320px; height: 100%; background: #1f1f25; z-index: 2000; padding: 20px; overflow-y: auto; }
            .mobile-nav__container ul { list-style: none; margin: 0; padding: 0; }
            .mobile-nav__container ul li { padding: 10px 0; }
            .mobile-nav__container ul li a { color: #fff; text-decoration: none; font-size: 16px; }
        }
    </style>
    <!-- Custom Header -->
    <header style="background: #1f1f25; width: 100%; position: relative; z-index: 1000;">
        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 10px 40px; box-sizing: border-box; min-height: 100px;">
            
            <!-- Logo on Left -->
            <div style="flex: 0 0 auto; position: relative; z-index: 1001;">
                <a href="<?= h(page_url('home')) ?>" style="text-decoration: none;">
                    <img src="<?= h($logo) ?>" alt="RIO AD Agency" style="max-width: 180px; height: auto; display: block;">
                </a>
            </div>
            
            <!-- Navigation in Center -->
            <nav style="flex: 1 1 auto; display: flex; justify-content: center; position: relative; z-index: 1001;">
                <ul style="display: flex; align-items: center; justify-content: center; gap: 40px; list-style: none; margin: 0; padding: 0;">
                    <?php
                    $nav = nav_items();
                    foreach ($nav as $index => $item) {
                        if ($index === 2) {
                    ?>
                    <li style="flex: 0 0 auto; position: relative;">
                        <a href="#" style="display: inline-flex; align-items: center; padding: 10px 0; line-height: 1; font-size: 18px; font-weight: 500; color: #ffffff; text-decoration: none; font-family: 'Poppins', sans-serif;">Services</a>
                        <ul style="position: absolute; top: 100%; left: 0; background: #1f1f25; list-style: none; margin: 0; padding: 10px 0; min-width: 250px; display: none;">
                            <?php foreach (service_nav_items() as $sitem) { ?>
                                <li style="padding: 8px 20px;">
                                    <a href="<?= h(page_url($sitem['slug'])) ?>" style="color: #fff; text-decoration: none; font-size: 16px; display: block;"><?= h($sitem['label']) ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php
                        }
                    ?>
                    <li class="<?= $currentSlug === $item['slug'] ? 'current' : '' ?>" style="flex: 0 0 auto;">
                        <a href="<?= h(page_url($item['slug'])) ?>" style="display: inline-flex; align-items: center; padding: 10px 0; line-height: 1; font-size: 18px; font-weight: 500; color: #ffffff; text-decoration: none; font-family: 'Poppins', sans-serif;"><?= h($item['label']) ?></a>
                    </li>
                    <?php
                    }
                    ?>
                    </nav>

                    <!-- Right side: contact and social -->
                    <div style="display:flex; align-items:center; gap:18px; flex:0 0 auto;">
                        <div style="margin-right:12px; color:#fff; font-weight:600;">
                            <a href="tel:<?= h(preg_replace('/\D+/', '', value($siteContact, 'sub_heading', '9703636052'))) ?>" style="color:#fff; text-decoration:none;"><?= h(value($siteContact, 'sub_heading', '+91 9703636052')) ?></a>
                        </div>
                        <div style="display:flex; gap:8px; align-items:center;">
                            <?php foreach ($siteSocialLinks as $social) { ?>
                                <a href="<?= h(value($social, 'link', '#')) ?>" style="color:#fff; text-decoration:none; font-size:18px;">
                                    <i class="<?= h(value($social, 'icon_class', 'fab fa-link')) ?>"></i>
                                </a>
                            <?php } ?>
                        </div>
                        <button class="mobile-nav__toggler" style="display:none; background:transparent; border:none; color:#fff; font-size:22px; margin-left:12px;">☰</button>
                    </div>
                </div>
            </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function buildMobileMenu() {
                var container = document.querySelector('.mobile-nav__container');
                if (!container) return;
                container.innerHTML = '';
                var desktop = document.querySelector('header nav ul');
                if (!desktop) return;
                var clone = desktop.cloneNode(true);
                // Ensure submenus are visible in mobile
                clone.querySelectorAll('ul').forEach(function(sub){ sub.style.display = 'block'; });
                clone.classList.add('mobile-menu');
                container.appendChild(clone);
            }

            var togglers = document.querySelectorAll('.mobile-nav__toggler');
            togglers.forEach(function(btn){
                btn.addEventListener('click', function(e){
                    var wrapper = document.querySelector('.mobile-nav__wrapper');
                    if (!wrapper) return;
                    if (!wrapper.classList.contains('open')) {
                        buildMobileMenu();
                        wrapper.classList.add('open');
                    } else {
                        wrapper.classList.remove('open');
                    }
                });
            });

            // Close when clicking overlay or close button
            var overlay = document.querySelector('.mobile-nav__overlay');
            if (overlay) overlay.addEventListener('click', function(){ document.querySelector('.mobile-nav__wrapper')?.classList.remove('open'); });
            var closeBtn = document.querySelector('.mobile-nav__close');
            if (closeBtn) closeBtn.addEventListener('click', function(){ document.querySelector('.mobile-nav__wrapper')?.classList.remove('open'); });
        });
    </script>

    <?php
}

function render_site_footer()
{
    global $siteIdentity, $siteContact, $siteFooter, $siteSocialLinks;

    $logo = media_url(value($siteIdentity, 'image', 'assets/images/resources/rio-ad.png'));
    ?>
    <footer class="site-footer">
        <div class="site-footer__top">
            <div class="site-footer-top-bg" style="background-image: url(assets/images/backgrounds/site-footer-bg.jpg)"></div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="footer-widget__column footer-widget__about">
                            <div class="footer-widget__about-logo">
                                <a href="<?= h(page_url('home')) ?>"><img src="<?= h($logo) ?>" alt=""></a>
                            </div>
                            <p class="footer-widget__about-text"><?= render_content(value($siteFooter, 'content', 'At RIO AD Agency in Hyderabad, we specialize in delivering creative advertising solutions that help businesses grow, stand out, and succeed in the digital era.')) ?></p>
                            <div class="footer-widget__about-social-list">
                                <?php foreach ($siteSocialLinks as $social) { ?>
                                    <a href="<?= h(value($social, 'link', '#')) ?>" class="<?= h(value($social, 'subtitle')) ?>"><i class="<?= h(value($social, 'icon_class', 'fab fa-link')) ?>"></i></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                        <div class="footer-widget__column footer-widget__explore clearfix">
                            <h3 class="footer-widget__title">Quick Links</h3>
                            <ul class="footer-widget__explore-list list-unstyled">
                                <?php foreach (nav_items() as $item) { ?>
                                    <li><a href="<?= h(page_url($item['slug'])) ?>"><?= h($item['label']) ?></a></li>
                                <?php } ?>
                                <li><a href="<?= h(page_url('terms-conditions')) ?>">Terms & conditions</a></li>
                                <li><a href="<?= h(page_url('privacy')) ?>">Privacy & Policy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                        <div class="footer-widget__column footer-widget__explore clearfix">
                            <h3 class="footer-widget__title">Services</h3>
                            <ul class="footer-widget__explore-list list-unstyled">
                                <?php foreach (service_nav_items() as $item) { ?>
                                    <li><a href="<?= h(page_url($item['slug'])) ?>"><?= h($item['label']) ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                        <div class="footer-widget__column footer-widget__contact">
                            <h3 class="footer-widget__title">Contact Us</h3>
                            <span class="footer-widget__contact-text"><?= render_content(value($siteContact, 'content', 'RIO AD Agency, Sangeet Nagar, Kukatpally, Hyderabad, Telangana 500072.')) ?></span>
                            <div class="footer-widget__contact-info">
                                <p>
                                    <a href="tel:<?= h(preg_replace('/\D+/', '', value($siteContact, 'sub_heading', '9703636052'))) ?>" class="footer-widget__contact-phone"><?= h(value($siteContact, 'sub_heading', '+91 9703636052')) ?></a>
                                    <a href="mailto:<?= h(value($siteContact, 'extra_text', 'rioadagency@gmail.com')) ?>" class="footer-widget__contact-mail"><?= h(value($siteContact, 'extra_text', 'rioadagency@gmail.com')) ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="site-footer-bottom__inner">
                            <p class="site-footer-bottom__copy-right"><?= render_content(value($siteFooter, 'extra_text', '&copy; Copyright ' . current_year() . ' | All Rights Reserved')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"></span>

            <div class="logo-box">
                <a href="<?= h(page_url('home')) ?>" aria-label="logo image"><img src="<?= h($logo) ?>" width="155" alt="" /></a>
            </div>
            <div class="mobile-nav__container"></div>

            <ul class="mobile-nav__contact list-unstyled">
                <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:<?= h(value($siteContact, 'extra_text', 'rioadagency@gmail.com')) ?>"><?= h(value($siteContact, 'extra_text', 'rioadagency@gmail.com')) ?></a>
                </li>
                <li>
                    <i class="fa fa-phone-alt"></i>
                    <a href="tel:<?= h(preg_replace('/\D+/', '', value($siteContact, 'sub_heading', '9703636052'))) ?>"><?= h(value($siteContact, 'sub_heading', '+91 9703636052')) ?></a>
                </li>
            </ul>
            <div class="mobile-nav__top">
                <div class="mobile-nav__social">
                    <?php foreach ($siteSocialLinks as $social) { ?>
                        <a href="<?= h(value($social, 'link', '#')) ?>" class="<?= h(value($social, 'icon_class', 'fab fa-link')) ?>"></a>
                    <?php } ?>
                </div>
            </div>
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

    <script>
        (function () {
            var options = {
                whatsapp: "<?= addslashes(value($siteContact, 'button_link', '+91 9703636052')) ?>",
                call_to_action: "<?= addslashes(value($siteContact, 'button_text', 'Connect Us')) ?>",
                position: "right"
            };
            var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
            s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
        })();
    </script>
    <?php
}

function render_page_header($title, $breadcrumb, $backgroundImage = 'assets/images/backgrounds/main-slider-1-10.jpg')
{
    ?>
    <!--Page Header Start-->
    <section class="page-header" style="background-image: url(<?= h(media_url($backgroundImage)) ?>);">
        <div class="page-header-shape-1"></div>
        <div class="page-header-shape-2"></div>
        <div class="container">
            <div class="page-header__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="<?= h(page_url('home')) ?>">Home</a></li>
                    <li><span>.</span></li>
                    <li><?= h($breadcrumb) ?></li>
                </ul>
                <h2><?= h($title) ?></h2>
            </div>
        </div>
    </section>
    <!--Page Header End-->
    <?php
}
