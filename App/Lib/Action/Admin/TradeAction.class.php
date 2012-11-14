<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class TradeAction extends CommonAction {

	function index(){
		$model = M("Trade");
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
		if($_REQUEST['resultid']!=""){    //结果排序
			$order=$_REQUEST['resultid'];
			$sort=$_REQUEST['sortid'];
			$this->assign("resultid",$_REQUEST['resultid']);
			$this->assign("sortid",$_REQUEST['sortid']);
			}
		    if($_REQUEST["clist"]!=""){
				$value = array();
				$value = $_REQUEST['clist'];
				$_SESSION["tvalue"]=$value;
			}
			if(!$_SESSION["tvalue"]){
				$this->assign("menuticket",1);
				$this->assign("menutags",1);
				$this->assign("menupoint",1);
				$this->assign("menuprints",1);
				$this->assign("menucollect",1);
				$this->assign("menuexh",1);
				$this->assign("menuupdate_time",1);
				}
			if($_SESSION["tvalue"]){
					foreach($_SESSION["tvalue"] as $k=>$v){
					$this->assign("menu".$v,1);
					}
			}
		$sql='1=1';
		if($_REQUEST['id']!=''){
			 $sql=$sql." and id=$_REQUEST[id]";
			 $this->assign('id',$_REQUEST['id']);
		}
		if($_REQUEST['cate_id']!=''){
			$ticket = M("Ticket");
			$tlist  = $ticket->where("cate_id=$_REQUEST[cate_id]")->select();
			$str="(-1";
		    foreach($tlist as $k=>$val){
				$str=$str.",".$val['trade_id'];
				}
			$str=$str.")";
			$sql="id in $str";
		}
		if($_REQUEST['area']!=''){
			$loc = M("Location");
			$alist  = $loc->where("area=$_REQUEST[area]")->select();
			$str="(-1";
		    foreach($alist as $k=>$val){
				$str=$str.",".$val['id'];
				}
			$str=$str.")";
			$tsql="p_id in $str";
			$ten=M("Tenancy");
			$tlist=$ten->where($tsql)->select();
			$str="(-1";
			foreach($tlist as $k=>$val){
				$str=$str.",".$val['trade_id'];
				}
			$str=$str.")";
			$sql=$sql." and id in $str";
			
		}
		if($_REQUEST['title']!=''){
		     $sql=$sql." and title like'%".$_REQUEST['title']."%'";
			 $this->assign('title',$_REQUEST['title']);
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
		if($_REQUEST['tags']!=''){
			$sql.="and  tags like'%".$_REQUEST['tags']."%'";
//			$tagmodel=M("Tag");
//			$taglist=$tagmodel->where($tagsql)->select();
//			$tagstr="tags in (-1";
//			foreach($taglist as $k=>$tv){
//				$tagstr=$tagstr.",$tv[id]";
//				}
//			$tagstr=$tagstr.")";
//			$sql=$sql." and ".$tagstr;
			$this->assign('tags',$_REQUEST['tags']);
		}
		if($_REQUEST['money1']!=''){
		     $sql=$sql." and money >=$_REQUEST[money1]";
			 $this->assign('money1',$_REQUEST['money1']);
		}
		if($_REQUEST['money2']!=''){
		     $sql=$sql." and money <$_REQUEST[money2]";
			 $this->assign('money2',$_REQUEST['money2']);
		}
		if($_REQUEST['getmoney1']!=''){
		     $sql=$sql." and getmoney >=$_REQUEST[getmoney1]";
			 $this->assign('getmoney1',$_REQUEST['getmoney1']);
		}
		if($_REQUEST['getmoney2']!=''){
		     $sql=$sql." and getmoney <$_REQUEST[getmoney2]";
			 $this->assign('getmoney2',$_REQUEST['getmoney2']);
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
		if($_SESSION['sql']!=$this->getActionName()){
		   $_SESSION['sql']=$this->getActionName();
		   $_SESSION["trade_sql"]=$sql;
		}
		if($_REQUEST['flag']){  //保存状态
		   $_SESSION["trade_sql"]=$sql;
		}
        $count = $model->where($_SESSION["trade_sql"])->count('id');

		if($_REQUEST['submit']=='exp'){
			$downlist=$model->where($_SESSION["trade_sql"])->select();
			$this->down($downlist);
		}

		if($count > 0){
		import("@.ORG.Page");
		$p=New Page($count,C('PAGE_LIST_ROWS'));
				if($order=="point"||$order=="prints"||$order=="collect"){
		           $userlist=$model->where($_SESSION["trade_sql"])->limit($p->firstRow . ',' . $p->listRows)->findAll();
		           }else{
				   $userlist=$model->where($_SESSION["trade_sql"])->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
				   }

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
//			    if($_REQUEST["tt"]==11){  //今天
//					$d=date("Y-m-d",time());
//					$tt1=strtotime($d);
//					$tt2=$tt1+3600*24;
//				}elseif($_REQUEST["tt"]==22){//昨天
//					$d=date("Y-m-d",time());
//					$tt2=strtotime($d);
//					$tt1=$tt2-3600*24;
//				}elseif($_REQUEST["tt"]==33){//本周
//					$d1=date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
//					$d2=date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
//					$tt1=strtotime($d1);
//					$tt2=strtotime($d2);
//				}elseif($_REQUEST["tt"]==44){//上周
//					$d=date("Y-m-d",time());
//					$tt2=strtotime($d);
//					$tt1=$tt2-3600*24;
//				}elseif($_REQUEST["tt"]==55){//本月
//					$d=date("Y-m-01",time());
//					$tt1=strtotime($d);
//					$tt2=time();
//				}elseif($_REQUEST["tt"]==66){//上月
//					$d1=date('Y-m-01',strtotime(date('Y',time()).'-'.(date('m',time())-1).'-01'));
//					$d2=date('Y-m-d',strtotime("$d1 +1 month -1 day"));
//					$tt1=strtotime($d1);
//					$tt2=strtotime($d2);
//				}else{
				$tt1=time()-3600*24*$_REQUEST["tt"];
				$tt2=time();
//					}
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
			  $val["point"]=ticket_count_bytrade($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],0);
			  $val["prints"]=ticket_count_bytrade($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],1);
			  $val["collect"]=ticket_count_bytrade($val["id"],$_SESSION['tt1'],$_SESSION['tt2'],2);
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

//修改授权用户
    public function aupdate() {
        $model  = M("Trade");
        $id     = $_POST['id'];
        $data['user_id'] = trim($_POST['user_id']);
        $model->where("id=$id")->save($data);
		$u     = explode(",",$data['user_id']);
		$user  = M("User");
		$length= count($u);
		for($i=0;$i<$length;$i++){
			$d['type_id']=1;
			$d['trade_id']=$id;
			$user->where("id=$u[$i]")->save($d);
			}
		$this->display("Trade:touser");
    }


    // 插入数据
	public function _before_insert(){
		  $arr=getimagesize($_FILES["Img"]["tmp_name"]); 
		  if(!$arr[0]){
			$this->error('请添加添加图片');
			}
		}
    public function _after_insert($id){
       if($_FILES['Img']['size']>0)
       {
            import("@.ORG.UploadLogo");
            $upload = new UploadLogo();
            $logo   = $upload->upload($id);
            if($logo['status']!==1)
            {
              //如果上传失败，跳转至更新页面
              $this->assign('jumpUrl', __URL__."/edit/id/{$id}");
              $this->error('添加成功，但Logo图片上传失败.<br/>'.$logo['info']);
            }else{
              //成功更换记录
              M('Trade')->where("id={$id}")
                        ->setField('logo',$logo['data'][0]['savepath'].$logo['data'][0]['filename']);
              $this->_writelog("添加商家",$_POST['title']);
            }
       }
    }

    function edit() {
        $model = M("Trade");
        $id = $_REQUEST["id"];
        $vo = $model->getById($id);
        if($vo['status']==3 && $vo['extra']!='')
        {
            //如果后台管理员修改则提出序列化的数据，供管理员进行修改和补充
            $vo = array_merge($vo,unserialize($vo['extra']));
            $vo['status']=1;//置状态为1
        }
		$attach=M("Attach");
		if($_REQUEST["edit"]){
				import("@.ORG.UploadFile");
				$upload = new UploadFile();
				$upload->thumb = true;
				$upload->thumbMaxWidth = 48;
				$upload->thumbMaxHeight = 48;
				$upload->thumbPrefix = "";
				$upload->thumbPath = './img/thumshop/';
				$upload->savePath = './img/shop/';
				$upload->saveRule = 'uniqid';
				if(!$upload->upload()) {
				   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
				   $this->error($msg['info']);
				  }
				$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
				$data['path']="/img/shop/".$uploadList[0]['savename'];
				$data['thumpath']="/img/thumshop/".$uploadList[0]['savename'];
				$data['module']="trade";
				$data['bid']=$id;
				$data['create_time']=time();
				$attach->add($data);
		}
		$att   =$attach->where("module='trade' and bid=$id")->select();
		$this->assign('att', $att);
        $this->assign('vo', $vo);
        $this->display();
    }
	
	
	//删除附件
	public function delattach(){
		$attach = M("Attach");
		$id      = (int)$_REQUEST['id']; 
		$attach->where("id=$id")->delete();
		$this->success("删除成功");
		 
	}
   // 修改数据

    public function _after_update($id){
       if($_FILES['Img']['size']>0)
       {
            import("@.ORG.UploadLogo");
            $upload = new UploadLogo();
            $logo   = $upload->upload($_POST['id']);
            if($logo['status']!==1)
            {
              //如果上传失败，跳转至更新页面
              $this->assign('jumpUrl', __URL__."/edit/id/{$_POST['id']}");
              $this->error('添加成功，但Logo图片上传失败.<br/>'.$logo['info']);
            }else{
              //成功更换记录
              M('Trade')->where("id={$_POST['id']}")
                        ->setField('logo',$logo['data'][0]['savepath'].$logo['data'][0]['filename']);
              $this->_writelog("修改商家",$_POST['title']);
            }
       }
    }

	function down($data){
		$filename =date('y年-m月-d日')."——商家资料";
		$filename=mb_convert_encoding($filename,'gb2312','utf-8');
		$title = array(
			'name' => '英文标识',
			'title' => '商家名称',
			'user_id' => '绑定的用户编号',
			'contact' => '联系人',
			'mobile' => '手机',
			'phone' => '电话',
			'email' => '邮箱',
			'tags' => '标签',
			'keyword' => '关键字',
			'getmoney' => '结算总额',
			'money' => '储值',
		);
		import("@.ORG.Down");
		Down::down_xls($data,$title,$filename);
	 }

  public function touser(){

	if($_REQUEST["account"]!=''){
        $id     = $_REQUEST['id'];
	    $model  = M("User");
		$sql="account like'%".$_POST['account']."%'";
        $user=$model->where($sql)->find();
        if($user){
			$d['type_id']=1;
			$model->where("id=$user[id]")->save($d);
			$ut=M("User_trade");
			$data['user_id']=$user['id'];
			$data['trade_id']=$id;
			$data['create_time']=time();
			$ut->add($data);
			}else{
			$this->error('没有找到该用户！');
				}
		$this->_writelog("为商家授权用户","商家ID:$id");
	}

	$id = $_REQUEST["id"];
	$t = M("User_trade");
	if($_REQUEST['did']!=""){
		$t->where("id=$_REQUEST[did]")->delete();
		}
	$userlist= $t->where("trade_id=$id")->select();
	$this->assign("id",$id);
	$this->assign("list",$userlist);
	$this->display();
	}


  public function deluser_trade(){
	  $model=M("User_trade");
	  $id=$_REQUEST['id'];
	  if($model->where("id=$id")->delete()){
		  $this->success('删除成功！');
		  }else{
		  $this->error('删除失败！');
			  }
	  }

	   //审核
	function shh(){
		$model = M("Trade");
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

		   //充值
	function addmoney(){
		$model = M("Trade");
		$id    = $_REQUEST["id"];
		if($_REQUEST["addmoney"]!=''){
			$money = $_REQUEST["money"];
			$d["money"]=$money+$_REQUEST["addmoney"];
			$model->where("id=$id")->save($d);
			$m = M("Paylist");
			$data["trade_id"]=$id;
			$data["remark"]  ="管理员".get_user($_SESSION[C('USER_AUTH_KEY')])."手动充值 ".$_REQUEST["remark"];
			$data["money"]   =$_REQUEST["addmoney"];
			$data["tmoney"]  =$d["money"];
			$data["create_time"]=time();
			$data["type"]    = 1;
			$m->add($data);
			}
		$vo    = $model->where("id=$id")->find();
		$this->assign("vo",$vo);
		$this->display();
	}


	public function recycle() {
        $name = $this->getActionName();
        $model = M($name);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
                $condition = "$pk=$id";
				$data['status'] = 1;
				$data['update_time']=time();
                $list = $model->where($condition)->save($data);
					//$m=M("Ticket");
					//$d["status"]=1;
					//$m->where("trade_id=$id")->save($d);
					//$m=M("Tenancy");
					//$m->where("trade_id=$id")->save($d);
                    $this->_writelog("恢复","记录ID号:$id");
            }
        }

    }

	public function foreverdelete() {
        //永久删除指定记录
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)){
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)){
				$data['status'] = -1;
				$data['user_id '] = '';
				$data['update_time']=time();
                if (false !== $model->where("id=$id")->save($data)){
					$m=M("Ticket");
					$d["status"]=-1;
					$d['update_time']=time();
					$m->where("trade_id=$id")->save($d);
					$m=M("Tenancy");
					$m->where("trade_id=$id")->save($d);
					$this->_writelog("删除","记录ID号:$id");
                    $this->success('删除成功！');
                 }else{
                    $this->error('删除失败！');
                }
            }else{
                $this->error('非法操作');
            }
        }
        $this->forward();
    }

	public function showmoney(){
		$model = M("Paylist");
		$id = $_REQUEST["id"];
		$sql="trade_id=$id";
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
			$list=$model->where($sql)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
	   $this->assign('id',$id);
	   $this->display();
		}

	public function showgetmoney(){
		$model = M("Trademoney");
		$id = $_REQUEST["id"];
		$sql="trade_id=$id";
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
			$list=$model->where($sql)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
	   $this->assign('id',$id);
	   $this->display();
		}
	public function branch(){
		$model=M("Trade_branch");
		$list=$model->where("trade_id=$_REQUEST[id]")->select();
		$this->assign("list",$list);
		$this->assign("trade_id",$_REQUEST['id']);
		$this->display();
		}
	public function addbranch(){
		$model=M("Trade_branch");
		$list=$model->where("trade_id=$_REQUEST[id]")->select();
		$this->assign("list",$list);
		$this->assign("trade_id",$_REQUEST['id']);
		$this->display();
		}
	public function editbranch(){
		$model=M("Trade_branch");
		$list=$model->where("id=$_REQUEST[id]")->find();
		$this->assign("vo",$list);
		$this->display();
		}
	public function addbranch2(){
	    $model=M("Trade_branch");
		$data['trade_id']=$_REQUEST['trade_id'];
		$data['title']=$_REQUEST['title'];
		$data['area']=$_REQUEST['area'];
		$data['address']=$_REQUEST['address'];
		$data['phone']=$_REQUEST['phone'];
		$data['remark']=$_REQUEST['remark'];
		$model->add($data);
        $this->success('添加成功！');
		}
	public function updatebranch(){
		$id=$_REQUEST['id'];
	    $model=M("Trade_branch");
		$data['trade_id']=$_REQUEST['trade_id'];
		$data['title']=$_REQUEST['title'];
		$data['area']=$_REQUEST['area'];
		$data['address']=$_REQUEST['address'];
		$data['phone']=$_REQUEST['phone'];
		$data['remark']=$_REQUEST['remark'];
		$model->where("id=$id")->save($data);
        $this->assign("jumpUrl", __APP__."/Trade/addbranch/id/$_REQUEST[trade_id]");
		$this->success("编辑成功！");
		}
	public function delbranch(){
		$id=$_REQUEST['id'];
	    $model=M("Trade_branch");
		$model->where("id=$id")->delete();
		$this->success("删除成功！");
		}

   public function validcont()
   {
       $id = (int)$_REQUEST['id'];
       $data = M('Trade')->where('id='.$id)->find();
       if(!$data || $data['status']!=3) $this->error('该商家填写内容正常，无需审核');
       $content = empty($data['extra'])?array():unserialize($data['extra']);
       $data  = array_merge($data,$content);
       $data['status']=1;
       $data['extra']='';
       $data['update_time'] =time();
       $status = M('Trade')->where('id='.$id)->save($data);
       if($status)
       {
            $this->success('审核成功');
       }else{
            $this->error('审核失败');
       }
   }
}
?>