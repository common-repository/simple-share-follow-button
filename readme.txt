=== Simple Share Follow Button ===
Contributors: Katsushi Kawamori
Donate link: https://shop.riverforest-wp.info/donate/
Tags: follow, share
Requires at least: 4.7
Requires PHP: 8.0
Tested up to: 6.6
Stable tag: 1.07
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays the Share button and Follow button.

== Description ==

Displays the Share button and Follow button.

= Button =
* X(Twitter)
* Facebook
* Instagram
* Youtube
* WordPress
* Github
* Line
* Pocket
* Hatena
* Rss
* Feedly

= View =
* The share button is displayed immediately after the content.
* The follow button is displayed on the top right by default. It can be changed in the settings page.
* The follow button can also be displayed with a shortcode.

= Filter for share button =
* Each initial value indicates the position from the left side.
* If the value is null, it will be hidden.
* `ssfb_share_twitter` : initial value 1
* `ssfb_share_facebook` : initial value 2
* `ssfb_share_pocket` : initial value 3
* `ssfb_share_hatena` : initial value 4
* `ssfb_share_line` : initial value 5
~~~
/** ==================================================
 * Filter for hide X(twitter).
 *
 */
add_filter( 'ssfb_share_twitter', function(){ return null; }, 10, 1 );
~~~
~~~
/** ==================================================
 * Filter for position X(twitter) and facebook.
 *
 */
add_filter( 'ssfb_share_twitter', function(){ return 2; }, 10, 1 );
add_filter( 'ssfb_share_facebook', function(){ return 1; }, 10, 1 );
~~~

* If you want to hide it, set it to false.
* `ssfb_share` : initial value true
~~~
/** ==================================================
 * Filter for hide all.
 *
 */
add_filter( 'ssfb_share', function(){ return false; }, 10, 1 );
~~~
* If you want to hide it by post ID, set it to false.
* `ssfb_share_id` : initial value true
~~~
/** ==================================================
 * Display by post ID or not for Simple Share Follow Button
 *
 * @param bool $flag  view.
 * @param int  $pid  post ID.
 * @since 1.00
 */
function ssfb_share_post_id( $flag, $pid ) {
	if ( 1567 === $pid ) {
		$flag = false;
	}
	return $flag;
}
add_filter( 'ssfb_share_id', 'ssfb_share_post_id', 10, 2 );
~~~

* If you want to hide it by post type, set it to false.
* `ssfb_share_type` : initial value true
~~~
/** ==================================================
 * Display by post type or not for Simple Share Follow Button
 *
 * @param bool   $flag  view.
 * @param string $type  post type.
 * @since 1.00
 */
function ssfb_share_post_type( $flag, $type ) {
	if ( 'page' === $type || 'attachment' === $type ) {
		$flag = false;
	}
	return $flag;
}
add_filter( 'ssfb_share_type', 'ssfb_share_post_type', 10, 2 );
~~~

= icon =
[IcoMoon - Free https://icomoon.io/]
License GPL / CC BY 4.0

== Installation ==

1. Upload `simple-share-follow-button` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

none

== Screenshots ==

1. Follow button view
2. Share button view
3. Manage screen

== Changelog ==

= [1.07] 2024/02/07 =
* Fix - Twitter icon to X icon.
* Fix - Icons is regenerated.
* Tweak - Tweaked admin screen.

= 1.06 =
Changed json_encode to wp_json_encode.

= 1.05 =
Supported WordPress 6.4.
PHP 8.0 is now required.

= 1.04 =
Fixed problem of XSS via shortcode.

= 1.03 =
Supported WordPress 6.1.

= 1.02 =
Rebuild react.

= 1.01 =
Rebuild react.

= 1.00 =
Initial release.

== Upgrade Notice ==

= 1.04 =
Security measures.

= 1.00 =
Initial release.
