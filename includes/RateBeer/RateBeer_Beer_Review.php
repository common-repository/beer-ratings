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
   
  class RateBeer_Beer_Review extends RateBeer {
     
        var $ResultNum;
     		var $RatingID;
     		var $Appearance;
     		var $Aroma;
     		var $Flavor;
     		var $Mouthfeel;
     		var $Overall;
     		var $TotalScore;
     		var $Comments; 
     		var $TimeEntered;
     		var $TimeUpdated;
     		var $UserID;
     		var $UserName;
     		var $City;
     		var $StateID;
     		var $State;
     		var $CountryID;
     		var $Country;
     		var $RateCount;
     		 
     		var $UserURL;
  /*
  * loads the returned JSON array fields into the class properties
  */
	function set_beer_review_fields( $beerReview ){
    $this->ResultNum = $beerReview->resultNum;
    $this->RatingID = $beerReview->RatingID;
    $this->Appearance = $beerReview->Appearance;
    $this->Aroma = $beerReview->Aroma;
    $this->Flavor = $beerReview->Flavor;
    $this->Mouthfeel = $beerReview->Mouthfeel;
    $this->Overall = $beerReview->Overall;
    $this->TotalScore = $beerReview->TotalScore;
    $this->Comments = $beerReview->Comments;
    $this->TimeEntered = $beerReview->TimeEntered;
    $this->TimeUpdated = $beerReview->TimeUpdated;
    $this->UserID = $beerReview->UserID;
    $this->UserName = $beerReview->UserName;
    $this->City = $beerReview->City;
    $this->StateID = $beerReview->StateID;
    $this->State = $beerReview->State;
    $this->CountryID = $beerReview->CountryID;
    $this->Country = $beerReview->Country;
    $this->RateCount = $beerReview->RateCount;
    
    $this->UserURL = RateBeer::get_user_url($beerReview->UserID);
	}
	
	/* 
  * get the beer reviews 
  * [beerratings-beer-review id='X']
  */			 	
	function get_beer_reviews( $attrs, $content = null) {
		$output = "";
		$beer_id = $attrs['id'];
		$user_id = '';
		$sort = '';
		$page_index = 1;		  
		   
		if( isset($attrs['userid'])){
      $user_id = $attrs['userid'];
		}
		
		if( isset($attrs['sort'])){
      $sort = $attrs['sort'];
    }
    
    if( isset($attrs['page'])){
      $page_index = $attrs['page'];
    }
				
	  $headerLayout = stripslashes( get_option(beerratings_beerreviewlist_header) );
    $output .= $headerLayout;
 
		$beerReviews = $this->retrieve_beer_reviews( $beer_id, $user_id, $sort, $page_index );				
		if ($beerReviews->error) { 
			return;
		}			 
		
		if (is_array($beerReviews)) {	 	
      foreach ( $beerReviews as $beerReview ) {
        // assign vars
        $this->set_beer_review_fields($beerReview);
        // get layout
        $layout = stripslashes( get_option(beerratings_beerreviewlist_item) );
        $output .= $this->apply_layout($layout);				 
      }			
	 }
	 
	 $footerLayout = stripslashes( get_option(beerratings_beerreviewlist_footer) );
   $output .= $footerLayout;
		
		$output .= $this->get_ratebeer_copyright(); 
		return $output;
	}
		 
  /* 
  * Make JSON/API call for [beerratings-beer-review id='X']
  */
  function retrieve_beer_reviews( $beer_id, $user_id, $sort, $page_index ) {
    // example: http://www.ratebeer.com/json/gr.asp?k=<YOUR_KEY>&bid=32329&uid=&s=&p=
  
		if ( is_null( $beer_id ) || "" == $beer_id ) {
			throw new Exception("Error");
		}
		
		// validate beer_id
		if (filter_var($beer_id, FILTER_VALIDATE_INT) == false) {                
       $beer_id = 0;
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
    
    // validate page_index
		if (filter_var($page_index, FILTER_VALIDATE_INT) == false) {                
       $page_index = 1;
    }  
		 
		$cache_filename = $this->get_cache_filepath( "beer-review-" . $beer_id . "-" . $user_id . "-" . $sort . "-" . $page_index );
		
		if ( $this->has_cache_file( $cache_filename ) ) {
			$beerReviews = $this->get_cache_file_contents( $cache_filename ) ;
		} else {
			$api_args = array(
				'k'   => $this->api_key,				
				'bid' => $beer_id,			
				'p'   => $page_index
			);
			
			// optional, doesn't work if it's included with null
			if (!empty($user_id)){
				$api_args['uid'] = $user_id;
		  }
		  
		  // optional, doesn't work if it's included with null
		  if (!empty($sort)){
				$api_args['s'] = $sort;
		  } 
				
			$url = $this->create_api_url( 'gr.asp', $api_args );				
			$beerReviews = $this->save_cache_file_contents( $url, $cache_filename);		 
		}
		 	
		// var_dump($beerReviews);		
		return $beerReviews;
	}	

  /* 
  * apply the layout
  */	 
	function apply_layout( $format, $target = "html" ){
		$output = $format;
		 
  	//Now let's check out the placeholders.
	 	preg_match_all("/(#_[A-Za-z0-9]+)?/", $format, $placeholders);
	 	
	 	// stores what's replace
	 	$replaces = array();
	 	  
	 	// loop through regex matches
		foreach($placeholders[1] as $key => $result) {
			$match = true;
			$replace = '';
			$full_result = $placeholders[0][$key]; 
			 
			// get matched term
			switch( $result ){
        case '#_RESULTNUM':	 
					$replace = $this->ResultNum;
					break;			
				case '#_RATINGID':	 
					$replace = $this->RatingID;
					break;
				case '#_APPEARANCE':	 
					$replace = $this->Appearance;
					break;
				case '#_AROMA':	 
					$replace = $this->Aroma;
					break;
				case '#_FLAVOR':	 
					$replace = $this->Flavor;
					break;
				case '#_MOUTHFEEL':	 
					$replace = $this->Mouthfeel;
					break;
				case '#_OVERALL':
					$replace = $this->Overall;
					break;
        case '#_TOTALSCORE':	  
					$replace = $this->TotalScore;
					break;		
				case '#_COMMENTS':	 
					$replace = $this->Comments;
					break;
        case '#_TIMEENTERED':  
          $replace = $this->TimeEntered;
          break;
        case '#_TIMEUPDATED':  
          $replace = $this->TimeUpdated;
          break;
        case '#_USERID':   
          $replace = $this->UserID;
          break;  
        case '#_USERNAME':    
          $replace = $this->UserName;
          break;
        case '#_CITY':    
          $replace = $this->City;
          break;
        case '#_STATEID':    
          $replace = $this->StateID;
          break; 
        case '#_STATE':   
          $replace = $this->State;
          break; 
        case '#_COUNTRYID':    
          $replace = $this->CountryID;
          break;
        case '#_COUNTRY':    
          $replace = $this->Country;
          break;
        case '#_RATECOUNT':    
          $replace = $this->RateCount;
          break;

        case '#_USERURL':
          $replace = $this->UserURL;
          break;
        case '#_USERLINK':
          $replace = "<a href='" . $this->UserURL . "'>" . $this->UserName . "</a>";
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