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

/* the Settings  pages */
class BeerRatings_Settings {


  /*
	*  build the tab strip
	*/    
	function settings_tabs( $current = 'welcome' ) {	
	    $tabs = array(  'settings' => 'Settings', 'layout' => 'Format/Layouts', 'help' => 'Help', 'faq' => 'FAQ', 'about' => 'About' );
	    echo '<div id="icon-options-general" class="icon32"><br></div>';
	    echo '<h2 class="nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
	        echo "<a class='nav-tab$class' href='?page=beerratings-settings&tab=$tab'>$name</a>";	 
	    }
	    echo '</h2>';
	}

  /*
	*  read the help file contents
	*/
  function get_help_file_contents( $help_filename ){
    $helpfiles_folder = '/beer-ratings/assets/html/';    
    $help_filepath = WP_PLUGIN_DIR . $helpfiles_folder . $help_filename;
    return file_get_contents( $help_filepath );
  }
  
  /*
	*  build the settings page
	*/  
  function plugin_options() { 
	  if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
				
    // start with header
    ?>    
	 <div class="wrap">
    <h2>Beer Ratings Settings</h2>
    
    <?php 
      // add tab strip
			if ( isset ( $_GET['tab'] ) ) {
        BeerRatings_Settings::settings_tabs($_GET['tab']); 
      } else {
        BeerRatings_Settings::settings_tabs('settings');
      }
		?>
		
		<div id="poststuff">			
	<?php	    			
			// add tabbed content	
			if ( $_GET['page'] == 'beerratings-settings' ){ 			
        if ( isset ( $_GET['tab'] ) ){
          $tab = $_GET['tab']; 
        } else {
          $tab = 'settings'; 
        }					
			 	 
				switch ( $tab ){  
          case 'settings' :	
						 if ($_POST && $_POST['save']) {
                update_option('beerratings_ratebeer_apikey', $_POST['beerratings_ratebeer_apikey']);
                
                // only allow ints
                if (filter_var($_POST['beerratings_cachetime'], FILTER_VALIDATE_INT)) {                
                  update_option('beerratings_cachetime', $_POST['beerratings_cachetime']);
                } else {
                  update_option('beerratings_cachetime', 24);
                }
                
                if (filter_var($_POST['beerratings_ratebeer_copyrighttext'], FILTER_VALIDATE_INT)) {                
                  update_option('beerratings_ratebeer_copyrighttext', $_POST['beerratings_ratebeer_copyrighttext']);
                } else {
                  update_option('beerratings_ratebeer_copyrighttext', 0);
                }
                
                if (empty($_POST['beerratings_ratebeer_copyrightlink'])) {
                  update_option('beerratings_ratebeer_copyrightlink', 0);
                } else {
                  update_option('beerratings_ratebeer_copyrightlink', 1);
                }
                
                if (empty($_POST['beerratings_ratebeer_copyrightimage'])) {
                  update_option('beerratings_ratebeer_copyrightimage', 0);
                } else {
                  update_option('beerratings_ratebeer_copyrightimage', 1);
                }
                
                if (empty($_POST['beerratings_creditlink'])) {
                  update_option('beerratings_creditlink', 0);
                } else {
                  update_option('beerratings_creditlink', 1);
                }
                
              } 
              if ($_POST && $_POST['delete_cache']) {
                // delete all cached files!
              }
            ?>            
            <h2>Beer Ratings Settings</h2>
            <form method="post">
            <?php wp_nonce_field( "beerratings-settings-page" ); ?>
            
            <div id="rb-toggleRootContainer">
            
            <h3 class="rb-toggleHeader"><a href="#">RateBeer API Settings</a></h3>
            <div style="display: block;" class="rb-toggleContainer">
              <div class="rb-toggleBlock">                     
                <p><b>Do not share your API Key!</b>
                  You must register for an API key at 
                  <a href="http://www.ratebeer.com/json/ratebeer-api.asp" target="_blank">http://www.ratebeer.com/json/ratebeer-api.asp</a>.
                  For API Key related problems, please contact RateBeer.com. The plugin's author has no involvement with the API Key registration process.
                </p>
                <table class="form-table"> 			                
                  <tr valign="top">
                    <th scope="row"><b>Enter your Rate Beer API Key</b></th>
                    <td><input type="text" name="beerratings_ratebeer_apikey" value="<?php echo get_option('beerratings_ratebeer_apikey'); ?>" /></td>                
                  </tr>
                  <tr valign="top">
                    <th scope="row"><b>Select your copyright statement</b></th>
                    <td><select name='beerratings_ratebeer_copyrighttext'>
                        <option value='0' <?php if(get_option('beerratings_ratebeer_copyrighttext')=='0') echo 'selected' ?>>Data Source: RateBeer</option>
                        <option value='1' <?php if(get_option('beerratings_ratebeer_copyrighttext')=='1') echo 'selected' ?>>Beer scores provided by RateBeer</option>
                        <option value='2' <?php if(get_option('beerratings_ratebeer_copyrighttext')=='2') echo 'selected' ?>>Beer data by RateBeer</option>
                        <option value='3' <?php if(get_option('beerratings_ratebeer_copyrighttext')=='3') echo 'selected' ?>>Beer Data Source: RateBeer</option>
                        </select> 
                    </td>                
                  </tr>
                  <tr valign="top">
                    <th scope="row"><b>Include hyperlink to RateBeer.com in copyright statement?</b></th>
                    <td><input type="checkbox" name="beerratings_ratebeer_copyrightlink" 
                        value="1" <?php if(get_option('beerratings_ratebeer_copyrightlink')=='1') echo " checked='checked' " ?> /></td>                
                  </tr>
                   <tr valign="top">
                    <th scope="row"><b>Include RateBeer image in copyright statement?</b></th>
                    <td><input type="checkbox" name="beerratings_ratebeer_copyrightimage" 
                        value="1" <?php if(get_option('beerratings_ratebeer_copyrightimage')=='1') echo " checked='checked' " ?> /></td>                
                  </tr>
                </table>               
               </div>
             </div>
             
             <h3 class="rb-toggleHeader"><a href="#">Caching</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                <div class="rb-toggleBlock">            
                  <p>
                    <b>Please be kind and use a cache.</b> This plugin will cache the API response within a '/cache/' folder within the plugin install directory.
                    At each API call*, the plugin will check the cache first and see if the response is within the cache time. If so, then the page will load 
                    the API response from the cache folder. If the file is not found or the time exceeds the cache expiration, then the plugin will use the
                    RateBeer API to connect and download/save the data. Using a cache speeds up calls on your site and reduces the server load on RateBeer.com
                    servers. 
                  </p>            
                  <table class="form-table"> 
                    <tr valign="top">
                      <th scope="row"><b>Cache Expiration (Hours)</b></th>
                      <td><input type="text" name="ratebeer_cachetime" value="<?php echo get_option('beerratings_cachetime'); ?>" /><br/>
                          <p class="submit">
                             <input type="submit" class="button-secondary" name='delete_cache' value="<?php _e('Delete Cache') ?>" />
                          </p> 
                      </td>                  
                    </tr>
                  </table> 
                </div>
              </div>
            
              <h3 class="rb-toggleHeader"><a href="#">Show Some Love?</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                <div class="rb-toggleBlock">    
                  <table class="form-table"> 
                   <tr valign="top">
                      <th scope="row"><b>Show plugin credit link on pages?</b></th>
                      <td><input type="checkbox" name="beerratings_creditlink" 
                          value="1" <?php if(get_option('beerratings_creditlink')=='1') echo " checked='checked' " ?> /></td>                
                    </tr>
                  </table>   
                </div>
               </div>
            </div>
             
			       <p class="submit" style="clear:both;">
              <input type="submit" class="button-primary" name="save" value="<?php _e('Save Changes') ?>" />
             </p>
			      </form>
          <?php
          break; 
					case 'layout' : 
					 if ($_POST) {  
					       update_option('beerratings_brewerlist_header', htmlspecialchars_decode( $_POST['beerratings_brewerlist_header']) ); 
                 update_option('beerratings_brewerlist_item', htmlspecialchars_decode( $_POST['beerratings_brewerlist_item']) );
                 update_option('beerratings_brewerlist_footer', htmlspecialchars_decode( $_POST['beerratings_brewerlist_footer']) );  
                 
                 update_option('beerratings_beerlist_header', htmlspecialchars_decode( $_POST['beerratings_beerlist_header']) ); 
                 update_option('beerratings_beerlist_item', htmlspecialchars_decode( $_POST['beerratings_beerlist_item']) );
                 update_option('beerratings_beerlist_footer', htmlspecialchars_decode( $_POST['beerratings_beerlist_footer']) );  
                 
                 update_option('beerratings_beerlist_bybrewer_header', htmlspecialchars_decode( $_POST['beerratings_beerlist_bybrewer_header']) ); 
                 update_option('beerratings_beerlist_bybrewer_item', htmlspecialchars_decode( $_POST['beerratings_beerlist_bybrewer_item']) );
                 update_option('beerratings_beerlist_bybrewer_footer', htmlspecialchars_decode( $_POST['beerratings_beerlist_bybrewer_footer']) );  
                 
                 update_option('beerratings_beerlist_byplace_header', htmlspecialchars_decode( $_POST['beerratings_beerlist_byplace_header']) ); 
                 update_option('beerratings_beerlist_byplace_item', htmlspecialchars_decode( $_POST['beerratings_beerlist_byplace_item']) );
                 update_option('beerratings_beerlist_byplace_footer', htmlspecialchars_decode( $_POST['beerratings_beerlist_byplace_footer']) ); 
                 
                 update_option('beerratings_beerreviewlist_header', htmlspecialchars_decode( $_POST['beerratings_beerreviewlist_header']) ); 
                 update_option('beerratings_beerreviewlist_item', htmlspecialchars_decode( $_POST['beerratings_beerreviewlist_item']) );
                 update_option('beerratings_beerreviewlist_footer', htmlspecialchars_decode( $_POST['beerratings_beerreviewlist_footer']) ); 
                 
                 update_option('beerratings_beerlist_top50_header', htmlspecialchars_decode( $_POST['beerratings_beerlist_top50_header']) ); 
                 update_option('beerratings_beerlist_top50_item', htmlspecialchars_decode( $_POST['beerratings_beerlist_top50_item']) );
                 update_option('beerratings_beerlist_top50_footer', htmlspecialchars_decode( $_POST['beerratings_beerlist_top50_footer']) ); 
                 
                 update_option('beerratings_beerlist_bystyle_header', htmlspecialchars_decode( $_POST['beerratings_beerlist_bystyle_header']) ); 
                 update_option('beerratings_beerlist_bystyle_item', htmlspecialchars_decode( $_POST['beerratings_beerlist_bystyle_item']) );
                 update_option('beerratings_beerlist_bystyle_footer', htmlspecialchars_decode( $_POST['beerratings_beerlist_bystyle_footer']) ); 
                 
                 update_option('beerratings_beerlist_bycountry_header', htmlspecialchars_decode( $_POST['beerratings_beerlist_bycountry_header']) ); 
                 update_option('beerratings_beerlist_bycountry_item', htmlspecialchars_decode( $_POST['beerratings_beerlist_bycountry_item']) );
                 update_option('beerratings_beerlist_bycountry_footer', htmlspecialchars_decode( $_POST['beerratings_beerlist_bycountry_footer']) ); 

                 update_option('beerratings_beerlist_byseason_header', htmlspecialchars_decode( $_POST['beerratings_beerlist_byseason_header']) ); 
                 update_option('beerratings_beerlist_byseason_item', htmlspecialchars_decode( $_POST['beerratings_beerlist_byseason_item']) );
                 update_option('beerratings_beerlist_byseason_footer', htmlspecialchars_decode( $_POST['beerratings_beerlist_byseason_footer']) ); 
                                  
                 update_option('beerratings_placelist_header', htmlspecialchars_decode( $_POST['beerratings_placelist_header']) ); 
                 update_option('beerratings_placelist_item', htmlspecialchars_decode( $_POST['beerratings_placelist_item']) );
                 update_option('beerratings_placelist_footer', htmlspecialchars_decode( $_POST['beerratings_placelist_footer']) ); 
                 
                 // update_option('beerratings_placesearch_header', htmlspecialchars_decode( $_POST['beerratings_placesearch_header']) ); 
                 // update_option('beerratings_placesearch_item', htmlspecialchars_decode( $_POST['beerratings_placesearch_item']) );
                 // update_option('beerratings_placesearch_footer', htmlspecialchars_decode( $_POST['beerratings_placesearch_footer']) ); 
              } 
							?>
							<h2>Beer Ratings Format/Layouts</h2>
							<form method="post">
              <?php wp_nonce_field( "beerratings-settings-page" ); ?>			      
							<div id="rb-toggleRootContainer">
							<h2>Brewer Information</h2>
							<h3 class="rb-toggleHeader"><a href="#">Brewer Listings [beerratings-brewer]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table"> 
                       <tr valign="top">
                        <th scope="row">Brewer list format header</th>
                        <td><textarea rows='3' cols='60' name="beerratings_brewerlist_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_brewerlist_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Brewer list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_brewerlist_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_brewerlist_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Brewer list format footer</th>
                        <td><textarea rows='3' cols='60' name="beerratings_brewerlist_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_brewerlist_footer') ) ); ?></textarea></td>
                      </tr>
                   </table>
                 </div>
              </div>
              
              <h2>Beer Information</h2>
              <h3 class="rb-toggleHeader"><a href="#">Beer Listings [beerratings-beer]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">            			
                       <tr>
                        <th colspan='2'><b></b></th>
                       </tr>
                       <tr valign="top">
                        <th scope="row">Beer list header format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Beer list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_beerlist_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Beer list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_footer') ) ); ?></textarea></td>
                      </tr>
                  </table>
                 </div>
              </div>
                            
              <h3 class="rb-toggleHeader"><a href="#">Beer (By Brewer) Listings [beerratings-beer-brewer]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">            			
                       <tr>
                        <th colspan='2'><b></b></th>
                       </tr>
                       <tr valign="top">
                        <th scope="row">Beer list header format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_bybrewer_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bybrewer_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Beer list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_beerlist_bybrewer_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bybrewer_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Beer list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_bybrewer_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bybrewer_footer') ) ); ?></textarea></td>
                      </tr>
                  </table>
                 </div>
              </div>
             
              <h3 class="rb-toggleHeader"><a href="#">Beer (By Place) Listings [beerratings-beer-place]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">         			 
                       <tr valign="top">
                        <th scope="row">Beer list header format </th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_byplace_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_byplace_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Beer list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_beerlist_byplace_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_byplace_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Beer list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_byplace_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_byplace_footer') ) ); ?></textarea></td>
                      </tr>
                    </table>
                 </div>
              </div>
              
               <h3 class="rb-toggleHeader"><a href="#">Best Beer (Top 50) Listings [beerratings-bestbeer]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">          		 
                       <tr valign="top">
                        <th scope="row">Beer list header format </th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_top50_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_top50_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Beer list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_beerlist_top50_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_top50_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Beer list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_top50_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_top50_footer') ) ); ?></textarea></td>
                      </tr>
                    </table>
                 </div>
              </div>
               
              <h3 class="rb-toggleHeader"><a href="#">Best Beer (By Country) Listings [beerratings-bestbeer-country]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">          		 
                       <tr valign="top">
                        <th scope="row">Beer list header format </th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_bycountry_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bycountry_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Beer list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_beerlist_bycountry_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bycountry_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Beer list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_bycountry_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bycountry_footer') ) ); ?></textarea></td>
                      </tr>
                    </table>
                 </div>
              </div>
              
               <h3 class="rb-toggleHeader"><a href="#">Best Beer (By Season) Listings [beerratings-bestbeer-season]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">          		 
                       <tr valign="top">
                        <th scope="row">Beer list header format </th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_byseason_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_byseason_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Beer list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_beerlist_byseason_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_byseason_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Beer list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_byseason_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_byseason_footer') ) ); ?></textarea></td>
                      </tr>
                    </table>
                 </div>
              </div>
              
               <h3 class="rb-toggleHeader"><a href="#">Best Beer (By Style) Listings [beerratings-bestbeer-style]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">          		 
                       <tr valign="top">
                        <th scope="row">Beer list header format </th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_bystyle_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bystyle_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Beer list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_beerlist_bystyle_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bystyle_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Beer list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerlist_bystyle_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerlist_bystyle_footer') ) ); ?></textarea></td>
                      </tr>
                    </table>
                 </div>
              </div>
              
              <h3 class="rb-toggleHeader"><a href="#">Beer Review Listings [beerratings-beer-review]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">           		 
                       <tr valign="top">
                        <th scope="row">Review list header format </th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerreviewlist_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerreviewlist_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Review list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_beerreviewlist_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerreviewlist_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Review list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_beerreviewlist_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_beerreviewlist_footer') ) ); ?></textarea></td>
                      </tr>
                    </table>
                 </div>
              </div>
               
            <h2>Place Information</h2>
             <h3 class="rb-toggleHeader"><a href="#">Place Listings [beerratings-place]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">           		 
                       <tr valign="top">
                        <th scope="row">Place list header format </th>
                        <td><textarea rows='3' cols='60' name="beerratings_placelist_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_placelist_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Place list item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_placelist_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_placelist_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Place list footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_placelist_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_placelist_footer') ) ); ?></textarea></td>
                      </tr>
                    </table>
                 </div>
              </div>
              <!--
              <h3 class="rb-toggleHeader"><a href="#">Place Search [beerratings-place-search]</a></h3>
              <div style="display: block;" class="rb-toggleContainer">
                  <div class="rb-toggleBlock"> 
                     <table class="form-table">           		 
                       <tr valign="top">
                        <th scope="row">Place search header format </th>
                        <td><textarea rows='3' cols='60' name="beerratings_placesearch_header"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_placesearch_header') ) ); ?></textarea></td>
                      </tr>        			
                      <tr valign="top">
                        <th scope="row">Place search item format</th>
                        <td><textarea rows='10' cols='60' name="beerratings_placesearch_item"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_placesearch_item') ) ); ?></textarea></td>
                      </tr>        			
                       <tr valign="top">
                        <th scope="row">Place search footer format</th>
                        <td><textarea rows='3' cols='60' name="beerratings_placesearch_footer"><?php echo stripslashes ( htmlspecialchars( get_option('beerratings_placesearch_footer') ) ); ?></textarea></td>
                      </tr>
                    </table>
                 </div>
              </div>
              -->
          </div>            
          <br style='clear:both;'/>         
			      <p class="submit" style="clear:both;">
    				<input type="submit" class="button-primary" name="save" value="<?php _e('Save Changes') ?>" />
            </p> 
			      </form>
        	<?php
        	break;
        	case 'help' : 
              echo BeerRatings_Settings::get_help_file_contents( 'settings-help.html');
							?>							
              <br style='clear:both;'/>
              <h3>Need Additional Help?</h3>
              <p>If you need help or have found a bug please visit the 
              <a href="<?php echo BeerRatings::$wp_supportpage ?>">support forums</a>.</p>
        	<?php
        	break;				 
        	case 'faq' : 
        	  $showSavebutton = false; ?>
        	  <h2>Beer Ratings Frequently Asked Questions</h2>
        	  <?php 
              echo BeerRatings_Settings::get_help_file_contents( 'settings-faq.html');             
        	break;				 
        	case 'about' :
        	 $showSavebutton = false;  ?>
						<h2>About Beer Ratings</h2>
            <p>This plugin allows you to display RateBeer.com beer, brewer, and place information on your WordPress blog.</p>
            
            <h3>Support This Plugin</h2>
            <p>
            Have you found this plugin useful? Please help support it's continued development
            with a donation of $5, $10, or $20!
            </p>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
            <p><strong>Short on funds?</strong></p>
            <ul style='list-style:circle;padding-left:10px;'>
              <li><a href="<?php echo BeerRatings::$wp_homepage ?>" target="_blank">Rate Beer Ratings Plugin 5★'s on WordPress.org</a></li>
			        <li>Talk about it on your site and link back to the <a href="<?php echo BeerRatings::$homepage ?>" target="_blank">plugin page</a>.</li>
              <li><a href="http://twitter.com/home?status=I use Beer Ratings Plugin for WordPress by @jamesewelch and you should too - <?php echo urlencode(BeerRatings::$homepage) ?>" target="_blank">Tweet about it</a>.</li>
            </ul> 
            <h3>Need Help?</h3>
            <p>If you need help or have found a bug please visit the 
            <a href="<?php echo BeerRatings::$wp_supportpage ?>">support forums</a>.</p>
          <?php
        	break;	        	
					}			
				} 	      
	  }
}
?>