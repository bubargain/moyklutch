<?php
/**
  +------------------------------------------------------------------------------
  * 打印机错误日志模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class TrademoneyAction extends CommonAction {
	
	function index(){
		$model=M("Trademoney");
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
		  $_SESSION["tmo_sql"]=$sql;
		}
		if($_REQUEST['flag']){  //保存状态
		   $_SESSION["tmo_sql"]=$sql;
		}
        $count = $model->where($_SESSION["tmo_sql"])->count('id');
		if($count > 0){
		import("@.ORG.Page");
			$p=New Page($count,C('PAGE_LIST_ROWS'));
			$userlist=$model->where($_SESSION["tmo_sql"])->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll();
			$page=$p->show();
			$this->assign('list',$userlist);
			$this->assign('page',$page);
		}
		$this->display();
		}
}

?>