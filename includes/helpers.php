<?php

/**
*	Follow a user
*
*	@param $user_to_follow int id of user to follow
*	@param $current_user int id of the current user
*	@since 5.0
*/
function cgc_follow_user( $user_to_follow = 0, $current_user = 0 ) {

	if ( empty( $user_to_follow ) )
		return;

	if ( empty( $current_user ) )
		$current_user = get_current_user_ID();

	$db = new CGC_FOLLOW_DB;

	$args = array(
		'user_id' 	=> $user_to_follow,
		'follower'	=> $current_user
	);

	$db->add_follower( $args );
}

/**
*	Unfollow a user
*
*	@param $user_id int id of user to unfollow
*	@since 5.0
*/
function cgc_unfollow_user( $user_to_unfollow = 0, $current_user = 0 ){

	if ( empty( $user_to_unfollow ) )
		return;

	if ( empty( $current_user ) )
		$current_user = get_current_user_ID();

	$db = new CGC_FOLLOW_DB;

	$args = array(
		'user_id' 	=> $user_to_unfollow,
		'follower'	=> $current_user
	);

	$db->remove_follower( $args );

}

/**
*	Get a users followers
*
*	@param $user_id int id of user to get followers for
*	@since 5.0
*/
function cgc_get_followers( $user_id = 0 ) {

	if ( empty( $user_id ) )
		$user_id = get_current_user_ID();
}