<?php
//模块
class InfoAction extends Action{
	
	public function show(){
		$vo=D("Info")->getone();
		$this->assign("vo",$vo);
		$this->display();
		}
	}
?>