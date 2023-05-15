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
 * CodeIgniter Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class MY_Config extends CI_Config {
	/**
	 * Site URL
	 *
	 * Returns base_url . index_page [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	public function site_url($uri = '', $langcode = '', $protocol = NULL)
	{
		$uri = ltrim($uri, '/');
		$langcode = trim(strtolower($langcode));
		$arr = $this->item('lang_uri_abbr');
		$uri_path = URI_PATH == '' ? '' : URI_PATH . '/';
		
		if($langcode == '')
		{
			$langcode = $arr[$this->item('language_abbr')];	
		}
		
		$validLangCode = false;
		foreach($arr as $key=>$item)
		{
			if($item == $langcode)
			{
				$langcode = $key;
				$validLangCode = true;
				break;
			}
		}
		if(!$validLangCode)
		{
			$langcode = $this->item('language_abbr');
		}
		
		$uri = ltrim($uri, '/');
		
		
		// get base url
		$base_url = $this->slash_item('base_url');
		if (isset($protocol))
		{
			// For protocol-relative links
			if ($protocol === '')
			{
				$base_url = substr($base_url, strpos($base_url, '//'));
			}
			else
			{
				$base_url = $protocol.substr($base_url, strpos($base_url, '://'));
			}
		}
		
		if ($uri == '')
		{
			if(MULTI_LANGUAGE)
			{
				return $base_url . $this->slash_item($this->item('index_page')) . $langcode . '/' . $uri_path;
			}
			else
			{
				return $base_url . $this->slash_item($this->item('index_page')) . $uri_path;
			}
		}
		else
		{
			$chkIsLogin = false;
			$arr = explode('?', $uri, 2);
			$narr = explode('/', $arr[0]);
			$nUri = '';
			$nIndex = 0;
			
			if(isset($narr[0]) && (strtolower(trim($narr[0])) == LOGIN_MOD || strtolower(trim($narr[0])) == LOGOUT_MOD))
			{
				$chkIsLogin = true;
			}
		
			foreach($narr as $item)
			{
				if(trim($item) != '')
				{
					$nUri .= ($nUri == '') ? '' : '/';	
					$nUri .= ($nIndex == 1) ? trim(strtolower(str_replace('_','-',$item))) : trim($item);
				}
				else
				{
					$nUri .= '/';
				}
				$nIndex++;
			}
			
			
			if($chkIsLogin)
			{
				if(isset($arr[1]))
				{
					$arr[1] = trim($arr[1]) == '' ? 'url=' . urlencode($base_url) : trim($arr[1]);
				}
				else
				{
					$arr[1] = 'url=' . urlencode($base_url);
				}
			}
			
			$uri = isset($arr[1]) ? $nUri . '?' . trim($arr[1]) : $nUri;
			
			$arr = null;
			$narr = null;
			if(MULTI_LANGUAGE)
			{
				$index = $this->slash_item('index_page');
				$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
				
				if($chkIsLogin)
				{
					return rtrim(ROOT_DOMAIN , '/') . '/' . $index . $langcode . '/' . trim($uri, '/').$suffix;
				}
				else
				{
					return $base_url.$index . $langcode . '/' . $uri_path .trim($uri, '/').$suffix;
				}
			}
			else
			{
				$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
				
				if($chkIsLogin)
				{
					return rtrim(ROOT_DOMAIN , '/') . '/' .$this->slash_item('index_page') . trim($uri, '/').$suffix;
				}
				else
				{
					return $base_url.$this->slash_item('index_page') . $uri_path.trim($uri, '/').$suffix;
				}
			}
		}
	}
	
	/**
	 * Toannh: Get language code
	 *
	 * @access	public
	 * @return	string
	 */
	 
	 function get_langcode()
	 {
	 	$langcode = '';
		$arrLang = $this->item('lang_uri_abbr');
		$defaultLangCode = $this->item('language_abbr');
		$currUri = $_SERVER['REQUEST_URI'];
		if(CUSTOM_FOLDER != '')
		{
			$currUri = preg_replace('/^\/?'.CUSTOM_FOLDER.'\s*/i', '', $currUri);
		}
		if(MULTI_LANGUAGE)
		{
			if($currUri != '')
			{
				if(substr($currUri,0, 1) == '/')
				{
					$currUri = substr($currUri,1);
				}
				$arrUri = explode('/', $currUri);
				$tmpLang = $arrUri[0];							
				$isValidLang = false;
				foreach($arrLang as $key=>$val)
				{
					if($key == $tmpLang)
					{
						$isValidLang = true;
						break;
					}
				}
				$langcode = ($isValidLang) ? $arrLang[$tmpLang] : $arrLang[$defaultLangCode];
			}
			else
			{
				$langcode =  $arrLang[$defaultLangCode];
			}
		}
		else
		{
			$langcode =  $arrLang[$defaultLangCode];
		}
		return $langcode;
	 }
	
	/**
	 * Toannh: Get language code for url
	 *
	 * @access	public
	 * @param	string the current language string
	 * @return	string
	 */
	 
	 function get_langcode_url($language)
	 {
	 	$language = trim(strtolower($language));
	 	$arrLang = $this->item('lang_uri_abbr');
		$langcodeUrl = '';
		if(MULTI_LANGUAGE)
		{
			foreach($arrLang as $key=>$langItem)
			{
				if($language == $langItem)
				{
					$langcodeUrl = $key;
					break;
				}
			}
		}
		
		return $langcodeUrl;
	 }
	 
	 /**
	 * Toannh: Get language code id
	 *
	 * @access	public
	 * @param	string the current language string
	 * @return	string
	 */
	 
	 function get_langcode_id($language)
	 {
	 	$language = trim(strtolower($language));
	 	$arrLangId = $this->item('langcodeid');
		$langcodeid = '';
		foreach($arrLangId as $key=>$langid)
		{
			if($language == $key)
			{
				$langcodeid = $langid;
				break;
			}
		}
		
		if($langcodeid == '')
		{
			$langcodeid = 1;
		}
		
		return $langcodeid;
	 }
	 
	 /**
	 * Toannh: Get list langcode
	 *
	 * @access	public
	 * @param	get list language
	 * @return	string
	 */
	 
	 function get_list_langcode()
	 {
	 	$arrLangId = $this->item('langcodeid');
		$arr = array();
		foreach($arrLangId as $key=>$langid)
		{
			$arr[] = array('id'=> $langid, 'name'=>$key);
		}
		return $arr;
	 }

}

// END CI_Config class

/* End of file Config.php */
/* Location: ./system/core/Config.php */