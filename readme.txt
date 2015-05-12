=== Simple FAQs Plugin ===
Contributors: speedito
Tags: FAQ listing
Requires at least: 3.7.1
Tested up to: 4.2.1
Stable tag: 2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a plugin to help put up FAQ lists on Wordpress pages in an easy manner. Multiple options are provided to allow different variations.

== Description ==
This is a simple plugin to help users put up FAQ lists on their site. Most FAQ plugins only allow a single format (usually the Accordion) format to put up FAQ lists. While that is great there are a number of folks who would rather just have simple bookmark tags to list their Questions at the top and lead to Answers below. This plugin attempts to provide an easy to use solution for such scenarios.

Options for FAQ
<ol>
<li>Accordion (the default option)</li>
<li>Simple (just list each answer below that question)</li>
<li>Bookmarks (show the questions at the top and lead to the related answer below) - now implements scrolling via LocalScroll jQuery plugin</li>
</ol>

Also has options for ordering the FAQ and includes 1 pre-built skin with more coming soon

Details and examples about using the plugin are available at <a href="http://speedsoftsol.com/simple-faq-plugin/" target="_blank">speedsoftsol.com/simple-faq-plugin/</a>

== Installation ==
1. Upload zip archive `simple-faq.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==
1. What are the different parameters available:
style (can be Accordion, Simple, Bookmark)
skin (can be none, Dark)
order (can be Default, Alpha, Date)
category (can be All or any category slug defined in your site)

2. Can we use multiple shortcodes on the same page?
Yes that is possible since version 2 of the plugin

== Screenshots ==
1. screenshot-1.png 
2. screenshot-2.png 

== Changelog ==
= 2.2 =
Added new skins

= 2.1 =
Added new skins
Solved the shortcode generator issue

= 2.0 =
Added shortcode generator
Now able to have multiple shortcodes on the same page
Added new styling skins
Multiple ordering options (by order id, publish date or alphabetical)

= 1.1.4 =
Sort out SVN issue

= 1.1.3 =
Add smooth scrolling feature for Bookmark Style FAQ

= 1.1.2 =
Add Back To Top feature for Bookmark Style

= 1.1.1
Show all FAQ without pagination

= 1.1 =
Reset Post Data To avoid conflict with Theme Queries

= 1.0 =
Initial launch
