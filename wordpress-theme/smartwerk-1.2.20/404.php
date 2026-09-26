<?php
get_header();
?>
<main class="smartwerk-content" id="main-content">
    <section class="section section-soft">
        <div class="site-shell">
            <div class="empty-state">
                <span class="eyebrow">404</span>
                <h1>Diese Seite gibt es nicht.</h1>
                <p>Vielleicht wurde sie verschoben oder der Link ist nicht mehr aktuell.</p>
                <div class="action-row">
                    <a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>">Zur Startseite</a>
                    <a class="button button-secondary" href="<?php echo esc_url(home_url('/smartwerk-shop/')); ?>">Zum Shop</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
