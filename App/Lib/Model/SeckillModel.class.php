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
class SeckillModel extends CommonModel {
	protected $tableName = 'Seckill_user'; 
    protected $_validate =  array(
		array('name','require','姓名必须'),
		array('tel','require','电话不能为空'),
		array('address','require','地址不能为空'),
           );
	protected $_auto=array(
			
		   );
	
	
	
}
?>