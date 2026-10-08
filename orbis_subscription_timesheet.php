<?php
/**
 * Pronamic support messages for the subscription timesheet period.
 *
 * Loaded on the `orbis_subscription_timesheet_period` action of the Orbis
 * Timesheets plugin, which passes the timesheet period in `$args`.
 */

$timesheet_period = $args['timesheet_period'];

$subscription      = $timesheet_period->subscription;
$total_seconds     = $timesheet_period->total_seconds;
$available_seconds = $timesheet_period->available_seconds;

if ( null === $available_seconds ) {
	return;
}

$is_exceeded = $total_seconds > $available_seconds;

?>
<div class="card mb-3">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" data-bs-toggle="tab" data-bs-target="#orbis-pronamic-message-simple" type="button" role="tab">Eenvoudig</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" data-bs-toggle="tab" data-bs-target="#orbis-pronamic-message-reply" type="button" role="tab">Bericht</button>
			</li>
		</ul>
	</div>

	<div class="tab-content">
		<div class="tab-pane active" id="orbis-pronamic-message-simple" role="tabpanel">
			<div class="card-body">

				<?php if ( 'strippenkaart' === ( $subscription->status ?? null ) ) : ?>

					<div class="alert alert-warning mb-3" role="alert">
						<i class="fas fa-exclamation-triangle"></i> Tijdregistraties op strippenkaart.
					</div>

				<?php endif; ?>

				<?php if ( $is_exceeded ) : ?>

					<?php

					$search_url = add_query_arg(
						array(
							's' => 'strippenkaart ' . $subscription->name,
						),
						get_post_type_archive_link( 'orbis_project' )
					);

					?>

					<div class="alert alert-info mb-3" role="alert">
						<i class="fas fa-ticket-alt"></i> <a href="<?php echo esc_url( $search_url ); ?>">Zoek "Strippenkaart"</a>.
					</div>

				<?php endif; ?>

				<div id="orbis-subscription-simple-message">
					<?php if ( $is_exceeded ) : ?>

						🤖 We hebben in de afgelopen periode meer support uren geregistreerd dan beschikbaar zijn binnen het <a href="https://www.pronamic.nl/wordpress/wordpress-onderhoud/">WordPress onderhoud en support</a> abonnement 📈. We komen graag in contact met je om af te stemmen hoe we hier mee verder gaan 📞. Je kunt bijvoorbeeld een <a href="https://www.pronamic.nl/strippenkaarten/">strippenkaart</a> bestellen of je abonnement upgraden. We horen graag van je!

					<?php else : ?>

						<?php

						printf(
							'Je hebt nog %s uren beschikbaar binnen het onderhoudsabonnement (%s uren).',
							esc_html( orbis_time( $available_seconds - $total_seconds ) ),
							esc_html( orbis_time( $available_seconds ) )
						);

						?>

					<?php endif; ?>
				</div>
			</div>

			<div class="card-footer">
				<button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText( document.getElementById( 'orbis-subscription-simple-message' ).innerHTML.trim() );"><i class="fas fa-paste"></i> Kopieer HTML-bericht</button>
			</div>
		</div>

		<div class="tab-pane" id="orbis-pronamic-message-reply" role="tabpanel">
			<div class="card-body">
				<div id="helpscout-auto-reply-message">
					Beste lezer,<br />
					<br />
					Bedankt voor het indienen van een supportaanvraag bij Pronamic. Hieronder vind je alvast een overzicht van de geregistreerde uren binnen het "<?php echo esc_html( get_the_title() ); ?>" abonnement:<br />
					<br />
					<table class="table table-striped table-bordered w-auto mb-0" border="1">
						<thead>
							<tr>
								<th scope="col">Maand</th>
								<th scope="col">Tijd</th>
							</tr>
						</thead>

						<tfoot>
							<tr>
								<th scope="row">Totaal</th>
								<td><?php echo esc_html( orbis_time( $total_seconds ) . ' / ' . orbis_time( $available_seconds ) ); ?></td>
							</tr>
						</tfoot>

						<tbody>

							<?php foreach ( $timesheet_period->months as $month ) : ?>

								<tr>
									<th scope="row"><?php echo esc_html( ucfirst( wp_date( 'F Y', $month->date->getTimestamp() ) ) ); ?></th>
									<td><?php echo esc_html( orbis_time( $month->number_seconds ) ); ?></td>
								</tr>

							<?php endforeach; ?>

						</tbody>
					</table>

					<br />

					<?php if ( $is_exceeded ) : ?>

						We hebben in de afgelopen periode meer support uren geregistreerd dan beschikbaar zijn binnen het
						<a href="https://www.pronamic.nl/wordpress/wordpress-onderhoud/">WordPress onderhoud en support</a>
						abonnement. Om je te kunnen helpen willen we je vragen om een
						<a href="https://www.pronamic.nl/strippenkaarten/">strippenkaart</a> te bestellen of je abonnement
						te upgraden.<br />

						<br />

					<?php endif; ?>

					Met vriendelijke groet,<br />
					Pronamic
				</div>
			</div>

			<div class="card-footer">
				<button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText( document.getElementById( 'helpscout-auto-reply-message' ).innerHTML.trim() );"><i class="fas fa-paste"></i> Kopieer HTML-bericht</button>
			</div>
		</div>
	</div>
</div>
