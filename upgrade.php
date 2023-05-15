<?php 
$uri = ltrim(trim(strtolower($_SERVER['REQUEST_URI'])), '/');

if( $uri != 'vn/upgrade' && $uri != 'en/upgrade' && $uri != 'upgrade')
{
	header("Location: upgrade");
	die();
}
include_once('app/default/errors/error_general.php');
?>
