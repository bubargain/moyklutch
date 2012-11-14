<?php
//模块
class HuodongAction extends Action{
	public function index(){
		$vo=D("Huodong")->getlist();
		$this->assign('count',$vo['count']);
		$this->assign('page',$vo['page']);
		$this->assign('list',$vo['list']);
		$this->assign('title',$vo['title']);
		$this->display();
		}
		
	public function show(){
		$vo=D("Huodong")->getone();
		$this->assign("vo",$vo);
		$this->display();
		}
   }
?>