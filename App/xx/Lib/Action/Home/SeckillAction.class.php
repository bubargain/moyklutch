<?php
//模块
class SeckillAction extends Action{
	public function index(){
		//分类
		$class = M('SeckillClassify');
		$classlist = $class->select();
		$this->assign('classlist',$classlist);

		$model = M('SeckillGoods');
		if(empty($_GET['cid'])){
			//正在秒杀的商品
			$this->flist = $model->where('starttime<'.time().' AND endtime>'.time().' AND status<>0')->limit(10)->select();
			//等待秒杀的商品
			$this->slist = $model->where('starttime>'.time().' AND status<>0')->limit(5)->select();
			//结束的商品
			$sql = "SELECT * FROM think_seckill_goods WHERE starttime<".time()." AND id IN(
					SELECT gid FROM think_seckill_user WHERE sectype<>0) ORDER BY id DESC LIMIT 0,2";
			$this->elist = M()->query($sql);
		}else{
			//商品分类名
			$this->classname = M('SeckillClassify')->where('id='.$_GET['cid'])->getField('title');
			//正在秒杀的商品
			$this->flist = $model->where('cid='.$_GET['cid'].' AND status<>0 AND starttime<'.time().' endtime>'.time())->limit(10)->select();
			//等待秒杀的商品
			$this->slist = $model->where('cid='.$_GET['cid'].' AND starttime>'.time().' AND status<>0')->limit(5)->select();
			//结束的商品
			$this->elist = $model->where('cid='.$_GET['cid'].' AND endtime<'.time().' AND status<>0')->limit(2)->select();
			$sql = "SELECT * FROM think_seckill_goods WHERE endtime<".time()." AND cid='{$_GET['cid']}' AND id IN(
					SELECT gid FROM think_seckill_user WHERE sectype<>0) ORDER BY id DESC LIMIT 0,2";
			$this->elist = M()->query($sql);
		}
		//ECHO $model->getLastSql();exit;
		$this->assign('title','秒杀');
		$this->display();
	}

	//等待秒杀的商品列表
	public function slist(){
		//分类
		$class = M('SeckillClassify');
		$classlist = $class->select();
		$this->assign('classlist',$classlist);

		$model = M('SeckillGoods');
		if($_POST['search']){
			$stime = strtotime($_POST['stime']);
			$etime = strtotime($_POST['etime'])+86400;
			$this->slist = $model->where('starttime>'.time().' AND '.$stime.'<starttime AND endtime<'.$etime.' AND status<>0')->order('endtime DESC')->select();
		}else{
			$this->slist = $model->where('starttime>'.time().' AND status<>0')->select();
		}
		//ECHO $model->getLastSql();exit;
		$this->assign('title','待秒杀的商品列表');
		$this->display();
	}

	//秒杀结束的商品列表
	public function elist(){
		//分类
		$class = M('SeckillClassify');
		$classlist = $class->select();
		$this->assign('classlist',$classlist);

		$model = M('SeckillGoods');
		if($_POST['search']){
			$stime = strtotime($_POST['stime']);
			$etime = strtotime($_POST['etime'])+86400;
			$this->elist = $model->where('endtime<'.time().' AND '.$stime.'<starttime AND endtime<'.$etime.' AND status<>0')->order('endtime DESC')->select();
		}else{
			$this->elist = $model->where('endtime<'.time().' AND status<>0')->order('endtime DESC')->select();
		}

		$this->assign('title','秒杀结束的商品列表');
		$this->display();
	}

	//检查规则
	public function ckauth(){
		$id = (int)$_POST['gid'];
        $userid = (int)$_SESSION['auth'];
        $myscore = (int)getUserInfo($userid,'score');
        $nowtime = time();
		$model = M('SeckillUser');
        $product = M('SeckillGoods')->where('id='.$id)->find();
        if (!$product || $product['endtime']<$nowtime || $product['status']!=1) $this->error('您来晚来，该秒杀已结束');
        $fail_score = (int)$product['score']*$product['failscore']/100; //获取失败积分
		//验证码验证
		if($_SESSION['verify'] != md5($_POST['verify'])) $this->error('验证码错误！');
        $user_exists = $model->where('gid='.$id.' AND uid='.$userid)->find();
        if (!$user_exists) $this->msg('您没有报名,需报名后才能秒杀',U("Seckill/content/id/$id#c"),3);
        if ($myscore<$product['score']) $this->error('对不起，您的积分不够,商品:'.$product['title'].' 需要积分：'.$product['score'].' 分');
        //获取已成功秒杀的产品
        $all_user_count = $model->where('gid='.$id.' and sectype=1')->count();
        if ($user_exists['sectype']==1) $this->error('您已秒杀成功此商品，不需要再次秒杀');
        if ($all_user_count>=$product['num'])
        {
                $this->error('您来晚了，该商品已秒杀结束！请到会员中心领取您返回的积分:'.$failscore.' 分');
        }else{
                M('SeckillUser')->where('uid='.$userid.' AND gid='.$id)->setField('sectype',1);
                M('User')->setDec('score','id='.$userid,$product['score']); //扣除积分
                $score['user_id'] = $_SESSION['auth'];
                $score['why'] = '参加秒杀"'.$_POST['title'].'"扣除积分';
                $score['point'] = '-'.$product['score'];
                $score['log_time'] = time();
                M('Pointlog')->add($score);
                 $this->msg('恭喜您，您已成功秒杀到商品：'.$_POST['title'],U("User/seckill"),3);
        }
	}
	public function test(){
		$this->display();
	}

	public function verify(){
		$type = isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Image");
        Image::buildImageVerify(4,1,$type);
    }

	//msg
	public function msg($title,$url,$second){
		$this->msgTitle = $title;
		$this->jumpUrl = $url;
		$this->waitSecond = $second;
		$this->display('Public:success');
	}

	//报名
	public function apply(){
		$model = D('Seckill_user');
		$data['name'] = trim($_POST['name']);
		$data['tel'] = trim($_POST['tel']);
		$data['address'] = trim($_POST['address']);
		$data['gid'] = (int)$_POST['gid'];
		$data['uid'] = (int)$_SESSION['auth'];
        $myscore = (int)getUserInfo($data['uid'],'score'); //获取我的用户积分
        //检查是否重复报名
        $user_exists = $model->where('uid='.$data['uid'].' and gid='.$data['gid'])->find();
        if ($user_exists) $this->error('您已经报名过，不需要再次报名');
        if ($data['uid']==0) $this->error('请登录后再行报名');
        if ($data['name']=='' || $data['tel']=='' || $data['address']=='') $this->error('为了便于我们和您联系，请正确填写真实姓名，电话和联系地址');
        if ($myscore<$_POST['score']) $this->error('您的积分不够，不能报名');
		if(!$model->create()){$this->error($model->getError());}
        if (!$model->add($data)) $this->error('添加失败');
        $replace_data = '';
        $replace_data .= "`realname`='".$data['name']."',";
        $replace_data .="`mobile`='".$data['tel']."',";
        $replace_data .="`address`='".$data['tel']."',";
        $replace_data  = 'update `'.C('DB_PREFIX').'`  SET '.substr($replace_data,0,-1)." where id=".$data['uid'];
        M()->query($replace_data);
        $ac = M('User')->setDec('score','id='.$data['uid'],$_POST['score']);
        $score['user_id'] = $data['uid'];
		$score['why'] = '报名秒杀"'.$_POST['title'].'"扣除积分';
		$score['point'] = '-'.$_POST['score'];
		$score['log_time'] = time();
		M('Pointlog')->add($score);
        $this->msg('报名成功，请准时参加秒杀：',U("User/seckill"),3);
	}

	//产品内容
	public function content(){
		//商品是否已结束
		if(!M('SeckillGoods')->where('id='.$_GET['id'].' AND endtime<'.time().' AND status<>0')->select()){
			//用户是否已经报名
			if(M('SeckillUser')->where('gid='.$_GET['id'].' AND uid='.$_SESSION['auth'])->count('*')){
				$this->gtype = 3;
			}else{
				$this->gtype = 1;
			}
		}else{
			$this->gtype = 2;
		}
		$sql = "SELECT * FROM think_seckill_goods WHERE starttime<".time()." AND id IN(
					SELECT gid FROM think_seckill_user WHERE sectype<>0) ORDER BY id DESC LIMIT 0,2";
			$this->elist = M()->query($sql);
		$this->vo = M('SeckillGoods')->where('id='.$_GET['id'])->find();
		$this->display();
	}
    /**
     +----------------------------------------------------------
     * 领取积分
     +----------------------------------------------------------
     */
     public function returnscore()
    {
         $gid = (int)$_REQUEST['gid'];
         $uid = isset($_REQUEST['uid'])?(int)$_REQUEST['uid']:(int)$_SESSION['auth'];
          $userinfo = M('SeckillUser')->where('uid='.$uid.' and gid= '.$gid)->find();
          if (!$userinfo  || $userinfo['sectype']==1) $this->error('找不到退款记录');
          if ($userinfo['failscore']!=0) $this->error('您已领取了积分');
          $product = M('SeckillGoods')->where('id='.$gid)->find();
          if (!$product) $this->error('找不到指定的商品');
          $failscore = ($product['score']*($product['signupscore']/100))*($product['failscore']/100);
           M('User')->setInc('score','id='.$uid,$failscore); //扣除积分
            $score['user_id'] = $uid;
            $score['why'] = '秒杀"'.$product['title'].'"返还积分';
            $score['point'] = ''.$failscore;
            $score['log_time'] = time();
            M('Pointlog')->add($score);
             M('SeckillUser')->where('uid='.$uid)->setField('failscore',1);
            $this->msg('积分返还成功',U("User/seckill"),3);
     }
}
?>