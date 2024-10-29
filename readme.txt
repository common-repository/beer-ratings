=== Beer Ratings ===
Contributors: jamesewelch
Donate link: http://www.jamesewelch.com/
Tags: beer, breweries, craft beer, beer library, brew, database, brewery
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 1.0.2
License: GPLv2 ror later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Beer Ratings plugin allows you to display information about beers, brewers, and places to drink. The plugin requires a RateBeer API Key.

== Description ==

The Beer Ratings plugin allows you to display information about beers, brewers, and places to drink. This plugin uses the RateBeer API to retrieve data and you must register for a RateBeer API Key at http://www.ratebeer.com/json/ratebeer-api.asp.

You can retrieve beers by brewers, best beers by style, beers available at a specific place, brewer information, place information, and beer information. You can also retrieve beer reviews, rankings, and scores.

== Installation ==

1. Upload `beer-ratings` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Do I need a RateBeer API key to use this plugin? =

Yes. You must have a RateBeer API key to use this plugin.

= How do I get a RateBeer API Key? =

Visit http://www.ratebeer.com/json/ratebeer-api.asp and follow the instructions on the page. The plugin author cannot assist you with the API key request process.

= How do I show brewer information? =

The code below will show information about Stone Brewing Company (brewer ID of 76).

[beerratings-brewer id='76']

= How do I show all beers from a specific brewer? =

The code below will show all of the beers by the Stone Brewing Company (brewer ID of 76).

[beerratings-beer-brewer id='76']

= How do I show place information? =

The code below will show information about SStone Brewing World Bistro and Gardens (place ID of 7319).

[beerratings-place id='7319']

= How do I show beers available at a specific place? =

The code below will show all available beers at the Stone Brewing World Bistro and Gardens (place ID of 7319).

[beerratings-beer-place id='7319']

= How do I show the top 25 IPA beers? =

The code below will show the top 25 beers by style. The Imperial Pale Ale style identifier is 17.

[beerratings-beer-style id='17']

= How do I show beer reviews for a specific beer? =

The code below will show the first 10 reviews (page 1) for Alchemist's Heady Topper (beer ID of 32329).

[beerratings-beer-review id='32329]

= What else can I do? =

Please read the detailed documentation provided on the plugin's settings page.

= How do I find all of these "ids"? =

The IDs can be found in the RateBeer.com's web site URLs. More detailed information is included in the plugin's Settings page on the Help tab.

= How do I customize the output/display? =

Each tag, such as [beerratings-beer], has three settings on the Format/Layouts tab of the plugin settings page. These three settings are:

1. List Header - this HTML is prepended to the item display
1. List Item - this HTML is used for each item display
1. List Footer - this HTML is appended to the item display

This means that when the tag outputs HTML, the List Header will be output first. For example, you include HTML specific tags such as "&lt;table&gt;&lt;tr&gt;&lt;th&gt;Beer Name&lt;/th&gt;&lt;th&gt;Brewer Name&lt;/th&gt;&lt;th&gt;ABV&lt;/th&gt;&lt;/tr&gt;". 

Next, the List Item will be used to loop through the returned records using the specific output fields and output conditions available for that tag. For example, "&lt;tr&gt;&lt;td&gt;#_BEERNAME&lt;/td&gt;&lt;td&gt;#_BREWERNAME&lt;/td&gt;&lt;td&gt;{has_abv}#_ALCOHOL%{/has_abv}&lt;/td&gt;&lt;/tr&gt;". The example format will be output for each returned record. The condition state of {has_abv} means that the inner content will only be rendered if the beer has a non-empty/non-null value for the ABV value (#_ALCOHOL).

Finally, the List Footer will be output last. This is where you would close any &lt;table&gt; or &lt;div&gt; tags. Using the above examples, the footer could be as sample as "&lt;/table&gt;".

== Screenshots ==
1. Example listing of 2 beers
2. Example listing of Best Beer by Style
3. Example listing of all beers by specific brewer
4. Settings tab of options page
5. Format/Layouts tab of options page showing header, footer, item
  
== Changelog ==

= 1.0 =
* initial upload to WordPress plugin gallery

= 1.0.1 =
* Updated help page for #_STYLEPCTL 
* Updated help page for #_OVERALLPCTL
* Updated default item format for [beerratings-beer] to use conditionals around overall and style percentages
* Updated various tags using 'limit' and 'show_retired' attributes to not count retired as part of limit when show_retired='false'.
* Added [beerratings-bestbeer-country] tag and help documentation
* Added [beerratings-bestbeer-season] tag and help documentation

= 1.0.2 =
* Added user_id to  [beerratings-bestbeer-country] 
* Added user_id to  [beerratings-bestbeer-season] 
* Added [beerratings-bestbeer] (top 50) tag and help documentation

== Upgrade Notice ==
 
== Arbitrary section ==
More information can be found at http://www.jamesewelch.com/projects/beer-ratings-wp-plugin
