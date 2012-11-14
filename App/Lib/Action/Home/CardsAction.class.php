<?php
//模块
class CardsAction extends Action{
	
	public function index(){
		$vo=D("Cards")->getlist();
		$this->assign('count',$vo['count']);
		$this->assign('page',$vo['page']);
		$this->assign('list',$vo['list']);
		$this->assign('title',"个性卡");
		$this->display();
		}
		
	public function show(){
		$vo=D("Cards")->getone();
		$this->assign("vo",$vo);
		$this->display();
		}
	}
?>