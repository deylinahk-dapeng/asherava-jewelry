<?php
/**
 * US / CA region selector (header + mobile drawer).
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$modifier = isset( $args['modifier'] ) ? sanitize_html_class( $args['modifier'] ) : '';
$wrap_class = 'av-header-utilities__locale-wrap';
if ( $modifier ) {
	$wrap_class .= ' av-header-utilities__locale-wrap--' . $modifier;
}
?>

<div class="<?php echo esc_attr( $wrap_class ); ?>">
	<button
		type="button"
		class="av-header-utilities__link av-header-utilities__locale-btn"
		aria-expanded="false"
		aria-controls="av-header-locale-menu<?php echo $modifier ? '-' . esc_attr( $modifier ) : ''; ?>"
		aria-label="<?php esc_attr_e( 'Country and region', 'asherava-jaxxon' ); ?>"
		data-country="us"
	>
		<span class="av-header-utilities__flag av-header-utilities__flag--us" aria-hidden="true">
			<svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect width="21" height="15" fill="#B22234"/>
				<path d="M0 1.15h21M0 3.46h21M0 5.77h21M0 8.08h21M0 10.38h21M0 12.69h21" stroke="#fff" stroke-width="1.15"/>
				<rect width="8.4" height="8.08" fill="#3C3B6E"/>
			</svg>
		</span>
	</button>
	<ul class="av-header-utilities__locale-menu" id="av-header-locale-menu<?php echo $modifier ? '-' . esc_attr( $modifier ) : ''; ?>" hidden>
		<li>
			<button type="button" class="av-header-utilities__locale-option is-active" data-country="us">
				<span class="av-header-utilities__flag av-header-utilities__flag--us" aria-hidden="true">
					<svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect width="21" height="15" fill="#B22234"/>
						<path d="M0 1.15h21M0 3.46h21M0 5.77h21M0 8.08h21M0 10.38h21M0 12.69h21" stroke="#fff" stroke-width="1.15"/>
						<rect width="8.4" height="8.08" fill="#3C3B6E"/>
					</svg>
				</span>
				<span><?php esc_html_e( 'United States', 'asherava-jaxxon' ); ?></span>
			</button>
		</li>
		<li>
			<button type="button" class="av-header-utilities__locale-option" data-country="ca">
				<span class="av-header-utilities__flag av-header-utilities__flag--ca" aria-hidden="true">
					<svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect width="21" height="15" fill="#fff"/>
						<rect x="5.25" width="10.5" height="15" fill="#D80621"/>
						<path d="M10.5 3.2l.9 1.85 2.02-.3-1.46 1.42.35 2.01-1.81-.95-1.81.95.35-2.01-1.46-1.42 2.02.3.9-1.85z" fill="#D80621"/>
					</svg>
				</span>
				<span><?php esc_html_e( 'Canada', 'asherava-jaxxon' ); ?></span>
			</button>
		</li>
	</ul>
</div>
