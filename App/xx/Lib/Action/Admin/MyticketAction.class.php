<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class MyticketAction extends CommonAction {
	
	function index(){
	   $model=M("Ticket");
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
		$id    = $_SESSION[C('USER_AUTH_KEY')];
		$u     = M("User");
		$user  = $u->where("id=$id")->find();
        $tradeid=$_SESSION["trade_id"];
		if(!$tradeid){
			$utmodel=M("User_trade");
			$t=$utmodel->where("user_id=$user[id]")->order("id desc")->find();
			$tradeid=$t['trade_id'];
			}
		$sql="trade_id=$tradeid";
		if($_REQUEST['account']!=''){
		     $sql=$sql." and account like'%".$_REQUEST['account']."%'";
			 $this->assign('account',$_REQUEST['account']);
		}
		if($_REQUEST['title']!=''){
		     $sql=$sql." and title like'%".$_REQUEST['title']."%'";
			 $this->assign('title',$_REQUEST['title']);
		}
		if($_REQUEST['time1']!=''){
		     $time1=strtotime($_REQUEST['time1']);
		     $sql=$sql." and create_time >=$time1";
			 $this->assign('time1',$_REQUEST['time1']);
		}
		if($_REQUEST['status']!=''){
		     $time1=$_REQUEST['status'];
		     $sql=$sql." and status=$_REQUEST[status]";
			 $this->assign('status',$_REQUEST['status']);
		}else{
			$sql=$sql." and status>-1";
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
			$userlist=$model->where($sql)->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
		}



    // 插入数据
	public function insert() {
		$id    = $_SESSION[C('USER_AUTH_KEY')];
		$u     = M("User");
		$user  = $u->where("id=$id")->find();
		$model = M("Trade");
		$mytrade=$model->where("id=$user[trade_id]")->find();
        $model = M("Ticket");
		if (false === $model->create()) {
            $this->error($model->getError());
        }
		$arr=getimagesize($_FILES["Img"]["tmp_name"]); 
		if(!$arr[0]){
			$this->error('请添加添加图片');
			}
		$data['name']=$_REQUEST['name'];
		$data['title']=$_REQUEST['title'];
		$data['trade_id']=$mytrade['id'];
		$data['cate_id']=$_REQUEST['cate_id'];
		$data['status']=$_REQUEST['status'];
		$data['num']=$_REQUEST['num'];
		$data['money']=$_REQUEST['money'];
		$data['create_time']=time();
		$data['start_time']=strtotime($_REQUEST['start_time']);
		$data['close_time']=strtotime($_REQUEST['close_time']);
		$data['score']=$_REQUEST['score'];
		$data['keyword']=$_REQUEST['keyword'];
		$data['tags']=$_REQUEST['tags'];
		$data['sortnum']=$_REQUEST['sortnum'];
		$data['attention']=$_REQUEST['attention'];
		$data['introduce']=$_REQUEST['introduce'];
		$data['content']=$_REQUEST['content'];
        $list = $model->add($data);
		if(false != $list){
			if($arr[0]){
			   if($arr[0]<800||$arr[1]<400){
					$this->error('添加图片失败！图片尺寸不低于800*400');
				  }else{
					unlink("./img/image/$id.jpg");
					import("@.ORG.UploadFile");
					$upload = new UploadFile();
					$upload->thumb = true;
					$upload->thumbMaxWidth = 800;
					$upload->thumbMaxHeight = 400;
					$upload->thumbPrefix = "";
					$upload->thumbPath = './img/image/';
					$upload->savePath = './img/image/';
					$upload->saveRule = 'uniqid';
					 if(!$upload->upload("$list")) {
					   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
					   $this->error($msg['info']);
					  }
					$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
					$da['logo']="./img/image/"."$list".".jpg";
					$model->where("id=$list")->save($da);
				 }
			}
		   $this->_writelog("添加优惠券",$data['title']);
		   $this->success('添加成功！');
		}else{
		  $this->error('添加失败！');
		}

    }
	
	    // 更新数据
	public function update() {
        $model = M("Ticket");
		if (false === $model->create()) {
            $this->error($model->getError());
        }
		$arr=getimagesize($_FILES["Img"]["tmp_name"]);  
		$id=$_REQUEST["id"];
		$data['name']=$_REQUEST['name'];
		$data['title']=$_REQUEST['title'];
		$data['cate_id']=$_REQUEST['cate_id'];
		$data['num']=$_REQUEST['num'];
		$data['money']=$_REQUEST['money'];
		$data['start_time']=strtotime($_REQUEST['start_time']);
		$data['close_time']=strtotime($_REQUEST['close_time']);
		$data['update_time']=time();
		$data['score']=$_REQUEST['score'];
		$data['keyword']=$_REQUEST['keyword'];
		$data['tags']=$_REQUEST['tags'];
		$data['sortnum']=$_REQUEST['sortnum'];
		$data['attention']=$_REQUEST['attention'];
		$data['introduce']=$_REQUEST['introduce'];
		$data['content']=$_REQUEST['content'];
        $list = $model->where("id=$id")->save($data);
		if(false != $list){
		  if($arr[0]){
			if($arr[0]<800||$arr[1]<400){
				 $this->error('编辑失败！图片尺寸不低于800*400');
			}else{
			unlink("./img/image/$id.jpg");
			import("@.ORG.UploadFile");
			$upload = new UploadFile();
			$upload->thumb = true;
			$upload->thumbMaxWidth = 800;
			$upload->thumbMaxHeight = 400;
			$upload->thumbPrefix = "";
			$upload->thumbPath = './img/image/';
			$upload->savePath = './img/image/';
			$upload->saveRule = 'uniqid';
			if(!$upload->upload("$id")) {
				 $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
				 $this->error($msg['info']);
			  }
			}
		  }
		   $this->_writelog("编辑优惠券",$data['title']);
		   $this->success('编辑成功！');
		}else{
		  $this->error('编辑失败！');
		}

    }

	 function edit() {
        $model = M("Ticket");
        $id = $_REQUEST ['id'];
        $vo = $model->getById($id);
        $this->assign('vo', $vo);
		$model = M("Cate");
		$catelist = $model->select();
		$this->assign("cate",$catelist);
        $this->display();
    }
	
//ajax修改优惠券排序
    public function aupdate() {
        $model = M("Ticket");
        $id = $_REQUEST ['id'];
        $data['sortnum'] = $_REQUEST ['sortnum'];
        $model->where("id=$id")->save($data);
    }
	
//优惠券统计	
	public function showcount(){
	   $model  = M("Ticket_statistic");
	   $typeid = $_REQUEST["type"];
	   $uid    = $_REQUEST["id"];

	   $count = $model->where("ticket_id=$uid and action_type=$typeid")->count('id');
	   if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$list=$model->where("ticket_id=$uid and action_type=$typeid")->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
	   $this->display();
	   }
	   
    public function foreverdelete() {
        $model = M("Ticket");
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
				$data['status'] = -1;
				$data['update_time'] =time();
                if (false !== $model->where("id=$id")->save($data)) {
					$this->_writelog("删除","记录ID号:$id");
                    $this->success('禁用成功！');
                } else {
                    $this->error('禁用失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
        $this->forward();
    }
}

?>