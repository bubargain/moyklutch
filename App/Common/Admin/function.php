	<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT, JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 上海顶想信息科技有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Author:TopThink Teams <TopThink.com>
// +----------------------------------------------------------------------
// $Vision: 2.1 $
// +----------------------------------------------------------------------
// $Date: 后台函数库

/**
+----------------------------------------------------------
* 根据状态来获取状态显示（模板中使用）
+----------------------------------------------------------
*/
    function getStatus($status, $imageShow = true) {
        switch ($status)
        {
            case 0 :
                $showText   = '禁用';
                $showImg    = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/locked.gif" WIDTH="20" HEIGHT="20"                      BORDER="0" ALT="禁用">';
                break;
            case 2 :
                $showText   = '待审';
                $showImg    = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/prected.gif" WIDTH="20" HEIGHT="20"                     BORDER="0" ALT="待审">';
                break;
            case - 1 :
                $showText   = '删除';
                $showImg    = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0"               ALT="删除">';
                break;
            case 1 :
            default :
                $showText   = '正常';
                $showImg    = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0"               ALT="正常">';
        }

        return ($imageShow === true) ? $showImg : $showText;
    }

/**
+----------------------------------------------------------
* 获取缺省的风格
+----------------------------------------------------------
*/

    function getDefaultStyle($style) {
        return empty($style)?'blue':$style;
    }

/**
+----------------------------------------------------------
* 显示结点名称
+----------------------------------------------------------
*/

    function getNodeName($id) {
        if (Session::is_set('nodeNameList')) {
            $name   = Session::get('nodeNameList');
            return $name [$id];
        }
        $Group  = D("Node");
        $list   = $Group->getField('id,name');
        $name   = $list [$id];
        Session::set('nodeNameList', $list);

        return $name;
    }

/**
+----------------------------------------------------------
* 显示状态(模板使用)
+----------------------------------------------------------
*/

    function get_pawn($pawn) {
        if ($pawn == 0)
            return "<span style='color:green'>没有</span>";
        else
            return "<span style='color:red'>有</span>";
    }

/**
+----------------------------------------------------------
* 判断是否有父节点（模板使用）
+----------------------------------------------------------
*/

    function get_patent($patent) {
        if ($patent == 0)
            return "<span style='color:green'>没有</span>";
        else
            return "<span style='color:red'>有</span>";
    }

/**
+----------------------------------------------------------
*  获取指定节点组的名称
+----------------------------------------------------------
*/

    function getNodeGroupName($id) {
        if (empty($id)) {
            return '未分组';
        }
        if (isset($_SESSION ['nodeGroupList'])) {
            return $_SESSION ['nodeGroupList'] [$id];
        }
        $Group                      = D("Group");
        $list                       = $Group->getField('id,title');
        $name                       = $list [$id];
        $_SESSION ['nodeGroupList'] = $list;

        return $name;
    }

/**
+----------------------------------------------------------
* 显示状态
+----------------------------------------------------------
*/

    function showStatus($status, $id) {
        switch ($status) {
            case 0 :
                $info = '<a href="javascript:resume(' . $id . ')">恢复</a>';
                break;
            case 2 :
                $info = '<a href="javascript:pass(' . $id . ')">批准</a>';
                break;
            case 1 :
                $info = '<a href="javascript:forbid(' . $id . ')">禁用</a>';
                break;
            case - 1 :
                $info = '<a href="javascript:recycle(' . $id . ')">还原</a>';
                break;
        }
        return $info;
    }


/**
+----------------------------------------------------------
* 获取组名称
+----------------------------------------------------------
*/
    function getGroupName($id) {
        if ($id == 0) {
            return '无上级组';
        }
        if ($list = F('groupName')) {
            return $list [$id];
        }
        $dao    = D("Role");
        $list   = $dao->findAll(array('field' => 'id,name'));
        foreach ($list as $vo) {
            $nameList [$vo ['id']]  = $vo ['name'];
        }
        $name = $nameList [$id];
        F('groupName', $nameList);
        return $name;
    }
/**
+----------------------------------------------------------
* 数组排序
+----------------------------------------------------------
*/
    function sort_by($array, $keyname = null, $sortby = 'asc') {
        $myarray    = array();
        $inarray    = array();
        //存储指定的键值到单独的数组中
        foreach ($array as $i => $befree) {
            $myarray [$i] = $array [$i] [$keyname];
        }
        //按照指定方法进行指定排序
        switch ($sortby) {
            case 'asc' :
                asort($myarray); //正向排序
                break;
            case 'desc' :
            case 'arsort' :
                arsort($myarray);//逆向排序
                break;
            case 'natcasesor' :
                natcasesort($myarray);//自然排序
                break;
        }
        foreach ($myarray as $key => $befree) {
            $inarray [] = $array [$key];
        }//重建数组
        return $inarray;
    }

/**
  +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
  +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */
    function rand_string($len = 6, $type = '', $addChars = '') {
        $str = '';
        switch ($type) {
            case 0 :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1 :
                $chars = str_repeat('0123456789', 3);
                break;
            case 2 :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3 :
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        if ($len > 10) { //位数过长重复字符串一定次数
            $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $len);
        } else {
            // 中文随机字
            for ($i = 0; $i < $len; $i++) {
                $str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }
/**
  +----------------------------------------------------------
  * 把返回的数据集转换成Tree
  +----------------------------------------------------------
  * @access public
  +----------------------------------------------------------
  * @param array $list 要转换的数据集
  * @param string $pid parent标记字段
  * @param string $level level标记字段
  +----------------------------------------------------------
  * @return array
  +----------------------------------------------------------
 */
    function toTree($list=null, $pk='id', $pid = 'pid', $child = '_child', $root=0) {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = & $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = & $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = & $refer[$parentId];
                        $parent[$child][] = & $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
/**
+----------------------------------------------------------
* 将tree转化为一维数组
+----------------------------------------------------------
* @access Public
+----------------------------------------------------------
* @param string  $array   toTree转化的数组
* @param integer $level   层次
+----------------------------------------------------------
* @return string
+----------------------------------------------------------
*/
   function listTree(&$array,$level=0,$child='_child')
   {
        static $_result = array();//初始化返回数组

        foreach ($array as $key => &$val)
        {
            $val['level'] = $level;//为本列填加层次结构
            if(isset($val[$child]))
            {
                $tmp = $val[$child];//临时存储tmp
                unset($val[$child]);
                $_result[] = $val;
                listTree($tmp,$level+1,$child);//递归函数
            }else{
                $_result[] = $val;
            }
        }
        return $_result;
   }

 /**
 +----------------------------------------------------------
 * 获取系统中可用的应用
 +----------------------------------------------------------
 */

    function getApps() {
        $apps = M('Group')->where('status=1')->order('sort asc')->field('id,name,title')->select();
        return $apps;
    }
 /**
 +----------------------------------------------------------
 * 获取系统中的应用名称
 +----------------------------------------------------------
 */

    function getAppName($name='System', $field='name', $want='title') {
        static $_appstitle = array();
        $name = strtolower($name);
        if (isset($_appstitle[$name . '_' . $want]))
            return $_appstitle[$name . '_' . $want]; //如果缓存数组中存在直接返回
        $apps = getApps();
        foreach ($apps as $key => $val) {
            if (strtolower($val[$field]) == $name) {
                $_appstitle[$name . '_' . $want] = $val[$want]; //存在赋值，不存在则用系统基础的.
                return $val[$want];
            }
        }
        return $apps[0][$want]; //默认返回系统础始值
    }

/**
+----------------------------------------------------------
*  获取当前应用的名称
+----------------------------------------------------------
*/
    function getCurrentApp()
    {
        return getAppName($_SESSION['_menuTag'], 'id', 'name');
    }

/**
  +----------------------------------------------------------
  * 显示局部全局状态
  +----------------------------------------------------------
 */

    function showActType($status) {
        return ($status == 0) ? '局部' : "<font color='red'><b>全局</b></font>";
    }
/**
  +----------------------------------------------------------
  * 根据输入数据自动获取日志信息
  +----------------------------------------------------------
  * @param string  $model  model名称
  * @param integer $id     记录id
  * @param array   $data   比较的数据
  +----------------------------------------------------------
 * @return array  根据数据获取的值
  +----------------------------------------------------------
 */
 function build_log($model, $data='', $compare='') {
    //初始化输入
    $data = empty($data) ? $_POST : $data;
    if (empty($data)){
        return false; //如果输入的数据为空返回false;
    }
    $id         = $data['id'];
    $vo         = D($model);
    $original   = $vo->where("id=" . intval($id))->find();
    if (!$original){
        return false; //新加数据无法比较
    }
    $compare    = empty($compare) ? $vo->_log : $compare; //获取比较日志
    $logs       = array(); //初始化返回数组

    //进行值的比较
    foreach ($compare as $key => $val) {
        if (isset($data[$key]) && ($original[$key] != $data[$key])) {
            $title      = $compare[$key]['title'];
            $or_val     = $original[$key];
            $now_val    = $data[$key];
            if (isset($val['value'])) {
                //获取额外配置的值
                $config = $val['value'];
                if (!is_array($config)) {
                    $config = getConfig($config);
                }
                $logs[$key]     = $title . "从 '" . $config[$or_val] . "' 改变成: '" . $config[$now_val] . "' ";
            } elseif (substr($key, -4) == 'time') {
                if (strtotime($now_val) != $or_val) {
                    $logs[$key] = $title . "从 '" . toDate($or_val) . "' 改变成: '" . toDate($now_val) . "' ";
                }
            } else {
                $logs[$key] = $title . "从 '" . $or_val . "'改变成:'" . $now_val . "' ";
            }
        }
        //判断是否有修改说明
        if (isset($data[$key . '_remark']) && !empty($data[$key . '_remark'])) {
            $logs[$key].="<br/>修改原因：" . $data[$key . '_remark'] . '<br/>';
        }
    }
    return $logs;
 }

/**
  +----------------------------------------------------------
  * 在模板中根据数组的内容获取值
  +----------------------------------------------------------
 */
    function get_val($val, $ary) {
        $ary = is_string($ary)? explode(',',$ary):$ary;
        return isset($ary[$val]) ? $ary[$val] : '';
    }
/**
 +----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
    function byte_format($size, $dec=2)
    {
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
             $size /= 1024;
               $pos++;
        }
        return round($size,$dec)." ".$a[$pos];
    }
/**
 +----------------------------------------------------------
 * 对查询结果集进行排序
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}

/**
 +----------------------------------------------------------
 * 在数据列表中搜索
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}
//判断是否有上传文件
function check_upload()
{
   if(empty($_FILES)) return false;//如果不存在，则直接返回flase
   $data = $_FILES;
   foreach ($data as $key => &$val)
   {
       if(is_array($val['name']))
       {
          $data[$key] = array_filter($val['name']);
       }else{
          if(empty($val['name']))
          {
             unset($data[$key]);
          }
       }
   }
   return (count(array_filter($data))>0)?true:false;
}
//显示所有附件
function showAttach($ids,$showkey=-1,$thumb='',$Suffix='')
{
    static $_Image = array();
    if(!$_Image)
    {
        $api = Api();
        $_Image = $api->getAttachInfo($ids);
        $_Image = isset($_Image['id'])?array($_Image):$_Image;
        foreach ($_Image as $key => $val)
        {
            $thumb_name = substr_replace($val['name'],$Suffix,strrpos($val['name'],'.'),0);
            $thumb_name = $thumb.$thumb_name;
            if(is_file($val['path'].$thumb_name))
            {
              $_Image[$key]['showimage'] = $val['path'].$thumb_name;
            }else{
              $_Image[$key]['showimage'] = $val['path'].$val['name'];
            }
        }
    }
    if($showkey<0) return $_Image;
    return $_Image[$showkey];
}
//显示图片
function showImage($id,$thumb='',$Suffix='')
{
     $api = Api();
     $id = intval($id);
     $image = $api->getAttachInfo($id);
     $thumb_name = substr_replace($image['name'],$Suffix,strrpos($image['name'],'.'),0);
     $thumb_name = $thumb.$thumb_name;
     $filename   = $image['path'].$thumb_name;
     return !is_file($filename)?__ROOT__.'/Uploads/noimages.jpg':__ROOT__.$filename;
}


//根据user_id优惠券统计 点击0，打印1，收藏2
function user_count($id,$type_id)
{
	$model= M("Ticket_statistic");
		if($type_id==2){
		$cm=M("Collect");
		$count=$cm->where("user_id=$id")->count("id");
		}else{
		$sql  = "user_id=$id and action_type=$type_id";
		$count= $model->where($sql)->count("id");
		}
	return $count;
}
//
function ticket_count_byuser($id,$t1,$t2,$type_id){
	$t2=$t2+60*60*24-1;
	$model= M("Ticket_statistic");
	$sql="1=1";
	if($t1>0){
	$sql=$sql." and create_time>$t1";
	}
	if($t2>60*60*24){
	$sql=$sql." and create_time<$t2";
	}
		if($type_id==2){
		$cm=M("Collect");
		$sql  = $sql." and user_id=$id";
		$count=$cm->where($sql)->count("id");
		}else{
		$sql  = $sql." and user_id=$id and action_type=$type_id";
		$count= $model->where($sql)->count();
		}
	return $count;
}

function ticket_count_bytrade($id,$t1,$t2,$type_id){
	$t2=$t2+60*60*24-1;
	$model= M("Ticket_statistic");
	$sql="1=1";
	if($t1>0){
	$sql=$sql." and create_time>$t1";
	}
	if($t2>60*60*24){
	$sql=$sql." and create_time<$t2";
	}

	$m=M("Ticket");
	$tlist=$m->where("trade_id=$id")->select();
	$str="(0";
	foreach($tlist as $k=>$val){
	$str=$str.",$val[id]";
	}
	$str=$str.")";
		if($type_id==2){
			$cm=M("Collect");
			$sql  = $sql." and ticket_id in $str";
			$count=$cm->where($sql)->count("id");
			}else{
				$sql  = $sql." and ticket_id in $str and action_type=$type_id";
				$count= $model->where($sql)->count();
			}
	return $count;
}

function ticket_count_byticket($id,$t1,$t2,$type_id){
	$t2=$t2+60*60*24-1;
	$model= M("Ticket_statistic");
	$sql="1=1";
	if($t1>0){
	$sql=$sql." and create_time>$t1";
	}
	if($t2>60*60*24){
	$sql=$sql." and create_time<$t2";
	}
	        if($type_id==2){
			$cm=M("Collect");
			$sql  = $sql." and ticket_id =$id";
			$count=$cm->where($sql)->count("id");
			}else{
			$sql  = $sql." and ticket_id=$id and action_type=$type_id";
			$count= $model->where($sql)->count();
			}
	return $count;
}

//根据ticket_id优惠券统计 点击0，打印1，收藏2
function ticket_count($id,$type_id)
{
	$model= M("Ticket_statistic");
	if($type_id==2){
	$cm=M("Collect");
	$sql  ="ticket_id =$id";
	$count=$cm->where($sql)->count("id");
	}else{
		$sql  = "ticket_id=$id and action_type=$type_id";
		$count= $model->where($sql)->count("id");
	}
	return $count;
}

function trade_count($id,$type_id)
{
	$m=M("Ticket");
	$tlist=$m->where("trade_id=$id")->select();
	$str="(0";
	foreach($tlist as $k=>$val){
		$str=$str.",$val[id]";
		}
	$str=$str.")";
	$model= M("Ticket_statistic");
		if($type_id==2){
			$cm=M("Collect");
			$sql  ="ticket_id in $str";
			$count=$cm->where($sql)->count("id");
		}else{
			$sql  = "ticket_id in $str and action_type=$type_id";
			$count= $model->where($sql)->count("id");
		}
	return $count;
}
//取得商圈列表
function get_p_catelist($id)
{
	$model = M("Area");
	$plist = $model->where("pid=0 and status=1")->select();
	return $plist;
	}
//根据id取得商圈名称
function get_p_cate($id)
{
	$model = M("Area");
	$p     = $model->getById($id);
	return $p['title'];
	}

//根据id取得类别名称
function get_cate($id)
{
	$model = M("Cate");
	$p     = $model->getById($id);
	return $p['title'];
	}

//根据id取得地址名称
function get_position($id)
{
	$model = M("Location");
	$p     = $model->where("id=$id")->find();
	return $p['name'];
	}
//根据标识取得地址名称
function get_positionbyname($name)
{
	$model = M("Machine");
	$p     = $model->where("name='$name'")->find();
	$model = M("Location");
	$l     = $model->where("id=$p[p_id]")->find();
	return $l['name'];
	}
//根据标识取得地址id
function get_pidbyname($name)
{
	$model = M("Machine");
	$p     = $model->where("name='$name'")->find();
	return $p['p_id'];
	}

//取得地址列表
function get_plist()
{
	$p    = M("Location");
	$plist= $p->where("status=1")->select();
	return $plist;
   }
//取得地址option列表
function get_p_option()
{
	$p    = M("Location");
	$plist= $p->select();
	$str="";
	foreach($plist as $list)
	{
		$str.="<option value='".$list['id']."'>".$list['name']."</option>";
		}
	return $str;
   }


   //根据ID取得商家名
function get_trade($id)
{
	$model   = M("Trade");
	$m=$model->getById($id);
	return $m['title'];
	}
   //根据优惠券ID取得商家名
function get_tradebyticket($id)
{
	$model=M("Ticket");
	$ticket=$model->where("id=$id")->find();
	$model   = M("Trade");
	$m=$model->where("id=$ticket[trade_id]")->find();
	return $m['title'];
	}
   //根据ID取得优惠券名
function get_ticket($id)
{
	$model   = M("Ticket");
	$m=$model->getById($id);
	return $m['title'];
	}
function get_ticketbytrade($id)
{
	$model   = M("Ticket");
	$m=$model->where("trade_id=$id and status=1 and close_time>".time())->count();
	return $m;
}
function get_branch($id)
{
	$model   = M("Trade_branch");
	$m=$model->where("trade_id=$id")->count();
	return $m;
	}

function get_tenancybytrade($id)
{
    $model  = M("Tenancy");
	$m=$model->where("trade_id=$id and status<2 and status>0")->count();
	return $m;
}

function get_countbytenacy($id){
	$model=M("Ticket");
	$ticket=$model->where("trade_id=$id")->select();
	$str="(-1";
	foreach($ticket as $k=>$v){
		$str=$str.",$v[id]";
		}
	$str=$str.")";
	$model=M("Ticket_statistic");
	$m=$model->where("ticket_id in $str")->count();
	return $m;
	}
function get_countinfobytenacy($id,$pid,$time1,$time2){
	$model=M("Ticket");
	$ticket=$model->where("trade_id=$id")->select();
	$str="(-1";
	foreach($ticket as $k=>$v){
		$str=$str.",$v[id]";
		}
	$str=$str.")";
	$model=M("Ticket_statistic");
	$sql="position_id=$pid and ticket_id in ".$str;
	$pjsql="position_id=$pid";
	if($time1!=0){
		$sql=$sql." and create_time>$time1";
		$pjsql=$pjsql." and create_time>$time1";
		}
	if($time2!=0){
		$sql=$sql." and create_time<$time2";
		$pjsql=$pjsql." and create_time<$time2";
		}
	$m=$model->where($sql)->count();
	$pj=$model->where($pjsql)->count();
	$pjm=$pj/15;
	if($m<$pjm){
	return $m."次，偏低";
	}
	if($m==$pjm){
	return $m."次，正常";
	}
	if($m>$pjm){
	return $m."次，很好";
	}
	}
//根据ID取得用户名
function get_user($id)
{
	$model   = M("User");
	$user=$model->getById($id);
	return $user['account'];
	}
//根据授权取得用户名
function get_userbytrade($id)
{
	$model   = M("User_trade");
	$user=$model->where("trade_id=$id")->select();
	$str="";
    foreach($user as $k=>$val){
		$str.="<a href='__APP__/Admin/User/index/id/".$val["user_id"]."' >".get_user($val["user_id"])."</a>&nbsp;&nbsp;";
		}
	return $str;
	}
//根据ID取得标签名
function get_tag($id)
{
	$model   = M("Tag");
	$user=$model->getById($id);
	return $user['title'];
	}
//根据ID取得标签名
function get_bt_position($id)
{
	$model   = M("Exhibition");
	$l=$model->getById($id);
	return $l['bt_position'];
	}
//根据pID取得标签列表
function get_taglist()
{
	$model   = M("Tag");
	$plist=$model->where("status=1")->select();
	$str="";
	foreach($plist as $list)
	{
		$str.="<option value='".$list['id']."'>".$list['title']."</option>";
	}
	return $str;
}

//取得大商圈一级列表
function get_clist()
{
	$p    = M("Area");
	$plist= $p->where("pid=0 and status=1")->select();
	$str="";
	foreach($plist as $list)
	{
		$select="";
		if($_REQUEST['pid']==$list['id'])
		{
			$select="selected";
		}
		$str.="<option value='".$list['id']."' ".$select.">".$list['title']."</option>";
		}
	return $str;
	}

//取得产品一级列表
function get_catelist()
{
	$p    = M("Cate");
	$plist= $p->where("pid=0 and status=1")->select();
	$str="";
	foreach($plist as $list)
	{
		$select="";
		if($_REQUEST['pid']==$list['id'])
		{
			$select="selected";
		}
		$str.="<option value='".$list['id']."' ".$select.">".$list['title']."</option>";
		}
	return $str;
}


//展位是否出租
	function get_color($id){
		$model = M("Tenancy");
		$result= $model->where("status<2 and bid=$id")->find();
		if($result){
			$over=$result['close_time']-time();
			if($over<3600*24*90){
		     return "#FC3";
		     }else{
			 return "#C33";
				 }
		}else{
		  return "#0C0";
			}
	}
//展位出租信息
	function get_rendinfo($id){
		$model = M("Tenancy");
		$t=$model->where("status<2 and  bid=$id")->order("id desc")->find();
		if($t&&$t['close_time']>time()){
		  if($t['close_time']<(time()+60*60*24*30)||$t['start_time']<(time()+3600*24)){
			  $color="#FC3";
			  }else{
			  $color="#C33";
				  }
		  return  "<font color='$color'>".date("Y-m-d",$t['close_time']+3600*24)."到期结束，当前商家：</font><a target='_blank' href='__APP__/Trade/index/id/$t[trade_id]'>".get_trade($t['trade_id'])."</a>";
		}else{
		  return "<font color=green>暂未出租</font>";
		}
	}
//展位出租信息  用于批量添加租赁
	function get_rendinfo2($id){
		$model = M("Tenancy");
		$t=$model->where("status<2 and bid=$id")->find();
		if($t&&$t['close_time']<(time()+60*60*24*30)){
		  return 2;
		}elseif($t&&$t['close_time']>(time()+60*60*24*30)){
		  return 3;
		}elseif(!$t){
		  return 1;
			}
	}
//展位的到期时间
	function get_rendclosetime($id){
		$model = M("Tenancy");
		$t=$model->where("status>0 and bid=$id and close_time>".time())->find();
		if($t){
		  return $t['close_time']+2;
		}else{
		  return 1;
			}
	}
//展位到期时间
	function get_rendtime($id){
		$model = M("Tenancy");
		$t     = $model->where("status<2 and bid=$id")->find();
		if($t){
		  return date("y-m-d",$t['close_time']);
		}else{
		  return "暂未出租";
			}
	}
//展位到期时间
	function get_rendtime2($id){
		$model = M("Tenancy");
		$t     = $model->where("status<2 and bid=$id")->order("id desc")->find();
		if($t){
		  return date("y-m-d",$t['close_time']);
		}else{
		  return "";
			}
	}
//机器到期时间
	function get_rend_ltime($id){
		$model = M("Location");
		$t     = $model->where("id=$id")->find();
		if($t){
		  return date("y年-m月-d日",$t['close_time']);
		}else{
			return "";
			}
	}
//机器到期时间
	function get_rend_ltime2($id){
		$model = M("Location");
		$t     = $model->where("id=$id")->find();
		if($t){
		  return date("y-m-d",$t['close_time']);
		}else{
			return "";
			}
	}
//展位租金
	function get_rendmoney($id){
		$model = M("Location");
		$t     = $model->where("id=$id")->find();
		  return $t['money'];
	}

//根据user_id取得会员卡绑定的会员
   function getuserbycard($id){
	    $model = M("User");
		$vo    = $model->where("card_id='{$id}'")->find();
		if($vo){
			return $vo['account'];
			}
		else{
		    return "未绑定会员";
			}
	   }

//取得产品大分类option列表
function get_cate_option()
{
	$p    = M("Cate");
	$plist= $p->where("pid=0 and status=1")->select();
	$str="";
	foreach($plist as $list)
	{
		$str.="<option value='".$list['id']."'>".$list['title']."</option>";
		}
	return $str;
}
//取得商家信息
function get_trade_option()
{
	$p    = M("Trade");
	$plist= $p->where("status=1")->select();
	$str="";
	foreach($plist as $list)
	{
		$str.="<option value='".$list['id']."'>".$list['title']."</option>";
		}
	return $str;
}
//取得大商圈option列表
function get_area_option()
{
	$p    = M("Area");
	$plist= $p->where("pid=0 and status=1")->select();
	$str="";
	foreach($plist as $list)
	{
		$str.="<option value='".$list['id']."'>".$list['title']."</option>";
		}
	return $str;
}

function deldir($dir) {
	  $dh=opendir($dir);
	  while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
		  $fullpath=$dir."/".$file;
		  if(!is_dir($fullpath)) {
			  unlink($fullpath);
		  } else {
			  deldir($fullpath);
		  }
		}
	  }
	  closedir($dh);

	  if(rmdir($dir)) {
		return true;
	  } else {
		return false;
	  }
	}

	function get_tradelogo($id){
		$model = M("Attach");
		$count =$model->where("module='trade' and bid=$id")->count();
		$t     = $model->where("module='trade' and bid=$id")->limit(3)->select();
		$str="";
		foreach($t as $k=>$v){
			$str=$str."<img src='__ROOT__".$v['thumpath']."' width='48' height='48'>&nbsp;";
			}
		if($count>3){
		  return $str."(共".$count."张)";
		  }else{
		  return $str;
			  }
	}

	function get_rendnumbyloc($id){
		$model=M("Tenancy");
		$count=$model->where("p_id=$id and status=1")->count();
		$count2=$model->where("p_id=$id and (status=0 or status=2)")->count();
		return $count."($count2)";
		}
	function get_countbyloc($id){
		$model=M("Ticket_statistic");
		$count=$model->where("position_id=$id")->count();
		return $count;
		}

   function getuseravtar($id){
	   $model=M("User");
	   $user=$model->where("id=$id")->find();
	   return $user['logo'];
	   }
   function get_userinfo($id){
	   $model=M("User");
	   $user=$model->where("id=$id")->find();
	   return "ID:".$user['id']."<br />用户名:".$user['account']."<br />姓名：".$user['realname']."<br />邮箱：".$user['email']."<br />地址：".$user['address']."<br />手机：".$user['mobile'];
	   }



// +----------------------------------------------------------------------
//新加的
// +----------------------------------------------------------------------

function get_newlist()
{
	$model   = M("Newclass");
	$list=$model->field("id,name,pid,path,concat(path,'-',id) as bpath")->order('bpath')->select();
	   foreach($list as $key=>$value){
                $list[$key]['count']=count(explode('-',$value['bpath']));
	    }
	$str="";
	foreach($list as $klist)
	{
		$str.="<option value='".$klist['id']."'>";
 	    for($i=0;$i<$klist['count']*2;$i++){
			$str.= '-';
            }
        $str.=$klist['name']."</option>";
	}
	return $str;
}
	//检查秒杀是否失败
	function get_seckilltype($id){
		$type = M('SeckillUser')->where('gid='.$id.' AND sectype<>0')->count();
		return $type;
	}
?>