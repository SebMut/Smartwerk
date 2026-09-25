<footer class="site-footer">
    <div class="site-shell footer-main">
        <div class="footer-intro">
            <a class="footer-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('SmartWerk Startseite', 'smartwerk'); ?>">
                <img src="<?php echo esc_url(smartwerk_logo_url()); ?>" alt="SmartWerk.art">
                <span class="brand-fallback" aria-hidden="true">SmartWerk.art</span>
            </a>
            <p>Online 3D-Druck &amp; individuelle Fertigung aus Feldkirchen bei München.</p>
            <span class="footer-location">Wilhelm-Vetter-Strasse 1 · 85622 Feldkirchen</span>
            <span class="footer-contact">
                <a href="tel:+491608462257">+49 (0) 160 846 22 57</a><i>·</i>
                <a href="mailto:info@smartwerk.art">info@smartwerk.art</a>
            </span>
        </div>

        <nav class="footer-services" aria-label="<?php esc_attr_e('SmartWerk Leistungen', 'smartwerk'); ?>">
            <span><?php esc_html_e('Leistungen', 'smartwerk'); ?></span>
            <a href="<?php echo esc_url(home_url('/3d-druck-bestellen/#konfigurator')); ?>">3D-Druck</a>
            <a href="<?php echo esc_url(home_url('/individuelle-anfertigung/')); ?>">Individuelle Fertigung</a>
            <a href="<?php echo esc_url(home_url('/smartwerk-shop/')); ?>">Shop</a>
        </nav>
    </div>

    <div class="site-shell footer-bottom">
        <span class="footer-service-line">3D-Druck · Individuelle Fertigung · Shop</span>
        <nav class="footer-links" aria-label="<?php esc_attr_e('Kontakt und Rechtliches', 'smartwerk'); ?>">
            <a href="<?php echo esc_url(home_url('/kontakt/')); ?>">Kontakt</a>
            <a href="<?php echo esc_url(home_url('/impressum/')); ?>">Impressum</a>
            <a href="<?php echo esc_url(home_url('/datenschutzerklaerung/')); ?>">Datenschutz</a>
            <a href="<?php echo esc_url(home_url('/agb/')); ?>">AGB</a>
        </nav>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
