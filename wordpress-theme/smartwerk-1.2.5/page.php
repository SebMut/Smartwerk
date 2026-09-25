<?php
get_header();

$layout_mode = smartwerk_page_layout_mode();
?>
<main class="smartwerk-content smartwerk-layout-<?php echo esc_attr($layout_mode); ?>" id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <?php if ($layout_mode === 'boxed') : ?>
            <section class="smartwerk-gutenberg-section">
                <div class="site-shell">
                    <article id="post-<?php the_ID(); ?>" <?php post_class('smartwerk-page'); ?>>
                        <div class="entry-content smartwerk-gutenberg-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                </div>
            </section>
        <?php elseif ($layout_mode === 'commerce') : ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('smartwerk-page smartwerk-commerce-page'); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php else : ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('smartwerk-page smartwerk-transfer-page'); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endif; ?>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
