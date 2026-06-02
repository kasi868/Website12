<?php
if (!function_exists('h')) {
    function h($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('cms_e')) {
    function cms_e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('value')) {
    function value($array, $key, $default = '')
    {
        return isset($array[$key]) && $array[$key] !== null ? $array[$key] : $default;
    }
}

if (!function_exists('cms_slugify')) {
    function cms_slugify($value)
    {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value);
        return trim((string) $value, '-');
    }
}

if (!function_exists('cms_heading_tag')) {
    function cms_heading_tag($value, $default = 'h2')
    {
        $allowed = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        $value = strtolower(trim((string) $value));
        return in_array($value, $allowed, true) ? $value : $default;
    }
}

if (!function_exists('cms_table_exists')) {
    function cms_table_exists($conn, $tableName)
    {
        $tableName = mysqli_real_escape_string($conn, $tableName);
        $result = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
        return $result && mysqli_num_rows($result) > 0;
    }
}

if (!function_exists('cms_column_exists')) {
    function cms_column_exists($conn, $tableName, $columnName)
    {
        $tableName = mysqli_real_escape_string($conn, $tableName);
        $columnName = mysqli_real_escape_string($conn, $columnName);
        $result = mysqli_query($conn, "SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");
        return $result && mysqli_num_rows($result) > 0;
    }
}

if (!function_exists('cms_add_column_if_missing')) {
    function cms_add_column_if_missing($conn, $tableName, $columnName, $definition)
    {
        if (!cms_column_exists($conn, $tableName, $columnName)) {
            mysqli_query($conn, "ALTER TABLE `$tableName` ADD COLUMN `$columnName` $definition");
        }
    }
}

if (!function_exists('cms_run_schema_updates')) {
    function cms_run_schema_updates($conn)
    {
        static $ran = false;

        if ($ran || !$conn) {
            return;
        }

        $ran = true;

    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `blogs` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `subtitle` varchar(255) DEFAULT NULL,
        `slug` varchar(255) NOT NULL,
        `intro_text` text DEFAULT NULL,
        `content` longtext DEFAULT NULL,
        `conclusion` text DEFAULT NULL,
        `header_image` varchar(255) DEFAULT NULL,
        `header_image_alt` varchar(255) DEFAULT NULL,
        `image` varchar(255) DEFAULT NULL,
        `image_alt` varchar(255) DEFAULT NULL,
        `image2` varchar(255) DEFAULT NULL,
        `image2_alt` varchar(255) DEFAULT NULL,
        `image3` varchar(255) DEFAULT NULL,
        `image3_alt` varchar(255) DEFAULT NULL,
        `meta_title` text DEFAULT NULL,
        `meta_description` text DEFAULT NULL,
        `meta_tags` text DEFAULT NULL,
        `meta_keywords` text DEFAULT NULL,
        `backlinks` text DEFAULT NULL,
        `canonical_url` varchar(255) DEFAULT NULL,
        `title_heading_tag` varchar(10) DEFAULT 'h1',
        `subtitle_heading_tag` varchar(10) DEFAULT 'h2',
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `setting_key` varchar(191) NOT NULL,
        `setting_value` longtext DEFAULT NULL,
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `setting_key` (`setting_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `social_links` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `label` varchar(255) NOT NULL,
        `icon_class` varchar(255) DEFAULT NULL,
        `url` varchar(500) NOT NULL,
        `sort_order` int(11) NOT NULL DEFAULT 0,
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `open_new_tab` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    cms_add_column_if_missing($conn, 'pages', 'header_image', "VARCHAR(255) DEFAULT NULL AFTER `banner_title`");
    cms_add_column_if_missing($conn, 'pages', 'header_image_alt', "VARCHAR(255) DEFAULT NULL AFTER `header_image`");
    cms_add_column_if_missing($conn, 'pages', 'canonical_url', "VARCHAR(255) DEFAULT NULL AFTER `meta_tags`");
    cms_add_column_if_missing($conn, 'pages', 'banner_heading_tag', "VARCHAR(10) DEFAULT 'h1' AFTER `canonical_url`");
    cms_add_column_if_missing($conn, 'pages', 'service_list_heading_tag', "VARCHAR(10) DEFAULT 'h2' AFTER `banner_heading_tag`");
    cms_add_column_if_missing($conn, 'pages', 'sort_order', "INT(11) NOT NULL DEFAULT 0 AFTER `service_list_heading_tag`");
    cms_add_column_if_missing($conn, 'pages', 'is_active', "TINYINT(1) NOT NULL DEFAULT 1 AFTER `sort_order`");
    cms_add_column_if_missing($conn, 'pages', 'template_name', "VARCHAR(50) DEFAULT 'service' AFTER `is_active`");
    cms_add_column_if_missing($conn, 'pages', 'page_type', "VARCHAR(50) DEFAULT 'page' AFTER `template_name`");

    cms_add_column_if_missing($conn, 'blogs', 'canonical_url', "VARCHAR(255) DEFAULT NULL AFTER `backlinks`");
    cms_add_column_if_missing($conn, 'blogs', 'title_heading_tag', "VARCHAR(10) DEFAULT 'h1' AFTER `canonical_url`");
    cms_add_column_if_missing($conn, 'blogs', 'subtitle_heading_tag', "VARCHAR(10) DEFAULT 'h2' AFTER `title_heading_tag`");
    cms_add_column_if_missing($conn, 'blogs', 'updated_at', "TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`");

    cms_add_column_if_missing($conn, 'sections', 'heading_tag', "VARCHAR(10) DEFAULT 'h2' AFTER `sub_heading`");
    cms_add_column_if_missing($conn, 'sections', 'sort_order', "INT(11) NOT NULL DEFAULT 0 AFTER `heading_tag`");
    cms_add_column_if_missing($conn, 'sections', 'image', "VARCHAR(255) DEFAULT NULL AFTER `content`");
    cms_add_column_if_missing($conn, 'sections', 'image_alt', "VARCHAR(255) DEFAULT NULL AFTER `image`");
    cms_add_column_if_missing($conn, 'sections', 'image_position', "VARCHAR(20) DEFAULT 'right' AFTER `image_alt`");
    cms_add_column_if_missing($conn, 'sections', 'layout_style', "VARCHAR(50) DEFAULT 'default' AFTER `image_position`");

    cms_add_column_if_missing($conn, 'service_list', 'sort_order', "INT(11) NOT NULL DEFAULT 0 AFTER `service_name`");
    cms_add_column_if_missing($conn, 'service_list', 'heading_tag', "VARCHAR(10) DEFAULT 'h3' AFTER `sort_order`");
    cms_add_column_if_missing($conn, 'service_list', 'layout_style', "VARCHAR(50) DEFAULT 'default' AFTER `heading_tag`");

    cms_add_column_if_missing($conn, 'section_images', 'alt_text', "VARCHAR(255) DEFAULT NULL AFTER `image`");
    cms_add_column_if_missing($conn, 'section_images', 'caption', "VARCHAR(255) DEFAULT NULL AFTER `alt_text`");

    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `page_slug_redirects` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `page_id` int(11) DEFAULT NULL,
        `old_slug` varchar(255) NOT NULL,
        `new_slug` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `old_slug` (`old_slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
}

if (!function_exists('cms_get_settings')) {
    function cms_get_settings($conn)
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $cache = [];

        if (!cms_table_exists($conn, 'settings')) {
            return $cache;
        }

        $result = mysqli_query($conn, "SELECT setting_key, setting_value FROM settings");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cache[$row['setting_key']] = $row['setting_value'];
            }
        }

        return $cache;
    }
}

if (!function_exists('cms_get_setting')) {
    function cms_get_setting($conn, $key, $default = '')
    {
        $settings = cms_get_settings($conn);
        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }
}

if (!function_exists('cms_set_setting')) {
    function cms_set_setting($conn, $key, $value)
    {
        $key = mysqli_real_escape_string($conn, $key);
        $value = mysqli_real_escape_string($conn, $value);
        mysqli_query($conn, "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value')
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    }
}

if (!function_exists('cms_detect_base_path')) {
    function cms_detect_base_path()
    {
        $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
        // Detect the directory where the script is located relative to document root
        $base = rtrim(str_replace('\\', '/', dirname($script)), '/');

        if ($base === '' || $base === '.') {
            return '';
        }

        $entryPoints = [
            '/config',
            '/includes',
            '/admin',
        ];

        foreach ($entryPoints as $entryPoint) {
            if (substr($base, -strlen($entryPoint)) === $entryPoint) {
                $base = substr($base, 0, -strlen($entryPoint));
                break;
            }
        }
        
        if ($base === '/public_html' || $base === '/htdocs') {
            $base = '';
        }

        return $base === '/' ? '' : $base;
    }
}

if (!function_exists('cms_base_path')) {
    function cms_base_path()
    {
        return defined('BASE_PATH') ? rtrim(BASE_PATH, '/') : cms_detect_base_path();
    }
}

if (!function_exists('cms_base_url')) {
    function cms_base_url($conn = null)
    {
        static $baseUrl = null;

        if ($baseUrl !== null) {
            return $baseUrl;
        }

        $configured = '';
        if ($conn) {
            $configured = trim(cms_get_setting($conn, 'site_base_url', ''));
        }

        if ($configured !== '') {
            $baseUrl = rtrim($configured, '/');
            return $baseUrl;
        }

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
            || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
        $scheme = $isHttps ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $baseUrl = $scheme . '://' . $host . cms_base_path();

        return $baseUrl;
    }
}

if (!function_exists('base_url')) {
    function base_url($path = '')
    {
        $base = cms_base_url();
        $path = trim((string) $path);

        if ($path === '') {
            return $base;
        }

        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('cms_url_path')) {
    function cms_url_path($path = '')
    {
        $path = trim((string) $path);
        $path = $path === '' ? '' : '/' . ltrim($path, '/');
        $basePath = cms_base_path();

        if ($basePath !== '' && strpos($path, $basePath . '/') === 0) {
            return $path;
        }

        return ($basePath !== '' ? $basePath : '') . $path;
    }
}

if (!function_exists('cms_absolute_url')) {
    function cms_absolute_url($path, $conn = null)
    {
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        if (strpos($path, '/') === 0) {
            $base = cms_base_url($conn);
            $parts = parse_url($base);
            $scheme = isset($parts['scheme']) ? $parts['scheme'] : 'http';
            $host = isset($parts['host']) ? $parts['host'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
            $port = isset($parts['port']) ? ':' . $parts['port'] : '';

            return $scheme . '://' . $host . $port . $path;
        }

        return rtrim(cms_base_url($conn), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('cms_current_url')) {
    function cms_current_url($conn = null)
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        return cms_absolute_url($uri, $conn);
    }
}

if (!function_exists('cms_blog_url')) {
    function cms_blog_url($blog, $absolute = false, $conn = null)
    {
        $slug = value($blog, 'slug');
        $path = page_url('blog/' . $slug);
        return $absolute ? cms_absolute_url($path, $conn) : $path;
    }
}

if (!function_exists('page_url')) {
    function page_url($slug)
    {
        $slug = trim((string) $slug);
        $slug = ($slug === 'home') ? '' : $slug;

        if ($slug === '') {
            return cms_url_path('/');
        }

        if (preg_match('#^(https?:)?//#i', $slug) || strpos($slug, '#') === 0 || preg_match('#^(mailto|tel|javascript):#i', $slug)) {
            return $slug;
        }

        $parts = parse_url($slug);
        $path = isset($parts['path']) ? trim($parts['path'], '/') : '';
        $path = preg_replace('/\.php$/i', '', $path);

        if ($path === '') {
            $url = cms_url_path('/');
        } elseif (strpos($path, '/') !== false) {
            $segments = array_map('cms_slugify', explode('/', $path));
            $url = cms_url_path(implode('/', array_filter($segments)));
        } else {
            $url = get_page_url_by_slug($path);
        }

        if (!empty($parts['query'])) {
            $url .= '?' . $parts['query'];
        }

        if (!empty($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }

        return $url;
    }
}

if (!function_exists('cms_link_url')) {
    function cms_link_url($url)
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '#';
        }

        if (preg_match('#^(https?:)?//#i', $url) || strpos($url, '#') === 0 || preg_match('#^(mailto|tel|javascript):#i', $url)) {
            return $url;
        }

        if (strpos($url, '/rioadagency/') === 0) {
            return $url;
        }

        $parts = parse_url($url);
        $path = isset($parts['path']) ? preg_replace('/\.php$/i', '', trim($parts['path'], '/')) : '';

        if ($path === '') {
            return $url;
        }

        $normalized = '';
        global $conn;
        if ($conn && strpos($path, '/') === false) {
            $map = cms_page_link_map($conn);
            $key = cms_slugify($path);
            if (isset($map[$key])) {
                $normalized = $map[$key];
            }
        }

        if ($normalized === '') {
            $normalized = page_url($path);
        }
        if (!empty($parts['query'])) {
            $normalized .= '?' . $parts['query'];
        }
        if (!empty($parts['fragment'])) {
            $normalized .= '#' . $parts['fragment'];
        }

        return $normalized;
    }
}

if (!function_exists('cms_page_url')) {
    function cms_page_url($slug, $absolute = false, $conn = null)
    {
        $slug = trim((string) $slug, '/');

        $path = cms_url_path($slug === '' || $slug === 'index' ? '' : cms_slugify($slug));

        return $absolute ? cms_absolute_url($path, $conn) : $path;
    }
}

if (!function_exists('cms_page_select_columns')) {
    function cms_page_select_columns(mysqli $conn)
    {
        static $columns = null;

        if ($columns !== null) {
            return $columns;
        }

        $columns = '*';
        if (cms_column_exists($conn, 'pages', 'updated_at') && cms_column_exists($conn, 'pages', 'created_at')) {
            $columns = '*';
        }

        return $columns;
    }
}

if (!function_exists('cms_page_status_sql')) {
    function cms_page_status_sql(mysqli $conn, $alias = '')
    {
        $prefix = $alias !== '' ? $alias . '.' : '';
        return cms_column_exists($conn, 'pages', 'is_active') ? " AND IFNULL({$prefix}is_active, 1) = 1" : '';
    }
}

if (!function_exists('cms_fetch_page_by_id')) {
    function cms_fetch_page_by_id(mysqli $conn, $pageId)
    {
        $pageId = (int) $pageId;
        $result = mysqli_query($conn, "SELECT * FROM pages WHERE id=$pageId LIMIT 1");

        return $result ? mysqli_fetch_assoc($result) : null;
    }
}

if (!function_exists('cms_fetch_page_by_slug')) {
    function cms_fetch_page_by_slug(mysqli $conn, $slug, $activeOnly = false)
    {
        $slug = mysqli_real_escape_string($conn, cms_slugify($slug));
        $statusSql = $activeOnly ? cms_page_status_sql($conn) : '';
        $result = mysqli_query($conn, "SELECT * FROM pages WHERE slug='$slug'$statusSql LIMIT 1");

        return $result ? mysqli_fetch_assoc($result) : null;
    }
}

if (!function_exists('cms_fetch_page_by_template')) {
    function cms_fetch_page_by_template(mysqli $conn, $template, $activeOnly = true)
    {
        $template = cms_normalize_template_name($template);
        $aliases = [$template];
        if ($template === 'blog') {
            $aliases[] = 'blogs';
        }
        if ($template === 'terms-conditions') {
            $aliases[] = 'terms';
        }

        $aliases = array_unique(array_map(function ($alias) use ($conn) {
            return "'" . mysqli_real_escape_string($conn, cms_slugify($alias)) . "'";
        }, $aliases));
        
        if (!cms_column_exists($conn, 'pages', 'template_name')) {
            return cms_fetch_page_by_slug($conn, $template, $activeOnly);
        }

        $statusSql = $activeOnly ? cms_page_status_sql($conn) : '';
        $result = mysqli_query($conn, "SELECT * FROM pages WHERE template_name IN (" . implode(',', $aliases) . ")$statusSql ORDER BY sort_order ASC, id ASC LIMIT 1");

        return $result ? mysqli_fetch_assoc($result) : null;
    }
}

if (!function_exists('cms_normalize_template_name')) {
    function cms_normalize_template_name($template)
    {
        $template = cms_slugify($template);
        $map = [
            'index' => 'home',
            'blogs' => 'blog',
            'terms' => 'terms-conditions',
            'terms-conditions' => 'terms-conditions',
        ];

        return isset($map[$template]) ? $map[$template] : $template;
    }
}

if (!function_exists('cms_template_file_map')) {
    function cms_template_file_map()
    {
        return [
            'home' => 'index.php',
            'about' => 'about.php',
            'portfolio' => 'portfolio.php',
            'contact' => 'contact.php',
            'web' => 'web.php',
            'seo' => 'seo.php',
            'social' => 'social.php',
            'graphic' => 'graphic.php',
            'brand' => 'brand.php',
            'industrial' => 'industrial.php',
            'influence' => 'influence.php',
            'product' => 'product.php',
            'privacy' => 'privacy.php',
            'terms-conditions' => 'terms-conditions.php',
            'blog' => 'blogs.php',
            'blogs' => 'blogs.php',
            'google' => 'google.php',
        ];
    }
}

if (!function_exists('cms_page_template')) {
    function cms_page_template($page)
    {
        return cms_normalize_template_name(value($page, 'template_name', 'page'));
    }
}

if (!function_exists('cms_template_file')) {
    function cms_template_file($template)
    {
        $template = cms_normalize_template_name($template);
        $map = cms_template_file_map();

        return isset($map[$template]) ? $map[$template] : 'dynamic-page.php';
    }
}

if (!function_exists('cms_fetch_pages')) {
    function cms_fetch_pages(mysqli $conn, $activeOnly = true)
    {
        $statusSql = $activeOnly ? "WHERE IFNULL(is_active, 1) = 1" : '';
        if (!cms_column_exists($conn, 'pages', 'is_active')) {
            $statusSql = '';
        }

        return fetch_all_assoc(mysqli_query($conn, "SELECT * FROM pages $statusSql ORDER BY sort_order ASC, id ASC"));
    }
}

if (!function_exists('cms_navigation_pages')) {
    function cms_navigation_pages(mysqli $conn, $group = 'main')
    {
        $group = $group === 'service' ? 'service' : 'main';

        if (!cms_table_exists($conn, 'pages')) {
            return [];
        }

        $hasTpl = cms_column_exists($conn, 'pages', 'template_name');
        $hasType = cms_column_exists($conn, 'pages', 'page_type');
        $hasActive = cms_column_exists($conn, 'pages', 'is_active');
        $hasOrder = cms_column_exists($conn, 'pages', 'sort_order');

        $templateColumn = $hasTpl ? 'template_name' : "'' AS template_name";
        $pageTypeColumn = $hasType ? 'page_type' : "'' AS page_type";
        $statusSql = $hasActive ? 'WHERE IFNULL(is_active, 1) = 1' : 'WHERE 1=1';
        $orderSql = $hasOrder ? 'ORDER BY sort_order ASC, id ASC' : 'ORDER BY id ASC';

        $query = "SELECT id, slug, page_name, $templateColumn, $pageTypeColumn FROM pages $statusSql $orderSql";
        $res = mysqli_query($conn, $query);
        
        if (!$res) {
            error_log("CMS Navigation Query Failed: " . mysqli_error($conn));
            // Emergency fallbacks if DB is completely broken
            return $group === 'main' ? [
                ['slug' => '', 'label' => 'Home', 'template_name' => 'home'],
                ['slug' => 'about', 'label' => 'About Us', 'template_name' => 'about'],
                ['slug' => 'portfolio', 'label' => 'Portfolio', 'template_name' => 'portfolio'],
                ['slug' => 'blogs', 'label' => 'Blogs', 'template_name' => 'blog'],
                ['slug' => 'contact', 'label' => 'Contact Us', 'template_name' => 'contact']
            ] : [];
        }

        $pages = fetch_all_assoc($res);
        $items = [];
        
        if ($group === 'main') {
            $order = ['home', 'about', 'portfolio', 'blog', 'contact'];
            $labels = ['home' => 'Home', 'about' => 'About Us', 'portfolio' => 'Portfolio', 'blog' => 'Blogs', 'contact' => 'Contact Us'];
            $map = [];

            foreach ($pages as $p) {
                $tpl = cms_normalize_template_name(value($p, 'template_name'));
                if (in_array($tpl, $order)) {
                    $map[$tpl] = [
                        'id' => $p['id'],
                        'slug' => $p['slug'],
                        'label' => $labels[$tpl],
                        'template_name' => $tpl
                    ];
                }
            }

            foreach ($order as $tpl) {
                if (isset($map[$tpl])) $items[] = $map[$tpl];
            }
        } else {
            // Service Navigation
            $serviceTemplates = ['web', 'seo', 'social', 'influence', 'google', 'product', 'industrial', 'brand', 'graphic', 'video'];
            $exclude = ['home', 'about', 'portfolio', 'blog', 'blogs', 'contact', 'privacy', 'terms', 'terms-conditions'];
            
            foreach ($pages as $p) {
                $tpl = cms_normalize_template_name(value($p, 'template_name'));
                $type = strtolower(value($p, 'page_type'));
                
                if (in_array($tpl, $serviceTemplates) || ($type === 'service' && !in_array($tpl, $exclude))) {
                    $items[] = [
                        'id' => $p['id'],
                        'slug' => $p['slug'],
                        'label' => $p['page_name'],
                        'template_name' => $tpl
                    ];
                }
            }
        }

        return $items;
    }
}

if (!function_exists('get_home_page')) {
    function get_home_page($conn = null)
    {
        if (!$conn) global $conn;
        $home = cms_fetch_page_by_template($conn, 'home', true);
        if (!$home) {
            error_log("CMS Routing Warning: Home page not found by template. Falling back to slug 'home'.");
            $home = cms_fetch_page_by_slug($conn, 'home', true);
        }
        return $home;
    }
}

if (!function_exists('get_home_url')) {
    function get_home_url($conn = null)
    {
        $home = get_home_page($conn);
        return $home ? page_url($home['slug']) : cms_url_path('/');
    }
}

if (!function_exists('get_page_url')) {
    function get_page_url($pageId, $absolute = false, $conn = null)
    {
        if (!$conn) {
            global $conn;
        }

        if (!$conn) {
            return '#';
        }

        $page = cms_fetch_page_by_id($conn, $pageId);
        return $page ? cms_page_url(value($page, 'slug'), $absolute, $conn) : '#';
    }
}

if (!function_exists('get_page_url_by_slug')) {
    function get_page_url_by_slug($slug, $absolute = false, $conn = null)
    {
        if (!$conn) {
            global $conn;
        }

        $slug = cms_slugify($slug);
        if ($slug === '' || $slug === 'index') {
            return cms_page_url('', $absolute, $conn);
        }

        if ($conn) {
            $page = cms_fetch_page_by_slug($conn, $slug, true);
            if ($page) {
                return cms_page_url(value($page, 'slug'), $absolute, $conn);
            }

            $redirect = cms_resolve_page_redirect($conn, $slug);
            if ($redirect) {
                return cms_page_url(value($redirect, 'new_slug'), $absolute, $conn);
            }
        }

        return cms_page_url($slug, $absolute, $conn);
    }
}

if (!function_exists('get_page_url_by_template')) {
    function get_page_url_by_template($template, $absolute = false, $conn = null)
    {
        if (!$conn) {
            global $conn;
        }

        if (!$conn) {
            return '#';
        }

        $page = cms_fetch_page_by_template($conn, $template, true);
        return $page ? cms_page_url(value($page, 'slug'), $absolute, $conn) : '#';
    }
}

if (!function_exists('get_page_by_slug')) {
    function get_page_by_slug($slug, $activeOnly = true, $conn = null)
    {
        if (!$conn) {
            global $conn;
        }

        return $conn ? cms_fetch_page_by_slug($conn, $slug, $activeOnly) : null;
    }
}

if (!function_exists('get_page_by_template')) {
    function get_page_by_template($templateName, $activeOnly = true, $conn = null)
    {
        if (!$conn) {
            global $conn;
        }

        return $conn ? cms_fetch_page_by_template($conn, $templateName, $activeOnly) : null;
    }
}

if (!function_exists('cms_active_page')) {
    function cms_active_page()
    {
        return isset($GLOBALS['cmsCurrentPage']) && is_array($GLOBALS['cmsCurrentPage']) ? $GLOBALS['cmsCurrentPage'] : null;
    }
}

if (!function_exists('cms_page_slug_candidates')) {
    function cms_page_slug_candidates(mysqli $conn, $pageSlug)
    {
        $pageSlug = cms_slugify($pageSlug);
        $candidates = [];
        $activePage = cms_active_page();
        $activeTemplate = $activePage ? cms_page_template($activePage) : '';

        if ($activePage && ($pageSlug === value($activePage, 'slug') || $pageSlug === $activeTemplate)) {
            $candidates[] = value($activePage, 'slug');
        }

        $templatePage = cms_fetch_page_by_template($conn, $pageSlug, true);
        if ($templatePage) {
            $candidates[] = value($templatePage, 'slug');
        }

        $candidates[] = $pageSlug;

        return array_values(array_unique(array_filter($candidates)));
    }
}

if (!function_exists('cms_resolve_page_redirect')) {
    function cms_resolve_page_redirect(mysqli $conn, $oldSlug)
    {
        if (!cms_table_exists($conn, 'page_slug_redirects')) {
            return null;
        }

        $oldSlug = mysqli_real_escape_string($conn, cms_slugify($oldSlug));
        $result = mysqli_query($conn, "SELECT * FROM page_slug_redirects WHERE old_slug='$oldSlug' LIMIT 1");

        return $result ? mysqli_fetch_assoc($result) : null;
    }
}

if (!function_exists('cms_save_page_redirect')) {
    function cms_save_page_redirect(mysqli $conn, $pageId, $oldSlug, $newSlug)
    {
        $oldSlug = cms_slugify($oldSlug);
        $newSlug = cms_slugify($newSlug);

        if ($oldSlug === '' || $newSlug === '' || $oldSlug === $newSlug) {
            return;
        }

        cms_run_schema_updates($conn);

        $pageId = (int) $pageId;
        $oldSlugSql = mysqli_real_escape_string($conn, $oldSlug);
        $newSlugSql = mysqli_real_escape_string($conn, $newSlug);

        mysqli_query($conn, "INSERT INTO page_slug_redirects (page_id, old_slug, new_slug)
            VALUES ($pageId, '$oldSlugSql', '$newSlugSql')
            ON DUPLICATE KEY UPDATE page_id=VALUES(page_id), new_slug=VALUES(new_slug)");
        mysqli_query($conn, "UPDATE page_slug_redirects SET new_slug='$newSlugSql' WHERE new_slug='$oldSlugSql'");
    }
}

if (!function_exists('cms_update_related_page_slugs')) {
    function cms_update_related_page_slugs(mysqli $conn, $oldSlug, $newSlug)
    {
        $oldSlug = cms_slugify($oldSlug);
        $newSlug = cms_slugify($newSlug);

        if ($oldSlug === '' || $newSlug === '' || $oldSlug === $newSlug) {
            return;
        }

        $oldSlugSql = mysqli_real_escape_string($conn, $oldSlug);
        $newSlugSql = mysqli_real_escape_string($conn, $newSlug);
        $tables = ['sections', 'section_images', 'service_list', 'pricing_packages', 'faqs'];

        foreach ($tables as $table) {
            if (cms_table_exists($conn, $table) && cms_column_exists($conn, $table, 'page_slug')) {
                mysqli_query($conn, "UPDATE `$table` SET page_slug='$newSlugSql' WHERE page_slug='$oldSlugSql'");
            }
        }
    }
}

if (!function_exists('cms_page_link_map')) {
    function cms_page_link_map(mysqli $conn)
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $homePage = cms_fetch_page_by_template($conn, 'home', true);
        $homeUrl = $homePage ? cms_page_url(value($homePage, 'slug')) : cms_url_path('/');
        $cache = [
            'index' => $homeUrl,
            'home' => $homeUrl,
        ];

        if (cms_table_exists($conn, 'pages')) {
            foreach (cms_fetch_pages($conn, false) as $page) {
                $slug = value($page, 'slug');
                if ($slug !== '') {
                    $cache[$slug] = cms_page_url($slug);
                }
                $template = cms_page_template($page);
                if ($template !== '') {
                    $cache[$template] = cms_page_url($slug);
                }
            }
        }

        if (cms_table_exists($conn, 'page_slug_redirects')) {
            $redirects = mysqli_query($conn, "SELECT old_slug, new_slug FROM page_slug_redirects");
            if ($redirects) {
                while ($redirect = mysqli_fetch_assoc($redirects)) {
                    $cache[value($redirect, 'old_slug')] = get_page_url_by_slug(value($redirect, 'new_slug'), false, $conn);
                }
            }
        }

        return $cache;
    }
}

if (!function_exists('cms_rewrite_internal_page_links')) {
    function cms_rewrite_internal_page_links($html, $conn = null)
    {
        if (!$conn) {
            global $conn;
        }

        if (!$conn || trim((string) $html) === '') {
            return $html;
        }

        $map = cms_page_link_map($conn);

        return preg_replace_callback('/\bhref=(["\'])([^"\']+)\1/i', function ($matches) use ($map) {
            $quote = $matches[1];
            $href = $matches[2];

            if (preg_match('#^(https?:)?//#i', $href) || strpos($href, '#') === 0 || preg_match('#^(mailto|tel):#i', $href)) {
                return $matches[0];
            }

            $parts = parse_url($href);
            $path = isset($parts['path']) ? trim($parts['path'], '/') : '';
            $path = preg_replace('/\.php$/i', '', $path);

            if ($path === '' || strpos($path, '/') !== false || !isset($map[$path])) {
                return $matches[0];
            }

            $newHref = $map[$path];
            if (!empty($parts['query'])) {
                $newHref .= '?' . $parts['query'];
            }
            if (!empty($parts['fragment'])) {
                $newHref .= '#' . $parts['fragment'];
            }

            return 'href=' . $quote . $newHref . $quote;
        }, $html);
    }
}

if (!function_exists('cms_canonical_url')) {
    function cms_canonical_url($entity, $conn = null)
    {
        $canonical = trim((string) value($entity, 'canonical_url'));
        if ($canonical !== '') {
            return preg_match('#^https?://#i', $canonical) ? $canonical : cms_absolute_url($canonical, $conn);
        }

        return cms_current_url($conn);
    }
}

if (!function_exists('cms_get_social_links')) {
    function cms_get_social_links($conn)
    {
        $links = [];

        if (!cms_table_exists($conn, 'social_links')) {
            return $links;
        }

        $result = mysqli_query($conn, "SELECT * FROM social_links WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $links[] = $row;
            }
        }

        return $links;
    }
}

if (!function_exists('cms_generate_sitemap')) {
    function cms_generate_sitemap($conn, $targetFile = null)
    {
        $baseUrl = cms_base_url($conn);
        $items = [];
        $items[] = ['loc' => $baseUrl . '/', 'changefreq' => 'weekly', 'priority' => '1.0'];

        if (cms_table_exists($conn, 'pages')) {
            $pages = mysqli_query($conn, "SELECT slug, updated_at, created_at FROM pages WHERE IFNULL(is_active, 1) = 1 ORDER BY sort_order ASC, id ASC");
            if ($pages) {
                while ($page = mysqli_fetch_assoc($pages)) {
                    $items[] = [
                        'loc' => get_page_url_by_slug(value($page, 'slug'), true, $conn),
                        'lastmod' => date('c', strtotime(value($page, 'updated_at', value($page, 'created_at', 'now')))),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                }
            }
        }

        if (cms_table_exists($conn, 'blogs')) {
            $blogs = mysqli_query($conn, "SELECT slug, updated_at, created_at FROM blogs ORDER BY created_at DESC");
            if ($blogs) {
                while ($blog = mysqli_fetch_assoc($blogs)) {
                    $items[] = [
                        'loc' => cms_blog_url($blog, true, $conn),
                        'lastmod' => date('c', strtotime(value($blog, 'updated_at', value($blog, 'created_at', 'now')))),
                        'changefreq' => 'monthly',
                        'priority' => '0.7',
                    ];
                }
            }
        }

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($items as $item) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . cms_e($item['loc']) . "</loc>\n";
            if (!empty($item['lastmod'])) {
                $xml .= "    <lastmod>" . cms_e($item['lastmod']) . "</lastmod>\n";
            }
            $xml .= "    <changefreq>" . cms_e($item['changefreq']) . "</changefreq>\n";
            $xml .= "    <priority>" . cms_e($item['priority']) . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        if ($targetFile === null) {
            $targetFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'sitemap.xml';
        }

        file_put_contents($targetFile, $xml);

        return $xml;
    }
}

if (!function_exists('cms_write_robots_txt')) {
    function cms_write_robots_txt($conn, $targetFile = null)
    {
        $default = "User-agent: *\nAllow: /\n\nSitemap: " . cms_absolute_url('sitemap.xml', $conn) . "\n";
        $content = trim((string) cms_get_setting($conn, 'robots_txt_content', $default));

        if (stripos($content, 'Sitemap:') === false) {
            $content .= "\n\nSitemap: " . cms_absolute_url('sitemap.xml', $conn);
        }

        if ($targetFile === null) {
            $targetFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'robots.txt';
        }

        file_put_contents($targetFile, $content . "\n");

        return $content;
    }
}

if (!function_exists('fetch_all_assoc')) {
    function fetch_all_assoc($result)
    {
        $rows = [];
        if ($result instanceof mysqli_result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}

if (!function_exists('fetch_page')) {
    function fetch_page(mysqli $conn, $slug)
    {
        $page = cms_fetch_page_by_slug($conn, $slug);
        if ($page) {
            return $page;
        }

        return cms_fetch_page_by_template($conn, $slug, false);
    }
}

if (!function_exists('fetch_section')) {
    function fetch_section(mysqli $conn, $pageSlug, $sectionKey)
    {
        $sectionKey = mysqli_real_escape_string($conn, $sectionKey);
        foreach (cms_page_slug_candidates($conn, $pageSlug) as $candidateSlug) {
            $safeSlug = mysqli_real_escape_string($conn, $candidateSlug);
            $result = mysqli_query($conn, "SELECT * FROM sections WHERE page_slug='$safeSlug' AND section_key='$sectionKey' LIMIT 1");
            if ($result && mysqli_num_rows($result) > 0) {
                return mysqli_fetch_assoc($result);
            }
        }

        return null;
    }
}

if (!function_exists('fetch_sections')) {
    function fetch_sections(mysqli $conn, $pageSlug)
    {
        foreach (cms_page_slug_candidates($conn, $pageSlug) as $candidateSlug) {
            $safeSlug = mysqli_real_escape_string($conn, $candidateSlug);
            $rows = fetch_all_assoc(mysqli_query($conn, "SELECT * FROM sections WHERE page_slug='$safeSlug' ORDER BY sort_order ASC, id ASC"));
            if ($rows) {
                return $rows;
            }
        }

        return [];
    }
}

if (!function_exists('fetch_images')) {
    function fetch_images(mysqli $conn, $pageSlug, $sectionKey)
    {
        $sectionKey = mysqli_real_escape_string($conn, $sectionKey);
        foreach (cms_page_slug_candidates($conn, $pageSlug) as $candidateSlug) {
            $safeSlug = mysqli_real_escape_string($conn, $candidateSlug);
            $rows = fetch_all_assoc(mysqli_query($conn, "SELECT * FROM section_images WHERE page_slug='$safeSlug' AND section_key='$sectionKey' ORDER BY position ASC, id ASC"));
            if ($rows) {
                return $rows;
            }
        }

        return [];
    }
}

if (!function_exists('fetch_image')) {
    function fetch_image(mysqli $conn, $pageSlug, $sectionKey, $position = null)
    {
        $images = fetch_images($conn, $pageSlug, $sectionKey);
        if ($position === null) {
            return $images ? $images[0] : null;
        }

        foreach ($images as $image) {
            if ((int) value($image, 'position') === (int) $position) {
                return $image;
            }
        }

        return null;
    }
}

if (!function_exists('fetch_services')) {
    function fetch_services(mysqli $conn, $pageSlug)
    {
        foreach (cms_page_slug_candidates($conn, $pageSlug) as $candidateSlug) {
            $safeSlug = mysqli_real_escape_string($conn, $candidateSlug);
            $rows = fetch_all_assoc(mysqli_query($conn, "SELECT * FROM service_list WHERE page_slug='$safeSlug' ORDER BY sort_order ASC, id ASC"));
            if ($rows) {
                return $rows;
            }
        }

        return [];
    }
}

if (!function_exists('fetch_faqs')) {
    function fetch_faqs(mysqli $conn, $pageSlug)
    {
        foreach (cms_page_slug_candidates($conn, $pageSlug) as $candidateSlug) {
            $safeSlug = mysqli_real_escape_string($conn, $candidateSlug);
            $rows = fetch_all_assoc(mysqli_query($conn, "SELECT * FROM faqs WHERE page_slug='$safeSlug' ORDER BY position ASC, id ASC"));
            if ($rows) {
                return $rows;
            }
        }

        return [];
    }
}

if (!function_exists('fetch_testimonials')) {
    function fetch_testimonials(mysqli $conn)
    {
        return fetch_all_assoc(mysqli_query($conn, "SELECT * FROM testimonials ORDER BY position ASC, created_at DESC, id DESC"));
    }
}

if (!function_exists('fetch_blogs')) {
    function fetch_blogs(mysqli $conn, $limit = null)
    {
        $sql = "SELECT * FROM blogs ORDER BY created_at DESC, id DESC";
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }

        return fetch_all_assoc(mysqli_query($conn, $sql));
    }
}

if (!function_exists('fetch_blog')) {
    function fetch_blog(mysqli $conn, $slug)
    {
        $slug = mysqli_real_escape_string($conn, $slug);
        $result = mysqli_query($conn, "SELECT * FROM blogs WHERE slug='$slug' LIMIT 1");

        return $result ? mysqli_fetch_assoc($result) : null;
    }
}

if (!function_exists('media_url')) {
    function media_url($path)
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (preg_match('/^(https?:)?\/\//i', $path) || preg_match('/^(data|blob):/i', $path)) {
            return $path;
        }

        $relativePath = $path;
        // If it already starts with uploads/, use that
        if (strpos($path, 'uploads/') === 0) {
            $relativePath = $path;
        } 
        // If it's just a filename (no slashes), assume it's in uploads/blogs
        elseif (strpos($path, '/') === false && !preg_match('/\.(html|css|js)$/i', $path)) {
            // Check if it looks like an uploaded file (has timestamp prefix)
            if (preg_match('/^\d+_/', $path)) {
                $relativePath = 'uploads/blogs/' . $path;
            }
        }

        return base_url($relativePath);
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path)
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (preg_match('/^(https?:)?\/\//i', $path) || preg_match('/^(data|blob):/i', $path)) {
            return $path;
        }

        return base_url($path);
    }
}

if (!function_exists('page_file')) {
    function page_file($slug)
    {
        $url = get_page_url_by_template($slug);
        return $url !== '#' ? $url : get_page_url_by_slug($slug);
    }
}

if (!function_exists('blog_file')) {
    function blog_file($slug)
    {
        return page_url('blog/' . $slug);
    }
}

if (!function_exists('render_content')) {
    function render_content($content)
    {
        return $content !== '' ? cms_rewrite_internal_page_links($content) : '';
    }
}

if (!function_exists('excerpt_text')) {
    function excerpt_text($html, $length = 120)
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $html)));
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $length - 3)) . '...';
    }
}

if (!function_exists('current_year')) {
    function current_year()
    {
        return date('Y');
    }
}
?>
