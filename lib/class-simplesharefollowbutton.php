<?php
/**
 * Simple Share Follow Button
 *
 * @package    Simple Share Follow Button
 * @subpackage SimpleShareFollowButton Main Functions
/*
	Copyright (c) 2021- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$simplesharefollowbutton = new SimpleShareFollowButton();

/** ==================================================
 * Main Functions
 */
class SimpleShareFollowButton {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'load_style' ) );
		add_filter( 'the_content', array( $this, 'simpleshare' ) );
		add_shortcode( 'ssfbf', array( $this, 'simplefollow' ) );
		$ssfbf_options = get_option( 'ssfbf' );
		if ( ! empty( $ssfbf_options ) && $ssfbf_options['body_open'] ) {
			add_action( 'wp_body_open', array( $this, 'simplefollow_body_open' ) );
		}
	}

	/** ==================================================
	 * contents hook for share
	 *
	 * @param string $content  content.
	 * @return $html
	 * @since 1.00
	 */
	public function simpleshare( $content ) {

		if ( is_singular() ) {
			$title = get_the_title();
			$permalink = get_permalink();
			$twitter = apply_filters( 'ssfb_share_twitter', 1 );
			$facebook = apply_filters( 'ssfb_share_facebook', 2 );
			$pocket = apply_filters( 'ssfb_share_pocket', 3 );
			$hatena = apply_filters( 'ssfb_share_hatena', 4 );
			$line = apply_filters( 'ssfb_share_line', 5 );

			$share_id = apply_filters( 'ssfb_share_id', true, get_the_ID() );
			$share_type = apply_filters( 'ssfb_share_type', true, get_post_type( get_the_ID() ) );

			if ( $share_id && $share_type ) {
				$content .= '<ul class="ssfbShare">';
				$share_html = array();
				if ( ! is_null( $twitter ) && is_int( $twitter ) && 0 <= $twitter ) {
					$share_html[ $twitter ] = '<li><a class="twitter_ssfb icon-twitter_ssfb" href="//twitter.com/intent/tweet?text=' . esc_attr( $title ) . '&' . esc_url( $permalink ) . '&url=' . esc_url( $permalink ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Share on Twitter', 'simple-share-follow-button' ) . '"></a></li>';
				}
				if ( ! is_null( $facebook ) && is_int( $facebook ) && 0 <= $facebook ) {
					$share_html[ $facebook ] = '<li><a class="facebook_ssfb icon-facebook_ssfb" href="//www.facebook.com/sharer.php?u=' . esc_url( $permalink ) . '&t=' . esc_attr( $title ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Share on facebook', 'simple-share-follow-button' ) . '"></a></li>';
				}
				if ( ! is_null( $pocket ) && is_int( $pocket ) && 0 <= $pocket ) {
					$share_html[ $pocket ] = '<li><a class="pocket_ssfb icon-pocket_ssfb" href="//getpocket.com/edit?url=' . esc_url( $permalink ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Read later in Pocket', 'simple-share-follow-button' ) . '"></a></li>';
				}
				if ( ! is_null( $hatena ) && is_int( $hatena ) && 0 <= $hatena ) {
					$share_html[ $hatena ] = '<li><a class="hatena_ssfb icon-hatebu_ssfb" href="//b.hatena.ne.jp/add?mode=confirm&url=' . esc_url( $permalink ) . '&title=' . esc_attr( $title ) . '" target="_blank" rel="noopener noreferrer" data-hatena-bookmark-title="' . esc_url( $permalink ) . '" title="' . esc_attr__( 'Add this entry to Hatena bookmark', 'simple-share-follow-button' ) . '"></a></li>';
				}
				if ( ! is_null( $line ) && is_int( $line ) && 0 <= $line ) {
					$share_html[ $line ] = '<li><a class="line_ssfb icon-line_ssfb" href="//timeline.line.me/social-plugin/share?url=' . esc_url( $permalink ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Share on LINE', 'simple-share-follow-button' ) . '"></a></li>';
				}
				if ( ! is_null( $share_html ) ) {
					ksort( $share_html );
					foreach ( $share_html as $value ) {
						$content .= $value;
					}
				}
				$content .= '</ul>';
				$width_per = round( 90 / count( $share_html ) );
				update_option( 'ssfbs_width_per', $width_per );
			}
		}

		return $content;
	}

	/** ==================================================
	 * Follow body_open
	 *
	 * @since 1.00
	 */
	public function simplefollow_body_open() {

		echo do_shortcode( '[ssfbf]' );
	}

	/** ==================================================
	 * Follow short code
	 *
	 * @param array  $atts  atts.
	 * @param string $html  html.
	 * @return $html
	 * @since 1.00
	 */
	public function simplefollow( $atts, $html = null ) {

		$ssfbf_options = get_option( 'ssfbf' );

		$a = shortcode_atts(
			array(
				'twitter' => $ssfbf_options['url']['twitter'],
				'twitter_index' => $ssfbf_options['index']['twitter'],
				'facebook' => $ssfbf_options['url']['facebook'],
				'facebook_index' => $ssfbf_options['index']['facebook'],
				'instagram' => $ssfbf_options['url']['instagram'],
				'instagram_index' => $ssfbf_options['index']['instagram'],
				'youtube' => $ssfbf_options['url']['youtube'],
				'youtube_index' => $ssfbf_options['index']['youtube'],
				'wordpress' => $ssfbf_options['url']['wordpress'],
				'wordpress_index' => $ssfbf_options['index']['wordpress'],
				'github' => $ssfbf_options['url']['github'],
				'github_index' => $ssfbf_options['index']['github'],
				'rss' => $ssfbf_options['url']['rss'],
				'rss_index' => $ssfbf_options['index']['rss'],
				'feedly' => $ssfbf_options['url']['feedly'],
				'feedly_index' => $ssfbf_options['index']['feedly'],
				'align' => $ssfbf_options['align'],
				'blank' => $ssfbf_options['blank'],
			),
			$atts
		);

		$follow_html = array();
		if ( ! empty( $a['twitter'] ) ) {
			$follow_html[ $a['twitter_index'] ] = '<a class="twitter_ssfbFollow icon-twitter_ssfb" href="' . esc_url( $a['twitter'] ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Follow', 'simple-share-follow-button' ) . '"></a>';
		}
		if ( ! empty( $a['facebook'] ) ) {
			$follow_html[ $a['facebook_index'] ] = '<a class="facebook_ssfbFollow icon-facebook_ssfb" href="' . esc_url( $a['facebook'] ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Follow', 'simple-share-follow-button' ) . '"></a>';
		}
		if ( ! empty( $a['instagram'] ) ) {
			$follow_html[ $a['instagram_index'] ] = '<a class="instagram_ssfbFollow icon-instagram_ssfb" href="' . esc_url( $a['instagram'] ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Follow', 'simple-share-follow-button' ) . '"></a>';
		}
		if ( ! empty( $a['youtube'] ) ) {
			$follow_html[ $a['youtube_index'] ] = '<a class="youtube_ssfbFollow icon-youtube_ssfb" href="' . esc_url( $a['youtube'] ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Follow', 'simple-share-follow-button' ) . '"></a>';
		}
		if ( ! empty( $a['wordpress'] ) ) {
			$follow_html[ $a['wordpress_index'] ] = '<a class="wordpress_ssfbFollow icon-wordpress_ssfb" href="' . esc_url( $a['wordpress'] ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Follow', 'simple-share-follow-button' ) . '"></a>';
		}
		if ( ! empty( $a['github'] ) ) {
			$follow_html[ $a['github_index'] ] = '<a class="github_ssfbFollow icon-github_ssfb" href="' . esc_url( $a['github'] ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Follow', 'simple-share-follow-button' ) . '"></a>';
		}
		if ( ! empty( $a['rss'] ) ) {
			$follow_html[ $a['rss_index'] ] = '<a class="rss_ssfbFollow icon-rss_ssfb" href="' . esc_url( $a['rss'] ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Follow', 'simple-share-follow-button' ) . '"></a>';
		}
		if ( ! empty( $a['feedly'] ) ) {
			$follow_html[ $a['feedly_index'] ] = '<a class="feedly_ssfbFollow icon-feedly_ssfb" href="' . esc_url( $a['feedly'] ) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__( 'Follow', 'simple-share-follow-button' ) . '"></a>';
		}

		$blank = null;
		if ( ! is_null( $a['blank'] ) && is_int( $a['blank'] ) && 0 < $a['blank'] ) {
			$blank = str_repeat( '&nbsp;', $a['blank'] );
		}

		if ( ! empty( $follow_html ) ) {
			$html .= '<ul class="ssfbFollow">';
			ksort( $follow_html );
			foreach ( $follow_html as $value ) {
				$html .= $value . $blank;
			}
			$html .= '</ul>';
		}

		return $html;
	}

	/** ==================================================
	 * Load Style
	 *
	 * @since 1.00
	 */
	public function load_style() {

		wp_enqueue_style(
			'ssfb-icon-style',
			plugin_dir_url( __DIR__ ) . 'icomoon/style.css',
			array(),
			'1.00',
		);
		$css = 'ul.ssfbShare li{ width: ' . get_option( 'ssfbs_width_per', 18 ) . '%; }';
		$ssfbf_options = get_option( 'ssfbf' );
		$css .= 'ul.ssfbFollow { justify-content: ' . $ssfbf_options['align'] . ' }';
		wp_add_inline_style( 'ssfb-icon-style', $css );
	}
}
