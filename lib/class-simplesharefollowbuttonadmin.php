<?php
/**
 * Simple Share Follow Button
 *
 * @package    Simple Share Follow Button
 * @subpackage SimpleShareFollowButtonAdmin Management screen
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

$simplesharefollowbuttonadmin = new SimpleShareFollowButtonAdmin();

/** ==================================================
 * Management screen
 */
class SimpleShareFollowButtonAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10, 1 );
		add_action( 'rest_api_init', array( $this, 'register_rest' ) );
	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'simple-share-follow-button/simplesharefollowbutton.php';
		}
		if ( $file === $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=simplesharefollowbutton' ) . '">' . __( 'Settings' ) . '</a>';
		}
			return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_menu() {
		add_options_page( 'Simple Share Follow Button Options', 'Simple Share Follow Button', 'manage_options', 'simplesharefollowbutton', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_options() {

		echo '<div id="simplesharefollowbuttonadmin"></div>';
	}

	/** ==================================================
	 * Load script
	 *
	 * @param string $hook_suffix  hook_suffix.
	 * @since 1.00
	 */
	public function admin_scripts( $hook_suffix ) {

		if ( 'settings_page_simplesharefollowbutton' !== $hook_suffix ) {
			return;
		}

		$asset_file = include plugin_dir_path( __DIR__ ) . 'guten/dist/simple-share-follow-button-admin.asset.php';

		wp_enqueue_style(
			'simplesharefollowbuttonadmin-style',
			plugin_dir_url( __DIR__ ) . 'guten/dist/simple-share-follow-button-admin.css',
			array( 'wp-components' ),
			'1.0.0',
		);

		wp_enqueue_script(
			'simplesharefollowbuttonadmin',
			plugin_dir_url( __DIR__ ) . 'guten/dist/simple-share-follow-button-admin.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		wp_localize_script(
			'simplesharefollowbuttonadmin',
			'simplesharefollowbuttonadmin_text',
			array(
				'settings' => __( 'Settings' ),
				'body_open' => __( 'Display to top', 'simple-share-follow-button' ),
				'body_open_description' => __( 'If you turn it off, you can still display it with a shortcode.', 'simple-share-follow-button' ),
				'follow' => __( 'Follow', 'simple-share-follow-button' ),
				'shortcode' => __( 'Shortcode', 'simple-share-follow-button' ),
				'service' => __( 'Service', 'simple-share-follow-button' ),
				'index' => __( 'Order from the left side', 'simple-share-follow-button' ),
				'attr' => __( 'Shortcode attribute value', 'simple-share-follow-button' ),
				'position' => __( 'Position', 'simple-share-follow-button' ),
				'right' => __( 'Right justified', 'simple-share-follow-button' ),
				'center' => __( 'Centering', 'simple-share-follow-button' ),
				'left' => __( 'Left justified', 'simple-share-follow-button' ),
				'blank' => __( 'Space between icons', 'simple-share-follow-button' ),
				'share' => __( 'Share', 'simple-share-follow-button' ),
				'share_description' => __( 'The share button is configured in the filter hook. Please refer to the following URL.', 'simple-share-follow-button' ),
				'share_description_url' => __( 'https://wordpress.org/plugins/simple-share-follow-button', 'simple-share-follow-button' ),
			)
		);

		$ssfbf_options = get_option( 'ssfbf' );
		$services = array_keys( $ssfbf_options['url'] );

		wp_localize_script(
			'simplesharefollowbuttonadmin',
			'simplesharefollowbuttonadmin_data',
			array(
				'services' => wp_json_encode( $services, JSON_UNESCAPED_SLASHES ),
				'options' => wp_json_encode( $ssfbf_options, JSON_UNESCAPED_SLASHES ),
			)
		);

		$this->credit_gutenberg( 'simplesharefollowbuttonadmin' );
	}

	/** ==================================================
	 * Register Rest API
	 *
	 * @since 1.00
	 */
	public function register_rest() {

		register_rest_route(
			'rf/simplesharefollowbutton_api',
			'/token',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'api_save' ),
				'permission_callback' => array( $this, 'rest_permission' ),
			),
		);
	}

	/** ==================================================
	 * Rest Permission
	 *
	 * @since 1.00
	 */
	public function rest_permission() {

		return current_user_can( 'manage_options' );
	}

	/** ==================================================
	 * Rest API save
	 *
	 * @param object $request  changed data.
	 * @since 1.00
	 */
	public function api_save( $request ) {

		$args = json_decode( $request->get_body(), true );

		update_option( 'ssfbf', $args );

		return new WP_REST_Response( $args, 200 );
	}

	/** ==================================================
	 * Credit for Gutenberg
	 *
	 * @param string $handle  handle.
	 * @since 1.00
	 */
	private function credit_gutenberg( $handle ) {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}

		wp_localize_script(
			$handle,
			'credit',
			array(
				'links'          => __( 'Various links of this plugin', 'simple-share-follow-button' ),
				'plugin_version' => __( 'Version:' ) . ' ' . $plugin_ver_num,
				/* translators: FAQ Link & Slug */
				'faq'            => sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'simple-share-follow-button' ), $slug ),
				'support'        => 'https://wordpress.org/support/plugin/' . $slug,
				'review'         => 'https://wordpress.org/support/view/plugin-reviews/' . $slug,
				'translate'      => 'https://translate.wordpress.org/projects/wp-plugins/' . $slug,
				/* translators: Plugin translation link */
				'translate_text' => sprintf( __( 'Translations for %s' ), $plugin_name ),
				'facebook'       => 'https://www.facebook.com/katsushikawamori/',
				'twitter'        => 'https://twitter.com/dodesyo312',
				'youtube'        => 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w',
				'donate'         => __( 'https://shop.riverforest-wp.info/donate/', 'simple-share-follow-button' ),
				'donate_text'    => __( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'simple-share-follow-button' ),
				'donate_button'  => __( 'Donate to this plugin &#187;' ),
			)
		);
	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 1.00
	 */
	public function register_settings() {

		if ( ! get_option( 'ssfbf' ) ) {
			$ssfbf_options = array(
				'url' => array(
					'twitter' => '',
					'facebook' => '',
					'instagram' => '',
					'youtube' => '',
					'wordpress' => '',
					'github' => '',
					'rss' => get_bloginfo( 'rss2_url' ),
					'feedly' => 'https://feedly.com/i/subscription/feed/' . get_bloginfo( 'rss2_url' ),
				),
				'index' => array(
					'twitter' => 1,
					'facebook' => 2,
					'instagram' => 3,
					'youtube' => 4,
					'wordpress' => 5,
					'github' => 6,
					'rss' => 7,
					'feedly' => 8,
				),
				'align' => 'flex-end',
				'blank' => 2,
				'body_open' => true,
			);
			update_option( 'ssfbf', $ssfbf_options );
		}
	}
}
