<?php
//模块
class IndexAction extends CommonAction{

	public function index(){

        if ($_SESSION['__account__'])
        {
          import('@.ORG.Uc'); //uhome同步登陆
          $Uc = new Uc();
          $data = $Uc->synclogin( $_SESSION['__account__'],strrev($_SESSION['__password__']),true);
          unset($_SESSION['__account__'],$_SESSION['__password__']);
          if ($data['data']!='')
          {
            $this->assign('synlogin',$data['data']);
          }
        }
		if($_SESSION['uc_ava']){ //更新头像 短消息 公共
			import('@.ORG.Uc');
			$Uc     = new Uc();
			$name   = $_SESSION['account'];
			$result = $Uc->getUserInfo($name);
			$_SESSION['uc_uid'] = $result['id'];
			$_SESSION['uc_ava'] = $result['avatar'];
			$_SESSION['msg_num']=$Uc->get_msgnum($_SESSION['uc_uid'],0);
			$_SESSION['announce']=$Uc->get_announce($_SESSION['uc_uid']);
		}
		$model   = M("Seckill");
		$miaosha = $model->where("ktype=2")->order("id desc")->find();
		$this->assign("miaosha",$miaosha);
		$this->display();
	}



    //****************************************************************************************************
    //添加的函数
    protected function userinfo($username,$userpass,$userinfo){

          $_SESSION['__account__'] =$username;
          $_SESSION['__password__'] = strrev($userpass);
    	  $_SESSION[C('USER_AUTH_KEY')] = $userinfo['id'];
          $_SESSION['authid']   = $userinfo['id'];
          $_SESSION['account'] = $userinfo['account'];
		  $_SESSION['score'] = $userinfo['score'];
          $_SESSION['email'] = $userinfo['email'];
          $_SESSION['loginUserName'] = $userinfo['account'];
          $_SESSION['lastLoginTime'] = $userinfo['last_login_time'];
          $_SESSION['login_count'] =$userinfo['login_count'];
		  $_SESSION['type_id']=$userinfo['type_id'];
		  $_SESSION['sql']=$this->getActionName();
            if ($userinfo['type_id'] == 0||$userinfo['type_id']==1) {
                $_SESSION[C('ADMIN_AUTH_KEY')] = true;
                }
			if ($userinfo['type_id'] == 0) {
                $_SESSION['usertype'] = "管理员";
				}else{
					$_SESSION['usertype'] = "商家";
				}

			//获取UC用户信息
			import('@.ORG.Uc');
			$Uc     = new Uc();
			$name   =$username;
			$result =$Uc->getUserInfo($name);
			$_SESSION['uc_uid'] = $result['id'];
            $_SESSION['uc_ava'] = $result['avatar'];
			$_SESSION['msg_num']=$Uc->get_msgnum($_SESSION['uc_uid'],0);
            //保存登录信息
            $User = M('User');
            $ip = get_client_ip();
            $time = time();
            $data = array();
            $data['id'] = $userinfo['id'];
            $data['last_login_time'] = $time;
            $data['login_count'] = array('exp', 'login_count+1');
            $data['last_login_ip'] = $ip;
            $User->save($data);
        }
     //****************************************************************************************************
     //修改的END

  public function checkLogin() {
        if (empty($_POST['account'])) {
            $this->error('帐号错误！');
        } elseif (empty($_POST['password'])) {
            $this->error('密码必须！');
        }
        //生成认证条件
        $map = array();
        // 支持使用绑定帐号登录
        $map['account'] = $_POST['account'];
        $map["status"] = array('gt', 0);
        $authInfo=M("User")->where($map)->find();
        if (false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        } else {
            if ($authInfo['password'] != md5($_POST['password'])) {
                $this->error('密码错误！');
            }
             //修改的××××××××××××××××××××××××××××××××××××××××××××××××××××××
             $this->userinfo($_POST['account'],$_POST['password'],$authInfo);
            //修改的end×××××××××××××××××××××××××××××××××××××××××××××××××××
            // 缓存访问权限
             $this->assign("jumpUrl", __URL__ . '/reg');
             $this->success('登录成功！');

        }
    }

			    // 用户登出
    public function logout() {
        if (isset($_SESSION[C('USER_AUTH_KEY')])){
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
            $this->assign("jumpUrl", __URL__ . '/index/');
            $this->success('登出成功！');
        } else {
            $this->error('已经登出！');
        }
    }


	public function reg(){
	        $this->assign('register',0);
         if($_SESSION[C('USER_AUTH_KEY')]){
              $map['account']=$_SESSION['account'];
              $register=M("User")->where($map)->find();
           if($register['userinfo_if']!=1)
             {
      	      // 载入上传照片函数
                import('@.ORG.Uc');
        		$Uc   = new Uc();
        		$avar = $Uc->edit_avatar($_SESSION['uc_uid'],"virtual");
        		$this->assign("avar",$avar);
                $this->assign('userid',$register['id']);
                $this->assign('register',1);
             }elseif($register['mobile_if']==0||$register['email_if']==0||empty($register['card_id'])){
                $this->assign('userid',$register['id']);
                $this->assign('mobile',$register['mobile']);
                $this->assign('email',$register['email']);
                $this->assign('register',2);
             }else{
                $this->assign('jumpUrl',__GROUP__);
			    $this->success('您已登录！');
             }
    	}
		$this->display();
	}

    public function update(){

         $userupdate=M('User');
          if(empty($_REQUEST['realname'])){
                 $this->error('请填写真实姓名！');
            }elseif(empty($_REQUEST['mobile'])){
                  $this->error('请填写手机号码！');
            }elseif(empty($_REQUEST['address'])){
                  $this->error('请填写地址！');
            }elseif(empty($_REQUEST['zip_code'])){
                  $this->error('请填写邮编！');
            }elseif(empty($_REQUEST['birthday'])){
                  $this->error('请填写生日！');}
            if($userupdate->create()){
                $userupdate->save();
            }else{
                $this->error($userupdate->getError());
            }
           	$model  = M("Config");
			$config = $model->find();
		    $score  = (int)$config['info_if'];

			//增加积分
			$data['score']=array("exp","score+$score");
            $map['id']=intval($_REQUEST['id']);
			$userupdate->where($map)->save($data);
		    $_SESSION['score'] = $_SESSION['score'] + $score;

			//积分记录
			$m = M("Pointlog");
			$dat["user_id"]=$id;
			$dat["ptype"]=0;
			$dat["tpoint"]=$_SESSION['score'];
			$dat["why"]   ="完善积分";
			$dat["point"] =$score;
			$dat["log_time"]=time();
            $m->add($dat);
            $this->redirect( __URL__ . '/reg');
    }
    //××××××××××××××××××××××××××××××××××××××××××END××××××××××××××××××××××××××××××××××××××××××××××××××××××××/

	public function useradd(){//快速注册信息处理
		$user = D("User");
        $_POST['type_id'] = 2; //强制用户类型为普通用户
		if(!$user->create()){
			$this->error($user->getError());
		}
		$id=$user->add();
		if($id){
			//uc同步注册
			import('@.ORG.Uc');
			$Uc = new Uc();
			$name=$_REQUEST["account"];
			$pwd=$_REQUEST["password"];
			$email=$_REQUEST["email"];
			$d =$Uc->addUser($name,$pwd,$email);
			//积分记录
			$config = M("Config")->find();
			$m = M("Pointlog");
			$dat["user_id"] = $id;
			$dat["ptype"]   = 0;
			$dat["tpoint"]  = $config['reg_if'];
			$dat["why"]     = "注册获得积分";
			$dat["point"]   = $config['reg_if'];
			$dat["log_time"]= time();
			$m->add($dat);
            $userinfo['id'] =intval($id);
            $userinfo["status"] = array('gt', 0);
            //修改的×××××××××××××*******************************************
            $info=M("User")->where($userinfo)->find();
            $this->userinfo($name,$pwd,$info);
		    $this->redirect( __URL__ . '/reg');
            //修改的end××××××××××××********************************************
		}else{
			 $this->error('注册失败，请检查输入项！');
		}
	}

	public function checkname(){
		$str = trim($_REQUEST['str']);
		$list=M("User")->where("account='".$str."'")->find();
		if($list){
			echo "<font color='red'>用户名已存在</font>";
		}else{
			echo "<font color='green'>用户名可以使用</font>";
		}
	}
}
?>