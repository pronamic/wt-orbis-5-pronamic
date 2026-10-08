<?php

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function orbis_pronamic_setup() {
	/* Make theme available for translation */
	load_theme_textdomain( 'orbis_pronamic', get_stylesheet_directory() . '/languages' );
}

add_action( 'after_setup_theme', 'orbis_pronamic_setup' );
