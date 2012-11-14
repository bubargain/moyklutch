<?php
/**
  +------------------------------------------------------------------------------
  * 新闻管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class NewsAction extends CommonAction {

	function index(){
		$model=M("News");
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
		if($_GET['title']!=''){
		     $sql=$sql." and title like'%".$_GET['title']."%'";
			 $this->assign('title',$_GET['title']);
		}
		if($_GET['stitle']!=''){
		     $sql=$sql." and stitle =$_GET[stitle]";
			 $this->assign('stitle',$_GET['stitle']);
		}
 	    if($_GET['cid']!=''){
		     $sql=$sql." and cid =$_GET[cid]";

			 $this->assign('cid',$_REQUEST['cid']);
		}
		if($_REQUEST['status']!=''){
		     $sql=$sql." and status =$_REQUEST[status]";
			 $this->assign('status',$_REQUEST['status']);
		}else{
			$sql=$sql." and status >0";
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
		if($_SESSION['sql']!=$this->getActionName()){
		   $_SESSION['sql']=$this->getActionName();
		   $_SESSION["news_sql"]=$sql;
		}
		if($_REQUEST['flag']){  //保存状态
		   $_SESSION["news_sql"]=$sql;
		}
        $count = $model->where($_SESSION["news_sql"])->count('id');
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->field('think_newclass.id as cid,think_news.id as id,title,stitle,name,author,tags,count_num,status,create_time,update_time')->join('LEFT JOIN think_newclass ON think_news.cid = think_newclass.id' )->where($_SESSION["news_sql"])->limit($p->firstRow . ',' . $p->listRows)->order("`" . $order . "` " . $sort)->findAll();
        	$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
		}

    // 插入数据
	public function insert() {
        $model = M("News");
        $data['cid']=$_REQUEST['cid'];
		$data['title']=$_REQUEST['title'];
		$data['stitle']=$_REQUEST['stitle'];
		$data['author']=$_REQUEST['author'];
		$data['tags']=$_REQUEST['tags'];
		$data['status']=$_REQUEST['status'];
		$data['create_time']=mktime();
		$data['update_time']=mktime();
		$data['content']=$_REQUEST['content'];
		$arr=getimagesize($_FILES["Img"]["tmp_name"]);
		if($arr[0]){
		        import("@.ORG.UploadFile");
				$upload = new UploadFile();
				$upload->thumb = true;
				$upload->thumbMaxWidth = 100;
				$upload->thumbMaxHeight = 100;
				$upload->thumbPrefix = "";
				$upload->thumbPath = './img/thumnews/';
				$upload->savePath = './img/news/';
				$upload->saveRule = 'uniqid';
				if(!$upload->upload()) {
				   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
				   $this->error($msg['info']);
				  }
				$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
				$data['path']="/img/news/".$uploadList[0]['savename'];
				$data['thumpath']="/img/thumnews/".$uploadList[0]['savename'];
			}
        $list = $model->add($data);
		if(false != $list){
		   $this->_writelog("添加新闻",$data['title']);
		   $this->success('添加成功！');
		}else{
		  $this->error('添加失败！');
		}

    }


	    // 更新数据
	public function update() {
        $model = M("News");
		$id=$_REQUEST["id"];
 	    $data['cid']=$_REQUEST['cid'];
		$data['title']=$_REQUEST['title'];
		$data['stitle']=$_REQUEST['stitle'];
		$data['author']=$_REQUEST['author'];
		$data['tags']=$_REQUEST['tags'];
		$data['status']=$_REQUEST['status'];
		$data['update_time']=time();
		$data['content']=$_REQUEST['content'];
        $arr=getimagesize($_FILES["Img"]["tmp_name"]);
		if($arr[0]){
		        import("@.ORG.UploadFile");
				$upload = new UploadFile();
				$upload->thumb = true;
				$upload->thumbMaxWidth = 100;
				$upload->thumbMaxHeight = 100;
				$upload->thumbPrefix = "";
				$upload->thumbPath = './img/thumnews/';
				$upload->savePath = './img/news/';
				$upload->saveRule = 'uniqid';
				if(!$upload->upload()) {
				   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
				   $this->error($msg['info']);
				  }
				$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
				$data['path']="/img/news/".$uploadList[0]['savename'];
				$data['thumpath']="/img/thumnews/".$uploadList[0]['savename'];
			}
		$list = $model->where("id=$id")->save($data);
		if(false != $list){
		   $this->_writelog("编辑新闻",$data['title']);
		   $this->success('编辑成功！');
		}else{
		  $this->error('编辑失败！');
		}

    }

	 function edit() {
        $model = M("News");
        $id = $_REQUEST ['id'];
        $vo = $model->getById($id);
        $this->assign('vo', $vo);
        $this->display();
    }

	    //审核
	function shh(){
		$model = M("Ticket");
		$sql   = "status=0";
        $count = $model->where($sql)->count('id');
		if($_REQUEST['submit']=='导出'){
			$downlist=$model->where($sql)->select();
			$this->down($downlist);
		}
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->where($sql)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
	}
}

?>