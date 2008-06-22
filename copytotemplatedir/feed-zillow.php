<?php
/**
 *  CUSTOM   ----   RSS2 Feed Template for Zillow
 *
 * @package WordPress
 */

header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

function zstatus($mystatus) {
	$zstat = array ( 'For Sale' => 'Active',
		   'Sale Pending' => 'Pending',
		   'Sold' => 'Sold' );
	return $zstat["$mystatus"];
}

$siteurl = get_option('siteurl');
$max_pics = 20; /* Zillow max is 50 */

if (!function_exists(get_re_listprice)) {
	return;
}
?>
<?php   

$querystr = "
    SELECT wposts.*
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
    WHERE wposts.ID = wpostmeta.post_id 
    AND wpostmeta.meta_key = 'rehomes_propstatus' 
    AND wposts.post_status = 'publish' 
    AND (wpostmeta.meta_value = 'For Sale' OR wpostmeta.meta_value = 'Sale Pending'
         OR wpostmeta.meta_value = 'Sold')
    AND wposts.post_type = 'page' 
    ORDER BY wposts.post_title ASC
 ";

 $pageposts = $wpdb->get_results($querystr, OBJECT);

 $email = 'roger@rogertheriault.com';

?>
<Listings>
<?php if ($pageposts): ?>
<?php foreach ($pageposts as $post): ?>
    <?php setup_postdata($post); ?>
	<?php $propstatus = get_re_propstatus(); ?>
	<?php $propblurb = get_re_blurb(); ?>
	<?php $listprice = get_re_listprice(); ?>
	<?php $saleprice = get_re_saleprice(); ?>
	<?php if (get_re_saletime()) $saledate = date('Y-m-d',intval(get_re_saletime())); ?>
	<?php $galleryid = get_re_galleryid(); ?>
	<?php $numbr = get_re_numbr(); ?>
	<?php $numfba = get_re_numfba(); ?>
	<?php $numhba = get_re_numhba(); ?>
	<?php $numgar = get_re_numgar(); ?>
	<?php $numacsf = get_re_numacsf(); ?>
	<?php $numtotsf = get_re_numtotsf(); ?>
	<?php $numacres = get_re_numacres(); ?>
	<?php $has_pool = get_re_haspool(); ?>
	<?php $has_water = get_re_haswater(); ?>
	<?php $has_golf = get_re_hasgolf(); ?>
 	<?php $line1 = ''; $line2 = ''; $line3 = ''; ?>
<?php $address = get_re_address();
	$city = get_re_city();
 	$state = get_re_state();
	$zip = get_re_zip();
	$MLSID = get_re_mlsid();  ?>

<?php if ($address && $city && $state && $zip && $propstatus) { ?>
  <Listing>
    <Location>
      <StreetAddress><?php echo $address; ?></StreetAddress>
      <City><?php echo $city; ?></City>
			<State><?php echo $state; ?></State>
			<Zip><?php echo $zip; ?></Zip>
<?php if (($longitude = get_re_longitude()) && 
          ($latitude = get_re_latitude())) {	?>
			<Lat><?php echo $latitude; ?></Lat>
			<Long><?php echo $longitude; ?></Long>
<?php } ?>
			<DisplayAddress>Yes</DisplayAddress>
		</Location>
		<ListingDetails>
			<Status><?php echo zstatus($propstatus); ?></Status>
<?php if ($propstatus == 'Sold') { ?>
			<Price><?php echo $saleprice; ?></Price>
<?php } else { ?>
			<Price><?php echo $listprice; ?></Price>
<?php } ?>
			<ListingUrl><?php the_permalink(); ?></ListingUrl>
			<MlsId><?php echo $MLSID; ?></MlsId>
			<MlsName>RMLS</MlsName>
			<DateListed><?php echo $listdate; ?></DateListed>
<?php if (($propstatus == 'Sold') && $saledate) { ?>
			<DateSold><?php echo $saledate; ?></DateSold>
<?php } ?>
			<VirtualTourUrl><?php the_permalink(); ?></VirtualTourUrl>
		</ListingDetails>
		<BasicDetails>
			<Title><![CDATA[<?php echo $propblurb . " :: "; the_title(); ?>]]></Title>
			<Description><![CDATA[<?php the_excerpt_rss(); ?>]]></Description>
			<Bedrooms><?php echo $numbr; ?></Bedrooms>
			<FullBathrooms><?php echo $numfba; ?></FullBathrooms>
			<HalfBathrooms><?php echo $numhba; ?></HalfBathrooms>
			<LivingArea><?php echo $numacsf; ?></LivingArea>
			<LotSize><?php echo $numacres; ?></LotSize>
		</BasicDetails>
<?php if (function_exists(rehomes_picturelist)) { ?>
	<?php $piclist = rehomes_picturelist(); ?>

	<?php if (!empty($piclist)) { ?>
		<Pictures>
		<?php $picnum = 1; foreach ($piclist as $picture) { ?>
			<?php if ($picnum <= $max_pics) { ?>
			<Picture>
			<PictureUrl><?php echo $siteurl.'/'.$picture->path.'/'.$picture->filename; ?></PictureUrl>
<?php if (!empty($picture->description)) { ?>
			<Caption><?php echo strip_tags(stripslashes($picture->description)); ?></Caption>
<?php } ?>
			</Picture>
			<?php } ?>
		<?php $picnum+= 1;} ?>
		</Pictures>
	<?php } ?>
<?php } ?>
		<Agent>
			<FirstName>Roger</FirstName>
			<LastName>Theriault</LastName>
			<EmailAddress><?php echo $email; ?></EmailAddress>
			<PictureUrl><?php 
		$out = 'http://www.gravatar.com/avatar/';
		$out .= md5( strtolower( $email ) );
 		$out .= '?s=96';
 		echo $out; ?></PictureUrl>
			<MobilePhoneLineNumber>561-827-0899</MobilePhoneLineNumber>
		</Agent>
		<Office>
			<BrokerageName>Illustrated Properties</BrokerageName>
			<StreetAddress>6177 Jog Rd</StreetAddress>
			<UnitNumber>D-6</UnitNumber>
			<City>Lake Worth</City>
			<State>Florida</State>
			<Zip>33467</Zip>
		</Office>
	</Listing>
	<?php } ?>
	<?php endforeach; ?>
<?php endif; ?>
</Listings>
