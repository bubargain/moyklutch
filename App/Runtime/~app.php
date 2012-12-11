<?php  function toDate($time, $format = 'Y-m-d') { if (empty($time) || $time <= 0){ return ''; } $format = str_replace('#', ':', $format); return date($format, $time); } function get_client_ip($format=0) { if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP"); else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR"); else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR"); else if (isset($_SERVER ['REMOTE_ADDR']) && $_SERVER ['REMOTE_ADDR'] && strcasecmp($_SERVER ['REMOTE_ADDR'], "unknown")) $ip = $_SERVER ['REMOTE_ADDR']; else $ip = "unknown"; return ($format==0)?$ip:ip2long($ip); } function xcache($name, $value='', $expire=3600) { if ('' === $value) { return xcache_get($name); } else { if (is_null($value)) { return xcache_unset($name); } else { return xcache_set($name, $value, $expire); } } } function dbCache($name, $value='', $expire=-1) { static $cache = ''; if (empty($cache)) { import('@.ORG.DbCache'); $cache = new DbCache(); } if ('' === $value) { return $cache->get($name); } else { if (is_null($value)) { return $cache->rm($name); } else { return $cache->set($name, $value, $expire); } } } function saveCache($name, $value) { switch (strtoupper(C('CACHE_TYPE'))) { case 'DB': $result = dbCache($name, $value); break; case 'XCACHE': $result = xcache($name, $value); break; case 'FILE': default: $result = file_put_contents(DATA_PATH . '~' . $name . '.php', "<?php\nreturn " . var_export($value, true) . ";\n?>"); break; } return $result; } function loadCache($name) { switch (strtoupper(C('CACHE_TYPE'))) { case 'DB': $cache = dbCache($name); break; case 'XCACHE': $cache = xcache($name); break; case 'FILE': default: $cache = include DATA_PATH . '~' . $name . '.php'; break; } return $cache; } function build_verify($length = 4, $mode = 1) { return rand_string($length, $mode); } function pwdHash($password, $type = 'md5') { return hash($type, $password); } function api($name='System') { static $_api = array(); $name = ucfirst($name) . 'Api'; if (isset($_action[$name])) { return $_api[$name]; } import('@.Api.' . $name); if (class_exists($name)) { $_api[$name] = new $name(); return $_api[$name]; } else { return false; } } function getUserInfo($id,$field='nickname') { static $_user = array(); if(!$_user[$id]) { $User = M("User"); $_user[$id] = $User->where('id='.(int)$id)->find(); if(!$_user[$id]) return false; } if($field=='*') return $_user[$id]; return isset($_user[$id][$field])?$_user[$id][$field]:''; } function word_filter($str) { $vo = M("Config")->find(); $strlist = explode(",",$vo['word_filter']); foreach($strlist as $k=>$val){ $str = str_replace($val,"",$str); } return $str; } function get_collect($id) { $count = M("Collect")->where("user_id=$id")->count(); return $count; } return array ( 'app_debug' => 0, 'app_domain_deploy' => false, 'app_sub_domain_deploy' => false, 'app_plugin_on' => false, 'app_file_case' => false, 'app_group_depr' => '.', 'app_group_list' => 'Home,Admin', 'app_autoload_reg' => false, 'app_autoload_path' => 'Think.Util.', 'app_config_list' => array ( 0 => 'taglibs', 1 => 'routes', 2 => 'tags', 3 => 'htmls', 4 => 'modules', 5 => 'actions', ), 'cookie_expire' => 3600, 'cookie_domain' => '', 'cookie_path' => '/', 'cookie_prefix' => '', 'default_app' => '@', 'default_group' => 'Home', 'default_module' => 'Index', 'default_action' => 'index', 'default_charset' => 'utf-8', 'default_timezone' => 'PRC', 'default_ajax_return' => 'JSON', 'default_theme' => 'default', 'default_lang' => 'zh-cn', 'db_type' => 'mysql', 'db_host' => 'www.uhquan.com', 'db_name' => 'ticket', 'db_user' => 'root', 'db_pwd' => 'RooT', 'db_port' => '3306', 'db_prefix' => 'think_', 'db_suffix' => '', 'db_fieldtype_check' => false, 'db_fields_cache' => true, 'db_charset' => 'utf8', 'db_deploy_type' => 0, 'db_rw_separate' => false, 'data_cache_time' => -1, 'data_cache_compress' => false, 'data_cache_check' => false, 'data_cache_type' => 'File', 'data_cache_path' => './App//Runtime/Temp/', 'data_cache_subdir' => false, 'data_path_level' => 1, 'error_message' => '您浏览的页面暂时发生了错误！请稍后再试～', 'error_page' => '', 'html_cache_on' => false, 'html_cache_time' => 60, 'html_read_type' => 0, 'html_file_suffix' => '.shtml', 'lang_switch_on' => false, 'lang_auto_detect' => true, 'log_exception_record' => true, 'log_record' => false, 'log_file_size' => 2097152, 'log_record_level' => array ( 0 => 'EMERG', 1 => 'ALERT', 2 => 'CRIT', 3 => 'ERR', ), 'page_rollpage' => 5, 'page_listrows' => 20, 'session_auto_start' => true, 'show_run_time' => false, 'show_adv_time' => false, 'show_db_times' => false, 'show_cache_times' => false, 'show_use_mem' => false, 'show_page_trace' => false, 'show_error_msg' => true, 'tmpl_engine_type' => 'Think', 'tmpl_detect_theme' => false, 'tmpl_template_suffix' => '.html', 'tmpl_content_type' => 'text/html', 'tmpl_cachfile_suffix' => '.php', 'tmpl_deny_func_list' => 'echo,exit', 'tmpl_parse_string' => '', 'tmpl_l_delim' => '{', 'tmpl_r_delim' => '}', 'tmpl_var_identify' => 'array', 'tmpl_strip_space' => false, 'tmpl_cache_on' => true, 'tmpl_cache_time' => -1, 'tmpl_action_error' => 'Public:success', 'tmpl_action_success' => 'Public:success', 'tmpl_trace_file' => './ThinkPHP//Tpl/PageTrace.tpl.php', 'tmpl_exception_file' => './ThinkPHP//Tpl/ThinkException.tpl.php', 'tmpl_file_depr' => '/', 'taglib_begin' => '<', 'taglib_end' => '>', 'taglib_load' => true, 'taglib_build_in' => 'cx', 'taglib_pre_load' => '', 'tag_nested_level' => 3, 'tag_extend_parse' => '', 'token_on' => true, 'token_name' => '__hash__', 'token_type' => 'md5', 'url_case_insensitive' => false, 'url_router_on' => false, 'url_route_rules' => array ( ), 'url_model' => 1, 'url_pathinfo_model' => 2, 'url_pathinfo_depr' => '/', 'url_html_suffix' => '', 'var_group' => 'g', 'var_module' => 'm', 'var_action' => 'a', 'var_router' => 'r', 'var_page' => 'p', 'var_template' => 't', 'var_language' => 'l', 'var_ajax_submit' => 'ajax', 'var_pathinfo' => 's', 'cache_type' => 'file', 'member_auth_key' => 'userid', 'uc_connect' => 'POST', 'uc_ip' => 'www.moyklutch.net', 'uc_client_path' => 'App/uc_client/', 'uc_api' => 'http://www.uhquan.com/uc', 'uc_charset' => 'utf8', 'uc_appid' => '1', 'uc_key' => 'y8DbNdlahev8N2P8V6e4Yfo2t8SbL5614fUbaen5Q7v1Ram8pa65p5dbrez8A3J6', 'my_uc_key' => 'y8DbNdlahev8N2P8V6e4Yfo2t8SbL5614fUbaen5Q7v1Ram8pa65p5dbrez8A3J6', '_taglibs_' => array ( 'leishi' => '@.TagLib.TagLibLeishi', ), ); ?>