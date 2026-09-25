<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-page="<?php echo esc_attr(smartwerk_page_key()); ?>">
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e('Zum Inhalt springen', 'smartwerk'); ?></a>

<header class="site-header" data-site-header>
    <div class="site-shell header-row">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('SmartWerk Startseite', 'smartwerk'); ?>">
            <img src="<?php echo esc_url(smartwerk_logo_url()); ?>" width="512" height="245" alt="SmartWerk.art">
            <span class="brand-fallback" aria-hidden="true">SmartWerk.art</span>
        </a>

        <nav class="desktop-nav" aria-label="<?php esc_attr_e('Hauptnavigation', 'smartwerk'); ?>">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '<ul class="desktop-menu">%3$s</ul>',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ]);
            } else {
                smartwerk_primary_fallback();
            }
            ?>
        </nav>

        <div class="header-actions">
            <a class="button button-primary header-price" href="<?php echo esc_url(home_url('/3d-druck-bestellen/#konfigurator')); ?>">
                <span><?php esc_html_e('Preis berechnen', 'smartwerk'); ?></span><b aria-hidden="true">→</b>
            </a>

            <a class="cart-button" href="<?php echo esc_url(smartwerk_cart_url()); ?>" aria-label="<?php esc_attr_e('Warenkorb', 'smartwerk'); ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 4.5h2l1.7 9.1a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 1.9-1.4l1.2-5.3H7.1M9.5 19a1.25 1.25 0 1 0 0 .01M17.3 19a1.25 1.25 0 1 0 0 .01"/></svg>
                <?php echo smartwerk_cart_count_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </a>

            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="<?php esc_attr_e('Menü öffnen', 'smartwerk'); ?>">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <nav class="mobile-nav" id="mobile-menu" aria-label="<?php esc_attr_e('Mobile Navigation', 'smartwerk'); ?>" hidden>
        <div class="site-shell mobile-nav-inner">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '<ul class="mobile-menu">%3$s</ul>',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ]);
            } else {
                smartwerk_mobile_fallback();
            }
            ?>
            <a class="mobile-contact" href="<?php echo esc_url(home_url('/kontakt/')); ?>"><?php esc_html_e('Kontakt', 'smartwerk'); ?></a>
            <a class="mobile-price" href="<?php echo esc_url(home_url('/3d-druck-bestellen/#konfigurator')); ?>">
                <span><?php esc_html_e('Preis berechnen', 'smartwerk'); ?></span><b aria-hidden="true">→</b>
            </a>
        </div>
    </nav>
</header>
