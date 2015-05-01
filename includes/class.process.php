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

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_follow' ) {

			if( !is_user_logged_in() )
				return;

	    	if ( wp_verify_nonce( $_POST['nonce'], 'process_follow' ) ) {

	    		$user_id 	= get_current_user_id();

	    		$user_to_follow = isset( $_POST['user_to_follow'] ) ? $_POST['user_to_follow'] : false;

	    		cgc_follow_user( $user_to_follow, $user_id );

		        wp_send_json_success();

		    } else {

		    	wp_send_json_error();

		    }

		} elseif ( isset( $_POST['action'] ) && $_POST['action'] == 'process_unfollow' ) {

	    	if ( wp_verify_nonce( $_POST['nonce'], 'process_follow' ) ) {

	    		$user_id 	= get_current_user_id();

	    		$user_to_unfollow = isset( $_POST['user_to_follow'] ) ? $_POST['user_to_follow'] : false;

	    		cgc_unfollow_user( $user_to_unfollow, $user_id );

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