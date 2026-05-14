<?php
/**
 * Plugin Name:       Enix Animation – Advanced Animation 
 * Plugin URI:        https://github.com/ahamedenamul/enix-animation
 * Description:       Adds advanced viewport scroll animations (bidirectional) to any Elementor element via the Advanced tab. 20+ animation styles, customizable duration, delay, easing & offset.
 * Version:           1.0.0
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Author:            Enix Solutions Ltd (By Enamul Islam)
 * Author URI:        https://github.com/ahamedenamul
 * License:           GPL v2 or later
 * Text Domain:       enix-animation
 * Domain Path:       /languages
 *
 * @package Enix_Animation
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$enixUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/ahamedenamul/enix-animation/',
	__FILE__,
	'enix-animation'
);
$enixUpdateChecker->setBranch( 'main' );

define( 'ENIX_ANIMATION_VERSION', '1.0.0' );
define( 'ENIX_ANIMATION_FILE', __FILE__ );
define( 'ENIX_ANIMATION_PATH', plugin_dir_path( __FILE__ ) );
define( 'ENIX_ANIMATION_URL', plugin_dir_url( __FILE__ ) );
define( 'ENIX_ANIMATION_MIN_ELEMENTOR', '3.0.0' );
define( 'ENIX_ANIMATION_MIN_PHP', '7.4' );

final class Enix_Animation_Plugin {

	private static $_instance = null;

	public static function enix_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'enix_on_plugins_loaded' ) );
	}

	public function enix_on_plugins_loaded() {
		if ( $this->enix_is_compatible() ) {
			add_action( 'elementor/init', array( $this, 'enix_init' ) );
		}
	}

	public function enix_is_compatible() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'enix_notice_missing_elementor' ) );
			return false;
		}
		if ( ! version_compare( ELEMENTOR_VERSION, ENIX_ANIMATION_MIN_ELEMENTOR, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'enix_notice_min_elementor' ) );
			return false;
		}
		if ( version_compare( PHP_VERSION, ENIX_ANIMATION_MIN_PHP, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'enix_notice_min_php' ) );
			return false;
		}
		return true;
	}

	public function enix_init() {
		load_plugin_textdomain( 'enix-animation', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		require_once ENIX_ANIMATION_PATH . 'includes/class-enix-controls.php';

		// Register controls in Advanced tab for sections, containers, columns, widgets.
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'enix_register_controls' ), 10, 2 );
		add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'enix_register_controls' ), 10, 2 );
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'enix_register_controls' ), 10, 2 );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'enix_register_controls' ), 10, 2 );

		// Render data attributes on the frontend.
		add_action( 'elementor/frontend/section/before_render',   array( $this, 'enix_before_render' ) );
		add_action( 'elementor/frontend/container/before_render', array( $this, 'enix_before_render' ) );
		add_action( 'elementor/frontend/column/before_render',    array( $this, 'enix_before_render' ) );
		add_action( 'elementor/frontend/widget/before_render',    array( $this, 'enix_before_render' ) );

		// Enqueue assets.
		add_action( 'elementor/frontend/after_enqueue_styles',   array( $this, 'enix_enqueue_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'enix_register_scripts' ) );
	}

	public function enix_register_controls( $element, $args ) {
		$controls = new Enix_Animation_Controls( $element );
		$controls->enix_register();
	}

	public function enix_before_render( $element ) {
		$settings  = $element->get_settings_for_display();
		$animation = isset( $settings['enix_animation_type'] ) ? $settings['enix_animation_type'] : '';

		if ( empty( $animation ) || 'none' === $animation ) {
			return;
		}

		$duration_map = array( 'fast' => 300, 'normal' => 600, 'slow' => 1000 );
		$duration_key = isset( $settings['enix_animation_duration'] ) ? $settings['enix_animation_duration'] : 'normal';
		$duration_ms  = isset( $duration_map[ $duration_key ] ) ? $duration_map[ $duration_key ] : 600;

		$delay_ms = isset( $settings['enix_animation_delay'] ) ? (int) $settings['enix_animation_delay'] : 0;
		$delay_ms = max( 0, (int) round( $delay_ms / 100 ) * 100 );

		$easing = isset( $settings['enix_animation_easing'] ) ? $settings['enix_animation_easing'] : 'ease-out';
		$once   = isset( $settings['enix_animation_once'] )   ? $settings['enix_animation_once']   : 'no';
		$offset = isset( $settings['enix_animation_offset']['size'] )
			? (int) $settings['enix_animation_offset']['size']
			: ( isset( $settings['enix_animation_offset'] ) ? (int) $settings['enix_animation_offset'] : 80 );

		$element->add_render_attribute(
			'_wrapper',
			array(
				'data-enix-animation' => esc_attr( $animation ),
				'data-enix-duration'  => esc_attr( $duration_ms ),
				'data-enix-delay'     => esc_attr( $delay_ms ),
				'data-enix-easing'    => esc_attr( $easing ),
				'data-enix-once'      => esc_attr( $once ),
				'data-enix-offset'    => esc_attr( $offset ),
				'class'               => 'enix-anim-init',
			)
		);

		wp_enqueue_script( 'enix-frontend' );
	}

	public function enix_enqueue_styles() {
		wp_enqueue_style( 'enix-frontend', ENIX_ANIMATION_URL . 'assets/css/enix-frontend.css', array(), ENIX_ANIMATION_VERSION );
	}

	public function enix_register_scripts() {
		wp_register_script( 'enix-frontend', ENIX_ANIMATION_URL . 'assets/js/enix-frontend.js', array(), ENIX_ANIMATION_VERSION, true );
	}

	public function enix_notice_missing_elementor() {
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			sprintf( esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'enix-animation' ), '<strong>Enix Animation</strong>', '<strong>Elementor</strong>' ) );
	}
	public function enix_notice_min_elementor() {
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			sprintf( esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'enix-animation' ), '<strong>Enix Animation</strong>', '<strong>Elementor</strong>', ENIX_ANIMATION_MIN_ELEMENTOR ) );
	}
	public function enix_notice_min_php() {
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			sprintf( esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'enix-animation' ), '<strong>Enix Animation</strong>', '<strong>PHP</strong>', ENIX_ANIMATION_MIN_PHP ) );
	}
}

Enix_Animation_Plugin::enix_instance();
