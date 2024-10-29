<?php 
   /*
    Copyright 2012 James Welch 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  */

/* the activate, deactivate, and static vars */
class BeerRatings {
  	public static $homepage			  = 'http://www.jamesewelch.com/projects/beer-ratings-wp-plugin/';
		public static $wp_supportpage 	= 'http://wordpress.org/support/plugin/beer-ratings/';
		public static $wp_homepage 		= 'http://wordpress.org/extend/plugins/beer-ratings/';
		
  /*
	*  Constructor
	*/	
  function BeerRatings() {
      register_activation_hook(  'BeerRatings', 'activate_plugin');
      register_deactivation_hook('BeerRatings', 'deactivate_plugin');
  } 

  /* 
  * gets the plugin credit link
  */
  function get_plugin_creditlink(){
    return "<div class='beerratings_credit'>Beer Ratings WP Plugin by <a href='" . BeerRatings::$homepage . "'>James Welch</a></div>";  
  }
  
  /*
	*  Activate the plugin, add options to database
	*/	  
  function activate_plugin() {  
      // settings
      add_option('beerratings_ratebeer_apikey', '');
      add_option('beerratings_cachetime' , 24);
      add_option('beerratings_ratebeer_copyrighttext', 0);
      add_option('beerratings_ratebeer_copyrightlink', 0);
      add_option('beerratings_ratebeer_copyrightimage', 0);
      add_option('beerratings_creditlink', 0);
		
      // brewer info layouts
      add_option('beerratings_brewerlist_header', "<div class='beerratings_brewer_list'>");
		
      add_option('beerratings_brewerlist_item', 
"<div id='brewer-#_BREWERID' style='border: 1px solid #D6D6D6; border-radius: 6px 6px 6px 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25); padding: 4px;margin-bottom:10px;'>
  <b>#_BREWERLINK</b>
  {has_breweraddress} 
     <br/>#_BREWERADDRESS 		 
     <br/>#_BREWERCITY, #_BREWERSTATE #_BREWERCOUNTRY    
  {/has_breweraddress} 
   {has_brewerdescription}<br/>#_BREWERDESCRIPTION{/has_brewerdescription}
</div>" );

      add_option('beerratings_brewerlist_footer', "</div>");
		
      // beers by brewer info layouts
      add_option('beerratings_beerlist_bybrewer_header', "<ol class='beerratings_beer_list'>");
		
      add_option('beerratings_beerlist_bybrewer_item', 
"<li id='beer-#_BEERID' class='beerratings_beer_item'>
  <b>#_BEERLINK</b> {has_overallpctl}(Overall Rating: #OverallPctl2%){/has_overallpctl}
  {has_abv}ABV: #_ALCOHOL%{/has_abv}
</li>" );

      add_option('beerratings_beerlist_bybrewer_footer', "</ol>");
		
			// beers by place info layouts
      add_option('beerratings_beerlist_byplace_header', "<ol class='beerratings_beer_list'>");
		
      add_option('beerratings_beerlist_byplace_item', 
"<li id='beer-#_BEERID' class='beerratings_beer_item'>
  <b>#_BEERLINK</b> {has_avg_rating}(<b>Avg Rating:</b> #_AVERAGERATING2){/has_avg_rating}
</li>" );

      add_option('beerratings_beerlist_byplace_footer', "</ol>");
		 
      // beer reviews layouts
      add_option('beerratings_beerreviewlist_header', "<div class='beerratings_beerreview_list'>");
		
      add_option('beerratings_beerreviewlist_item', 
"<div id='beerreview-#_RATINGID' style='border: 1px solid #D6D6D6; border-radius: 6px 6px 6px 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25); padding: 4px;margin-bottom:10px;'>
  #_RESULTNUM. <b>Reviewed By:</b> #_USERLINK (<b>Total Score:</b> #_TOTALSCORE)<br/>
    <b>Appearance:</b> #_APPEARANCE,
    <b>Aroma:</b> #_AROMA,
    <b>Flavor:</b> #_FLAVOR,
    <b>Mouthfeel:</b> #_MOUTHFEEL,
    <b>Overall:</b> #_OVERALL<br/>  
    <p>#_COMMENTS</p> 
</div>" );

      add_option('beerratings_beerreviewlist_footer', "</div>");
		
		
      // best beer by top50
      add_option('beerratings_beerlist_top50_header', "<ol class='beerratings_beer_list'>");
		
      add_option('beerratings_beerlist_top50_item', 
"<li id='beer-#_BEERID'>
   <b>#_BEERLINK</b> by <b>#_BREWERLINK</b>
   {has_avg_rating}<br/><b>Avg Rating:</b> #_AVERAGERATING2{/has_avg_rating}
</li>" );

      add_option('beerratings_beerlist_top50_footer', "</ol>");
      
      // best beer by style
      add_option('beerratings_beerlist_bystyle_header', "<ol class='beerratings_beer_list'>");
		
      add_option('beerratings_beerlist_bystyle_item', 
"<li id='beer-#_BEERID'>
   <b>#_BEERLINK</b> by <b>#_BREWERLINK</b> 
</li>" );

      add_option('beerratings_beerlist_bystyle_footer', "</ol>");
	  	  
      // best beer by country
      add_option('beerratings_beerlist_bestbycountry_header', "<ol class='beerratings_beer_list'>");
		
      add_option('beerratings_beerlist_bestbycountry_item', 
"<li id='beer-#_BEERID'>
  <b>#_BEERLINK</b><br/>
  <b>Style:</b> #_BEERSTYLENAME {has_avg_rating}<b>Avg Rating:</b> #_AVERAGERATING2{/has_avg_rating}
</li>" );

      add_option('beerratings_beerlist_bestbycountry_footer', "</ol>");
		  
		   // best beer by season
      add_option('beerratings_beerlist_bestbyseason_header', "<ol class='beerratings_beer_list'>");
		
      add_option('beerratings_beerlist_bestbyseason_item', 
"<li id='beer-#_BEERID'>
  <b>#_BEERLINK</b> by <b>#_BREWERLINK</b><br/>
  <b>Style:</b> #_BEERSTYLENAME {has_avg_rating}<b>Avg Rating:</b> #_AVERAGERATING2{/has_avg_rating}
</li>" );

      add_option('beerratings_beerlist_bestbycountry_footer', "</ol>");
      
		  // beer by id
      add_option('beerratings_beerlist_header', "<div class='beerratings_beer_list'>");
		
      add_option('beerratings_beerlist_item', 
"<div id='beer-#_BEERID' style='border: 1px solid #D6D6D6; border-radius: 6px 6px 6px 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25); padding: 4px;margin-bottom:10px;'> 
<b>#_BEERLINK</b> by <b>#_BREWERLINK</b><br/>
<b>Style:</b> #_BEERSTYLENAME {has_abv}<b>ABV:</b> #_ALCOHOL%{/has_abv} {has_ibu}<b>IBU:</b> #_IBU{/has_ibu}
{has_overallpctl}<br/><b>Overall:</b> #_OVERALLPCTL2%{/has_overallpctl} {has_stylepctl}<b>Style:</b> #_STYLEPCTL2%{/has_stylepctl}
{has_description}<br/>#_DESCRIPTION{/has_description}  
</div>" );

      add_option('beerratings_beerlist_footer', "</div>");
      
      // place info layouts
      add_option('beerratings_placelist_header', "<div class='beerratings_place_list'>");
		
      add_option('beerratings_placelist_item', 
"<div id='place-#_PLACEID' style='border: 1px solid #D6D6D6; border-radius: 6px 6px 6px 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25); padding: 4px;margin-bottom:10px;'> 
  <b>#_PLACELINK</b>
  {has_address} 
      <br/>#_ADDRESS
      <br/>#_CITY, #_STATE #_COUNTRY
  {/has_address}
  {has_description}<br/>#_DESCRIPTION{/has_description}
</div>" );

      add_option('beerratings_placelist_footer', "</div>");
		
		  // place search layouts
		  /*
      add_option('beerratings_placesearch_header', "<div class='beerratings_place_search'>");
		
      add_option('beerratings_placesearch_item', 
"<div id='place-#_PLACEID' class='beerratings_place_item'> 
  <div class='name'>#_PLACENAME</a></div>
  <div class='address'>
      <span class='city'>#_CITY</span> 
  </div>
</div>" );

      add_option('beerratings_placesearch_footer', "</div>");	
      */
	}

  /*
	* Deactivate the plugin
	* don't remove saved options from db
	* because plugins are deactivated on
	* updating and then reactivated!
	*/	 
	function deactivate_plugin() {
	}
	  
	/*
	* Uninstall the plugin, remove saved options from db
	*/	 
	function uninstall_plugin() {  
 
		delete_option('beerratings_ratebeer_apikey');
		delete_option('beerratings_cachetime');
		
		delete_option('beerratings_ratebeer_copyrighttext');
    delete_option('beerratings_ratebeer_copyrightlink');
    delete_option('beerratings_ratebeer_copyrightimage');
    delete_option('beerratings_creditlink');
        
		delete_option('beerratings_brewerlist_header');
		delete_option('beerratings_brewerlist_item');
		delete_option('beerratings_brewerlist_footer');
		
		delete_option('beerratings_beerlist_header');
		delete_option('beerratings_beerlist_item');
		delete_option('beerratings_beerlist_footer');
		
		delete_option('beerratings_beerlist_bybrewer_header');
		delete_option('beerratings_beerlist_bybrewer_item');
		delete_option('beerratings_beerlist_bybrewer_footer');
		
		delete_option('beerratings_beerlist_byplace_header');
		delete_option('beerratings_beerlist_byplace_item');
		delete_option('beerratings_beerlist_byplace_footer');
		
		delete_option('beerratings_beerreviewlist_header');
		delete_option('beerratings_beerreviewlist_item');
		delete_option('beerratings_beerreviewlist_footer');
		
		delete_option('beerratings_beerlist_top50_header');
		delete_option('beerratings_beerlist_top50_item');
		delete_option('beerratings_beerlist_top50_footer');
		
		delete_option('beerratings_beerlist_bycountry_header');
		delete_option('beerratings_beerlist_bycountry_item');
		delete_option('beerratings_beerlist_bycountry_footer');

		delete_option('beerratings_beerlist_byseason_header');
		delete_option('beerratings_beerlist_byseason_item');
		delete_option('beerratings_beerlist_byseason_footer');

		delete_option('beerratings_beerlist_bystyle_header');
		delete_option('beerratings_beerlist_bystyle_item');
		delete_option('beerratings_beerlist_bystyle_footer');
						
		delete_option('beerratings_placelist_header');
		delete_option('beerratings_placelist_item');
		delete_option('beerratings_placelist_footer');
		
		// delete_option('beerratings_placesearch_header');
		// delete_option('beerratings_placesearch_item');
		// delete_option('beerratings_placesearch_footer');
	}
	
	/*
	* Display the admin notice if no api key 
	*/
  function admin_notice(){
    $apiKey = get_option('beerratings_ratebeer_apikey');
    if( empty( $apiKey ) ) {
        echo '<div class="error fade">
           <p>Beer Ratings Plugin: You must enter a valid RateBeer API Key.</p>
        </div>';
    } 
	}
	
	 /*
   * A safe way of adding javascripts to a WordPress generated page.
   */
  function admin_enqueue_scripts() {
    wp_enqueue_style(  'beerratings-plugin-options', plugins_url( '/beer-ratings/assets/css/plugin-options.css') , false, '1.0' );
    wp_enqueue_script( 'beerratings-plugin-options', plugins_url( '/beer-ratings/assets/js/plugin-options.js') , array( 'jquery' ), '1.0' );
  }
  
  /*
  * Set up options page
  */	
  function admin_menu() {
	  // $page_title, $menu_title, $capability, $menu_slug, $function 
		add_options_page('Beer Ratings Options', 'Beer Ratings', 'manage_options', 'beerratings-settings', array('BeerRatings_Settings', 'plugin_options'));
	}
	
}
			 