<?php

define('TIGAAISTHEMESAPI_BASE_PATH', dirname(__FILE__)."/../");

add_action('tiga_routes',function()
{
	include TIGAAISTHEMESAPI_BASE_PATH."app/routes.php";

});

// Add default config
add_filter('tiga_config',function($configs)
{
	$config = include TIGAAISTHEMESAPI_BASE_PATH."app/config/app.php";

	return array_merge_recursive($configs,$config);
});