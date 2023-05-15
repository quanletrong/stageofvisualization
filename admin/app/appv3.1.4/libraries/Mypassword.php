<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mypassword
{
	public function strength($password){
		$checkLengthPassWord = $this->checkLengthPassWord($password); //max 3
		$checkLowerPassword = $this->checkLowerPassword($password); //max 2
		$checkUpperPassword = $this->checkUpperPassword($password);//max 2
		$checkNumberPassword = $this->checkNumberPassword($password);//max 2
		$strength = 0;
		$numStreng = $checkLengthPassWord + $checkLowerPassword + $checkUpperPassword + $checkNumberPassword;
//        var_dump($checkLengthPassWord , $checkLowerPassword , $checkUpperPassword , $checkNumberPassword);
		if($checkLengthPassWord == 0 || $checkLowerPassword == 0 || $checkUpperPassword == 0 || $checkNumberPassword == 0)
		{
			if($numStreng <= 2){
				$strength = 1;
			}elseif($numStreng <= 4){
				$strength = 2;
			}else{
				$strength = 3;
			}
		}else
		{
			if($numStreng <= 7){
				$strength = 4;
			}else{
				$strength = 5;
			}
		}

		return $strength;
	}

	private function checkLengthPassWord($password){
		$length = strlen($password);
		if($length < 9)
		{
			return 0;
		}
		// check string length is 8 -15 chars
		if($length >= 9 && $length <= 15)
		{
			return 1;
		}

		// check if lenth is 16 - 35 chars
		if($length >= 16 && $length <=35)
		{
			return 2;
		}

		// check if length greater than 35 chars
		if($length > 35)
		{
			return 3;
		}

	}

	private function checkUpperPassword($password){
		// check upper char
		preg_match_all('/[A-Z]/', $password, $upperChar);
		$num_unique_upper_char = sizeof(array_unique($upperChar[0]));
		if($num_unique_upper_char > 0)
		{
			if($num_unique_upper_char <= 3)
			{
				return 1;
			}
			else
			{
				return 2;
			}
		}
		else
		{
			return 0;
		}
	}

	private function checkLowerPassword($password){
		// check lower char
		preg_match_all('/[a-z]/', $password, $lowerChar);
		$num_unique_lower_char = sizeof(array_unique($lowerChar[0]));
		if($num_unique_lower_char > 0)
		{
			if($num_unique_lower_char <= 4)
			{
				return 1;
			}
			else
			{
				return 2;
			}
		}
		else
		{
			return 0;
		}
	}

	private function checkNumberPassword($password){
		// check number
		preg_match_all('/[0-9]/', $password, $numbers);
		$num_unique_number_char = sizeof(array_unique($numbers[0]));
		if($num_unique_number_char > 0)
		{
			if($num_unique_number_char <= 2)
			{
				return 1;
			}
			else
			{
				return 2;
			}
		}
		else
		{
			return 0;
		}
	}
}