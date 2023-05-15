<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myencrypt
{
	private static $code = "ITuCOrzlYbgAeBq7x4R31Fy-jiU8VDhH2mfJ5WwKPEk0GpZ9LaQs6X_NovtncdMS";
	
	private static $adm_code = "1Fji0COrR39KJ5NBITuly-zoAtncdMSeGpZvUPEkfWwYbg8VQs6X_DahH2Lmq7x4"; // ko duoc thay doi
	
	private static $payment_code = "Se1Fy-ji0GpZCOovBqH2m9LUfWwKJ5N7x4R3IPEkTuAtncdMYbg8VDaQs6X_hrzl";// for payment visa debit
	
	private static function _shiftLeft($pos, $shiftStep)
	{
		$charCodeCount = strlen(self::$code);

		if (($pos + $shiftStep) > ($charCodeCount - 1))
		{
			$pos = ($pos + $shiftStep) - $charCodeCount;
		}
		else
		{
			$pos = $pos + $shiftStep;
		}
		return $pos;
	}

	private static function _shiftRight($pos, $shiftStep)
	{
		$charCodeCount = strlen(self::$code);
		if ($pos - $shiftStep < 0)
		{
			$pos = $charCodeCount - ($shiftStep - $pos);
		}
		else
		{
			$pos = $pos - $shiftStep;
		}
		return $pos;
	}
	
	
	public static function Encode($inputID)
	{
		$output = "";
        $id = $inputID;
        $shift = $inputID%64;
        $quotient = $id;
		do
		{
			$id       = $quotient % 64;
			$quotient = $quotient >> 6; // divide by 64
			$output   = self::$code[$id] . $output;
		}
		while ($quotient>0);
		return $output;
	}
    public static function Decode($inputID)
	{
        $output = 0;
        $len = strlen($inputID);
        $i;
		for ($i=0; $i<$len; $i++)
		{
            $value = strpos(self::$code, $inputID[$i]);
			$output += $value*pow(64,$len-$i-1);
		}
		return $output;
	}
	
	public static function Encrypt($uid, $isLogin, $codeIndex = 1)
	{
		$datetime = date('Y-m-d H:i:s');
		$time = strtotime($datetime);
		$str = $uid . ',' . $isLogin . ',' . $datetime;
		$shift = $time%64;
		$charlinkcode = '';
		switch($codeIndex)
		{
			case 0:
				$charlinkcode = self::$code;
				break;
			case 1:
				$charlinkcode = self::$adm_code;
				break;
			case 2:
				$charlinkcode = self::$payment_code;
				break;
			default:
				$charlinkcode = self::$code;
				break;
		}
		$strLinkLen = strlen($str);
		$i = 0;
		$result = '';
		for ($i = 0; $i < $strLinkLen; $i++)
		{
			$asciiPos = ord($str[$i]);
			$pos = ($asciiPos % 64);
			$result .= $charlinkcode[self::_shiftLeft($pos, $shift, $charlinkcode)];
			
		}
		return $charlinkcode[$shift] . $result;
	}
	
	public static function Decrypt($str, $codeIndex = 1)
	{
		$result = "";
		$charlinkcode = '';
		switch($codeIndex)
		{
			case 0:
				$charlinkcode = self::$code;
				break;
			case 1:
				$charlinkcode = self::$adm_code;
				break;
			case 2:
				$charlinkcode = self::$payment_code;
				break;
			default:
				$charlinkcode = self::$code;
				break;
		}
		$shift = strpos($charlinkcode, $str[0]);
		$strLinkLen = strlen($str);
		$i = 0;
		while ($i < $strLinkLen)
		{
			$subCode = substr($str, $i, 1);
			$pos = strpos($charlinkcode, $subCode);
			$result .= chr(self::_shiftRight($pos, $shift, $charlinkcode));
			$i += 1;
		}
		return $result;
	}
	
	public static function validLogin($str, $codeIndex = 1, $numMinute = 30)
	{
		$data = array();
		$data['uid'] = 0;
		$data['islogin'] = 0;
		$charlinkcode = '';
		switch($codeIndex)
		{
			case 0:
				$charlinkcode = self::$code;
				break;
			case 1:
				$charlinkcode = self::$adm_code;
				break;
			default:
				$charlinkcode = self::$code;
				break;
		}
		
		
		$shift = strpos($charlinkcode, $str[0]);
		$str = self::Decrypt($str, $codeIndex);
		$arr = explode(',', $str);
		if(isset($arr[0]) && isset($arr[1]) && isset($arr[2]) && count($arr) == 3)
		{
			$time = strtotime(trim($arr[2]));
			$arr[0] = trim($arr[0]);
			$arr[1] = trim($arr[1]);
			$arr[2] = trim($arr[2]);
			if(is_numeric($arr[0]) && $arr[0] > 0 && ($arr[1] != '0' || $arr[1] != '1') && $time !== -1 && $time !== false)
			{
				$currTime = strtotime(date('Y-m-d H:i:s'));
				// check thoi gian request voi thoi gian hien tai ko qua 45p
				$chkMinute = $numMinute * 60;
				if($currTime - $time <= $chkMinute)
				{
					if(($time%64) == $shift)
					{
						$data['islogin'] = 1;
						$data['uid'] = $arr[0];
					}
				}
			}
		}
		
		return $data;
	}
}