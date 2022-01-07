<?php /* Template Name: About Page */ ?>
<?php get_header("apex"); ?>

<div class="hero about">
    <div class="container hero-container">
        <div class="row">
            <div class="col-12 mx-auto text-center">

        <?php the_content(); ?>
                
            </div>
        </div>
    </div>
</div>
<div class="section no-gutter about-flare"></div>

<div class="section">
    <div class="container">
        <h1 class="text-center">The Team</h1>
        <div class="row">


            <?php
            $args = array(
                'post_type' => 'team_member',
                'posts_per_page' => 3
            );
            $the_query = new WP_Query($args);
            ?>

            <?php // if ($the_query->have_posts()) : ?>

                <?php 
                while (
                    $the_query->have_posts()
                ) : $the_query->the_post();
                ?>

                    <div class="col-12 col-md-4 mb-4 mb-lg-0 d-flex flex-column align-items-center"><img class="img-fluid founder-image" src="<?php the_field('thumbnail'); ?>">

                        <?php the_title('<h3 class="team-header">', '</h1>'); ?>

                        <h5 class="team-header subheader"> <?php the_field('team_member_title'); ?></h5>
                    </div>
                <?php endwhile; ?>
                <?php // wp_reset_postdata(); 
                ?>
            <?php // endif; ?>











        </div>
    </div>
</div>

<?php echo get_footer("apex"); ?>