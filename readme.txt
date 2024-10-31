=== Plugin Name ===
Contributors: techno-geek (essej2003)
Donate link: http://techno-geeks.org/selective-rss/
Tags: rss, feed
Requires at least: 2.0
Tested up to: 2.8
Stable tag: 0.1.2b

This is a simple Plugin that allows you to embed RSS feed items into Pages or Posts.

== Description ==

This is a simple Plugin that allows you to embed RSS feed items into Pages or Posts. It also optionally allows you to choose how many items to display and allows you to limit items to ones that contain certain words in the titles. Additionally, you can store feed entries for a specified number of days.

== Installation ==

1. Upload the files to the `/wp-content/plugins/` directory
2. chmod 777 /wp-content/plugins/selective-rss/persistent
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place [srss ...] tag into a post or page.

Examples
---------------
Simple: [srss url=http://www.domain.example/feed]

Complex: [srss url=http://www.domain.example/feed,limit=10,filter=word1;word2]

Entry Persistence: [srss url=http://www.domain.example/feed,limit=10,filter=word1;word2,persist=true,persist-duration=60]

Defaults
---------------
Limit: No limit

Filters: None

Persist: No

Persist Duration: 7 days (if persist is set to true)

== Changelog ==

0.1.1b
---------------
Bug Fix: Multiple RSS Feeds in same page/post. Thanks to Sheik for the report.

0.1.2b
---------------
Feature: Ability to cache/store feed entries for specified number of days.

== Frequently Asked Questions ==

How did this plugin start?

See: http://techno-geeks.org/2009/06/selective-rss-plugin-for-wordpress/

== Screenshots ==

Nothing yet...
