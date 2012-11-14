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

class PartnerModel extends CommonModel {
	
	public function getlist(){
		$count=$this->where("typeid=1")->count('id');
		if($count > 0){
			import("@.ORG.Page");
			$p=New Page($count,10);
			$list=$this->where("typeid=1")->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$vo['count'] = $count;
			$vo['page']  = $page;
			$vo['list']  = $list;
	    }
		return $vo;
	}
	
	public function getone(){
		if($_REQUEST['id']){
			$sql="id=$_REQUEST[id]";
		}
		$vo=$this->where($sql)->find();
		return $vo;
		}


}
?>