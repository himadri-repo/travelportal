<?php
$term_link=WPBooking_User::inst()->get_term_condition_link();
$error_field = array();

if(!WPBooking_Input::post('action')){
    WPBooking()->set('error_r_field','');
}
if(!empty(WPBooking()->get('error_r_field'))){
    $error_field = WPBooking()->get('error_r_field');
}
$reset = false;
if(WPBooking()->get('register') == 'successful'){
    $reset = true;
}

?>
<form action="" method="post" id="wpbooking-register-form" class="login-register-form">
	<input type="hidden"  name="action" value="wpbooking_do_register">

	<h3 class="form-title"><?php esc_html_e('Register','travelagency') ?></h3>
	<div class="form-group-wrap">
		<div class="form-group">
			<label for="reg-login" class="control-label"><?php esc_html_e('Username','travelagency') ?> <span class="required">*</span></label>
			<input type="text" required class="form-control <?php echo (array_key_exists('rg-login',$error_field)?'wb-error':'')?>" value="<?php echo (!$reset?WPBooking_Input::post('rg-login'):'') ?>" name="rg-login" id="reg-login" ">
		</div>
		<div class="form-group">
			<label for="input-email" class="control-label"><?php esc_html_e('Email','travelagency') ?> <span class="required">*</span></label>
			<input type="text" required class="form-control <?php echo (array_key_exists('rg-email',$error_field)?'wb-error':'')?>" value="<?php echo (!$reset?WPBooking_Input::post('rg-email'):'') ?>" name="rg-email" id="input-email" ">
		</div>
		<div class="form-group">
			<label for="input-password" class="control-label"><?php esc_html_e('Password','travelagency') ?> <span class="required">*</span></label>
			<input type="password" required class="form-control <?php echo (array_key_exists('rg-password',$error_field)?'wb-error':'')?>" id="input-password" name="rg-password" value="<?php echo (!$reset?WPBooking_Input::post('rg-password'):'') ?>">
		</div>
		<div class="form-group">
			<label for="input-repassword" class="control-label"><?php esc_html_e('Re-type Password','travelagency') ?> <span class="required">*</span></label>
			<input type="password" required class="form-control <?php echo (array_key_exists('rg-repassword',$error_field)?'wb-error':'')?>" id="input-repassword" name="rg-repassword" value="<?php echo (!$reset?WPBooking_Input::post('rg-repassword'):'') ?>">
		</div>
		<?php
		$page_privacy = get_option( 'wp_page_for_privacy_policy' );
		if ( $page_privacy ):
		?>
		<div class="form-group">
			<label class="accept-term">
					<input type="checkbox" name="privacy" <?php (!$reset?checked(WPBooking_Input::post('term_condition'),1):'') ?> value="1"><?php printf(esc_html__('Accept with %s','travelagency'),sprintf('<a href="%s" target="_blank">%s</a>',get_the_permalink( $page_privacy ),esc_html__('Our privacy policy','travelagency'))); ?>
			</label>
		</div>
		<?php endif; ?>
		<button type="submit" class="wb-btn wb-btn-default wb-disabled"><?php esc_html_e('Register','travelagency') ?></button>
	</div>
	<?php
	if(WPBooking_Input::post('action')=='wpbooking_do_register')
        WPBooking()->set('register','');
		echo wpbooking_get_message();
	?>
</form>

