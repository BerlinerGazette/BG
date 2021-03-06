/**
 *	Simple Plugin for toggling input fields
 *
 *	Usage:
 *		$('input.searchTerm').toggleValue();
 *
 *	Example Usage with custom class:
 *		$('input.searchTerm').toggleValue({toggleClass: 'selected'});
 *	
 *	@author Marcel Eichner // Ephigenia <love@ephigenia.de>
 *	@since 2009-06-22
 */
(function($){
	$.fn.extend({
		toggleValue: function (options) {
			// iterate over selected elements
			return this.each(function() {
				
				// default options
				defaults = {
					value: $(this).val(),
					toggleClass: 'active'
				};
				var options = $.extend({}, defaults, options);
				$(this).data('toggleValueOptions', options);
				
				// add focus and blur behavior
				$(this).focus(function() {
					if ($(this).val() == options.value) {
						$(this).val('').toggleClass(options.toggleClass);
					}
				}).blur(function() {
					if ($.trim($(this).val()) === '') {
						$(this).val(options.value).toggleClass(options.toggleClass);
					}
				});
				
			});
		} // toggleValue
	}); // extend
})(jQuery);

/**
 * Berliner Gazette Theme Script
 * @author Marcel Eichner // Ephigenia <love@ephigenia.de>
 * @since 2010-01-17
 */
$(document).ready(function() {
	
	$('#searchForm input[type=text], #NewsletterForm input[type=text]').toggleValue();

	// Twitter Wall
	if ($('.db-twitter-wall').length > 0) {
		var wall = new TwitterWall('.db-twitter-wall', 'http://twitter-wall.berlinergazette.de/?ajax=1');
	}
	
	// autolink dropdowns with urls
	$('select.autoLink').change(function(e) {
		var url = $(this).val();
		if (url.length > 0) {
			document.location.href = url;
		}
		return true;
	});

	// Flattr Button integration
	var s = document.createElement('script');
	var t = document.getElementsByTagName('script')[0];
	s.type = 'text/javascript';
	s.async = true;
	s.src = '//api.flattr.com/js/0.6/load.js?'+
			'mode=auto&uid=BerlinerGazette&language=de_DE&category=text';
	t.parentNode.insertBefore(s, t);

});


// google analytics
var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20944588-1']);
  _gaq.push(['_trackPageview']);
  (function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();