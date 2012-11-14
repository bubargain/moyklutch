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
 * ���� FLEA_Rbac_RolesManager ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: RolesManager.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

// {{{ includes
FLEA::loadClass('FLEA_Db_TableDataGateway');
// }}}

/**
 * FLEA_Rbac_RolesManager ������ FLEA_Db_TableDataGateway��
 * ���ڷ��ʱ����ɫ��Ϣ�����ݱ�
 *
 * ������ݱ�����ֲ�ͬ��Ӧ�ô� FLEA_Rbac_RolesManager
 * �����ಢʹ���Զ�������ݱ����֡������ֶ����ȡ�
 *
 * @package Core
 */
class FLEA_Rbac_RolesManager extends FLEA_Db_TableDataGateway
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
     * ��ɫ���ֶ�
     *
     * @var string
     */
    var $rolesNameField = 'rolename';

    /**
     * ���캯��
     *
     * @param array $params
     *
     * @return FLEA_Rbac_RolesManager
     */
    function FLEA_Rbac_RolesManager($params = null)
    {
        parent::FLEA_Db_TableDataGateway($params);
    }
}
