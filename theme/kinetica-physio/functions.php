<?php
/**
 * Kinetica Physio (Demo) — theme functions.
 *
 * Self-initiated demonstration theme by Dhansurrya S P. The clinic is fictional.
 *
 * @package kinetica-physio
 */

defined( 'ABSPATH' ) || exit;

define( 'KP_VERSION', '1.0.0' );

/**
 * Theme supports.
 */
add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'responsive-embeds' );
		add_editor_style( 'assets/css/landing.css' );
	}
);

/**
 * Front-end assets.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'kp-fonts',
			'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap',
			array(),
			null
		);
		wp_enqueue_style( 'kp-landing', get_theme_file_uri( 'assets/css/landing.css' ), array(), KP_VERSION );
	}
);

/**
 * Private post type that stores contact-form enquiries for review in the dashboard.
 */
add_action(
	'init',
	function () {
		register_post_type(
			'kp_enquiry',
			array(
				'labels'          => array(
					'name'          => __( 'Enquiries', 'kinetica-physio' ),
					'singular_name' => __( 'Enquiry', 'kinetica-physio' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'menu_icon'       => 'dashicons-email-alt',
				'supports'        => array( 'title', 'editor' ),
				'capability_type' => 'post',
				'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'    => true,
			)
		);
	}
);

/**
 * Services offered in the form's select field.
 *
 * @return string[]
 */
function kp_services() {
	return array(
		'sports'   => __( 'Sports injury rehab', 'kinetica-physio' ),
		'back'     => __( 'Back and neck pain', 'kinetica-physio' ),
		'surgery'  => __( 'Post-surgery rehab', 'kinetica-physio' ),
		'posture'  => __( 'Posture and desk pain', 'kinetica-physio' ),
		'womens'   => __( "Women's health physio", 'kinetica-physio' ),
		'home'     => __( 'Home visit', 'kinetica-physio' ),
		'not-sure' => __( 'Not sure yet', 'kinetica-physio' ),
	);
}

/**
 * [kp_contact_form] — renders the enquiry form and its status message.
 *
 * @return string
 */
function kp_contact_form_shortcode() {
	$status   = isset( $_GET['enquiry'] ) ? sanitize_key( wp_unslash( $_GET['enquiry'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$messages = array(
		'sent'    => array( 'success', __( 'Thank you — your enquiry has been received. In this demo it is saved to the WordPress dashboard under Enquiries.', 'kinetica-physio' ) ),
		'invalid' => array( 'error', __( 'Please fill in your name, a valid email address and a short message, and accept the privacy note.', 'kinetica-physio' ) ),
		'expired' => array( 'error', __( 'Your session expired. Please submit the form again.', 'kinetica-physio' ) ),
	);

	ob_start();

	if ( isset( $messages[ $status ] ) ) {
		printf(
			'<p class="kp-form__notice kp-form__notice--%1$s" role="%2$s">%3$s</p>',
			esc_attr( $messages[ $status ][0] ),
			'success' === $messages[ $status ][0] ? 'status' : 'alert',
			esc_html( $messages[ $status ][1] )
		);
	}
	?>
	<form class="kp-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
		<input type="hidden" name="action" value="kp_contact">
		<?php wp_nonce_field( 'kp_contact', 'kp_nonce' ); ?>
		<div class="kp-form__hp" aria-hidden="true">
			<label for="kp-website">Website</label>
			<input type="text" id="kp-website" name="kp_website" tabindex="-1" autocomplete="off">
		</div>
		<div class="kp-form__row">
			<p class="kp-form__field">
				<label for="kp-name"><?php esc_html_e( 'Full name', 'kinetica-physio' ); ?> <span aria-hidden="true">*</span></label>
				<input type="text" id="kp-name" name="kp_name" required maxlength="100" autocomplete="name">
			</p>
			<p class="kp-form__field">
				<label for="kp-email"><?php esc_html_e( 'Email', 'kinetica-physio' ); ?> <span aria-hidden="true">*</span></label>
				<input type="email" id="kp-email" name="kp_email" required maxlength="150" autocomplete="email">
			</p>
		</div>
		<div class="kp-form__row">
			<p class="kp-form__field">
				<label for="kp-phone"><?php esc_html_e( 'Phone (optional)', 'kinetica-physio' ); ?></label>
				<input type="tel" id="kp-phone" name="kp_phone" maxlength="30" autocomplete="tel">
			</p>
			<p class="kp-form__field">
				<label for="kp-service"><?php esc_html_e( 'What do you need help with?', 'kinetica-physio' ); ?></label>
				<select id="kp-service" name="kp_service">
					<?php foreach ( kp_services() as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>
		<p class="kp-form__field">
			<label for="kp-message"><?php esc_html_e( 'Tell us a little about the problem', 'kinetica-physio' ); ?> <span aria-hidden="true">*</span></label>
			<textarea id="kp-message" name="kp_message" rows="5" required maxlength="2000"></textarea>
		</p>
		<p class="kp-form__consent">
			<input type="checkbox" id="kp-consent" name="kp_consent" value="1" required>
			<label for="kp-consent"><?php esc_html_e( 'I agree that the clinic may contact me about this enquiry. Please do not include detailed medical records here.', 'kinetica-physio' ); ?></label>
		</p>
		<button type="submit" class="kp-form__submit wp-element-button"><?php esc_html_e( 'Send enquiry', 'kinetica-physio' ); ?></button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'kp_contact_form', 'kp_contact_form_shortcode' );

/**
 * Handles form submissions for logged-in and logged-out visitors.
 */
function kp_handle_contact() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( 'enquiry', $back );

	if ( ! isset( $_POST['kp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kp_nonce'] ) ), 'kp_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'expired', $back ) . '#contact' );
		exit;
	}

	// Honeypot: real visitors never fill this hidden field.
	if ( ! empty( $_POST['kp_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'sent', $back ) . '#contact' );
		exit;
	}

	$name    = isset( $_POST['kp_name'] ) ? sanitize_text_field( wp_unslash( $_POST['kp_name'] ) ) : '';
	$email   = isset( $_POST['kp_email'] ) ? sanitize_email( wp_unslash( $_POST['kp_email'] ) ) : '';
	$phone   = isset( $_POST['kp_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['kp_phone'] ) ) : '';
	$service = isset( $_POST['kp_service'] ) ? sanitize_key( wp_unslash( $_POST['kp_service'] ) ) : '';
	$message = isset( $_POST['kp_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['kp_message'] ) ) : '';
	$consent = ! empty( $_POST['kp_consent'] );

	$services = kp_services();
	if ( ! isset( $services[ $service ] ) ) {
		$service = 'not-sure';
	}

	if ( '' === $name || ! is_email( $email ) || '' === $message || ! $consent ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'invalid', $back ) . '#contact' );
		exit;
	}

	$name    = mb_substr( $name, 0, 100 );
	$phone   = mb_substr( $phone, 0, 30 );
	$message = mb_substr( $message, 0, 2000 );

	$body = sprintf(
		"Name: %s\nEmail: %s\nPhone: %s\nService: %s\n\n%s",
		$name,
		$email,
		$phone ? $phone : '—',
		$services[ $service ],
		$message
	);

	wp_insert_post(
		array(
			'post_type'    => 'kp_enquiry',
			'post_status'  => 'private',
			/* translators: 1: visitor name, 2: service label. */
			'post_title'   => sprintf( __( '%1$s — %2$s', 'kinetica-physio' ), $name, $services[ $service ] ),
			'post_content' => $body,
		)
	);

	wp_mail(
		get_option( 'admin_email' ),
		/* translators: %s: visitor name. */
		sprintf( __( 'New enquiry from %s', 'kinetica-physio' ), $name ),
		$body,
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	wp_safe_redirect( add_query_arg( 'enquiry', 'sent', $back ) . '#contact' );
	exit;
}
add_action( 'admin_post_nopriv_kp_contact', 'kp_handle_contact' );
add_action( 'admin_post_kp_contact', 'kp_handle_contact' );
