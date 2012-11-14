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

class CommentModel extends RelationModel {
    
    //关联属性的定义
    protected $_link=array(
           'trade'=>array(
               'mapping_type'=>BELONGS_TO,
               'foreign_key'=>'pid'
           ),
          
    );
	
	//************************************************************************************
	//************************************************************************************
	//此方法修改过
	public function getlist($module){
		$count=$this->where("pid=$_REQUEST[id] and module='".$module."' and status=1")->count('id');
			import("@.ORG.Page");
			$p=New Page($count,20);
			$list=$this->where("pid=$_REQUEST[id] and module='".$module."' and status=1")->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$vo['count'] = $count;
			$vo['page']  = $page;
			$vo['list']  = $list;
		return $vo;
	}
	//修改end
	
	public function addone(){
		$data["pid"]    = $_REQUEST["pid"];
		$data['module'] = $_REQUEST['module'];
		$data['star']   = $_REQUEST['star'];
		$data["user_id"]    = $_SESSION[C('USER_AUTH_KEY')];
		$data["content"]    = word_filter($_REQUEST['content']);
		$data["create_time"]= time();
		$vo=$this->add($data);
		return $vo;
		}
}
?>