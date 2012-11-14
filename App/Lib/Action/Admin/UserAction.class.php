<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class UserAction extends CommonAction {

	function index(){
		$model=D("User");
		 //排序字段 默认为主键名
        if (isset($_REQUEST ['_order'])) {
            $order = $_REQUEST ['_order'];
        } else {
            $order = $model->getPk();
        }
        //排序方式默认按照倒序排列
        if (isset($_REQUEST ['_sort'])) {
			$sort = $_REQUEST['_sort'];
			if($sort == 'asc'){
			$this->assign('sort','desc');
			} else{
			$this->assign('sort','asc');
			}
        } else {
            $sort = 'desc';
			$this->assign('sort',$sort);
        }
		if($_REQUEST['resultid']!=""){  //结果排序
			$order=$_REQUEST['resultid'];
			$sort=$_REQUEST['sortid'];
			$this->assign("resultid",$_REQUEST['resultid']);
			$this->assign("sortid",$_REQUEST['sortid']);
			}
			if($_REQUEST["clist"]!=""){
				$value = array();
				$value = $_REQUEST['clist'];
				$_SESSION["uvalue"]=$value;
			}
			if(!$_SESSION["uvalue"]){
					$this->assign("menucard_id",1);
					$this->assign("menuaddress",1);
					$this->assign("menupoint",1);
					$this->assign("menuprints",1);
					$this->assign("menucollect",1);
					$this->assign("menutype_id",1);
					$this->assign("menumoney",1);
					$this->assign("menucreate_time",1);
					$this->assign("menuremark",1);
			}
			if($_SESSION["uvalue"]){
					foreach($_SESSION["uvalue"] as $k=>$v){
					$this->assign("menu".$v,1);
					}
			}
		$sql="1=1";
		if($_REQUEST['id']!=''){
			 $sql=$sql." and id=$_REQUEST[id]";
			 $this->assign("id",$_REQUEST['id']);
		}
		if($_REQUEST['account']!=''){
		     $sql=$sql." and account like'%".$_REQUEST['account']."%'";
			 $this->assign('account',$_REQUEST['account']);
		}
		if($_REQUEST['realname']!=''){
		     $sql=$sql." and realname like'%".$_REQUEST['realname']."%'";
			 $this->assign('realname',$_REQUEST['realname']);
		}
		if($_REQUEST['userinfo_if']!=''){
		     $sql=$sql." and userinfo_if =$_REQUEST[userinfo_if]";
			 $this->assign('userinfo_if',$_REQUEST['userinfo_if']);
		}
		if($_REQUEST['card_if']=='0'){
		     $sql=$sql." and card_id =''";
			 $this->assign('card_if',$_REQUEST['card_if']);
		}
		if($_REQUEST['card_if']=='1'){
		     $sql=$sql." and card_id <>''";
			 $this->assign('card_if',$_REQUEST['card_if']);
		}
		if($_REQUEST['type_id']!=''){
		     $sql=$sql." and type_id =$_REQUEST[type_id]";
			 $this->assign('type_id',$_REQUEST['type_id']);
		}
		if($_REQUEST['email_if']!=''){
		     $sql=$sql." and email_if =$_REQUEST[email_if]";
			 $this->assign('email_if',$_REQUEST['email_if']);
		}
		if($_REQUEST['mobile_if']!=''){
		     $sql=$sql." and mobile_if =$_REQUEST[mobile_if]";
			 $this->assign('mobile_if',$_REQUEST['mobile_if']);
		}
		if($_REQUEST['wap_if']!=''){
		     $sql=$sql." and wap_if =$_REQUEST[wap_if]";
			 $this->assign('wap_if',$_REQUEST['wap_if']);
		}
		if($_REQUEST['money1']!=''){
		     $sql=$sql." and score >=$_REQUEST[money1]";
			 $this->assign('money1',$_REQUEST['money1']);
		}
		if($_REQUEST['money2']!=''){
		     $sql=$sql." and score <=$_REQUEST[money2]";
			 $this->assign('money2',$_REQUEST['money2']);
		}
		if($_REQUEST['time1']!=''){
		     $time1=strtotime($_REQUEST['time1']);
		     $sql=$sql." and create_time >=$time1";
			 $this->assign('time1',$_REQUEST['time1']);
		}
		if($_REQUEST['time2']!=''){
		     $time2=strtotime($_REQUEST['time2'])+60*60*24;
		     $sql=$sql." and create_time <$time2";
			 $this->assign('time2',$_REQUEST['time2']);
		}
		if($_REQUEST['status']!=''){
			if($_REQUEST['status']==3){
			}else{
				$sql=$sql." and status =$_REQUEST[status]";
				}
			 $this->assign('status',$_REQUEST['status']);
		}else{
			$sql=$sql." and status >-2";
			}
		if($_SESSION['sql']!=$this->getActionName()){
		   $_SESSION['sql']=$this->getActionName();
		  $_SESSION["user_sql"]=$sql;
		}
        if($_REQUEST['flag']){  //保存状态
		   $_SESSION["user_sql"]=$sql;
		}
        $count = $model->where($_SESSION["user_sql"])->count('id');
		if($_REQUEST['submit']=='exp'){
			$downlist=$model->where($_SESSION["user_sql"])->select();
			$this->user_down($downlist);
		}
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
				if($order=="point"||$order=="prints"||$order=="collect"){
			      $userlist=$model->where($_SESSION["user_sql"])->limit($p->firstRow . ',' . $p->listRows)->findAll();
				   }else{
				  $userlist=$model->where($_SESSION["user_sql"])->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
				   }
			$page=$p->show();
			$order = $_REQUEST ['_order'];
			if($_SESSION['t']!=$this->getActionName()){
			   $_SESSION['t']=$this->getActionName();
			   $_SESSION['tt']=0;
			  $_SESSION['tt1']=0;
			  $_SESSION['tt2']=0;
			}
			if($_REQUEST['flag']){  //保存状态
			 $_SESSION['tt1']=0;
			 $_SESSION['tt2']=0;
		    }
			if($_REQUEST["tt"]!=''){
			  $_SESSION['tt']=$_REQUEST["tt"];
//			    if($_REQUEST["tt"]==11){  //今天
//					$d=date("Y-m-d",time());
//					$tt1=strtotime($d);
//					$tt2=$tt1+3600*24;
//				}elseif($_REQUEST["tt"]==22){//昨天
//					$d=date("Y-m-d",time());
//					$tt2=strtotime($d);
//					$tt1=$tt2-3600*24;
//				}elseif($_REQUEST["tt"]==33){//本周
//					$d1=date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
//					$d2=date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
//					$tt1=strtotime($d1);
//					$tt2=strtotime($d2);
//				}elseif($_REQUEST["tt"]==44){//上周
//					$d=date("Y-m-d",time());
//					$tt2=strtotime($d);
//					$tt1=$tt2-3600*24;
//				}elseif($_REQUEST["tt"]==55){//本月
//					$d=date("Y-m-01",time());
//					$tt1=strtotime($d);
//					$tt2=time();
//				}elseif($_REQUEST["tt"]==66){//上月
//					$d1=date('Y-m-01',strtotime(date('Y',time()).'-'.(date('m',time())-1).'-01'));
//					$d2=date('Y-m-d',strtotime("$d1 +1 month -1 day"));
//					$tt1=strtotime($d1);
//					$tt2=strtotime($d2);
//				}else{
				$tt1=time()-3600*24*$_REQUEST["tt"];
				$tt2=time();
//					}
			  $_SESSION['tt1']=$tt1;
			  $_SESSION['tt2']=$tt2;
			}
			$this->assign("tt",$_SESSION['tt']);
			if($_REQUEST["tt1"]!=''){
			$tt1=strtotime($_REQUEST["tt1"]);
			$_SESSION['tt1']=$tt1;
			}else{
			$tt1=0;
			}
			if($_SESSION['tt1']==0){
			$this->assign('tt1',"");
			}else{
			$this->assign('tt1',date("Y-m-d",$_SESSION['tt1']));
			}
			if($_REQUEST["tt2"]!=''){
			$tt2=strtotime($_REQUEST["tt2"]);
			$_SESSION['tt2']=$tt2;
			}else{
			$tt2=0;
			}
			if($_SESSION['tt2']==0){
			$this->assign('tt2',"");
			}else{
			$this->assign('tt2',date("Y-m-d",$_SESSION['tt2']));
			}
			foreach($userlist as $k=>$val){
			  $val["point"]=ticket_count_byuser($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],0);
			  $val["prints"]=ticket_count_byuser($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],1);
			  $val["collect"]=ticket_count_byuser($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],2);
			  $userlist[$k]=$val;
			  if($order=="point"){
			  $r[]=$val['point'];
			  }
			  if($order=="prints"){
			  $r[]=$val['prints'];
			  }
			  if($order=="collect"){
			  $r[]=$val['collect'];
			  }
			}
			if($order=="point"||$order=="prints"||$order=="collect"){
			   $sort=$_SESSION["sort"]==SORT_DESC?SORT_ASC:SORT_DESC;
			   $_SESSION["sort"]=$sort;
			   $out = array_multisort($r,$sort,$userlist);
			}
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
	}

//ajax修改备注和用户组
    public function aupdate() {
        $model = M("User");
		$arr = $_REQUEST;
        $id = $_REQUEST ['id'];
        $data['remark'] = $_REQUEST ['remark'];
        $model->where("id=$id")->save($data);
    }
        // 更新数据
    public function update() {
		$model = M("User");
		$id=$_REQUEST["id"];
		$data["account"]=$_REQUEST["account"];
		$data["nickname"]=$_REQUEST["nickname"];
		if($_REQUEST["birthday"]!=''){
				$data["birthday"]=strtotime($_REQUEST["birthday"]);
			}
		$data["address"]=$_REQUEST["address"];
		$data["email"]=$_REQUEST["email"];
		$data["mobile"]=$_REQUEST["mobile"];
		$data["phone"]=$_REQUEST["phone"];
		$data["card_id"]=$_REQUEST["card_id"];
		$data["type_id"]=$_REQUEST["type_id"];
		$data["status"]=$_REQUEST["status"];
		$data["remark"]=$_REQUEST["remark"];
		$data["update_time"]=time();
        $list = $model->where("id=$id")->save($data);
			if(false != $list){
			  import('@.ORG.Uc');   //与UC同步
			  $Uc = new Uc();
			  $data = $Uc->editUserInfo($_REQUEST["account"],'',$_POST['password'],$vo['email']);
			  $this->_writelog("更新数据",$data["account"]);
			  $this->success('编辑成功！');
			}else{
			  $this->error('编辑失败！');
			}
    }

    // 检查帐号
    public function checkAccount() {
        if (!preg_match('/^[a-z]\w{4,}$/i', $_POST['account'])) {
            $this->error('用户名必须是字母，且5位以上！');
        }
        $User = M("User");
    // 检测用户名是否冲突
        $name = $_REQUEST['account'];
        $result = $User->getByAccount($name);
        if ($result) {
            $this->error('该用户名已经存在！');
        } else {
            $this->success('该用户名可以使用！');
        }
    }

    // 插入数据
    public function insert() {
        // 创建数据对象
        $User = M("User");
        $name = $_REQUEST['account'];
        $result = $User->getByAccount($name);
        if ($result) {
            $this->error('该用户名已经存在！');
        } else {
            $data["account"]=$_REQUEST["account"];
			$data["password"]=md5($_REQUEST["password"]);
			$data["nickname"]=$_REQUEST["nickname"];
			if($_REQUEST["birthday"]!=''){
			$data["birthday"]=strtotime($_REQUEST["birthday"]);
			}
			$data["address"]=$_REQUEST["address"];
			$data["email"]=$_REQUEST["email"];
			$data["mobile"]=$_REQUEST["mobile"];
			$data["phone"]=$_REQUEST["phone"];
			$data["card_id"]=$_REQUEST["card_id"];
			$data["type_id"]=$_REQUEST["type_id"];
			$data["status"]=$_REQUEST["status"];
			$data["remark"]=$_REQUEST["remark"];
			$data["create_time"]=time();
            if ($result = $User->add($data)) {
				$this->_writelog("添加用户",$data["account"]);
				
				//UC同步添加用户
				import('@.ORG.Uc');
                $Uc = new Uc();
				$name=$_REQUEST["account"];
				$pwd=$_REQUEST["password"];
				$email=$_REQUEST["email"];
				$d =$Uc->addUser($name,$pwd,$email);
				$this->assign("jumpUrl","__URL__");
                $this->success('用户添加成功！');
            } else {
                $this->error('用户添加失败！');
            }
        }
    }


    //重置密码
    public function resetPwd() {
        $id       = $_REQUEST['id'];
        $password = $_REQUEST['password'];
        $User     = M('User');
        $data["password"] = md5($password);
        $User->where("id=$id")->save($data);
		$vo=$User->where("id=$id")->find();
		import('@.ORG.Uc');   //与UC同步
		$Uc = new Uc();
		$data = $Uc->editUserInfo($vo['account'],$vo['password'],$_POST['password'],$vo['email']);
		$this->_writelog("重置密码","用户id".$id);
    }


//资料导出
function user_down($data){
	$filename =date('y年-m月-d日')."——会员资料";
	$filename=mb_convert_encoding($filename,'gb2312','utf-8');
	$title = array(
		'id' => 'ID',
		'account' => '用户名',
		'nickname' => '昵称',
		'realname' => '真实姓名',
		'register_ip'=>'注册IP',
		'last_login_time'=>'最后登录时间',
		'last_login_ip'=>'最后登录IP',
		'reg_ip'=>'注册IP',
		'login_count'=>'登录次数',
		'verify'=>'验证码',
		'create_time'=>'创建时间',
		'update_time'=>'更新时间',
		'card_id' => '绑定的卡编号',
		'type_id' => '用户类型',
		'mobile' => '手机',
		'phone' => '电话',
		'email' => '邮箱',
		'birthday' => '生日',
		'sex' => '性别',
		'region'=>'所在地区',
		'address' => '地址',
		'zip_code' => '邮编地址',
		'email_if'=>'是否邮箱验证',
		'mobile_if'=>'是否手机验证',
		'wap_if' => '是否登录WAP网站',
		'userinfo_if'=>'是否完善用户信息',
		'remark' => '备注',
		'score' => '积分',
	);
	foreach($data as $k=>$val){
		 $val['last_login_time']=date("Y年-m月-d日：H-i-s",$val['last_login_time']);
		 $val['create_time']=date("Y年-m月-d日：H-i-s",$val['create_time']);
		 $val['update_time']=date("Y年-m月-d日：H-i-s",$val['update_time']);
		 $val['birthday']=date("Y年-m月-d日",$val['birthday']);
		 $val['sex']=$val['sex']==0?"男":"女";
		 $val['email_if']=$val['email_if']==0?"否":"是";
		 $val['mobile_if']=$val['mobile_if']==0?"否":"是";
		 $val['wap_if']=$val['wap_if']==0?"否":"是";
		 $val['userinfo_if']=$val['userinfo_if']==0?"否":"是";
		 $data[$k]=$val;
		}
	import("@.ORG.Down");
	Down::down_xls($data,$title,$filename);
	}


	//邮件群发
    function email($data,$title,$subject,$body){
	import("@.ORG.PHPMailer");
	import("@.ORG.SMTP");
	$model=M("Config");
	$config=$model->field('stmp_server,stmp_username,stmp_password,stmp_send,stmp_back,stmp_subject,stmp_body,stmp_fromname')->find();
	$i=0;
	foreach($data as $k=>$v){
	  if(preg_match("/^[a-zA-Z0-9_]+@[a-zA-Z0-9-]+.[a-zA-Z0-9]+$/",$v['email'])){
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP();
		$mail->Host = $config['stmp_server'];
		$mail->SMTPAuth = TRUE;
		$mail->Username = $config['stmp_username'];
		$mail->Password =$config['stmp_password'];
		$mail->From = $config['stmp_username'];
		$mail->FromName = $title;
		$mail->Subject = $subject;
		$mail->Body = $body;
		$address = $v['email'];
		$mail->AddAddress("$address",$v['account']);
		$mail->Send();
		$i=$i+1;
		}
	  }
	  return $i;
   }


	  public function showcount(){
	   $model  = M("Ticket_statistic");
	   $typeid = $_REQUEST["type"];
	   $uid    = $_REQUEST["id"];
	   if($typeid==2){
			$cm=M("Collect");
			$count=$cm->where("user_id=$id")->count("id");
			if($count > 0){
			import("@.ORG.Page");
				$p=New Page($count,C('PAGE_LIST_ROWS'));
				$list=$cm->where("user_id=$id")->limit($p->firstRow . ',' . $p->listRows)->findAll();
				$page=$p->show();
				$this->assign('list',$list);
				$this->assign('page',$page);
			}
		}else{
		   $sql="user_id=$uid and action_type=$typeid";
		   $count = $model->where($sql)->count('id');
		   if($count > 0){
			import("@.ORG.Page");
				$p=New Page($count,C('PAGE_LIST_ROWS'));
				$list=$model->where($sql)->limit($p->firstRow . ',' . $p->listRows)->findAll();
				$page=$p->show();
				$this->assign('list',$list);
				$this->assign('page',$page);
			}
		}
	   $this->display();
	   }

   //审核
	function shh(){
		$model = M("User");
		$sql   = "status=0";
        $count = $model->where($sql)->count('id');
		if($_REQUEST['submit']=='导出'){
			$downlist=$model->where($sql)->select();
			$this->user_down($downlist);
		}
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->where($sql)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
	}

	  public function foreverdelete() {
		$id = $_REQUEST ["id"];
        $model = D("User");
        if (isset($id)) {
				$data['status'] = -1;
				$data['type_id']=2;
				$data['trade_id']="";
				$data['card_id']="";
				$data['update_time']=time();
                if (false !== $model->where("id=$id")->save($data)) {
					$this->_writelog("删除","记录ID号:$id");
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('非法操作');
            }
    }
		   //充值
	function addscore(){
		$model = M("User");
		$id    = $_REQUEST["id"];
		$conf=M("Config");
		$cvo =$conf->find();
		if($_REQUEST["addscore"]!=''){
			$score = $_REQUEST["score"];
			$d["score"]=$score+$_REQUEST["addscore"]*$cvo["money_rate"];
			$model->where("id=$id")->save($d);
			$m = M("Pointlog");
			$data["user_id"]=$id;
			$data["ptype"]=0;
			$data["tpoint"]=$d["score"];
			$data["why"]  ="管理员".get_user($_SESSION[C('USER_AUTH_KEY')])."手动充值 ".$_REQUEST["remark"];
			$data["point"]   =$_REQUEST["addscore"]*$cvo["money_rate"];
			$data["log_time"]=time();
			$m->add($data);
			}
		$vo    = $model->where("id=$id")->find();
		$this->assign("vo",$vo);
		$this->assign("rate",$cvo["money_rate"]);
		$this->display();
	}

	public function showscore(){
		$model = M("Pointlog");
		$id = $_REQUEST["id"];
		$mu=M("User");
		$user=$mu->where("id=$id")->find();
		$sql="user_id=$id";
		if($_REQUEST['time1']!=''){
		     $time1=strtotime($_REQUEST['time1']);
		     $sql=$sql." and log_time >=$time1";
			 $this->assign('time1',$_REQUEST['time1']);
		}
		if($_REQUEST['time2']!=''){
		     $time2=strtotime($_REQUEST['time2'])+60*60*24;
		     $sql=$sql." and log_time <$time2";
			 $this->assign('time2',$_REQUEST['time2']);
		}
		$count = $model->where("user_id=$id")->count('id');
	    if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$list=$model->where($sql)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
	   $this->assign('user',$user);
	   $this->assign('id',$id);
	   $this->display();
		}

	public function sendmail(){
		if($_REQUEST['submit']=='发送'){
			$model=M("User");
			$elist=$model->select();
			$count=$model->where("status=1")->count('id');
			$title=$_REQUEST["title"];
			$subject=$_REQUEST["subject"];
			$body=$_REQUEST["body"];
			$t1=time();
			$i=$this->email($elist,$title,$subject,$body);
			$t2=time();
			$t=$t2-$t1;
			$this->assign("waitSecond","4");
			$this->success("发送完毕！共发送成功".$i."个，失败".($count-$i)."个,用时".$t."秒");
		 }
		 $this->display();
	}
	
	//UC同步删除用户
	public function _before_delete(){
		import('@.ORG.Uc');
		$Uc     = new Uc();
		$name   = $_SESSION['account'];
		$Uc->deleteuser($name);
	}
}

?>