<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class PayAction extends CommonAction {
	
	function index(){
		 $uid    = $_SESSION[C('USER_AUTH_KEY')];
		 $id     = $_SESSION["trade_id"];
		 if(!$id){
			$utmodel=M("User_trade");
			$t=$utmodel->where("user_id=$uid")->order("id desc")->find();
			$id=$t['trade_id'];
			}
		$model=M("Trade");
		$list=$model->where("id=$id")->find();
		$_SESSION["tradeid"]=$id;
		$_SESSION["tradename"]=get_trade($id);
		$this->assign('id',$id);
		$this->assign('vo',$list);
		
    	$this->display();
		}
	
	function confirm(){
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

		$total_money =$_REQUEST["total_fee"];
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
	
function confirm2(){
        $model=M("Config");
		$config=$model->where('id=1')->field( 'zf_username,zf_password' )->find();
        echo $config["zf_username"].'--'.$config["zf_password"].'--ppppp';
		$service = 'create_direct_pay_by_user';
		$_input_charset = 'utf-8';
		$partner = $config["zf_username"];
		$security_code = $config["zf_password"];
		$seller_id = $config["zf_username"];
		
		$sign_type = 'MD5';
		$out_trade_no = 'tsyhq'.time();
	
		$return_url ='http://'.$_SERVER['SERVER_NAME'].__URL__.'/payreturn';
		$notify_url ='http://'.$_SERVER['SERVER_NAME'].__URL__.'/paynotify';
		$show_url   ='http://'.$_SERVER['SERVER_NAME'];

		$total_money =$_REQUEST["total_fee"];
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
//		$model=M("Config");
//		$config=$model->where('id=1')->field( 'zf_username,zf_password' )->find();
//		
//		$_input_charset = 'utf-8';
//		$partner = $config["zf_username"];
//		$security_code = $config["zf_password"];
//		$sign_type = 'MD5';
//		$transport = 'http';
//		
//		$out_trade_no = $_POST['out_trade_no']; 
//        $total_fee = $_POST['total_fee'];
//		
//		import("@.ORG.AlipayNotify");
//		$alipay = new AlipayNotify($partner, $security_code, $sign_type, $_input_charset, $transport);
//        $verify_result = $alipay->notify_verify();
//		if ( $verify_result ) {
//			if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
//                $m=M("Trade");
//				$data['money'] = array('exp', "money+$total_fee");
//				$m->where("id=$_SESSION[tradeid]")->save($data);
//				$log=M("Paylist");
//				$data['trade_id']   = $_SESSION["tradeid"];
//				$data['money']      = $total_fee;
//				$data['create_time']=time();
//				$data['remark']     ="支付宝充值";
//				$log->data($data)->add();
//			}
//		  }
		  echo 'success';
    	}
		
	function payreturn(){
//		$model=M("Config");
//		$config=$model->where('id=1')->field( 'zf_username,zf_password' )->find();
//		
//		$_input_charset = 'utf-8';
//		$partner = $config["zf_username"];
//		$security_code = $config["zf_password"];
//		$sign_type = 'MD5';
//		$transport = 'http';
//		import("@.ORG.AlipayNotify");
//		$alipay = new AlipayNotify($partner, $security_code, $sign_type, $_input_charset, $transport);
//        $verify_result = $alipay->notify_verify();
//		if ( $verify_result ) {
//			if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
//		$this->success('充值成功！');	
//			   }
//		  }
		$total_fee=$_REQUEST['total_fee'];
		if($_REQUEST['trade_status']=='TRADE_SUCCESS'){
			$m=M("Trade");
			$data['money'] = array('exp', "money+$total_fee");
			$m->where("id=$_SESSION[tradeid]")->save($data);
			$log=M("Paylist");
			$data['trade_id']   = $_SESSION["tradeid"];
			$data['money']      = $total_fee;
			$data['create_time']=time();
			$data['remark']     ="支付宝充值";
			$log->data($data)->add();
			$this->assign('jumpUrl',__GROUP__);
		    $this->success("您已经成功充值".$total_fee."元！");
		}
    }
}

?>