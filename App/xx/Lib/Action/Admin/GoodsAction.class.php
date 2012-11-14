<?php
/**
  +------------------------------------------------------------------------------
  * 秒杀商品模块
  +------------------------------------------------------------------------------
  * @category Home
  +------------------------------------------------------------------------------
 */
class GoodsAction extends CommonAction {
	//商品列表
	function index(){
		$Model = M();
		$list = $Model->query("SELECT a.*,b.title AS classname FROM think_seckill_goods AS a LEFT JOIN think_seckill_classify AS b ON a.cid=b.id WHERE endtime>".time());
		$this->assign("list",$list);
		$this->display();
	}

	//商品分类
	function classify(){
		$this->display();
	}

	//商品分类
	function _before_add(){
		$class = M('SeckillClassify');
		$list = $class->select();
		$this->assign('list',$list);
    }

    // 插入数据
	public function insert() {
		$model = M("SeckillGoods");
		$data['title']=$_REQUEST['title'];
		$data['cid']=$_REQUEST['cid'];
		$data['starttime']=strtotime($_REQUEST['sectime'].' '.$_REQUEST['starttime']);
		$data['endtime']=strtotime($_REQUEST['sectime'].' '.$_REQUEST['endtime']);
		$data['num']=$_REQUEST['num'];
		$data['score']=$_REQUEST['score'];
		$data['signupscore']=$_REQUEST['signupscore'];
		$data['failscore']=$_REQUEST['failscore'];
		$data['price']=$_REQUEST['price'];		
		$data['content']=$_REQUEST['content'];
		$arr=getimagesize($_FILES["Img"]["tmp_name"]); 
		if($arr[0]){
		        import("@.ORG.UploadFile");
				$upload = new UploadFile();
				$upload->thumb = true;
				$upload->thumbMaxWidth = 168;
				$upload->thumbMaxHeight = 118;
				$upload->thumbPrefix = "";
				$upload->thumbPath = './img/thumkill/';
				$upload->savePath = './img/kill/';
				$upload->saveRule = 'uniqid';
				if(!$upload->upload()) {
				   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
				   $this->error($msg['info']);
				  }
				$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
				$data['path']="/img/kill/".$uploadList[0]['savename'];
				$data['thumpath']="/img/thumkill/".$uploadList[0]['savename'];	
			}
        $list = $model->add($data);
		if(false != $list){
		   $this->_writelog("添加秒杀商品",$data['title']);
		   $this->success('添加成功！');
		}else{
		  $this->error('添加失败！');
		}
    }
	
	    // 更新数据
	public function update() {
        $model = M("SeckillGoods");
		$id=$_REQUEST["id"];
		$data['title']=$_REQUEST['title'];
		$data['cid']=$_REQUEST['cid'];
		$data['starttime']=strtotime($_REQUEST['sectime'].' '.$_REQUEST['starttime']);
		$data['endtime']=strtotime($_REQUEST['sectime'].' '.$_REQUEST['endtime']);
		$data['num']=$_REQUEST['num'];
		$data['score']=$_REQUEST['score'];
		$data['signupscore']=$_REQUEST['signupscore'];
		$data['failscore']=$_REQUEST['failscore'];
		$data['price']=$_REQUEST['price'];	
		$data['content']=$_REQUEST['content'];
		$arr=getimagesize($_FILES["Img"]["tmp_name"]); 
		if($arr[0]){
		        import("@.ORG.UploadFile");
				$upload = new UploadFile();
				$upload->thumb = true;
				$upload->thumbMaxWidth = 168;
				$upload->thumbMaxHeight = 118;
				$upload->thumbPrefix = "";
				$upload->thumbPath = './img/thumkill/';
				$upload->savePath = './img/kill/';
				$upload->saveRule = 'uniqid';
				if(!$upload->upload()) {
				   $msg['info'] = $upload->getErrorMsg(); //获取错误返回信息
				   $this->error($msg['info']);
				  }
				$uploadList = $upload->getUploadFileInfo();//获取传送的数据信息
				$data['path']="/img/kill/".$uploadList[0]['savename'];
				$data['thumpath']="/img/thumkill/".$uploadList[0]['savename'];	
			}
        $list = $model->where('id='.$id)->save($data);
		if(false != $list){
		   $this->_writelog("修改秒杀商品",$data['title']);
		   $this->success('修改成功！');
		}else{
		  $this->error('修改失败！');
		}
    }
	
	 function edit() {
		$class = M('SeckillClassify');
		$list = $class->select();
		$this->assign('list',$list);

        $model = M("SeckillGoods");
        $id = (int)$_REQUEST ['id'];
        $this->vo = $model->getById($id);
        $this->display();
    }

	function getActionName(){
		return "SeckillGoods";
	}

	//审核
	function audit(){
		$data['status'] = 1;
		$id = $_GET['id'];
		$list = M('SeckillGoods')->where('id='.$id)->save($data );
		if(false != $list){
		   $this->success('审核完成！');
		}else{
		  $this->error('审核失败！');
		}
	}

}

?>