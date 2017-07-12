<?php
if (!class_exists('GNA_Miscellaneous')) {
	class GNA_Miscellaneous {
		var $plugin_url;
		var $admin_init;
		var $configs;

		public function __construct() {
			$this->load_configs();
			$this->define_constants();
			$this->define_variables();
			$this->includes();
			$this->loads();

			add_action('init', array(&$this, 'plugin_init'), 0);
			add_action('wp_print_styles', array(&$this, 'add_front_styles'));
			add_filter('plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2);
		}

		public function load_configs() {
			include_once('inc/gna-miscellaneous-config.php');
			$this->configs = GNA_Miscellaneous_Config::get_instance();
		}

		public function define_constants() {
			define('GNA_MISCELLANEOUS_VERSION', '1.0.5');

			define('GNA_MISCELLANEOUS_BASENAME', plugin_basename(__FILE__));
			define('GNA_MISCELLANEOUS_URL', $this->plugin_url());

			define('GNA_MISCELLANEOUS_MENU_SLUG_PREFIX', 'gna-m-settings-menu');
		}

		public function define_variables() {
		}

		public function includes() {
			if(is_admin()) {
				include_once('admin/gna-miscellaneous-admin-init.php');
			}
		}

		public function loads() {
			if(is_admin()){
				$this->admin_init = new GNA_Miscellaneous_Admin_Init();
			}
		}

		public function add_front_styles() {
			wp_enqueue_style('gna-miscellaneous-front-css', GNA_MISCELLANEOUS_URL. '/assets/css/gna-miscellaneous.css');
		}

		public function plugin_init() {
			load_plugin_textdomain('gna-miscellaneous', false, dirname(plugin_basename(__FILE__ )) . '/languages/');
			
			global $g_miscellaneous;
			if(is_admin()){
				if ( $g_miscellaneous->configs->get_value('g_allow_html_cate_desc') == '1' ) {
					remove_filter('pre_term_description', 'wp_filter_kses');
				}
			}

			if ( $g_miscellaneous->configs->get_value('g_allow_shop_page_has_featured_image') == '1' ) {
				add_theme_support( 'post-thumbnails' );
				add_action( 'gna_fullwidth_top', array(&$this, 'gna_add_featured_image') );
			}

			if ( $g_miscellaneous->configs->get_value('g_allow_only_one_product_in_cart') == '1' ) {
				add_filter( 'woocommerce_add_cart_item_data', array(&$this, 'only_one_product_in_cart') );
			}

			if ( $g_miscellaneous->configs->get_value('g_change_add_to_cart_text') != '' ) {
				add_filter( 'woocommerce_product_add_to_cart_text', array(&$this, 'archive_custom_cart_button_text') );	// 2.1 +
				add_filter( 'woocommerce_product_single_add_to_cart_text', array(&$this, 'custom_cart_button_text') );    // 2.1 +
			}
			
			if ( $g_miscellaneous->configs->get_value('g_change_proceed_to_paypal_text') != '' || $g_miscellaneous->configs->get_value('g_change_view_basket_text') != '' || $g_miscellaneous->configs->get_value('g_change_proceed_checkout_btn_text') != '' || $g_miscellaneous->configs->get_value('g_change_update_cart_btn_text') != '' ) {
				add_filter( 'gettext', array(&$this, 'custom_gettext'), 20, 3 );
			}
			
			if ( $g_miscellaneous->configs->get_value('g_change_success_msg_text') != '' ) {
				add_filter( 'wc_add_to_cart_message', array(&$this, 'custom_add_to_cart_message') );
			}
			
			if ( $g_miscellaneous->configs->get_value('g_allow_core_update_notification') != '' && $g_miscellaneous->configs->get_value('g_allow_core_update_notification') == '1' ) {
				add_filter( 'auto_core_update_send_email', '__return_false' );
			}
			
			if ( $g_miscellaneous->configs->get_value('g_disable_emoji') != '' && $g_miscellaneous->configs->get_value('g_disable_emoji') == '1' ) {
				add_action('init', array(&$this, 'gna_disable_emoji'), 1);
			}
			
			if ( $g_miscellaneous->configs->get_value('g_discount_shipping_over') != '' && $g_miscellaneous->configs->get_value('g_discount_shipping_rate') != '' ) {
				add_filter( 'woocommerce_package_rates', array(&$this, 'gna_discount_shipping_cost'), 999, 2 );
				add_filter( 'woocommerce_cart_shipping_method_full_label', array(&$this, 'gna_woocommerce_cart_shipping_method_full_label'), 999, 2 );
			}
		}

		public function plugin_url() {
			if ($this->plugin_url) return $this->plugin_url;
			return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		}

		public function filter_plugin_meta($links, $file) {
			if( strpos( GNA_MISCELLANEOUS_BASENAME, str_replace('.php', '', $file) ) !== false ) {
				$links[] = '<a target="_blank" href="https://profiles.wordpress.org/chris_dev/" rel="external">' . __('Developer\'s Profile', 'gna-miscellaneous') . '</a>';
			}

			return $links;
		}

		function gna_add_featured_image() {
			if ( !is_single() ) {
				echo '<div id="fullwidth_featured_img">';
				echo get_the_post_thumbnail( get_option( 'woocommerce_shop_page_id' ), 'full' );
				echo '</div>';
			}
		}

		function only_one_product_in_cart( $cart_item_data ) {
			global $woocommerce;
			$woocommerce->cart->empty_cart();

			return $cart_item_data;
		}

		function archive_custom_cart_button_text() {
			global $g_miscellaneous;
			return __( $g_miscellaneous->configs->get_value('g_change_add_to_cart_text'), 'gna-miscellaneous' );
		}

		function custom_cart_button_text() {
			global $g_miscellaneous;
			return __( $g_miscellaneous->configs->get_value('g_change_add_to_cart_text'), 'gna-miscellaneous' );
		}

		function custom_gettext( $translated_text, $text, $domain ) {
			global $g_miscellaneous;
			switch ( $translated_text ) {
				case 'Proceed to PayPal' :
					$translated_text = __( $g_miscellaneous->configs->get_value('g_change_proceed_to_paypal_text'), $domain );
					break;
				case 'View Basket' :
				case 'View Cart' :
					$translated_text = __( $g_miscellaneous->configs->get_value('g_change_view_basket_text'), $domain );
					break;
				case 'Proceed to Checkout' :
					$translated_text = __( $g_miscellaneous->configs->get_value('g_change_proceed_checkout_btn_text'), $domain );
					break;
				case 'Update Basket' :
				case 'Update Cart' :
					$translated_text = __( $g_miscellaneous->configs->get_value('g_change_update_cart_btn_text'), $domain );
					break;
				default:
					break;
			}
			return stripslashes($translated_text);
		}
		
		function custom_add_to_cart_message( $product_id ) {
			global $g_miscellaneous;
			$titles = array();

			if ( is_array( $product_id ) ) {
				foreach ( $product_id as $id ) {
					$titles[] = get_the_title( $id );
				}
			} else {
				$titles[] = get_the_title( $product_id );
			}

			$titles     = array_filter( $titles );
			$added_text = stripslashes($g_miscellaneous->configs->get_value('g_change_success_msg_text'));

			// Output success messages
			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				$return_to = apply_filters( 'woocommerce_continue_shopping_redirect', wp_get_referer() ? wp_get_referer() : home_url() );
				$message   = sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', esc_url( $return_to ), esc_html__( 'Continue Shopping', 'woocommerce' ), esc_html( $added_text ) );
			} else {
				$message   = sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'View Cart', 'woocommerce' ), esc_html( $added_text ) );
			}

			return $message;
		}
		
		function gna_disable_emoji() {
			// all actions related to emojis
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

			// filter to remove TinyMCE emojis
			add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
		}
		
		function disable_emojicons_tinymce( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			} else {
				return array();
			}
		}
		
		public function gna_discount_shipping_cost( $rates, $package ) {
			global $g_miscellaneous;
			global $woocommerce;

			$shipping_over = $g_miscellaneous->configs->get_value('g_discount_shipping_over');
			$discount_shipping_rate = $g_miscellaneous->configs->get_value('g_discount_shipping_rate');
			//$cart_total = $woocommerce->cart->cart_contents_total + $woocommerce->cart->tax_total;
			$cart_total = $woocommerce->cart->cart_contents_total + $woocommerce->cart->taxes[1];
//pprint_r($woocommerce->cart->tax_total);
//pprint_r($woocommerce->cart->taxes[1]);
			if ( $cart_total >= $shipping_over ) {
				foreach( $rates as $rate ) {
					$rate->original_cost = $rate->cost;
					$rate->cost = $rate->cost - ($rate->cost * $discount_shipping_rate / 100);
					if ( !empty($rate->taxes) ) {
						$rate->original_tax = $rate->taxes[1];
						$rate->taxes[1] = $rate->original_tax * $discount_shipping_rate / 100;
					}
				}
			}
//pprint_r($rates);
			return $rates;
		}
		
		public function gna_woocommerce_cart_shipping_method_full_label($rate_lbl, $method) {
			//pprint_r($method);
			if (isset($method->original_cost) && !empty($method->original_cost) ) {
				$taxes = $method->taxes;
				$cal_tax = 0;
				/*foreach( $taxes as $tax ) {
					$cal_tax .= $tax;
				}*/
				$cal_tax = $method->original_tax;
				global $g_miscellaneous;
				$discount_shipping_rate = $g_miscellaneous->configs->get_value('g_discount_shipping_rate');
				$rate_lbl = $rate_lbl . ' <span class="price"><span class="del">' . get_woocommerce_currency_symbol() . number_format(floatval($method->original_cost) + floatval($cal_tax), 2) . '</span></span><span class="discounted_lbl">('.$discount_shipping_rate.'% discounted)</span>';
			}
			
			return $rate_lbl;
		}

		public function install() {
		}

		public function uninstall() {
		}

		public function activate_handler() {
		}

		public function deactivate_handler() {
		}
	}
}
$GLOBALS['g_miscellaneous'] = new GNA_Miscellaneous();
