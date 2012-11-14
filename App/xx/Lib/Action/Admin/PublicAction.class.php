<?php
/**
  +------------------------------------------------------------------------------
  * 公共模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
 class PublicAction extends CommonAction {

    // 检查用户是否登录

    protected function checkUser() {
        if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->assign('jumpUrl', 'Admin/Public/login');
            $this->error('没有登录');
        }
    }

    // 顶部页面
    public function top() {
        C('SHOW_RUN_TIME', false);   // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        if($_SESSION['type_id']==0){
		$list=array(array('title'=>'日常审核','module'=>'User','menu'=>'shh','action'=>'shh'),
					array('title'=>'日志管理','module'=>'Error','menu'=>'logs'),
					array('title'=>'系统配置','module'=>'Config','menu'=>'config'),
					array('title'=>'秒杀管理','module'=>'Goods','menu'=>'seckill'),
					);
        $this->assign('nodeGroupList', $list);
		} elseif($_SESSION['type_id']==1){
		$list=array();
        $this->assign('nodeGroupList', $list);
			}
        $this->display();
    }

    // 尾部页面
    public function footer() {
        C('SHOW_RUN_TIME', false);   // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        $this->display();
    }

    // 菜单页面
    public function menu() {
        $this->checkUser();
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
				//显示菜单项
				if($_SESSION['type_id']==0){
				$menu=array(array('title'=>'用户管理','module'=>'User','action'=>'index'),
							array('title'=>'商家管理','module'=>'Trade','action'=>'index'),
							array('title'=>'优惠券管理','module'=>'Ticket','action'=>'index'),
							array('title'=>'展位租赁管理','module'=>'Tenancy','action'=>'index'),
							array('title'=>'会员卡管理','module'=>'Card','action'=>'index'),
							array('title'=>'机器放置地点','module'=>'Location','action'=>'index'),
                            array('title'=>'新闻分类管理','module'=>'Newclass','action'=>'index'),
							array('title'=>'新闻管理','module'=>'News','action'=>'index'),
						    array('title'=>'评论管理','module'=>'Comment','action'=>'index'),
                            array('title'=>'活动派发','module'=>'Huodong','action'=>'index'),
							array('title'=>'合作伙伴','module'=>'Partner','action'=>'index'),
							array('title'=>'个性卡片','module'=>'cards','action'=>'index'),
							//array('title'=>'秒杀兑换','module'=>'Seckill','action'=>'index'),
							array('title'=>'其他信息','module'=>'Info','action'=>'index'),
								  );
				$this->assign('menu', $menu);
				C('SHOW_RUN_TIME', false);   // 运行时间显示
				C('SHOW_PAGE_TRACE', false);
			    }  elseif($_SESSION['type_id']==1){
				$menu=array(array('title'=>'财务记录','module'=>'Mypaylist','action'=>'index'),
							array('title'=>'商家信息','module'=>'Mytrade','action'=>'edit'),
							array('title'=>'我的展位','module'=>'Myexh','action'=>'myexhib'),
							array('title'=>'挑选展位','module'=>'Myexh','action'=>'add3'),
							array('title'=>'我的优惠券','module'=>'Myticket','action'=>'index'),
								  );
				$this->assign('menu',$menu);
				C('SHOW_RUN_TIME', false);   // 运行时间显示
				C('SHOW_PAGE_TRACE', false);
				}
			$this->display();
		}
    }

    // 后台首页 查看系统信息
    public function main() {

		$today = strtotime(date("Y-m-d",time()));
		//用户数量
		$model = M("User");
		$usercount  = $model->where("status>-1")->count("id");
		$this->assign("usercount",$usercount);

		//待审核用户数量
		$model = M("User");
		$usercount_s  = $model->where("status=0")->count("id");
		$this->assign("usercount_s",$usercount_s);

		//今日打印数量
		$model = M("Ticket_statistic");
		$printcount  = $model->where("create_time>$today and action_type=1")->count("id");
		$this->assign("printcount",$printcount);
		//今日点击数量
		$model = M("Ticket_statistic");
		$pointcount  = $model->where("create_time>$today and action_type=0")->count("id");
		$this->assign("pointcount",$pointcount);
		//今日收藏数量
		$model = M("Ticket_statistic");
		$collectcount  = $model->where("create_time>$today and action_type=2")->count("id");
		$this->assign("collectcount",$collectcount);

		//商家数量
		$model = M("Trade");
		$tradecount  = $model->count("id");
		$this->assign("tradecount",$tradecount);

		//会员卡数量
		$model = M("Card");
		$cardcount  = $model->count("id");
		$this->assign("cardcount",$cardcount);

		//优惠券数量
		$model = M("Ticket");
		$ticketcount = $model->count("id");
		$ticketwait  = $model->where("status=0")->count("id");  //待审核
		$this->assign("ticketwait",$ticketwait);
		$this->assign("ticketcount",$ticketcount);

		//展位租赁数量
		$model = M("Tenancy");
		$tenancycount = $model->count("id");
		$tenancywait  = $model->where("status=0")->count("id"); //预约中
		$tenancyover  = $model->where("status=1 and close_time<$today+3600*24*7")->count("id"); //即将到期
		$this->assign("tenancyover",$tenancyover);
		$this->assign("tenancywait",$tenancywait);
		$this->assign("tenancycount",$tenancycount);

		//今日打印机错误报告
		$model = M("Error");
		$errorcount  = $model->where("log_time>$today")->count("id");
		$this->assign("errorcount",$errorcount);

		//今日充值记录
		$model = M("Paylist");
		$paycount  = $model->where("create_time>$today")->count("id");
		$this->assign("paycount",$paycount);

		//无法正常连接的机器
		$model = M("Location");
		$overtime=date("H",time());
		$time=time()-900;
		$overlist  = $model->where("link_time<$time")->select();
		$overlist2=array();
		$errormodel=M("Error");
		foreach($overlist as $k=>$v){
			if($v['open']<$overtime&&$v['close']>$overtime){
				$overlist2[$k]=$v;
				$error=$errormodel->where("mac_id='$v[macno]' and flag=1 and status=0")->find();
				if(!$error){
					$data['source']=$v['name'];
					$data['mac_id']=$v['macno'];
					$data['level']=1;
					$data['status']=0;
					$data['flag']=1;
					$data['error_content']="机器无法正常连接";
					$data['log_time']=time();
					$errormodel->add($data);
				  }else{
					$data['source']=$v['name'];
					$data['status']=0;
					$data['log_time']=time();
					$errormodel->where("mac_id='$error[mac_id]' and flag=1")->save($data);
				  }
			 }
		 }
		$this->assign("overlist",$overlist2);
		$errory=$errormodel->where("flag=0 and level=1 and status=0")->select();
		$this->assign("overlist2",$errory);


        $info = array(
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            'ThinkPHP版本' => THINK_VERSION . ' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            '剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            'register_globals' => get_cfg_var("register_globals") == "1" ? "ON" : "OFF",
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? 'YES' : 'NO',
        );
        $this->assign('info', $info);
        $this->display();
    }

   public function trade(){
	   $model=M("User_trade");
	   $userid=$_SESSION[C('USER_AUTH_KEY')];
	   $tradelist=$model->where("user_id=$userid")->select();
	   $str="(-1";
		  foreach($tradelist as $k=>$v){
			  $str=$str.",$v[trade_id]";
			  }
	   $str=$str.")";
	   $model=M("Tenancy");
	   $tenlist=$model->where("status=1 and trade_id in $str")->select();
	   if($_REQUEST['time1']!=""){
	   $time1=strtotime($_REQUEST['time1']);
	   $this->assign("time1",date("Y-m-d",$time1));
	   }else{
	   $time1=0;
		   }
	   if($_REQUEST['time2']!=""){
	   $time2=strtotime($_REQUEST['time2'])+3600*24;
	   $this->assign("time2",date("Y-m-d",$time2-3600*24));
	   }else{
	   $time2=0;
		   }
		$this->assign("time11",$time1);
		$this->assign("time22",$time2);
	   foreach($tenlist as $k=>$val){
		      $val["ticket_count"]=get_countinfobytenacy($val['trade_id'],$val['p_id'],$time1,$time2);
			  $tenlist[$k]=$val;
		   }
	   $this->assign("list",$tenlist);
	   $this->display();

	   }
    // 用户登录页面
    public function login() {
        if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/index');
        }
    }

    public function index() {
        //如果通过认证跳转到首页
        redirect(__APP__."/Admin");
    }

    // 用户登出
    public function logout() {
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
            $this->assign("jumpUrl", __URL__ . '/login/');
            $this->success('登出成功！');
        } else {
            $this->error('已经登出！');
        }
    }

    // 登录检测
    public function checkLogin() {
        if (empty($_POST['account'])) {
            $this->error('帐号错误！');
        } elseif (empty($_POST['password'])) {
            $this->error('密码必须！');
        } elseif (empty($_POST['verify'])) {
            $this->error('验证码必须！');
        }
        //生成认证条件
        $map = array();
        // 支持使用绑定帐号登录
        $map['account'] = $_POST['account'];
        $map["status"] = array('gt', 0);
        if ($_SESSION['verify'] != md5($_POST['verify'])) {
            $this->error('验证码错误！');
        }
       $authInfo=M("User")->where($map)->find();
        if (false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        } else {
            if ($authInfo['password'] != md5($_POST['password'])) {
                $this->error('密码错误！');
            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
            $_SESSION['__account__'] = $authInfo['account'];
            $_SESSION['__password__'] = strrev($_POST['password']);
            $_SESSION['account'] = $authInfo['account'];
            $_SESSION['email'] = $authInfo['email'];
            $_SESSION['loginUserName'] = $authInfo['account'];
            $_SESSION['lastLoginTime'] = $authInfo['last_login_time'];
            $_SESSION['login_count'] = $authInfo['login_count'];
			$_SESSION['type_id']=$authInfo['type_id'];
			$_SESSION['sql']=$this->getActionName();
            if ($authInfo['type_id'] == 0||$authInfo['type_id']==1) {
                $_SESSION[C('ADMIN_AUTH_KEY')] = true;
            }
			if ($authInfo['type_id'] == 0) {
                $_SESSION['usertype'] = "管理员";
            }else{
				$_SESSION['usertype'] = "商家";
				}
            //保存登录信息
            $User = M('User');
            $ip = get_client_ip();
            $time = time();
            $data = array();
            $data['id'] = $authInfo['id'];
            $data['last_login_time'] = $time;
            $data['login_count'] = array('exp', 'login_count+1');
            $data['last_login_ip'] = $ip;
            $User->save($data);

            // 缓存访问权限
            $this->success('登录成功！');
        }
    }

    // 更换密码
    public function changePwd() {
        $this->checkUser();
        //对表单提交处理进行处理或者增加非表单数据
        if (md5($_POST['verify']) != $_SESSION['verify']) {
            $this->error('验证码错误！');
        }
        $map = array();
        $map['password'] = pwdHash($_POST['oldpassword']);
        if (isset($_POST['account'])) {
            $map['account'] = $_POST['account'];
        } elseif (isset($_SESSION[C('USER_AUTH_KEY')])) {
            $map['id'] = $_SESSION[C('USER_AUTH_KEY')];
        }
        //检查用户
        $User = M("User");
		$list=$User->where($map)->field('id')->find();
        if (!$list) {
            $this->error('旧密码不符或者用户名错误！');
        } else {
            $User->password = pwdHash($_POST['password']);
            $User->save();
            import('@.ORG.Uc');
            $Uc = new Uc();
			$data = $Uc->editUserInfo($list['account'],$_POST['oldpassword'],$_POST['password'],$list['email']);
            $this->success('密码修改成功！');
        }
    }

    public function profile() {
        $this->checkUser();
        $User = M("User");
        $vo = $User->getById($_SESSION[C('USER_AUTH_KEY')]);
        $this->assign('vo', $vo);
        $this->display();
    }

    public function verify() {
        $type = isset($_GET['type']) ? $_GET['type'] : 'gif';
        import("@.ORG.Image");
        Image::buildImageVerify(4, 1, $type);
    }

// 修改资料
    public function change() {
        $this->checkUser();
        $User = D("User");
        if (!$User->create()) {
            $this->error($User->getError());
        }
        $result = $User->save();
        if (false !== $result) {
            $this->success('资料修改成功！');
        } else {
            $this->error('资料修改失败!');
        }
    }


	public function logs() {
	$this->checkUser();
	if (isset($_SESSION[C('USER_AUTH_KEY')])) {
			//显示菜单项
			$menu=array(array('title'=>'打印统计记录','module'=>'Ticket_statistic','action'=>'index'),
						array('title'=>'商家结算记录','module'=>'Trademoney','action'=>'index'),
						array('title'=>'打印机错误报告','module'=>'Error','action'=>'index'),
						array('title'=>'商家充值记录','module'=>'Paylist','action'=>'index'),
						array('title'=>'用户积分记录','module'=>'Addscore','action'=>'index'),
						array('title'=>'用户打印积分记录','module'=>'Pointlog','action'=>'index'),
						array('title'=>'后台操作日志','module'=>'Log','action'=>'index'),
							  );
			$this->assign('menu', $menu);
			C('SHOW_RUN_TIME', false);   // 运行时间显示
			C('SHOW_PAGE_TRACE', false);
			$this->display("Public:menu");
	   }
    }

	public function shh() {  //审核
	$this->checkUser();
	if (isset($_SESSION[C('USER_AUTH_KEY')])) {
			//显示菜单项
			$menu=array(array('title'=>'用户审核','module'=>'User','action'=>'shh'),
						array('title'=>'商家审核','module'=>'Trade','action'=>'shh'),
						array('title'=>'优惠券审核','module'=>'Ticket','action'=>'shh'),
						array('title'=>'展位租赁审核','module'=>'Tenancy','action'=>'shh'),
							  );
			$this->assign('menu', $menu);
			C('SHOW_RUN_TIME', false);   // 运行时间显示
			C('SHOW_PAGE_TRACE', false);
			$this->display("Public:menu");
	   }
    }
	public function config(){ //审核
	$this->checkUser();
	if (isset($_SESSION[C('USER_AUTH_KEY')])){
			//显示菜单项
			$menu=array(array('title'=>'系统配置','module'=>'Config','action'=>'index'),
						array('title'=>'卡相关配置','module'=>'Config','action'=>'card'),
						array('title'=>'商圈管理','module'=>'Area'),
						array('title'=>'产品类别','module'=>'Cate'),
						array('title'=>'标签配置','module'=>'Tag','action'=>'index'),
						);
			$this->assign('menu',$menu);
			C('SHOW_RUN_TIME', false);      //运行时间显示
			C('SHOW_PAGE_TRACE', false);
			$this->display("Public:menu");
	   }
    }
	public function seckill(){ //秒杀
	$this->checkUser();
	if (isset($_SESSION[C('USER_AUTH_KEY')])){
			//显示菜单项
			$menu=array(array('title'=>'商 品  管 理','module'=>'Goods','action'=>'index'),
						array('title'=>'秒杀结束商品','module'=>'Goodsover','action'=>'index'),
						array('title'=>'商品分类管理','module'=>'Classify','action'=>'index'),
						);
			$this->assign('menu',$menu);
			C('SHOW_RUN_TIME', false);      //运行时间显示
			C('SHOW_PAGE_TRACE', false);
			$this->display("Public:menu");
	   }
    }

	public function delcache(){
	 $dir=dirname(dirname(dirname(dirname(__FILE__))))."/Runtime";
     deldir($dir);
	 $this->assign("jumpUrl", __URL__ .'/main');
	 $this->success('成功清除缓存！');
	}
}

?>