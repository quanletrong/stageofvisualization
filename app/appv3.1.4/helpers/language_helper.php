<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Create by toannh
 * 
 */
 
/**
 * get_langcode
 *
 * Get language code
 *
 * @access	public 
 * @return	string
 */
if ( ! function_exists('get_langcode'))
{
	function get_langcode()
	{
		$CI =& get_instance();
		return $CI->config->get_langcode();
	}
}

/**
 * get_langcode url
 *
 * Get language code url
 *
 * @access	public 
 * @return	string
 */
if ( ! function_exists('get_langcode_url'))
{
	function get_langcode_url($language)
	{
		$CI =& get_instance();
		return $CI->config->get_langcode_url($language);
	}
}

/**
 * get_langcode id
 *
 * Get language code id
 *
 * @access	public 
 * @return	string
 */
if ( ! function_exists('get_langcode_id'))
{
	function get_langcode_id($language)
	{
		$CI =& get_instance();
		return $CI->config->get_langcode_id($language);
	}
}

/**
 * get_list_langcode
 *
 * Get list language code
 *
 * @access	public 
 * @return	string
 */
if ( ! function_exists('get_list_langcode'))
{
	function get_list_langcode()
	{
		$CI =& get_instance();
		return $CI->config->get_list_langcode();
	}
}