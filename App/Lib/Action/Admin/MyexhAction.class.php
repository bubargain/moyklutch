<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class MyexhAction extends CommonAction {
	
	function index(){
		$model=M("Location");
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
		$sql='1=1';
		if($_REQUEST['area']!=''){
		     $sql=$sql." and area=$_REQUEST[area]";
		}
		if($_REQUEST['money1']!=''){
		     $sql=$sql." and money>=$_REQUEST[money1]";
		}
		if($_REQUEST['money2']!=''){
		     $sql=$sql." and money<=$_REQUEST[money2]";
		}
        $count = $model->where($sql)->count('id');
		if($count > 0){
		import("@.ORG.Page");
		$p=New Page($count,C('PAGE_LIST_ROWS'));
		$userlist=$model->where($sql)->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
		$page=$p->show();
		$this->assign('list',$userlist);
		$this->assign('page',$page);
		}
		$this->display();
	}
	function myexhib(){
				//我的展位
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
		 $id    = $_SESSION[C('USER_AUTH_KEY')];
		 $tradeid=$_SESSION["trade_id"];
		 if(!$tradeid){
			$utmodel=M("User_trade");
			$t=$utmodel->where("user_id=$id")->order("id desc")->find();
			$tradeid=$t['trade_id'];
			}
		 $model = M("Tenancy");
		 $tenancy= $model->where("trade_id=$tradeid")->order("`" . $order . "` " . $sort)->select();
		 $this->assign("mt",$tenancy);
		 $this->display();
		}
		
	function topay(){
		$id=$_REQUEST['id'];
		$model=M("Tenancy");
		$vo=$model->where("id=$id")->find();
		$this->assign("vo",$vo);
		$this->display();
		}
//	function topay2(){
//		$id=$_REQUEST['id'];
//		$trade_id=$_REQUEST['trade_id'];
//		$money=$_REQUEST['money'];
//		$model=M("Trade");
//		$tenancy=M("Tenancy");
//		$tenancyvo=$tenancy->where("id=$id")->find();
//		if($tenancy->where("start_time >=$tenancyvo[start_time] and start_time <=$tenancyvo[close_time] and status<2")->find()){
//		    $vo=$model->where("id=$trade_id")->find();	
//			if($money>$vo['money']){
//				$this->error('余额不足，付款失败！');
//				}else{
//				$data['money']=	$vo['money']-$money;
//				$model->where("id=$trade_id")->save($data);
//				$m = M("Paylist");
//				$da["trade_id"]=$trade_id;
//				$da["remark"]  ="用户".get_user($_SESSION[C('USER_AUTH_KEY')])." 为商家 ".get_trade($trade_id)." 购买展位";
//				$da["money"]   =$_REQUEST["money"];
//				$da["tmoney"]  =$data['money'];
//				$da["create_time"]=time();
//				$da["type"]    = 2;
//				$m->add($da);
//	
//				$d['status']=0;
//				$tenancy->where("id=$id")->save($d);
//				$this->success("付款成功！已支付".$money."元");
//				}
//		  }else{
//			  $this->error('该展位已被其他人购买，请挑选其他展位！');
//			  }
//		}
		
     function apply(){
		 $id    = $_SESSION[C('USER_AUTH_KEY')];
		 $u     = M("User");
		 $user  = $u->where("id=$id")->find();
		 $model = M("Exhibition");
		 $id    = $_REQUEST['id'];
		 $exh   = $model->where("id=$id")->find();
		 $this->assign("vo",$exh);
		 $this->assign("trade_id",$user["trade_id"]);
		 $this->display();
		 }	
//	 function insert(){
//		 
//			 $id    = $_SESSION[C('USER_AUTH_KEY')];
//		 if($_REQUEST['rend']>3){
//			 $this->error('最多不能超过3个月，申请失败！');
//			 }else{
//			 $u     = M("User");
//			 $user  = $u->where("id=$id")->find();
//			 
//			 $model              = M("Tenancy");
//			 $data["bid"]        = $_REQUEST["bid"];
//			 $data["p_id"]       = $_REQUEST["p_id"];
//			 $data["trade_id"]   = $_REQUEST["trade_id"];
//			 $data["bt_position"]= $_REQUEST["bt_position"];
//			 $data['create_time']=time();
//			 $data['update_time']=time();
//			 $data["status"] = 0;
//			 if($_REQUEST['rendtime']!=''){
//			 $rendtime = strtotime($_REQUEST['rendtime']);
//			 }else{
//			 $rendtime=time();
//			 }
//			 $overtime = strtotime($_REQUEST['overtime']);
//			 $money = trim($_REQUEST["money"]);
//			 if(time()>$overtime){
//				 $this->error("超过租期，申请失败！");
//			 }else{
//			 if($rendtime+trim($_REQUEST['rend'])*3600*24*30<$overtime){  //在机器放置地点的租期内
//			    $time=date("Y-m-d",$rendtime+3600*31);
//				$data["start_time"] = $rendtime+3600*31;
//				$num=$_REQUEST['rend'];
//				$data["close_time"] = strtotime("$time +$num month")-1;
//			    $totalmoney = $num*$money;
//				}
//			  else{     //超过机器放置地点的租期,总额不足50元的舍去
//				$data["start_time"] = $rendtime+3600*24;
//				$data["close_time"] = $overtime-1;
//				$totalmoney = ($money/30)*(($overtime-$rendtime)/(3600*24));
//				$totalmoney=(intval($totalmoney/50))*50;
//			  }
//			 }
//			 $m_trade = M("Trade");
//			 $trade = $m_trade->where("id=$user[trade_id]")->find();
//			 if(($trade["money"]-$totalmoney)<0){
//				 $this->error("共需要".$totalmoney."元，您余额不足，申请失败！");
//				 $model->add($data);
//			   }else{
//     		 $d["money"] = $trade["money"]-$totalmoney;
//			 $m_trade->where("id=$user[trade_id]")->save($d);
//			 $model->add($data);
//			 $m = M("Paylist");
//			 $data2["trade_id"]=$_REQUEST["trade_id"];
//			 $data2["remark"]  ="商家".get_user($_SESSION[C('USER_AUTH_KEY')])."购买展位";
//			 $data2["money"]   =$totalmoney;
//			 $data2["tmoney"]  =$d["money"];
//			 $data2["create_time"]=time();
//			 $data2["type"]    = 2;
//			 $m->add($data2);
//			 $this->success("申请成功！已支付".$totalmoney."元");
//			 }
//		 }
//	}

    public function applyexh(){
		$id    = $_REQUEST['id'];
		$model = M("Exhibition");
		$list  = $model->where("p_id=$id")->select();
		$this->assign("list",$list);
		$this->display();
		}
		 
		 
//ajax修改优惠券备注
    public function aupdate() {
        $model = M("Tenancy");
        $id = $_REQUEST ['id'];
        $data['remark'] = $_REQUEST ['remark'];
        $model->where("id=$id")->save($data);
    }
	
		public function add3(){
		//取得机器放置地址列表
		$title=$_REQUEST['title'];
		 $uid    = $_SESSION[C('USER_AUTH_KEY')];
		 $id=$_SESSION["trade_id"];
		 if(!$id){
			$utmodel=M("User_trade");
			$t=$utmodel->where("user_id=$uid")->order("id desc")->find();
			$id=$t['trade_id'];
			}
			if($_REQUEST["flag"]){  //筛选位置
				if($_REQUEST["tdx"]!=""){
					$tdx = array(); 
					$tdx = $_REQUEST['tdx'];
				}
				$sql="1=1";
				$status=$_REQUEST["status"];
				if($_REQUEST["area"]!=""){
					$sql=$sql." and area=$_REQUEST[area]";
					}
				if($_REQUEST["money1"]!=""){
					$sql=$sql." and money>=$_REQUEST[money1]";
					$this->assign("money1",$_REQUEST["money1"]);
					}
				if($_REQUEST["money2"]!=""){
					$sql=$sql." and money<=$_REQUEST[money2]";
					$this->assign("money2",$_REQUEST["money2"]);
					}
				$model=M("Location");
				$loclist=$model->where($sql)->select();
				$str="p_id in (0";
				 foreach($loclist as $k=>$v){
					$str=$str.",$v[id]";
					}
				$str=$str.")";
				$dxstr="and (bt_position like'%dx0'";
				foreach($tdx as $k=>$v){
					$this->assign($v,1);
					$dxstr=$dxstr." or bt_position like'%".$v."'";
					}
				$dxstr=$dxstr.")";
				$emodel=M("Exhibition");
				$esql=$str.$dxstr;
				$exhlist=$emodel->where($esql)->order("id desc")->select();
                  if($_REQUEST["status"]!=''){
					$this->assign("status",$_REQUEST["status"]);
					$exhlist2=array();			
					foreach($exhlist as $k=>$v){
						  $s=get_rendinfo2($v['id']);
						  if($s==$_REQUEST["status"]){
							  $exhlist2[$k]=$v;
							  }						
						}
					  $this->assign("exhlist",$exhlist2);
				     }else{
					  $this->assign("exhlist",$exhlist);
					 }
			  }
		$this->assign("id",$id);
		$this->assign("title",$title);
		$this->display();
		}
	public function add4(){
		$title=$_REQUEST['title'];
		 $uid    = $_SESSION[C('USER_AUTH_KEY')];
		 $id=$_SESSION["trade_id"];
		 if(!$id){
			$utmodel=M("User_trade");
			$t=$utmodel->where("user_id=$uid")->order("id desc")->find();
			$id=$t['trade_id'];
			}
        $list=array();
		$list=$_REQUEST['dxlist'];
			$str="id in (0";
			foreach($list as $k=>$v){
				$str=$str.",$v";
				}
			$str=$str.")";
			$model=M("Exhibition");
			$elist=$model->where($str)->select();
		    $this->assign("exhlist",$elist);
		$this->assign("id",$id);
		$this->assign("title",$title);
		$this->display();
		}
	public function add5(){
		 $uid    = $_SESSION[C('USER_AUTH_KEY')];
		 $id=$_SESSION["trade_id"];
		 if(!$id){
			$utmodel=M("User_trade");
			$t=$utmodel->where("user_id=$uid")->order("id desc")->find();
			$id=$t['trade_id'];
			}
		$num=$_REQUEST['rend'];
		$title=$_REQUEST['title'];
        $list=array();
		$list=$_REQUEST['dxlist'];
			$str="id in (0";
			foreach($list as $k=>$v){
				$str=$str.",$v";
				}
			$str=$str.")";
			$model=M("Exhibition");
			$elist=$model->where($str)->select();
			$tmodel=M("Tenancy");
			$totalmoney=0;
			foreach($elist as $k=>$v){
				$totalmoney=$totalmoney+get_rendmoney($v['p_id'])*$num;
				}
			$trademodel=M("Trade");
			$trade=$trademodel->where("id=$id")->find();
			if($totalmoney>$trade['money']){
				$data['status']=2;
				$msg="余额不足，请充值！";
				}else{
				$data['status']=0;
				$msg="购买成功！";
				$m = M("Paylist");
				$da["trade_id"]=$id;
				$da["remark"]  ="用户".get_user($_SESSION[C('USER_AUTH_KEY')])."为商家 ".get_trade($id)." 购买展位";
				$da["money"]   =$totalmoney;
				$da["tmoney"]  =$trade['money']-$totalmoney;
				$da["create_time"]=time();
				$da["type"]    = 2;
				$m->add($da);
				$tdata["money"]=$da["tmoney"];
				$trademodel->where("id=$id")->save($tdata);
				}
			foreach($elist as $k=>$v){
				$data['bid']=$v['id'];
				$data['p_id']=$v['p_id'];
				$data['trade_id']=$id;
				$data['bt_position']=$v['bt_position'];
				$data['money']=get_rendmoney($v['p_id'])*$num;
				$close_time=get_rendclosetime($v['id']);
				 if($close_time>time()){
					 $data['type_buy']=0;
					 $data['start_time']=$close_time;
					 $time=date("Y-m-d",$data['start_time']);
					 $data['close_time']=strtotime("$time +$num week")-1;
					 }elseif($close_time<time()){
					 $data['type_buy']=0;
					 if(date("w",time())==1){
					     $data['start_time']=strtotime(date("Y-m-d",time()))+3600*24*4+1;
					 }elseif(date("w",time())==0){
					     $data['start_time']=strtotime(date("Y-m-d",time()))+3600*24*5+1;
					 }else{
						 $data['start_time']=strtotime(date("Y-m-d",time()))+3600*24*(8-date("w",time())+4)+1;
						 }
					 $time=date("Y-m-d",$data['start_time']);
					 $data['close_time']=strtotime("$time +$num week")-1;
					 }
				$data['create_time']=time();
				$data['update_time']=time();
				$tmodel->add($data);
				}
			$count=count($elist);
			$this->_writelog("(".$title.")购买了".$count."个展位","商家ID：".$id);
			$this->assign("jumpUrl", __URL__);
			$this->success($msg);
		}
}

?>