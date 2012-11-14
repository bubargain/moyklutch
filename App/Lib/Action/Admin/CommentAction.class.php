<?php
/**
  +------------------------------------------------------------------------------
  * 评论管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class CommentAction extends CommonAction {
	
	function index(){
		$model=M("Comment");
		 //排序字段 默认为主键名
        if (isset($_REQUEST ['_order'])) {
            $order = $_REQUEST ['_order'];
        } else {
            $order = $model->getPk();
        }
        //排序方式默认按照倒序排列
        if (isset($_REQUEST ['_sort'])) {
			$sort = $_REQUEST['_sort'];
			if($sort == 'asc'){
			$this->assign('sort','desc');
			} else{
			$this->assign('sort','asc');
			}
        } else {
            $sort = 'desc';
			$this->assign('sort',$sort);
        }
        //查询
		$sql="1=1";
		if($_REQUEST['status']!=''){
		     $sql=$sql." and status =$_REQUEST[status]";
			 $this->assign('status',$_REQUEST['status']);
		}else{
			$sql=$sql." and status >=-2";
			}
		if($_REQUEST['time1']!=''){
		     $time1=strtotime($_REQUEST['time1']);
		     $sql=$sql." and create_time >=$time1";
			 $this->assign('time1',$_REQUEST['time1']);
		}
		if($_REQUEST['time2']!=''){
		     $time2=strtotime($_REQUEST['time2'])+60*60*24;
		     $sql=$sql." and create_time <$time2";
			 $this->assign('time2',$_REQUEST['time2']);
		}
        $count = $model->where($sql)->count('id');
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->order("`" . $order . "` " . $sort)->where($sql)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
		}

	    // 更新数据
        function edit() {
        $model = M("Comment");
        $id = $_REQUEST ['id'];
        $vo = $model->getById($id);
        $this->assign('vo', $vo);
        $this->display();
    }
	public function update() {
        $model = M("Comment");
		$id=$_REQUEST["id"];
		$data['status']=$_REQUEST['status'];
		$data['content']=$_REQUEST['content'];
		$list = $model->where("id=$id")->save($data);
		if(false != $list){
		   $this->_writelog("修改评论",$data['title']);
		   $this->success('编辑成功！');
		}else{
		  $this->error('编辑失败！');
		}

    }
	
 	
}

?>