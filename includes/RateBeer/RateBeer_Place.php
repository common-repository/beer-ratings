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
   
  class RateBeer_Place extends RateBeer {
     
     		var $PlaceID ;
				var $MapID;
			  var $TourID;
			  var $PlaceName;
			  var $PlainPlaceName;
			  var $MSA;
			  var $RegionID;
			  var $Latitude;
			  var $Longitude;
			  var $Description;
			  var $PlaceType;
			  var $Address;
			  var $City;
			  var $StateID;
			  var $CountryID;
			  var $PostalCode;
			  var $PhoneNumber;
			  var $WebSiteURL;
			  var $Facebook;
			  var $Twitter;
			  var $Rating;
			  var $Taps;
			  var $Bottles;
			  var $Wifi;
			  var $Togo;
			  var $Singles;
			  var $RealAles;
			  var $RareBeers;
			  var $Macros;
			  var $Glassware;		
			  var $Smoking;
			  var $Cigars;
			  var $Children;
			  var $Reservations;
			  var $Patio;
			  var $TV;
			  var $Games;
			  var $Scene;
			  var $Staff;
			  var $Parking;
			  var $Music;
			  var $Events;
			  var $Noise;
			  var $Seating;
			  var $Food;
			  var $Comments;
			  var $Hours;
			  var $UserID;
			  var $EditedBy;
			  var $TimeAdded;
			  var $PhoneCC;
			  var $PhoneAC;
			  var $MailOrder;
			  var $MailOrderIntl;
			  var $AvgRating;
			  var $BayMean;
			  var $Percentile;
			  var $RateCount;
			  var $BrewerID;
			  var $TimeEdited;
			  var $Currency;
			  var $Retired;
			  		
				var $State;
				var $Country;
        var $PlaceURL;
  /*
  * loads the returned JSON array fields into the class properties
  */
	function set_place_fields( $placeInfo ) {	 
    // assign vars
    $this->PlaceID = $placeInfo->PlaceID;
    $this->MapID = $placeInfo->MapID;
    $this->TourID = $placeInfo->TourID;
    $this->PlaceName = $placeInfo->PlaceName;
    $this->PlainPlaceName = $placeInfo->PlainPlaceName;
    $this->MSA = $placeInfo->MSA;
    $this->RegionID = $placeInfo->RegionID;
    $this->Latitude = $placeInfo->Latitude;
    $this->Longitude = $placeInfo->Longitude;
    $this->Description = RateBeer::remove_notes($placeInfo->Description);
    $this->PlaceType = $placeInfo->PlaceType;
    $this->Address = $placeInfo->Address;
    $this->City = $placeInfo->City;
    $this->StateID = $placeInfo->StateID;
    $this->CountryID = $placeInfo->CountryID;
    $this->PostalCode = $placeInfo->PostalCode;
    $this->PhoneNumber= $placeInfo->PhoneNumber;
    $this->WebSiteURL= $placeInfo->WebSiteURL;
    $this->Facebook = $placeInfo->Facebook;
    $this->Twitter = $placeInfo->Twitter;
    $this->Rating = $placeInfo->Rating;
    $this->Taps = $placeInfo->Taps;
    $this->Bottles= $placeInfo->Bottles;
    $this->Wifi= $placeInfo->Wifi;
    $this->Togo= $placeInfo->Togo;
    $this->Singles= $placeInfo->Singles;
    $this->RealAles= $placeInfo->RealAles;
    $this->RareBeers= $placeInfo->RareBeers;
    $this->Macros= $placeInfo->Macros;
    $this->Glassware= $placeInfo->Glassware;
    $this->Smoking= $placeInfo->Smoking;
    $this->Cigars= $placeInfo->Cigars;
    $this->Children= $placeInfo->Children;
    $this->Reservations= $placeInfo->Reservations;
    $this->Patio= $placeInfo->Patio;
    $this->TV= $placeInfo->TV;
    $this->Games= $placeInfo->Games;
    $this->Scene= $placeInfo->Scene;
    $this->Staff= $placeInfo->Staff;
    $this->Parking= $placeInfo->Parking;
    $this->Music= $placeInfo->Music;
    $this->Events= $placeInfo->Events;
    $this->Noise= $placeInfo->Noise;
    $this->Seating= $placeInfo->Seating;
    $this->Food= $placeInfo->Food;
    $this->Comments= $placeInfo->Comments;
    $this->Hours= $placeInfo->Hours;
    $this->UserID= $placeInfo->UserID;
    $this->EditedBy= $placeInfo->EditedBy;
    $this->TimeAdded= $placeInfo->TimeAdded;
    $this->PhoneCC= $placeInfo->PhoneCC;
    $this->PhoneAC= $placeInfo->PhoneAC;
    $this->MailOrder= $placeInfo->MailOrder;
    $this->MailOrderIntl= $placeInfo->MailOrderIntl;
    $this->AvgRating= $placeInfo->AvgRating;
    $this->BayMean= $placeInfo->BayMean;
    $this->Percentile= $placeInfo->Percentile;
    $this->RateCount= $placeInfo->RateCount;
    $this->BrewerID= $placeInfo->BrewerID;
    $this->TimeEdited= $placeInfo->TimeEdited;
    $this->Currency= $placeInfo->Currency;
    $this->Retired= $placeInfo->Retired; 
    
    $this->State = RateBeer_Lookups::get_state_name($placeInfo->StateID);
    $this->Country = RateBeer_Lookups::get_country_name($placeInfo->CountryID);				          	
    
    $this->PlaceURL = RateBeer::get_place_url($placeInfo->PlaceID, $placeInfo->PlaceName);
	}
	
  /*
	* get place by id
	* [beerratings-place id='X']
	*/ 					
	function get_place_info( $attrs, $content = null) {
		$output = "";
		$place_ids = explode( ",", $attrs['id'] );
		
		// if array of ids, then loop
		if ( is_array( $place_ids ) ) {
		  $headerLayout = stripslashes( get_option(beerratings_placelist_header) );
      $output .= $headerLayout;
           
			// start looping
			foreach ( $place_ids as $place_id ) {
				$placesInfo = $this->retrieve_place_info( $place_id );
				if ($placesInfo->error) {
					return;
				}
				
				if ( is_array( $placesInfo ) ) {
          foreach ( $placesInfo as $placeInfo ) {
            // assign vars
             $this->set_place_fields($placeInfo);                     
            // get layout
            $layout = stripslashes( get_option(beerratings_placelist_item) );
            $output .= $this->apply_layout($layout);				 
          }
        }
			}
			
			$footerLayout = stripslashes( get_option(beerratings_placelist_footer) );
      $output .= $footerLayout;
		}
		 
		$output .= $this->get_ratebeer_copyright();		  
		return $output;
  }

 	/*
	* get place by geo
	* [beerratings-place-geo miles_radius='X' city='X']
	*/ 
  function get_place_by_geo( $attrs, $content = null) {
    // TO DO
    $output = "";
    $miles_radius = $attr['miles_radius'];
    $city = '';
    $latitude = '';
    $longitude = '';
    
    if( isset($attrs['city'])){
      $city = $attrs['city'];
		}
		
		if( isset($attrs['latitude'])){
      $latitude = $attrs['latitude'];
    }
    
    if( isset($attrs['longitude'])){
      $latitude = $attrs['longitude'];
    }
    
  }
 
 	/*
	* get place by search
	* [beerratings-place-search query='X']
	*/ 
	function get_place_search( $attrs, $content = null) {
		$output = "";
		$search = $attrs['query'];
		
		// if array of ids, then loop 
		$headerLayout = stripslashes( get_option(beerratings_placesearch_header) );
    $output .= $headerLayout;
           
    $placesInfo = $this->retrieve_place_search( $search );
    if ($placesInfo->error) {
      return;
    }
    
    if ( is_array( $placesInfo ) ) {  
      foreach ( $placesInfo as $placeInfo ) {
          // assign vars
          $this->set_place_fields($placeInfo);                     
          // get layout
          $layout = stripslashes( get_option(beerratings_placesearch_item) );
          $output .= $this->apply_layout($layout);				 
      }
		}
		 
	  $footerLayout = stripslashes( get_option(beerratings_placesearch_footer) );
    $output .= $footerLayout;
		
		$output .= $this->get_ratebeer_copyright();
		return $output;
  }
    
  /* 
  * Make JSON/API call for [beerratings-place-search query='X']
  */
  function retrieve_place_search( $search ) {
    // example: http://www.ratebeer.com/json/psstring.asp?k=<YOUR_KEY>&s=vermont 
    if ( is_null( $search ) || "" == $search ) {
      throw new Exception("Error");
    }
		 
    $cache_filename = $this->get_cache_filepath( "place-search-" . $search );
		
    if ( $this->has_cache_file( $cache_filename ) ) {
      $placeInfo = $this->get_cache_file_contents( $cache_filename ) ;
     } else {
      $api_args = array(
        'k' => $this->api_key,				
        's' => $search
      );
        
      $url = $this->create_api_url( 'psstring.asp', $api_args );			 
      $placeInfo = $this->save_cache_file_contents( $url, $cache_filename);	
    }

    return $placeInfo;
  }	

	/* 
  * Make JSON/API call for [beerratings-place id='X']
  */  
  function retrieve_place_info( $place_id) {
  // example: http://www.ratebeer.com/json/pss.asp?k=<YOUR_KEY>&pid=985
  
		if ( is_null( $place_id ) || "" == $place_id ) {
			throw new Exception("Error");
		}
		
		// validate brewer_id
		if (filter_var($place_id, FILTER_VALIDATE_INT) == false) {                
       $place_id = 0;
    }
    
		$cache_filename = $this->get_cache_filepath( "place-info-" . $place_id );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$placeInfo = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,				
				'pid' => $place_id
			);
			
			$url = $this->create_api_url( 'pss.asp', $api_args );			 
			$placeInfo = $this->save_cache_file_contents( $url, $cache_filename);	
		}
		 	
		// var_dump($placeInfo);		
		return $placeInfo;
	}	
	
	/*
	* apply the layout
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
					
					if ($condition == 'has_address') {
						 // does this beer have address?
						$show_condition = (!empty($this->Address) );
						 
          }elseif ($condition == 'has_description'){
						// does this beer have a valid Description?
						$show_condition = (!empty($this->Description) );	
								
					}elseif ($condition == 'is_retired'){
						// is this beer retired?
						$show_condition = ( $this->Retired == 'true');
						
					} elseif ($condition == 'is_not_retired'){
						// is this beer retired?
						$show_condition = ( $this->Retired != 'true');
											 				
					}	elseif ($condition == 'has_avg_rating') {
						// does this beer have an avg rating?
						$show_condition = (!empty($this->AverageRating ));					
          
          } elseif ($condition == 'has_bay_mean') {
						// does this beer have an bay mean?
						$show_condition = (!empty($this->BayMean ));					
					
					}	elseif ($condition == 'has_percentile') {
						// does this beer have an bay mean?
						$show_condition = (!empty($this->Percentile ));					
				
					}	elseif ($condition == 'has_rate_count') {
						// does this beer have a rate count
						$show_condition = (!empty($this->RateCount ));					
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
	 	
  	//Now let's check out the placeholders.
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
				case '#_PLACEID':	 
					$replace = $this->PlaceID;
					break;
				case '#_MAPID':	 
					$replace = $this->MapID;
					break;
				case '#_TOURID':	 
					$replace = $this->TourID;
					break;
				case '#_PLACENAME':	 
					$replace = $this->PlaceName;
					break;
				case '#_PLAINPLACENAME':	 
					$replace = $this->PlainPlaceName;
					break;
				case '#_MSA':	  
					$replace = $this->MSA;
					break;
        case '#_REGIONID':	  
					$replace = $this->RegionID;
					break;		
				case '#_LATITUDE':	 
					$replace = $this->Latitude;
					break;
        case '#_LONGITUDE':  
          $replace = $this->Longitude;
          break;
        case '#_DESCRIPTION':  
          $replace = $this->Description;
          break;
        case '#_PLACETYPE':   
          $replace = $this->PlaceType;
          break;  
        case '#_ADDRESS':    
          $replace = $this->Address;
          break;
        case '#_CITY':    
          $replace = $this->City;
          break;
        case '#_STATEID':    
          $replace = $this->StateID;
          break; 
        case '#_COUNTRYID':   
          $replace = $this->CountryID;
          break; 
        case '#_POSTALCODE':    
          $replace = $this->PostalCode;
          break;         
        case '#_PHONENUMBER':    
          $replace = $this->PhoneNumber;
          break;
        case '#_WEBSITEURL':    
          $replace = $this->WebSiteURL;
          break;
        case '#_FACEBOOK ':   
          $replace = $this->Facebook;
          break; 
        case '#_TWITTER':    
          $replace = $this->Twitter;
          break;
        case '#_RATING':   
          $replace = $this->Rating;
          break; 
        case '#_TAPS':    
          $replace = $this->Taps;
          break;
        case '#_BOTTLES':    
          $replace = $this->Bottles;
          break;
        case '#_WIFI':    
          $replace = $this->Wifi;
          break;
        case '#_TOGO':   
          $replace = $this->Togo;
          break;
        case '#_SINGLES':    
          $replace = $this->Singles;
          break;
        case '#_REALALES':   
          $replace = $this->RealAles;
          break;  
        case '#_RAREBEERS':  
          $replace = $this->RareBeers;
          break;  
        case '#_MACROS':  
          $replace = $this->Macros;
          break;  
        case '#_GLASSWARE':  
          $replace = $this->Glassware;
          break;  
        case '#_SMOKING':  
          $replace = $this->Smoking;
          break; 
        case '#_CIGARS':  
          $replace = $this->Cigars;
          break; 			 
        case '#_CHILDREN':  
          $replace = $this->Children;
          break;  
        case '#_RESERVATIONS':  
          $replace = $this->Reservations;
          break; 
        case '#_PATIO':  
          $replace = $this->Patio;
          break; 
        case '#_TV':  
          $replace = $this->TV;
          break;  
        case '#_GAMES':  
          $replace = $this->Games;
          break; 
        case '#_SCENE':  
          $replace = $this->Scene;
          break;
        case '#_STAFF':  
          $replace = $this->Staff;
          break;  
        case '#_PARKING':  
          $replace = $this->Parking;
          break; 
        case '#_MUSIC':  
          $replace = $this->Music;
          break; 
        case '#_EVENTS':  
          $replace = $this->Events;
          break;  
        case '#_NOISE':  
          $replace = $this->Noise;
          break; 
        case '#_SEATING':  
          $replace = $this->Seating;
          break;           
        case '#_FOOD':  
          $replace = $this->Food;
          break;  
        case '#_COMMENTS':  
          $replace = $this->Comments;
          break; 
        case '#_HOURS':  
          $replace = $this->Hours;
          break;           
        case '#_USERID':  
          $replace = $this->UserID;
          break; 
        case '#_EDITEDBY':  
          $replace = $this->EditedBy;
          break;                     
        case '#_TIMEADDED':  
          $replace = $this->TimeAdded;
          break;           
        case '#_PHONECC':  
          $replace = $this->PhoneCC;
          break; 
        case '#_PHONEAC':  
          $replace = $this->PhoneAC;
          break;           
        case '#_MAILORDER':  
          $replace = $this->MailOrder;
          break;           
        case '#_MAILORDERINTL':  
          $replace = $this->MailOrderIntl;
          break; 
        case '#_AVGRATING':  
          $replace = $this->AvgRating;
          break;           
         case '#_BAYMEAN':  
          $replace = $this->BayMean;
          break;           
        case '#_PERCENTILE':  
          $replace = $this->Percentile;
          break; 
        case '#_RATECOUNT':  
          $replace = $this->RateCount;
          break; 
        case '#_TIMEEDITED':  
          $replace = $this->TimeEdited;
          break;           
        case '#_CURRENCY':  
          $replace = $this->Currency;
          break; 
        case '#_RETIRED':  
          $replace = $this->Retired;
          break;     
                               
				case '#_STATE':	  
					$replace = $this->State;
					break;
        case '#_COUNTRY':	  
					$replace = $this->Country;
					break;
				 
				case '#_PLACEURL':
          $replace = $this->PlaceURL;
          break;
        case '#_PLACELINK':
          $replace = "<a href='" . $this->PlaceURL . "'>" . $this->PlaceName . "</a>";
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