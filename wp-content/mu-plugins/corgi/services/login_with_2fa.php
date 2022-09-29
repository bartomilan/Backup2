<?php

if (!CORGI__LOGIN_WITH_2FA) {
	return;
}

class Corgi2FA
{
	public static function register_routes()
	{
		$base = '2fa-code';

		register_rest_route(CORGI__REST_NAMESPACE, '/' . $base, array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => __CLASS__ . '::send_2fa_code',
				'args' => array(),
			)
		));
	}

	public static function send_2fa_code($request)
	{
		error_reporting(0);

		$curl = curl_init(CORGI__GET_2FA_CODE_URL);

		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = json_decode(curl_exec($curl), true);
		curl_close($curl);

		$_SESSION['corgi']['2fa_code'] = $response['code'];
	}
}

/*
 * Login with 2FA
 */

add_action('login_enqueue_scripts', function () {
	?>
	<script>
		// CORGI 2FA
		var form, submit, input, wrapper, login, password, remember, wp_submit, sent;

		function sendCode(e) {
			if (login.value && password.value && !sent) {
				e.preventDefault();

				submit.innerText = '<?php _e('Sending...'); ?>';

				fetch('<?php echo CORGI__SEND_2FA_CODE_URL; ?>')
				.then(function () {
					wrapper.style.display = 'block';
					remember.style.display = 'initial';
					wp_submit.style.display = 'initial';
					submit.style.display = 'none';
					submit.innerText = '<?php _e('Send SMS code'); ?>';
					sent = true;
				})
			} else {
				form.submit();
			}
		}

		window.addEventListener('DOMContentLoaded', function () {
			form = document.getElementById('loginform');
			submit = document.getElementById('corgi-send-2fa-code');
			remember = document.getElementsByClassName('forgetmenot')[0];
			wp_submit = document.getElementsByClassName('submit')[0];
			login = document.getElementById('user_login');
			password = document.getElementById('user_pass');
			input = document.getElementById('corgi-2fa-code');
			wrapper = document.getElementById('corgi-2fa');

			form.onsubmit = sendCode;
			submit.onclick = sendCode;
		})
	</script>
	<?php
}, true);


add_action('login_form', function () {
	?>
	<style>
		.forgetmenot,
		.submit {
			display: none;
		}
	</style>

	<div>
		<button id="corgi-send-2fa-code" type="submit" class="button button-primary button-large"><?php _e('Send SMS code'); ?></button>
	</div>

	<p id="corgi-2fa" style="display: none;">
		<label for="corgi-2fa-code">SMS kod</label>
		<input type="tel" maxlength="6" tabindex="20" size="20" value="" class="input" id="corgi-2fa-code"
			   name="corgi-2fa-code"/>
	</p>
	<?php
});
