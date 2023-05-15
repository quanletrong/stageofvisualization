<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}


// define common function
function sendsms($data)
{
    if (SENDSMS)
    {
        $url = SMS_URL;
        $number = $data['number'];
        $content = $data['content'];
        $myvars = '{"username":"' . SMS_ACCOUNT . '","password":"' . SMS_PASS . '","message":"' . $content . '","brandname":"' . SMS_BRAND_NAME . '","recipients":[{"message_id":"a0","number":"' . $number . '"}]}';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        curl_close($ch);
        return TRUE;
    }
    return FALSE;
}

function getDomainFromUrl($strUrl)
{
    $parse = parse_url($strUrl);
    $domain = isset($parse['host']) ? $parse['host'] : '';
    $domain = mb_strtolower($domain, 'UTF-8');
    if (preg_match('/^www\./i', $domain))
    {
        $domain = substr($domain, 4);
    }
    return $domain;
}

function trimTitle($title, $limit = 20)
{
    $newTitle = '';
    if (mb_strlen($title) > $limit)
    {
        $newTitle = mb_substr($title, 0, $limit) . '...';
    }
    else
    {
        $newTitle = $title;
    }
    return $newTitle;
}


function isIdNumber($str)
{
    $len = strlen($str);
    if ($len >= 1)
    {
        if (preg_match('/^[1-9][0-9]*$/', $str))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    else
    {
        if (preg_match('/^[0-9]+$/', $str))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}

function show_custom_error($mess = '')
{
    $CI =& get_instance();
    $langcode = get_langcode();
    if (class_exists('CI_DB') AND isset($CI->db))
    {
        $CI->db->close();
    }

    if (class_exists('CI_DB') AND isset($CI->db_slave))
    {
        $CI->db_slave->close();
    }

    if (class_exists('CI_DB') AND isset($CI->db_other))
    {
        $CI->db_other->close();
    }

    $showCustomError = $CI->config->item('show_custom_error');
    if ($showCustomError)
    {
        die($mess);
    }
    else
    {
        ?>
        <script type="text/javascript">window.location = '/upgrade';</script>
        <?php
        die();
    }
}

function getCurrentUrl()
{
    return HTTP_PROTOCOL . '://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
}

function isUrl($url)
{
    return (preg_match('/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i',
                       $url)) ? TRUE : FALSE;
}

function removeAllTags($text)
{
    $text = rawurldecode($text);
    $text = htmlspecialchars_decode(html_entity_decode($text, ENT_QUOTES | ENT_IGNORE, "UTF-8"),
                                    ENT_QUOTES | ENT_IGNORE);
    // remove invalid charset code
    $regex = '/( [\x00-\x7F] | [\xC0-\xDF][\x80-\xBF] | [\xE0-\xEF][\x80-\xBF]{2} | [\xF0-\xF7][\x80-\xBF]{3} ) | ./x';
    $text = preg_replace($regex, '$1', $text);
    
    $text = trim($text);
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
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
            "\n\$0",
        ),
        $text);
	// remove emotion
	$text = preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    // Remove all remaining tags and comments and return.
    return strip_tags($text);
}

function getSaveSqlStr($str)
{
	return $str;
}

function myEscapeStr($str)
{
    $str = trim(removeAllTags($str));
    return $str;
}

function str_valid_phone($strNumber)
{
    $strNumber = trim($strNumber);
    $chk = FALSE;
    $len = strlen($strNumber);
    if ((
            ($len == 10 && substr($strNumber, 0, 2) == '09') ||
            ($len == 10 && substr($strNumber, 0, 3) == '088') ||
            ($len == 10 && substr($strNumber, 0, 3) == '086') ||
            ($len == 10 && substr($strNumber, 0, 3) == '089') ||
            //($len == 10 && substr($strNumber, 0, 3) == '061') ||
            //($len == 11 && substr($strNumber, 0, 2) == '01') ||
			($len == 10 && substr($strNumber, 0, 3) == '032') || 
			($len == 10 && substr($strNumber, 0, 3) == '033') || 
			($len == 10 && substr($strNumber, 0, 3) == '034') || 
			($len == 10 && substr($strNumber, 0, 3) == '035') || 
			($len == 10 && substr($strNumber, 0, 3) == '036') || 
			($len == 10 && substr($strNumber, 0, 3) == '037') || 
			($len == 10 && substr($strNumber, 0, 3) == '038') || 
			($len == 10 && substr($strNumber, 0, 3) == '039') || 
			
			($len == 10 && substr($strNumber, 0, 3) == '070') || 
			($len == 10 && substr($strNumber, 0, 3) == '076') || 
			($len == 10 && substr($strNumber, 0, 3) == '077') || 
			($len == 10 && substr($strNumber, 0, 3) == '078') || 
			($len == 10 && substr($strNumber, 0, 3) == '079') || 
			
			($len == 10 && substr($strNumber, 0, 3) == '081') || 
			($len == 10 && substr($strNumber, 0, 3) == '082') || 
			($len == 10 && substr($strNumber, 0, 3) == '083') || 
			($len == 10 && substr($strNumber, 0, 3) == '084') || 
			($len == 10 && substr($strNumber, 0, 3) == '085') || 
			($len == 10 && substr($strNumber, 0, 3) == '087') || 
			
			($len == 10 && substr($strNumber, 0, 3) == '056') || 
			($len == 10 && substr($strNumber, 0, 3) == '058') || 
			
			($len == 10 && substr($strNumber, 0, 3) == '059')) 
        && !preg_match("/[^0-9]/", $strNumber)
    )
    {
        $chk = TRUE;
    }

    return $chk;
}

function is_date($date)
{
    $date = str_replace(array('\'', '-', '.', ','), '/', $date);
    $date = explode('/', $date);
    if (count($date) == 1 // No tokens
        && is_numeric($date[0])
        && $date[0] < 20991231
        && (checkdate(substr($date[0], 4, 2), substr($date[0], 6, 2), substr($date[0], 0, 4)))
    )
    {
        return TRUE;
    }
    if (count($date) == 3
        && is_numeric($date[0])
        && is_numeric($date[1])
        && is_numeric($date[2])
        && (checkdate($date[0], $date[1], $date[2]) //mmddyyyy
            OR checkdate($date[1], $date[0], $date[2]) //ddmmyyyy
            OR checkdate($date[1], $date[2], $date[0])) //yyyymmdd
    )
    {
        return TRUE;
    }
    return FALSE;
}

function getRealIpAddr()
{
    $rel = '';
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
    {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (isset($_SERVER['HTTP_X_CLIENT_RIP']))
    {
        $ipaddress = $_SERVER['HTTP_X_CLIENT_RIP'];
    }
    else
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            if (isset($_SERVER['HTTP_X_FORWARDED']))
            {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            }
            else
            {
                if (isset($_SERVER['HTTP_FORWARDED_FOR']))
                {
                    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                }
                else
                {
                    if (isset($_SERVER['HTTP_FORWARDED']))
                    {
                        $ipaddress = $_SERVER['HTTP_FORWARDED'];
                    }
                    else
                    {
                        if (isset($_SERVER['REMOTE_ADDR']))
                        {
                            $ipaddress = $_SERVER['REMOTE_ADDR'];
                        }
                        else
                        {
                            $ipaddress = '';
                        }
                    }
                }
            }
        }
    }
    
    // validate is ip v4 or ip v6
    if($ipaddress != '' && (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) || filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)))
    {
    	$rel = $ipaddress;
    }
    
    return $rel;
}

function hex2rgba($color, $opacity = false)
{
    $default = 'rgb(0,0,0)';
    //Return default if no color provided
    if(empty($color))
    {
        return $default;
    }
    //Sanitize $color if "#" is provided
    if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
    }

    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6)
    {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    }
    elseif ( strlen( $color ) == 3 )
    {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else
    {
        return $default;
    }

    //Convert hexadec to rgb
    $rgb =  array_map('hexdec', $hex);

    //Check if opacity is set(rgba or rgb)
    if($opacity)
    {
        if(abs($opacity) > 1)
        {
            $opacity = 1.0;
        }
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
    }
    else
    {
        $output = 'rgb('.implode(",",$rgb).')';
    }

    //Return rgb(a) color string
    return $output;
}

function generateSeoTitle($str)
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
    $str = preg_replace("/(&)/", 'va', $str);
    $str = str_replace(" ", "-", $str);
    return preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($str));
}

function generateSeoLink($str, $id)
{
    return generateSeoTitle($str) . '-' . $id;
}

function parseSeoLink($str)
{
    $data = $arr = array();
    $arrStr = explode("-", $str);
    for ($i = 0; $i < count($arrStr) - 1; $i++)
    {
        $arr[] = $arrStr[$i];
    }
    $data['title'] = implode('-', $arr);
    $data['id'] = end($arrStr);
    return $data;
}

function cutWord($str, $limit = 20, $comm = '...')
{
    if ($str == '')
    {
        return $str;
    }
    $str2arr = explode(' ', $str);
    if ($limit >= count($str2arr))
    {
        return $str;
    }
    $return = array();
    for ($i = 0; $i < $limit; $i++)
    {
        $return[] = $str2arr[$i];
    }
    return implode(' ', $return) . $comm;
}

function getFromAndToDate(&$fromdate, &$todate, $sysdate = '', $returnCheck = FALSE)
{
    if ($sysdate == '')
    {
        $sysdate = getSysDate();
        $systime = strtotime($sysdate);
    }
    else
    {
        $systime = strtotime($sysdate);
        if ($systime === FALSE OR $systime == -1)
        {
            $sysdate = getSysDate();
            $systime = strtotime($sysdate);
        }
    }
    $fromTime = strtotime($fromdate);
    $totime = strtotime($todate);
    if ($fromTime === FALSE OR $fromTime === -1 OR $totime === FALSE OR $totime === -1 OR $fromTime > $totime)
    {
        $checkValid = FALSE;
        $todate = $sysdate;
        $fromdate = date('Y-m-d', strtotime('-7 day', strtotime($todate)));
    }
    else
    {
        $checkValid = TRUE;
    }
    if ($returnCheck)
    {
        return $checkValid;
    }
}

function validUsername($str)
{
    return preg_match('/^[a-z][a-z0-9_\-\.]+$/i', $str);
}

function validEmail($email)
{
    return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email);
//		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

function realEmail($email)
{
    $chk = false;
    $email = trim($email);
    if($email == '')
    {
        return $chk;
    }
    if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email))
    {
        return $chk;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return $chk;
    }

    list($userName, $mailDomain) = explode("@", $email);
    $mailDomain = trim($mailDomain);
    if(!checkdnsrr($mailDomain, "MX"))
    {
        return $chk;
    }
	/*
    $arr = dns_get_record($mailDomain);
    if(empty($arr))
    {
        return $chk;
    }
    else
    {
        if(isset($arr[1]) && isset($arr[1]['target']) && strtolower(trim($arr[1]['target'])) == 'thongbao.vnnic.vn')
        {
            return $chk;
        }
    }
    */
    return true;
}

function validMd5($md5)
{
    return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
}

function sendmail($data)
{
	/* data array
	$data = array(
		'to' => '',
		'cc' => '',
        'bcc' => '',
		'subject' => '',
		'body' => ''
	);
	*/
	
	$res = array();
    $res['success'] = false;
    $res['message'] = '';
    
    $configs = array(
        'protocol'  =>  'smtp',
        'smtp_host' =>  EMAIL_SMTP_HOST,
        'smtp_user' =>  EMAIL_SMTP_USER,
        'smtp_pass' =>  EMAIL_SMTP_PASS,
        'smtp_port' =>  EMAIL_SMTP_PORT,
        'mailtype'  => 'html'
    );
    
    $realEmailSend = '';
    if (strpos($data['to'], ','))
    {
        $emailList = explode(',', $data['to']);
        foreach ($emailList as $eL)
        {
            if(realEmail($eL))
            {
                $realEmailSend .= $eL . ',';
            }
        }
        $realEmailSend = rtrim($realEmailSend, ',');
    }
    else
    {
        $realEmailSend = realEmail($data['to']) ? $data['to'] : '';
    }

    if ($realEmailSend != '')
    {
        $ci = &get_instance();
        $mail_from = EMAIL_SENDER;
        $from_name = EMAIL_SENDER_NAME;

        $ci->load->library('email', $configs);
        $ci->email->set_newline("\r\n");
        $ci->email->from($mail_from, $from_name);
        $ci->email->to($realEmailSend);
        if (array_key_exists('cc', $data) && trim($data['cc']) != '')
        {
            $ci->email->cc(trim($data['cc']));
        }
        if (array_key_exists('bcc', $data) && trim($data['bcc']) != '')
        {
            $ci->email->cc(trim($data['bcc']));
        }
        $ci->email->subject($data['subject']);
        $ci->email->message($data['body']);

        if($ci->email->send())
        {
            $ci->email->clear();
            $res['success'] = TRUE;
        }
        else
        {
            $res['success'] = false;
            $res['message'] = $ci->email->print_debugger();
        }
    }
    
    return $res;
}


function getSysDate()
{
	$CI = &get_instance();
	$sysdate = trim($CI->session->userdata('sysdate'));
	if($sysdate != '')
	{
		$currTime = time();
		$tmpDate = getdate($currTime);
		if($tmpDate['hours'] >= 7)
		{
			if(strtotime($sysdate) < $currTime)
			{
				$sysdate = date('Y-m-d', $currTime);
			}
		}
	}
	else
	{
		$tmpDate = getdate(time());
		if($tmpDate['hours'] < 7)
		{
			$tmpSysTime = strtotime('-1 day', strtotime (date('Y-m-d')));
			$sysdate = date('Y-m-d', $tmpSysTime);
		}
		else
		{
			$sysdate = date('Y-m-d');
		}
	}
	return $sysdate;
}


function bytesToSize($bytes, $precision = 2)
{
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
    if (($bytes >= 0) && ($bytes < $kilobyte))
    {
        return $bytes . ' B';
    }
    elseif(($bytes >= $kilobyte) && ($bytes < $megabyte))
    {
        return round($bytes / $kilobyte, $precision) . ' KB';
    }
    elseif(($bytes >= $megabyte) && ($bytes < $gigabyte))
    {
        return round($bytes / $megabyte, $precision) . ' MB';

    }
    elseif(($bytes >= $gigabyte) && ($bytes < $terabyte))
    {
        return round($bytes / $gigabyte, $precision) . ' GB';

    }
    elseif($bytes >= $terabyte)
    {
        return round($bytes / $terabyte, $precision) . ' TB';
    }
    else
    {
        return $bytes . ' B';
    }
}


function curl_get_file_size( $url )
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    $data = curl_exec($ch);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    curl_close($ch);
    return $size;
}

function alphaID($in, $to_num = false, $pad_up = false, $pass_key = null)
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

function validate_raw_url($rawUrl)
{
	$chk = false;
	$urlCheck = removeAllTags($rawUrl);
	$urlCompare =	rawurldecode($rawUrl);
	$urlCompare = htmlspecialchars_decode(html_entity_decode($urlCompare, ENT_QUOTES | ENT_IGNORE, "UTF-8"), ENT_QUOTES | ENT_IGNORE);
	$urlCompare = trim($urlCompare);
	
	$chk = $urlCheck == $urlCompare ? true : false;
	
	return $chk;
}

function getImageSizeFromUrl($url)
{
	$rel = [];
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	// Include header in result? (0 = yes, 1 = no)
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//set header token
	// Should cURL return or print out the data? (true = return, false = print)
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Timeout in seconds
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");

	curl_setopt($ch,CURLOPT_POST, 0);

	//execute post
	$data = curl_exec($ch);
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE );
	//$curl_errno = curl_errno($ch);	
	//close connection
	curl_close($ch);
	
	if($http_status == 200)
	{
		$rel = getimagesizefromstring($data);
	}
	
	return $rel;
}

function getCanonicalUrl()
{
    $url = HTTP_PROTOCOL . '://' . $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['PATH_INFO']))
    {
        $url .= $_SERVER['PATH_INFO'];
    }
    else
    {
        $uri = $_SERVER["REQUEST_URI"];
        $tmp = explode('?', $uri);
        $url .= isset($tmp[0]) ? trim($tmp[0]) : $uri;
    }
    return $url;
}

function get_array_year_from_two_date($startDate, $endDate, $format = "Y-m-d")
{
    $result_date = [];

    $start = new DateTime($startDate);
    $start_date = $start->format('Y-m-d');
    $start_year = $start->format('Y');

    $end = new DateTime($endDate);
    $end_date = $end->format('Y-m-d');
    $end_year = $end->format('Y');

    if(strtotime($end_date) >= strtotime($start_date))
    {
        if($start_year == $end_year)
        {
            $result_date[$start_year]['fromdate'] = $start->format('Y-m-d');
            $result_date[$start_year]['todate'] = $end->format('Y-m-d');
        }
        else
        {
            $arr_date = [];
            $interval = new DateInterval('P1D');
            $end = $end->modify( '+1 day' );
            $dateRange = new DatePeriod($start, $interval, $end);
            foreach ($dateRange as $date) {
                $arr_date[$date->format('Y')][] = $date->format($format);
            }

            if(!empty($arr_date))
            {
                foreach ($arr_date as $year => $d)
                {
                    $fromdate = reset($d);
                    $todate = end($d);
                    $result_date[$year] = [
                        'fromdate' => $fromdate,
                        'todate' => $todate,
                    ];

                }
            }
        }
    }
    return $result_date;
}


function trimText($title, $limit)
{
    $newTitle = '';
    if (mb_strlen($title) > $limit)
    {
        $newTitle = mb_substr($title, 0, $limit);
    }
    else
    {
        $newTitle = $title;
    }
    return $newTitle;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}