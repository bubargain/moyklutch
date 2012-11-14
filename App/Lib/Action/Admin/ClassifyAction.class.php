<?php
class ClassifyAction extends CommonAction{
	public function index(){
		$model = M("SeckillClassify");
		$list= $model->select();
		$this->assign("list",$list);
		$this->display();
	}

	// 插入数据
	public function insert() {
        $model = D("SeckillClassify");
		if(!$model->create()){
			$this->error($model->getError());
		}
		if($model->add()){
		   $this->success('添加成功！');
		}else{
		  $this->error('添加失败！');
		}
    }
	
	
	    // 更新数据
	public function update() {
        $model = D("SeckillClassify");
		if(!$model->create()){
			$this->error($model->getError());
		}
		if($model->save()){
		   $this->success('更新成功！');
		}else{
		  $this->error('更新失败！');
		}
    }
	
	 function edit() {
        $model = M("SeckillClassify");
        $id = (int)$_REQUEST ['id'];
        $vo = $model->getById($id);
        $this->assign('vo', $vo);
        $this->display();
    }
	
	function getActionName(){
		return "SeckillClassify";
	}
}
?>