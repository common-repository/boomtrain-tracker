<div class="wrap">
	<h2><?php _e( 'Boomtrain', 'bmtr' ) ?></h2>
		
	<h3><span><?php _e( 'Manage your Boomtrain options', 'bmtr' ); ?></span></h3>
	
	<div class="inside">
		<form action="" method="POST">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="boomtrain_script"><?php _e( 'Boomtrain Snippet', 'bmtr' ) ?></label></th>
						<td>
							<textarea name="boomtrain_script" id="boomtrain_script" class="regular-text" rows="25" cols="50"><?php echo ! empty( $boomtrain_options['script'] ) ? stripslashes( $boomtrain_options['script'] ) : ''; ?></textarea>
							<p class="description"><?php _e( 'Enter the code provided by the Boomtrain team.', 'bmtr' ) ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="boomtrain_signup_form"><?php _e( 'Insert Signup Form Script', 'bmtr' ) ?></label></th>
						<td>
							<textarea name="boomtrain_signup_form" id="boomtrain_signup_form" class="regular-text" rows="25" cols="50"><?php echo ! empty( $boomtrain_options['signup_form'] ) ? stripslashes( $boomtrain_options['signup_form'] ) : ''; ?></textarea>
							<p class="description"><?php _e( 'Enter the Signup Form Script here.', 'bmtr' ) ?></p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
		</form>
	</div>
</div>