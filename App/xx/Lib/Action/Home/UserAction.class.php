<?php
//模块
class UserAction extends CommonAction{

    //优惠券收藏
    public function index(){
		$ticket = D("User")->ticketcol();  //优惠券收藏
		$this->assign("list",$ticket);
		$this->display();
		}

    //商家收藏
    public function trade(){
		$trade  = D("User")->tradecol();   //优惠商家收藏
		$this->assign("list",$trade);
		$this->display();
		}

	//我的资料
    public function info(){
		$vo = D("User")->getinfo();
		$this->assign("vo",$vo);
		$this->display();
	}


	//我的积分
    public function score(){
		$vo = D("User")->getscore();
		$this->assign("list",$vo['list']);
		$this->assign("count",$vo['count']);
		$this->assign("page",$vo['page']);
		$this->display();
		}

	//投诉建议
    public function suggest(){
		$this->display();
    }
    //绑定会员卡
    public function bindcard()
    {

	   $userinfo   = M('User')->where('id='.(int)$_SESSION['auth'])->find();
	   if(!$userinfo)
	   {
		  $this->error('找不到指定的用户');
	   }
	   $card_id = M('user')->where("id=".(int)$_SESSION['auth'])->getField('card_id');
	   $this->assign('card_id',$card_id);
	   $card_time = M('user')->where("id=".(int)$_SESSION['auth'])->getField('card_time');
	   $this->assign('card_time',$card_time);
	   $card_score = M('Config')->where("id=1")->getField('score_card');
	   $this->assign('userinfo',$userinfo);
       $this->assign('card_score',$card_score);
       $this->display();
    }
    //会员卡绑定
    public function savebind()
    {
	   $default_score = (int)$_REQUEST['default_score'];
       $password      = trim(strtoupper($_REQUEST['card_password']));
	   //获取用户信息
	   $userinfo   = M('User')->where('id='.(int)$_SESSION['auth'])->find();
	   if(!$userinfo)
	   {
		  $this->error('找不到指定的用户');
	   }

	   $err           = false;
       $err_msg       = '';

       //处理密码为空的情况
	   if(empty($password))
       {
           $err    = true;
           $err_msg = '密码不能为空';
       }
       if(!$err)
       {
             //卡做大小写处理
			 $card_info = M('Card')->where("upper(password)='{$password}' and status=1")->find();
             //判断记录是否存在，或者卡息是否过期
             if(!$card_info)
             {
                $err = true;
                $err_msg = '密码指定错误';
             }
       }
       if($err)
       {
          $this->assign('error',$err_msg);
          $this->assign('card_score',$card_score);
		  $this->assign('new_user',(int)$_POST['new_user']);
          $this->display('bindcard');
          return false;
       }else{
		  //如果是第一次绑定会员则进行加分处理
		  if($_POST['new_user']==1 && $default_score>0)
		  {
			  M('User')->where('id='.(int)$_SESSION['auth'])->setinc('score',$default_score);
			  $log_data = array('user_id'=>$userid,
							    'why'=>'绑定会员赠送积分',
							    'point'=>$default_score,
								'tpoint'=>$default_score,
								'ptype'=>'',
								'log_time'=>time()
								);
			 //添加积分记录日志
			 M('Pointlog')->add($data);
		  }else{
             //释放原来的帐号权限
			 M('Card')->where('id='.(int)$userinfo['card_id'])->setfield('status',1);
		  }
		  //绑定会员卡
		  $User = M('User');
		  $User->where('id='.(int)$_SESSION['auth'])->setField('card_time',time());
		  $User->where('id='.(int)$_SESSION['auth'])->setField('card_id',$card_info['card_id']);
		  M('Card')->where('id='.(int)$card_info['id'])->setField('status',2);
		  $this->assign("jumpUrl","/User/bindcard/");
          $this->success('您已成功绑定帐号');
       }
    }

	//解绑会员卡
	public function delbind(){
		$data['card_id']='';
		$user = M('user')->where('id='.(int)$_SESSION['auth'])->save($data);
		if($user){
			$this->success('您已解绑成功！');
		}else{
			$this->error('解绑失败！');
		}
	}
	//提交投诉建议
    public function addsuggest(){
        $from    = $_SESSION['uc_uid'];
		$to      = 1;
		$subject = "投诉和建议";
        $content = $_REQUEST['content'];
		//uc发送管理员消息
			import('@.ORG.Uc');
			$Uc = new Uc();
		if($Uc->sendmsg($from,$to,$subject,$content)){
			$this->success("提交成功！");
			}
		}

	// 优惠券收藏
	public function ticket_col(){
		$userinfo = M('User')->where('id='.(int)$_SESSION['auth'])->find();
		if(!$userinfo)
		{
			$this->error('您没有登录，请登录后再来');
		}
		if(!$userinfo['card_id'])
		{
			$this->error('请绑定会员卡后再进行收藏');
		}
		 if($_REQUEST["id"]){
				$cm    = M("Collect");
				$user_id=$_SESSION[C('USER_AUTH_KEY')];
				if($cm->where("ticket_id=$_REQUEST[id] and user_id=$user_id")->find()){
					$this->error('该券已收藏！');
				    }else{
					$model = M("Ticket");
				    $vo    = $model->where("id=$_REQUEST[id]")->find();
					$data['user_id']    = $user_id;
					$data['ticket_id']  = $_REQUEST['id'];
					$data['start_time'] = $vo['start_time'];
					$data['close_time'] = $vo['close_time'];
					$data['create_time']= time();
					if($cm->add($data)){
						$this->success('收藏成功！');
					   }else{
						$this->error('收藏失败！');
					   }
				}
		 }
	}

	 // 商家收藏
	public function trade_col(){
		if($_REQUEST["id"]){
				$cm = M("Trade_collect");
				$user_id=$_SESSION[C('USER_AUTH_KEY')];
				if($cm->where("trade_id=$_REQUEST[id] and user_id=$user_id")->find()){
					$this->error('该商家已收藏！');
				  }else{
					$model = M("Trade");
					$vo    = $model->where("id=$_REQUEST[id]")->find();
					$data['user_id']    = $_SESSION[C('USER_AUTH_KEY')];
					$data['trade_id']   = $_REQUEST['id'];
					$data['create_time']= time();
					if($cm->add($data)){
						$this->success('收藏成功！');
					  }else{
						$this->error('收藏失败！');
					  }
				}
		 }

	 }
   //修改用户资料
   public function update(){
	   $user = D("User");
		if(!$user->create()){
			$this->error($user->getError());
		}
		if($user->save()){
			//uc同步注册
			import('@.ORG.Uc');
			$Uc = new Uc();
			$name=$_REQUEST["account"];
			$pwd=$_REQUEST["password"];
			$email=$_REQUEST["email"];
			$d =$Uc->editUserInfo($name,'',$pwd,$email);
			//
			$id    = (int)$_REQUEST["id"];
			$info = $user->where("id=$id")->find();
			$mo  = M("Config");
			$config = $mo->find();
			$score  = (int)$config['info_if'];
			if(!$info['userinfo_if']){
				if($_REQUEST['email']&&$_REQUEST['birthday']&&$_REQUEST['realname']&&$_REQUEST['phone']&&$_REQUEST['mobile']&&$_REQUEST['zip_code']&&$_REQUEST['address']){
					//增加积分
					$model  = M("Config");
					$config = $model->find();
					$data['score']=array("exp","score+$score");
					$data['userinfo_if']=1;
					$user->where("id=$id")->save($data);
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

					$this->success("资料已完善，获得".$score."个积分！");
				}else{
					$this->success("修改成功！继续完善资料可获得".$score."个积分！");
					}
			}else{
			$this->success('修改成功！');
			}
		}else{
			$this->error('修改失败，请检查输入项！');
		}
	}

	public function del(){
		$module  = trim($_REQUEST["module"]);
		$id      = (int)$_REQUEST["id"];
		if($module == "trade"){
			$model = M("Trade_collect");
			}
		if($module == "ticket"){
			$model = M("Collect");
			}
		if($model->where("id=$id")->delete()){
			$this->success("成功删除！");
			}else{
			$this->error("操作失败！");
			}
	}

	//修改头像
	public function avatar(){
		import('@.ORG.Uc');
		$Uc   = new Uc();
		$avar = $Uc->edit_avatar($_SESSION['uc_uid'],"virtual");
		$this->assign("avar",$avar);
		$this->display();
	}

	//秒杀
	public function miaosha(){
		$id      = (int)$_REQUEST['id'];
		$kill    = M("Seckill");
		$killone = $kill->where("id=$id")->find();
		if($killone['user_count']<$killone['limit_count']){
			$model = M("User");
			$user  = $model->where("id=".$_SESSION[C('USER_AUTH_KEY')])->find();
			if($user['score'] >= $killone['score']){

				   $data['user_count' ]= array("exp","user_count+1");
				   $kill->where("id=$id")->save($data);

				   $d['score'] = array("exp","score-$killone[score]");
				   $model->where("id=".$_SESSION[C('USER_AUTH_KEY')])->save($d);

				   if($killone['ktype']==2){
					  $this->success("秒杀成功！");
				   }else{
					  $this->success("兑换成功！");
				   }
			}else{
			   $this->error("对不起！您积分不足，请先充值！");
			}
		}else{
			$this->error("人数已到，活动已结束！");
		}
	}

	//发送邮箱或手机验证码
	public function check(){
		$type  = trim($_REQUEST['type']);
		$field = "verify_".$type;
		$user  = M("User")->where("id=".$_SESSION[C('USER_AUTH_KEY')])->find();
	    $verify= random(8);
		if($type == "email"){//邮箱验证
        
            if(empty($_REQUEST['email'])){
                 $this->error('验证邮箱不能为空！');
            }
            import("@.ORG.PHPMailer");
	        import("@.ORG.SMTP");
			$model=M("Config");
	        $config=$model->field('stmp_server,stmp_username,stmp_password,stmp_send,stmp_back,stmp_subject,stmp_body,stmp_fromname,sms_username,sms_password')->find();
		    if(preg_match("/^[a-zA-Z0-9_]+@[a-zA-Z0-9-]+.[a-zA-Z0-9]+$/",$_REQUEST['email'])){
				$mail = new PHPMailer();
				$mail->CharSet = "UTF-8";
				$mail->IsSMTP();
				$mail->Host = $config['stmp_server'];
				$mail->SMTPAuth = TRUE;
				$mail->Username = $config['stmp_username'];
				$mail->Password =$config['stmp_password'];
				$mail->From = $config['stmp_username'];
				$mail->FromName = "优惠券";
				$mail->Subject = "优惠券邮箱验证码";
				$mail->Body = "您的邮箱验证码为:".$verify.",请登录http://".$_SERVER['SERVER_NAME']."优惠券网站经行验证!";
				$address = trim($_REQUEST['email']);  //修改的
				$mail->AddAddress("$address",$user['account']);
				$mail->Send();
			}
            $d[$field] = $verify; //修改的
            $d['email'] = trim($_GET['email']);//修改的
			M("User")->where("id=".$_SESSION[C('USER_AUTH_KEY')])->save($d);
			$this->assign("title","邮箱");
		}elseif($type == "mobile"){ //手机验证
           if(empty($_REQUEST['mobile'])){
                 $this->error('验证手机不能为空！');
            }
			import('@.ORG.SMS');
			$sms = new SMS();
			$sms->sendSMS('3SDK-EMY-0130-LIZLM','680628',array(intval($_REQUEST['mobile'])),"$verify");
            $d[$field] = $verify;
            $d['mobile'] =trim($_REQUEST['mobile']);
            //修改的*********************************************************************************************
		 	M("User")->where("id=".$_SESSION[C('USER_AUTH_KEY')])->save($d);
            //end***********************************************************************************************
			$this->assign("title","手机");
			}
           $this->assign("field",$type);
		   $this->display();
	}

	//验证用户输入的验证码
	public function checkverify(){
		 $field  = trim($_REQUEST['field']);
		 $verify = trim($_REQUEST['verify']);
		 $user  = M("User")->where("id=".$_SESSION[C('USER_AUTH_KEY')])->find();
		 $this->assign('jumpUrl', __GROUP__."/User/info");
		 if($verify == $user["verify_".$field]){
			 $config = M("Config")->field($field."_if")->find();
			 $data['score'] = array("exp","score+".$config[$field."_if"]);
			 $data[$field."_if"]=1;
			 if(M("User")->where("id=".$_SESSION[C('USER_AUTH_KEY')])->save($data)){
				//积分记录
				$m = M("Pointlog");
				$dat["user_id"]= (int)$_SESSION[C('USER_AUTH_KEY')];
				$dat["ptype"]  = 0;
				$dat["tpoint"] = $_SESSION['score'];
				$dat["why"]    = "验证".$field;
				$dat["point"]  = $config[$field."_if"];
				$dat["log_time"]=time();
				$m->add($dat);
				$this->success("验证成功！获得".$config[$field."_if"]."个积分");
			}
		 }else{
			 $this->error("验证码错误！验证失败，请尝试重新输入，或者重新获得验证码！");
		 }
	}

    //清除已经过期和被系统删除的优惠券
    public function clearCollect() {
        // -1 代表优惠券的禁用状态
        $time = time();
        $userId  = (int)$_SESSION[C('USER_AUTH_KEY')];
        $sql = "DELETE FROM collect USING think_ticket AS ticket,think_collect AS collect WHERE collect.ticket_id = ticket.id AND collect.user_id = $userId AND (ticket.status = -1 OR collect.close_time < $time)";
        $res = M()->query($sql);
        if($res === false) {
            $this->error('清除失败！');
        } else {
            $this->success('清除成功！');
        }
    }

	//最新短消息列表
	public function message(){
		import('@.ORG.Uc');
		$Uc = new Uc();
		$list = $Uc->get_announce($_SESSION['uc_uid']);
		$this->assign('count',$list['count']);
		$this->assign('list',$list['data']);
		$this->display();
	}

	//删除短消息
	public function delmsg(){
		import('@.ORG.Uc');
		$Uc = new Uc();
		if($Uc->delmsg($_SESSION['uc_uid'],$_GET['msgid'])){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}

	//查看短消息
	public function readmsg(){
		import('@.ORG.Uc');
		$Uc = new Uc();
		$content = $Uc->viewmsg($_SESSION['uc_uid'],$_GET['msgid']);
		$this->assign('content',$content[0]);
		$this->display();
	}
	//秒杀
	public function seckill(){
		$vo = D("User")->getgoods();
		$this->assign("list",$vo['list']);
		$this->assign("count",$vo['count']);
		$this->assign("page",$vo['page']);
		$this->display();
	}

        //充值
       public function charge(){
                $model = M("User");
		$id  = intval($_SESSION['authid']); //获取用户id
		$conf=M("Config");
		$cvo =$conf->find();
		$vo = $model->where("id=$id")->find();
		$this->assign("vo",$vo);
	         $this->assign("rate",$cvo["money_rate"]);
              $this->display();
        }
      //支付宝充值
      public function payoff(){
            $model=M("Config");
		$config=$model->where('id=1')->field( 'zf_username,zf_password' )->find();

		$service = 'create_direct_pay_by_user';
		$_input_charset = 'utf-8';
		$partner = $config["zf_username"];//'2088002114757166';//'2088002114757166';
		$security_code =$config["zf_password"];//'qw2e4i0i3uqrcjk931n9yltr81jqig7q';//
		$seller_id = $config["zf_username"];//'2088002114757166';//$config["zf_username"];

		$sign_type = 'MD5';
		$out_trade_no = 'tsyhq'.time();

		$return_url ='http://'.$_SERVER['SERVER_NAME'].__URL__.'/payreturn';
		$notify_url ='http://'.$_SERVER['SERVER_NAME'].__URL__.'/paynotify';
		$show_url   ='http://'.$_SERVER['SERVER_NAME'];

		$total_money =$_REQUEST["money"];
		$subject = "唐山优惠券网充值";
		$body = $show_url;
		$quantity = 1;
		$parameter = array(
			"service"         => $service,
			"partner"         => $partner,
			"return_url"      => $return_url,
			"notify_url"      => $notify_url,
			"_input_charset"  => $_input_charset,
			"subject"         => $subject,
			"body"            => $body,
			"out_trade_no"    => $out_trade_no,
			"total_fee"       => $total_money,
			"payment_type"    => "1",
			"show_url"        => $show_url,
			"seller_id"       => $seller_id,
			);
		import("@.ORG.AlipayService");
	    $alipay = new AlipayService($parameter, $security_code, $sign_type);
	    $sign = $alipay->Get_Sign();
		$this->assign("body",$body);
		$this->assign("notify_url",$notify_url);
		$this->assign("out_trade_no",$out_trade_no);
		$this->assign("partner",$partner);
		$this->assign("return_url",$return_url);
		$this->assign("seller_id",$seller_id);
		$this->assign("service",$service);
		$this->assign("show_url",$show_url);
		$this->assign("subject",$subject);
		$this->assign("sign_type",$sign_type);
		$this->assign("sign",$sign);
		$this->assign("_input_charset",$_input_charset);
		$this->assign("total_fee",$total_money);
		$this->display();
	}

        function paynotify(){
		  echo 'success';
    	}

	function payreturn(){
		 $total_fee=$_REQUEST['money'];
		if($_REQUEST['trade_status']=='TRADE_SUCCESS'){
                      $model = M("User");
		      $id  = intval($_SESSION['authid']); //获取用户id
		      $conf=M("Config");
		      $cvo =$conf->find();
		      if($_REQUEST["money"]!=''){
			$score = intval($_REQUEST["score"]);
			$d["score"]=$score+$_REQUEST["money"]*$cvo["money_rate"];
			$model->where("id=$id")->save($d);
			$m = M("Pointlog");
                        $data["user_id"]=$id;
			$data["ptype"]=0;
			$data["tpoint"]=$d["score"];
			$data["why"]  ="用户".$_SESSION['account']."通过支付宝充值 ";
			$data["point"]   =$_REQUEST["money"]*$cvo["money_rate"];
			$data["log_time"]=time();
			$m->add($data);
			}
		      $this->assign('jumpUrl',__GROUP__/user/charge);
		      $this->success("您已经成功充值");
		}
    }


}
?>