<?php
/*
Template Name: Listings Index
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


<?php   
if (function_exists(get_pages_with_active_listings)) {
?>
<!-- list of listings -->
<?php
	$pageposts = get_pages_with_active_listings('','highprice');
?>

<?php if ($pageposts): ?>
<div id="activelistings">
<h2><?php echo esc_html( gre_get_option( 'active-listings-title' ) ); ?></h2>
	<?php foreach ($pageposts as $post): ?>
		<?php setup_postdata($post); ?>
		<?php setup_listingdata($post); ?>
		<?php $line1 = ''; $line2 = ''; $line3 = ''; ?>

	<div class="prop-box-avail">
	<div class="prop-thumb">
	<?php the_listing_thumbnail(); ?>
	</div>
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	<h3><?php the_listing_status(); ?>
		<?php if (get_listing_listprice()) { ?>
		- Offered at <?php the_listing_listprice(); } ?> 
	</h3>
      	<div>
 	<?php the_listing_blurb(); ?>
      	</div>
	<?php if ($bedrooms = get_listing_bedrooms()) 
		$line1 .= "<div>$bedrooms Bedrooms</div>"; ?>
	<?php if ($bathrooms = get_listing_bathrooms()) {
		$line1 .= "<div>$bathrooms Full ";
		if ($halfbaths = get_listing_halfbaths()) 
			$line1 .= "&amp; $halfbaths Half ";
		$line1 .= " Baths</div>"; 
              }	?>
	<?php if (get_listing_garage()) 
		$line1 .= "<div>" . get_listing_garage() . " Garage Spaces</div>"; ?>
	<?php if (get_listing_acsf()) 
		$line2 .= "<div>" . get_listing_acsf() ." Sq/Ft Living</div>"; ?>
	<?php if (get_listing_totsf()) $line2 .= "<div>" .get_listing_totsf(). " Sq/Ft Total</div>"; ?>
	<?php if (get_listing_acres()) $line2 .= "<div>" .get_listing_acres()." Acres</div>"; ?>
	<?php if (get_listing_haspool()) $line3 .= "<div>Private Pool</div>"; ?>
	<?php if (get_listing_haswater()) $line3 .= "<div>Waterfront</div>"; ?>
	<?php if (get_listing_hasgolf()) $line3 .= "<div>On Golf Course</div>"; ?>
 	<?php if ($line1 || $line2 || $line3) { ?>
      <div class='propdata'>
	<?php if ($line1) echo "<div class='propdata-line'>$line1</div>"; ?>
	<?php if ($line2) echo "<div class='propdata-line'>$line2</div>"; ?>
	<?php if ($line3) echo "<div class='propdata-line propfeatures'>$line3</div>"; ?>
      </div>
	<?php } ?>
  
    	</div>
  <?php endforeach; ?>
  
  </div>
 <?php endif; ?>

<!-- list of pending sales -->
<?php   

 $pageposts = get_pages_with_pending_listings('','highprice');

?>

<?php if ($pageposts): ?>
  <div id="pendingsales">
  <h2><?php echo esc_html( gre_get_option( 'pending-sale-listings-title', 'greatrealestate' ) ); ?></h2>
  <?php foreach ($pageposts as $post): ?>
    <?php setup_postdata($post); ?>
    <?php setup_listingdata($post); ?>

    <div class="prop-float-container prop-box">
	<div class="prop-thumb">
	<?php the_listing_thumbnail(); ?>
	</div>
        <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>"><?php the_title(); ?></a></h3>
	<p><span class="propispending"><?php the_listing_status(); ?></span>
		<?php if (get_listing_listprice()) { ?>
		Last offered at <?php the_listing_listprice(); ?> 
		<?php } ?>
	</p>
    </div> 
  <?php endforeach; ?>
  </div> 
 <?php endif; ?>


<!-- list of sold -->
<?php   

 $pageposts = get_pages_with_sold_listings('','saledate');

?>

<?php if ($pageposts): ?>
  <div id="soldlistings">
  <h2><?php echo esc_html( gre_get_option( 'sold-listings-title', 'greatrealestate' ) ); ?></h2>
  <?php foreach ($pageposts as $post): ?>
    <?php setup_postdata($post); ?>
    <?php setup_listingdata($post); ?>

    <div class="prop-float-container prop-box">
	<div class="prop-thumb">
	<?php the_listing_thumbnail(); ?>
	</div>
        <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>"><?php the_title(); ?></a></h3>
	<p><span class="propwassold"><?php the_listing_status(); ?></span>
		<?php if (get_listing_saleprice()) the_listing_saleprice(); ?> 
		<?php if (get_listing_saledate()) { ?>
		<em><?php the_listing_saledate(); ?></em>
		<?php } ?>
	</p>
    </div>
  <?php endforeach; ?>
  </div>

 <?php endif; ?>

<?php } ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
