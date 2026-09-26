<?php
if (!defined('ABSPATH')) {
    exit;
}

function smartwerk_theme_setup(): void {
    load_theme_textdomain('smartwerk', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
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
    $version         = wp_get_theme()->get('Version');
    $min_style_file  = get_template_directory() . '/assets/css/theme.min.css';
    $min_script_file = get_template_directory() . '/assets/js/theme.min.js';
    $style_file      = is_readable($min_style_file) ? $min_style_file : get_stylesheet_directory() . '/style.css';
    $script_file     = is_readable($min_script_file) ? $min_script_file : get_template_directory() . '/assets/js/theme.js';
    $style_uri       = is_readable($min_style_file) ? get_template_directory_uri() . '/assets/css/theme.min.css' : get_stylesheet_uri();
    $script_uri      = is_readable($min_script_file) ? get_template_directory_uri() . '/assets/js/theme.min.js' : get_template_directory_uri() . '/assets/js/theme.js';
    $style_ver       = is_readable($style_file) ? $version . '.' . filemtime($style_file) : $version;
    $script_ver      = is_readable($script_file) ? $version . '.' . filemtime($script_file) : $version;


    wp_enqueue_style(
        'smartwerk-style',
        $style_uri,
        [],
        $style_ver
    );

    wp_enqueue_script(
        'smartwerk-theme',
        $script_uri,
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
    $sizes = '(max-width: 640px) calc(100vw - 28px), (max-width: 920px) min(calc(100vw - 48px), 500px), 430px';

    $html = preg_replace('/\\sloading="[^"]*"/i', '', $html);
    $html = preg_replace('/\\sdecoding="[^"]*"/i', '', $html);
    $html = preg_replace('/\\sfetchpriority="[^"]*"/i', '', $html);
    $html = preg_replace('/\\ssizes="[^"]*"/i', '', $html);

    $attributes = sprintf(
        ' loading="%s" decoding="async" fetchpriority="%s" sizes="%s"',
        esc_attr($loading),
        esc_attr($priority),
        esc_attr($sizes)
    );

    return preg_replace('/<img\\b/i', '<img' . $attributes, $html, 1) ?: $html;
}
add_filter('woocommerce_single_product_image_thumbnail_html', 'smartwerk_product_gallery_image_html', 20, 2);

/**
 * SmartWerk 1.2.22 — preload the actual product hero image with the same
 * responsive candidates/sizes used by the WooCommerce gallery. This lets the
 * browser start the LCP request from <head> without downloading a second,
 * larger image. Originals remain untouched.
 */
function smartwerk_preload_product_lcp_image(): void {
    if (!function_exists('is_product') || !is_product() || !function_exists('wc_get_product')) {
        return;
    }

    $product = wc_get_product(get_queried_object_id());
    if (!($product instanceof WC_Product)) {
        return;
    }

    $image_id = (int) $product->get_image_id();
    if ($image_id <= 0) {
        return;
    }

    $src = wp_get_attachment_image_url($image_id, 'medium_large');
    if (!$src) {
        return;
    }

    $srcset = wp_get_attachment_image_srcset($image_id, 'medium_large');
    $sizes  = '(max-width: 640px) calc(100vw - 28px), (max-width: 920px) min(calc(100vw - 48px), 500px), 430px';

    printf(
        '<link rel="preload" as="image" href="%1$s"%2$s imagesizes="%3$s" fetchpriority="high">' . "\n",
        esc_url($src),
        $srcset ? ' imagesrcset="' . esc_attr($srcset) . '"' : '',
        esc_attr($sizes)
    );
}
add_action('wp_head', 'smartwerk_preload_product_lcp_image', 2);

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





/**
 * SmartWerk 1.2.13 — products where the chosen variation already defines
 * the amount (e.g. Staffelpreis or Menge) must not expose another quantity
 * selector. WooCommerce therefore keeps the cart line quantity at one.
 */
function smartwerk_product_uses_pack_quantity($product): bool {
    if (!($product instanceof WC_Product)) {
        return false;
    }

    if ($product->is_type('variation')) {
        $parent = wc_get_product($product->get_parent_id());
        if ($parent instanceof WC_Product) {
            $product = $parent;
        }
    }

    foreach ($product->get_attributes() as $attribute) {
        if (!($attribute instanceof WC_Product_Attribute)) {
            continue;
        }

        $raw_name = (string) $attribute->get_name();
        $label    = function_exists('wc_attribute_label') ? (string) wc_attribute_label($raw_name) : $raw_name;

        $names = [
            sanitize_title(str_replace('pa_', '', $raw_name)),
            sanitize_title($label),
        ];

        if (in_array('staffelpreis', $names, true) || in_array('menge', $names, true)) {
            return true;
        }
    }

    return false;
}

function smartwerk_pack_products_sold_individually(bool $sold_individually, $product): bool {
    if ($sold_individually) {
        return true;
    }

    return smartwerk_product_uses_pack_quantity($product);
}
add_filter('woocommerce_is_sold_individually', 'smartwerk_pack_products_sold_individually', 20, 2);

function smartwerk_product_purchase_body_classes(array $classes): array {
    if (!function_exists('is_product') || !is_product() || !function_exists('wc_get_product')) {
        return $classes;
    }

    $product = wc_get_product(get_queried_object_id());
    if ($product && smartwerk_product_uses_pack_quantity($product)) {
        $classes[] = 'smartwerk-pack-quantity-product';
    }

    return $classes;
}
add_filter('body_class', 'smartwerk_product_purchase_body_classes', 30);


/**
 * SmartWerk 1.2.14 — show the full product description directly beneath
 * the purchase controls and keep the WooCommerce tabs focused on factual
 * product details/reviews.
 */
function smartwerk_direct_product_description(): void {
    global $product;

    if (!($product instanceof WC_Product)) {
        return;
    }

    $description = trim((string) $product->get_description());
    if ($description === '') {
        return;
    }

    echo '<section class="smartwerk-product-description-direct" aria-labelledby="smartwerk-product-description-title">';
    echo '<h2 id="smartwerk-product-description-title">Beschreibung</h2>';
    echo '<div class="smartwerk-product-description-content">';
    echo wp_kses_post(apply_filters('the_content', $description));
    echo '</div>';
    echo '</section>';
}
add_action('woocommerce_single_product_summary', 'smartwerk_direct_product_description', 35);

function smartwerk_product_tabs_labels(array $tabs): array {
    global $product;

    if ($product instanceof WC_Product && trim((string) $product->get_description()) !== '') {
        unset($tabs['description']);
    }

    if (isset($tabs['additional_information'])) {
        $tabs['additional_information']['title'] = 'Produktdetails';
    }

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'smartwerk_product_tabs_labels', 30);


/**
 * SmartWerk 1.2.15 — compact factual trust/shipping block.
 * Content mirrors the site's published Versandarten/Zahlungsarten pages.
 */
function smartwerk_product_trust_block(): void {
    global $product;

    if (!($product instanceof WC_Product)) {
        return;
    }

    $shipping_url = get_permalink(191);
    $payment_url  = get_permalink(192);

    echo '<div class="smartwerk-product-trust" aria-label="Versand und Zahlung">';

    if (!$product->is_virtual()) {
        echo '<div class="smartwerk-product-trust-item">';
        echo '<span class="smartwerk-product-trust-icon" aria-hidden="true">↗</span>';
        echo '<div><strong>Versand</strong>';
        echo '<span>Deutschland · DHL / Deutsche Post · weitere Länder auf Anfrage</span>';
        if ($shipping_url) {
            printf(' <a href="%s">Versanddetails</a>', esc_url($shipping_url));
        }
        echo '</div></div>';

        echo '<div class="smartwerk-product-trust-item">';
        echo '<span class="smartwerk-product-trust-icon" aria-hidden="true">€</span>';
        echo '<div><strong>Versandkosten</strong>';
        echo '<span>Werden im Checkout angezeigt</span>';
        echo '</div></div>';

        echo '<div class="smartwerk-product-trust-item">';
        echo '<span class="smartwerk-product-trust-icon" aria-hidden="true">⌂</span>';
        echo '<div><strong>Abholung</strong>';
        echo '<span>In 85622 Feldkirchen nach vorheriger Absprache möglich</span>';
        echo '</div></div>';
    }

    echo '<div class="smartwerk-product-trust-item">';
    echo '<span class="smartwerk-product-trust-icon" aria-hidden="true">✓</span>';
    echo '<div><strong>Zahlungsarten</strong>';
    echo '<span>Kreditkarte, Debitkarte, Apple Pay und Google Pay über WooPayments</span>';
    if ($payment_url) {
        printf(' <a href="%s">Zahlungsdetails</a>', esc_url($payment_url));
    }
    echo '</div></div>';

    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'smartwerk_product_trust_block', 34);

/**
 * Product pages do not need WooCommerce cart fragments because SmartWerk
 * refreshes its header cart count through the Store API. Keep all scripts
 * required for variations, gallery and add-to-cart.
 */
function smartwerk_single_product_performance_cleanup(): void {
    if (!function_exists('is_product') || !is_product()) {
        return;
    }

    wp_dequeue_script('wc-cart-fragments');
    wp_dequeue_script('wp-embed');
}
add_action('wp_enqueue_scripts', 'smartwerk_single_product_performance_cleanup', 110);


/* ==========================================================================
 * SmartWerk 1.2.16 — technical SEO fallback.
 * WooCommerce remains the source of truth for Product structured data.
 * Meta/canonical/Open Graph fallback runs only when no established SEO
 * plugin is detected, preventing duplicate tags.
 * ========================================================================== */

function smartwerk_has_external_seo_plugin(): bool {
    return defined('WPSEO_VERSION')
        || defined('RANK_MATH_VERSION')
        || function_exists('aioseo')
        || defined('SEOPRESS_VERSION')
        || defined('THE_SEO_FRAMEWORK_VERSION');
}

function smartwerk_product_seo_map(): array {
    return [
        1705 => [
            'title' => 'Trikot Ausstecher personalisiert | SmartWerk',
            'description' => 'Personalisierter Trikot-Ausstecher mit Wunschname und Nummer für Kekse und Plätzchen. Direkt bei SmartWerk bestellen.',
        ],
        1728 => [
            'title' => 'Mini-Ortsschild personalisiert | SmartWerk',
            'description' => 'Personalisiertes Mini-Ortsschild im deutschen Design mit Wunschname oder Wunschtext als 3D-Druck direkt bei SmartWerk.',
        ],
        1752 => [
            'title' => 'Wiesn Fotorahmen personalisiert | SmartWerk',
            'description' => 'Personalisierter Wiesn-Fotorahmen mit Wunschtext als Foto-Prop für Oktoberfest, Volksfest und Trachtenparty.',
        ],
        1777 => [
            'title' => 'Mini-Kennzeichen personalisiert | SmartWerk',
            'description' => 'Personalisiertes Mini-Kennzeichen im deutschen Design mit Wunschtext als individuelle 3D-Druck-Dekoration.',
        ],
        1794 => [
            'title' => 'Sportplatz Zaunhaken für Tennis & Padel | SmartWerk',
            'description' => '3D-gedruckter Zaunhaken für Sportplatz, Tennis, Padel und Outdoor. Praktische Aufhängung für Tasche, Handtuch oder Kappe.',
        ],
        1832 => [
            'title' => 'Tennisschläger Wandhalter | SmartWerk',
            'description' => 'Individualisierbarer Wandhalter für Tennisschläger in zwei Größen und mehreren Farben direkt bei SmartWerk.',
        ],
        1850 => [
            'title' => 'Flaschenanhänger personalisiert | SmartWerk',
            'description' => 'Personalisierter 3D-gedruckter Flaschenanhänger mit Wunschtext oder Spruch für Flaschen und persönliche Geschenkideen.',
        ],
        1910 => [
            'title' => 'Tennis Netz Kamera Halterung | SmartWerk',
            'description' => 'PETG-Halterung für das Tennisnetz zur sicheren Smartphone-Befestigung mit gedruckten Sicherungsschrauben für Training und Video.',
        ],
        1932 => [
            'title' => 'Fußball Plätzchenausstecher Set | SmartWerk',
            'description' => 'Sportliche Plätzchenformen mit Fußball, Trikot, Schuh und Handschuh für Kekse, Fondant und Fußball-Mottos.',
        ],
        1048 => [
            'title' => 'Armband Ausstecher personalisierbar | SmartWerk',
            'description' => 'Personalisierbarer Armband-Ausstecher mit Wunschtext für Kekse, Fondant und Clay direkt bei SmartWerk.',
        ],
        1179 => [
            'title' => 'Oster Hasen Ausstecher mit Prägung | SmartWerk',
            'description' => '3D-gedruckter Hasen-Plätzchenausstecher mit Prägung für Kekse, Fondant und Marzipan – passend für Ostern und kreative Backideen.',
        ],
        783 => [
            'title' => '3D-Druck nach Maß | SmartWerk',
            'description' => 'Individuellen 3D-Druck bei SmartWerk konfigurieren und das eigene Modell für die Preisberechnung verwenden.',
        ],
    ];
}

function smartwerk_fallback_document_title(array $parts): array {
    if (smartwerk_has_external_seo_plugin()) {
        return $parts;
    }

    if (is_front_page()) {
        $parts['title'] = '3D-Druck & personalisierte Produkte';
        $parts['site']  = 'SmartWerk';
    } elseif (function_exists('is_product') && is_product()) {
        $map = smartwerk_product_seo_map();
        $id  = get_queried_object_id();

        if (isset($map[$id]['title'])) {
            $title = $map[$id]['title'];
            $title = preg_replace('/\s*\|\s*SmartWerk\s*$/u', '', $title);
            $parts['title'] = $title;
            $parts['site']  = 'SmartWerk';
        }
    } elseif (function_exists('is_product_category') && is_product_category()) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $parts['title'] = $term->name;
            $parts['site']  = 'SmartWerk';
        }
    }

    return $parts;
}
add_filter('document_title_parts', 'smartwerk_fallback_document_title', 30);

function smartwerk_current_seo_description(): string {
    if (is_front_page()) {
        return 'Individueller 3D-Druck nach Datei oder Idee sowie eigene und personalisierbare 3D-Druck-Produkte von SmartWerk aus Feldkirchen bei München.';
    }

    if (function_exists('is_product') && is_product()) {
        $id  = get_queried_object_id();
        $map = smartwerk_product_seo_map();

        if (isset($map[$id]['description'])) {
            return $map[$id]['description'];
        }

        $product = function_exists('wc_get_product') ? wc_get_product($id) : null;
        if ($product instanceof WC_Product) {
            $text = $product->get_short_description();
            if ($text === '') {
                $text = $product->get_description();
            }
            return wp_trim_words(wp_strip_all_tags($text), 26, '…');
        }
    }

    if (function_exists('is_product_category') && is_product_category()) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            return wp_trim_words(wp_strip_all_tags(term_description($term)), 28, '…');
        }
    }

    if (is_page()) {
        $map = smartwerk_page_seo_map();
        $id  = get_queried_object_id();

        if (isset($map[$id]['description'])) {
            return $map[$id]['description'];
        }
    }

    if (is_singular()) {
        $post = get_queried_object();
        if ($post instanceof WP_Post) {
            $text = has_excerpt($post) ? get_the_excerpt($post) : $post->post_content;
            return wp_trim_words(wp_strip_all_tags(strip_shortcodes($text)), 28, '…');
        }
    }

    return '';
}

function smartwerk_current_social_image(): string {
    if (function_exists('is_product') && is_product() && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
        if ($product instanceof WC_Product && $product->get_image_id()) {
            $image = wp_get_attachment_image_url($product->get_image_id(), 'large');
            if ($image) {
                return $image;
            }
        }
    }

    if (function_exists('is_product_category') && is_product_category()) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $thumbnail_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
            if ($thumbnail_id) {
                $image = wp_get_attachment_image_url($thumbnail_id, 'large');
                if ($image) {
                    return $image;
                }
            }
        }
    }

    if (is_singular() && has_post_thumbnail()) {
        $image = get_the_post_thumbnail_url(get_queried_object_id(), 'large');
        if ($image) {
            return $image;
        }
    }

    $custom_logo_id = (int) get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $image = wp_get_attachment_image_url($custom_logo_id, 'large');
        if ($image) {
            return $image;
        }
    }

    return (string) get_site_icon_url(512);
}

function smartwerk_current_canonical_url(): string {
    $paged = max(1, (int) get_query_var('paged'));

    if ((function_exists('is_product_category') && is_product_category()) || (function_exists('is_shop') && is_shop())) {
        if ($paged > 1) {
            return get_pagenum_link($paged);
        }

        if (function_exists('is_product_category') && is_product_category()) {
            $term = get_queried_object();
            if ($term instanceof WP_Term) {
                $url = get_term_link($term);
                return is_wp_error($url) ? '' : $url;
            }
        }

        if (function_exists('is_shop') && is_shop()) {
            $shop_id = (int) wc_get_page_id('shop');
            return $shop_id > 0 ? get_permalink($shop_id) : '';
        }
    }

    if (is_singular()) {
        return get_permalink(get_queried_object_id());
    }

    if (is_front_page()) {
        return home_url('/');
    }

    return '';
}

function smartwerk_fallback_meta_tags(): void {
    if (is_admin() || smartwerk_has_external_seo_plugin()) {
        return;
    }

    if (!(is_front_page() || is_singular() || (function_exists('is_product_category') && is_product_category()) || (function_exists('is_shop') && is_shop()))) {
        return;
    }

    $title       = wp_get_document_title();
    $description = smartwerk_current_seo_description();
    $canonical   = smartwerk_current_canonical_url();
    $image       = smartwerk_current_social_image();
    $og_type     = (function_exists('is_product') && is_product()) ? 'product' : 'website';

    if ($description !== '') {
        printf("<meta name=\"description\" content=\"%s\">\n", esc_attr($description));
    }

    printf("<meta property=\"og:locale\" content=\"de_DE\">\n");
    printf("<meta property=\"og:type\" content=\"%s\">\n", esc_attr($og_type));
    printf("<meta property=\"og:site_name\" content=\"SmartWerk\">\n");
    printf("<meta property=\"og:title\" content=\"%s\">\n", esc_attr($title));

    if ($description !== '') {
        printf("<meta property=\"og:description\" content=\"%s\">\n", esc_attr($description));
    }

    if ($canonical !== '') {
        printf("<meta property=\"og:url\" content=\"%s\">\n", esc_url($canonical));
    }

    if ($image !== '') {
        printf("<meta property=\"og:image\" content=\"%s\">\n", esc_url($image));
    }

    echo "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    printf("<meta name=\"twitter:title\" content=\"%s\">\n", esc_attr($title));

    if ($description !== '') {
        printf("<meta name=\"twitter:description\" content=\"%s\">\n", esc_attr($description));
    }

    if ($image !== '') {
        printf("<meta name=\"twitter:image\" content=\"%s\">\n", esc_url($image));
    }

    if (function_exists('is_product') && is_product() && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
        if ($product instanceof WC_Product) {
            printf("<meta property=\"product:price:amount\" content=\"%s\">\n", esc_attr(wc_format_decimal($product->get_price(), 2)));
            printf("<meta property=\"product:price:currency\" content=\"%s\">\n", esc_attr(get_woocommerce_currency()));
            printf("<meta property=\"product:availability\" content=\"%s\">\n", esc_attr($product->is_in_stock() ? 'in stock' : 'out of stock'));
        }
    }
}
add_action('wp_head', 'smartwerk_fallback_meta_tags', 5);

function smartwerk_archive_canonical(): void {
    if (is_admin() || smartwerk_has_external_seo_plugin()) {
        return;
    }

    if (!((function_exists('is_product_category') && is_product_category()) || (function_exists('is_shop') && is_shop()))) {
        return;
    }

    $canonical = smartwerk_current_canonical_url();
    if ($canonical !== '') {
        printf("<link rel=\"canonical\" href=\"%s\">\n", esc_url($canonical));
    }
}
add_action('wp_head', 'smartwerk_archive_canonical', 4);

/**
 * Keep WooCommerce Product JSON-LD authoritative; only add SmartWerk as brand
 * when WooCommerce has not already supplied a brand.
 */
function smartwerk_enrich_product_schema(array $markup, $product): array {
    if (!($product instanceof WC_Product)) {
        return $markup;
    }

    if (empty($markup['brand'])) {
        $markup['brand'] = [
            '@type' => 'Brand',
            'name'  => 'SmartWerk',
        ];
    }

    if (empty($markup['url'])) {
        $markup['url'] = get_permalink($product->get_id());
    }

    return $markup;
}
add_filter('woocommerce_structured_data_product', 'smartwerk_enrich_product_schema', 20, 2);

function smartwerk_breadcrumb_defaults(array $defaults): array {
    $defaults['delimiter']   = ' <span aria-hidden="true">/</span> ';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">';
    $defaults['wrap_after']  = '</nav>';
    $defaults['home']        = 'Startseite';
    return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'smartwerk_breadcrumb_defaults');

/**
 * WordPress core sitemap fallback. SEO plugins remain free to provide their
 * own sitemap because this is bypassed when one of them is active.
 */
function smartwerk_core_sitemaps_enabled(bool $enabled): bool {
    return smartwerk_has_external_seo_plugin() ? $enabled : true;
}
add_filter('wp_sitemaps_enabled', 'smartwerk_core_sitemaps_enabled');

function smartwerk_robots_sitemap(string $output, bool $public): string {
    if (!$public || smartwerk_has_external_seo_plugin()) {
        return $output;
    }

    if (stripos($output, 'Sitemap:') === false) {
        $output .= "\nSitemap: " . home_url('/wp-sitemap.xml') . "\n";
    }

    return $output;
}
add_filter('robots_txt', 'smartwerk_robots_sitemap', 20, 2);


/* ==========================================================================
 * SmartWerk 1.2.17 — SEO hardening
 * ========================================================================== */

/**
 * Keep utility and thin-result pages out of the search index while allowing
 * crawlers to follow their links. Product/shop/category pages stay indexable.
 */
function smartwerk_utility_page_robots(array $robots): array {
    $noindex = is_search() || is_404();

    if (function_exists('is_cart') && is_cart()) {
        $noindex = true;
    }

    if (function_exists('is_checkout') && is_checkout()) {
        $noindex = true;
    }

    if (function_exists('is_account_page') && is_account_page()) {
        $noindex = true;
    }

    if ($noindex) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
        $robots['noarchive'] = true;
    }

    return $robots;
}
add_filter('wp_robots', 'smartwerk_utility_page_robots', 30);

/**
 * Minimal organization schema for the SmartWerk business entity.
 * Only emitted by the theme fallback when no major SEO plugin is active.
 */
function smartwerk_organization_schema(): void {
    if (!is_front_page() || smartwerk_has_external_seo_plugin()) {
        return;
    }

    $logo = smartwerk_current_social_image();

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        '@id'      => home_url('/#organization'),
        'name'     => 'SmartWerk',
        'url'      => home_url('/'),
        'email'    => 'info@smartwerk.art',
        'telephone'=> '+49 160 8462257',
        'address'  => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Wilhelm-Vetter-Strasse 1',
            'postalCode'      => '85622',
            'addressLocality' => 'Feldkirchen',
            'addressCountry'  => 'DE',
        ],
    ];

    if ($logo !== '') {
        $schema['logo'] = [
            '@type' => 'ImageObject',
            'url'   => $logo,
        ];
    }

    echo '<script type="application/ld+json">'
        . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . "</script>\n";
}
add_action('wp_head', 'smartwerk_organization_schema', 25);

/**
 * Reduce low-value head noise. These are not needed for SmartWerk discovery
 * and do not affect feeds, canonical URLs or the XML sitemap.
 */
function smartwerk_seo_head_cleanup(): void {
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('template_redirect', 'wp_shortlink_header', 11);
}
add_action('init', 'smartwerk_seo_head_cleanup', 30);


/* ==========================================================================
 * SmartWerk 1.2.18 — migration cleanup + internal SEO links
 * ========================================================================== */

function smartwerk_page_seo_map(): array {
    return [
        1636 => [
            'title' => '3D-Druck online bestellen | SmartWerk',
            'description' => '3D-Modell hochladen, Druck konfigurieren und individuellen 3D-Druck bei SmartWerk aus Feldkirchen bei München kalkulieren.',
        ],
        1638 => [
            'title' => 'Individuelle 3D-Druck Anfertigung | SmartWerk',
            'description' => 'Individuelle 3D-Druck-Lösung nach Idee, Skizze, Foto, Muster oder Maßen – von der Abstimmung bis zum fertigen Bauteil.',
        ],
        1640 => [
            'title' => '3D-Druck Wissen & STL-Dateien | SmartWerk',
            'description' => '3D-Druck verständlich erklärt: Drucker, Filamente, Slicer, Einstellungen, Zubehör, Fehlerbilder und Quellen für STL-Dateien.',
        ],
        1642 => [
            'title' => '3D-Druck Projekte & Beispiele | SmartWerk',
            'description' => 'SmartWerk Projekte aus dem 3D-Druck: Halterungen, Funktionsteile, Prototypen und individuelle Lösungen aus Feldkirchen bei München.',
        ],
        1657 => [
            'title' => '3D-Druck Shop & personalisierte Produkte | SmartWerk',
            'description' => 'SmartWerk Shop mit eigenen 3D-Druck-Produkten, Sportzubehör, personalisierbaren Artikeln und praktischen Lösungen direkt bestellen.',
        ],
        210 => [
            'title' => 'Kontakt | SmartWerk',
            'description' => 'SmartWerk in Feldkirchen bei München kontaktieren – für individuellen 3D-Druck, Produktfragen und persönliche Projektanfragen.',
        ],
    ];
}

function smartwerk_page_document_title_override(array $parts): array {
    if (smartwerk_has_external_seo_plugin() || !is_page()) {
        return $parts;
    }

    $map = smartwerk_page_seo_map();
    $id  = get_queried_object_id();

    if (!isset($map[$id]['title'])) {
        return $parts;
    }

    $title = preg_replace('/\s*\|\s*SmartWerk\s*$/u', '', $map[$id]['title']);
    $parts['title'] = $title;
    $parts['site']  = 'SmartWerk';

    return $parts;
}
add_filter('document_title_parts', 'smartwerk_page_document_title_override', 40);

/**
 * During migration, some old page bodies may still contain Kiosko styles and
 * their own header/footer. Strip those shells at render time so only the
 * actual SmartWerk theme header/footer is delivered.
 */


/**
 * Server-rendered category links improve crawl paths and give shoppers a fast
 * way into the most useful WooCommerce category archives.
 */
function smartwerk_shop_category_navigation_html(): string {
    $slugs = [
        '3d-druck',
        'personalisierte-produkte',
        'sport-outdoor',
        'tennis-zubehoer',
        'plaetzchenausstecher',
        'flaschenanhaenger-3d-druck',
    ];

    $links = [];

    foreach ($slugs as $slug) {
        $term = get_term_by('slug', $slug, 'product_cat');
        if (!($term instanceof WP_Term) || (int) $term->count < 1) {
            continue;
        }

        $url = get_term_link($term);
        if (is_wp_error($url)) {
            continue;
        }

        $links[] = sprintf(
            '<a href="%1$s">%2$s</a>',
            esc_url($url),
            esc_html($term->name)
        );
    }

    if (!$links) {
        return '';
    }

    return '<nav class="smartwerk-shop-category-nav" aria-label="Shop-Kategorien">'
        . '<span class="smartwerk-shop-category-nav-label">Direkt zu:</span>'
        . implode('', $links)
        . '</nav>';
}

function smartwerk_inject_shop_category_navigation(string $content): string {
    if (is_admin() || !is_page(1657) || str_contains($content, 'smartwerk-shop-category-nav')) {
        return $content;
    }

    $nav = smartwerk_shop_category_navigation_html();
    if ($nav === '') {
        return $content;
    }

    $marker = '<div class="smartwerk-shop-filters"';
    $pos = strpos($content, $marker);

    if ($pos === false) {
        return $content;
    }

    return substr($content, 0, $pos) . $nav . substr($content, $pos);
}
add_filter('the_content', 'smartwerk_inject_shop_category_navigation', 12);

/**
 * The homepage/shop use WordPress.com's image CDN heavily. Preconnect reduces
 * connection setup time without changing image URLs or lazy-loading behavior.
 */
function smartwerk_image_cdn_resource_hints(array $urls, string $relation_type): array {
    if ($relation_type === 'preconnect' && (is_front_page() || is_page(1657))) {
        $urls[] = 'https://i0.wp.com';
    }

    return $urls;
}
add_filter('wp_resource_hints', 'smartwerk_image_cdn_resource_hints', 20, 2);


/* ==========================================================================
 * SmartWerk 1.2.19 — final asset scoping / SEO cleanup
 * ========================================================================== */

function smartwerk_is_wp3dprinting_context(): bool {
    if (is_page(1636)) {
        return true;
    }

    return function_exists('is_product')
        && is_product()
        && (int) get_queried_object_id() === 783;
}

function smartwerk_is_woocommerce_context(): bool {
    if (smartwerk_is_wp3dprinting_context()) {
        return true;
    }

    return (function_exists('is_shop') && is_shop())
        || is_page(1657)
        || (function_exists('is_product') && is_product())
        || (function_exists('is_cart') && is_cart())
        || (function_exists('is_checkout') && is_checkout())
        || (function_exists('is_account_page') && is_account_page());
}

function smartwerk_dequeue_assets_matching(array $needles): void {
    $scripts = wp_scripts();

    if ($scripts instanceof WP_Scripts) {
        foreach ((array) $scripts->queue as $handle) {
            $src = isset($scripts->registered[$handle]) ? (string) $scripts->registered[$handle]->src : '';

            foreach ($needles as $needle) {
                if ($src !== '' && str_contains($src, $needle)) {
                    wp_dequeue_script($handle);
                    break;
                }
            }
        }
    }

    $styles = wp_styles();

    if ($styles instanceof WP_Styles) {
        foreach ((array) $styles->queue as $handle) {
            $src = isset($styles->registered[$handle]) ? (string) $styles->registered[$handle]->src : '';

            foreach ($needles as $needle) {
                if ($src !== '' && str_contains($src, $needle)) {
                    wp_dequeue_style($handle);
                    break;
                }
            }
        }
    }
}

function smartwerk_final_asset_scope(): void {
    if (!smartwerk_is_wp3dprinting_context()) {
        smartwerk_dequeue_assets_matching([
            '/plugins/3dprint/',
            '/plugins/3dprint-round/',
        ]);
    }

    if (!smartwerk_is_woocommerce_context()) {
        smartwerk_dequeue_assets_matching([
            '/plugins/woocommerce/assets/',
            '/plugins/woocommerce-payments/',
            '/plugins/woocommerce-germanized/',
        ]);
    }

    if (function_exists('is_product') && is_product() && !smartwerk_is_wp3dprinting_context()) {
        smartwerk_dequeue_assets_matching([
            '/plugins/woocommerce-payments/',
        ]);

        foreach ([
            'wc-address-autocomplete-common',
            'wc-dompurify',
            'wc-address-autocomplete',
            'wc-select2',
            'wc-zoom',
            'wc-photoswipe',
            'wc-photoswipe-ui-default',
            'WCPAY_ASSETS',
            'WCPAY_EXPRESS_CHECKOUT_ECE',
            'WCPAY_PRODUCT_DETAILS',
            'wcpay-frontend-tracks',
        ] as $handle) {
            wp_dequeue_script($handle);
        }

        foreach ([
            'wc-address-autocomplete',
            'wc-blocks-checkout-style',
            'select2',
            'photoswipe',
            'photoswipe-default-skin',
            'WCPAY_EXPRESS_CHECKOUT_ECE',
            'wcpay-product-details',
        ] as $handle) {
            wp_dequeue_style($handle);
        }
    }
}
add_action('wp_enqueue_scripts', 'smartwerk_final_asset_scope', 999);

/**
 * WP3DPrinting registers/enqueues part of its large frontend stack after the
 * normal wp_enqueue_scripts phase. Repeat only the URL-based dequeue directly
 * before WordPress prints assets so late plugin enqueues cannot leak onto
 * unrelated pages.
 */
function smartwerk_late_wp3dprinting_asset_scope(): void {
    if (smartwerk_is_wp3dprinting_context()) {
        return;
    }

    smartwerk_dequeue_assets_matching([
        '/plugins/3dprint/',
        '/plugins/3dprint-round/',
    ]);
}
add_action('wp_print_styles', 'smartwerk_late_wp3dprinting_asset_scope', PHP_INT_MAX);
add_action('wp_print_scripts', 'smartwerk_late_wp3dprinting_asset_scope', PHP_INT_MAX);
add_action('wp_print_footer_scripts', 'smartwerk_late_wp3dprinting_asset_scope', PHP_INT_MAX);

/**
 * WooPayments, Select2 and the optional WooCommerce zoom/lightbox stacks can
 * enqueue after wp_enqueue_scripts as well. On normal product pages SmartWerk
 * keeps the native variation form and FlexSlider thumbnails, but removes these
 * non-essential product-page assets. Cart and checkout are never touched.
 */
function smartwerk_late_product_performance_scope(): void {
    if (!function_exists('is_product') || !is_product() || smartwerk_is_wp3dprinting_context()) {
        return;
    }

    smartwerk_dequeue_assets_matching([
        '/plugins/woocommerce-payments/',
    ]);

    foreach ([
        'wc-address-autocomplete-common',
        'wc-dompurify',
        'wc-address-autocomplete',
        'wc-select2',
        'wc-zoom',
        'wc-photoswipe',
        'wc-photoswipe-ui-default',
        'WCPAY_ASSETS',
        'WCPAY_EXPRESS_CHECKOUT_ECE',
        'WCPAY_PRODUCT_DETAILS',
        'wcpay-frontend-tracks',
    ] as $handle) {
        wp_dequeue_script($handle);
    }

    foreach ([
        'wc-address-autocomplete',
        'wc-blocks-checkout-style',
        'select2',
        'photoswipe',
        'photoswipe-default-skin',
        'WCPAY_EXPRESS_CHECKOUT_ECE',
        'wcpay-product-details',
    ] as $handle) {
        wp_dequeue_style($handle);
    }
}
add_action('wp_print_styles', 'smartwerk_late_product_performance_scope', PHP_INT_MAX);
add_action('wp_print_scripts', 'smartwerk_late_product_performance_scope', PHP_INT_MAX);
add_action('wp_print_footer_scripts', 'smartwerk_late_product_performance_scope', PHP_INT_MAX);



function smartwerk_page_display_title(int $page_id, string $default): string {
    $map = [
        210 => 'Erzähl uns, was du brauchst.',
        12  => 'Warenkorb',
        13  => 'Kasse',
        14  => 'Mein Konto',
    ];

    return $map[$page_id] ?? $default;
}

function smartwerk_product_context_links(): void {
    if (!function_exists('is_product') || !is_product()) {
        return;
    }

    $links = [
        sprintf(
            '<a href="%s">Zum SmartWerk Shop</a>',
            esc_url(home_url('/smartwerk-shop/'))
        ),
    ];

    $terms = get_the_terms(get_queried_object_id(), 'product_cat');

    if (is_array($terms)) {
        usort($terms, static fn($a, $b) => (int) $b->parent <=> (int) $a->parent);

        foreach ($terms as $term) {
            if ($term->slug === 'unkategorisiert') {
                continue;
            }

            $url = get_term_link($term);
            if (!is_wp_error($url)) {
                $links[] = sprintf(
                    '<a href="%1$s">%2$s</a>',
                    esc_url($url),
                    esc_html($term->name)
                );
            }

            if (count($links) >= 3) {
                break;
            }
        }
    }

    echo '<nav class="smartwerk-product-links" aria-label="Weitere Shop-Bereiche">'
        . implode('', $links)
        . '</nav>';
}
add_action('woocommerce_single_product_summary', 'smartwerk_product_context_links', 37);

function smartwerk_exclude_utility_pages_from_sitemap(array $args, string $post_type): array {
    if ($post_type !== 'page') {
        return $args;
    }

    $exclude  = [11, 12, 13, 14, 145, 1628];
    $existing = isset($args['post__not_in']) ? (array) $args['post__not_in'] : [];
    $args['post__not_in'] = array_values(array_unique(array_merge($existing, $exclude)));

    return $args;
}
add_filter('wp_sitemaps_posts_query_args', 'smartwerk_exclude_utility_pages_from_sitemap', 20, 2);

function smartwerk_redirect_legacy_pages(): void {
    if (is_admin() || wp_doing_ajax()) {
        return;
    }

    if (is_page(1628)) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }

    if (is_page([11, 145])) {
        wp_safe_redirect(home_url('/3d-druck-bestellen/'), 301);
        exit;
    }
}
add_action('template_redirect', 'smartwerk_redirect_legacy_pages', 2);


/**
 * WooCommerce normally generates Product JSON-LD during the single-product
 * summary and outputs it in wp_footer. If another integration suppresses the
 * generator, trigger WooCommerce's own generator once after the product
 * template instead of emitting a competing custom Product schema.
 */
function smartwerk_ensure_native_product_schema(): void {
    if (!function_exists('is_product') || !is_product() || !function_exists('WC')) {
        return;
    }

    $wc = WC();
    if (!$wc || !isset($wc->structured_data) || !is_object($wc->structured_data)) {
        return;
    }

    if (!method_exists($wc->structured_data, 'get_structured_data')
        || !method_exists($wc->structured_data, 'generate_product_data')) {
        return;
    }

    $existing = $wc->structured_data->get_structured_data(['product']);
    if (!empty($existing)) {
        return;
    }

    $product = function_exists('wc_get_product')
        ? wc_get_product(get_queried_object_id())
        : null;

    if ($product instanceof WC_Product) {
        $wc->structured_data->generate_product_data($product);
    }
}
add_action('woocommerce_after_single_product', 'smartwerk_ensure_native_product_schema', 99);
