<?php

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function orbis_pronamic_setup() {
	/* Make theme available for translation */
	load_theme_textdomain( 'orbis_pronamic', get_stylesheet_directory() . '/languages' );
}

add_action( 'after_setup_theme', 'orbis_pronamic_setup' );

/**
 * Pronamic support messages for the subscription timesheet period of the Orbis Timesheets plugin.
 */
add_action( 'orbis_subscription_timesheet_period', function( $timesheet_period ) {
	get_template_part(
		'orbis_subscription_timesheet',
		null,
		array(
			'timesheet_period' => $timesheet_period,
		)
	);
} );

add_action( 'orbis_before_side_content', function() {
	if ( ! is_singular( 'orbis_company' ) ) {
		return;
	}

	$post = get_post();

	?>
	<div class="card mb-3">
		<div class="card-header"><?php esc_html_e( 'Agreement Form', 'orbis' ); ?></div>

		<div class="list-group">
			<?php

			$post = get_post();

			$args = array(
				'bedrijf'        => get_the_title( $post ),
				'kvk-nummer'     => get_post_meta( $post->ID, '_orbis_kvk_number', true ),
				'btw-nummer'     => get_post_meta( $post->ID, '_orbis_vat_number', true ),
				'voornaam'       => '',
				'achternaam'     => '',
				'straat'         => get_post_meta( $post->ID, '_orbis_address', true ),
				'postcode'       => get_post_meta( $post->ID, '_orbis_postcode', true ),
				'plaats'         => get_post_meta( $post->ID, '_orbis_city', true ),
				'factuur-e-mail' => get_post_meta( $post->ID, '_orbis_invoice_email', true ),
				'referentie'     => '',
				'eenmalig'       => '0',
				'jaarlijks'      => '0',
				'maandelijks'    => '0',
			);

			$url_agreement_form = 'https://www.pronamic.nl/akkoord/';

			$url_agreement_form = add_query_arg( urlencode_deep( $args ), $url_agreement_form );

			$products = array(
				(object) array(
					'name'  => 'Strippenkaart 2 uren',
					'price' => '210',
				),
				(object) array(
					'name'  => 'Strippenkaart 5 uren',
					'price' => '500',
				),
				(object) array(
					'name'  => 'Strippenkaart 10 uren',
					'price' => '950',
				),
			);

			foreach ( $products as $product ) {
				$url = add_query_arg(
					urlencode_deep(
						array(
							'referentie' => $product->name,
							'eenmalig'   => $product->price,
						)
					),
					$url_agreement_form
				);

				printf(
					'<a href="%s" class="list-group-item list-group-item-action">%s</a>',
					esc_url( $url ),
					esc_html( $product->name )
				);
			}

			?>
		</div>
	</div>
	<?php
} );
