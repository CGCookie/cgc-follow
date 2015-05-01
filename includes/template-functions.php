<?php

/**
*	Draw follow/unfollow buttons
*	@since 5.0
*/
function cgc_draw_follow_buttons( $user_to_check, $current_user ){

	if( empty( $user_to_check ) || $current_user == get_current_user_ID() || !is_user_logged_in() )
		return;

	?><div class="cgc-follow--wrap"><?php

	if ( cgc_user_is_following( $user_to_check, $current_user ) ) { ?>
		<a href="#" class="cgc-follow cgc-follow--unfollow button primary tiny">unfollow</a>
		<a href="#" class="cgc-follow cgc-follow--follow button primary tiny" style="display:none;">follow</a>
	<?php } else { ?>
		<a href="#" class="cgc-follow cgc-follow--follow button primary tiny">follow</a>
		<a href="#" class="cgc-follow cgc-follow--unfollow button primary tiny" style="display:none;">unfollow</a>
	<?php } ?>

	</div><?php
}