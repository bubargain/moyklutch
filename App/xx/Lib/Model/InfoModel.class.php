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

class InfoModel extends CommonModel {
	
	public function getone(){
		if($_REQUEST['id']){
			$sql="id=$_REQUEST[id]";
		}
		$vo=$this->where($sql)->find();
		return $vo;
		}


}
?>