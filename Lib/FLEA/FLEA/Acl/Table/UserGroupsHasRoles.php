<?php
/////////////////////////////////////////////////////////////////////////////
// FleaPHP Framework
//
// Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
//
// ����Э�飬��鿴Դ�����и����� LICENSE.txt �ļ���
// ���߷��� http://www.fleaphp.org/ �����ϸ��Ϣ��
/////////////////////////////////////////////////////////////////////////////

/**
 * ���� FLEA_Acl_Table_UserGroupsHasRoles ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: UserGroupsHasRoles.php 1060 2008-05-04 05:02:59Z qeeyuan $
 */

// {{{ includes
FLEA::loadClass('FLEA_Db_TableDataGateway');
// }}}

/**
 * FLEA_Acl_Table_UserGroupsHasRoles ���ڹ����û���ͽ�ɫ
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Acl_Table_UserGroupsHasRoles extends FLEA_Db_TableDataGateway
{
    /**
     * �����ֶ���
     *
     * @var string
     */
    var $primaryKey = 'user_group_id';

    /**
     * ���ݱ�����
     *
     * @var string
     */
    var $tableName = 'user_groups_has_roles';

}