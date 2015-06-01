<?php
/*
Template Name: Great Real Estate - Listings Index
*/

# intended only for page use, displays all listings
# first for sale and for rent (larger format), followed by
# a list of pending sale and pending lease, followed by
# a list of sold and leased
#
# Before the lists, display the page's stored title and content
# and an edit link

?>
<?php get_header(); ?>

    <div id="content" class="narrowcolumn">

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="post" id="post-<?php the_ID(); ?>">
        <h2><?php the_title(); ?></h2>
            <div class="entry">
                <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

                <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

            </div>
        </div>
        <?php endwhile; endif; ?>
        <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

        <?php get_template_part( 'great-real-estate/listings-page-content' ); ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
