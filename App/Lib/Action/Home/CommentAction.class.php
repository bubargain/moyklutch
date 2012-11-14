<?php
//模块
class CommentAction extends CommonAction{
	
	public function index(){
		$module = $_REQUEST['module'];
		$vo     = D("Comment")->getlist($module);
		$this->assign('count',$vo['count']);
		$this->assign('page',$vo['page']);
		$this->assign('list',$vo['list']);
		$this->assign('title',get_ticket($_REQUEST["id"]));
		$this->assign('ticket_id',$_REQUEST["id"]);
		$this->display();
		}
		
	public function insert(){
		if(D("Comment")->addone()){
			$this->success("提交成功！");
			}else{
			$this->error("操作失败！");
			}
		}
	}
?>