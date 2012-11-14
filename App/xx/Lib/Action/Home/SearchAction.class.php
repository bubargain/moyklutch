<?php
//模块
class SearchAction extends Action{
	
    //导航搜索
    public function index(){
		$m     = trim($_REQUEST['mod']);
		$model = M($m);
		$title = word_filter(trim($_REQUEST['title']));
		
		if($m == "Trade"){
		   $m = "Shop";
		}
		//搜索为空时转向相应模块
		if(!$title){
			$this->redirect("$m/index");
		}

		if($m == 'Ticket'){
			$map['_string'] = "(title like '%".$title."%') OR (keyword like '%".$title."%')";
		}else{
			$map['title']   = array('like',"%$title%");
		}
		
		
		
		//记录搜索关键字
		$tag    = M("Tag");
		$result =$tag->where($map)->find();
		if(!$result){
			$data['title'] = trim($_REQUEST['title']);
			$tag->add($data);
			}else{
			$tag->setInc('search_count',"id=$result[id]");
			}  //

		$count=$model->count('id');
		if($count > 0){
			//import("@.ORG.Page");
			//$p=New Page($count,10);
			//$list=$model->where($map)->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$list=$model->where($map)->order("id desc")->findAll();
			//$page=$p->show();
			$vo['count'] = $count;
			//$vo['page']  = $page;
			$vo['list']  = $list;
			

	    }
		
		$this->assign("list",$vo['list']);
		$this->assign('count',$vo['count']);
		//$this->assign('page',$vo['page']);
		$this->display("$m:index");
	}
}
?>