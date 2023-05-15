<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Initialize the database
 *
 * @category	Database
 * @author	EllisLab Dev Team
 * @link	https://codeigniter.com/user_guide/database/
 *
 * @param 	string|string[]	$params
 * @param 	bool		$query_builder_override
 *				Determines if query builder should be used or not
 */
function &DB($params = '', $query_builder_override = NULL)
{
	// Load the DB config file if a DSN string wasn't passed
	if (is_string($params) && strpos($params, '://') === FALSE)
	{
		// Is the config file in the environment folder?
		if ( ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
			&& ! file_exists($file_path = APPPATH.'config/database.php'))
		{
			show_error('The configuration file database.php does not exist.');
		}

		include($file_path);

		// Make packages contain database config files,
		// given that the controller instance already exists
		if (class_exists('CI_Controller', FALSE))
		{
			foreach (get_instance()->load->get_package_paths() as $path)
			{
				if ($path !== APPPATH)
				{
					if (file_exists($file_path = $path.'config/'.ENVIRONMENT.'/database.php'))
					{
						include($file_path);
					}
					elseif (file_exists($file_path = $path.'config/database.php'))
					{
						include($file_path);
					}
				}
			}
		}

		if ( ! isset($db) OR count($db) === 0)
		{
			show_error('No database connection settings were found in the database config file.');
		}

		if ($params !== '')
		{
			$active_group = $params;
		}

		if ( ! isset($active_group))
		{
			show_error('You have not specified a database connection group via $active_group in your config/database.php file.');
		}
		elseif ( ! isset($db[$active_group]))
		{
			show_error('You have specified an invalid database connection group ('.$active_group.') in your config/database.php file.');
		}

		$params = $db[$active_group];
	}
	elseif (is_string($params))
	{
		/**
		 * Parse the URL from the DSN string
		 * Database settings can be passed as discreet
		 * parameters or as a data source name in the first
		 * parameter. DSNs must have this prototype:
		 * $dsn = 'driver://username:password@hostname/database';
		 */
		if (($dsn = @parse_url($params)) === FALSE)
		{
			show_error('Invalid DB Connection String');
		}

		$params = array(
			'dbdriver'	=> $dsn['scheme'],
			'hostname'	=> isset($dsn['host']) ? rawurldecode($dsn['host']) : '',
			'port'		=> isset($dsn['port']) ? rawurldecode($dsn['port']) : '',
			'username'	=> isset($dsn['user']) ? rawurldecode($dsn['user']) : '',
			'password'	=> isset($dsn['pass']) ? rawurldecode($dsn['pass']) : '',
			'database'	=> isset($dsn['path']) ? rawurldecode(substr($dsn['path'], 1)) : ''
		);

		// Were additional config items set?
		if (isset($dsn['query']))
		{
			parse_str($dsn['query'], $extra);

			foreach ($extra as $key => $val)
			{
				if (is_string($val) && in_array(strtoupper($val), array('TRUE', 'FALSE', 'NULL')))
				{
					$val = var_export($val, TRUE);
				}

				$params[$key] = $val;
			}
		}
	}

	// No DB specified yet? Beat them senseless...
	if (empty($params['dbdriver']))
	{
		show_error('You have not selected a database type to connect to.');
	}

	// Load the DB classes. Note: Since the query builder class is optional
	// we need to dynamically create a class that extends proper parent class
	// based on whether we're using the query builder class or not.
	if ($query_builder_override !== NULL)
	{
		$query_builder = $query_builder_override;
	}
	// Backwards compatibility work-around for keeping the
	// $active_record config variable working. Should be
	// removed in v3.1
	elseif ( ! isset($query_builder) && isset($active_record))
	{
		$query_builder = $active_record;
	}

	require_once(BASEPATH.'database/DB_driver.php');

	if ( ! isset($query_builder) OR $query_builder === TRUE)
	{
		require_once(BASEPATH.'database/DB_query_builder.php');
		if ( ! class_exists('CI_DB', FALSE))
		{
			/**
			 * CI_DB
			 *
			 * Acts as an alias for both CI_DB_driver and CI_DB_query_builder.
			 *
			 * @see	CI_DB_query_builder
			 * @see	CI_DB_driver
			 */
			class CI_DB extends CI_DB_query_builder { }
		}
	}
	elseif ( ! class_exists('CI_DB', FALSE))
	{
		/**
	 	 * @ignore
		 */
		class CI_DB extends CI_DB_driver { }
	}

	// Load the DB driver
	$driver_file = BASEPATH.'database/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_driver.php';

	file_exists($driver_file) OR show_error('Invalid DB driver');
	require_once($driver_file);

	// Instantiate the DB adapter
	$driver = 'CI_DB_'.$params['dbdriver'].'_driver';
	$DB = new $driver($params);

	// Check for a subdriver
	if ( ! empty($DB->subdriver))
	{
		$driver_file = BASEPATH.'database/drivers/'.$DB->dbdriver.'/subdrivers/'.$DB->dbdriver.'_'.$DB->subdriver.'_driver.php';

		if (file_exists($driver_file))
		{
			require_once($driver_file);
			$driver = 'CI_DB_'.$DB->dbdriver.'_'.$DB->subdriver.'_driver';
			$DB = new $driver($params);
		}
	}

	$DB->initialize();
	return $DB;
}

function &DB_SLAVE($params = '', $query_builder_override = NULL)
{
	// Load the DB config file if a DSN string wasn't passed
	$randNum = 0;
	$numSlave = 0;
	$arrSlaveDie = array();
	$numSlaveDie = 0;
	$dbMaster = array();
	if(defined('DB_SLAVE_DIE'))
	{
		if(DB_SLAVE_DIE != '')
		{
			$arrSlaveDie = explode(',', DB_SLAVE_DIE);
			$numSlaveDie = count($arrSlaveDie);
		}
	}
	
	// Load the DB config file if a DSN string wasn't passed
	if (is_string($params) && strpos($params, '://') === FALSE)
	{
		// Is the config file in the environment folder?
		if ( ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
			&& ! file_exists($file_path = APPPATH.'config/database.php'))
		{
			show_error('The configuration file database.php does not exist.');
		}

		include($file_path);

		// Make packages contain database config files,
		// given that the controller instance already exists
		if (class_exists('CI_Controller', FALSE))
		{
			foreach (get_instance()->load->get_package_paths() as $path)
			{
				if ($path !== APPPATH)
				{
					if (file_exists($file_path = $path.'config/'.ENVIRONMENT.'/database.php'))
					{
						include($file_path);
					}
					elseif (file_exists($file_path = $path.'config/database.php'))
					{
						include($file_path);
					}
				}
			}
		}

		if ( ! isset($db) OR count($db) === 0)
		{
			show_error('No database connection settings were found in the database config file.');
		}

		$dbMaster = $db[$active_group];
		$numSlave = count($db['slave']);
		// neu tong so slave die = tong so slave, set params slave = master
		if($numSlaveDie == $numSlave) 
		{
			$params = $db[$active_group];
		}
		else
		{
			$randNum = ($numSlave > 1) ? rand(0, $numSlave - 1) : 0;
			while(in_array($randNum, $arrSlaveDie))
			{
				$randNum = ($numSlave > 2) ? rand(0, $numSlave - 1) : 0;
			}
			$params = $db['slave'][$randNum];
		}
	}
	elseif (is_string($params))
	{
		/**
		 * Parse the URL from the DSN string
		 * Database settings can be passed as discreet
		 * parameters or as a data source name in the first
		 * parameter. DSNs must have this prototype:
		 * $dsn = 'driver://username:password@hostname/database';
		 */
		if (($dsn = @parse_url($params)) === FALSE)
		{
			show_error('Invalid DB Connection String');
		}

		$params = array(
			'dbdriver'	=> $dsn['scheme'],
			'hostname'	=> isset($dsn['host']) ? rawurldecode($dsn['host']) : '',
			'port'		=> isset($dsn['port']) ? rawurldecode($dsn['port']) : '',
			'username'	=> isset($dsn['user']) ? rawurldecode($dsn['user']) : '',
			'password'	=> isset($dsn['pass']) ? rawurldecode($dsn['pass']) : '',
			'database'	=> isset($dsn['path']) ? rawurldecode(substr($dsn['path'], 1)) : ''
		);

		// Were additional config items set?
		if (isset($dsn['query']))
		{
			parse_str($dsn['query'], $extra);

			foreach ($extra as $key => $val)
			{
				if (is_string($val) && in_array(strtoupper($val), array('TRUE', 'FALSE', 'NULL')))
				{
					$val = var_export($val, TRUE);
				}

				$params[$key] = $val;
			}
		}
	}

	// No DB specified yet? Beat them senseless...
	if (empty($params['dbdriver']))
	{
		show_error('You have not selected a database type to connect to.');
	}

	// Load the DB classes. Note: Since the query builder class is optional
	// we need to dynamically create a class that extends proper parent class
	// based on whether we're using the query builder class or not.
	if ($query_builder_override !== NULL)
	{
		$query_builder = $query_builder_override;
	}
	// Backwards compatibility work-around for keeping the
	// $active_record config variable working. Should be
	// removed in v3.1
	elseif ( ! isset($query_builder) && isset($active_record))
	{
		$query_builder = $active_record;
	}

	require_once(BASEPATH.'database/DB_driver.php');

	if ( ! isset($query_builder) OR $query_builder === TRUE)
	{
		require_once(BASEPATH.'database/DB_query_builder.php');
		if ( ! class_exists('CI_DB', FALSE))
		{
			/**
			 * CI_DB
			 *
			 * Acts as an alias for both CI_DB_driver and CI_DB_query_builder.
			 *
			 * @see	CI_DB_query_builder
			 * @see	CI_DB_driver
			 */
			class CI_DB extends CI_DB_query_builder { }
		}
	}
	elseif ( ! class_exists('CI_DB', FALSE))
	{
		/**
	 	 * @ignore
		 */
		class CI_DB extends CI_DB_driver { }
	}
	if($dbMaster['hostname'] != $params['hostname'])
	{
		// Load the DB driver
		$driver_file = BASEPATH.'database/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_driver.php';
	
		file_exists($driver_file) OR show_error('Invalid DB driver');
		require_once($driver_file);
	
		// Instantiate the DB adapter
		$driver = 'CI_DB_'.$params['dbdriver'].'_driver';
		$DB_SLAVE = new $driver($params);
	
		// Check for a subdriver
		if ( ! empty($DB_SLAVE->subdriver))
		{
			$driver_file = BASEPATH.'database/drivers/'.$DB_SLAVE->dbdriver.'/subdrivers/'.$DB_SLAVE->dbdriver.'_'.$DB_SLAVE->subdriver.'_driver.php';
	
			if (file_exists($driver_file))
			{
				require_once($driver_file);
				$driver = 'CI_DB_'.$DB_SLAVE->dbdriver.'_'.$DB_SLAVE->subdriver.'_driver';
				$DB_SLAVE = new $driver($params);
			}
		}

		$chk = $DB_SLAVE->initialize();
		
		if($chk === FALSE)
		{
			$tmpIndex = 0;
			for($tmpIndex = 0; $tmpIndex < $numSlave; $tmpIndex++)
			{
				if($tmpIndex != $randNum && isset($db['slave'][$tmpIndex]) && !in_array($tmpIndex, $arrSlaveDie))
				{
					$params = $db['slave'][$tmpIndex];
					$DB_SLAVE = new $driver($params);
					$chk = $DB_SLAVE->initialize();
					if($chk)
					{
						break;
					}		
				}
			}
			
			// neu khong connect duoc toi dbslave thi dung info cua master de tao slave
			if($tmpIndex == $numSlave && !$chk)
			{
				if ( ! isset($active_group) OR ! isset($db[$active_group]))
				{
					show_error('You have specified an invalid database connection group.');
				}
				$params = $db[$active_group];
				$DB_SLAVE = new $driver($params);
				$chk = $DB_SLAVE->initialize();
			}
			if(!$chk)
			{
				show_error('Database slave connect error.');
			}
		}
	}
	else
	{
		$_c_ci = & get_instance();
		$DB_SLAVE = $_c_ci->db;
	}
	return $DB_SLAVE;
}



/**
 * Initialize the other database for load other data with low priority, there for return false if not connect
 *
 * @category	Database
 * @author		TOANNH
 * @link		http://codeigniter.com/user_guide/database/
 */
function &DB_OTHER($params, $query_builder_override = NULL)
{
	$DB_OTHER = FALSE;
	if($params ==  '')
	{
		return $DB_OTHER;
	}
	
	// fix for load db by array config
	if(!is_array($params))
	{
		// Load the DB config file if a DSN string wasn't passed
		if (is_string($params) && strpos($params, '://') === FALSE)
		{
			// Is the config file in the environment folder?
			if ( ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
				&& ! file_exists($file_path = APPPATH.'config/database.php'))
			{
				return $DB_OTHER;
			}
	
			include($file_path);
	
			// Make packages contain database config files,
			// given that the controller instance already exists
			if (class_exists('CI_Controller', FALSE))
			{
				foreach (get_instance()->load->get_package_paths() as $path)
				{
					if ($path !== APPPATH)
					{
						if (file_exists($file_path = $path.'config/'.ENVIRONMENT.'/database.php'))
						{
							include($file_path);
						}
						elseif (file_exists($file_path = $path.'config/database.php'))
						{
							include($file_path);
						}
					}
				}
			}
	
			if ( ! isset($db) OR count($db) === 0)
			{
				return $DB_OTHER;
			}
			
			if ( ! isset($db[$params]))
			{
				return $DB_OTHER;
			}
	
			$params = $db[$params];
		}
		elseif (is_string($params))
		{
			/**
			 * Parse the URL from the DSN string
			 * Database settings can be passed as discreet
			 * parameters or as a data source name in the first
			 * parameter. DSNs must have this prototype:
			 * $dsn = 'driver://username:password@hostname/database';
			 */
			if (($dsn = @parse_url($params)) === FALSE)
			{
				return $DB_OTHER;
			}
	
			$params = array(
				'dbdriver'	=> $dsn['scheme'],
				'hostname'	=> isset($dsn['host']) ? rawurldecode($dsn['host']) : '',
				'port'		=> isset($dsn['port']) ? rawurldecode($dsn['port']) : '',
				'username'	=> isset($dsn['user']) ? rawurldecode($dsn['user']) : '',
				'password'	=> isset($dsn['pass']) ? rawurldecode($dsn['pass']) : '',
				'database'	=> isset($dsn['path']) ? rawurldecode(substr($dsn['path'], 1)) : ''
			);
	
			// Were additional config items set?
			if (isset($dsn['query']))
			{
				parse_str($dsn['query'], $extra);
	
				foreach ($extra as $key => $val)
				{
					if (is_string($val) && in_array(strtoupper($val), array('TRUE', 'FALSE', 'NULL')))
					{
						$val = var_export($val, TRUE);
					}
	
					$params[$key] = $val;
				}
			}
		}
	}
	
	// No DB specified yet? Beat them senseless...
	if (empty($params['dbdriver']))
	{
		return $DB_OTHER;
	}
	// Load the DB classes. Note: Since the query builder class is optional
	// we need to dynamically create a class that extends proper parent class
	// based on whether we're using the query builder class or not.
	if ($query_builder_override !== NULL)
	{
		$query_builder = $query_builder_override;
	}
	// Backwards compatibility work-around for keeping the
	// $active_record config variable working. Should be
	// removed in v3.1
	elseif ( ! isset($query_builder) && isset($active_record))
	{
		$query_builder = $active_record;
	}

	require_once(BASEPATH.'database/DB_driver.php');

	if ( ! isset($query_builder) OR $query_builder === TRUE)
	{
		require_once(BASEPATH.'database/DB_query_builder.php');
		if ( ! class_exists('CI_DB', FALSE))
		{
			/**
			 * CI_DB
			 *
			 * Acts as an alias for both CI_DB_driver and CI_DB_query_builder.
			 *
			 * @see	CI_DB_query_builder
			 * @see	CI_DB_driver
			 */
			class CI_DB extends CI_DB_query_builder { }
		}
	}
	elseif ( ! class_exists('CI_DB', FALSE))
	{
		/**
	 	 * @ignore
		 */
		class CI_DB extends CI_DB_driver { }
	}

	// Load the DB driver
	$driver_file = BASEPATH.'database/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_driver.php';

	file_exists($driver_file) OR show_error('Invalid DB driver');
	require_once($driver_file);

	// Instantiate the DB adapter
	$driver = 'CI_DB_'.$params['dbdriver'].'_driver';
	$DB_OTHER = new $driver($params);

	// Check for a subdriver
	if ( ! empty($DB_OTHER->subdriver))
	{
		$driver_file = BASEPATH.'database/drivers/'.$DB_OTHER->dbdriver.'/subdrivers/'.$DB_OTHER->dbdriver.'_'.$DB_OTHER->subdriver.'_driver.php';

		if (file_exists($driver_file))
		{
			require_once($driver_file);
			$driver = 'CI_DB_'.$DB_OTHER->dbdriver.'_'.$DB_OTHER->subdriver.'_driver';
			$DB_OTHER = new $driver($params);
		}
	}

	$chk = $DB_OTHER->initialize();
	if (!$chk)
	{
		$DB_OTHER = FALSE;
	}
	return $DB_OTHER;
}