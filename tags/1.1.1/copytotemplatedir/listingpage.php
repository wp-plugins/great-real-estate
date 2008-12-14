<?php
/*
Template Name: Listing Page
*/
?>
<?php get_header(); ?>

	<!-- page content - single listing -->
	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
			<div class="entry">
<?php if (function_exists('get_listing_status')) { ?>
	<?php getandsetup_listingdata(); ?>
<div class="page-propdata-box">
 	<?php $line1 = ''; $line2 = ''; $line3 = ''; ?>

 	<div class="page-blurb"><?php the_listing_blurb(); ?></div>
	<?php if ($bedrooms = get_listing_bedrooms()) 
		$line1 .= "<div>$bedrooms Bedrooms</div>"; ?>
	<?php if ($bathrooms = get_listing_bathrooms()) {
		$line1 .= "<div>$bathrooms Full ";
		if ($halfbaths = get_listing_halfbaths()) 
			$line1 .= "&amp; $halfbaths Half ";
		$line1 .= " Baths</div>"; 
              }	?>
	<?php if ($garage = get_listing_garage()) 
		$line1 .= "<div>$garage Garage Spaces</div>"; ?>
	<?php if ($acsf = get_listing_acsf()) 
		$line2 .= "<div>$acsf Sq/Ft Under Air</div>"; ?>
	<?php if ($totsf = get_listing_totsf()) 
		$line2 .= "<div>$totsf Sq/Ft Total</div>"; ?>
	<?php $acres = get_listing_acres(); ?>
	<?php if ($acres > 0) $line2 .= "<div>$acres Acres</div>"; ?>
	<?php if (get_listing_haspool()) $line3 .= "<div>Private Pool</div>"; ?>
	<?php if (get_listing_haswater()) $line3 .= "<div>Waterfront</div>"; ?>
	<?php if (get_listing_hasgolf()) $line3 .= "<div>On Golf Course</div>"; ?>
 	<?php if ($line1 || $line2 || $line3 || $propstatus) { ?>
      <div class='propdata'>
	<?php if ($line1) echo "<div class='propdata-line'>$line1</div>"; ?>
	<?php if ($line2) echo "<div class='propdata-line'>$line2</div>"; ?>
	<?php if ($line3) echo "<div class='propdata-line propfeatures'>$line3</div>"; ?>
		<h3><?php the_listing_status(); ?>
		<?php if (get_listing_hasclosed()) { ?>
		<?php the_listing_saledate(); ?> for <?php the_listing_saleprice(); ?> - last offered<?php } else { ?>- Offered<?php } ?>  at <?php the_listing_listprice(); ?></h3>
      </div>
</div>
	<?php } ?>
<?php the_listing_description_beforemore(); ?>

<?php } else { ?>
<?php the_content(); // plugin disabled, just spit out the normal content ?>
<?php } ?>

</div>
  
<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>


<p>&nbsp;</p>

<?php if (function_exists('get_listing_status')) { ?>
<div id="listing-container">
	<ul id="tabnav">
	<?php the_listing_map_tab(); // recommend this be first ?>
	<?php the_listing_description_tab(); ?>
	<?php the_listing_gallery_tab(); ?>
	<?php the_listing_video_tab(); ?>
	<?php the_listing_panorama_tab(); ?>
	<?php the_listing_downloads_tab(); ?>
	<?php the_listing_community_tab(); ?>
	</ul>
	<?php the_listing_map_content(); // recommend this be first ?>
	<?php the_listing_description_content(); ?>
	<?php the_listing_gallery_content(); ?>
	<?php the_listing_video_content(); ?>
	<?php the_listing_panorama_content(); ?>
	<?php the_listing_downloads_content(); ?>
	<?php the_listing_community_content(); ?>
</div>
<?php } ?>

</div>
</div>
<?php endwhile; endif; ?>

<?php get_sidebar(); # left sidebar ?>

<?php get_footer(); ?>
