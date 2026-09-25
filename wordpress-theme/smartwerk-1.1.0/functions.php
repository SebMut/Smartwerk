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
    $version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'smartwerk-style',
        get_stylesheet_uri(),
        [],
        $version
    );

    wp_enqueue_script(
        'smartwerk-theme',
        get_template_directory_uri() . '/assets/js/theme.js',
        [],
        $version,
        true
    );
}
add_action('wp_enqueue_scripts', 'smartwerk_assets');

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
        ];

        return $map[$slug] ?? sanitize_html_class($slug ?: 'page');
    }

    return 'site';
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
 * Keeps the new theme safe while the old SmartWerk page content is migrated.
 * Nothing is deleted from WordPress. Legacy page-level headers/footers are
 * hidden by the theme stylesheet until Easy MCP/WPWriter cleans those pages.
 */
function smartwerk_is_legacy_transfer_page(): bool {
    if (!is_page()) {
        return false;
    }

    return in_array(get_queried_object_id(), [1628, 1636, 1638, 1640, 1642, 1657], true);
}
