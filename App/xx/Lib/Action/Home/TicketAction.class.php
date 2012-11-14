<?php
//模块
class TicketAction extends Action{
	public function index(){
		$list=D('Ticket')->getlistall(10);
        //dump(M()->getlastsql());
		$this->assign('count',$list['count']);
		$this->assign('page',$list['page']);
		$this->assign('list',$list['list']);
		$this->assign('title','优惠券');
        $this->assign('search',$_GET);
		$sql="SELECT cate_id  FROM `think_ticket` where status=1 GROUP BY cate_id order by count(cate_id) desc limit 0,8";
		$hotcate=M("Ticket")->query($sql);
		$this->assign("hotcate",$hotcate);
		$this->display();
		}

	public function show(){
		$id = (int)$_REQUEST["id"];

		//计数加一
		$data['click_count']=array("exp","click_count+1");
		M("Trade")->where("id=$id")->save($data);

		//得到优惠券资料
		$vo=D('Ticket')->getone();
		$this->assign('vo',$vo);
		$this->display();
		}

	}
?>