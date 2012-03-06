=== New Tag Cloud ===
Contributors: funnydingo
Donate link: 
Tags: tag, cloud, widget
Requires at least: 2.3.0
Tested up to: 2.8.5
Stable tag: 0.7

A small plugin providing a very nice tag cloud

== Description ==
New Tag Cloud is a little WordPress plugin wich generates a tag cloud. New Tag Cloud uses the WordPress own tagging feature, so that you don't need any tagging plugin. You can use New Tag Cloud directly in a post/page, in the theme as PHP code or as widget.

Configurebale options
* widget box title
* how much tags should be shown
* biggest font size
* smallest font size
* font size stepping
* font size type (px, em, ...)
* filtering
* caching

There are much changes in version 0.5 and you should take a look into the notes of this version!

== New in version 0.7 ==
Wow, it's a very long time ago I released the last update. But now New Tag Cloud has some new features!

= Filtering =
No you are able to filter for tags used in defined categories and skip tags. It's very nice because you can say: Only display tags used in categories a, b and c but not the tags home, baby and car. I hope you like it!

= Caching =
That's done too. I've implemented a caching feature. For the first time the cloud is generated normal but after generating, the complete HTML code will be written to the database and for every next time the cloud has to been shown, the plugin can read the complete HTML code from the database and can skip the generating process. That's very cool ;) Oh, the cache will be cleared every time you publish or update an post, so the cloud is up to date (sure, you have the ability to clear the cache manualy).

== New in version 0.6 ==
There is a small new global configuration option "Heading size for widget title". You can choose a size from h1 to h6.

== New in version 0.5 ==
It's done! Version 0.5 comes with much new features. At first a list of the new features and than I'll explain how to use them.

1. Instances: now you're able to create multiple configurations
2. Shortcode: use `[newtagcloud]` and `[newtagclund int=<ID>]`
3. Filter: `<!--new-tag-cloud-->` and `<!--new-tag-cloud-<ID>-->` are availible, but you should use the shortcode instead
4. PHP: `<?php newtagcloud(); ?>` and `<?php newtagcloud(<ID>)` can be used directly in your theme files

OK, now lets look how it works.

= Instances =
This is the new way to create multiple configurations. All "layout specific options" are stored in an instance. Each instance has an ID to wich you can referr. This feature allow you the place different tag clouds on your blog.

= Shortcode =
That's the new way to place tag clouds in posts or pages. Simply add `[newtagcloud]` or `[newtagclound int=<ID>]` to ypur post or page and the tag cloud will be shown there. If you use `[newtagcloud]` the instances set as "Default instance for shortcode" is used. With int=<ID> you can specifiy an instance or example if you need two or more different tag clouds generate via shortcode.

Thanks to [Christian](http://www.christianschenk.org/ "Christian Schenk") for the idea and code.

= Filter =
This is the old way to place a tag cloud in a post or a page. It works in the same way as the shortcode. You should replace the filter with a shortcode and disable the option "Enable filtering of <!--new-tag-cloud--> because it slows down your blog, the shortcode does not!

= PHP =
If you use New Tag Cloud directly in your theme because your theme don't support widgets, you can still use `<?php newtagcloud() ?>`. New: set an instance ID as parameter and the configuration of this instance will be used.

= Misc =
Oh, I forgot it: now you can change the sort order: by name or by count. Also the known problem with mixing tags starting with capitals and tags starting with lower cases is solved ;-)

I hope you like the new version.

== New in version 0.4 ==
Now the plugin supports a filter. So you are able to place "&lt;!--new-tag-cloud--&gt;" in a post or on a page and the tag cloud will be shown there.

Thanks to Christian  for providing the code for this feature!

Also there is a small bug fix. Know it's possible to change to option 'CSS size type'.

== New in version 0.3 ==

*   New way to configure the plugin (Settings > New Tag Cloud)
*   Modify html code before and after the entries
*   Tags of not publish posts are ignored

== New in version 0.2 ==
You can use New Tag Cloud directly from your theme (maybe in footer or if your sidebar don't supports widgets).

Thanks to tekanji for testing ;-)

== Installation ==
Installation is very easy:

1. Upload `newtagcloud.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Check out the 'Widgets' tab in the 'Theme' menu and drag the 'New Tag Cloud' widget to your sidebar.

Using directly from a theme file:
Follow the steps 1 and 2 above. Step 3 is to place this code into your theme file:
`<?php newtagcloud(); ?>`

Or you wan't place a tag cloud in a post or on a page? Use a shortcode: `[newtagcloud]`

== Frequently Asked Questions ==
N/A

== Screenshots ==
1. Place the widget where you want
2. The widget configuration dialog
3. The global configuration
4. The instance configuration
