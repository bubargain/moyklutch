<?php
class GoodsoverAction extends CommonAction{
	//秒杀结束的商品列表
	function index(){
		$count = M('SeckillGoods')->where('endtime<'.time())->count();
		import("@.ORG.Page");
		$p = new Page($count, 15);
		$page = $p->show();
		$list = M()->query("SELECT a.*,b.title AS classname FROM think_seckill_goods AS a LEFT JOIN think_seckill_classify AS b ON a.cid=b.id WHERE endtime<".time().' ORDER BY id DESC LIMIT '.$p->firstRow.','.$p->listRows);
		$this->assign('page',$page);
		$this->assign("list",$list);
		$this->display();
	}

	//完成领取
	function over(){
		$data['status'] = 3;
		$id = $_GET['id'];
		$list = M('SeckillGoods')->where('id='.$id)->save($data);
		if(false != $list){
		   $this->success('领取完成！');
		}else{
		  $this->error('领取失败！');
		}
	}

	//用户领取
	function shipments(){
		$data['sectype'] = 2;
		$id = $_GET['id'];
		$list = M('SeckillUser')->where('id='.$id)->save($data);
		if(false != $list){
		   $this->success('领取完成！');
		}else{
		  $this->error('领取失败！');
		}
	}

	//秒杀成功用户列表
	function user(){
		$id = $_GET['id'];
		$this->title = M("SeckillGoods")->where("id=".$id)->getField('title');
		$this->list = M('SeckillUser')->field('think_seckill_user.*,think_user.account')->join("think_user ON think_user.id=think_seckill_user.uid")->where('think_seckill_user.gid='.$id.' AND think_seckill_user.sectype<>0')->order('think_seckill_user.typeid DESC')->select();
		//dump($this->list);exit;
		//echo M()->getLastSql();exit;
		$this->display();
	}

	function delete(){
		$id = $_GET['id'];
		$res = M('SeckillGoods')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
}
?>