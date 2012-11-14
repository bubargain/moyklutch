<?php
/**
  +------------------------------------------------------------------------------
  * 公共模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
 class TestAction extends CommonAction {
    public function index(){
        header("Content-Type:text/html; charset=utf-8");
        import('@.ORG.Uc');
        $Uc = new Uc();
        //示例1：检查用户名是否可用
        $data = $Uc->checkUserName('zzguo28');
        print_r($data);
//        //示例2：检查邮箱是否可用
//        $data =$Uc->checkEmail('chenqiang@qq.com');
//        if ($data)
//        {
//            echo 'yes';
//        }else{
//            echo 'no';
//        }
//        //示例3：添加新用户，参数分别为：用户名，密码，邮箱
//        $data = $Uc->addUser('chenqiang','chenqiang','chenqiang@qq.com');
//        print_r($data);
//          //示例4：同步登录,参数为：帐号，密码
//          $data = $Uc->synclogin('chenqiang','chenqiang');
//          print_r($data);
//          //示例5：同步退出
//          $data = $Uc->synclogout();
//          print_r($data);
//          //示例6：修改帐号和密码
//          $data = $Uc->editUserInfo('chenqiang','chenqiang','123456','chengqiang@qq.com');
//          print_r($data);
    }

}

?>