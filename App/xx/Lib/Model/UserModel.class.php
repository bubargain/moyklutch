<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

//import('AdvModel');
class UserModel extends CommonModel {
    protected $_validate =  array(
		array('account','require','帐号必须'),
		array('password','require','密码必须',MUST_TO_VALIDATE,'',self::MODEL_INSERT),
		array('repassword','require','确认密码必须',MUST_TO_VALIDATE,'',self::MODEL_INSERT),
		array('repassword','password','确认密码不一致',MUST_TO_VALIDATE,'confirm',self::MODEL_INSERT),
		array('account','','帐号已经存在',MUST_TO_VALIDATE,'unique',self::MODEL_INSERT),
        array('email','email','电子邮件格式错误'),
           );
	public $_auto=array(
			array('password','md5',3,'function'),
			array('status','1'),
			array('score','get_regscore',1,'function'),
			array('reg_ip','get_client_ip',1,'function'),
			array('create_time','time',1,'function'),
			array('update_time','time',3,'function'),
			array('birthday','strtotime',2,'function'),
		   );
    //检查电话号码
    public function checkphone()
    {
        if(empty($_POST['mobile']) && empty($_POST['phone']))
        {
            return false;
        }
        return true;
    }

	//得到会员信息
	public function getinfo(){
        $id  = (int)$_SESSION[C('USER_AUTH_KEY')];
		$sql = "id=$id";
		$vo  = $this->where($sql)->find();
		return $vo;
		}

	//得到会员优惠券收藏
	public function ticketcol(){
		$id  = (int)$_SESSION[C('USER_AUTH_KEY')];
		$sql = "user_id=$id";
		$vo  = D("Collect")->where($sql)->select();
		return $vo;
		}

	//得到会员商家收藏
	public function tradecol(){
		$id  = (int)$_SESSION[C('USER_AUTH_KEY')];
		$sql = "user_id=$id";
		$vo  = D("Trade_collect")->where($sql)->select();
		return $vo;
		}

	//得到会员积分纪录
	public function getscore(){
		$id       = (int)$_SESSION[C('USER_AUTH_KEY')];
		$pointlog = D("Pointlog");
		$sql      = "user_id=$id";
		$count    = $pointlog->where($sql)->count('id');
		import("@.ORG.Page");
		$p    = New Page($count,15);
		$list = $pointlog->where($sql)->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
		$page = $p->show();
		$vo['list'] = $list;
		$vo['count']= $count;
		$vo['page'] = $page;
		return $vo;
	}

	//秒杀商品记录
	public function getgoods(){
		$id       = (int)$_SESSION['auth'];
		$goods = M("SeckillUser");
		$sql      = "uid=$id";
		$count    = $goods->where($sql)->count('id');
		import("@.ORG.Page");
		$p    = New Page($count,15);
		$list = $goods->field('think_seckill_user.id,think_seckill_user.uid,think_seckill_user.gid,think_seckill_user.tel,think_seckill_user.address,think_seckill_user.typeid,think_seckill_user.failscore as isfailscore,think_seckill_user.sectype ,g.title,g.score,g.signupscore,g.failscore,g.endtime,g.starttime')->join('think_seckill_goods AS g ON g.id=think_seckill_user.gid')->where($sql)->order("think_seckill_user.id desc")->limit($p->firstRow . ',' . $p->listRows)->findAll();
		//echo M()->getLastSql();exit;
		$page = $p->show();
		$vo['list'] = $list;
		$vo['count']= $count;
		$vo['page'] = $page;
		return $vo;
	}

    /**
    +----------------------------------------------------------
    * 插入新的score记录
    +----------------------------------------------------------
    */
    public function insertScore($why,$score,$userid=0)
    {
        $userid = ($userid==0)?(int)$_SESSION['auth']:(int)$userid;
        $money = M('User')->where('id='.$userid)->getField('money');
        $score = (int)$score;
        $money = (int)$money+$score;
        M('User')->where('id='.$userid)->setField('money',$money);
        $data = array('user_id'=>$userid,'why'=>$why,'point'=>(int)$score,'tpoint'=>(int)$score,'ptype'=>'','log_time'=>time());
        $data = M('Pointlog')->add($data);
        return $money;
    }

}
?>