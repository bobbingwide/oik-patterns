=== oik patterns ===
Contributors:      bobbingwide
Tags:              block patterns
Requires at least: 5.6.0
Tested up to:      5.7.1
Stable tag:        0.0.0
Requires PHP:      7.3.0
License:           GPL 3.0
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Registers block patterns from active theme's .htm files

== Description ==
Prototype solution to load Gutenberg patterns directly from .htm files saved from templates and/or template parts.


== Installation ==


1. Upload the plugin files to the `/wp-content/plugins/oik-patterns` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress


== Frequently Asked Questions ==

= Which templates are loaded? =

Any .htm file in the thisis theme's block-template-parts folder.
Not .html files!

= Are these templates translatable? =

Yes, if we use my proposal for internationalization and localization of templates and template parts
AND if I implement code to look in the user's current locale.


== Screenshots ==

1. None yet


== Upgrade Notice ==

= 0.0.0 = 
This is a prototype plugin.


== Changelog ==

= 0.0.0 =
* Added: A couple of PHP based patterns,[github bobbingwide oik-patterns issues 1]
* Added: Logic to load .htm files from the thisis theme,[github bobbingwide oik-patterns issues 2]


== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above. This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation." Arbitrary sections will be shown below the built-in sections outlined above.
