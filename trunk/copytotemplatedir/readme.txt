These template files are ready to go, or you can customize them.

To get started,
 - copy the files to your current theme directory (/wp-content/themes/foo)
 - create a page slugged "listings"
 - select the custom template file "Listings Index" for your listings page (under "Page Template"
 - save the page
 - create a page and make the listings page its parent (under "Page Parent")
 - set the Page Template for this page to "Listings Page"
 - re-load the editor for the page. You can now edit the property features such as number of bedrooms.
 - save the page
 - when you view the page, the template will include your extra "listing" data
 - when you view the index page (the page slugged "listings") you'll see all your current and former listings

To enhance your listings:
 - install the recommended plugins: NextGen Gallery, WordTube, WP_Downloadmanager
 - get a Google API key (for the maps - http://code.google.com/apis/maps/signup.html)
 - create a NextGen gallery full of your listing photos, then select the gallery in the "Edit Page" interface for your listing
 - create a WordTube video, then select the video by editing your listing
 - set up some downloadables (PDFs of your property brochure would be nice) and then select these by editing your listing
 - get the lat and long for your property address and add it to the listing (next version will have an easier way to geocode your property)

The Panorama features will be enabled in the next version. These will require Flash Panorama Player. 


FILES
-----
THEME FILES
page.php - sample single page use as a home page; see the function called 
           when is_front_page is true - displays featured homes
	(DONT COPY THIS OVER YOUR OWN CUSTOMIZED FILES!!!)
listings.php - custom template for listings index - assign this to the page
           slugged "listings"
listingpage.php - custom template for a listing - assign this to any listing

CUSTOM CSS
nobrand.css - sample css file referenced when you add /nobrand/foo/ to the
              permalink of any listing; hides navigation to comply with MLS
(Please also see the CSS files in the great-real-estate/css directory)

CUSTOM RSS
feed-googlebase.php - use with Feed Wrangler to supply Google Base with your
           active listings
feed-trulia.php - use with Feed Wrangler to supply Trulia with your listings
	   NOTE: not verified; Trulia will only feed from one source; if
           your broker is already feeding listings, Trulia will not process
           and they probably will not give you any feedback
feed-zillow.php - use with Feed Wrangler to supply Zillow with your listings
	Zillow has confirmed this feed as of August 1, 2008, although square foot information does not appear to be displaying correctly on Zillow.

