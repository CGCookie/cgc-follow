<?php
/**
 * Plugin Name: CGC Follow
 * Description: Implementation of the Follow functionality from the cgcookie theme
 */

// returns an array of user IDs for all users the current user is following
function cgc_get_following( $user_id = null ) {

	if ( !$user_id ) {
		global $user_ID;
		$user_id = $user_ID;
	}

	$following = get_user_meta( $user_id, '_cgc_following', true );
	if ( $following ) {
		return $following;
	}
	return false;
}

// returns an array of user IDs for all users that are following $user_id
function cgc_get_followers( $user_id = null ) {

	if ( !$user_id ) {
		global $user_ID;
		$user_id = $user_ID;
	}

	$followers = get_user_meta( $user_id, '_cgc_followers', true );
	if ( $followers ) {
		return $followers;
	}
	return false;
}

/*
 * Adds a user ID to a list of users to follow
* @param $user_id int - the ID of the user whose list we are adding to
* @param $user_to_follow - the ID of the user we are going to begin following
* return bool - true on success, false on failure
*/
function cgc_follow_user( $user_id, $user_to_follow ) {

	$following = cgc_get_following( $user_id );
	if ( $following && is_array( $following ) ) {
		$following[] = $user_to_follow;
	} else {
		$following = array();
		$following[] = $user_to_follow;
	}

	// retrieve the IDs of all users who are following $user_to_follow
	$followers = cgc_get_followers( $user_to_follow );
	if ( $followers && is_array( $followers ) ) {
		$followers[] = $user_id;
	} else {
		$followers = array();
		$followers[] = $user_id;
	}

	// update the IDs that this user is following
	$followed = update_user_meta( $user_id, '_cgc_following', $following );

	// update the IDs that follow $user_id
	$followers = update_user_meta( $user_to_follow, '_cgc_followers', $followers );

	// increase the followers count
	$followed_count = cgc_increase_followed_by_count( $user_to_follow );

	if ( $followed ) {
		cgc_email_follow_alert( $user_to_follow, $user_id );
		return true;
	}
	return false;
}

/*
 * Removes a user ID from a user's follow list
* @param $user_id int - the ID of the user whose list we are removing from
* @param $unfollow_user - the ID of the user we are going to stop following
* return bool - true on success, false on failure
*/
function cgc_unfollow_user( $user_id, $unfollow_user ) {

	// get all IDs that $user_id follows
	$following = cgc_get_following( $user_id );
	if ( is_array( $following ) && in_array( $unfollow_user, $following ) ) {
		$modified = false;
		foreach ( $following as $key => $follow ) {
			if ( $follow == $unfollow_user ) {
				unset( $following[$key] );
				$modified = true;
			}
		}
		if ( $modified ) {
			if ( update_user_meta( $user_id, '_cgc_following', $following ) ) {
				cgc_decrease_followed_by_count( $unfollow_user );
			}
		}
	}

	// get all IDs that follow the user we have just unfollowed so that we can remove $user_id
	$followers = cgc_get_followers( $unfollow_user );
	if ( is_array( $followers ) && in_array( $user_id, $followers ) ) {
		$modified = false;
		foreach ( $followers as $key => $follower ) {
			if ( $follower == $user_id ) {
				unset( $followers[$key] );
				$modified = true;
			}
		}
		if ( $modified ) {
			update_user_meta( $unfollow_user, '_cgc_followers', $followers );
		}
	}
	if ( $modified ) {
		return true;
	}

	return false;
}

/*
 * Retrieves the number of users $user_id is following
* @param $user_id int - the ID of the user to get a count for
* return int - the number of users the user is following
*/
function cgc_get_following_count( $user_id ) {
	$following = cgc_get_following( $user_id );
	if ( $following ) {
		return count( $following );
	}
	return 0;
}

/*
 * Retrieves the number of users $user_id is followed by
* @param $user_id int - the ID of the user to get a count for
* return int - the number of users following this user
*/
function cgc_get_follower_count( $user_id ) {
	$followed_count = get_user_meta( $user_id, '_cgc_followed_by_count', true );
	if ( $followed_count ) {
		return $followed_count;
	}
	return 0;
}

/*
 * Increases the total follower count for a specific user
* @param $user_id int - the ID of the user whose follower count we are increasing
* return int - the number of followers
*/
function cgc_increase_followed_by_count( $user_id ) {

	$followed_count = get_user_meta( $user_id, '_cgc_followed_by_count', true );
	if ( $followed_count !== false ) {
		$new_followed_count = update_user_meta( $user_id, '_cgc_followed_by_count', $followed_count + 1 );
	} else {
		$new_followed_count = update_user_meta( $user_id, '_cgc_followed_by_count', 1 );
	}
	return $new_followed_count;
}

/*
 * Decreases the total follower count for a specific user
* @param $user_id int - the ID of the user whose follower count we are decreasing
* return mixed - the number of followers, if any, false if none
*/
function cgc_decrease_followed_by_count( $user_id ) {

	$followed_count = get_user_meta( $user_id, '_cgc_followed_by_count', true );
	if ( $followed_count ) {
		return update_user_meta( $user_id, '_cgc_followed_by_count', ( $followed_count - 1 ) );
	}
	return false;
}

/*
 * Checks to see if the current user is following the specified ID
* @param $user_id int - the ID of the user whose list we are adding to
* @param $followed_user - the ID of the user we are going to check for follow status
* return bool - true if following, false if not
*/
function cgc_is_following( $user_id, $followed_user ) {
	$following = cgc_get_following( $user_id );
	if ( is_array( $following ) && in_array( $followed_user, $following ) ) {
		return true; // is following
	}
	return false; // is not following
}

/*
 * Adds a new follow
*/
function cgc_process_new_follow() {
	if ( isset( $_POST['user_id'] ) && wp_verify_nonce( $_POST['cgc_nonce'], 'cgc-nonce' ) ) {
		if( $_POST['user_id'] == $_POST['follow_id'] ) {
			echo 'self';
		}elseif ( cgc_follow_user( $_POST['user_id'], $_POST['follow_id'] ) ) {
			echo 'success';
		} else {
			echo 'failed';
		}
	}
	die();
}
add_action( 'wp_ajax_follow', 'cgc_process_new_follow' );

/*
 * Removes a follower
*/
function cgc_process_unfollow() {
	if ( isset( $_POST['user_id'] ) && wp_verify_nonce( $_POST['cgc_nonce'], 'cgc-nonce' ) ) {
		if ( cgc_unfollow_user( $_POST['user_id'], $_POST['follow_id'] ) ) {
			echo 'success';
		} else {
			echo 'failed';
		}
	}
	die();
}
add_action( 'wp_ajax_unfollow', 'cgc_process_unfollow' );

/*
 * Emails the followed user an alert when a user starts following them
* @param $followed_user int - the ID of the user being followed / alerted
* @param $follower_id - the ID of the user who has started following $followed_user
* return bool - true if following, false if not
*/
function cgc_email_follow_alert( $followed_user, $follower_id ) {

	$followed_user = get_userdata( $followed_user );
	$follower = get_userdata( $follower_id );

	$profile_url = home_url( 'profile/' . urlencode( $follower->user_login ) );
	// Try to fetch the proper URL for the profile (link to the site user followed from)
	if ( isset( $_POST['action'] ) ) {
		$action_url = $_POST['action'];
		if ( strpos( $action_url, 'cgcookie.com/' ) !== false && strpos( $action_url, 'wp-admin' ) !== false ) {
			$subsite_url = substr( $action_url, strpos( $action_url, 'wp-admin/' ) );
			$profile_url = $subsite_url . 'profile/' . urlencode( $follower->user_login );
		}
	}

	$message = "Hello $followed_user->display_name,\n\n";
	$message .= "$follower->display_name has started following you on the CG Cookie Network.\n\n";
	$message .= "View $follower->display_name's profile and return the favor: "  . $profile_url . "\n\n";
	$message .= "To stop receiving these notfications, go to your dashboard profile settings.\n\n";
	$message .= "Best regards from the Crew at CG Cookie, Inc.";

	if ( ! get_user_meta( $followed_user->ID, 'no_emails', true ) ) {
		wp_mail( $followed_user->user_email, 'New Follower', $message );
	}
}
