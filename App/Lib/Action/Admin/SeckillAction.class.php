<?php
/**
  +------------------------------------------------------------------------------
  * 秒杀兑换模块
  +------------------------------------------------------------------------------
  * @category Home
  +------------------------------------------------------------------------------
 */
class SeckillAction extends CommonAction {
	
	function index(){
		$model=M("Seckill");
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
		
		$sql="1=1";
		if($_REQUEST['title']!=''){
		     $sql=$sql." and title like'%".$_REQUEST['title']."%'";
			 $this->assign('title',$_REQUEST['title']);
		}
		if($_REQUEST['ktype']!=''){
		     $sql=$sql." and ktype =$_REQUEST[ktype]";
			 $this->assign('ktype',$_REQUEST['ktype']);
		}
		if($_REQUEST['time1']!=''){
		     $time1=strtotime($_REQUEST['time1']);
		     $sql=$sql." and update_time >=$time1";
			 $this->assign('time1',$_REQUEST['time1']);
		}
		if($_REQUEST['time2']!=''){
		     $time2=strtotime($_REQUEST['time2'])+60*60*24;
		     $sql=$sql." and update_time <$time2";
			 $this->assign('time2',$_REQUEST['time2']);
		}
        $count = $model->where($sql)->count('id');
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->where($sql)->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
		}

    // 插入数据
	public function insert() {
        $model = D("Seckill");
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
        $model = D("Seckill");
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
        $model = M("Seckill");
        $id = (int)$_REQUEST ['id'];
        $vo = $model->getById($id);
        $this->assign('vo', $vo);
        $this->display();
    }
}

?>