<?php
/////////////////////////////////////////////////////////////////////////////
// FleaPHP Framework
//
// Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
//
// ���Э�飬��鿴Դ�����и����� LICENSE.txt �ļ���
// ���߷��� http://www.fleaphp.org/ �����ϸ��Ϣ��
/////////////////////////////////////////////////////////////////////////////

/**
 * ���� FLEA_Acl_Exception_UserGroupNotFound �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: UserGroupNotFound.php 1060 2008-05-04 05:02:59Z qeeyuan $
 */

/**
 * FLEA_Acl_Exception_UserGroupNotFound ָʾָ�����û���û���ҵ�
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Acl_Exception_UserGroupNotFound extends FLEA_Exception
{
    var $userGroupId;

    function FLEA_Acl_Exception_UserGroupNotFound($userGroupId)
    {
        $this->userGroupId = $userGroupId;
        parent::FLEA_Exception("UserGroup ID: {$userGroupId} not found.");
    }
}
