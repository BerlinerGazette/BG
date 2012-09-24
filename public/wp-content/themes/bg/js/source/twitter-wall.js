/**
 * Source for Twitter Wall
 */
TwitterWall = function(selector, apiRoot) {

	var that = this;

	this.apiRoot = apiRoot;

	this.defaultQueryParameters = {
		p: 1,
		perPage: 10,
		q: ''
	};

	this.template = '<input type="search" class="q" name="q" value="" placeholder="search" /><div class="tweets"></div>';
	this.el = $(selector).html(this.template);

	this.searchInput = this.el.find('input');
	this.searchInput.bind('keypress', function(event) {
		var keyCode = event.charCode || event.keyCode;
		if (keyCode != 13) {
			return true;
		}
		that.loadTweets({
			q: $(this).val()
		});
	});

	this.tick = function()
	{
		var tweetcontainer = that.el.find('.tweets');
		tweetcontainer.find('div:first').slideUp(function() {
			$(this).appendTo(tweetcontainer).slideDown();
		});
	},
	window.setInterval(that.tick, 3000);

	this.loadTweets = function(params)
	{
		var query = $.extend(that.defaultQueryParameters, params);
		$.getJSON(this.apiRoot, query, that.displayTweets);
		return that;
	};

	this.displayTweets = function(data, status)
	{
		rendered = '';
		if (data.length > 0) {
			for (var i = 0; i < data.length; i++) {
				rendered += that.renderSingleTweet(data[i]);
			}
		}
		that.el.find('.tweets').html(rendered + '<script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>');
		return that;
	};

	this.renderSingleTweet = function(tweet)
	{
		var formattedDate = tweet.created.substr(0, 10) + ' ' + tweet.created.substr(11, 5);
		var rendered = '<blockquote class="twitter-tweet" lang="de"> \
						<p>' + tweet.text + '</p>&mdash; ' + tweet.user.name + ' (@' + tweet.user.username + ') \
						<a href="https://twitter.com/twitterapi/status/' + tweet.id + '" data-datetime="' + tweet.created + '">' + formattedDate + '</a> \
					</blockquote>';
		return rendered;
	};

	Tweets = this.loadTweets();
};