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
	*	Add a single follower
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

		do_action( 'cgc_follower_added', $args, $wpdb->insert_id );

		if ( $add )
			return $wpdb->insert_id;

		return false;
	}

	/**
	*	Remove a follower
	*
	*	@since 5.0
	*/
	public function remove_follower( $args = array() ) {

		global $wpdb;

		if( empty( $args['user_id'] ) || empty( $args['follower'] )  )
			return;

		do_action( 'cgc_follower_removed', $args );

 		$remove = $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->table} WHERE `user_id` = '%d' AND `follower` = '%d' ;", absint( $args['user_id'] ), absint( $args['follower'] ) ) );

	}
}