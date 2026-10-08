<?php
/**
 * Orbis 5 Pronamic deploy
 *
 * @package orbis-5-pronamic
 */

declare(strict_types=1);

namespace Deployer;

require 'recipe/common.php';

set( 'theme_slug', 'orbis-5-pronamic' );

set( 'build_path', './build/' );

$deployer_import = getenv( 'DEPLOYER_IMPORT' );

if ( false !== $deployer_import && '' !== $deployer_import ) {
	import( $deployer_import );
}

/**
 * Build.
 */
task(
	'build',
	function () {
		runLocally( 'composer run-script build' );
	}
);

task(
	'deploy:update_code',
	function () {
		upload( '{{build_path}}/orbis-5-pronamic/', '{{release_path}}' );
	}
);

task(
	'deploy:symlink_theme',
	function () {
		run( 'ln -sfn {{deploy_path}}/current {{themes_dir}}/{{theme_slug}}' );
	}
);

after( 'deploy:symlink', 'deploy:symlink_theme' );

task(
	'deploy',
	[
		'build',
		'deploy:prepare',
		'deploy:publish',
	]
);
