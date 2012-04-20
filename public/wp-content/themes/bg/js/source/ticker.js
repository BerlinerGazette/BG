/**
 * Simple JQuery script that hide/shows elements like a news ticker
 * 
 * @author Marcel Eichner // Ephigenia <love@ephigenia.de>
 */
$(document).ready(function() {
	var ticker = function()
	{
		setTimeout(function() {
			$('#blogpost-ticker li:first').fadeOut(function() {
				$(this).detach().appendTo('#blogpost-ticker');
				$('#blogpost-ticker li:first').fadeIn();
			});
			ticker();
		}, 6000);
	}
	ticker();
});