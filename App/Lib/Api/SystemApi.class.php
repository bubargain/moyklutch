 <?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT, JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author:TopThink Teams <TopThink.com>
// +----------------------------------------------------------------------
// |  $Vision: 2.1 $
// +----------------------------------------------------------------------
//  系统底层接口Api
// $Date: 2010.10.01 $

class SystemApi{

    /**
    +----------------------------------------------------------
    * 上传附件
    +----------------------------------------------------------
    * @access public
    +----------------------------------------------------------
    * @param string  $config 上传配置选项
    +----------------------------------------------------------
    * @return array  $info['status']  成功状态 0-false 1-true
    * @return array  $info['info']    返回信息
    * @return array  $info['data']    插入的数组信息
    +----------------------------------------------------------
    */

    public function uploadAttach($config)
    {
        $info   = D('Attach')->uploadAttach($config);
        return $info;
    }
    //设置附件的记录id
    public function setAttachRecord($attachid,$record_id)
    {
        $attachid = is_array($attachid)?implode(',',$attachid):$attachid;
        $info   = D('Attach')->where("id in ({$attachid})")
                             ->setField(array('record_id','status'),array(intval($record_id),1));
        return true;
    }
    //删除指定id的记录
    public function delAttach($attachid)
    {
        $attachid = is_array($attachid)?implode(',',$attachid):$attachid;
        $info       = D('Attach')->where("id in ({$attachid})")->setField('status',0);
        return true;
    }
    //获取指定记录的数据
    public function getAttachInfo($attachid)
    {
        $attachid   = is_array($attachid)?implode(',',$attachid):$attachid;
        $info       = D('Attach')->where("id in ({$attachid})")
                                 ->field("id,savepath as path,savename as name,extension as ext,size,remark")
                                 ->select();
        return (count($info)>1)?$info:$info[0];
    }

    //检查指定的用户名是否可用
    public function ValidAccount($username)
    {
        if(empty($username))return false;
        $map = array();
        $map['account'] = $username;
        $result = D('User')->where($map)->find();
        return (!$result)?true:false;
    }
    //添加帐户
    public function addAcount($data)
    {
        $user = array(
            'login_count'=>0,
            'create_time'=>time(),
            'register_ip'=>get_client_ip(1),
            'status'=>0,
            'type_id'=>0,
        );
        $user = array_merge($user,$data);
        $result = array('status'=>0,'info'=>'帐号建立成功','data'=>'');
        if(!$this->ValidAccount($user['account'])) //如果存在该用户，则报错
        {
            $result['info'] = '用户已存在';
            return $result;
        }
        if(!isset($user['password']))
        {
            $user['password'] = md5('666666');//默认密码为6个6
        }else{
            $user['password'] = md5($user['password']);
        }
        $id = D('User')->add($user);
        if(!$id)
        {
            $result['info'] = '插入错误';
            return $result;
        }
        $result['status'] = 1;
        $result['data'] = $id;
        return $result;
    }
    /**
    +----------------------------------------------------------
    * 获取用户信息
    +----------------------------------------------------------
    * @access public
    +----------------------------------------------------------
    * @param  mix    $condition  查询条件
    +----------------------------------------------------------
    * @return array   符合条件的用户基本信息
    +----------------------------------------------------------
    */

    public function getUsers($condition='')
    {
        $map = array();//初始化查询条件

        if(!$condition)
        {
            if(is_string($condition)){
                parse_str($condition,$condition);//如果是字符串转化为数组
            }
            $condition  = array_filter($condition);//过滤非法数据
            $map        = $condition;
        }

        $map['status']  = 1;
        $map['account'] = array('neq','admin');
        $data           = M('User')->where($map)->select();

        return $data;
    }

    /**
    +----------------------------------------------------------
    * 获取指定用户的信息
    +----------------------------------------------------------
    * @access public
    +----------------------------------------------------------
    * @param  int    $userid  指定用户的userid
    +----------------------------------------------------------
    * @return array   用户的详细信息
    +----------------------------------------------------------
    */

    public function getUserInfo($userid)
    {
        $data   = M('User')->where('status =1 and id='.intval($userid))->find();

        return $data;
    }

    /**
    +----------------------------------------------------------
    * 获取指定用户的列表信息
    +----------------------------------------------------------
    * @access public
    +----------------------------------------------------------
    * @param  int    $userid  指定用户的userid
    +----------------------------------------------------------
    * @return array   用户的详细信息
    +----------------------------------------------------------
    */

    public function getUserList($condition='')
    {
        $map = array();//初始化查询条件

        if(!$condition)
        {
            if(is_string($condition)){
                parse_str($condition,$condition);//如果是字符串转化为数组
            }
            $condition  = array_filter($condition);//过滤非法数据
            $map        = $condition;
        }

        $map['status']  = 1;
        $map['account'] = array('neq','admin');
        $data           = M('User')->where($map)->getField('id,nickname');

        return $data;
    }
    //设置指定的用户字段
    public function setUserField($userid,$modify)
    {
        parse_str($modify,$modify);
        M('User')->where("id=".intval($userid))
                 ->setField(array_keys($modify),array_values($modify));
        return true;
    }

   /**
    +----------------------------------------------------------
    * 获取指定模块的日志信息
    +----------------------------------------------------------
    * @access public
    +----------------------------------------------------------
    * @param string  $condition     过滤条件
    * @param array   $config        指定查询条件
    +----------------------------------------------------------
    * @return array  返回指定的操作
    +----------------------------------------------------------
    */
    public function ReadLogs($condition='',$config=array())
    {
        $map = array();//初始化查询数组

        if(!empty($condition))
        {
            if(is_string($condition)){
                 parse_str($condition,$condition);//如果是字符串转化为数组
            }
            $condition  = array_filter($condition);//过滤非法数据
            $map        = array_merge($map,$condition);
        }
       $map['action']   = isset($config['action'])?$config['action']:ACTION_NAME;
       $map['module']   = isset($config['module'])?$config['module']:MODULE_NAME;
       $map['appname']  = isset($config['app'])?$config['app']
                                              :getAppName($_SESSION['_menuTag'],'id','name');

       return M('Log')->where($map)->order('create_time desc')->select();
    }

    /**
    +----------------------------------------------------------
    * 添加日志内容
    +----------------------------------------------------------
    * @access protected
    +----------------------------------------------------------
    * @param string  $title     日志标题
    * @param string  $content   备注内容
    * @param string  $userid    用户id
    * @param array   $config    配置
    +----------------------------------------------------------
    * @return boolean
    +----------------------------------------------------------
    */

    public function WriteLogs($title='',$content='',$userid='',$config=array())
    {
       //配置系统初始的action,module,appname
       if(empty($title) && empty($content)) return false;
       $data['action']      = isset($config['action'])?$config['action']:ACTION_NAME;
       $data['module']      = isset($config['module'])?$config['module']:MODULE_NAME;
       $data['appname']     = isset($config['app'])?$config['app']
                                                   :getAppName($_SESSION['_menuTag'],'id','name');
       $data['user_id']     = empty($userid)?$_SESSION[C('USER_AUTH_KEY')]:$userid;
       $data['create_time'] = time();
       $data['title']       = $title;
       $data['content']     = $content;
       $data['status']      = 1;

       return M('Log')->add($data);
    }
}

?>