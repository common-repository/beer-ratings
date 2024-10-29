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
  
class BeerRatings_Shortcode {

  /*
	*  validate the API key before executing shortcodes
	*/ 
  function validate_api_key(){
     $apiKey = get_option('beerratings_ratebeer_apikey');
     if( empty( $apiKey ) ) {
        return "Error: A RateBeer API Key is required.";
     } 
  }

  /*
	* [beerratings-brewer id='X']
	*/ 
	function beerratings_brewer_info( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
     }
     
    $rbb = new RateBeer_Brewer();
    $output = $rbb->get_brewer_info($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;
  }
    
  /*
	* [beerratings-place id='X']
	*/ 
  function beerratings_place_info( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    }
    
    $rbb = new RateBeer_Place();
    $output = $rbb->get_place_info($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;
  }
    
  /*
	* [beerratings-place-search id='X']
	*/ 
  function beerratings_place_search( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    }   
    
    $rbb = new RateBeer_Place();
    $output = $rbb->get_place_search($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;
  }
     
  /*
	* [beerratings-beer-brewer id='X']
	*/ 
  function beerratings_beer_brewer( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    } 
    
    $rbb = new RateBeer_Beer();
    $output = $rbb->get_beer_by_brewerid($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;
  }
    
  /*
	* [beerratings-beer-place id='X']
	*/ 
  function beerratings_beer_place( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    }  
     
    $rbb = new RateBeer_Beer();
    $output = $rbb->get_beer_by_placeid($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;
  }

 /*
	* [beerratings-bestbeer]
	*/ 
  function beerratings_bestbeer( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    }
    
    $rbb = new RateBeer_Beer();
    $output =  $rbb->get_best_beer($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;    
  }
       
  /*
	* [beerratings-bestbeer-style id='X']
	*/ 
  function beerratings_bestbeer_style( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    }
    
    $rbb = new RateBeer_Beer();
    $output =  $rbb->get_best_beer_by_styleid($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;    
  }
    
  /*
	* [beerratings-bestbeer-country id='X']
	*/ 
  function beerratings_bestbeer_country( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    } 
    
    $rbb = new RateBeer_Beer();
    $output =  $rbb->get_best_beer_by_countryid($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;
  }
     
         
  /*
	* [beerratings-bestbeer-season id='X']
	*/ 
  function beerratings_bestbeer_season( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    } 
    
    $rbb = new RateBeer_Beer();
    $output =  $rbb->get_best_beer_by_seasonid($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;
  }
  
  /*
	* [beerratings-beer-review id='X']
	*/ 
  function beerratings_beer_review( $attrs, $content = null) {     
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    }  
     
    $rbbr = new RateBeer_Beer_Review();
    $output = $rbbr->get_beer_reviews($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;    
  }
    
  /*
	* [beerratings-beer id='X']
	*/ 
  function beerratings_beer_info( $attrs, $content = null) {
    $apiKeyValidate = BeerRatings_Shortcode::validate_api_key();
    if(!empty($apiKeyValidate)){
      return $apiKeyValidate;
    }  
    
    $rbb = new RateBeer_Beer();
    $output =  $rbb->get_beer_info($attrs, $content);
    
    if(get_option('beerratings_creditlink') == '1'){
      $output .= BeerRatings::get_plugin_creditlink();
    }
    
    return $output;
  }
   
}
