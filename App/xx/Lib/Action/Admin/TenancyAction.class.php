<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class TenancyAction extends CommonAction {
	
	function index(){
		$model=M("Tenancy");
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
		if($_REQUEST['resultid']!=""){    //结果排序
			$order=$_REQUEST['resultid'];
			$sort=$_REQUEST['sortid'];
			$this->assign("resultid",$_REQUEST['resultid']);
			$this->assign("sortid",$_REQUEST['sortid']);
			}
		$sql="1=1";
		if($_REQUEST['bt_position']!=''){
		     $sql=$sql." and bt_position ='$_REQUEST[bt_position]'";
			 $this->assign('bt_position',$_REQUEST['bt_position']);
		}
		if($_REQUEST['area']!=''){
			 $area = M("Location");
			 $vo  = $area->where("area=$_REQUEST[area]")->find();
			 $loc = M("Tenancy");
			 $tenlist=$loc->where("p_id=$vo[id]")->select();
			 $str="(0";
			 foreach($tenlist as $k=>$val){
				 $str=$str.",$val[id]";
				}
			$str=$str.")";
		     $sql=$sql." and id in $str";	 
		}
		if($_REQUEST['p_id']!=''){
		     $sql=$sql." and p_id =$_REQUEST[p_id]";
			 $this->assign('p_id',$_REQUEST['p_id']);
		}
		if($_REQUEST['trade_id']!=''){
		     $sql=$sql." and trade_id =$_REQUEST[trade_id]";
			 $this->assign('trade_id',$_REQUEST['trade_id']);
		}
		if($_REQUEST['s_time1']!=''){
		     $s_time1=strtotime($_REQUEST['s_time1']);
		     $sql=$sql." and start_time >=$s_time1";
			 $this->assign('s_time1',$_REQUEST['s_time1']);
		}
		if($_REQUEST['s_time2']!=''){
		     $s_time2=strtotime($_REQUEST['s_time2'])+60*60*24;
		     $sql=$sql." and start_time <$s_time2";
			 $this->assign('s_time2',$_REQUEST['s_time2']);
		}
		if($_REQUEST['c_time1']!=''){
		     $c_time1=strtotime($_REQUEST['c_time1']);
		     $sql=$sql." and close_time >=$c_time1";
			 $this->assign('c_time1',$_REQUEST['c_time1']);
		}
		if($_REQUEST['c_time2']!=''){
		     $c_time2=strtotime($_REQUEST['c_time2'])+60*60*24;
		     $sql=$sql." and close_time <$c_time2";
			 $this->assign('c_time2',$_REQUEST['c_time2']);
		}
		if($_REQUEST['status']!=''){
			if($_REQUEST['status']==3){
			}else{
				$sql=$sql." and status =$_REQUEST[status]";
				}
			 $this->assign('status',$_REQUEST['status']);
		}else{
			$sql=$sql." and status =1";
			}
		
		 $_SESSION["tenancy_sql"]=$sql;
		if($_REQUEST['flag']){  //保存状态
		   $_SESSION["tenancy_sql"]=$sql;
		}
        $count = $model->where($_SESSION["tenancy_sql"])->count('id');
		if($_REQUEST['submit']=='exp'){
			$downlist=$model->where($_SESSION["tenancy_sql"])->select();
			$this->down($downlist);
		}
		if($_REQUEST['submit']=='tomorrow'){
			$now=time();
			$tomorrow=time()+3600*30;
			$downlist=$model->where("start_time>$now and start_time<$tomorrow")->select();
			$this->down($downlist);
		}
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			if($order=="prints"){
				$userlist=$model->where($_SESSION["tenancy_sql"])->limit($p->firstRow . ',' . $p->listRows)->findAll();
				}else{
			$userlist=$model->where($_SESSION["tenancy_sql"])->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
				}
			$page=$p->show();
			foreach($userlist as $k=>$val){
			  $val["prints"]=get_countbytenacy($val["trade_id"]);
			  $userlist[$k]=$val;
			  $r[]=$val['prints'];
			}
			if($order=="prints"){
			   $sort=$_SESSION["tensort"]==SORT_ASC?SORT_DESC:SORT_ASC;
			   $_SESSION["tensort"]=$sort;
			   $out = array_multisort($r,$sort,$userlist);
			}
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
//		//发送即将到期的展位邮件
//		$model=M("Config");
//		$config=$model->where('id=1')->field('error_rate,update_time')->find();
//		if(($config['update_time']+$config['error_rate']*3600*24)<time()){
//			$t=M("Tenancy");
//			$time=time();
//			$l=$t->select();
//			if($l){
//				$str="";
//				$i=1;
//				foreach($l as $k=>$val){
//					$str=$str.$i."——商家：".get_trade($val['trade_id'])." 购买的位于 ".get_position($val['p_id'])."的展位将于".date("Y年-m月-d日",$val['close_time'])."过期；";
//					$i++;
//					}
//				$str=$str."请注意查看！";
//				$data['title']="展位即将到期的商家";
//				$data['body']=$str;
//				$this->email($data);
//			  }
//			$data["update_time"]=time();
//			$model->where("id=1")->save($data);
//		  }
//取得机器放置地址列表
		$plist=get_plist();
		$this->assign("plist",$plist);
		$this->display();
		}

//ajax修改备注
    public function aupdate() {
        $model = M("Tenancy");
		$arr = $_REQUEST;
        $id = $_REQUEST ['id'];
        $data['remark'] = $_REQUEST ['remark'];
        $model->where("id=$id")->save($data);
    }
	
	//添加
	public function add(){
		//取得机器放置地址列表
		$plist=get_plist();
		$this->assign("plist",$plist);
		$this->display();
		}
	public function add2(){
	    if($_REQUEST['id']!=''){
		     $sql="id =$_REQUEST[id]";
		}
	    if($_REQUEST['title']!=''){
		     $sql="title like'%".$_REQUEST['title']."%'";
		}
		$model=M("Trade");
		$list=$model->where($sql)->select();
		if(!$list){
			$this->error("未找到相关商家！");
			}else{
			for($i=0;$i<count($list);$i++){
				$list[$i]['encodeTitle'] = urlencode(urlencode($list[$i]["title"]));
			}
			$this->assign("list",$list);
			$this->display();
			}
		}
	public function add3(){
		//取得机器放置地址列表
		$title=urldecode($_GET['title']);
		$id=$_REQUEST['id'];
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
				$exhlist=$emodel->where($esql)->select();
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
		$id=$_REQUEST['id'];
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
		$id=$_REQUEST['id'];
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
			foreach($elist as $k=>$v){
				$data['bid']=$v['id'];
				$data['p_id']=$v['p_id'];
				$data['trade_id']=$id;
				$data['bt_position']=$v['bt_position'];
				$close_time=get_rendclosetime($v['id']);
				 if($close_time>time()){
				     $data['status']=0;
					 $data['type_buy']=1;
					 $data['start_time']=$close_time;
					 $time=date("Y-m-d",$data['start_time']);
					 $data['close_time']=strtotime("$time +$num week")-1;
					 }elseif($close_time<time()){
				     $data['status']=0;
					 $data['type_buy']=1;
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
			$this->_writelog("为(".$title.")添加了".$count."个展位","商家ID：".$id);
			$this->assign("jumpUrl", __URL__);
			$this->success("添加成功！");
		}
		    // 插入数据
	public function insert() {
        $name = $this->getActionName();
        $model = M($name);
		if (false === $model->create()) {
            $this->error($model->getError());
        }
		$data['bid']=$_REQUEST['bt_position'];
		$data['bt_position']=get_bt_position($data['bid']);
		$data['p_id']=$_REQUEST['p_id'];
		$data['trade_id']=$_REQUEST['trade_id'];
		$data['start_time']=strtotime($_REQUEST['start_time']);
		$data['close_time']=strtotime($_REQUEST['close_time'])+3600*24-1;
		$data['status']=$_REQUEST['status'];
		$data['remark']=$_REQUEST['remark'];
		$data['create_time']=time();
		$data['update_time']=time();
        $list = $model->add($data);
		if(false != $list){
			   $this->_writelog("添加租赁信息","租赁ID：".$id);
			   $this->success("添加成功！");
		}else{
			  $this->error("添加失败！");
		}
    }
	    // 更新数据
	public function update() {
        $name = $this->getActionName();
        $model = M($name);
		if (false === $model->create()) {
            $this->error($model->getError());
        }
        $id=$_REQUEST['id'];
		$data['start_time']=strtotime($_REQUEST['start_time']);
		$data['close_time']=strtotime($_REQUEST['close_time'])+3600*24-1;
		$data['status']=$_REQUEST['status'];
		$data['remark']=$_REQUEST['remark'];
		$data['update_time']=time();
        $list = $model->where("id=$id")->save($data);
		if(false != $list){
			   $this->_writelog("编辑租赁信息","租赁ID：".$id);
			   $this->success("编辑成功！");
		}else{
			  $this->error("编辑失败！");
		}
    }
	
	//资料导出
	function down($data){
		$filename =date('y年-m月-d日')."——租赁资料";
		$filename=mb_convert_encoding($filename,'gb2312','utf-8');
		$title = array(
			'p_id'        => '机器放置位置',
			'bt_position' => '按钮位置',
			'trade_id'    => '商家',
            'start_time'  => '开始时间',
			'close_time'  => '结束时间',
			'remark'      => '备注',
		);
		foreach($data as $k=>$v){
			 $v['p_id']       = get_position($v['p_id']);
			 $v['trade_id']   = get_trade($v['trade_id']);
			 $v['start_time'] = date("Y-m-d",$v['start_time']);
			 $v['close_time'] = date("Y-m-d",$v['close_time']);
			 $data[$k]=$v;
			}
		import("@.ORG.Down");
		Down::down_xls($data,$title,$filename);
	}
	
	function shh(){
		$model = M("Tenancy");
		$sql   = "status=0";
        $count = $model->where($sql)->count('id');
		if($_REQUEST['submit']=='导出'){
			$downlist=$model->where($sql)->select();
			$this->down($downlist);
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
	
		//发送错误提示邮件
    function email($data){
	import("@.ORG.PHPMailer");
	import("@.ORG.SMTP");
	$model=M("Config");
	$config=$model->where('id=1')->field('stmp_server,stmp_username,stmp_password,stmp_send,stmp_back,stmp_subject,stmp_body,stmp_fromname,error_email')->find();
     	$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP();
		$mail->Host = $config['stmp_server'];
		$mail->SMTPAuth = TRUE;
		$mail->Username = $config['stmp_username'];
		$mail->Password =$config['stmp_password'];
		$mail->From = $config['stmp_username'];
		$mail->FromName = $config['stmp_fromname'];
		$mail->Subject = $data['title'];
		$mail->Body = $data['body'];
		$address = $config['error_email'];
		$mail->AddAddress("$address","admin");
		$mail->Send();
   }


   	public function ajaxexh(){		
		$id    = $_REQUEST["id"];
		$model = M("Exhibition");
		$list  = $model->where("p_id=$id")->select();
		if($list){
		$str   = "-请选择-";
		foreach($list as $k=>$val){
		  $str = $str."|".$val['bt_position'].",".$val['id'];
			}
		echo $str;
		}else{
			echo "-暂无-";
			}
		
		}
		
	public function showcount(){
		$trade_id=$_REQUEST['tradeid'];
		$model=M("Ticket");
		$ticket=$model->where("trade_id=$trade_id")->select();
		$str="(-1";
		foreach($ticket as $k=>$v){
			$str=$str.",$v[id]";
			}
	    $str=$str.")";
		$p_id=$_REQUEST['pid'];
		$time1=$_REQUEST["time1"];
		$time2=$_REQUEST["time2"];
		$model=M("Ticket_statistic");
		$sql="select position_id,create_time,ticket_id,count(id) as count_ticket from __TABLE__ where ";
		$sql=$sql."ticket_id in $str and position_id=$p_id";
		if($time1!=0){
			$sql=$sql." and create_time>$time1";
			}
		if($time2!=0){
			$sql=$sql." and create_time<$time2";
			}
		$sql=$sql." group by ticket_id";
	    $m=$model->query($sql);
		$this->assign("list",$m);
		$this->display();
		}
}

?>