<?php

/*
+--------------------------------------------------------------------------
|   Basic Micro Framework v0.1
|   ========================================
|   Copyright (c), 2014 Cazacu Dan
+--------------------------------------------------------------------------
*/

// security measure for included files
define("IN_ENGINE", true);

// build a basic router

// include router
require_once dirname(__FILE__)."/libs/class.Router.php";

// init router
$router = new Router();

// generate routes
$route = $router->parse();

// index route exception
$route[0] = $route[0] == '/index' ? $router->redirect('/') : $route[0];
$route[0] = $route[0] == '/' ? $route[0] = 'index' : trim($route[0], '/');

if (file_exists(dirname(__FILE__)."/template/".$route[0].".html") && $route[0] != 'header' && $route[0] != 'footer')
{
	// include header
	require_once dirname(__FILE__)."/header.php";

	// include content
	require_once dirname(__FILE__)."/content.php";

	// include footer
	require_once dirname(__FILE__)."/footer.php";
}
	else
{
	$router->error(404);
}

?>