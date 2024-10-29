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
   
 class BeerRatings_CacheManager {   
	  var $cache_time = 24; // hours
	  var $cache_folder = '/beer-ratings/cache/';
  
  /*
	*  Constructor
	*/	
  function BeerRatings_CacheManager() {       
      $this->cache_time = get_option( 'beerratings_cachetime' ); 
  }
	   
  /*
	*  get cache filename
	*/		  
	function get_cache_filepath( $cache_filename ){
    $output = WP_PLUGIN_DIR . $this->cache_folder . $cache_filename;  
    return $output;
	}

  /*
	*  check if cache file exists
	*/		 
	function has_cache_file( $cache_filepath ){ 
    $cache_time_seconds = $this->cache_time * 60 * 60;
	  if ( file_exists( $cache_filepath ) && time() - filemtime( $cache_filepath) < $cache_time_seconds ) {
			return true;
		} 
		return false;
	}

  /*
	*  get contents of cached file
	*/		 
	function get_cache_file_contents( $cache_filepath ) {
    return unserialize( file_get_contents( $cache_filepath ) );
	}

  /*
	*  download JSON file and save to cache
	*/		 
	function save_cache_file_contents ( $url, $cache_filepath) {
    $jsonBody = wp_remote_retrieve_body( wp_remote_get( $url ) );
		$fileContents = json_decode( $jsonBody );
			
		$file = fopen( $cache_filepath, 'w' );
    fwrite($file, serialize( $fileContents ) );
    fclose( $file );	
    
    return $fileContents;
	}  
	 
}
?>