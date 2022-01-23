=== oik patterns ===
Contributors:      bobbingwide
Tags:              block patterns
Requires at least: 5.6.0
Tested up to:      5.9-RC3
Stable tag:        0.1.2
Requires PHP:      7.3.0
License:           GPL 3.0
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Exports and imports patterns for the block editor.

== Description ==
Enables loading and display of a theme's patterns even when the theme is not active.

Also loads patterns from selected themes which don't include their own logic.


== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/oik-patterns` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. For each theme which delivers patterns use the preview_theme=theme query parameter to load the cache with the theme's patterns.


== Frequently Asked Questions ==

= Which patterns are loaded? =

- Any .html file in the theme's patterns folder. Selected themes only
- Cached patterns for a selected theme

Not yet complete

- During preview, if the cached versions are out of date with respect to the theme they are recached.


= Are these patterns translatable? =

Yes, if we use my proposal for internationalization and localization of templates and template parts
AND if I implement code to look in the user's current locale.


== Screenshots ==

1. None yet


== Upgrade Notice 
= 0.1.2 = 
Upgrade for minor performance improvements #4


= 0.1.1 = 
Update for theme and template validation for preview_theme query arg

= 0.1.0 = 
Update for pattern caching and improved loading from the patterns folder.

= 0.0.0 = 
This is a prototype plugin.


== Changelog ==
= 0.1.2 =
* Changed: Improve display of cached pattern source - trim blanks #4
* Changed: Only cache the patterns when necessary. If style.css is newer #4

= 0.1.1 = 
* Changed: Add theme and template validation for preview_theme query arg #4

= 0.1.0 = 
* Changed: Improve count_patterns() for uncached patterns #4
* Added: Add get_title() method #2
* Added: Add pattern import and display logic #4
* Added: patterns export/cache: first pass #4
* Changed: Load patterns from .html files in the patterns folder and subdirectories #2
* Tested: With WordPress 5.9-RC3 and WordPress Multi Site
* Tested: With Gutenberg 12.4.0
* Tested: With PHP 8.0

= 0.0.0 =
* Added: A couple of PHP based patterns,[github bobbingwide oik-patterns issues 1]
* Added: Logic to load .htm files from the thisis theme,[github bobbingwide oik-patterns issues 2]