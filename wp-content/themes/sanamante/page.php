<?php get_header(); ?>
    <main class="container">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post(); ?>
                <h1 class="my-3 d-none"><?php the_title(); ?></h1>
                <div class="mt-5"></div>
                <?php the_content(); ?>
            <?php   }
        } ?>
    </main>
<?php get_footer(); ?>