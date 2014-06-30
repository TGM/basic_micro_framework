<?php
/**
 * @author		Cazacu Dan <marius.cdm@extremetop.com>
 * @copyright	Copyright (c), 2014 Cazacu Dan
 * @license		MIT public license
 */

class Router
{
	// return full route path in an accesible format
	function parse()
	{
		// Get the current Request URI and remove rewrite basepath from it (= allows one to run the router in a subfolder)
		$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		// just to be safe throw an extra filter
		$secure_uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
		$uri = substr($secure_uri, strlen($basepath));

		// Don't take query params into account on the URL
		if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));

		// Remove trailing slash + enforce a slash at the start
		$uri = '/' . trim($uri, '/');

		// Remove unwanted characters
		$uri = preg_replace('/[^a-z0-9\-\_\/]/iD', '', $uri);
	
		// Put everything in an array for further use
		$uri = preg_split('%\b(?=/)%', $uri);
	
		return $uri;
	}

	function error($statusCode)
	{
		$error_title = null;
		$error_message = null;

		switch ($statusCode)
		{
			case 401:
				header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
				$error_title = 'Error 401 (Unauthorized)';
				$error_message = 'Requires user authentication, please retry.';
				break;
			case 404:
				header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
				$error_title = 'Error 404 (Not Found)';
				$error_message = 'The page that you have requested could not be found.';
				break;
			case 503:
				header($_SERVER['SERVER_PROTOCOL'].' 503 Service Temporarily Unavailable');
				header('Status: 503 Service Temporarily Unavailable');
				header('Retry-After: 300');
				echo '<div style="text-align:center">Website under maintenance!</div>';
				exit;
			default:
				return null;
			break;
		}
		
		$error = '<!doctype html>
		<html lang=en>
			<meta charset=utf-8>
			<meta name=viewport content="initial-scale=1, minimum-scale=1, width=device-width">
				<title>'.$error_title.'</title>
			<style>
				body {margin: 7% auto 0;max-width:550px;}ins{color:#777;text-decoration:none}
				p{margin:11px 0 22px;overflow:hidden}a{text-decoration:none; color:#0d85f6}a img{border:0}
			</style>
				<a href=""/><img src= alt=></a>
				<p><b>'.$statusCode.'.</b> <ins>That&#39;s an error.</ins>
				<p>'.$error_message.'</p>
				<p><ins>Back to <a href=""/></a></ins></p>
			</html>';
		echo $error;
		exit;
	}
	
	function redirect($url, $statusCode = null)
	{
		switch($statusCode)
		{
			case 301: // "Moved Permanently"—recommended for SEO
				header($_SERVER['SERVER_PROTOCOL'].' 301 Moved Permanently');
				break;
			case 302: // "Found" or "Moved Temporarily"
			default:
				break;
		}
		header('Location: '.$url);
		exit;
	}
	
	function validate($string)
	{
		// allow 0-9, a-z, -, _, case insensitive, end delimiter to avoid %0A
		$pattern = '/^[a-z0-9\-\_]+$/iD';
	
		return preg_match($pattern, $string);
	}

	static public function slugify($text)
	{ 
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

		// trim
		$text = trim($text, '-');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
			return 'n-a';
		}

		return $text;
	}


}

?>
