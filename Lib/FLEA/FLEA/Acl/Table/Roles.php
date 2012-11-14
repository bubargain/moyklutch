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
 * ���� FLEA_Acl_Table_Roles ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Roles.php 1060 2008-05-04 05:02:59Z qeeyuan $
 */

// {{{ includes
FLEA::loadClass('FLEA_Db_TableDataGateway');
// }}}

/**
 * FLEA_Acl_Table_Roles �ṩ�˽�ɫ���ݵĴ洢����
 *
 * @package Core
 */
class FLEA_Acl_Table_Roles extends FLEA_Db_TableDataGateway
{
    /**
     * �����ֶ���
     *
     * @var string
     */
    var $primaryKey = 'role_id';

    /**
     * ���ݱ�����
     *
     * @var string
     */
    var $tableName = 'roles';

    /**
     * һ����ɫ��Ӧ���Ȩ�ޣ�һ��Ȩ�޿���ָ�ɸ������ɫ
     *
     * @var array
     */
    var $manyToMany = array(
        array(
            'tableClass' => 'FLEA_Acl_Table_Permissions',
            'foreignKey' => 'role_id',
            'assocForeignKey' => 'permission_id',
            'joinTable' => 'roles_has_permissions',
            'mappingName' => 'permissions',
        ),
    );

}
