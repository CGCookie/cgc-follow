<?php

class CGC_FOLLOW_DB {


	private $table;
	private $db_version;

	function __construct() {

		global $wpdb;

		$this->table   		= $wpdb->base_prefix . 'cgc_follow';
		$this->db_version = '1.0';

	}

	/**
	*	Add a follower or followers to a user id
	*
	*	@since 5.0
	*/
	public function add_followers( $args = array() ) {

		global $wpdb;

		$defaults = array(
			'user_id'		=> '',
			'followers'		=> '',
			'followed_by'	=> ''
		);

		$args = wp_parse_args( $args, $defaults );

		//var_dump($args);exit;

		$add = $wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$this->table} SET
					`user_id`  		= '%s',
					`followers`  	= '%s',
					`followed_by`   = '%s'
				;",
				absint( $args['user_id'] ),
				$args['followers'],
				$args['followed_by']
			)
		);

		do_action( 'cgc_follow_add_follower', $args, $wpdb->insert_id );

		if ( $add )
			return $wpdb->insert_id;

		return false;
	}

	/**
	*	Update followers to a user id
	*
	*	@param $user_id int id of the user to update the followers for
	*	@since 5.0
	*/
	public function update_followers( $user_id = '', $args = array() ) {

		global $wpdb;

		$followers = $args['followers'];
		$followers = explode(', ', $followers);

		//var_dump($post_id);wp_die();

		$update = $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$this->table} SET
					`followers`    	= '%s'
					WHERE 'user_id' = '%d'
				;",
				trim( $followers ),
				absint( $user_id )
			)
		);

		if( false !== $update )
			return true;
		return false;
	}

}