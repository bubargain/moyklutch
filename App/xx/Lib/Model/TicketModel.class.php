<?php
// +----------------------------------------------------------------------
// | ThinkPHP顶想
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

//import('AdvModel');
class TicketModel extends CommonModel {

	public function getlist($where,$order,$limit,$w=''){ //标签用函数
	    if($limit==""){
			$limitstr="";
		}else{
			$limitstr="limit 0,$limit";
		}
		$sql="select ticket.*,trade.title as trade_title,trade.phone as trade_phone,trade.address as trade_address,cate.title as cate_title  from think_ticket as ticket
		      left join think_trade as trade on ticket.trade_id=trade.id
			  left join think_cate as cate on ticket.cate_id=cate.id
			  $where $order $limitstr";
		$result=$this->query($sql);
		return $result;
		}

	public function getlistall($nums=7){           //根据查询条件筛选
		$time = time(); //修改 hedong 2011-6-3
        $sql="status=1";
        $sql .= " and close_time > $time"; //修改 hedong 2011-6-3
		if($_REQUEST["trade_id"]){  //商家ID
			$trade_id=$_REQUEST["trade_id"];
			$sql.=" and trade_id=$trade_id";
		}
		if($_REQUEST['searchtag']&&$_REQUEST['search']){
			$sql.=" and title like'%$_REQUEST[searchtag]%'";
			}
		if($_REQUEST["cate_id"]){  //类别id
			$cate_id=$_REQUEST["cate_id"];
			$sql.=" and cate_id=$cate_id";
		}
		if($_REQUEST["area"]){   //小商圈
			$locmodel= M("Location");
			$loclist = $locmodel->where("area=$_REQUEST[area]")->select();//根据小商圈查找地点
				$str="(-1";
				foreach($loclist as $k=>$v){
					$str.=",$v[id]";
					}
				$str.=")";
			$tenmodel= M("Tenancy");   //租赁
			$tenlist = $tenmodel->where("p_id in $str")->select();//根绝地点查找查找租赁
			    $str="(-1";
			    foreach($tenlist as $k=>$v){
					$str.=",$v[trade_id]";
					}
				$str.=")";
			    $sql.=" and trade_id in $str";
		}
		if($_REQUEST['location']){
			$tenmodel= M("Tenancy");   //租赁
			$tenlist = $tenmodel->where("p_id = $_REQUEST[location]")->select();//根绝租赁查找商家
			    $str="(-1";
			    foreach($tenlist as $k=>$v){
					$str.=",$v[trade_id]";
					}
				$str.=")";
			    $sql.=" and trade_id in $str";
			}
			$count=$this->where($sql)->count('id');
			import("@.ORG.Page");
			$p=New Page($count,$nums);
			$list=$this->where($sql)->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$vo['count'] = $count;
			$vo['page']  = $page;
			$vo['list']  = $list;
		 return $vo;
		}

	public function getone(){   //得到一条记录的资料
		$id=$_REQUEST["id"];
		$act=$_REQUEST['act'];
		$trade_id=$_REQUEST['trade'];
		if($act=='up'){
			$sql="id>$id and trade_id=$trade_id and status=1";
			$order="id asc";
		}elseif($act=='down'){
			$sql="id<$id and trade_id=$trade_id and status=1";
			$order="id desc";
		}else{
			$sql="id=$id";
			}
		$vo=$this->where($sql)->order($order)->find();
		if(!$vo){
			$vo=$this->where("id=$id")->find();
			}
		return $vo;
		}

}
?>