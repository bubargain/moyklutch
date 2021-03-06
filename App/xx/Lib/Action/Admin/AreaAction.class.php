<?php
/**
  +------------------------------------------------------------------------------
  * 分类管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class AreaAction extends CommonAction {

    public function index() {
	    if($_SESSION['sql']!=$this->getActionName()){
		   $_SESSION['sql']=$this->getActionName();
		   }
        $model  = M("Area");
		$clist  = $model->where("pid=0 and status=1")->select();
		$listall= $model->where("status=1")->select();
		$this->assign("clist",$clist);
		$this->assign("listall",$listall);
		$this->display();
    }

    //添加新的分类
    public function add() {
        $this->display();
    }
	
	// 插入数据
    public function insert() {
        // 创建数据对象
        $model = M("Area");
        if (!$model->create()) {
            $this->error($model->getError());
        } else {
            // 写入数据
			$data['title']=$_REQUEST['title'];
			$data['name']=$_REQUEST['name'];
			$data['pid']=$_REQUEST['pid'];
			$data['remark']=$_REQUEST['remark'];
            if ($result = $model->add($data)) {
				$this->_writelog("添加商圈",$data['title']);
                $this->success('添加成功！');
            } else {
                $this->error('添加失败！');
            }
        }
    }
	
	// 更新数据
	public function update() {
        $name = $this->getActionName();
        $model = M($name);
		if (false === $model->create()) {
            $this->error($model->getError());
        }
		$id=$_REQUEST['id'];
		$data['title']=$_REQUEST['title'];
		$data['name']=$_REQUEST['name'];
		$data['pid']=$_REQUEST['pid'];
		$data['remark']=$_REQUEST['remark'];
        $list = $model->where("id=$id")->save($data);
		if(false != $list){
			$this->_writelog("编辑商圈",$data['title']);
		  $this->success('编辑成功！');
		}else{
		  $this->error('编辑失败！');
		}
    }
	
	public function ajaxarea(){		
		$id    = $_REQUEST["id"];
		$model = M("Area");
		$list  = $model->where("pid=$id and status=1")->select();
		if($list){
		$str   = "-请选择-";
		foreach($list as $k=>$val){
		  $str = $str."|".$val['title'].",".$val['id'];
			}
		echo $str;
		}else{
			echo "-暂无-";
			}
		
		}
	

}
?>