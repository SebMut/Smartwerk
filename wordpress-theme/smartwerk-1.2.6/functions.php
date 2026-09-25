<?php
if (!defined('ABSPATH')) {
    exit;
}

function smartwerk_theme_setup(): void {
    load_theme_textdomain('smartwerk', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 360,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Hauptnavigation', 'smartwerk'),
        'footer'  => __('Footer Navigation', 'smartwerk'),
    ]);
}
add_action('after_setup_theme', 'smartwerk_theme_setup');

function smartwerk_assets(): void {
    $version      = wp_get_theme()->get('Version');
    $style_file   = get_stylesheet_directory() . '/style.css';
    $script_file  = get_template_directory() . '/assets/js/theme.js';
    $style_ver    = is_readable($style_file) ? $version . '.' . filemtime($style_file) : $version;
    $script_ver   = is_readable($script_file) ? $version . '.' . filemtime($script_file) : $version;

    wp_enqueue_style(
        'smartwerk-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'smartwerk-style',
        get_stylesheet_uri(),
        [],
        $style_ver
    );

    wp_enqueue_script(
        'smartwerk-theme',
        get_template_directory_uri() . '/assets/js/theme.js',
        [],
        $script_ver,
        true
    );

    wp_localize_script(
        'smartwerk-theme',
        'smartwerkTheme',
        [
            'storeCartUrl' => esc_url_raw(rest_url('wc/store/v1/cart')),
        ]
    );
}
add_action('wp_enqueue_scripts', 'smartwerk_assets');

function smartwerk_async_font_stylesheet(string $html, string $handle, string $href, string $media): string {
    if ($handle !== 'smartwerk-fonts') {
        return $html;
    }

    $url = esc_url($href);

    return sprintf(
        '<link rel="preload" as="style" href="%1$s" onload="this.onload=null;this.rel=\'stylesheet\'"><noscript><link rel="stylesheet" href="%1$s"></noscript>' . "\n",
        $url
    );
}
add_filter('style_loader_tag', 'smartwerk_async_font_stylesheet', 10, 4);

function smartwerk_resource_hints(array $urls, string $relation_type): array {
    if ($relation_type === 'preconnect') {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = [
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        ];
    }

    return $urls;
}
add_filter('wp_resource_hints', 'smartwerk_resource_hints', 10, 2);

function smartwerk_remove_frontend_overhead(): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles');
}
add_action('init', 'smartwerk_remove_frontend_overhead', 20);

function smartwerk_frontpage_asset_cleanup(): void {
    if (!is_front_page()) {
        return;
    }

    foreach ([
        'wp-block-library',
        'wp-block-library-theme',
        'global-styles',
        'classic-theme-styles',
        'woocommerce-general',
        'woocommerce-layout',
        'woocommerce-smallscreen',
        'wc-blocks-style',
        'wc-blocks-packages-style',
    ] as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
    }

    foreach ([
        'wc-add-to-cart',
        'woocommerce',
        'wc-cart-fragments',
        'wc-add-to-cart-variation',
        'wc-single-product',
        'wc-checkout',
        'wc-country-select',
        'wc-address-i18n',
        'wp-embed',
    ] as $handle) {
        wp_dequeue_script($handle);
    }

    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
    }
}
add_action('wp_enqueue_scripts', 'smartwerk_frontpage_asset_cleanup', 100);

function smartwerk_page_key(): string {
    if (is_front_page()) {
        return 'home';
    }

    if (function_exists('is_cart') && is_cart()) {
        return 'warenkorb';
    }

    if (function_exists('is_checkout') && is_checkout()) {
        return 'checkout';
    }

    if (function_exists('is_shop') && is_shop()) {
        return 'shop';
    }

    if (function_exists('is_product') && is_product()) {
        return 'shop';
    }

    if (is_page()) {
        $slug = (string) get_post_field('post_name', get_queried_object_id());

        $map = [
            'smartwerk-transfer-startseite' => 'home',
            'smartwerk-startseite-neu'      => 'home',
            '3d-druck-bestellen'            => '3d-druck',
            '3d-druck-nach-mass'            => '3d-druck',
            '3d-druck-nach-mas'             => '3d-druck',
            'individuelle-anfertigung'      => 'individuell',
            'smartwerk-shop'                => 'shop',
            'projekte'                      => 'projekte',
            'stl-dateien'                   => 'stl-dateien',
            'stl-dateien-finden'            => 'stl-dateien',
            'kontakt'                       => 'kontakt',
            'warenkorb'                     => 'warenkorb',
            'kasse'                         => 'checkout',
            'mein-konto'                    => 'account',
            'materialien-qualitaet'         => 'materialien',
            'impressum'                     => 'legal',
            'datenschutzerklaerung'          => 'legal',
            'datenschutz'                    => 'legal',
            'agb'                           => 'legal',
        ];

        return $map[$slug] ?? sanitize_html_class($slug ?: 'page');
    }

    return 'site';
}

function smartwerk_page_layout_mode(): string {
    if (!is_page()) {
        return 'transfer';
    }

    if ((function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout())) {
        return 'commerce';
    }

    $page_id = (int) get_queried_object_id();

    $transfer_pages = [1981, 1628, 1636, 1638, 1640, 1642, 1649, 1657];
    $commerce_pages = [12, 13, 14];

    if (in_array($page_id, $transfer_pages, true)) {
        return 'transfer';
    }

    if (in_array($page_id, $commerce_pages, true)) {
        return 'commerce';
    }

    return 'boxed';
}

function smartwerk_body_classes(array $classes): array {
    $classes[] = 'smartwerk-theme';
    return $classes;
}
add_filter('body_class', 'smartwerk_body_classes');

function smartwerk_logo_url(): string {
    $custom_logo_id = get_theme_mod('custom_logo');

    if ($custom_logo_id) {
        $logo = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($logo) {
            return $logo;
        }
    }

    return 'https://www.smartwerk.art/wp-content/uploads/2025/12/smartwerk_logo.png';
}

function smartwerk_cart_count(): int {
    if (function_exists('WC') && WC()->cart) {
        return (int) WC()->cart->get_cart_contents_count();
    }

    return 0;
}

function smartwerk_cart_url(): string {
    if (function_exists('wc_get_cart_url')) {
        return wc_get_cart_url();
    }

    return home_url('/warenkorb/');
}

function smartwerk_cart_count_markup(): string {
    $count = smartwerk_cart_count();

    return sprintf(
        '<span class="cart-count" data-cart-count%s>%s</span>',
        $count === 0 ? ' hidden' : '',
        esc_html((string) $count)
    );
}

function smartwerk_cart_count_fragment(array $fragments): array {
    $fragments['span.cart-count'] = smartwerk_cart_count_markup();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'smartwerk_cart_count_fragment');

function smartwerk_primary_items(): array {
    return [
        ['label' => '3D-Druck',   'url' => home_url('/3d-druck-bestellen/'),       'key' => '3d-druck',   'class' => ''],
        ['label' => 'Individuell','url' => home_url('/individuelle-anfertigung/'), 'key' => 'individuell','class' => ''],
        ['label' => 'Shop',       'url' => home_url('/smartwerk-shop/'),            'key' => 'shop',       'class' => 'nav-shop'],
        ['label' => 'Projekte',   'url' => home_url('/projekte/'),                  'key' => 'projekte',   'class' => ''],
        ['label' => 'Wissen & STL','url'=> home_url('/stl-dateien/'),               'key' => 'stl-dateien','class' => ''],
    ];
}

function smartwerk_primary_fallback(): void {
    $current = smartwerk_page_key();

    echo '<ul class="desktop-menu smartwerk-fallback-menu">';

    foreach (smartwerk_primary_items() as $item) {
        $link_classes = trim($item['class'] . ' ' . ($current === $item['key'] ? 'is-active' : ''));

        printf(
            '<li class="menu-item"><a href="%1$s" class="%2$s">%3$s</a></li>',
            esc_url($item['url']),
            esc_attr($link_classes),
            esc_html($item['label'])
        );
    }

    echo '</ul>';
}

function smartwerk_mobile_fallback(): void {
    $current = smartwerk_page_key();

    echo '<ul class="mobile-menu smartwerk-fallback-menu">';

    foreach (smartwerk_primary_items() as $item) {
        $link_classes = trim($item['class'] . ' ' . ($current === $item['key'] ? 'is-active' : ''));

        printf(
            '<li class="menu-item"><a href="%1$s" class="%2$s">%3$s</a></li>',
            esc_url($item['url']),
            esc_attr($link_classes),
            esc_html($item['label'])
        );
    }

    echo '</ul>';
}

function smartwerk_menu_link_attributes(array $atts, $item, $args): array {
    if (!empty($args->theme_location) && $args->theme_location === 'primary') {
        $classes = isset($atts['class']) ? explode(' ', (string) $atts['class']) : [];

        if (stripos((string) $item->title, 'Shop') !== false) {
            $classes[] = 'nav-shop';
        }

        $atts['class'] = trim(implode(' ', array_unique(array_filter($classes))));
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'smartwerk_menu_link_attributes', 10, 3);


/**
 * SmartWerk 1.2.6 product-gallery optimization.
 * The first product image is the likely LCP candidate and must not be lazy.
 * Additional gallery images are lazy-loaded. The visible gallery uses a
 * responsive WordPress intermediate image; the full original stays available
 * to WooCommerce for zoom/lightbox.
 */
function smartwerk_product_gallery_image_size($size) {
    return 'medium_large';
}
add_filter('woocommerce_gallery_image_size', 'smartwerk_product_gallery_image_size');

function smartwerk_product_gallery_thumbnail_size($size) {
    return 'thumbnail';
}
add_filter('woocommerce_gallery_thumbnail_size', 'smartwerk_product_gallery_thumbnail_size');

function smartwerk_product_gallery_image_html(string $html, int $attachment_id): string {
    if (!function_exists('is_product') || !is_product() || !function_exists('wc_get_product')) {
        return $html;
    }

    $product = wc_get_product(get_queried_object_id());
    if (!$product) {
        return $html;
    }

    $is_main = (int) $product->get_image_id() === $attachment_id;
    $loading = $is_main ? 'eager' : 'lazy';
    $priority = $is_main ? 'high' : 'auto';
    $sizes = '(max-width: 640px) calc(100vw - 28px), (max-width: 920px) calc(100vw - 48px), (max-width: 1240px) 52vw, 560px';

    $html = preg_replace('/\\sloading=(["\\']).*?\\1/i', '', $html);
    $html = preg_replace('/\\sdecoding=(["\\']).*?\\1/i', '', $html);
    $html = preg_replace('/\\sfetchpriority=(["\\']).*?\\1/i', '', $html);
    $html = preg_replace('/\\ssizes=(["\\']).*?\\1/i', '', $html);

    $attributes = sprintf(
        ' loading="%s" decoding="async" fetchpriority="%s" sizes="%s"',
        esc_attr($loading),
        esc_attr($priority),
        esc_attr($sizes)
    );

    return preg_replace('/<img\\b/i', '<img' . $attributes, $html, 1) ?: $html;
}
add_filter('woocommerce_single_product_image_thumbnail_html', 'smartwerk_product_gallery_image_html', 20, 2);

function smartwerk_product_page_asset_cleanup(): void {
    if (!function_exists('is_product') || !is_product()) {
        return;
    }

    foreach ([
        'wp-block-library',
        'wp-block-library-theme',
        'global-styles',
        'classic-theme-styles',
        'wc-blocks-style',
        'wc-blocks-packages-style',
    ] as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
    }

    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
    }
}
add_action('wp_enqueue_scripts', 'smartwerk_product_page_asset_cleanup', 101);
