// JavaScript Document
jQuery(document).ready(function($){
	
	$(".rb-toggleContainer").hide();

	$("h3.rb-toggleHeader").click(function(){
	$(this).toggleClass("rb-active").next().slideToggle("fast");
		return false; //Prevent the browser jump to the link anchor
	});

});
 