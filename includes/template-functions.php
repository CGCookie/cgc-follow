<?php

/**
*	Draw follow/unfollow buttons
*	@since 5.0
*/
function cgc_draw_follow_buttons( $user_to_check, $current_user ){

	if( empty( $user_to_check ) || $current_user == get_current_user_ID() || !is_user_logged_in() )
		return;

	?><div class="cgc-follow--wrap"><?php

		if ( function_exists('cgc_user_is_following') && cgc_user_is_following( $user_to_check, $current_user ) ) : ?>
			<a href="#" data-userid="<?php echo $user_to_check;?>" class="button ghost cgc-follow cgc-follow--unfollow">Unfollow</a>
		<?php else: ?>
			<a href="#" data-userid="<?php echo $user_to_check;?>" class="button ghost cgc-follow cgc-follow--follow">Follow</a>
		<?php endif; ?>

	</div><?php
}