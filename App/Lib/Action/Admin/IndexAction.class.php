<?php
/**
  +------------------------------------------------------------------------------
  * 首页
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class IndexAction extends CommonAction {
	// 框架首页
	public function index() {
        if ($_SESSION['__account__'])
        {
          header("Content-Type:text/html; charset=utf-8");
          import('@.ORG.Uc'); //uhome同步登陆
          $Uc = new Uc();
          $data = $Uc->synclogin($_SESSION['__account__'],strrev($_SESSION['__password__']));
          unset($_SESSION['__account__'],$_SESSION['__password__']);
        }
		C ( 'SHOW_RUN_TIME', false ); // 运行时间显示
		C ( 'SHOW_PAGE_TRACE', false );
		$this->display();
	}
	public function verify() {
		import ( "ORG.Util.Image" );
		Image::buildImageVerify ( 4 );
	}

}
?>