<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// close all db connection
function dbClose()
{
	$CI =& get_instance();
	if (class_exists('CI_DB') AND isset($CI->db) && is_object($CI->db))
	{
        if(method_exists($CI->db, 'close'))
        {
		    $CI->db->close();
        }
		$CI->db = null;
	}

	if (class_exists('CI_DB') AND isset($CI->db_slave) && is_object($CI->db_slave) && method_exists($CI->db_slave, 'close'))
	{
        if(method_exists($CI->db_slave, 'close'))
        {
		    $CI->db_slave->close();
        }
		$CI->db_slave = null;
	}
}