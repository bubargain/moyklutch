<?php
//模块
class NewsAction extends Action{
	
	public function index(){
		$vo=D("News")->getlist();
		$this->assign('count',$vo['count']);
		$this->assign('page',$vo['page']);
		$this->assign('list',$vo['list']);
		$this->assign('title',"最新动态");
		$this->display();
		}
		
	public function show(){
		$vo=D("News")->getone();
		$this->assign("vo",$vo);
		$this->display();
		}
	}
?>