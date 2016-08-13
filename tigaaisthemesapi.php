<?php

/*
 * Plugin Name: Tigaaisthemesapi 
 * Description: Tigaaisthemesapi Plugin
 */

add_action( 'tiga_plugin', 'tigaaisthemesapi_bootstrap' );

function tigaaisthemesapi_bootstrap() {
	require __DIR__ . "/vendor/autoload.php";
	require __DIR__ . "/app/bootstrap.php";
}