<?php
// +----------------------------------------------------------------------
// | ThinkPHP顶想
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

//import('AdvModel');
class CommonModel extends Model {

    protected $_auto = array (
        array('create_time','time',3,'function'),
        array('update_time','time',3,'function'),
    );



}
?>