<?php
/**
  +------------------------------------------------------------------------------
  * 系统配置管理模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
// 系统配置管理模块
class ConfigAction extends CommonAction {

    public function index(){
		  $model = M("Config");
		  $config= $model->find();
		  $this->assign("vo",$config);
		  if($_SESSION['sql']!=$this->getActionName()){
		   $_SESSION['sql']=$this->getActionName();
		   }
		  $this->display();
	}
	public function other(){
		  $model = M("Config");
		  $config= $model->find();
		  $this->assign("vo",$config);
		  $this->display();
	}


		// 更新数据
	public function update() {
        $model = M("Config");
		if (false === $model->create()) {
            $this->error($model->getError());
        }
        
		$id=$_REQUEST['id'];
		$data['webname']  = $_REQUEST['webname'];
		$data['zf_username']  = $_REQUEST['zf_username'];
		if($_REQUEST['zf_password']!=$_REQUEST['zf_password2']){
		$data['zf_password']=md5(trim($_REQUEST['zf_password']));
		   }
		$data['stmp_server']  = $_REQUEST['stmp_server'];
		$data['stmp_port']    = $_REQUEST['stmp_port'];
		$data['stmp_username']= $_REQUEST['stmp_username'];
		$data['stmp_password']= $_REQUEST['stmp_password'];
		$data['stmp_send']    = $_REQUEST['stmp_send'];
		$data['stmp_back']    = $_REQUEST['stmp_back'];
		$data['stmp_rate']    = $_REQUEST['stmp_rate'];

		$data['error_email']    = $_REQUEST['error_email'];

		$data['money_rate']   = $_REQUEST['money_rate'];
		$data['score_rate']    = (int)$_REQUEST['score_rate'];
		$data['score_give']    = (int)$_REQUEST['score_give'];
        $data['score_card']    = (int)$_REQUEST['score_card'];
		$data['comment_if']   = $_REQUEST['comment_if'];
		$data['info_if']      = $_REQUEST['info_if'];
		$data['email_if']     = $_REQUEST['email_if'];
		$data['mobile_if']    = $_REQUEST['mobile_if'];
		$data['reg_if']    = $_REQUEST['reg_if'];
		$data['word_filter']    = $_REQUEST['word_filter'];
        $data['sms_user'] = $_REQUEST['sms_user'];
        $data['sms_password'] = $_REQUEST['sms_password'];
		$data['update_time']  = time();
        $list = $model->where("id=$id")->save($data);
		if(false != $list){
		  $this->_writelog("更新系统配置","");
		  $this->success('编辑成功！');
		}else{
		  $this->error('编辑失败！');
		}
	}



	public function card(){
		  $model = M("Config_card");
		  $config= $model->find();
		  $this->assign("vo",$config);
		  $this->display();
	}

	public function updatecardconf(){
		  $model = M("Config_card");
		  $data["key"]=$_REQUEST["key"];
		  $data["password_expire"]=$_REQUEST["password_expire"];
		  $data["card_mode"]=$_REQUEST["card_mode"];
		  $data["sitename"]=$_REQUEST["sitename"];
		  $data["point_name"]=$_REQUEST["point_name"];
		  $data["maxSize"]=$_REQUEST["maxSize"];
		  $data["gif_height"]=$_REQUEST["gif_height"];
		  $data['limit_time']    = $_REQUEST['limit_time'];
		  $data['limit_count']    = $_REQUEST['limit_count'];
		  $id=$_REQUEST["id"];
		  $config= $model->where("id=$id")->save($data);
		  $contents='<?php
//$_SCONFIG 配置信息 $_SGLOBAL 数据信息
$_SCONFIG = $_SGLOBAL = array();
//模板目录
//$_SCONFIG[\'template\'] = \'default\';
$_SCONFIG[\'Key\'] = \''.$data["key"].'\';
//RBAC SESSION
$_SCONFIG[\'RBACSessionKey\'] = \'YHJ_USERDATA\';
// 密码过期时间
$_SCONFIG[\'password_expire\'] = '.$data["password_expire"].';
//会员卡模式  1 支持所有M1  2只支持自己的会员卡
$_SCONFIG[\'card_mode\'] = '.$data["card_mode"].';
//最大上传图片
$_SCONFIG[\'maxSize\'] ='.$data["maxSize"].'; //2M
//网站名
$_SCONFIG[\'sitename\'] = \''.$data["sitename"].'\';
//积分名
$_SCONFIG[\'point_name\'] = \''.$data["point_name"].'\';
//生成gif文件的高度
$_SCONFIG[\'gif_height\'] = \''.$data["gif_height"].'\';
//限制卡的打印时间范围
$_SCONFIG[\'limit_time\'] = \''.$data["limit_time"].'\';
//限制卡打印时间范围内的打印数量
$_SCONFIG[\'limit_count\'] = \''.$data["limit_count"].'\';
?>';
           $filename = "./Config/config_card.php";
           $cfp = fopen($filename,'w');
           fwrite($cfp,$contents);
           fclose($cfp);
		   $this->assign("jumpUrl", __GROUP__ . '/Config/card');
	       $this->success('保存成功！');
	}



}
