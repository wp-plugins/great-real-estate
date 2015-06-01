<?php
/*
Template Name: Great Real Estate - Single Listing
*/
?>
<?php get_header(); ?>

    <!-- page content - single listing -->
    <div id="content" class="narrowcolumn">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="post" id="post-<?php the_ID(); ?>">
            <h2><?php the_title(); ?></h2>
            <div class="entry">
            <?php get_template_part( 'great-real-estate/listing-page-content' ); ?>
            </div>
        </div>
    <?php endwhile; endif; ?>

    </div>

<?php get_sidebar(); # left sidebar ?>

<?php get_footer(); ?>
