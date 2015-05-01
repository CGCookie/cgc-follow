<?php

/**
*	Class responsible for processing the ajax call to follow or unfollow a user
*	@since 5.0
*/
class cgcProcessFollow {

	public function __construct(){

		add_action('wp_ajax_process_follow',		array($this,'process_follow'));
		add_action('wp_ajax_process_unfollow',		array($this,'process_follow'));

	}

	public function process_follow(){

		if ( isset( $_POST['action'] ) ) {

	    	$current_user 	= get_current_user_id();

	    	$target_user 	= isset( $_POST['user_to_follow'] ) ? $_POST['user_to_follow'] : false;

	    	if ( empty ( $target_user ) )
	    		return;

			if ( $_POST['action'] == 'process_follow' && wp_verify_nonce( $_POST['nonce'], 'process_follow' )  ) {

	    		cgc_follow_user( $target_user, $current_user );

		        wp_send_json_success();

			} elseif ( $_POST['action'] == 'process_unfollow' && wp_verify_nonce( $_POST['nonce'], 'process_follow' ) ) {

	    		cgc_unfollow_user( $target_user, $current_user );

		        wp_send_json_success();

			} else {

				wp_send_json_error();

			}

		} else {

			wp_send_json_error();

		}

	}

}
new cgcProcessFollow;