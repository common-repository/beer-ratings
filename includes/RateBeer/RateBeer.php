<?php
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
   
 class RateBeer { 
   	var $api_url = 'http://www.ratebeer.com/json/';
	  var $api_key;	  
	  var $cacheManager;

  /*
	* Constructor
	*/ 	  
  function RateBeer() {   
		$this->api_key = get_option( 'beerratings_ratebeer_apikey' );
		$this->cacheManager = new BeerRatings_CacheManager();
		
		if ( "" == $this->api_key ) {
			return;
		}
		
		wp_enqueue_style('my-style', get_bloginfo('wpurl') . '/wp-content/plugins/beer-ratings/assets/css/style.css');
  }
	  
  /*
	* Builds the RateBeer API URL
	*/
  function create_api_url( $endpoint, $args = array() ) {
		$http_args = http_build_query( $args );
		$url = $this->api_url . $endpoint . '?' . $http_args;	 		
		return $url;
	}

  /*
	* retrieves the URL for the beer image
	*/
  function get_beer_image_url( $BeerID ) {
    $output = 'http://www.ratebeer.com/beerimages/' . $BeerID .'.jpg';
    return $output;
  }

  /*
	* retrieves the URL for the beer  
	*/  
  function get_beer_url( $BeerID, $BeerName ){
    $beerSlug = RateBeer::create_slug($BeerName);
    $output = 'http://www.ratebeer.com/beer/' . $beerSlug . '/' . $BeerID . '/';
    return $output;
  } 

  /*
	* retrieves the URL for the brewer image
	*/
  function get_brewer_image_url( $BrewerID ) {
    $output = 'http://www.ratebeer.com/BrewerImages/' .$BrewerID . '.jpg';
    return $output;
  }

  /*
	* retrieves the URL for the brewer image
	*/  
  function get_brewer_url( $BrewerID, $BrewerName ){
    $brewerSlug = RateBeer::create_slug($BrewerName);
    $output = 'http://www.ratebeer.com/brewers/' . $brewerSlug . '/' . $BrewerID . '/';
    return $output;
  } 

  /*
	* retrieves the URL for the place
	*/      
  function get_place_url( $PlaceID, $PlaceName ){
    $placeSlug = RateBeer::create_slug($PlaceName);
    $output = 'http://www.ratebeer.com/p/' . $placeSlug . '/' . $PlaceID . '/';
    return $output;
  } 
  
  /*
	* retrieves the URL for the user  
	*/  
  function get_user_url( $UserID ){ 
    $output = 'http://www.ratebeer.com/user/' . $UserID . '/';
    return $output;
  } 
  
	/*
	*  RateBeer's Required copyright text 
  */
  function get_ratebeer_copyright(){
    /* 
     Use of RateBeer's content requires acknowledgement in a visible, 
     readable form on a) every page that returns RateBeer data , and 
     b) on your application's "splash"/introductory page or header. 
     The smallest area on a page that references and acknowledges 
     RateBeer is required to be at least 100 x 16 pixels. 
     "Data source: RateBeer", "Beer scores provided by RateBeer", 
     "Beer data by RateBeer" and "beer data source: ratebeer" 
     are all just fine. 
    */
    
    $copyrighttext_option = get_option('beerratings_ratebeer_copyrighttext');
    $copyrighttext = 'Data Source: RateBeer';
    
    switch($copyrighttext_option){
      case '0':
        $copyrighttext = 'Data Source: RateBeer';
        break;
      case '1':
        $copyrighttext = 'Beer scores provided by RateBeer';
        break;
      case '2':
        $copyrighttext = 'Beer data by RateBeer';
        break;
      case '3':
        $copyrighttext = 'Beer Data Source: RateBeer';
        break; 
    }
   
   // if include link in copyright then fix it 
   if(get_option('beerratings_ratebeer_copyrightlink') == '1'){ 
      $copyrighttext = str_replace('RateBeer', "<a href='http://www.ratebeer.com'>RateBeer</a>", $copyrighttext);
    }
     
    // start div
    $output = "<div class='beerratings_ratebeer_copyright'>";
    
    if(get_option('beerratings_ratebeer_copyrightimage') == '1'){
      // if include image in copyright then add it
      $ratebeerLogo = get_bloginfo('wpurl') . '/wp-content/plugins/beer-ratings/assets/images/ratebeer-api-tiny.png';    
      $output .= "<img src='" . $ratebeerLogo . "' width='46' height='14' alt='RateBeer' /> ";
    }
    
    $output .= $copyrighttext;
    $output .= "</div>";
    
    return $output;
   }
    
	/*
	* gets the cache file absolute filepath
	*/
	function get_cache_filepath( $cache_filename ){    
    $output = $this->cacheManager->get_cache_filepath($cache_filename);
    return $output;
	}

  /*
	*  check if cache file exists 
	*/	 
	function has_cache_file( $cache_filename ){
    $output = $this->cacheManager->has_cache_file($cache_filename);
    return $output; 
	}
	
	/* 
	* get contents of cached file  
	*/
	function get_cache_file_contents( $cache_filename ) {
    $output = $this->cacheManager->get_cache_file_contents($cache_filename);
    return $output;
	}
	
	/* 
	* download JSON file and save to cache 
	*/
	function save_cache_file_contents ( $url, $cache_filename) {
    $output = $this->cacheManager->save_cache_file_contents ( $url, $cache_filename);
    return $output;
	}  
	
	/*
	* create a slug from a string
	*/
	function create_slug($string){
    $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    return $slug;
  }
  
  /*
	* remove RateBeer [Note: ...]
	*/
	function remove_notes($description){
    $newDescription=preg_replace('/\[Note\:.*?\]/', '', $description);
    return $newDescription;
  }
}
?>