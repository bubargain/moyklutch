<?php
//判断字符串是否存在
function strexists($haystack, $needle) {
	return !(strpos($haystack, $needle) === FALSE);
}
//安全输出
function e($string){
		//安全输出
		$string = str_replace('\'', '\\\'', $string);
		$string = str_replace('"', '\\"', $string);
		$string = str_replace(array("\r", "\n"), array('', '\n'), $string);
	return $string;
}

//模板调用
function template($file) {
	global $_SCONFIG;

	$tpl = "View/$_SCONFIG[template]/$file";
	$objfile = 'data/tpl_cache/'. $_SCONFIG['controllerName'] . '_' . $_SCONFIG['actionName'] . '.php';
	if(!file_exists($objfile)) {
		include_once('Lib/function_template.php');
		parse_template($tpl,$objfile);
	}
	return $objfile;
}

//获取文件内容
function sreadfile($filename) {
	$content = '';
	if(function_exists('file_get_contents')) {
		@$content = file_get_contents($filename);
	} else {
		if(@$fp = fopen($filename, 'r')) {
			@$content = fread($fp, filesize($filename));
			@fclose($fp);
		}
	}
	return $content;
}

//写入文件
function swritefile($filename, $writetext, $openmod='w') {
	if(@$fp = fopen($filename, $openmod)) {
		flock($fp, 2);
		fwrite($fp, $writetext);
		fclose($fp);
		return true;
	} else {
		runlog('error', "File: $filename write error.");
		return false;
	}
}

//产生随机字符
function random($len=6,$upper=1) {
	$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
	$str  = '';
    if($len>10 ) {//位数过长重复字符串一定次数
        $chars= str_repeat($chars,5);
    }
   
	for($i=0;$i<$len;$i++){
	  $str.= substr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
	}
    return ($upper==1)?strtoupper($str):$str;
}

//清除指定后缀的模板编译文件
function clear_tpl_files(){
    $dirs[] = S_ROOT . 'data/tpl_cache/';
    $dirs[] = S_ROOT . 'data/pic_cache/';
    $count   = 0;
	foreach($dirs as $dir)
	{
		$folder = opendir($dir);
		if ($folder != false){
			while ($file = readdir($folder)){
				if (is_file($dir . $file)){
					if (unlink($dir . $file)){
						$count++;
					}
				}
			}
		}
	closedir($folder);
	}
    return $count;
}
//get post COOKIE
function getgpc($key, $var = 'G', $type = 'str', $default = NULL)
{
    $var = strtoupper($var);
    switch($var)
    {
        case 'G': $var = &$_GET; break;
        case 'P': $var = &$_POST; break;
        case 'C': $var = &$_COOKIE; break;
    }
    if(isset($var[$key]))
    {
        $value = $var[$key];
        switch ($type)
        {
            case 'array':
                $value = (array)$value;
                return $value;
                break;

            case 'int':
                return (int)$value;
                break;

            case 'float':
                return (float)$value;
                break;

            case 'str':
                return (string)$value;
                break;

            default:
                return (string)$value;
                break;
        }
    }
    else
    {
        switch ($type)
        {
            case 'array':
                return array();
                break;

            case 'int':
                return ($default === NULL) ? 0   : (int)$default;
                break;

            case 'float':
                return ($default === NULL) ? 0.0 : (float)$default;
                break;

            case 'str':
                if ($default === NULL)
                {
                    return '';
                }
                elseif (strpos($default, '|') === false)
                {
                    return (string)$default;
                }
                else
                {
                    $_value = explode('|', $default);
                    return (string)$_value[0];
                }
                break;
            default:
                if ($default === NULL)
                {
                    return '';
                }
                elseif (strpos($default, '|') === false)
                {
                    return (string)$default;
                }
                else
                {
                    $_value = explode('|', $default);
                    return (string)$_value[0];
                }
                break;
        }
    }
}
//显示返回信息
function showmsg($message, $url='')
{
	$_SESSION['opmeg'] = $message;
	$url = empty($url)? $_SERVER['HTTP_REFERER'] : $url;
	if($url == $_SESSION['lastPage'])
	{
		unset($_SESSION['lastPage']);
	}
	header("HTTP/1.1 303 See Other");
	header("Location:" . $url);
    exit();
}

// 会员没有权限，跳转至错误信息页
function showact($controllerName, $actionName, $controllerClass, $ACT, $roles)
{
	global $_SCONFIG,$_SGLOBAL;
	if(isset($_SESSION[$_SCONFIG['RBACSessionKey']]))
	{
		showmsg('没有访问该页面的权限');
	}
	else
	{
		$_SESSION['lastPage'] = $_SERVER['REQUEST_URI'];
		$gourl = url('Default', 'Login');
		showmsg('请先登陆', $gourl);
	}
}
//获取字符串
function getstr($string, $length,$charset) {
	global $_SC, $_SGLOBAL;

	//$string = trim($string);

	if($length) {
		//截断字符
		$wordscut = '';
		if(strtolower($charset) == 'utf-8') {
			//utf8编码
			$n = 0;
			$tn = 0;
			$noc = 0;
			while ($n < strlen($string)) {
				$t = ord($string[$n]);
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1;
					$n++;
					$noc++;
				} elseif(194 <= $t && $t <= 223) {
					$tn = 2;
					$n += 2;
					$noc += 2;
				} elseif(224 <= $t && $t < 239) {
					$tn = 3;
					$n += 3;
					$noc += 2;
				} elseif(240 <= $t && $t <= 247) {
					$tn = 4;
					$n += 4;
					$noc += 2;
				} elseif(248 <= $t && $t <= 251) {
					$tn = 5;
					$n += 5;
					$noc += 2;
				} elseif($t == 252 || $t == 253) {
					$tn = 6;
					$n += 6;
					$noc += 2;
				} else {
					$tn = 3;
					$n+=3;
					$noc += 2;
				}
				if ($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
				$noc -= 2;
			}
			$wordscut = substr($string, 0, $n).'| |'.$noc;
			//echo($wordscut . ' '.  $noc .' <br>');
		} else {
			for($i = 0; $i < $length; $i++) {
				if(ord($string[$i]) > 127) {
					if($i == $length-1){
						break;
					}
					else{
						$wordscut .= $string[$i].$string[$i + 1];
						$i++;
					}
				} else {
					$wordscut .= $string[$i];
				}
			}
		}
		$string = $wordscut;
	}
	return $string;
}
?>