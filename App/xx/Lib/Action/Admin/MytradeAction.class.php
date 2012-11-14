<?php
/**
  +------------------------------------------------------------------------------
  * 用户管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class MytradeAction extends CommonAction {

	function index(){
		$userid    = $_SESSION[C('USER_AUTH_KEY')];
        $tradeid=$_SESSION["trade_id"];
		if(!$tradeid){
			$model=M("User_trade");
			$t=$model->where("user_id=$userid")->find();
			$tradeid=$t['trade_id'];
			}
		$model = M("Trade");
		$mytrade=$model->where("id=$tradeid")->find();
		$this->assign('vo',$mytrade);
		$this->display();
	  }
	// 更新数据
    public function update(){
      $data = $_POST;
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
            }
            $data['logo'] = $logo['data'][0]['savepath'].$logo['data'][0]['filename'];
       }
       $status =M('Trade')->where("id=".(int)$_REQUEST['id'])
                          ->setField(array('status','extra'),array(3,serialize($data)));
       if(false!==$status)
       {
            $this->_writelog("商家编辑信息",$data['title']);
            $this->success('您的修改已提交，管理员审核后您的信息将会被更新');
       }else{
            $this->error('更新错误，请重试');
       }
    }

	 function edit() {
		$userid    = $_SESSION[C('USER_AUTH_KEY')];
        $tradeid=$_SESSION["trade_id"];
		if(!$tradeid){
			$model=M("User_trade");
			$t=$model->where("user_id=$userid")->order("id desc")->find();
			$tradeid=$t['trade_id'];
			}
		$model = M("Trade");
		$mytrade=$model->where("id=$tradeid")->find();
        if($mytrade['extra']!='')
        {
            $data = unserialize($mytrade['extra']);
            $mytrade = array_merge($mytrade,$data);
        }
		$this->assign('vo',$mytrade);
        $this->display();
    }

}

?>