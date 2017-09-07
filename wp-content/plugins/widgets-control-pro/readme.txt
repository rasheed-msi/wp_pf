=== Widgets Control Pro ===
Contributors: itthinx
Donate link: http://www.itthinx.com/shop/widgets-control-pro/
Tags: appearance, conditional, control, customize, display, hide, logic, placement, restrict, restrict content, shortcode, show, view, visibility, widget, widgets
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 1.5.0
License: For files where GPLv3 applies or the license obtained via itthinx.com where the GPLv3 does not apply.

A Widget toolbox that adds visibility management and helps to control where widgets, sidebars and content are shown efficiently.

== Description ==

Widgets Control Pro is the advanced version of the Widgets Control toolbox. It features advanced visibility management for all widgets, sidebars and content sections.
It allows to show widgets based on conditions -
you can choose to show them only on certain pages or
exclude them from being displayed.
Sections of content can also be restricted by using this plugin's `[widgets_control]` shortcode.

For each widget and sidebar, you can decide where it should be displayed:

- show it on all pages
- show it on some pages
- show it on all except some pages

To include or exclude pages, the plugin allows you to indicate page ids, titles or slugs and tokens that identify the front page, categories, tags, etc.

In addition to page ids, titles and slugs, these tokens can be used to determine where a widget should or should not be displayed:

<code>[home] [front] [single] [page] [category] ...</code>

On sites using [WPML](http://wpml.org), widgets can be shown conditionally based on the language viewed.

The `[widgets_control]` shortcode is used to embed content and show it conditionally similar to the visibility options used for widgets and sidebars.
For example, `[widgets_control conditions="{archive}"]This text is shown only when the content is displayed on an archive page.[/widgets_control]`.

[Widgets Control Pro](http://www.itthinx.com/shop/widgets-control-pro/) provides additional features like limiting the display by <em>post type</em> or complete page <em>hierarchies</em>.

See the [documentation](http://docs.itthinx.com/document/widgets-control-pro/) for more details.

_Widgets Control Pro_ works with virtually any widget. It is compatible with lots of plugins, among these it has been tested with:

- [Groups](http://wordpress.org/plugins/groups/)
- [WooCommerce](http://wordpress.org/plugins/woocommerce/)
- [Events Manager](http://wordpress.org/plugins/events-manager/)
- [BuddyPress](http://buddypress.org)
- [bbPress](http://wordpress.org/plugins/bbpress/)
- [Ninja Forms](http://wordpress.org/plugins/ninja-forms)
- [Gravity Forms](http://gravityforms.com/)
- [Jetpack](http://wordpress.org/plugins/jetpack/)
- [WPML](http://wpml.org)
- [NextGEN Gallery](http://wordpress.org/plugins/nextgen-gallery/)
- [Image Widget](http://wordpress.org/plugins/image-widget/)
- [MailChimp for WordPress](http://wordpress.org/plugins/mailchimp-for-wp/)
- [Custom Post Widget](http://wordpress.org/plugins/custom-post-widget/)
- [The Events Calendar](http://wordpress.org/plugins/the-events-calendar/)
- [MailPoet Newsletters](http://wordpress.org/plugins/wysija-newsletters)

== Installation ==

= Dashboard =

Log in as an administrator and go to <strong>Plugins > Add New > Upload Plugin</strong>.
Click the <em>Choose File</em> button and locate the <em>Widgets Control Pro</em> plugin file that you have obtained via itthinx.com.
Click the <em>Install Now</em> button.
Now <em>activate</em> the plugin to have the advanced widget placement features available.

= FTP =

You can install the plugin via FTP, see [Manual Plugin Installation](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Frequently Asked Questions ==

= Where is the documentation for this plugin? =

You can find the documentation on the [Widgets Control Pro](http://docs.itthinx.com/document/widgets-control-pro/) documentation pages.
