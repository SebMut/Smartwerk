<?php
get_header();
?>
<main class="smartwerk-content" id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('smartwerk-front-page'); ?>>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
