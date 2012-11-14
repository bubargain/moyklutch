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

class CardsModel extends CommonModel {
	
	public function getlist(){
		$count=$this->count('id');
		if($count > 0){
			import("@.ORG.Page");
			$p=New Page($count,10);
			$list=$this->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
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