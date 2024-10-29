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
   
  class RateBeer_Brewer extends RateBeer {
     
     		var $BrewerID ;
				var $BrewerName;
			  var $BrewerDescription;
			  var $BrewerAddress;
			  var $BrewerCity;
			  var $BrewerStateID;
			  var $BrewerCountryID;
			  var $BrewerZipCode;
			  var $BrewerTypeID;
			  var $BrewerType;
			  var $BrewerWebSite;
			  var $Facebook;
			  var $Twitter;
			  var $BrewerEmail;
			  var $BrewerPhone;
			  var $Barrels;
			  var $Opened;
			  var $EnteredOn;
			  var $EnteredBy;
			  var $LogoImage;
			  var $ViewCount;
			  var $Score;
			  var $OOB;
			  var $Retired;
			  var $AreaCode;
			  var $Hours;
			  var $HeadBrewer;
			  var $MetroID;
			  var $MSA;
			  var $RegionID;		
			  		
				var $BrewerState;
				var $BrewerCountry;
				var $BrewerURL;
				var $BrewerImageURL;

  /*
  * loads the returned JSON array fields into the class properties
  */
	function set_brewer_fields( $brewerInfo ) {	 
    // assign vars
    $this->BrewerID = $brewerInfo->BrewerID;
    $this->BrewerName = $brewerInfo->BrewerName;
    $this->BrewerDescription = RateBeer::remove_notes($brewerInfo->BrewerDescription);
    $this->BrewerAddress = $brewerInfo->BrewerAddress;
    $this->BrewerCity = $brewerInfo->BrewerCity;
    $this->BrewerStateID = $brewerInfo->BrewerStateID;
    $this->BrewerCountryID = $brewerInfo->BrewerCountryID;
    $this->BrewerZipCode= $brewerInfo->BrewerZipCode;
    $this->BrewerTypeID= $brewerInfo->BrewerTypeID;
    $this->BrewerType= $brewerInfo->BrewerType;
    $this->BrewerWebSite= $brewerInfo->BrewerWebSite;
    $this->Facebook= $brewerInfo->Facebook;
    $this->Twitter= $brewerInfo->Twitter;
    $this->BrewerEmail= $brewerInfo->BrewerEmail;
    $this->BrewerPhone= $brewerInfo->BrewerPhone;
    $this->Barrels= $brewerInfo->Barrels;
    $this->Opened= $brewerInfo->Opened;
    $this->EnteredOn= $brewerInfo->EnteredOn;
    $this->EnteredBy = $brewerInfo->EnteredBy;
    $this->LogoImage= $brewerInfo->LogoImage;
    $this->ViewCount= $brewerInfo->ViewCount;
    $this->Score= $brewerInfo->Score;
    $this->OOB= $brewerInfo->OOB;
    $this->Retired= $brewerInfo->retired;
    $this->AreaCode= $brewerInfo->AreaCode;
    $this->Hours= $brewerInfo->Hours;
    $this->HeadBrewer= $brewerInfo->HeadBrewer;
    $this->MetroID= $brewerInfo->MetroID;
    $this->MSA= $brewerInfo->MSA;
    $this->RegionID= $brewerInfo->RegionID;
    
    $this->BrewerState = RateBeer_Lookups::get_state_name($brewerInfo->BrewerStateID);
    $this->BrewerCountry = RateBeer_Lookups::get_country_name($brewerInfo->BrewerCountryID);				          	
        
    $this->BrewerURL = $this->get_brewer_url($beerInfo->BrewerID, $beerInfo->BrewerName);
    $this->BrewerImageURL = $this->get_brewer_image_url($beerInfo->BrewerID);
	}

  /*
  * Gets the brewer info
  * [beerratings-brewer id='X']
  */					
	function get_brewer_info( $attrs, $content = null) {
		$output = "";
		$brewer_ids = explode( ",", $attrs['id'] );		
		
		// if array of ids, then loop
		if ( is_array( $brewer_ids ) ) {
		  $headerLayout = stripslashes( get_option(beerratings_brewerlist_header) );
      $output .= $headerLayout;
           
			// start looping
			foreach ( $brewer_ids as $brewer_id ) {
				$brewersInfo = $this->retrieve_brewer_info( $brewer_id );
				if ($brewerInfo->error) {
					return;
				}
				
				if (is_array($brewersInfo)) {	
          foreach ( $brewersInfo as $brewerInfo ) {
            // assign vars
            $this->set_brewer_fields($brewerInfo);                     
            // get layout
            $layout = stripslashes( get_option(beerratings_brewerlist_item) );
            $output .= $this->apply_layout($layout);				 
          }
        }
			}
			
			$footerLayout = stripslashes( get_option(beerratings_brewerlist_footer) );
      $output .= $footerLayout;
		}
		
		$output .= $this->get_ratebeer_copyright();
		return $output;
  }
    
  /* 
  * Make JSON/API call for [beerratings-brewer id='X']
  */
  function retrieve_brewer_info( $brewer_id ) {
    // example: http://www.ratebeer.com/json/bi.asp?k=<YOUR_KEY>&b=4275
  
		if ( is_null( $brewer_id ) || "" == $brewer_id ) {
			throw new Exception("Error");
		}
		
		// validate brewer_id
		if (filter_var($brewer_id, FILTER_VALIDATE_INT) == false) {                
       $brewer_id = 0;
    }
    
		$cache_filename = $this->get_cache_filepath( "brewer-info-" . $brewer_id );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$brewerInfo = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k' => $this->api_key,				
				'b' => $brewer_id
			);
			
			$url = $this->create_api_url( 'bi.asp', $api_args );			 
			$brewerInfo = $this->save_cache_file_contents( $url, $cache_filename);	
		}
		 	
		// var_dump($brewerInfo);		
		return $brewerInfo;
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
					
					if ($condition == 'has_brewerdescription') {
						 // does this beer have a valid BrewerDescription?
						$show_condition = (!empty($this->BrewerDescription) );
						
				  } elseif ($condition == 'has_breweraddress') {
						// check if there's a BrewerCity 
						$show_condition = (!empty($this->BrewerAddress) );										 
						
					}elseif ($condition == 'has_brewerwebsite'){
						// does this beer have a valid BrewerWebSite?
						$show_condition = (!empty($this->BrewerWebSite) );	
						
					}elseif ($condition == 'has_facebook'){
						// does this beer have a valid IBU?
						$show_condition = (!empty($this->Facebook) );
						
					}elseif ($condition == 'has_twitter'){			
						// does this beer have a valid Twitter?
						$show_condition = (!empty($this->Twitter) );
					
					}elseif ($condition == 'has_breweremail'){			
						// does this beer have a valid BrewerEmail?
						$show_condition = (!empty($this->BrewerEmail) );
					
					}elseif ($condition == 'has_brewerphone'){			
						// does this beer have a valid BrewerPhone?
						$show_condition = (!empty($this->BrewerPhone) );
													
					}
						 
					if($show_condition){
						// calculate lengths to delete placeholders
						$placeholder_length = strlen($condition) + 2;
						$replacement = substr($conditionals[0][$key], $placeholder_length, strlen($conditionals[0][$key])-($placeholder_length * 2 + 1));
					}else{
						$replacement = '';
					}				
					
					$output = str_replace($conditionals[0][$key], $replacement, $output);
					 				
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
				case '#_BREWERID':	 
					$replace = $this->BrewerID;
					break;
				case '#_BREWERNAME':	 
					$replace = $this->BrewerName;
					break;
				case '#_BREWERDESCRIPTION':	 
					$replace = $this->BrewerDescription;
					break;
				case '#_BREWERADDRESS':	 
					$replace = $this->BrewerAddress;
					break;
				case '#_BREWERCITY':	 
					$replace = $this->BrewerCity;
					break;
				case '#_BREWERSTATEID':	  
					$replace = $this->BrewerStateID;
					break;
        case '#_BREWERCOUNTRYID':	  
					$replace = $this->BrewerCountryID;
					break;		
				case '#_BREWERZIPCODE':	 
					$replace = $this->BrewerZipCode;
					break;
        case '#_BREWERTYPEID':  
          $replace = $this->BrewerTypeID;
          break;
        case '#_BREWERTYPE':  
          $replace = $this->BrewerType;
          break;
        case '#_BREWERWEBSITE':   
          $replace = $this->BrewerWebSite;
          break;  
        case '#_FACEBOOK':    
          $replace = $this->Facebook;
          break;
        case '#_TWITTER':    
          $replace = $this->Twitter;
          break;
        case '#_BREWEREMAIL':    
          $replace = $this->BrewerEmail;
          break; 
        case '#_BREWERPHONE':   
          $replace = $this->BrewerPhone;
          break; 
        case '#_BARRELS':    
          $replace = $this->Barrels;
          break;
        case '#_OPENED':    
          $replace = $this->Opened;
          break;
        case '#_ENTEREDON':    
          $replace = $this->EnteredOn;
          break;
        case '#_ENTEREDBY ':   
          $replace = $this->EnteredBy;
          break; 
        case '#_LOGOIMAGE':    
          $replace = $this->LogoImage;
          break;
        case '#_VIEWCOUNT':   
          $replace = $this->ViewCount;
          break; 
        case '#_SCORE':    
          $replace = $this->Score;
          break;
        case '#_OOB':    
          $replace = $this->OOB;
          break;
        case '#_RETIRED':    
          $replace = $this->Retired;
          break;
        case '#_AREACODE':   
          $replace = $this->AreaCode;
          break;
        case '#_HOURS':    
          $replace = $this->Hours;
          break;
        case '#_HEADBREWER':   
          $replace = $this->HeadBrewer;
          break;  
        case '#_METROID':  
          $replace = $this->MetroID;
          break;  
        case '#_MSA':  
          $replace = $this->MSA;
          break;  
        case '#_REGIONID':  
          $replace = $this->RegionID;
          break;  			 
          
				case '#_BREWERSTATE':	  
					$replace = $this->BrewerState;
					break;
        case '#_BREWERCOUNTRY':	  
					$replace = $this->BrewerCountry;
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