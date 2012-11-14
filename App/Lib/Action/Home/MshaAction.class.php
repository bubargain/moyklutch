<?php
//模块
class MshaAction extends Action{
	public function index(){
		$this->assign('title','秒杀');
		$this->display();
		}
	}
?>