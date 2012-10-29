/**
 * ScrollTo Plugin
 * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * @author Ariel Flesler
 * @version 1.4.3.1
 */
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/**
 * Source for Twitter Wall
 */
TwitterWall = function(selector, apiRoot) {

	var that = this;

	this.apiRoot = apiRoot;
	this.ticks = 0;
	this.current = 0;

	this.defaultQueryParameters = {
		p: 1,
		perPage: 10,
		q: ''
	};

	this.template = '<div class="searchForm"><input type="search" class="q" name="q" value="" placeholder="search" /></div><div class="tweets"></div>';
	this.el = $(selector).html(this.template);

	this.searchInput = this.el.find('input');
	this.searchInput.bind('keypress', function(event) {
		var keyCode = event.charCode || event.keyCode;
		if (keyCode != 13) {
			return true;
		}
		that.ticks = 0;
		that.loadTweets({
			q: $(this).val()
		});
	});

	this.tick = function()
	{
		that.current++;
		that.ticks++;
		if (that.current >= that.defaultQueryParameters.perPage) {
			that.current = 1;
		}
		if (that.current >= that.el.find('.twitter-tweet-rendered').length) {
			that.current = 1;
		}
		var currentTweet = $('.twitter-tweet-rendered:eq(' + (that.current - 1) + ')');
		that.el.scrollTo(currentTweet, 500);
	},
	window.setInterval(that.tick, 5000);

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
		that.el.find('.tweets').html(rendered);
		if (that.ticks < 1) {
			that.appendTwitterScript();
		}
		return that;
	};

	this.appendTwitterScript = function() 
	{
		$('body').append('<script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>');
		return true;
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