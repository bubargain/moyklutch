<?php
/**
  +------------------------------------------------------------------------------
  * 项目公共函数库
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */

/**
+----------------------------------------------------------
* 日期转字符串
+----------------------------------------------------------
*/

    function toDate($time, $format = 'Y-m-d') {
        if (empty($time) || $time <= 0){
            return '';
        }
        $format = str_replace('#', ':', $format);

        return date($format, $time);
    }

/**
+----------------------------------------------------------
* 获取用户ip地址
+----------------------------------------------------------
*/

    function get_client_ip($format=0) {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER ['REMOTE_ADDR']) && $_SERVER ['REMOTE_ADDR'] && strcasecmp($_SERVER ['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER ['REMOTE_ADDR'];
        else
            $ip = "unknown";

        return ($format==0)?$ip:ip2long($ip);
    }

/**
+----------------------------------------------------------
* xcache缓存数据
+----------------------------------------------------------
*/

    function xcache($name, $value='', $expire=3600) {
        if ('' === $value) {
            // 获取缓存
            return xcache_get($name);
        } else {
            if (is_null($value)) {
                return xcache_unset($name);
            } else {
                return xcache_set($name, $value, $expire);
            }
        }
    }

/**
+----------------------------------------------------------
* 使用数据库缓存数据
+----------------------------------------------------------
*/
    function dbCache($name, $value='', $expire=-1) {
        static $cache = '';
        if (empty($cache)) {
            import('@.ORG.DbCache');
            $cache = new DbCache();
        }
        if ('' === $value) {
            // 获取缓存
            return $cache->get($name);
        } else {
            if (is_null($value)) {
                return $cache->rm($name);
            } else {
                return $cache->set($name, $value, $expire);
            }
        }
    }

/**
+----------------------------------------------------------
* 缓存数据总方法，可以根据类型来缓存数据
+----------------------------------------------------------
*/

    function saveCache($name, $value) {
        switch (strtoupper(C('CACHE_TYPE'))) {
            case 'DB':// 数据库缓存
                $result = dbCache($name, $value);
                break;
            case 'XCACHE':// Xcache缓存
                $result = xcache($name, $value);
                break;
            case 'FILE':// 文件缓存
            default:
                $result = file_put_contents(DATA_PATH . '~' . $name . '.php', "<?php\nreturn " . var_export($value, true) . ";\n?>");
                break;
        }
        return $result;
    }

/**
+----------------------------------------------------------
* 加载缓存数据
+----------------------------------------------------------
*/

    function loadCache($name) {
        switch (strtoupper(C('CACHE_TYPE'))) {
            case 'DB':// 数据库缓存
                $cache = dbCache($name);
                break;
            case 'XCACHE':// Xcache缓存
                $cache = xcache($name);
                break;
            case 'FILE':// 文件缓存
            default:
                $cache = include DATA_PATH . '~' . $name . '.php';
                break;
        }
        return $cache;
    }
/**
  +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
  +----------------------------------------------------------
 * @param string $fmode 文件名
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */

    function build_verify($length = 4, $mode = 1) {
        return rand_string($length, $mode);
    }
/**
+----------------------------------------------------------
* 获取hash值
+----------------------------------------------------------
*/
    function pwdHash($password, $type = 'md5') {
        return hash($type, $password);
    }

/**
  +----------------------------------------------------------
 * 载入相应的api类(限定在同一应用之中)
  +----------------------------------------------------------
 * @param string $name api类别名称
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */

    function api($name='System') {
        static $_api    = array();
        $name   = ucfirst($name) . 'Api';
        if (isset($_action[$name]))
        {
            return $_api[$name]; //如果已载入api，则直接调用api方法
        }
        import('@.Api.' . $name); //载入指定的api类库
        if (class_exists($name)) {
            $_api[$name] = new $name();
            return $_api[$name];
        } else {
            return false;
        }
    }
/**
+----------------------------------------------------------
* 获取用户昵称
+----------------------------------------------------------
*/
function getUserInfo($id,$field='nickname')
{
    static $_user   =  array();
    if(!$_user[$id])
    {
        $User	=	M("User");
        $_user[$id]	=	$User->where('id='.(int)$id)->find();
        if(!$_user[$id]) return false; //排除找不到指定用户的情况
    }
    if($field=='*') return $_user[$id];
    return isset($_user[$id][$field])?$_user[$id][$field]:'';
}

function word_filter($str)
{
	$vo = M("Config")->find();
	$strlist = explode(",",$vo['word_filter']);
	foreach($strlist as $k=>$val){
	    $str = str_replace($val,"",$str);
	}
	return $str;
}

function get_collect($id)
{
	$count = M("Collect")->where("user_id=$id")->count();
	return $count;
	}
?>