<?php
/**
 * Contact Form 7 + Tidio helpers.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wpcf7_autop', 'asherava_cf7_disable_autop' );
/**
 * Keep CF7 markup clean for custom field layout.
 *
 * @param bool $autop Whether to wrap in paragraphs.
 */
function asherava_cf7_disable_autop( $autop ) {
	return false;
}

add_filter( 'wpcf7_form_elements', 'asherava_cf7_form_classes' );
/**
 * Add theme classes to CF7 submit button.
 *
 * @param string $html Form HTML.
 */
function asherava_cf7_form_classes( $html ) {
	return str_replace( 'class="wpcf7-submit', 'class="wpcf7-submit av-cf7__submit', $html );
}

add_action( 'wp_footer', 'asherava_tidio_visitor_context', 5 );
/**
 * Pass logged-in customer context to Tidio when the widget loads.
 */
function asherava_tidio_visitor_context() {
	if ( is_admin() || ! get_option( 'tidio-one-public-key' ) ) {
		return;
	}

	$visitor = array();
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$name = trim( $user->first_name . ' ' . $user->last_name );
		if ( '' === $name ) {
			$name = $user->display_name;
		}
		$visitor = array(
			'distinct_id' => (string) $user->ID,
			'email'       => $user->user_email,
			'name'        => $name,
		);
	}

	?>
	<script>
	(function () {
		var visitor = <?php echo wp_json_encode( $visitor ); ?>;

		function applyVisitor() {
			if (!window.tidioChatApi || !visitor.email) {
				return;
			}
			window.tidioChatApi.setVisitorData(visitor);
		}

		if (window.tidioChatApi) {
			applyVisitor();
		} else {
			document.addEventListener('tidioChat-ready', applyVisitor);
		}
	})();
	</script>
	<?php
}

/**
 * Open Tidio chat from theme templates or page links.
 */
function asherava_tidio_open_chat_link() {
	if ( ! get_option( 'tidio-one-public-key' ) ) {
		return '';
	}

	return 'javascript:if(window.tidioChatApi){window.tidioChatApi.open();}else{document.addEventListener(\'tidioChat-ready\',function(){window.tidioChatApi.open();},{once:true});}void(0);';
}
