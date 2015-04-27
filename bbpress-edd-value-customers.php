<?php
/*
 * Plugin Name: bbPress - Easy Digital Downloads - Customer Valuation Tools
 * Description: Displays to keymasters how much a customer has invested into your company.
 * Plugin URI: 
 * Author: Hudson Atwell
 * Author URI: http://www.hudsonatwell.co
 * Version: 1.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class BBP_EDD_Customer_Valuation {


	public function __construct() {
		add_action( 'bbp_theme_after_reply_author_details', array( $this, 'show_customer_value' ) );
	} 

	/**
	 * Front end output - Targgets 
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function show_customer_value() {

		if ( !function_exists('edd_purchase_total_of_user') ) {
			return;
		}	
		
		$reply_author_id = get_post_field( 'post_author', bbp_get_reply_id() );
		$user_data = get_userdata( $reply_author_id );
		$user_email = $user_data->user_email;
		
		if (current_user_can('activate_plugins') ){

			$total_spent = get_transient( 'bbp_edd_' . $user_email );
			echo '<br><b>Total Invested</b><br>';
			if ( $total_spent ) {
				echo edd_format_amount( $total_spent );
				return;
			} else {
				$total_spent = edd_purchase_total_of_user( $user_email );
				echo edd_format_amount( $total_spent );
				set_transient( 'bbp_edd_' . $user_email ,  $total_spent , 60 * 60 );
				return;
			}
		}
		
	}	

	

} 

$GLOBALS['bbp_edd_customer_valuation'] = new BBP_EDD_Customer_Valuation();
