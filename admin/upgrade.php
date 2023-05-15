<?php 
$uri = ltrim(trim(strtolower($_SERVER['REQUEST_URI'])), '/');

if( $uri != 'ecomx/admin/upgrade')
{
	header("Location: upgrade");
	die();
}
include_once('app/default/errors/error_general.php');
?>
