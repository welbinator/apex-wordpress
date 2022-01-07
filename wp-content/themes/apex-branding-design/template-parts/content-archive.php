<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Apex_Branding_&_Design
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="post-thumbnail-div"><?php apex_branding_design_post_thumbnail(); ?></div>
    <div class="post-header-content-footer">
        <header class="entry-header">
            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

            <?php  if ( 'post' === get_post_type()) : ?>
                <?php // if ( is_singular( array( 'post', 'podcast'))) : ?>
                <div class="entry-meta">
                    <?php
                    apex_branding_design_posted_on();
                    apex_branding_design_posted_by();
                    ?>

                    Categories:
                    <?php
                    $categories = get_the_category();
                    $separator = ' ';
                    $output = '';
                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" alt="' . esc_attr(sprintf(__('View all posts in %s', 'textdomain'), $category->name)) . '">' . esc_html($category->name) . '</a>' . $separator;
                        }
                        echo trim($output, $separator);
                    }
                    ?>

                </div><!-- .entry-meta -->
            <?php  endif; ?>
        </header><!-- .entry-header -->





        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->

        <footer class="entry-footer">
            <?php // apex_branding_design_entry_footer(); 
            ?>
        </footer><!-- .entry-footer -->

    </div>
</article><!-- #post-<?php the_ID(); ?> -->