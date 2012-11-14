<?php
/**
  +------------------------------------------------------------------------------
  * Ucenter 接口
  +------------------------------------------------------------------------------
  * @author Elser  2010-12-25 14:19:21
  +------------------------------------------------------------------------------
 */
 class Uc extends Think
{
    /**
    +----------------------------------------------------------
    * 调用uc内的接口方法
    +----------------------------------------------------------
    * @param string  $func
    * @param integer $params
    +----------------------------------------------------------
    */
    public function Ucall($func , $params=null)
    {
	    restore_error_handler();
	    if(!function_exists($func))
        {
	        include_once(C('UC_CLIENT_PATH'). 'client.php');
	    }
	    $_result= call_user_func_array($func, $params);
	    set_error_handler('exception_handler');
	    return $_result;
	}


    /**
    +----------------------------------------------------------
    * 验证用户注册名
    +----------------------------------------------------------
    */
    public function checkUserName($uName)
    {
		$_tempResult = $this->Ucall('uc_user_checkname',array($uName));

        switch ($_tempResult)
        {
            case '1':
                $result = array('type'=>1,'msg'=>'成功');
                break;
            case '-2':
                $result = array('type'=>0,'msg'=>'包含要允许注册的词语');
                break;
            case '-3':
                $result = array('type'=>0,'msg'=>'用户名已经存在');
                break;
            default:
                $result = array('type'=>0,'msg'=>'未知错误');
        }
		return $result;
	}
   /**
    +----------------------------------------------------------
    * 检查邮箱是否可用
    +----------------------------------------------------------
    */
    public function checkEmail($email)
    {
		$Result = $this->Ucall('uc_user_checkemail',array($email));
        return ((int)$Result<0)?false:true;
	}
    /**
     +----------------------------------------------------------
     * 同步登录
     +----------------------------------------------------------
     */
     public function synclogin($username, $password,$loaction=false)
     {
        $_tempResult = $this->Ucall('uc_user_login',array($username,$password));
        switch ($_tempResult[0])
        {
            case '-1':
                $result = array('type'=>0,'msg'=>'不存在该用户');
                break;
            case '-2':
                $result = array('type'=>0,'msg'=>'密码错误');
                break;
            case '-3':
                $result = array('type'=>0,'msg'=>'安全提问出错');
                break;
            default:
                $result = array('type'=>1,'msg'=>'登录成功','data'=>$_tempResult[0]);
                cookie('current_user_id',$_tempResult[0]);//在cookie中记录用户的ucenter 的id
                $data   = $this->Ucall('uc_user_synlogin',array($_tempResult[0]));//同步登录系统平台
                if ($loaction)
                {
                    $result['data'] = $data;
                }else{
                    if ($data!='') echo $data;//如果同步成功的话，则自动调用系统平台。
                }
                break;
        }
		return $result;
     }
    /**
     +----------------------------------------------------------
     * 同步登录
     +----------------------------------------------------------
     */
     public function synclogout()
     {
        $_tempResult = $this->ucall('uc_user_synlogout',array());
        if ($_tempResult!='') echo $_tempResult;
     }
   /**
    +----------------------------------------------------------
    * 添加用户
    +----------------------------------------------------------
    */
     public function addUser($username, $password, $email)
     {
        $_tempResult = $this->Ucall('uc_user_register',array($username,$password,$email));
        if ($_tempResult>0)
        {
            $result = array('type'=>1,'msg'=>'注册成功','data'=>$_tempResult);
        }else{
            $result = array('type'=>0,'msg'=>'注册失败');
        }
		return $_tempResult;
     }
    /**
     +----------------------------------------------------------
     * 获取用户信息
     +----------------------------------------------------------
     */
    public function getUserInfo($username,$avatar_type='small'){
       $_tempResult = $this->Ucall('uc_get_user',array($username));
       if (!$_tempResult) return false;
       $result = array('id'=>(int)$_tempResult[0],'nickname'=>$_tempResult[1],'email'=>$_tempResult[2]);
       $result['avatar'] = "<img src='".C('UC_API')."/avatar.php?uid=".$result['id']."&size=".$avatar_type."' style='user_avatar' />";
       return $result;
    }
    /**
     +----------------------------------------------------------
     * 修改用户帐号信息
     +----------------------------------------------------------
     */
    public function editUserInfo($username, $oldpw, $newpw, $email){
        $result = $this->Ucall('uc_user_edit',array($username,$oldpw,$newpw,$email,1));//1忽略旧密码
        return ($result<0)?false:true;
    }
    /**
     +----------------------------------------------------------
     * 修改用户头像
     +----------------------------------------------------------
     */
    public function changeUserAvatar($username=''){
        $username = empty($username)?cookie('current_user_id'):$username;
        if (is_string($username))
        {
            $userinfo = $this->getUserInfo($username);
            if (false===$userinfo) return false;
            $username = $userinfo['id'];
        }
        $result = $this->Ucall('uc_avatar',array($username));
        return $result;
    }
	/**
     +----------------------------------------------------------
     * 删除用户
     +----------------------------------------------------------
     */
	public function deleteuser($name){
        $this->Ucall('uc_user_delete',array($name));
	}

	 /**
     +----------------------------------------------------------
     * 获取短消息
     +----------------------------------------------------------
     */
	public function get_msgnum($uid,$more){
		$num  = $this->Ucall('uc_pm_checknew',array($uid,0));
		return 	$num;
	}

    /**
     +----------------------------------------------------------
     * 发送短消息
     +----------------------------------------------------------
     */
	public function sendmsg($from,$to,$subject,$message){
		$id  = $this->Ucall('uc_pm_send',array($from,$to,$subject,$message));
		return 	$id;
	}

	 /**
     +----------------------------------------------------------
     * 获取消息列表
     +----------------------------------------------------------
     */
	public function get_announce($uid){
		$data  = $this->Ucall('uc_pm_list',array($uid,1,20,'inbox','newpm',100));
		/*
		if($data['count']){
			return 	$data['data'][0]['subject'];
		}
		*/
		return $data;
	}
	 /**
     +----------------------------------------------------------
     * 查看用户的短消息
     +----------------------------------------------------------
     */
	public function viewmsg($uid,$msgid){
		$data  = $this->Ucall('uc_pm_view',array($uid,$msgid));
        return $data;
	}

	 /**
     +----------------------------------------------------------
     * 删除用户的短消息
     +----------------------------------------------------------
     */
	public function delmsg($uid,$msgid){
		$data  = $this->Ucall('uc_pm_delete',array($uid,'inbox',$msgid));
        return $data;
	}

	//编辑头像
	public function edit_avatar($uid,$type){
		$result = $this->Ucall('uc_avatar',array($uid,$type,1));
		return $result;
		}
}
?>