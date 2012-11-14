<?php
class NewclassAction extends CommonAction{
		
		function index(){
			$newclass=D('Newclass');
			$list=$newclass->field("id,name,pid,path,concat(path,'-',id) as bpath")->order('bpath')->select();
			foreach($list as $key=>$value){
				$list[$key]['count']=count(explode('-',$value['bpath']));
			}
			$this->assign('alist',$list);
			$this->display();
		}
		function insert(){
				$newclass=new NewclassModel();
				if($vo=$newclass->create()){
				if($newclass->add()){
						$this->success('添加分类成功');
					}else{
						$this->error('添加分类失败');
					}
				}else{
					$this->error($newclass->getError());
				}
		}
	     // 更新数据
	public function update() {
        $model = M("Newclass");
		$id=$_REQUEST["id"];
		$data['name']=$_REQUEST['name'];
		$list = $model->where("id=$id")->save($data);
		if(false != $list){
		   $this->success('修改成功！');
		}else{
		  $this->error('修改失败！');
		}

    }
	
	 function edit() {
        $newclass = M("Newclass");
        $id = $_REQUEST ['id'];
        $vo = $newclass->getById($id);
        $this->assign('vo', $vo);
        $this->display();
    }
	
}
?>