<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myvietstring {

    private $charMap = array(
		"From"=>array("à","ả","ã","á","ạ","ă","ằ","ẳ","ẵ","ắ","ặ","â","ầ","ẩ","ẫ","ấ","ậ","đ","è","ẻ","ẽ","é","ẹ","ê","ề","ể","ễ","ế","ệ","ì","ỉ","ĩ","í","ị","ò","ỏ","õ","ó","ọ","ô","ồ","ổ","ỗ","ố","ộ","ơ","ờ","ở","ỡ","ớ","ợ","ù","ủ","ũ","ú","ụ","ư","ừ","ử","ữ","ứ","ự","ỳ","ỷ","ỹ","ý","ỵ","À","Ả","Ã","Á","Ạ","Ă","Ằ","Ẳ","Ẵ","Ắ","Ặ","Â","Ầ","Ẩ","Ẫ","Ấ","Ậ","Đ","È","Ẻ","Ẽ","É","Ẹ","Ê","Ề","Ể","Ễ","Ế","Ệ","Ì","Ỉ","Ĩ","Í","Ị","Ò","Ỏ","Õ","Ó","Ọ","Ô","Ồ","Ổ","Ỗ","Ố","Ộ","Ơ","Ờ","Ở","Ỡ","Ớ","Ợ","Ù","Ủ","Ũ","Ú","Ụ","Ư","Ừ","Ử","Ữ","Ứ","Ự","Ỳ","Ỷ","Ỹ","Ý","Ỵ"),
		"To"  =>array("a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","d","e","e","e","e","e","e","e","e","e","e","e","i","i","i","i","i","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","u","u","u","u","u","u","u","u","u","u","u","y","y","y","y","y","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","D","E","E","E","E","E","E","E","E","E","E","E","I","I","I","I","I","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","U","U","U","U","U","U","U","U","U","U","U","Y","Y","Y","Y","Y")
	);
	
	public function removeInvalidCharacter($str)
	{
		$patterns[] = '/(,)/';
		$patterns[] = '/(\.)/';
		$patterns[] = '/(?)/';
		$patterns[] = '/(:)/';
		$patterns[] = "/(:|'|`|!|@|#|\\$|%|\^|\&|\*|\(|\)|\+|=|}|{|\\\|\/)/";
		$patterns[] = '/"/';
		$patterns[] = '/\|/';
		$patterns[] = '/(~)/';
		$str = preg_replace($patterns, '', $str);
		$str = preg_replace('!\s+!', ' ', $str);
		$str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
		return $str;
	}
	
	public function remove_html_tags( $text )
	{
		// PHP's strip_tags() function will remove tags, but it
		// doesn't remove scripts, styles, and other unwanted
		// invisible text between tags.  Also, as a prelude to
		// tokenizing the text, we need to insure that when
		// block-level tags (such as <p> or <div>) are removed,
		// neighboring words aren't joined.
		$text = preg_replace(
			array(
				// Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
	
				// Add line breaks before & after blocks
				'@<((br)|(hr))@iu',
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
				"\n\$0", "\n\$0",
			),
			$text );
	
		// Remove all remaining tags and comments and return.
		return strip_tags($text);
	}
	
	public function removeVietChars($str)
	{
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|y|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Y|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		//$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
		return $str;
	}
	
	public function vietChars2Lower($str)
	{
		$patterns = array();
		//a
		$patterns[] = '/(à|À)/';
		$patterns[] = '/(á|Á)/';
		$patterns[] = '/(ạ|Ạ)/';
		$patterns[] = '/(ả|Ả)/';
		$patterns[] = '/(ã|Ã)/';
		
		//â
		$patterns[] = '/(â|Â)/';
		$patterns[] = '/(ầ|Ầ)/';
		$patterns[] = '/(ấ|Ấ)/';
		$patterns[] = '/(ậ|Ậ)/';
		$patterns[] = '/(ẩ|Ẩ)/';
		$patterns[] = '/(ẫ|Ẫ)/';
		
		//ă
		$patterns[] = '/(ă|Ă)/';
		$patterns[] = '/(ằ|Ằ)/';
		$patterns[] = '/(ắ|Ắ)/';
		$patterns[] = '/(ặ|Ặ)/';
		$patterns[] = '/(ẳ|Ẳ)/';
		$patterns[] = '/(ẵ|Ẵ)/';
		
		//e
		$patterns[] = '/(è|È)/';
		$patterns[] = '/(é|É)/';
		$patterns[] = '/(ẹ|Ẹ)/';
		$patterns[] = '/(ẻ|Ẻ)/';
		$patterns[] = '/(ẽ|Ẽ)/';
		
		//ê
		$patterns[] = '/(ê|Ê)/';
		$patterns[] = '/(ề|Ề)/';
		$patterns[] = '/(ế|Ế)/';
		$patterns[] = '/(ệ|Ệ)/';
		$patterns[] = '/(ể|Ể)/';
		$patterns[] = '/(ễ|Ễ)/';
		
		//i
		$patterns[] = '/(ì|Ì)/';
		$patterns[] = '/(í|Í)/';
		$patterns[] = '/(ị|Ị)/';
		$patterns[] = '/(ỉ|Ỉ)/';
		$patterns[] = '/(ĩ|Ĩ)/';
		
		//o
		$patterns[] = '/(ò|Ò)/';
		$patterns[] = '/(ó|Ó)/';
		$patterns[] = '/(ọ|Ọ)/';
		$patterns[] = '/(ỏ|Ỏ)/';
		$patterns[] = '/(õ|Õ)/';
		
		//ô
		$patterns[] = '/(ô|Ô)/';
		$patterns[] = '/(ồ|Ồ)/';
		$patterns[] = '/(ố|Ố)/';
		$patterns[] = '/(ộ|Ộ)/';
		$patterns[] = '/(ổ|Ổ)/';
		$patterns[] = '/(ỗ|Ỗ)/';
		
		//ơ
		$patterns[] = '/(ơ|Ơ)/';
		$patterns[] = '/(ờ|Ờ)/';
		$patterns[] = '/(ớ|Ớ)/';
		$patterns[] = '/(ợ|Ợ)/';
		$patterns[] = '/(ở|Ở)/';
		$patterns[] = '/(ỡ|Ỡ)/';

		//u
		$patterns[] = '/(ù|Ù)/';
		$patterns[] = '/(ú|Ú)/';
		$patterns[] = '/(ụ|Ụ)/';
		$patterns[] = '/(ủ|Ủ)/';
		$patterns[] = '/(ũ|Ũ)/';
				
		//ư
		$patterns[] = '/(ư|Ư)/';
		$patterns[] = '/(ừ|Ừ)/';
		$patterns[] = '/(ứ|Ứ)/';
		$patterns[] = '/(ự|Ự)/';
		$patterns[] = '/(ử|Ử)/';
		$patterns[] = '/(ữ|Ữ)/';
		
		//y
		$patterns[] = '/(ỳ|Ỳ)/';
		$patterns[] = '/(ý|Ý)/';
		$patterns[] = '/(ỵ|Ỵ)/';
		$patterns[] = '/(ỷ|Ỷ)/';
		$patterns[] = '/(ỹ|Ỹ)/';		
		
		//đ
		$patterns[] = '/(đ|Đ)/';
		
		// Lower
		$replacements = array();
		//a
		$replacements[] = 'à';
		$replacements[] = 'á';
		$replacements[] = 'ạ';
		$replacements[] = 'ả';
		$replacements[] = 'ã';
		
		//â
		$replacements[] = 'â';
		$replacements[] = 'ầ';
		$replacements[] = 'ấ';
		$replacements[] = 'ậ';
		$replacements[] = 'ẩ';
		$replacements[] = 'ẫ';
		
		//ă
		$replacements[] = 'ă';
		$replacements[] = 'ằ';
		$replacements[] = 'ắ';
		$replacements[] = 'ặ';
		$replacements[] = 'ẳ';
		$replacements[] = 'ẵ';
		
		//e
		$replacements[] = 'è';
		$replacements[] = 'é';
		$replacements[] = 'ẹ';
		$replacements[] = 'ẻ';
		$replacements[] = 'ẽ';
		
		//ê
		$replacements[] = 'ê';
		$replacements[] = 'ề';
		$replacements[] = 'ế';
		$replacements[] = 'ệ';
		$replacements[] = 'ể';
		$replacements[] = 'ễ';
		
		//i
		$replacements[] = 'ì';
		$replacements[] = 'í';
		$replacements[] = 'ị';
		$replacements[] = 'ỉ';
		$replacements[] = 'ĩ';
		
		//o
		$replacements[] = 'ò';
		$replacements[] = 'ó';
		$replacements[] = 'ọ';
		$replacements[] = 'ỏ';
		$replacements[] = 'õ';
		
		//ô
		$replacements[] = 'ô';
		$replacements[] = 'ồ';
		$replacements[] = 'ố';
		$replacements[] = 'ộ';
		$replacements[] = 'ổ';
		$replacements[] = 'ỗ';

		//ơ
		$replacements[] = 'ơ';
		$replacements[] = 'ờ';
		$replacements[] = 'ớ';
		$replacements[] = 'ợ';
		$replacements[] = 'ở';
		$replacements[] = 'ỡ';
		
		//u
		$replacements[] = 'ù';
		$replacements[] = 'ú';
		$replacements[] = 'ụ';
		$replacements[] = 'ủ';
		$replacements[] = 'ũ';
		
		//ư
		$replacements[] = 'ư';
		$replacements[] = 'ừ';
		$replacements[] = 'ứ';
		$replacements[] = 'ự';
		$replacements[] = 'ử';
		$replacements[] = 'ữ';
		
		//y
		$replacements[] = 'ỳ';
		$replacements[] = 'ý';
		$replacements[] = 'ỵ';
		$replacements[] = 'ỷ';
		$replacements[] = 'ỹ';
		
		//đ
		$replacements[] = 'đ';
		
		$patterns2 = array(	
							'/A/',
							'/B/',
							'/C/',
							'/D/',
							'/E/',
							'/F/',
							'/G/',
							'/H/',
							'/I/',
							'/J/',
							'/K/',
							'/L/',
							'/M/',
							'/N/',
							'/O/',
							'/P/',
							'/Q/',
							'/R/',
							'/S/',
							'/T/',
							'/U/',
							'/V/',
							'/W/',
							'/X/',
							'/Y/',
							'/Z/'
						);
		$replacements2 = array(	
								'a',
								'b',
								'c',
								'd',
								'e',
								'f',
								'g',
								'h',
								'i',
								'j',
								'k',
								'l',
								'm',
								'n',
								'o',
								'p',
								'q',
								'r',
								's',
								't',
								'u',
								'v',
								'w',
								'x',
								'y',
								'z'
							);
				
		$str = preg_replace($patterns, $replacements, $str);
		$str = preg_replace($patterns2, $replacements2, $str);
		return $str;
	}

	public function vietSubStr($str, $numLimit)
	{
		$arrVietChar = array(
								'à','á','ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
								'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
								'ì', 'í', 'ị', 'ỉ', 'ĩ',
								'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
								'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
								'ỳ', 'ý', 'y', 'ỷ', 'ỹ',
								'đ',
								'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
								'È','É', 'Ẹ','Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
								'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
								'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
								'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
								'Ỳ', 'Ý', 'Y', 'Ỷ', 'Ỹ',
								'Đ'
		);
		
		
	}
	
	public function getTitle($str, $ext = ".html")
	{
		$str = htmlspecialchars_decode(html_entity_decode($str, ENT_QUOTES, "UTF-8"), ENT_QUOTES);
		$str = trim($str);
		$str = mb_strtolower($str, "UTF-8");
		$str = str_replace(array("&nbsp;", "\0", "\0x00"), "", $str);
		$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
		$str = $this->remove_html_tags($str);
		//$str = strip_tags($str);
		$str = $this->removeInvalidCharacter($str);
		$str = $this->removeVietChars($str);
		$str = trim(strtolower($str));
		
		$len = strlen($str);
		// ascii code: a = 97 -> z = 122, "-" = 45, "_" = 95, 0 = 48, 9= 57
		$validStr = "";
		for($i=0; $i < $len; $i++)
		{
			$asciiCode = ord($str[$i]);
			if($asciiCode == 45 || $asciiCode == 95 || ($asciiCode >= 97 && $asciiCode <= 122) || ($asciiCode >= 48 && $asciiCode <= 57))
			{
				$validStr .= $str[$i];
			}
		}
		return $validStr . $ext;
	}
}
?>