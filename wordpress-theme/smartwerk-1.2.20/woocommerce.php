<?php
defined('ABSPATH') || exit;
get_header();

/*
 * Render one explicit WooCommerce breadcrumb trail. Remove the default hook
 * first so WooCommerce versions that call woocommerce_before_main_content
 * cannot render a duplicate trail.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
?>
<main class="smartwerk-wc-main" id="main-content">
    <?php
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb();
    }

    woocommerce_content();
    ?>
</main>
<?php get_footer(); ?>
