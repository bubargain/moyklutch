<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$
class TradeModel extends CommonModel {
    protected $_validate =  array(
            array('name','require','英文名称必须！'),
            array('title','require','商家名称必须！'),
            array('title','','商家已存在',1,'unique',1),
            array('mobile','checkphone','电话号码或移动电话至少填写一项',1,'callback',3),
            array('email','email','电子邮件格式错误'),
           );
    //检查电话号码
    public function checkphone()
    {
        if(empty($_POST['mobile']) && empty($_POST['phone']))
        {
            return false;
        }
        return true;
    }
	
	public function getone(){
		$id=$_REQUEST["id"];
		$vo=$this->where("id=$id")->find();
		return $vo;
		}
	
	public function getlistall(){
		$sql="status=1";
		if($_REQUEST["cate_id"]){
			$ticketlist =D('ticket')->where("cate_id=$_REQUEST[cate_id]")->group("trade_id")->select();//根据优惠券类别查找商家
			$str="(-1";
			foreach($ticketlist as $k=>$v){
				$str.=",$v[trade_id]";
				}
			$str.=")";
			$sql.=" and id in $str";
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
			    $sql.=" and id in $str";
		}
			$count=$this->where($sql)->count('id');
			import("@.ORG.Page");
			$p=New Page($count,8);
			$list=$this->where($sql)->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$vo['list'] = $list;
			$vo['count']= $count;
			$vo['page'] = $page;
			return $vo;
		}

}
?>