<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PasswordHash
{
	/*
    * hash user password
    * username: username 
    * password: md5 password
    */
    public static function hash($username, $pass, $isMd5Pass = true)
    {
    	$username = mb_strtolower(trim($username));
    	$username = md5($username);
    	$pass = $isMd5Pass ? $pass : md5($pass);
    	$salt = '';
    	for($i = 0; $i < 32; $i++)
    	{
    		if($i%2 == 0)
    		{
    			$salt .= $username[$i];
    		}
    	}
    	
    	$options = [
    		'cost' => 10
		];
		$newPass = md5($pass.$salt);
		
		return password_hash($newPass, PASSWORD_BCRYPT, $options);
    }
    
    public static function hash_verify($username, $hash_pass, $pass, $isMd5Pass = true) 
    {
    	$username = mb_strtolower(trim($username));
    	$username = md5($username);
    	$pass = $isMd5Pass ? $pass : md5($pass);
    	
    	$salt = '';
    	for($i = 0; $i < 32; $i++)
    	{
    		if($i%2 == 0)
    		{
    			$salt .= $username[$i];
    		}
    	}
    	
    	$options = [
    		'cost' => 10
		];
		$newPass = md5($pass.$salt);
		return password_verify($newPass, $hash_pass);
    }
 }