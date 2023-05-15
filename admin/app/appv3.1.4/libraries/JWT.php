<?php
/*
* Support PHP version >= 5.5
* Support algorithm: HS256, HS384, HS512
*/
class JWT
{
	public static function ApiToken($domain, $key, $JSON_UNESCAPED_UNICODE_MODE = FALSE)
	{
		$algo = 'HS512';
		$payLoadData = array(
			'domain'=>$domain,
			'ctt'=>date('Y-m-d H:i:s')
		);
		$type = 'JWT';
		return self::Encode($payLoadData, $algo, $key='', $type, $JSON_UNESCAPED_UNICODE_MODE);
	}
	
	public static function Encode($payloadData, $algo = 'HS256', $key, $type = 'JWT', $JSON_UNESCAPED_UNICODE_MODE = FALSE)
	{
		$algo = strtoupper($algo);
		$header = array('alg'=>$algo, 'typ'=>$type);
		$payloadData = $JSON_UNESCAPED_UNICODE_MODE ? self::_base64Encode(json_encode($payloadData, JSON_UNESCAPED_UNICODE)) : self::_base64Encode(json_encode($payloadData));

		$sections = array(
			($JSON_UNESCAPED_UNICODE_MODE ? self::_base64Encode(json_encode($header, JSON_UNESCAPED_UNICODE)) : self::_base64Encode(json_encode($header))),
			$payloadData
		);

		$signWith = implode('.', $sections);
		$signature = self::_sign(
			$signWith,
			$key,
			$algo
		);

		if ($signature !== null) {
			$sections[] = self::_base64Encode($signature);
		}
		$result = implode('.', $sections);
		return $result;
	}

	public static function Decode($data, $key, $numSecondExpire = 300, $return_array = true)
	{
		$sections = explode('.', $data);
		if (count($sections) < 3) {
			return FALSE;
		}

		list($header, $payload, $signature) = $sections;
		$header = json_decode(self::_base64Decode($header), true);
		$signature = self::_base64Decode($signature);
		$payload_verify = json_decode(self::_base64Decode($payload), true);
		if (self::_verify($key, $header, $payload_verify, $signature, $numSecondExpire) === false)
		{
			return FALSE;
		}

		$payload = json_decode(self::_base64Decode($payload), $return_array);
		return $payload;
	}
	
	private static function _verify($key, $header, $payload, $signature, $numSecondExpire)
	{
		// $payload['ctt'] = custom param. This is created time to create encode jwt. Fomat: yyyy-mm-dd hh:ii:ss 
		// sample: 2017-06-06 15:22:10
		if(empty($header) || empty($header['alg']) || empty($payload['ctt']))
		{
			return FALSE;
		}
		
		// check expired time
		if($numSecondExpire > 0)
		{
			$chkTime = strtotime($payload['ctt']);
			if($chkTime === FALSE || $chkTime === -1)
			{
				return FALSE;
			}
			else
			{
				if(($chkTime + $numSecondExpire) < time())
				{
					return FALSE;
				}
			}
		}
		
		/*
		// If "expires at" defined, check against time
		if (isset($payload['exp']) && $payload['exp'] <= time()) {
			return FALSE;
		}
		*/

		// If a "not before" is provided, validate the time
		if (isset($payload['nbf']) && $payload['nbf'] > time()) {
			return FALSE;
		}

		$algorithm = $header['alg'];

		$rel = FALSE;

		if(!$rel)
		{
			$signWith = implode('.', array(
				self::_base64Encode(json_encode($header)),
				self::_base64Encode(json_encode($payload))
			));

			$rel = self::_hash_equals(self::_sign($signWith, $key, $algorithm), $signature);
		}

		// check json with JSON_UNESCAPED_UNICODE option added
		if(!$rel)
		{
			$signWith = implode('.', array(
				self::_base64Encode(json_encode($header, JSON_UNESCAPED_UNICODE)),
				self::_base64Encode(json_encode($payload, JSON_UNESCAPED_UNICODE))
			));

			$rel = self::_hash_equals(self::_sign($signWith, $key, $algorithm), $signature);
		}

		return $rel;
	}

	/**
	 * Base64 encode data and prepare for the URL
	 * 	NOTE: The "=" is removed as it's just padding in base64
	 *  and not needed.
	 *
	 * @param string $data Data string
	 * @return string Formatted data
	 */
	private static function _base64Encode($data)
	{
		return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
	}

	/**
	 * Base64 decode (and url decode) the given data
	 *
	 * @param string $data Data to decode
	 * @return string Decoded data
	 */
	private static function _base64Decode($data)
	{
		$remainder = strlen($data) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($data, '-_', '+/'));
	}

	/**
	 * Generate the signature with the given data, key and algorithm
	 *
	 * @param string $signWith Data to sign hash with
	 * @param string $key Key for signing
	 * @return string Generated signature hash
	 */
	private static function _sign($signWith, $key, $algo)
	{
		$hashAlgorithm = self::_getAlgorithm($algo);
		if ($hashAlgorithm == '') {
			return null;
		}
		
		if ($hashAlgorithm == 'SHA256' || $hashAlgorithm == 'SHA384' || $hashAlgorithm == 'SHA512') {
			$signature = hash_hmac(
				$hashAlgorithm,
				$signWith,
				$key,
				true
			);
		}
		else
		{
			return null;
		}
		
		if ($signature === false) {
			return null;
		}

		return $signature;
	}
	
	private static function _getAlgorithmKeyType($jwtAlgo)
	{
		$algoType = '';
		switch($jwtAlgo)
		{
			case 'ES256':
				$algoType = OPENSSL_KEYTYPE_RSA;
				break;
			case 'ES384':
				$algoType = OPENSSL_KEYTYPE_RSA;
				break;
			case 'ES512':
				$algoType = OPENSSL_KEYTYPE_RSA;
				break;
			case 'HS256':
				$algoType = 'HMAC';
				break;
			case 'HS384':
				$algoType = 'HMAC';
				break;
			case 'HS512':
				$algoType = 'HMAC';
				break;
			case 'RS256':
				$algoType = OPENSSL_KEYTYPE_RSA;
				break;
			case 'RS384':
				$algoType = OPENSSL_KEYTYPE_RSA;
				break;
			case 'RS512':
				$algoType = OPENSSL_KEYTYPE_RSA;
				break;
		}
		
		return $algoType;
	}
	
	private static function _getAlgorithm($jwtAlgo)
	{
		$algo = '';
		switch($jwtAlgo)
		{
			case 'ES256':
				$algo = OPENSSL_ALGO_SHA256;
				break;
			case 'ES384':
				$algo = OPENSSL_ALGO_SHA384;
				break;
			case 'ES512':
				$algo = OPENSSL_ALGO_SHA512;
				break;
			case 'HS256':
				$algo = 'SHA256';
				break;
			case 'HS384':
				$algo = 'SHA384';
				break;
			case 'HS512':
				$algo = 'SHA512';
				break;
			case 'RS256':
				$algo = OPENSSL_ALGO_SHA256;
				break;
			case 'RS384':
				$algo = OPENSSL_ALGO_SHA384;
				break;
			case 'RS512':
				$algo = OPENSSL_ALGO_SHA512;
				break;
		}
		
		return $algo;
	}

	/**
     * Polyfill PHP 5.6.0's hash_equals() feature
     */
    private static function _hash_equals($a, $b)
    {
        if (\function_exists('hash_equals')) {
            return \hash_equals($a, $b);
        }
        if (\strlen($a) !== \strlen($b)) {
            return false;
        }
        $res = 0;
        $len = \strlen($a);
        for ($i = 0; $i < $len; ++$i) {
            $res |= \ord($a[$i]) ^ \ord($b[$i]);
        }
        return $res === 0;
	}
}