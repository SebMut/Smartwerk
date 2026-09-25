<?php
get_header();

$layout_mode = smartwerk_page_layout_mode();
?>
<main class="smartwerk-content smartwerk-layout-<?php echo esc_attr($layout_mode); ?>" id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $content = apply_filters('the_content', get_the_content());
        $has_h1  = (bool) preg_match('/<h1\b/i', $content);
        $page_id = (int) get_the_ID();
        $heading = smartwerk_page_display_title($page_id, get_the_title());
        ?>
        <?php if ($layout_mode === 'boxed') : ?>
            <section class="smartwerk-gutenberg-section">
                <div class="site-shell">
                    <article id="post-<?php the_ID(); ?>" <?php post_class('smartwerk-page'); ?>>
                        <?php if (!$has_h1) : ?>
                            <header class="smartwerk-page-heading"><h1><?php echo esc_html($heading); ?></h1></header>
                        <?php endif; ?>
                        <div class="entry-content smartwerk-gutenberg-content">
                            <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                    </article>
                </div>
            </section>
        <?php elseif ($layout_mode === 'commerce') : ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('smartwerk-page smartwerk-commerce-page'); ?>>
                <?php if (!$has_h1) : ?>
                    <header class="smartwerk-page-heading"><h1><?php echo esc_html($heading); ?></h1></header>
                <?php endif; ?>
                <div class="entry-content">
                    <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </article>
        <?php else : ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('smartwerk-page smartwerk-transfer-page'); ?>>
                <?php if (!$has_h1) : ?>
                    <header class="smartwerk-page-heading"><h1><?php echo esc_html($heading); ?></h1></header>
                <?php endif; ?>
                <div class="entry-content">
                    <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </article>
        <?php endif; ?>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
