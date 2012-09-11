=== bbPress Search Widget ===
Contributors: daveshine, deckerweb, danielhuesken
Donate link: http://genesisthemes.de/en/donate/
Tags: bbpress, bbPress 2.0, search, widget, forum, forums, topic, topics, reply, replies, custom post type, search widget, searching, widget-only, deckerweb
Requires at least: 3.2
Tested up to: 3.4
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.opensource.org/licenses/gpl-license.php

This Plugin adds a search widget for the bbPress 2.x forum plugin post types independent from the regular WordPress search.

== Description ==

This **small and lightweight plugin** is pretty much like the regular WordPress search widget but limited to only search the post types of the new and awesome bbPress 2.x forum plugin: forum/forums, topic/topics and reply/replies.

Just drag the widget to your favorite widget area and enjoy finally to have forum-limited search function for your bbPress install ;-).

The plugin is also fully Multisite compatible, you can also network-enable it if ever needed (per site use is recommended).

= New Features Since Version 1.1 =
* Improved search results display for themes and bbPress post type detection/restriction.
* I've changed the behavior of the widget box: the post type selection is now gone! The search happens only in "topics" and "replies" automatically. It doesn't make much sense to search in/for forums so the important stuff in a forum happens in topics & replies. The change was also caused by better template display of the search results. I hope you can live with that. Me and my beta testers feel the new behavior is simpler and therefore better. Enjoy! :-)
* I added two new - fully optional - text fields: "Intro text" and "Outro text" to display for example additional forum or user instructions. Just leave blank to not use them!
* Added more ways to customize the widget appearance: 3 filters for the search label/ search placeholder/ search button text as well as a constant to conditionally remove the search label.
* Improved translation loading.
* Fully WPML compatible!
* Fully Multisite compatible, you can also network-enable it if ever needed (per site use is recommended).
* Tested with WordPress versions 3.3.1 and 3.4-beta - also in debug mode (no stuff there, ok? :)

= Localization =
* English (default) - always included
* German - always included
* .pot file (`bbpress-search-widget.pot`) for translators is also always included :)
* Easy plugin translation platform with GlotPress tool: [Translate "bbPress Search Widget"...](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/bbpress-search-widget)
* *Your translation? - [Just send it in](http://genesisthemes.de/en/contact/)*

[A plugin from deckerweb.de and GenesisThemes](http://genesisthemes.de/en/)

= Feedback =
* I am open for your suggestions and feedback - Thank you for using or trying out one of my plugins!
* Drop me a line [@deckerweb](http://twitter.com/#!/deckerweb) on Twitter
* Follow me on [my Facebook page](http://www.facebook.com/deckerweb.service)
* Or follow me on [+David Decker](http://deckerweb.de/gplus) on Google Plus ;-)

= Tips & More =
* *Plugin tip:* [My bbPress Toolbar / Admin Bar plugin](http://wordpress.org/extend/plugins/bbpress-admin-bar-addition/) -- a great time safer and helper tool :)
* [Also see my other plugins](http://genesisthemes.de/en/wp-plugins/) or see [my WordPress.org profile page](http://profiles.wordpress.org/daveshine/)
* Tip: [*GenesisFinder* - Find then create. Your Genesis Framework Search Engine.](http://genesisfinder.com/)

== Installation ==

1. Upload the entire `bbpress-search-widget` folder to the `/wp-content/plugins/` directory -- or just upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
2. Activate the plugin through the 'Plugins' menu in WordPress
3. On the regular WordPress Widgets settings page just drag the *bbPress Search Widget* to your favorite widget area and you're done :)

= How It Works =
* All is done smoothly under the surface :)
* *Topics*: searches in Topic title, Topic description and note, the first topic entry! (pretty much the topic itself, being the start entry)
* *Replies*: search in Replies to Topics

**Please note:** With version 1.1+ I've changed the behavior of the widget box: the post type selection is now gone! The search happens only in "topics" and "replies" automatically. It doesn't make much sense to search in/for forums so the important stuff in a forum happens in topics & replies. The change was also caused by better template display of the search results. I hope you can live with that. Me and my beta testers feel the new behavior is simpler and therefore better. Enjoy! :-)

**Note for own translation/wording:** For custom and update-secure language files please upload them to `/wp-content/languages/bbpress-search-widget/` (just create this folder) - This enables you to use fully custom translations that won't be overridden on plugin updates. Also, complete custom English wording is possible with that, just use a language file like `bbpress-search-widget-en_US.mo/.po` to achieve that (for creating one see the tools on "Other Notes").

== Frequently Asked Questions ==

= How can I style or remove the label "Search forum in topics and replies for"? =
(1) There's an extra CSS class included for that, named `.bbpsw-label` so you can style it with any rules or just remove this label with `display:none`.

(2) Second option, you can fully remove the label by adding a constant to your theme's/child theme's functions.php file or to a functionality plugin etc.:
`
/** bbPress Search Widget: Remove Search Label */
define( 'BBPSW_SEARCH_LABEL_DISPLAY', false );
`

= How can I change the text of the label "Search forum in topics and replies for"? =
(1) You can use the translation language file to use custom wording for that - for English language the file would be /`wp-content/plugins/bbpress-search-widget/languages/bbpress-search-widget-en_US.mo`. Just via the appropiate language/translation file. For doing that, a .pot/.po file is always included.

(2) Second option: Or you use the built-in filter to change the string. Add the following code to your `functions.php` file of current them/child theme, just like that:
`
add_filter( 'bbpsw_filter_label_string', 'custom_bbpsw_label_string' );
/**
 * bbPress Search Widget: Custom Search Label
 */
function custom_bbpsw_label_string() {
	return __( 'Your custom search label text', 'your-theme-textdomain' );
}
`

= How can I change the text of the placeholder in the search input field? =
(1) See above question: via language file!

(2) Or second option, via built-in filter for your `functions.php` file of theme/child theme:
`
add_filter( 'bbpsw_filter_placeholder_string', 'custom_bbpsw_placeholder_string' );
/**
 * bbPress Search Widget: Custom Placeholder Text
 */
function custom_bbpsw_placeholder_string() {
	return __( 'Your custom placeholder text', 'your-theme-textdomain' );
}
`

= How can I change the text of the search button? =
(1) Again, see above questions: via language file!

(2) Or second option, via built-in filter for your `functions.php` file of theme/child theme:
`
add_filter( 'bbpsw_filter_search_string', 'custom_bbpsw_search_string' );
/**
 * bbPress Search Widget: Custom Search Button Text
 */
function custom_bbpsw_search_string() {
	return __( 'Your custom search button text', 'your-theme-textdomain' );
}
`

All the custom & branding stuff code above as well as theme CSS hacks can also be found as a Gist on GitHub: https://gist.github.com/2394575 (you can also add your questions/ feedback there :)

= How can I further style the appearance of this widget? =
There are CSS classes for every little part included:

* main widget ID: `#bbpress_search-<ID>`
* main widget class: `.widget_bbpress_search`
* intro text: `.bbpsw-intro-text`
* form wrapper ID: `#bbpsw-form-wrapper`
* form: `.bbpsw-search-form`
* form div container: `.bbpsw-form-container`
* search label: `.bbpsw-label`
* input field: `.bbpsw-search-field`
* search button: `.bbpsw-search-submit`
* outro text: `.bbpsw-outro-text`

= How can I style the actual search results? =
This plugin's widget is limited to provide the widget and search functionality itself. Styling the search results output in your THEME or CHILD THEME is beyond the purpose of this plugin. You might style it yourself so it will fit your theme.

= In my theme this widget's display is "crashed" - what could I do? =
Please report in the [support forum here](http://wordpress.org/support/plugin/bbpress-search-widget), giving the correct name of your theme/child theme plus more info from where the theme is and where its documentation is located. For example the "iFeature Lite" theme, found on WordPress.org has issues with the CSS styling. For this example theme you found a CSS fix/hack directly here: https://gist.github.com/2394575#file_theme_ifeature_lite.css ---> Just place this additional CSS styling ad the bottom of this file `/wp-content/themes/ifeature/css/style.css` (please note the `/css/` subfolder here!)

== Screenshots ==

1. bbPress Search Widget in WordPress' widget settings area: default state
2. bbPress Search Widget in a sidebar: default state (shown here with [the free Autobahn Child Theme for Genesis Framework](http://genesisthemes.de/en/genesis-child-themes/autobahn/))
3. bbPress Search Widget in WordPress' widget settings area: with custom intro and outro text
4. bbPress Search Widget in a sidebar: custom intro and outro text shown - all parts can by styled individually, just [see FAQ section here](http://wordpress.org/extend/plugins/bbpress-search-widget/faq/) for custom CSS styling.

== Changelog ==

= 1.2 (2012-05-23) =
* NEW: Added additional plugin help tab on the Widgets admin page.
* UPDATE: Added additional div and wrapper-ID around the search form code to make this whole thing more compatible -- a.k.a styleable -- with more themes out there. This way you can style every tiny little part of the widget display. See also FAQ for a sample CSS fix/hack for the "iFeature Lite" theme.
* UPDATE: Moved all admin-only functions/code from main file to extra admin file which only loads within 'wp-admin', this way it's all  performance-improved! :)
* CODE: Minor code/documentation tweaks and improvements.
* UPDATE: Updated the FAQ documentation here, especially with CSS fixes for "iFeature Lite" theme.
* UPDATE: Updated German translations and also the .pot file for all translators!
* UPDATE: Extended GPL License info in readme.txt as well as main plugin file.

= 1.1 (2012-04-15) =
* UPDATE: Improved the display of the search results, therefore improved compatibility with lots of themes. -- Thanks to German WordPress developer Daniel Hüsken for his helping hand and so becoming a co-author of this plugin! :) Also thanks to Pippin Williams for beta testing and additional advice!
* UPDATE: Removed the post type selection box in the widget - to streamline the performance. It's now more simple and therefore better.
* NEW: Added fully optional intro and outro text areas, so for example you can add additional search or forum instructions - leave blank to not use them.
* NEW: Added filter and constants to make the plugin more customizeable: change search input field "label", "placeholder" and "button" text via filter -- a new constant allows also for custom disabling of the label text! -- See "FAQ" section here for more info on that!
* NEW: Added possibility for custom and update-secure language files for this plugin - just upload them to `/wp-content/languages/buddypress-toolbar/` (just create this folder) - this enables you to use complete custom wording or translations.
* CODE: Minor code and documentation tweaks and improvements.
* UPDATE: Updated readme.txt file for the new features plus documentation.
* UPDATE: Added some new and updated existing screenshots.
* UPDATE: Updated German translations and also the .pot file for all translators!
* NEW: Added banner image on WordPress.org for better plugin branding :)

= 1.0 (2011-10-10) =
* Initial release

== Upgrade Notice ==

= 1.2 =
Several additions & improvements: Added new CSS selector for improved styling & compatibility with themes. Added help tab system. Further optimized loading of admin stuff. Also, updated language files together width German translations.

= 1.1 =
Several changes and improvements - Improved search results display and theme compatibility. The forum post type selection is gone, now automatically searches in topics and replies. Also, updated language files together width German translations.

= 1.0 =
Just released into the wild.

== Plugin Links ==
* [Translations (GlotPress)](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/bbpress-search-widget)
* [User support forums](http://wordpress.org/support/plugin/bbpress-search-widget)
* [Code snippets archive for customizing, GitHub Gist](https://gist.github.com/2394575)
* *Plugin tip:* [My bbPress Toolbar / Admin Bar plugin](http://wordpress.org/extend/plugins/bbpress-admin-bar-addition/) -- a great time safer and helper tool :)

== Donate ==
Enjoy using *bbPress Search Widget*? Please consider [making a small donation](http://genesisthemes.de/en/donate/) to support the project's continued development.

== Translations ==

* English - default, always included
* German: Deutsch - immer dabei! [Download auch via deckerweb.de](http://deckerweb.de/material/sprachdateien/bbpress-forum/#bbpress-search-widget)
* For custom and update-secure language files please upload them to `/wp-content/languages/bbpress-search-widget/` (just create this folder) - This enables you to use fully custom translations that won't be overridden on plugin updates. Also, complete custom English wording is possible with that, just use a language file like `bbpress-search-widget-en_US.mo/.po` to achieve that (for creating one see the following tools).

**Easy plugin translation platform with GlotPress tool: [Translate "bbPress Search Widget"...](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/bbpress-search-widget)**

*Note:* All my plugins are internationalized/ translateable by default. This is very important for all users worldwide. So please contribute your language to the plugin to make it even more useful. For translating I recommend the awesome ["Codestyling Localization" plugin](http://wordpress.org/extend/plugins/codestyling-localization/) and for validating the ["Poedit Editor"](http://www.poedit.net/), which works fine on Windows, Mac and Linux.

== Additional Info ==
**Idea Behind / Philosophy:** A search feature or a widget is just missing yet for the new and awesome bbPress forum plugin. So I just set up this little widget. It's small and lightweight and only limited to this functionality.

== Credits ==
**I owe huge THANKS to WordPress developer Daniel Hüsken** from Germany who helped fix and improve the search results display! Thank you, my friend! Go and check out his awesome work with the [BackWPup plugin](http://wordpress.org/extend/plugins/backwpup/).

* Thanks to the WPMU.org blog crew who did a great post about this plugin back in the fall of 2011!
* Thanks to Pippin Williamson [@pippinsplugins](http://twitter.com/pippinsplugins) for testing and giving very helpful feedback!
* Thanks to all users who have tested and used (and still using!) this plugin - for all feedback which helped to improve stuff!
