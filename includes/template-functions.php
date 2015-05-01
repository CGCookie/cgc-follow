<?php
/**
*	Functions for theme templates
*
*/

function cgc_draw_follow_buttons(){

	ob_start();

	?>
	<div class="cgc-follow--wrap">
		<a href="#" class="cgc-follow cgc-follow--follow button primary tiny">Follow</a>
		<a href="#" class="cgc-follow cgc-follow--unfollow button primary tiny">Unfollow</a>
	</div>
	<?php

	return ob_get_clean();
}