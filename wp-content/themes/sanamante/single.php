<?php get_header(); ?>
    <main class="container post_type_custom_view">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="row mt-5">
                    <div class="col-md-12 col-12">
                        <div class="row">
                            <div class="col-1">
                                <img src="<?php the_post_thumbnail_url('small'); ?>" width="100%" alt="<?php the_title() ?>">
                            </div>
                            <div class="col-11">
                                <h1 class="my-3"><?php the_title() ?></h1>
                                <?php the_content(); ?>
                            </div>
                        </div>

                        <?php if (get_post_type() === 'orientadores'): ?>
                            <br><br>
                            <div class="row">
                                <div class="col-sm-12 col-lg-12 mb-5">
                                    <?= do_shortcode( '[customer_schedule employee='.get_the_ID().']' ); ?>
                                    <hr>
                                </div>
                                <?php if (!empty(get_field('redes_sociales'))): ?>
                                    <div class="col-sm-12 col-lg-12">
                                        <h5><?= __('Socials')?> </h5>
                                        <ul id="orientadores-social-links">
                                            <?php foreach (get_field('redes_sociales') as $key => $socialLinks): ?>
                                                <li>
                                                    <i class="fab fa-<?= $key  ?>"></i>
                                                    <a href="<?= $socialLinks  ?>"><?= $socialLinks  ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
        <?php endwhile; endif; ?>

    </main>
<?php get_footer(); ?>