<?php
/**
  +------------------------------------------------------------------------------
  * 公共模块
  +------------------------------------------------------------------------------
  * @category Home
  * @author liu21st，zzguo28，who
  +------------------------------------------------------------------------------
 */
class CommonAction extends Action {

    protected $appsname = '';

    function _initialize() {
		//网站名称
		$webname = M('config')->getField('webname');
		$this->assign('webtitle',$webname);
        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
            import('@.ORG.RBAC');
            if (!RBAC::AccessDecision ()) {
               // 检查认证识别号
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                 //   跳转到认证网关
				   if(strtolower(GROUP_NAME)=="admin"){
                       redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
				   }else{
					   $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
					   $this->error("请先登录！");
				   }
                }
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
                        $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
                    }
                    // 提示错误信息
                    $this->error(L('_VALID_ACCESS_'));
                }
            }
        }

//		if($_SESSION['uc_ava']){ //更新头像 短消息 公告
//			import('@.ORG.Uc');
//			$Uc     = new Uc();
//			$name   = $_SESSION['account'];
//			$result = $Uc->getUserInfo($name);
//			$_SESSION['uc_uid'] = $result['id'];
//			$_SESSION['uc_ava'] = $result['avatar'];
//			$_SESSION['msg_num']=$Uc->get_msgnum($_SESSION['uc_uid'],0);
//			$message = $Uc->get_announce($_SESSION['uc_uid']);
//			$_SESSION['announce']=$message;
//		}
        $this->appsname = getAppName($_SESSION['_menuTag'], 'id', 'name');
    }

    /**
    +----------------------------------------------------------
    * 通用index列表类
    +----------------------------------------------------------
    */
    public function index()
    {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if (method_exists($this, '_filter'))
        {
            $this->_filter($map);
        }
        $name = $this->getActionName();
        $model = M($name);
        if (!empty($model))
        {
            $this->_list($model, $map);
        }
        $this->display();
        return;
    }

    /**
      +----------------------------------------------------------
     * 取得操作成功后要返回的URL地址
     * 默认返回当前模块的默认操作
     * 可以在action控制器中重载
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    function getReturnUrl() {
        return __URL__ . '?' . C('VAR_MODULE') . '=' . MODULE_NAME . '&' . C('VAR_ACTION') . '=' . C('DEFAULT_ACTION');
    }

   /**
    +----------------------------------------------------------
    * 通用搜索方法，根据表单查询条件进行过滤
    +----------------------------------------------------------
    */
    protected function _search($name = '')
    {
        //生成查询条件
        if (empty($name))
        {
            $name = $this->getActionName();
        }
        $name = $this->getActionName();
        $model = M($name);
        $map = array();
        foreach ($model->getDbFields() as $key => $val)
        {
            if (isset($_REQUEST [$val]) && $_REQUEST [$val] != '')
            {
                $map [$val] = $_REQUEST [$val];
            }
        }
        return $map;
    }
     /**
      +----------------------------------------------------------
      * 根据表单生成查询条件
      * 进行列表过滤
      +----------------------------------------------------------
      * @access protected
      +----------------------------------------------------------
      * @param Model $model 数据对象
      * @param HashMap $map 过滤条件
      * @param string $sortBy 排序
      * @param boolean $asc 是否正序
      +----------------------------------------------------------
      * @return void
      +----------------------------------------------------------
      * @throws ThinkExecption
      +----------------------------------------------------------
     */
    protected function _list($model, $map, $sortBy = '', $asc = false) {
        //排序字段 默认为主键名
        if (isset($_REQUEST ['_order'])) {
            $order = $_REQUEST ['_order'];
        } else {
            $order = !empty($sortBy) ? $sortBy : $model->getPk();
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        if (isset($_REQUEST ['_sort'])) {
            $sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }
        //取得满足条件的记录数
        $count = $model->where($map)->count('id');
        if ($count > 0) {
            //创建分页对象
            if (!empty($_REQUEST ['listRows'])) {
                $listRows = $_REQUEST ['listRows'];
            } else {
                $listRows = 10;
            }
            $page = array();
            $page['counts'] = $count;
            $page['pages'] =  ceil($count/$listRows);
            $page['listRows'] = $listRows;
            $page['curr_page'] = !empty($_GET[C('VAR_PAGE')])?$_GET[C('VAR_PAGE')]:1;
            $page['curr_page'] = ($page['curr_page']>$page['pages'])?1:$page['curr_page'];
            $page['firstRow']  = ($page['curr_page']-1)*$page['listRows'];
            $page['prev_page'] = ($page['curr_page']<=1)?1:$page['curr_page']-1;
            $page['next_page'] = ($page['next_page']>=$page['pages'])?$page['pages']:$page['curr_page']+1;
            $page['parameter'] = '';
            //分页查询数据

            $voList = $model->where($map)->order("`" . $order . "` " . $sort)->limit($page['firstRow'] . ',' . $page['listRows'])->findAll();
            //echo $model->getlastsql();
            //分页跳转的时候保证查询条件
            foreach ($map as $key => $val) {
                if (!is_array($val)) {
                    $page['parameter'] .= "$key=" . urlencode($val) . "&";
                }
            }
            $this->assign('page',$page);
            //分页显示
            //列表排序显示
            $sortImg = $sort; //排序图标
            $sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
            $sort = $sort == 'desc' ? 1 : 0; //排序方式
            //模板赋值显示
            $this->assign('list', $voList);
            $this->assign('sort', $sort);
            $this->assign('order', $order);
            $this->assign('sortImg', $sortImg);
            $this->assign('sortType', $sortAlt);
            $this->assign("page", $page);
        }
        cookie('_currentUrl_',__SELF__);
        //Cookie::set('_currentUrl_', __SELF__);
        return;
    }

    /**
      +----------------------------------------------------------
     * 根据表单生成查询条件
     * 进行列表过滤
      +----------------------------------------------------------
     * @access protected
      +----------------------------------------------------------
     * @param string $name 数据对象名称
      +----------------------------------------------------------
     * @return HashMap
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    function read() {
        $this->edit();
    }

    function add(){
        $this->display();
    }
   /**
    +----------------------------------------------------------
    * 通用插入方法
    +----------------------------------------------------------
    */
    function insert()
    {
        //B('FilterString');
        $name = $this->getActionName();
        $model = D($name);
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        //保存当前数据对象
        $list = $model->add();
        if ($list !== false) { //保存成功
            $this->_after_insert($list);
            $this->assign('jumpUrl', cookie('_currentUrl_'));
            $this->success('新增成功!');
        } else {
            //失败提示
            $this->error('新增失败!');
        }
    }
   /**
    +----------------------------------------------------------
    * 通用修改方法
    +----------------------------------------------------------
    */
    function edit() {
        $name = $this->getActionName();
        $model = M($name);
        $id = $_REQUEST [$model->getPk()];
        $vo = $model->getById($id);
        $this->assign('vo', $vo);
        $this->display();
    }
   /**
    +----------------------------------------------------------
    * 通用更新方法
    +----------------------------------------------------------
    */
    function update() {
        $name = $this->getActionName();
        $model = D($name);
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        // 更新数据
        $list = $model->save();
        if (false !== $list) {
            //成功提示
            $this->_after_update($list);
            $this->assign('jumpUrl', cookie('_currentUrl_'));
            $this->success('编辑成功!');
        } else {
            //错误提示
            $this->error('编辑失败!');
        }
    }

    /**
      +----------------------------------------------------------
     * 默认删除操作
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    public function delete() {
        //删除指定记录
        $name = $this->getActionName();
        $model = M($name);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
                 $condition = array($pk => array('in', explode(',', $id)));
                 $list = $model->where($condition)->delete();
                 if ($list !== false) {
                    $this->_writelog("删除","记录ID号:".implode(',',$id));
                    $this->success('删除成功！');
                 } else {
                    $this->error('删除失败！');
                 }
            }
        }
    }
    /**
    +----------------------------------------------------------
    * 恢复记录
    +----------------------------------------------------------
    */
	public function recycle() {
        //删除指定记录
        $name = $this->getActionName();
        $model = M($name);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
                $condition = array($pk => array('in', explode(',', $id)));
				$data['status'] = 1;
				$data['update_time'] =time();
                $list = $model->where($condition)->save($data);
                if (false !== $list) {
                   //成功提示
                   $this->_writelog("恢复","记录ID号:".implode(',',$id));
                   $this->assign('jumpUrl', cookie('_currentUrl_'));
                   $this->success('恢复成功!');
                } else {
                   $this->error('恢复失败!');//错误提示
               }

            }
        }

    }
    /**
    +----------------------------------------------------------
    * 通用逻辑删除
    +----------------------------------------------------------
    */
    public function foreverdelete() {
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
                $condition = array($pk => array('in', explode(',', $id)));
				$data['status'] = -1;
				$data['update_time'] =time();
                if (false !== $model->where($condition)->save($data)) {
					$this->_writelog("禁用","记录ID号:".implode(',',$id));
                    $this->success('禁用成功！');
                } else {
                    $this->error('禁用失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
        $this->forward();
    }
    /**
    +----------------------------------------------------------
    * 通用清理函数
    +----------------------------------------------------------
    */
    public function clear() {
        //删除指定记录
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)) {
            if (false !== $model->where('status=1')->delete()) {
                $this->_writelog('delete');
                $this->assign("jumpUrl", $this->getReturnUrl());
                $this->success(L('_DELETE_SUCCESS_'));
            } else {
                $this->error(L('_DELETE_FAIL_'));
            }
        }
        $this->forward();
    }

    /**
      +----------------------------------------------------------
      * 默认禁用操作
      *
      +----------------------------------------------------------
      * @access public
      +----------------------------------------------------------
      * @return string
      +----------------------------------------------------------
      * @throws FcsException
      +----------------------------------------------------------
     */
    public function forbid() {
        $name = $this->getActionName();
        $model = D($name);
        $pk = $model->getPk();
        $id = $_REQUEST [$pk];
        $condition = array($pk => array('in', $id));
        $list = $model->forbid($condition);
        if ($list !== false) {
            $this->assign("jumpUrl", $this->getReturnUrl());
            $this->success('状态禁用成功');
        } else {
            $this->error('状态禁用失败！');
        }
    }
    /**
    +----------------------------------------------------------
    * 通用状态批准函数
    +----------------------------------------------------------
    */
    public function checkPass() {
        $name = $this->getActionName();
        $model = D($name);
        $pk = $model->getPk();
        $id = $_GET [$pk];
        $condition = array($pk => array('in', $id));
        if (false !== $model->checkPass($condition)) {
            $this->assign("jumpUrl", $this->getReturnUrl());
            $this->success('状态批准成功！');
        } else {
            $this->error('状态批准失败！');
        }
    }
    /**
    +----------------------------------------------------------
    * 通用日志记录函数
    +----------------------------------------------------------
    */
    public function _writelog($operate,$title)
    {
		$data['action_name']= $this->getActionName();
		$data['user_id']    = $_SESSION[C('USER_AUTH_KEY')];
		$data['create_time']= time();
		$data['operate']    = $operate;
		$data['target']     = $title;
		M("Log")->add($data);
    }

    public function _after_insert($id){}
    public function _after_update($id){}
}

?>