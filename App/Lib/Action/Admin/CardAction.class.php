<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class CardAction extends CommonAction {

	function index(){
		$model=M("Card");
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
		if($_REQUEST['id']!=''){
			 $sql=$sql." and id=$_REQUEST[id]";
			 $this->assign('id',$_REQUEST['id']);
		}
		if($_REQUEST['userif']){
            $sql .= " and status=".(int)$_REQUEST['userif'];
			$this->assign('userif',$_REQUEST['userif']);
		}
		if($_REQUEST['card_id']!=''){
		     $sql=$sql." and card_id like'%".$_REQUEST['card_id']."%'";
			 $this->assign('card_id',$_REQUEST['card_id']);
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
		if($_REQUEST['submit']=='删除过期卡号'){
			$m=M("Config_card");
			$conf=$m->find();
			$over=time()-3600*24*$conf['password_expire'];
            $model->where("create_time<$over and type_id=1")->delete();
			$this->success('删除完毕！');
		}
		if($_SESSION['sql']!=$this->getActionName()){
		   $_SESSION['sql']=$this->getActionName();
		   $_SESSION["card_sql"]=$sql;
		}
		if($_REQUEST['flag']){  //保存状态
		   $_SESSION["card_sql"]=$sql;
		}
        $count = $model->where($_SESSION["card_sql"])->count('id');
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->where($_SESSION["card_sql"])->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
    }

    // 插入数据
    public function insert() {
        // 创建数据对象
        $model = M("Card");
        if (!$model->create()) {
            $this->error($model->getError());
        } else {
		$data['card_id']    = $_REQUEST['card_id'];
		$data['password']   = $_REQUEST['password'];
		$data['remark']     = $_REQUEST['remark'];
		$data['status']     = $_REQUEST['status'];
		$data['create_time']= time();
            if ($result = $model->add($data)) {
				$this->_writelog("添加会员卡",$data['card_id']);
                $this->success('添加成功！');
            } else {
                $this->error('添加失败！');
            }
        }
    }

		// 更新数据
	public function update() {
        $model = M("Card");
		if (false === $model->create()) {
            $this->error($model->getError());
        }
        $id                 = $_REQUEST['id'];
		$data['card_id']    = $_REQUEST['card_id'];
		$data['password']   = $_REQUEST['password'];
		$data['remark']     = $_REQUEST['remark'];
		$data['status']     = $_REQUEST['status'];
        $list = $model->where("id=$id")->save($data);
		if(false != $list){
		  $this->_writelog("更新会员卡",$data['card_id']);
		  $this->success('更新成功！');
		}else{
		  $this->error('更新失败！');
		}
    }

	public function imports(){
		$this->display();
		}
		//导入卡编码
	public function card(){
		$model = M("Card");
		$file = fopen($_FILES['card']["tmp_name"],"r");//打开文件
        //$mydata=file($_FILES['card']["tmp_name"]);//把整个文件读入到一个数组中，一行一个元素
		//dump($mydata);
		while(!feof($file)){        //当文件不结束
			 $line=fgets($file);        //读文本文件一行
			 $data['card_id']    = trim($line);
			 $data['create_time']= time();
			 $model->add($data);
		}
         fclose($file);//文件关闭
		$this->success('导入成功！');
	}

}

?>