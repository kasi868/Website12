<header class="main-header-two clearfix">
    <nav class="main-menu main-menu-two clearfix">
        <div class="main-menu-two-wrapper clearfix">
            <div class="main-menu-two-wrapper__left">
                <div class="main-menu-two-wrapper__logo">
                    <a href="<?= h(page_url('home')) ?>"><img src="<?= h(media_url('assets/images/resources/rio-ad.png')) ?>" alt="RIO AD Agency"></a>
                </div>
                <div class="main-menu-two-wrapper__main-menu">
                    <a href="#" class="mobile-nav__toggler">
                        <span></span>
                    </a>
                    <ul class="main-menu__list">
                        <?php
                        $mainNavItems = [];
                        foreach (nav_items() as $item) {
                            if (value($item, 'slug') === 'home') {
                                continue;
                            }
                            $mainNavItems[] = $item;
                        }

                        $firstMainItem = array_shift($mainNavItems);
                        if ($firstMainItem) {
                        ?>
                            <li class=" ">
                                <a href="<?= h(page_url($firstMainItem['slug'])) ?>"><?= h($firstMainItem['label']) ?></a>
                            </li>
                        <?php } ?>
                        <li class="dropdown">
                            <a href="#">Services </a>
                            <ul>
                                <?php foreach (service_nav_items() as $item) { ?>
                                    <li><a href="<?= h(page_url($item['slug'])) ?>"><?= h($item['label']) ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php foreach ($mainNavItems as $item) { ?>
                            <li class=" ">
                                <a href="<?= h(page_url($item['slug'])) ?>"><?= h($item['label']) ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="main-menu-two-wrapper__right">
                <div class="main-menu-two-wrapper__social">
                    <a href="https://youtube.com/@rioadagency?si=o4JHWJ_imjoDoDMe"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.facebook.com/profile.php?id=61580874127741" class="clr-fb"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.whatsapp.com/?lang=en" class="clr-dri"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.instagram.com/rioadagency/?hl=en" class="clr-ins"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Sticky Header Overlay -->
<div class="stricky-header stricked-menu main-menu main-menu-two ">
    <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
</div><!-- /.stricky-header -->
