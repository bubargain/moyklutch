<?php
//模块
class ShopAction extends Action{
	public function index(){
	 if(!empty($_REQUEST["area"])){
        $sql['status'] = 1;
	    $sql['branch.area']=intval($_REQUEST[area]);
        $count = M("Trade")->alias('trade')->join('think_trade_branch as branch ON trade.id=branch.id')->where($sql)->count();
		import("@.ORG.Page");
		$p=New Page($count,6);
		$list=M("Trade")->alias('trade')
                        ->join('think_trade_branch as branch ON trade.id=branch.id')
                        ->where($sql)
                        ->order("trade.id desc")
                        ->limit($p->firstRow . ',' . $p->listRows)
                        ->select();
			$page=$p->show();
			$vo['list'] = $list;
			$vo['count']= $count;
			$vo['page'] = $page;
  	        $this->assign('list',$vo['list']);
		    $this->assign('count',$vo['count']);
	    	$this->assign('page',$vo['page']);
		    $this->assign('search',$_GET);
	    }else{
        $vo=D('Trade')->getlistall();
		$this->assign('list',$vo['list']);
		$this->assign('count',$vo['count']);
		$this->assign('page',$vo['page']);
		$this->assign('search',$_GET);
         }
       $this->display();
	}

	public function show(){
		$vo=D('Trade')->getone();

		//计数加一
		$id = (int)$_REQUEST["id"];
		$data['click_count']=array("exp","click_count+1");
		M("Trade")->where("id=$id")->save($data);

		//更多展示图片
		$attach=M("Attach");
		$att   =$attach->where("module='trade' and bid=$id")->select();


		//********************************加的****************************×××××××××××××/
		//×××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××××
		//评论是否开启
		$config=M("Config");
		$config_list = $config->getField('comment_if');
 	    $this->assign('config_list',$config_list[0]);
		//修改end


		//相关评论
		$com = D("Comment")->where("review=1")->getlist('trade');
		$this->assign('count',$com['count']);
		$this->assign('page',$com['page']);
		$this->assign('comment',$com['list']);
		$this->assign('att', $att);
		$this->assign('vo',$vo);
		$this->display();
		}
	}
?>