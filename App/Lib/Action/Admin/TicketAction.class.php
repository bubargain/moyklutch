<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class TicketAction extends CommonAction {

	function index(){
		$model=M("Ticket");
		 //排序字段 默认为主键名
        if (isset($_REQUEST ['_order'])) {
            $order = $_REQUEST ['_order'];
        } else {
            $order = $model->getPk();
        }
		if($order=="point"||$order=="prints"||$order=="collect"){
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
		if($_REQUEST['resultid']!=""){    //结果排序
			$order=$_REQUEST['resultid'];
			$sort=$_REQUEST['sortid'];
			$this->assign("resultid",$_REQUEST['resultid']);
			$this->assign("sortid",$_REQUEST['sortid']);
			}
		    if($_REQUEST["clist"]!=""){
				$value = array();
				$value = $_REQUEST['clist'];
				$_SESSION["ttvalue"]=$value;
			}
			if(!$_SESSION["ttvalue"]){
				$this->assign("menuticket",1);
				$this->assign("menucate_id",1);
				$this->assign("menupoint",1);
				$this->assign("menuprints",1);
				$this->assign("menucollect",1);
				$this->assign("menutrade_id",1);
				$this->assign("menuscore",1);
				$this->assign("menustart_time",1);
				$this->assign("menuclose_time",1);
				}
			if($_SESSION["ttvalue"]){
					foreach($_SESSION["ttvalue"] as $k=>$v){
					$this->assign("menu".$v,1);
					}
			}
		$sql="1=1";
		if($_REQUEST['id']!=''){
			 $sql=$sql." and id=$_REQUEST[id]";
			 $this->assign('id',$_REQUEST['id']);
		}
		if($_REQUEST['title']!=''){
		     $sql=$sql." and title like'%".$_REQUEST['title']."%'";
			 $this->assign('title',$_REQUEST['title']);
		}
//		if($_REQUEST['trade_id']!=''){
//			$tsql="title like'%".$_REQUEST['trade']."%'";
//			$tmodel=M("Trade");
//			$tlist=$tmodel->where($tsql)->select();
//			$tstr="(0";
//			foreach($tlist as $k=>$tv){
//				$tstr=$tstr.",$tv[id]";
//				}
//			$tstr=$tstr.")";
//         $sql=$sql." and trade_id in ".$tstr;
		//$this->assign('trade',$_REQUEST['trade']);
	//	}
		if($_REQUEST['trade_id']!=''){
		     $sql=$sql." and trade_id =$_REQUEST[trade_id]";
			 $this->assign('trade_id',$_REQUEST['trade_id']);
		}
		if($_REQUEST['cate_id']!=''){
		     $sql=$sql." and cate_id =$_REQUEST[cate_id]";
			 $this->assign('cate_id',$_REQUEST['cate_id']);
		}
		if($_REQUEST['status']!=''){
			if($_REQUEST['status']==3){
			}else{
				$sql=$sql." and status =$_REQUEST[status]";
				}
			 $this->assign('status',$_REQUEST['status']);
		}else{
			$sql=$sql." and status >-1";
			}
		//优惠券 过期 有效 未开始
			if($_REQUEST['tstatus']!=''){
				if($_REQUEST['tstatus']==1){
					$sql=$sql." and close_time >".time();
					}
				if($_REQUEST['tstatus']==-1){
					$sql=$sql." and close_time <".time();
					}
				if($_REQUEST['tstatus']==0){
					$sql=$sql." and start_time >".time();
					}
				$this->assign('tstatus',$_REQUEST['tstatus']);
				}else{
					$sql=$sql." and close_time >".time();
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
		//if($_SESSION['sql']!=$this->getActionName()){
//		   $_SESSION['sql']=$this->getActionName();
		   $_SESSION["ticket_sql"]=$sql;
//		}
		if($_REQUEST['flag']){  //保存状态
		   $_SESSION["ticket_sql"]=$sql;
		}
		if($_REQUEST['submit']=='exp'){
			$downlist=$model->where($_SESSION["ticket_sql"])->select();
			$this->down($downlist);
		}
        $count = $model->where($_SESSION["ticket_sql"])->count('id');
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->where($_SESSION["ticket_sql"])->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$order = $_REQUEST ['_order'];
			if($_SESSION['t']!=$this->getActionName()){
			   $_SESSION['t']=$this->getActionName();
			   $_SESSION['tt']=0;
			  $_SESSION['tt1']=0;
			  $_SESSION['tt2']=0;
			}
			if($_REQUEST['flag']){  //保存状态
			 $_SESSION['tt1']=0;
			 $_SESSION['tt2']=0;
		    }
			if($_REQUEST["tt"]!=''){
			  $_SESSION['tt']=$_REQUEST["tt"];
			  $tt1=time()-3600*24*$_REQUEST["tt"];
			  $tt2=time();
			  $_SESSION['tt1']=$tt1;
			  $_SESSION['tt2']=$tt2;
			}
			$this->assign("tt",$_SESSION['tt']);
			if($_REQUEST["tt1"]!=''){
			$tt1=strtotime($_REQUEST["tt1"]);
			$_SESSION['tt1']=$tt1;
			}else{
			$tt1=0;
			}
			if($_SESSION['tt1']==0){
			$this->assign('tt1',"");
			}else{
			$this->assign('tt1',date("Y-m-d",$_SESSION['tt1']));
			}
			if($_REQUEST["tt2"]!=''){
			$tt2=strtotime($_REQUEST["tt2"]);
			$_SESSION['tt2']=$tt2;
			}else{
			$tt2=0;
			}
			if($_SESSION['tt2']==0){
			$this->assign('tt2',"");
			}else{
			$this->assign('tt2',date("Y-m-d",$_SESSION['tt2']));
			}
			foreach($userlist as $k=>$val){
			  $val["point"]=ticket_count_byticket($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],0);
			  $val["prints"]=ticket_count_byticket($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],1);
			  $val["collect"]=ticket_count_byticket($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],2);
			  $userlist[$k]=$val;
			  if($order=="point"){
			  $r[]=$val['point'];
			  }
			  if($order=="prints"){
			  $r[]=$val['prints'];
			  }
			  if($order=="collect"){
			  $r[]=$val['collect'];
			  }
			}
			if($order=="point"||$order=="prints"||$order=="collect"){
			   $sort=$_SESSION["sort"]==SORT_DESC?SORT_ASC:SORT_DESC;
			   $_SESSION["sort"]=$sort;
			   $out = array_multisort($r,$sort,$userlist);
			}
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
        $model = M("Ticket");
		if (false === $model->create()) {
            $this->error($model->getError());
        }
		$arr=getimagesize($_FILES["Img"]["tmp_name"]);
		if(!$arr[0]){
			$this->error('请添加添加图片');
			}
		$data['title']=$_REQUEST['title'];
		$data['trade_id']=$_REQUEST['trade_id'];
		$data['cate_id']=$_REQUEST['cate_id'];
		$data['status']=$_REQUEST['status'];
		$data['num']=$_REQUEST['num'];
		$data['money']=$_REQUEST['money'];
		$data['create_time']=time();
		$data['update_time']=time();
		$data['start_time']=strtotime($_REQUEST['start_time']);
		$data['close_time']=strtotime($_REQUEST['close_time'])+3600*24-1;
		$data['score']=$_REQUEST['score'];
		$data['keyword']=$_REQUEST['keyword'];
        $data['tags']      = $_REQUEST['tags'];
		$data['sortnum']=$_REQUEST['sortnum'];
		$data['attention']=$_REQUEST['attention'];
		$data['introduce']=$_REQUEST['introduce'];
		$data['content']=$_REQUEST['content'];
        $list = $model->add($data);
		if(false != $list){
        //×××××××××××××××××××××修改的×××××××××××××××××××××
             $savepath= C('SAVEPATH');  //上传文件保存路径
             $thumbpath= C('THUMBPATH');  //上传文件缩略图保存路径
             $imagesize=C('IMAGESIZE');  //上传文件的大小
             $maxwidth= C('THUMBMAXWIDTH');  //缩略图对打宽度
             $maxhight=C('THUMDMAXHIGHT');  //缩略图最大高度
             $imgwidth= C('IMGWIDTH');  //图片宽度
             $imghight=C('IMGHIGHT');  //图片高度
			if($arr[0]){
			   if($arr[0]<$imgwidth||$arr[1]<$imgwidth){
					$this->error("添加图片失败！图片尺寸不低于 $imgwidth*$imghight");
				  }else{

					unlink($savepath.$id.".jpg");
					import("@.ORG.UploadFile");
					$upload = new UploadFile();
					$upload->thumb = true;
					$upload->thumbMaxWidth = $maxwidth;
					$upload->thumbMaxHeight =$maxhight;
					$upload->thumbPrefix = "";
					$upload->thumbPath =$thumbpath;
					$upload->savePath = $savepath;
					$upload->saveRule = 'uniqid';
					 if(!$upload->upload("$list")) {
					   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
					   $this->error($msg['info']);
					  }
					$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
					$da['logo']="/$savepath"."$list".".jpg";
     //×××××××××××××××××××××修改的××××××××××××××××××××
					$model->where("id=$list")->save($da);
				 }
			}
		   $this->_writelog("添加优惠券",$data['title']);
		   $this->assign("jumpUrl", __ROOT__."/editor/demo.php?id=$list&name=$data[title]");
		   $this->success('添加成功！请继续添加打印机打印图片');
		}else{
		  $this->error('添加失败！');
		}

    }

/*
	    // 更新数据
	public function update() {
        $model = M("Ticket");
		if (false === $model->create()) {
            $this->error($model->getError());
        }
		$arr=getimagesize($_FILES["Img"]["tmp_name"]);
		$id=$_REQUEST["id"];
		$data['title']=$_REQUEST['title'];
		$data['cate_id']=$_REQUEST['cate_id'];
		$data['status']=$_REQUEST['status'];
		$data['num']=$_REQUEST['num'];
		$data['money']=$_REQUEST['money'];
		$data['start_time']=strtotime($_REQUEST['start_time']);
		$data['close_time']=strtotime($_REQUEST['close_time'])+3600*24-1;
		$data['update_time']=time();
		$data['score']=$_REQUEST['score'];
		$data['keyword']=$_REQUEST['keyword'];
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
*/
	    // 更新数据
	public function update() {
        $model = M("Ticket");
		if (false === $model->create()) {
            $this->error($model->getError());
        }
        //修改的××××××××××××××××××××××××××××××××××××××××
         $savepath= C('SAVEPATH');  //上传文件保存路径
         $thumbpath= C('THUMBPATH');  //上传文件缩略图保存路径
         $imagesize=C('IMAGESIZE');  //上传文件的大小
         $maxwidth= C('THUMBMAXWIDTH');  //缩略图对打宽度
         $maxhight=C('THUMDMAXHIGHT');  //缩略图最大高度
         $imgwidth= C('IMGWIDTH');  //图片宽度
         $imghight=C('IMGHIGHT');  //图片高度
         //修改的××××××××××××××××××××××××××××××××××××××××
		$arr=getimagesize($_FILES["Img"]["tmp_name"]);
		$id=$_REQUEST["id"];
		$data['title']=$_REQUEST['title'];
		$data['cate_id']=$_REQUEST['cate_id'];
		$data['status']=$_REQUEST['status'];
		$data['num']=$_REQUEST['num'];
		$data['money']=$_REQUEST['money'];
		$data['start_time']=strtotime($_REQUEST['start_time']);
		$data['close_time']=strtotime($_REQUEST['close_time'])+3600*24-1;
		$data['update_time']=time();
		$data['score']=$_REQUEST['score'];
		$data['keyword']=$_REQUEST['keyword'];
        $data['tags']      = $_REQUEST['tags'];
		$data['sortnum']=$_REQUEST['sortnum'];
		$data['attention']=$_REQUEST['attention'];
        //×××××××××××××××××××××修改的××××××××××××××××××××
		$data['logo'] = "/$savepath".$id.'.jpg';
        //×××××××××××××××××××××修改的××××××××××××××××××××
		$data['introduce']=$_REQUEST['introduce'];
		$data['content']=$_REQUEST['content'];
        $list = $model->where("id=$id")->save($data);
       //dump(M()->getlastsql());die();
         //×××××××××××××××××××××修改的××××××××××××××××××××
		if(false != $list){
		  if($arr[0]){
			if($arr[0]<$imgwidth||$arr[1]<$imghight){
				 $this->error("编辑失败！图片尺寸不低于$imgwidth*$imghight");
			}else{
			unlink($savepath.$id.".jpg");
			import("@.ORG.UploadFile");
			$upload = new UploadFile();
			$upload->thumb = true;
			$upload->thumbMaxWidth = $maxwidth;
			$upload->thumbMaxHeight =$maxhight;
			$upload->thumbPrefix = "";
			$upload->thumbPath = $thumbpath;
			$upload->savePath = $savepath;
             //×××××××××××××××××××××修改的××××××××××××××××××××
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

    function down($data){
		$filename =date('y年-m月-d日')."——优惠券资料";
		$filename=mb_convert_encoding($filename,'gb2312','utf-8');
		$title = array(
			'trade_id' => '商家',
			'cate_id' => '类别',
			'name' => '英文标识',
			'title' => '中文名',
			'start_time' => '开始时间',
			'close_time' => '结束时间',
			'tags' => '标签',
			'score' => '打印所需积分',
			'keyword' => '关键字',
			'money' => '商家结算额',
			'num' => '数量',
		);
		foreach($data as $k=>$val){
			$val['trade_id']=get_trade($val['trade_id']);
			$val['cate_id']=get_cate($val['cate_id']);
			$val['start_time']=date("Y年-m月-d日:H时",$val['start_time']);
			$val['close_time']=date("Y年-m月-d日:H时",$val['close_time']);
			if($val['num']<0){
				$val['num']="无限制";
				}
			$data[$k]=$val;
			}
		import("@.ORG.Down");
		Down::down_xls($data,$title,$filename);
	 }

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

    //批量转换缩略图
    public function test() {
//          $list = M('Ticket')->field('logo,id')->where('id=1623')->find();
//          import("@.ORG.Image");
//          @unlink('./img/imagethum/1623.jpg');
//          Image::thumb('.'.$list['logo'],'./img/imagethum/1623.jpg','',130,90,true);
        $id = empty($_GET['id']) ? 1 : (int)$_GET['id'];
        $star = ($id -1)*50;
        $sql = "select id,logo from think_ticket limit $star,50";
        $list = M()->query($sql);
        import("@.ORG.Image");
        if($list)
        {
            foreach ($list as $key => $val)
            {
                @unlink('./img/imagethum/'.$val['id'].'.jpg');
                Image::thumb('.'.$val['logo'],'./img/imagethum/'.$val['id'].'.jpg','',130,90,true);
            }
        } else {
            echo '已经处理完！';die;
        }
        $_id = $id + 1;
        $url = __URL__;
        echo "<a href='$url/test/id/$_id'>下一批</a>";
        return ;
    }
}

?>