<?php
   /*
   Plugin Name: Beer Ratings
   Plugin URI: http://www.jamesewelch.com/projects/beer-ratings-wp-plugin/
   Description: A plugin to show beer, brewer, and place information using RateBeer.com's API
   Version: 1.0.2
   Author: James Welch
   Author URI: http://www.jamesewelch.com
   License: GPL2
   */
   
   /*
    Copyright 2012  James Welch   

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

/* ---------------------------------- */  
/* Include required files */
require_once(dirname (__FILE__) . '/includes/BeerRatings.php');
require_once(dirname (__FILE__) . '/includes/BeerRatings_Settings.php');
require_once(dirname (__FILE__) . '/includes/BeerRatings_Shortcode.php');
require_once(dirname (__FILE__) . '/includes/BeerRatings_CacheManager.php');

// Include required files - RateBeer specific classes
require_once(dirname (__FILE__) . '/includes/RateBeer/RateBeer.php');
require_once(dirname (__FILE__) . '/includes/RateBeer/RateBeer_Brewer.php');
require_once(dirname (__FILE__) . '/includes/RateBeer/RateBeer_Beer.php');
require_once(dirname (__FILE__) . '/includes/RateBeer/RateBeer_Beer_Review.php');
require_once(dirname (__FILE__) . '/includes/RateBeer/RateBeer_Place.php');
require_once(dirname (__FILE__) . '/includes/RateBeer/RateBeer_Lookups.php');

/* ---------------------------------- */  
/* set register activation hooks      */
register_activation_hook( __FILE__, array( 'BeerRatings', 'activate_plugin' ) );
register_deactivation_hook( __FILE__, array( 'BeerRatings', 'deactivate_plugin' ) );
register_uninstall_hook( __FILE__, array( 'BeerRatings', 'uninstall_plugin' ) );

 /* ---------------------------------- */  
/* add short codes                     */
/* maintain this order due to dashes   */

// places
add_shortcode( 'beerratings-place-search', array( 'BeerRatings_Shortcode', 'beerratings_place_search') ); 	
add_shortcode( 'beerratings-place', array( 'BeerRatings_Shortcode', 'beerratings_place_info') ); 	

// brewers
add_shortcode( 'beerratings-brewer', array( 'BeerRatings_Shortcode', 'beerratings_brewer_info') );

// beers
add_shortcode( 'beerratings-beer-review', array ( 'BeerRatings_Shortcode', 'beerratings_beer_review') );
add_shortcode( 'beerratings-beer-place', array( 'BeerRatings_Shortcode', 'beerratings_beer_place') ); 	
add_shortcode( 'beerratings-beer-brewer', array( 'BeerRatings_Shortcode', 'beerratings_beer_brewer') );
add_shortcode( 'beerratings-beer', array( 'BeerRatings_Shortcode', 'beerratings_beer_info') );

add_shortcode( 'beerratings-bestbeer-style', array ( 'BeerRatings_Shortcode', 'beerratings_bestbeer_style') );
add_shortcode( 'beerratings-bestbeer-season', array ( 'BeerRatings_Shortcode', 'beerratings_bestbeer_season') );  
add_shortcode( 'beerratings-bestbeer-country', array ( 'BeerRatings_Shortcode', 'beerratings_bestbeer_country') );  
add_shortcode( 'beerratings-bestbeer', array ( 'BeerRatings_Shortcode', 'beerratings_bestbeer') );  
  
/* ---------------------------------- */  
/* Add settings link on plugin list page */
function beerratings_add_plugin_links($links, $file) { 
    static $this_plugin;
 
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }
 
    // check to make sure we are on the correct plugin
    if ($file == $this_plugin) {    
      $settings_link = '<a href="options-general.php?page=beerratings-settings">Settings</a>';       
      array_unshift($links, $settings_link);        
    }
    
    return $links; 
}
  
/* ---------------------------------- */  
/* add admin menu, only if is_admin */
if ( is_admin() ) {
	add_action( 'admin_menu', array('BeerRatings', 'admin_menu'));	
	add_action( 'admin_notices',  array('BeerRatings', 'admin_notice')) ;
  add_action( 'admin_print_styles', array('BeerRatings', 'admin_enqueue_scripts')); 
  
  // add links to settings on plugins list page
  add_filter("plugin_action_links", 'beerratings_add_plugin_links', 10, 2);
}
	
?>