<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class PartnerAction extends CommonAction {
	
	function index(){
		$model=M("Partner");
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
		if($_REQUEST['typeid']!=''){
		     $sql=$sql." and typeid =$_REQUEST[typeid]";
			 $this->assign('typeid',$_REQUEST['typeid']);
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
        $model = M("Partner");
		$data['title']=$_REQUEST['title'];
		$data['typeid']=$_REQUEST['typeid'];
		$data['address']=$_REQUEST['address'];
		$data['create_time']=time();
		$data['update_time']=time();
		$data['content']=$_REQUEST['content'];
		$arr=getimagesize($_FILES["Img"]["tmp_name"]); 
		if($arr[0]){
		import("@.ORG.UploadFile");
				$upload = new UploadFile();
				$upload->thumb = false;
				$upload->savePath = './img/partner/';
				$upload->saveRule = 'uniqid';
				if(!$upload->upload()) {
				   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
				   $this->error($msg['info']);
				  }
				$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
				$data['picpath']="/img/partner/".$uploadList[0]['savename'];
		}
        $list = $model->add($data);
		if(false != $list){
		   $this->_writelog("添加链接",$data['title']);
		   $this->success('添加成功！');
		}else{
		  $this->error('添加失败！');
		}

    }
	
	
	    // 更新数据
	public function update() {
        $model = M("Partner");
		$id=$_REQUEST["id"];
		$data['title']=$_REQUEST['title'];
		$data['typeid']=$_REQUEST['typeid'];
		$data['address']=$_REQUEST['address'];
		$data['update_time']=time();
		$data['content']=$_REQUEST['content'];
		$arr=getimagesize($_FILES["Img"]["tmp_name"]); 
		if($arr[0]){
		import("@.ORG.UploadFile");
				$upload = new UploadFile();
				$upload->thumb = false;
				$upload->savePath = './img/partner/';
				$upload->saveRule = 'uniqid';
				if(!$upload->upload()) {
				   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
				   $this->error($msg['info']);
				  }
				$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
				$data['picpath']="/img/partner/".$uploadList[0]['savename'];
		}
        $list = $model->where("id=$id")->save($data);
		if(false != $list){
		   $this->_writelog("编辑链接",$data['title']);
		   $this->success('编辑成功！');
		}else{
		  $this->error('编辑失败！');
		}

    }
	
	 function edit() {
        $model = M("Partner");
        $id = $_REQUEST ['id'];
        $vo = $model->getById($id);
        $this->assign('vo', $vo);
        $this->display();
    }
	


}

?>