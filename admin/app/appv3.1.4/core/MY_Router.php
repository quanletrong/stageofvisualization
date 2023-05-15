<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Router extends CI_Router {
	
	/**
	 * Set default controller
	 *
	 * @return	void
	 */
	protected function _set_default_controller()
	{
		if (empty($this->default_controller))
		{
			show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
		}
		
		/*
		// Is the method being specified?
		if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
		{
			$method = 'index';
		}

		if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
		{
			// This will trigger 404 later
			return;
		}

		$this->set_class($class);
		$this->set_method($method);
		*/
		
		// modify by toannh
		$arr = explode('/', $this->default_controller);
		if(isset($arr[2]))
		{
			$arr[2] = trim($arr[2]);
			$arr[2] = $arr[2] == '' ? 'index' : $arr[2];
			$this->set_method($arr[2]);
		}
		
		if(isset($arr[1]))
		{
			$arr[1] = trim($arr[1]);
			$this->set_class($arr[1]);
		}
		
		if(isset($arr[0]))
		{
			$arr[0] = trim($arr[0]);
			$this->set_directory($arr[0]);
		}
		
		if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($this->class).'.php'))
		{
			// This will trigger 404 later
			return;
		}
		
		// Assign routed segments, index starting from 1
		$this->uri->rsegments = array(
			1 => $this->class,
			2 => $this->method
		);
		
		//log_message('debug', 'No URI present. Default controller set.');
	}
}