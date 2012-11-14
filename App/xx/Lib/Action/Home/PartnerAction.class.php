<?php
//模块
class PartnerAction extends Action{
	
	public function index(){
		$vo=D("Partner")->getlist();
		$this->assign('count',$vo['count']);
		$this->assign('page',$vo['page']);
		$this->assign('list',$vo['list']);
		$this->assign('title',"合作伙伴");
		$this->display();
		}
	}
?>