<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ip_address
 *
 * get client ip addresss
 *
 * @access	public 
 * @return	string
 */
if ( ! function_exists('ip_address'))
{
	function ip_address()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(isset($_SERVER['HTTP_X_CLIENT_RIP']))
		{
			$ipaddress = $_SERVER['HTTP_X_CLIENT_RIP'];
		}
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	    {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	    {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		}
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	    {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		}
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	    {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		}
	    else if(isset($_SERVER['REMOTE_ADDR']))
	    {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}
	    else
	        $ipaddress = '';
		return $ipaddress;
	}
}