<?php
get_header();
?>
<main class="smartwerk-content" id="main-content">
    <div class="site-shell section">
        <?php if (have_posts()) : ?>
            <div class="grid-3">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('card'); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_excerpt(); ?>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_navigation(); ?>
        <?php else : ?>
            <div class="empty-state"><h2>Keine Inhalte gefunden.</h2></div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
