<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myencryptdecrypt
{
    var $skey = "fc33b54611cacb"; // not change // not change
	/*
	* @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
	* @author  Simon Franz
	* @author  Deadfish
	* @author  SK83RJOSH
	* @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	* @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
	* @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
	* @link    http://kevin.vanzonneveld.net/
	*
	* @param mixed   $in   String or long input to translate
	* @param boolean $to_num  Reverses translation when true
	* @param mixed   $pad_up  Number or boolean padds the result up to a specified length
	* @param string  $pass_key Supplying a password makes it harder to calculate the original ID
	*
	* @return mixed string or long
	*/
	private function _alphaID($in, $to_num = false, $pad_up = false, $pass_key = null)
	{
		$index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$base  = strlen($index);
	
		if ($pass_key !== null) {
			// Although this function's purpose is to just make the
			// ID short - and not so much secure,
			// with this patch by Simon Franz (http://blog.snaky.org/)
			// you can optionally supply a password to make it harder
			// to calculate the corresponding numeric ID
	
			for ($n = 0; $n < strlen($index); $n++) {
				$i[] = substr($index, $n, 1);
			}
	
			$pass_hash = hash('sha256',$pass_key);
			$pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);
	
			for ($n = 0; $n < strlen($index); $n++) {
				$p[] =  substr($pass_hash, $n, 1);
			}
	
			array_multisort($p, SORT_DESC, $i);
			$index = implode($i);
		}
	
		if ($to_num) {
	        $out   = 0;
	        // Digital number  <<--  alphabet letter code
			$len = strlen($in) - 1;
	
			for ($t = $len; $t >= 0; $t--) {
				$bcp = pow($base, $len - $t);
				$out = $out + strpos($index, substr($in, $t, 1)) * $bcp;
			}
	
			if (is_numeric($pad_up)) {
				$pad_up--;
	
				if ($pad_up > 0) {
					$out -= pow($base, $pad_up);
				}
			}
		} else {
	        $out   = '';
	        // Digital number  -->>  alphabet letter code
			if (is_numeric($pad_up)) {
				$pad_up--;
	
				if ($pad_up > 0) {
					$in += pow($base, $pad_up);
				}
			}
	
			for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
				$bcp = pow($base, $t);
				$a   = floor($in / $bcp) % $base;
				$out = $out . substr($index, $a, 1);
				$in  = $in - ($a * $bcp);
			}
		}
	
		return $out;
	}
	
    public  function encode($value)
	{
		return $this->_alphaID($value, false, false, $this->skey);
    }

    public function decode($value)
	{
        return $this->_alphaID($value, true, false, $this->skey);
    }
}