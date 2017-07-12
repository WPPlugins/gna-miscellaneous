<?php
if (!class_exists('GNA_Miscellaneous_Settings_Menu')) {
	class GNA_Miscellaneous_Settings_Menu extends GNA_Miscellaneous_Admin_Menu {
		var $menu_page_slug = 'gna-m-settings-menu';

		/* Specify all the tabs of this menu in the following array */
		var $menu_tabs;

		var $menu_tabs_handler = array(
			'tab1' => 'render_tab1', 
			'tab2' => 'render_tab2', 
			'tab3' => 'render_tab3', 
			);

		public function __construct() {
			$this->render_menu_page();
		}

		public function set_menu_tabs() {
			$this->menu_tabs = array(
				'tab1' => __('Taxonomy', 'gna-miscellaneous'),
				'tab2' => __('WooCommerce', 'gna-miscellaneous'),
				'tab3' => __('WordPress', 'gna-miscellaneous'),
			);
		}

		public function get_current_tab() {
			$tab_keys = array_keys($this->menu_tabs);
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $tab_keys[0];
			return $tab;
		}

		/*
		 * Renders our tabs of this menu as nav items
		 */
		public function render_menu_tabs() {
			$current_tab = $this->get_current_tab();

			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $this->menu_tabs as $tab_key => $tab_caption ) 
			{
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->menu_page_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}

		/*
		 * The menu rendering goes here
		 */
		public function render_menu_page() {
			echo '<div class="wrap">';
			echo '<h2>'.__('All Settings','gna-miscellaneous').'</h2>';//Interface title
			$this->set_menu_tabs();
			$tab = $this->get_current_tab();
			$this->render_menu_tabs();
			?>
			<div id="poststuff"><div id="post-body">
			<?php 
				//$tab_keys = array_keys($this->menu_tabs);
				call_user_func(array(&$this, $this->menu_tabs_handler[$tab]));
			?>
			</div></div>
			</div><!-- end of wrap -->
			<?php
		}

		public function render_tab1() {
			global $g_miscellaneous;
			if ( isset($_POST['gna_m_tax_save_settings']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if ( !wp_verify_nonce($nonce, 'n_gna-m-tax-save-settings') ) {
					die("Nonce check failed on save settings!");
				}

				$g_miscellaneous->configs->set_value('g_allow_html_cate_desc', isset($_POST["g_allow_html_cate_desc"]) ? $_POST["g_allow_html_cate_desc"] : '');
				$g_miscellaneous->configs->save_config();
				$this->show_msg_settings_updated();
			}
			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('GNA Miscellaneous', 'gna-miscellaneous'); ?></label></h3>
				<div class="inside">
					<p><?php _e('Thank you for using our GNA Miscellaneous plugin.', 'gna-miscellaneous'); ?></p>
				</div>
			</div> <!-- end postbox-->

			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('Taxonomies', 'gna-miscellaneous'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-m-tax-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Allow HTML tags inside Category\'s Description', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="checkbox" name="g_allow_html_cate_desc" id="g_allow_html_cate_desc"<?php if($g_miscellaneous->configs->get_value('g_allow_html_cate_desc')=='1') echo ' checked="checked"'; ?> value="1" />
									</div>
								</td>
							</tr>
						</table>
						<input type="submit" name="gna_m_tax_save_settings" value="<?php _e('Save Settings', 'gna-miscellaneous')?>" class="button button-primary" />
					</form>
				</div>
			</div> <!-- end postbox-->
			<?php
		}

		public function render_tab2() {
			global $g_miscellaneous;
			if ( isset($_POST['gna_m_woo_save_settings']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if ( !wp_verify_nonce($nonce, 'n_gna-m-woo-save-settings') ) {
					die("Nonce check failed on save settings!");
				}

				$g_miscellaneous->configs->set_value('g_allow_shop_page_has_featured_image', isset($_POST["g_allow_shop_page_has_featured_image"]) ? $_POST["g_allow_shop_page_has_featured_image"] : '');
				$g_miscellaneous->configs->set_value('g_allow_only_one_product_in_cart', isset($_POST["g_allow_only_one_product_in_cart"]) ? $_POST["g_allow_only_one_product_in_cart"] : '');
				$g_miscellaneous->configs->set_value('g_change_add_to_cart_text', isset($_POST["g_change_add_to_cart_text"]) ? $_POST["g_change_add_to_cart_text"] : '');
				$g_miscellaneous->configs->set_value('g_change_proceed_to_paypal_text', isset($_POST["g_change_proceed_to_paypal_text"]) ? $_POST["g_change_proceed_to_paypal_text"] : '');
				$g_miscellaneous->configs->set_value('g_change_view_basket_text', isset($_POST["g_change_view_basket_text"]) ? $_POST["g_change_view_basket_text"] : '');
				$g_miscellaneous->configs->set_value('g_change_success_msg_text', isset($_POST["g_change_success_msg_text"]) ? $_POST["g_change_success_msg_text"] : '');
				$g_miscellaneous->configs->set_value('g_change_proceed_checkout_btn_text', isset($_POST["g_change_proceed_checkout_btn_text"]) ? $_POST["g_change_proceed_checkout_btn_text"] : '');
				$g_miscellaneous->configs->set_value('g_change_update_cart_btn_text', isset($_POST["g_change_update_cart_btn_text"]) ? $_POST["g_change_update_cart_btn_text"] : '');
				$g_miscellaneous->configs->set_value('g_discount_shipping_over', isset($_POST["g_discount_shipping_over"]) ? $_POST["g_discount_shipping_over"] : '');
				$g_miscellaneous->configs->set_value('g_discount_shipping_rate', isset($_POST["g_discount_shipping_rate"]) ? $_POST["g_discount_shipping_rate"] : '');
				$g_miscellaneous->configs->save_config();
				$this->show_msg_settings_updated();
			}
			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('GNA Miscellaneous', 'gna-miscellaneous'); ?></label></h3>
				<div class="inside">
					<p><?php _e('Thank you for using our GNA Miscellaneous plugin.', 'gna-miscellaneous'); ?></p>
				</div>
			</div> <!-- end postbox-->

			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('WooCommerce', 'gna-miscellaneous'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-m-woo-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Allow Full Width Featured Image in Shop page', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="checkbox" name="g_allow_shop_page_has_featured_image" id="g_allow_shop_page_has_featured_image"<?php if($g_miscellaneous->configs->get_value('g_allow_shop_page_has_featured_image')=='1') echo ' checked="checked"'; ?> value="1" />
											
										<span class="gna_more_info_anchor"><span class="gna_more_info_toggle_char">+</span><span class="gna_more_info_toggle_text"><?php _e('More Info', 'gna-miscellaneous'); ?></span></span>
										<div class="gna_more_info_body">
											<?php
												echo '<p class="description">'.__('You need to add this PHP code where you want to put the "Featured Image for Shop page"', 'gna-miscellaneous').'</p>';
												echo '<p class="description"><code>'.__('do_action( \'gna_fullwidth_top\' );', 'gna-miscellaneous').'</code></p>';
											?>
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Allow only one product in Cart', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="checkbox" name="g_allow_only_one_product_in_cart" id="g_allow_only_one_product_in_cart"<?php if($g_miscellaneous->configs->get_value('g_allow_only_one_product_in_cart')=='1') echo ' checked="checked"'; ?> value="1" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Change the add to cart button\'s text', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="text" name="g_change_add_to_cart_text" id="g_change_add_to_cart_text" value="<?php echo $g_miscellaneous->configs->get_value('g_change_add_to_cart_text'); ?>" />
											
										<span class="gna_more_info_anchor"><span class="gna_more_info_toggle_char">+</span><span class="gna_more_info_toggle_text"><?php _e('More Info', 'gna-miscellaneous'); ?></span></span>
										<div class="gna_more_info_body">
											<?php
												echo '<p class="description">'.__('If it is blank, it will show default button text', 'gna-miscellaneous').'</p>';
											?>
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Change \'Proceed to PayPal\' Text on Checkout button', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="text" name="g_change_proceed_to_paypal_text" id="g_change_proceed_to_paypal_text" value="<?php echo $g_miscellaneous->configs->get_value('g_change_proceed_to_paypal_text'); ?>" />
											
										<span class="gna_more_info_anchor"><span class="gna_more_info_toggle_char">+</span><span class="gna_more_info_toggle_text"><?php _e('More Info', 'gna-miscellaneous'); ?></span></span>
										<div class="gna_more_info_body">
											<?php
												echo '<p class="description">'.__('If it is blank, it will show default button text', 'gna-miscellaneous').'</p>';
											?>
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Change \'View Cart\' Text of Button when added to cart', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="text" class="large-text" name="g_change_view_basket_text" id="g_change_view_basket_text" value="<?php echo $g_miscellaneous->configs->get_value('g_change_view_basket_text'); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Change \'Success message\' Text when added to cart', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="text" class="large-text" name="g_change_success_msg_text" id="g_change_success_msg_text" value="<?php echo $g_miscellaneous->configs->get_value('g_change_success_msg_text'); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Change \'Proceed to Checkout\' Button Text', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="text" class="large-text" name="g_change_proceed_checkout_btn_text" id="g_change_proceed_checkout_btn_text" value="<?php echo $g_miscellaneous->configs->get_value('g_change_proceed_checkout_btn_text'); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Change \'Update Cart\' Button Text', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="text" class="regular-text" name="g_change_update_cart_btn_text" id="g_change_update_cart_btn_text" value="<?php echo $g_miscellaneous->configs->get_value('g_change_update_cart_btn_text'); ?>" />
									</div>
								</td>
							</tr>
						</table>
						<input type="submit" name="gna_m_woo_save_settings" value="<?php _e('Save Settings', 'gna-miscellaneous')?>" class="button button-primary" />
					</form>
				</div>
			</div> <!-- end postbox-->
			
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('WooCommerce Shipping', 'gna-miscellaneous'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-m-woo-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Discount Shipping Cost From($)', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="number" class="regular-text" name="g_discount_shipping_over" id="g_discount_shipping_over" value="<?php echo $g_miscellaneous->configs->get_value('g_discount_shipping_over'); ?>" min="0" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Discount Shipping Cost Rate(%)', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="number" class="regular-text" name="g_discount_shipping_rate" id="g_discount_shipping_rate" value="<?php echo $g_miscellaneous->configs->get_value('g_discount_shipping_rate'); ?>" min="0" max="100" />
									</div>
								</td>
							</tr>
						</table>
						<input type="submit" name="gna_m_woo_save_settings" value="<?php _e('Save Settings', 'gna-miscellaneous')?>" class="button button-primary" />
					</form>
				</div>
			</div> <!-- end postbox-->
			<?php
		}

		public function render_tab3() {
			global $g_miscellaneous;
			if ( isset($_POST['gna_m_wp_save_settings']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if ( !wp_verify_nonce($nonce, 'n_gna-m-wp-save-settings') ) {
					die("Nonce check failed on save settings!");
				}

				$g_miscellaneous->configs->set_value('g_allow_core_update_notification', isset($_POST["g_allow_core_update_notification"]) ? $_POST["g_allow_core_update_notification"] : '');
				$g_miscellaneous->configs->set_value('g_disable_emoji', isset($_POST["g_disable_emoji"]) ? $_POST["g_disable_emoji"] : '');
				$g_miscellaneous->configs->save_config();
				$this->show_msg_settings_updated();
			}
			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('GNA Miscellaneous', 'gna-miscellaneous'); ?></label></h3>
				<div class="inside">
					<p><?php _e('Thank you for using our GNA Miscellaneous plugin.', 'gna-miscellaneous'); ?></p>
				</div>
			</div> <!-- end postbox-->

			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('WordPress', 'gna-miscellaneous'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-m-wp-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Disable Email Notification', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="checkbox" name="g_allow_core_update_notification" id="g_allow_core_update_notification"<?php if($g_miscellaneous->configs->get_value('g_allow_core_update_notification')=='1') echo ' checked="checked"'; ?> value="1" />
											
										<span class="gna_more_info_anchor"><span class="gna_more_info_toggle_char">+</span><span class="gna_more_info_toggle_text"><?php _e('More Info', 'gna-miscellaneous'); ?></span></span>
										<div class="gna_more_info_body">
											<?php
												echo '<p class="description">'.__('Disable email notification when there is new version of WordPress.', 'gna-miscellaneous').'</p>';
											?>
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Disable Emoji Icons', 'gna-miscellaneous')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="checkbox" name="g_disable_emoji" id="g_disable_emoji"<?php if($g_miscellaneous->configs->get_value('g_disable_emoji')=='1') echo ' checked="checked"'; ?> value="1" />
									</div>
								</td>
							</tr>
						</table>
						<input type="submit" name="gna_m_wp_save_settings" value="<?php _e('Save Settings', 'gna-miscellaneous')?>" class="button button-primary" />
					</form>
				</div>
			</div> <!-- end postbox-->
			<?php
		}
	} //end class
}
