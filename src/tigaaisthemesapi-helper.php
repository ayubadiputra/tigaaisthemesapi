<?php
/*
 * Plugin specific function
 */

function tigaaisthemesapi_asset($path) {

	return plugins_url(Config::get('tigaaisthemesapi.assets')."/".$path, dirname(TIGAAISTHEMESAPI_BASE_PATH) );
}

