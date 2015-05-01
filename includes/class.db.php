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
	public function add_follower( $args = array() ) {

		global $wpdb;

		$defaults = array(
			'user_id'		=> '',
			'follower'		=> ''
		);

		$args = wp_parse_args( $args, $defaults );

		//var_dump($args);exit;

		$add = $wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$this->table} SET
					`user_id`  		= '%s',
					`follower`  	= '%s'
				;",
				absint( $args['user_id'] ),
				absint( $args['follower'] )
			)
		);

		do_action( 'cgc_follow_add_follower', $args, $wpdb->insert_id );

		if ( $add )
			return $wpdb->insert_id;

		return false;
	}

}