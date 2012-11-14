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

class HuodongModel extends CommonModel {
	
	public function getlist(){
		if($_REQUEST["typeid"]!=""){
			$sql="typeid=$_REQUEST[typeid]";
			}else{
			$sql="";
			}
		$count=$this->where($sql)->count('id');
		if($count > 0){
			import("@.ORG.Page");
			$p=New Page($count,7);
			$list=$this->where($sql)->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$vo['count'] = $count;
			$vo['page']  = $page;
			$vo['list']  = $list;
			if($_REQUEST["typeid"]){
			   $vo['title'] = "试用派发";
			  }else{
			   $vo['title'] = "精彩活动";  
				  }
			
		    }
		return $vo;
	}
	
	public function getone(){
		if($_REQUEST['id']){
			$sql="id=$_REQUEST[id]";
			}else{
			$sql="";
			}
		$vo=$this->where($sql)->find();
		return $vo;
		}


}
?>