<?php

/**
 * The home template file
 *
 * This template is the default for front page (if there is no front-page.php) and blog page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Apex_Branding_&_Design
 */

get_header('apex');
?>

<?php if (have_posts()) : ?>


    <div class="section container home">
    <?php
    /* Start the Loop */
    while (have_posts()) :
        the_post();

        /*
 * Include the Post-Type-specific template for the content.
 * If you want to override this in a child theme, then include a file
 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
 */

        get_template_part('template-parts/content-archive', get_post_type());
        // get_template_part('template-parts/content-archive', 'none');

    endwhile;

    the_posts_navigation();

else :

    get_template_part('template-parts/content', 'none');

endif;
    ?>
    </div><!-- section container -->

    <?php

    get_footer('apex');
