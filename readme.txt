=== XO10 - WooCommerce Categories widget ===
Contributors: saltern
Donate link: http://www.cancer.org
Tags: woocommerce, widget, product categories, category images, category thumbnails, category icons, xo10
Requires at least: 3.9
Tested up to: 4.4
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows WooCommerce product category images to be displayed in a WordPress widget.

== Description ==

The default WooCommerce Product Categories widget is unable to
display product category images. This plugin creates a new widget that
adds the following features that the default WooCommerce widget lacks.

* display category as text only, text with image, or image only.
* positions of text-image and product counts can be changed.
* specify the size of the category thumbnail to be displayed.
* product counts brackets can be turned off or displayed using different brackets.
* display the categories widget as an accordion which can be expanded/collapsed via a toggle button.
* specify an _optional_ HTML ID for the categories list if you need to style the list differently from other lists.
* specify one or more HTML classes for the categories list to make use of your theme's existing style.

[youtube https://www.youtube.com/watch?v=h--g92163cU]


**Plugin requirements**

* PHP 5.3 or later.
* WooCommerce 2.3 or later.


**Documentation**

You can find the [official documentation](http://cartible.com/projects/xo10-woocommerce-categories-widget-2/) and more stuff on WooCommerce on our site.


**Credits**

* [Plugin banner image](https://flic.kr/p/bEiYBV) by Ivan / [CC BY](https://creativecommons.org/licenses/by/2.0/)
* [Plugin icon](https://www.iconfinder.com/icons/287099) by Edoardo Coccia / [CC BY](http://creativecommons.org/licenses/by/3.0/)
* [Sample category icons](https://www.iconfinder.com/iconsets/cat-force) by [Iconka](http://iconka.com/en/).
* Accordion menu Javascript by [Cory Caywood](https://github.com/corycaywood/navAccordion).








== Installation ==

Follow these steps:

1. Upload the `xo10-woocommerce-categories-widget` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to 'Appearance > Widgets' and find the widget 'XO10 - WooCommerce Categories' and place it in your sidebar.


== Frequently Asked Questions ==


= Is there a premium or paid version of this plugin? =

No. This plugin is absolutely _free_ and my way of returning back to the open source 
community which has given me so much over the years.

There are no plans for a _premium version_ or need to pay any _subscription plans_ to use it.

= How can I style the list or images to look a certain way? =

You would need to know how your _theme_ works and how to use _CSS_ in order to style
the category images to look a certain way. For example, to display the images as a grid.

I'm **unable to help with CSS** issues anymore so please approach your theme author or 
approach a web designer for help.

= Why aren't images or post counts displayed when categories are displayed as a DropDown? =

The default WooCommerce Product Categories widget does not do that
also. For the time-being, we won't be implementing this feature
because it requires extra Javascript and other usability
considerations for mobile users.

= Where is the documentation for this plugin? =

Documentation for this plugin can be found at [cartible.com](http://cartible.com/projects/xo10-woocommerce-categories-widget-2/).

= Why won't you implement these features? = 

Features that won't be added in the near future due to a lack of time are as follows.

* AJAX refresh of categories.
* only display a specific sub-set of categories instead of the entire category tree.
* left-right text support in default stylesheets.
* responsive "hamburger" menu to cater to mobile devices.
* use of Font Awesome icons. This is due to the potential plugin/theme conflicts.

Features that won't be added because the Javascript library currently does not support these features are as follows.

* maintain the state of user-opened categories for the accordion menu across page views.
* allow two or more sub-categories to be expanded/collapsed.

= Where is the PO or MO file for translation? =

They are in the /languages directory of the plugin.
WordPress will be building in support for [plugin language packs](https://make.wordpress.org/plugins/2015/09/01/plugin-translations-on-wordpress-org/) soon.
So there may be more translations coming in future.

= Is the plugin's code on GitHub? =

Not at the moment because I'm too busy with my work. I need more time to test and
clean up the code before I put it up there.

= What does the XO10 mean? =

To me, it means lots of things but I'll talk about them another day. For now, just take
it that it sounds like my WordPress username - *saltern*. And is also what I use
as a unique namespace prefix for all plugins that I write.


== Screenshots ==

1. Widget admin settings.
2. Widget display (may be affected by your theme's styles).
3. Change of positions to post counts, text, image.
4. Sample custom effects.


== Changelog ==

= 2.0.1 =

* Updated YouTube link in readme.txt file. 

= 2.0 =

* Plugin is now compatible with WordPress 4.4 and WooCommerce 2.5.
* Added a "Hide empty categories" option to match upcoming WooCommerce 2.5.
* Added ability to change product count bracket type.
* Added basic style sheets for display of categories.
* Added [expand/collapse](https://wordpress.org/support/topic/add-collapse-funcions) (i.e. Accordion) functionality. Thanks to "Lukas Prelovsky" for testing.
* Removed French translation.

= 1.3 =

* Plugin is now compatible with WordPress 4.3 and WooCommerce 2.4.
* New: Fully translation-ready to cater to WordPress's transition to [language packs for plugins](https://make.wordpress.org/plugins/2015/09/01/plugin-translations-on-wordpress-org/).
* Fixed: Corrected the installation instructions. Thanks to _dbarnhart_.

= 1.2 =
* Code is updated to work properly with WooCommerce 2.3.x.

= 1.1 =

* Feature: Category name and thumbnail positions can be switched. As requested by *marco*.
* Feature: Post counts can be shown on the extreme left or right.
* Tweak: Added requirement for PHP 5.3 or later in plugin description.
* Tweak: Changed the plugin text domain.

= 1.0 =

* Initial release.


== Upgrade Notice ==

= 2.0.1 =

* None.

= 2.0 =

* None.

= 1.3 =

* None.

= 1.2 =

* None.

= 1.1 =

* Make sure the "Text/Image display" field value is correct after upgrade. Change the value and save the widget again if necessary.

= 1.0 =

* N.A.
