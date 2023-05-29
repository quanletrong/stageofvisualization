<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * URI Class
 *
 * Parses URIs and determines routing
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	URI
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/uri.html
 */
class MY_URI extends CI_URI {
	
	// --------------------------------------------------------------------
	
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->config =& load_class('Config', 'core');

		// If query strings are enabled, we don't need to parse any segments.
		// However, they don't make sense under CLI.
		if (is_cli() OR $this->config->item('enable_query_strings') !== TRUE)
		{
			$this->_permitted_uri_chars = $this->config->item('permitted_uri_chars');

			// If it's a CLI request, ignore the configuration
			if (is_cli())
			{
				$uri = $this->_parse_argv();
			}
			else
			{
				$protocol = $this->config->item('uri_protocol');
				empty($protocol) && $protocol = 'REQUEST_URI';

				switch ($protocol)
				{
					case 'AUTO': // For BC purposes only
					case 'REQUEST_URI':
						$uri = $this->_parse_request_uri();
						break;
					case 'QUERY_STRING':
						$uri = $this->_parse_query_string();
						break;
					case 'PATH_INFO':
						$uri = $this->_fetch_uri_string();
						break;
					default:
						$uri = isset($_SERVER[$protocol])
							? $_SERVER[$protocol]
							: $this->_parse_request_uri();
						break;
				}
			}

			$this->_set_uri_string($uri);
		}

		//log_message('info', 'URI Class Initialized');
	}
	
	/**
	 * Get the URI String
	 *
	 * @access	private
	 * @return	string
	 */
	function _fetch_uri_string()
	{
		$base_url = $this->config->item('base_url');
		// for server not support $_SERVER['PATH_INFO']
		$path = $_SERVER['REQUEST_URI'];
		if($path != '')
		{
			$tmp = parse_url($path);
			$path = trim($tmp['path'],'/');
			if($path != '')
			{
				$arr = parse_url($base_url);
				$base_path = isset($arr['path']) ? $arr['path']:'';
				$base_path = trim($base_path, '/');
				
				$path = str_ireplace($base_path, '', $path);
			}
		}
		$path = $this->_remove_relative_directory($path);
		// end for server not support $_SERVER['PATH_INFO']
		if($path != '')
		{
			// Filter out control characters and trim slashes
			$path = trim(remove_invisible_characters($path, FALSE), '/');
	
			if ($path !== '')
			{
				// Remove the URL suffix, if present
				if (($suffix = (string) $this->config->item('url_suffix')) !== '')
				{
					$slen = strlen($suffix);
	
					if (substr($path, -$slen) === $suffix)
					{
						$path = substr($path, 0, -$slen);
					}
				}
			}
		}
		
		$uri = $path;

		return $uri;
	}
}
// END URI Class

/* End of file URI.php */
/* Location: ./system/core/URI.php */