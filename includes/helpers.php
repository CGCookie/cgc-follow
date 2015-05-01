<?php

/**
*	Follow a user
*
*	@param $user_id int id of user to follow
*	@since 5.0
*/
function cgc_follow_user( $user_id = 0 ) {

	if ( empty( $user_id ) )
		$user_id = get_current_user_ID();
}

/**
*	Unfollow a user
*
*	@param $user_id int id of user to unfollow
*	@since 5.0
*/
function cgc_unfollow_user( $user_id = 0 ){

	if ( empty( $user_id ) )
		$user_id = get_current_user_ID();
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