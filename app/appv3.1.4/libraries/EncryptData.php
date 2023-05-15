<?php
class EncryptData
{
	private static $_key = ENCRYPT_DATA_PRIVATE_KEY;
	
	public static function Encode($str_encrypt, $private_key = '')
	{
		$private_key = $private_key == '' ? self::$_key : $private_key;
    	$iv = strrev($private_key);
		$encrypt_key = hash_hmac('sha256', $private_key, $iv);
		
		$encrypted_str = openssl_encrypt($str_encrypt , 'AES-256-CBC', $encrypt_key, OPENSSL_RAW_DATA, $iv); 
		$encrypted_str = base64_encode($encrypted_str);
		$encrypted_str = str_replace(array('+', '/'), array('-', '_'), $encrypted_str);
		return $encrypted_str;
	}

	public static function Decode($str_encrypted, $private_key = '')
	{
		$private_key = $private_key == '' ? self::$_key : $private_key;
		$iv = strrev($private_key);
		$encrypt_key = hash_hmac('sha256', $private_key, $iv);
		$decrypted_str = str_replace(array('-', '_'), array('+', '/'), $str_encrypted);
		$decrypted_str = base64_decode($decrypted_str);
		$decrypted_str = openssl_decrypt($decrypted_str, 'AES-256-CBC', $encrypt_key, OPENSSL_RAW_DATA, $iv);
		return $decrypted_str;
	}
    
    // for create websocket token request
	public static function Websocket_token_create($userid)
	{
		$data = [
			'domain' => DOMAIN_NAME,
			'uid' => $userid,
			'ctt' => time() //current timestamp
		];
		
		$str = json_encode($data);
		
		return self::Encode($str, self::$_key);
	}
}