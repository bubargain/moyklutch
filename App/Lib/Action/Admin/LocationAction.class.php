<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class LocationAction extends CommonAction {
	
	function index(){
		$model=M("Location");
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
		if($_REQUEST['name']!=''){
		     $sql=$sql." and name like'%".$_REQUEST['name']."%'";
			 $this->assign('name',$_REQUEST['name']);
		}
		if($_REQUEST['macno']!=''){
		     $sql=$sql." and macno like'%$_REQUEST[macno]%'";
			 $this->assign('macno',$_REQUEST['macno']);
		}
		if($_REQUEST['area']!=''){
		     $sql=$sql." and area =$_REQUEST[area]";
			 $this->assign('area',$_REQUEST['area']);
		}
		if($_REQUEST['status']!=''){
		     $sql=$sql." and status =$_REQUEST[status]";
			 $this->assign('status',$_REQUEST['status']);
		}
		//租赁到期时间
		if($_REQUEST['tstatus']!=''){
			$time=time()+3600*30*$_REQUEST['tstatus'];
			$sql=$sql." and close_time <".$time;
			$this->assign('tstatus',$_REQUEST['tstatus']);
			}
			
		if($_REQUEST['s_time1']!=''){
		     $time1=strtotime($_REQUEST['s_time1']);
		     $sql=$sql." and start_time >=$s_time1";
			 $this->assign('s_time1',$_REQUEST['s_time1']);
		}
		if($_REQUEST['s_time2']!=''){
		     $time1=strtotime($_REQUEST['s_time2'])+60*60*24;
		     $sql=$sql." and start_time <$s_time2";
			 $this->assign('s_time2',$_REQUEST['s_time2']);
		}
		if($_REQUEST['c_time1']!=''){
		     $time1=strtotime($_REQUEST['c_time1']);
		     $sql=$sql." and close_time >=$c_time1";
			 $this->assign('c_time1',$_REQUEST['c_time1']);
		}
		if($_REQUEST['c_time2']!=''){
		     $time2=strtotime($_REQUEST['c_time2'])+60*60*24;
		     $sql=$sql." and close_time <$c_time2";
			 $this->assign('c_time2',$_REQUEST['c_time2']);
		}
		if($_SESSION['sql']!=$this->getActionName()){
		   $_SESSION['sql']=$this->getActionName();
		   $_SESSION["loc_sql"]=$sql;
		}
		if($_REQUEST['flag']){  //保存状态
		   $_SESSION["loc_sql"]=$sql;
		}
        $count = $model->where($_SESSION["loc_sql"])->count('id');
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->where($_SESSION["loc_sql"])->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}

		$this->display();
		}

    //ajax修改备注
    public function aupdate() {
        $model = M("Location");
		$arr = $_REQUEST;
        $id = $_REQUEST ['id'];
        $data['remark'] = $_REQUEST ['remark'];
        $model->where("id=$id")->save($data);
    }
	
	    // 更新数据
	public function update() {
        $name = $this->getActionName();
        $model = M($name);
		if (false === $model->create()) {
            $this->error($model->getError());
        }
		$id=$_REQUEST['id'];
		$data['name']=$_REQUEST['name'];
		$data['macno']=$_REQUEST['macno'];
		$data['address']=$_REQUEST['address'];
		$data['contact']=$_REQUEST['contact'];
		$data['phone']=$_REQUEST['phone'];
		$data['open']=$_REQUEST['open'];
		$data['close']=$_REQUEST['close'];
		$data['money']=$_REQUEST['money'];
		$data['start_time']=strtotime($_REQUEST['start_time']);
		$data['close_time']=strtotime($_REQUEST['close_time']);
		$data['status']=$_REQUEST['status'];
		$data['remark']=$_REQUEST['remark'];
		$data['update_time']=time();
        $list = $model->where("id=$id")->save($data);
		if(false != $list){
		  $this->_writelog("编辑机器放置地点",$data['name']);
		  $this->success('编辑成功！');
		}else{
		  $this->error('编辑失败！');
		}
    }
    // 插入数据
    public function insert() {
        // 创建数据对象
        $model = M("Location");
        if (!$model->create()) {
            $this->error($model->getError());
        } else {
            // 写入数据
			$data['name']=$_REQUEST['name'];
			$data['macno']=$_REQUEST['macno'];
			$data['area']=$_REQUEST['area'];
			$data['address']=$_REQUEST['address'];
			$data['contact']=$_REQUEST['contact'];
			$data['phone']=$_REQUEST['phone'];
			$data['open']=$_REQUEST['open'];
			$data['close']=$_REQUEST['close'];
			$data['money']=$_REQUEST['money'];
			$data['start_time']=strtotime($_REQUEST['start_time']);
			$data['close_time']=strtotime($_REQUEST['close_time']);
			$data['create_time']=time();
			$data['update_time']=time();
			$data['status']=$_REQUEST['status'];
			$data['remark']=$_REQUEST['remark'];
            if ($result = $model->add($data)) {
				$m = M("Exhibition");
				for($i=1;$i<16;$i++){
					$d['p_id']=$result;
					$d['bt_position']="DX".$i;
					$m->add($d);
					}
				$this->_writelog("添加机器放置地点",$data['name']);
                $this->success('添加成功！');
            } else {
                $this->error('添加失败！');
            }
        }
    }
	
	function showexh(){
		$model = M("Exhibition");
		$id    = (int)$_REQUEST["id"];
		$list  = $model->where("p_id=$id")->select();
		$this->assign("list",$list);
		$this->display();
		}
		
	function showten(){
		$id    = (int)$_REQUEST["id"];
		$model = M("Location");
		$title = $model->where("id={$id}")->getField('name');
		$model = M("Tenancy");
		$sql="p_id=$id and (close_time+3600*24)>".time();
		$list  = $model->where($sql)->getField('bt_position,trade_id');
		$this->assign("pid",$id);
		$this->assign("title",$title);
		$this->assign("list",$list);
		$this->display();
		}
		
	function delten(){
		$pid  = (int)$_REQUEST["pid"];
		$bp    = $_REQUEST["bt"];
		$model=M("Tenancy");
		$model->where("p_id={$pid} and bt_position='$bp'")->delete();
		$this->assign("jumpUrl",__GROUP__."/Location/showten/id/".$pid); 
		$this->success("删除成功！");
		}

}

?>
