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
   
  class RateBeer_Beer extends RateBeer {
     
        var $BeerID;
     		var $BrewerID;
     		var $BrewerName;
     		var $ContractBrewerID;
     		var $ContractBrewer;
     		var $BeerName;
     		var $RateCount;
     		var $AverageRating;
     		var $Entered;
     		var $OverallPctl;
     		var $StylePctl;
     		var $IsAlias;
     		var $Alcohol;
     		var $Retired;
     		var $Verified;
     		var $UserHadIt;
     		var $UserRating;
     		var $TimeEntered;
     		var $IsOut;
     		var $BeerStyleID;
     		var $BeerStyleName;
     		var $IBU;
     		var $Description;
     		
     		var $BeerURL;
        var $BeerImageURL;
        var $BrewerURL; 
        var $BrewerImageURL;

  /*
  * loads the returned JSON array fields into the class properties
  */
	function set_beer_fields( $beerInfo ){
    $this->BeerID = $beerInfo->BeerID;
    $this->BrewerID = $beerInfo->BrewerID;
    $this->BrewerName = $beerInfo->BrewerName;
    $this->ContractBrewerID = $beerInfo->ContractBrewerID;
    $this->ContractBrewer = $beerInfo->ContractBrewer;
    $this->BeerName = $beerInfo->BeerName;
    $this->RateCount = $beerInfo->RateCount;
    $this->AverageRating = $beerInfo->AverageRating;
    $this->Entered = $beerInfo->Entered;
    $this->OverallPctl = $beerInfo->OverallPctl;
    $this->StylePctl = $beerInfo->StylePctl;
    $this->IsAlias = $beerInfo->IsAlias;
    $this->Alcohol = $beerInfo->Alcohol;
    $this->Retired = $beerInfo->Retired;
    $this->Verified = $beerInfo->Verified;
    $this->UserHadIt = $beerInfo->UserHadIt;
    $this->UserRating = $beerInfo->UserRating;
    $this->TimeEntered = $beerInfo->TimeEntered;
    $this->IsOut = $beerInfo->IsOut;
    
    $this->BeerStyleID = $beerInfo->BeerStyleID;
    $this->BeerStyleName = $beerInfo->BeerStyleName;
    
    $this->IBU = $beerInfo->IBU;
    $this->Description = RateBeer::remove_notes($beerInfo->Description);
    
    $this->BeerURL = $this->get_beer_url($beerInfo->BeerID, $beerInfo->BeerName );
    $this->BeerImageURL = $this->get_beer_image_url($beerInfo->BeerID);
    $this->BrewerURL = $this->get_brewer_url($beerInfo->BrewerID, $beerInfo->BrewerName);
    $this->BrewerImageURL = $this->get_brewer_image_url($beerInfo->BrewerID);
     
    if (empty($this->BeerStyleName) && (!empty($this->BeerStyleID))){
      $this->BeerStyleName = RateBeer_Lookups::get_beer_style($this->BeerStyleID);
     }
     
    if (!empty($this->HadIt)) {
      $this->UserHadIt = $this->HadIt;
     }
	}

  /*
  * Gets the beer info
  * [beerratings-beer id='X']
  */
  function get_beer_info( $attrs, $content = null) {
    // example http://ratebeer.com/json/bff.asp?bd=12&k=YOUR_KEY
		$output = "";
		$beer_ids = explode( ",", $attrs['id'] );
	  
		// if array of ids, then loop
		if ( is_array( $beer_ids ) ) {
			$headerLayout = stripslashes( get_option('beerratings_beerlist_header') );
      $output .= $headerLayout;
			 
			// start looping
			foreach ( $beer_ids as $beer_id ) {
				$beers = $this->retrieve_beer_info( $beer_id );				
				if ($beers->error) {
					return;
				}			 
				
				if (is_array($beers)) { 	
          foreach ( $beers as $beer ) {
            // assign vars
            $this->set_beer_fields($beer);
           
            // get layout
            $layout = stripslashes( get_option('beerratings_beerlist_item') );
            $output .= $this->apply_layout($layout);				            
          }
        }
			}
			
			$footerLayout = stripslashes( get_option('beerratings_beerlist_footer') );
      $output .= $footerLayout;
		}
		
		$output .= $this->get_ratebeer_copyright();		
		return $output;
	}

  /*
	* gets the best beer (top 50) worldwide 
	* [beerratings-bestbeer limit='X' sort='x']
	*/		
  function get_best_beer( $attrs, $content = null) {
		$output = "";	 
		$limit = $attrs['limit'];
		$sort = $attrs['sort'];
		$user_id = "";
		
		if( isset($attrs['userid'])){
      $user_id = $attrs['userid'];
		}
				  
   if ( is_null(  $limit ) || "" == $limit ) {
      $limit = 50;
    } else {
      $limit = intval($limit);
      if($limit > 50){
        $limit = 50;
      }
    }
    	 
		// if array of ids, then loop	 
	  $headerLayout = stripslashes( get_option('beerratings_beerlist_top50_header') );
    $output .= $headerLayout;
			 
		// start looping
		$beers = $this->retrieve_best_beer( $user_id, $sort );				
		if ($beers->error) {
      return;
		}			 
				
		if (is_array($beers)) { 	
		  $counter = 0;
      foreach ( $beers as $beer ) {
        if($counter >= $limit){
          break; // exit foreach
         }
            
         // assign vars
         $this->set_beer_fields($beer);
                     
         // get layout
         $counter += 1;
         $layout = stripslashes( get_option('beerratings_beerlist_top50_item') );
         $output .= $this->apply_layout($layout);				             
       }
    }
			
    $footerLayout = stripslashes( get_option('beerratings_beerlist_top50_footer') );
    $output .= $footerLayout;
		 
		$output .= $this->get_ratebeer_copyright();		
		return $output;
	}
	
  /*
	* gets the best beer by seasonid 
	* [beerratings-bestbeer-season id='X' limit='X' sort='x']
	*/		
  function get_best_beer_by_seasonid( $attrs, $content = null) {
		$output = "";
		$season_ids = explode( ",", $attrs['id'] );		 
		$limit = $attrs['limit'];
		$sort = $attrs['sort'];
		$user_id = '';
		
		if( isset($attrs['userid'])){
      $user_id = $attrs['userid'];
		}
					  
   if ( is_null(  $limit ) || "" == $limit ) {
      $limit = 50;
    } else {
      $limit = intval($limit);
      if($limit > 50){
        $limit =50;
      }
    }
    	 
		// if array of ids, then loop
		if ( is_array( $season_ids ) ) {
			$headerLayout = stripslashes( get_option('beerratings_beerlist_byseason_header') );
      $output .= $headerLayout;
			 
			// start looping
			foreach ( $season_ids as $season_id ) {
				$beers = $this->retrieve_best_beer_by_seasonid( $season_id, $user_id, $sort);				
				if ($beers->error) {
					return;
				}			 
				
				if (is_array($beers)) { 	
				  $counter = 0;
          foreach ( $beers as $beer ) {
           if($counter >= $limit){
              break; // exit foreach
            }
            
            // assign vars
            $this->set_beer_fields($beer);
                     
            // get layout
            $counter += 1;
            $layout = stripslashes( get_option('beerratings_beerlist_byseason_item') );
            $output .= $this->apply_layout($layout);				             
          }
        }
			}
			
			$footerLayout = stripslashes( get_option('beerratings_beerlist_byseason_footer') );
      $output .= $footerLayout;
		}
		
		$output .= $this->get_ratebeer_copyright();		
		return $output;
	}
		  
	/*
	* gets the best beer by countryid 
	* [beerratings-bestbeer-country id='X'] 
	*/		
  function get_best_beer_by_countryid( $attrs, $content = null) {
		$output = "";
		$country_ids = explode( ",", $attrs['id'] );
		$user_id = '';
		$limit = $attrs['limit'];
		$show_retired = true;
	  $sort = $attrs['sort'];
	  
		if( isset($attrs['show_retired'])){
      if( $attrs['show_retired'] == 'false' ) {
        $show_retired = false;
      }      
		}
		
		if( isset($attrs['userid'])){
      $user_id = $attrs['userid'];
		}

   if ( is_null(  $limit ) || "" == $limit ) {
      $limit = 50;
    } else {
      $limit = intval($limit);
      if($limit > 50){
        $limit =50;
      }
    }
    	 
		// if array of ids, then loop
		if ( is_array( $country_ids ) ) {
			$headerLayout = stripslashes( get_option('beerratings_beerlist_bycountry_header') );
      $output .= $headerLayout;
			 
			// start looping
			foreach ( $country_ids as $country_id ) {
				$beers = $this->retrieve_best_beer_by_countryid( $country_id, $user_id, $sort );				
				if ($beers->error) {
					return;
				}			 
				
				if (is_array($beers)) { 	
				  $counter = 0;
          foreach ( $beers as $beer ) {
           if($counter >= $limit){
              break; // exit foreach
            } 
            
            // assign vars
            $this->set_beer_fields($beer);
            
            if($show_retired == false && $this->Retired == true){
              // skip beer
            } else {
              // get layout
              $counter += 1;
              $layout = stripslashes( get_option('beerratings_beerlist_bycountry_item') );
              $output .= $this->apply_layout($layout);				 
            }
          }
        }
			}
			
			$footerLayout = stripslashes( get_option('beerratings_beerlist_bycountry_footer') );
      $output .= $footerLayout;
		}
		
		$output .= $this->get_ratebeer_copyright();		
		return $output;
	}
	
	/*
	* get best beer by style 
	* [beerratings-bestbeer-style id='X']
	*/
	function get_best_beer_by_styleid( $attrs, $content = null) {
		$output = "";
		$style_ids = explode( ",", $attrs['id'] );
		$show_retired = true;
		$limit = $attrs['limit'];
		
		if( isset($attrs['show_retired'])){
      if( $attrs['show_retired'] == 'false' ) {
        $show_retired = false;
      }      
		}
		
    if ( is_null(  $limit ) || "" == $limit ) {
      $limit = 25;
    } else {
      $limit = intval($limit);
      if($limit > 25){
        $limit = 25;
      }
    }
		 
		// if array of ids, then loop
		if ( is_array( $style_ids ) ) {
			$headerLayout = stripslashes( get_option('beerratings_beerlist_bystyle_header') );
      $output .= $headerLayout;
			 
			// start looping			
			foreach ( $style_ids as $style_id ) {        
        // get beer info
        $beerStyleName = RateBeer_Lookups::get_beer_style($style_id);
        
				$beers = $this->retrieve_best_beer_by_styleid( $style_id );				
				if ($beers->error) {
					return;
				}			 
				
				if (is_array($beers)) {  	
          $counter = 0;
          foreach ( $beers as $beer ) {
            if($counter >= $limit){
              break; // exit foreach
            } 
            
            // assign vars            
            $this->set_beer_fields($beer);
            $this->BeerStyleName = $beerStyleName;
            
            if($show_retired == false && $this->Retired == true){
              // skip beer
            } else {
              // get layout
              $counter += 1;
              $layout = stripslashes( get_option('beerratings_beerlist_bystyle_item') );
              $output .= $this->apply_layout($layout);
            }
          }
        }
			}
			
			$footerLayout = stripslashes( get_option('beerratings_beerlist_bystyle_footer') );
      $output .= $footerLayout;
		}
		
		$output .= $this->get_ratebeer_copyright();
		return $output;
	}
	
	/*
	* get beer by place 
	* [beerratings-beer-place id='X']
	*/		 	
	function get_beer_by_placeid( $attrs, $content = null) {
		$output = "";
		$place_ids = explode( ",", $attrs['id'] );
		$show_retired = true;
		
	  if( isset($attrs['show_retired'])){
      if( $attrs['show_retired'] == 'false' ) {
        $show_retired = false;
      }      
		}
		
		// if array of ids, then loop
		if ( is_array( $place_ids ) ) {
			$headerLayout = stripslashes( get_option('beerratings_beerlist_byplace_header') );
      $output .= $headerLayout;
			 
			// start looping
			foreach ( $place_ids as $place_id ) {
				$beers = $this->retrieve_beer_by_placeid( $place_id );				
				if ($beers->error) {
					return;
				}			 
				
				if (is_array($beers)) {  	
          foreach ( $beers as $beer ) {
            // assign vars
            $this->set_beer_fields($beer);
            if($show_retired == false && $this->Retired == true){
              // skip beer
            } else {
              // get layout
              $layout = stripslashes( get_option('beerratings_beerlist_byplace_item') );
              $output .= $this->apply_layout($layout);
            }
          }
        }
			}
			
			$footerLayout = stripslashes( get_option('beerratings_beerlist_byplace_footer') );
      $output .= $footerLayout;
		}
		
		$output .= $this->get_ratebeer_copyright();
		return $output;
	}
	
	/*
	* get beer by brewer 
	* [beerratings-beer-brewer id='X']
	*/					 				
	function get_beer_by_brewerid( $attrs, $content = null) {
		$output = "";
		$brewer_ids = explode( ",", $attrs['id'] );

		$user_id = '';
		$sort = ''; 	  
		$show_retired = true;
		   
		if( isset($attrs['userid'])){
      $user_id = $attrs['userid'];
		}
		
		if( isset($attrs['sort'])){
      $sort = $attrs['sort'];
    }

		if( isset($attrs['show_retired'])){
      if( $attrs['show_retired'] == 'false' ) {
        $show_retired = false;
      }      
		}
		    		  
		// if array of ids, then loop
		if ( is_array( $brewer_ids ) ) {
			$headerLayout = stripslashes( get_option(beerratings_beerlist_bybrewer_header) );
      $output .= $headerLayout;
			 
			// start looping
			foreach ( $brewer_ids as $brewer_id ) {
				$beers = $this->retrieve_beer_by_brewerid( $brewer_id, $user_id, $sort );
				if ($beers->error) {
					return;
				}
				
				if (is_array($beers)) {  	 	
          foreach ( $beers as $beer ) {
            // assign vars
            $this->set_beer_fields($beer);                       
             if($show_retired == false && $this->Retired == true){
              // skip beer
            } else {
              // get layout
              $layout = stripslashes( get_option('beerratings_beerlist_bybrewer_item') );
              $output .= $this->apply_layout($layout);
            }
          }
        }
			}
			
			$footerLayout = stripslashes( get_option('beerratings_beerlist_bybrewer_footer') );
      $output .= $footerLayout;
		}
		
		$output .= $this->get_ratebeer_copyright();
		return $output;
  }
 
  /* 
  * Make JSON/API call for [beerratings-bestbeer id='X']
  */
  function retrieve_best_beer( $user_id, $sort ) {
    // example:  http://ratebeer.com/json/tb.asp?k=<YOUR_KEY>&m=top50&s=1 
     
     // validate user_id
		if (filter_var($user_id, FILTER_VALIDATE_INT) == false) {                
       $user_id = '';
    } 
    
		// fix sorting options
		if($sort == 'rating' ) {
      $sort = 1;
		} elseif ( $sort == 'alpha') {
      $sort = 2;
    } else {   
      $sort = '';
    }
        
    $cache_filename = $this->get_cache_filepath( "bestbeer-" . "-" . $user_id . "-" . $sort );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$beers = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,				
				'm' => 'top50'
			);
			    
			// optional, doesn't work if it's included with null
      if (!empty($user_id)){
        $api_args['u'] = $user_id;
      }
      			   
      // optional, doesn't work if it's included with null
		  if (!empty($sort)){
				$api_args['s'] = $sort;
		  } 
		  
			$url = $this->create_api_url( 'tb.asp', $api_args );		
			$beers = $this->save_cache_file_contents( $url, $cache_filename);		 
		}
		 	
		// var_dump($beers);		
		return $beers;
 }
 
  /* 
  * Make JSON/API call for [beerratings-bestbeer-season id='X']
  */
  function retrieve_best_beer_by_seasonid( $season_id, $user_id, $sort ) {
  // example:  http://ratebeer.com/json/tb.asp?k=<YOUR_KEY>&m=season&s=1 
  	if ( is_null( $season_id ) || "" == $season_id ) {
			throw new Exception("Error");
		}
		
		// validate country_id
		if (filter_var($season_id, FILTER_VALIDATE_INT) == false) {     
       $season_id = 0;
    }
    
     // validate user_id
		if (filter_var($user_id, FILTER_VALIDATE_INT) == false) {                
       $user_id = '';
    } 
     
		// fix sorting options
		if($sort == 'rating' ) {
      $sort = 1;
		} elseif ( $sort == 'alpha') {
      $sort = 2;
    } else {   
      $sort = '';
    }
        
    $cache_filename = $this->get_cache_filepath( "bestbeer-season-" . $season_id . "-" . $user_id .  "-" . $sort );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$beers = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,				
				'm' => 'season',
				'season' => $season_id
			);

			// optional, doesn't work if it's included with null
      if (!empty($user_id)){
        $api_args['u'] = $user_id;
      }
      			   
      // optional, doesn't work if it's included with null
		  if (!empty($sort)){
				$api_args['s'] = $sort;
		  } 
		   
			$url = $this->create_api_url( 'tb.asp', $api_args );		
			$beers = $this->save_cache_file_contents( $url, $cache_filename);		 
		}
		 	
		// var_dump($beers);		
		return $beers;
 }
   
  /* 
  * Make JSON/API call for [beerratings-beer-country id='X']
  */
  function retrieve_best_beer_by_countryid( $country_id, $user_id, $sort ) {
  // example:  http://ratebeer.com/json/tb.asp?k=<YOUR_KEY>&m=country&c=213&u=<userid>
  	if ( is_null( $country_id ) || "" == $country_id ) {
			throw new Exception("Error");
		}
		
		// validate country_id
		if (filter_var($country_id, FILTER_VALIDATE_INT) == false) {     
       $country_id = 0;
    }
    
    // validate user_id
		if (filter_var($user_id, FILTER_VALIDATE_INT) == false) {                
       $user_id = '';
    } 

		// fix sorting options
		if($sort == 'rating' ) {
      $sort = 1;
		} elseif ( $sort == 'alpha') {
      $sort = 2;
    } else {   
      $sort = '';
    }
        
    $cache_filename = $this->get_cache_filepath( "bestbeer-country-" . $country_id . "-" . $user_id . "-" . $sort );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$beers = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,	
				'm' => 'country',			
				'c' => $country_id
			);
			 
			// optional, doesn't work if it's included with null
      if (!empty($user_id)){
        $api_args['u'] = $user_id;
      }
 
      // optional, doesn't work if it's included with null
		  if (!empty($sort)){
				$api_args['s'] = $sort;
		  } 
		  
			$url = $this->create_api_url( 'tb.asp', $api_args );		
			$beers = $this->save_cache_file_contents( $url, $cache_filename);		 
		}
		 	
		// var_dump($beers);		
		return $beers;
 }
    
  /* 
  * Make JSON/API call for [beerratings-beer id='X']
  */
  function retrieve_beer_info( $beer_id ) {
  // example:  http://ratebeer.com/json/bff.asp?k=<YOUR_KEY>&bd=12
  	if ( is_null( $beer_id ) || "" == $beer_id ) {
			throw new Exception("Error");
		}
		
		// validate country_id
		if (filter_var($beer_id, FILTER_VALIDATE_INT) == false) {     
       $beer_id = 0;
    }
    
    $cache_filename = $this->get_cache_filepath( "beer-info-" . $beer_id  );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$beer_info = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,				
				'bd' => $beer_id
			);
			 
			$url = $this->create_api_url( 'bff.asp', $api_args );		
			$beer_info = $this->save_cache_file_contents( $url, $cache_filename);		 
		}
		 	
		// var_dump($placeBeers);		
		return $beer_info;
 }
    
  /* 
  * Make JSON/API call for [beerratings-beer-style id='X']
  */	  
  function retrieve_best_beer_by_styleid( $style_id ) {
    // example: http://www.ratebeer.com/json/style.asp?k=<YOUR_KEY&s=17
  
		if ( is_null( $style_id ) || "" == $style_id ) {
			throw new Exception("Error");
		}
		
		// validate place_id
		if (filter_var($style_id, FILTER_VALIDATE_INT) == false) {                
       $style_id = 0;
    }
    
		$cache_filename = $this->get_cache_filepath( "best-beer-style-" . $style_id );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$beers = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,				
				's' => $style_id
			);
			
			$url = $this->create_api_url( 'style.asp', $api_args );		
			$beers = $this->save_cache_file_contents( $url, $cache_filename);		 
		}
		 	
		// var_dump($placeBeers);		
		return $beers;
	}	

  /* 
  * Make JSON/API call for [beerratings-beer-place id='X']
  */	  
  function retrieve_beer_by_placeid( $place_id ) {
    // example: http://www.ratebeer.com/json/beershere.asp?k=<YOUR_KEY>&pid=985
  
		if ( is_null( $place_id ) || "" == $place_id ) {
			throw new Exception("Error");
		}
		
		// validate place_id
		if (filter_var($place_id, FILTER_VALIDATE_INT) == false) {                
       $place_id = 0;
    }
    
		$cache_filename = $this->get_cache_filepath( "place-beers-" . $place_id );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$placeBeers = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,				
				'pid' => $place_id
			);
			
			$url = $this->create_api_url( 'beershere.asp', $api_args );		
			$placeBeers = $this->save_cache_file_contents( $url, $cache_filename);		 
		}
		 	
		// var_dump($placeBeers);		
		return $placeBeers;
	}	
	
  /* 
  * Make JSON/API call for [beerratings-beer-brewer id='X']
  */	
  function retrieve_beer_by_brewerid( $brewer_id ) {
  // example: http://www.ratebeer.com/json/bw.asp?k=<YOUR_KEY>&b=4275
  
		if ( is_null( $brewer_id ) || "" == $brewer_id ) {
			throw new Exception("Error");
		}
		
		// validate brewer_id
		if (filter_var($brewer_id, FILTER_VALIDATE_INT) == false) {   
       $brewer_id = 0;
    }  
    
    // validate user_id
		if (filter_var($user_id, FILTER_VALIDATE_INT) == false) {
       $user_id = '';
    }
    
		// fix sorting options
		if($sort == 'latest' ) {
      $sort = 1;
		} elseif ( $sort == 'top_raters') {
      $sort = 2;
    } elseif ($sort == 'highest_ratings') {
      $sort = 3;
    } else {
      $sort = '';
    }
    
		$cache_filename = $this->get_cache_filepath( "brewer-beers-" . $brewer_id . "-" . $user_id . "-" . $sort );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$breweryBeers = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,				
				'b' => $brewer_id
			);

			// optional, doesn't work if it's included with null
			if (!empty($user_id)){
				$api_args['u'] = $user_id;
		  }
		  
		  // optional, doesn't work if it's included with null
		  if (!empty($sort)){
				$api_args['s'] = $sort;
		  } 
		  			
			$url = $this->create_api_url( 'bw.asp', $api_args );			 
			$breweryBeers = $this->save_cache_file_contents( $url, $cache_filename);	
		}
		 	
		// var_dump($breweryInfo);		
		return $breweryBeers;
	}	

  /* 
  * Apply the layout
  */		
	function apply_layout( $format, $target = "html" ){
		$output = $format;
		
		//First let's do some conditional placeholder removals
	 	for ($i = 0 ; $i < get_option('beerratings_conditional_recursions', 1); $i++){ 
	 	
      // you can add nested recursions by modifying this setting in your wp_options table
			preg_match_all('/\{([a-zA-Z0-9_]+)\}(.+?)\{\/\1\}/s', $output, $conditionals);
			
			if( count($conditionals[0]) > 0 ) {	 
				foreach( $conditionals[1] as $key => $condition) {
					$show_condition = false;
					
					if ($condition == 'has_brewercity') {
						 // does this beer have a valid city?
						$show_condition = (!empty($this->BrewerCity) );
						
					}elseif ($condition == 'has_contractbrewer') {
						// check if there's a contractbrewer 
						$show_condition = (!empty($this->ContractBrewerID) );
						
					}elseif ($condition == 'has_abv'){
						// does this beer have a valid Alcohol?
						$show_condition = (!empty($this->Alcohol) );	
						
					}elseif ($condition == 'has_ibu'){
						// does this beer have a valid IBU?
						$show_condition = (!empty($this->IBU) );
					
					}elseif ($condition == 'has_overallpctl'){
						// does this beer have a valid OverallPctl?
						$show_condition = (!empty($this->OverallPctl) );
					
					}elseif ($condition == 'has_stylepctl'){
						// does this beer have a valid StylePctl?
						$show_condition = (!empty($this->StylePctl) );
          
          }elseif ($condition == 'has_description'){
						// does this beer have a valid Description?
						$show_condition = (!empty($this->Description) );	
								
					}elseif ($condition == 'is_retired'){
						// is this beer retired?
						$show_condition = ( $this->Retired == 'true');
						
					} elseif ($condition == 'is_not_retired'){
						// is this beer retired?
						$show_condition = ( $this->Retired != 'true');
						
					} elseif ($condition == 'is_alias'){
						// is this beer alias?
						$show_condition = ( $this->IsAlias == 'true');
						
					} elseif ($condition == 'is_not_alias'){
						// is this beer alias?
						$show_condition = ( $this->IsAlias != 'true');
												
					}	elseif ($condition == 'has_avg_rating') {
						// does this beer have an avg rating?
						$show_condition = (!empty($this->AverageRating ));					
					}	 
						 
					if($show_condition){
						// calculate lengths to delete placeholders
						$placeholder_length = strlen($condition) + 2;
						$replacement = substr($conditionals[0][$key], $placeholder_length, strlen($conditionals[0][$key])-($placeholder_length * 2 + 1));
					}else{
						$replacement = '';
					}				
					
					$output = str_replace($conditionals[0][$key], $replacement, $output);
					
					// var_dump($show_condition);
					// var_dump($conditionals[0][$key]);
					// var_dump($replacement);
					// var_dump($output);					
				}
			}
	 	}
	 	 
  	// Now let's check out the placeholders.
	 	preg_match_all("/(#_[A-Za-z0-9]+)?/", $output, $placeholders);
	 	
	 	// stores what's replace
	 	$replaces = array();
	 	  
	 	// loop through regex matches
		foreach($placeholders[1] as $key => $result) {
			$match = true;
			$replace = '';
			$full_result = $placeholders[0][$key]; 
			 
			// get matched term
			switch( $result ){
        case '#_BEERID':	 
					$replace = $this->BeerID;
					break;			
				case '#_BREWERID':	 
					$replace = $this->BrewerID;
					break;
				case '#_BREWERNAME':	 
					$replace = $this->BrewerName;
					break;
				case '#_CONTRACTBREWERID':	 
					$replace = $this->ContractBrewerID;
					break;
				case '#_CONTRACTBREWER':	 
					$replace = $this->ContractBrewer;
					break;
				case '#_BREWERCITY':	 
					$replace = $this->BrewerCity;
					break;
				case '#_BEERNAME':
					$replace = $this->BeerName;
					break;
        case '#_RATECOUNT':	  
					$replace = $this->RateCount;
					break;		
					
				case '#_AVERAGERATING':	 
					$replace = $this->AverageRating;
					break;
        case '#_AVERAGERATING0':  
          if(!empty($this->AverageRating)){
            $replace = round($this->AverageRating);
          }
          break;
        case '#_AVERAGERATING1':  
          if(!empty($this->AverageRating)){
            $replace = round($this->AverageRating,1);
          }
          break;
        case '#_AVERAGERATING2':  
          if(!empty($this->AverageRating)){
            $replace = round($this->AverageRating,2);
          }       
          break;					
										
        case '#_ENTERED':  
          $replace = $this->Entered;
          break;
          
        case '#_OVERALLPCTL':  
          $replace = $this->OverallPctl;
          break;
        case '#_OVERALLPCTL0':  
          if(!empty($this->OverallPctl)){
            $replace = round($this->OverallPctl);
          }
          break;
        case '#_OVERALLPCTL1':  
          if(!empty($this->OverallPctl)){
            $replace = round($this->OverallPctl,1);
          }
          break;
        case '#_OVERALLPCTL2':  
          if(!empty($this->OverallPctl)){
            $replace = round($this->OverallPctl,2);
          }       
          break;
          
        case '#_STYLEPCTL':   
          $replace = $this->StylePctl;
          break;
        case '#_STYLEPCTL0':  
          if(!empty($this->StylePctl)){
            $replace = round($this->StylePctl);
          }
          break;  
        case '#_STYLEPCTL1':  
          if(!empty($this->StylePctl)){
            $replace = round($this->StylePctl,1);
          }
          break;
        case '#_STYLEPCTL2':  
          if(!empty($this->StylePctl)){
            $replace = round($this->StylePctl,2);
          }       
          break;
                    
        case '#_ISALIAS':    
          $replace = $this->IsAlias;
          break;
        case '#_ALCOHOL':    
          $replace = $this->Alcohol;
          break;
        case '#_RETIRED':    
          $replace = $this->Retired;
          break; 
        case '#_VERIFIED':   
          $replace = $this->Verified;
          break; 
        case '#_USERHADIT':    
          $replace = $this->UserHadIt;
          break;
        case '#_USERRATING':    
          $replace = $this->UserRating;
          break;
        case '#_BEERSTYLENAME':    
          $replace = $this->BeerStyleName;
          break;
        case '#_IBU':    
          $replace = $this->IBU;
          break;
        case '#_DESCRIPTION':
					$replace = $this->Description;
					break;      
        
        case '#_BEERURL':    
          $replace = $this->BeerURL;
          break;
        case '#_BEERLINK':
          $replace = "<a href='" . $this->BeerURL . "'>" . $this->BeerName . "</a>";
          break;
                    
        case '#_BEERIMAGEURL':
          $replace = $this->BeerImageURL;
          break;
        case '#_BEERIMAGE':
          $replace = "<img src='" . $this->BeerImageURL . "' alt='" . $this->BeerName . "'/>";
          break;
          
        case '#_BREWERURL':
          $replace = $this->BrewerURL;
          break;
        case '#_BREWERLINK':
          $replace = "<a href='" . $this->BrewerURL . "'>" . $this->BrewerName . "</a>";
          break;
          
        case '#_BREWERIMAGEURL':
          $replace = $this->BrewerImageURL;
          break;
        case '#_BREWERIMAGE':
          $replace = "<img src='" . $this->BrewerImageURL . "' alt='" . $this->BrewerName . "'/>";
          break;                  
                
				default:
					$replace = $full_result;
					break;
   	   }
		
		  if(!empty($replace)){ 
        $output = str_replace( $result, $replace , $output );
      } 		
    } 
    
		 return $output;  
  } 
}
?>