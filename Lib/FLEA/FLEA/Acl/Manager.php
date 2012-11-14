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
 * ���� FLEA_Acl_Manager ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Manager.php 1060 2008-05-04 05:02:59Z qeeyuan $
 */

/**
 * FLEA_Acl_Manager �ṩ ACL ���ݵ�ȫ�������
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Acl_Manager
{
    /**
     * ����ʹ�õ��ı����ݶ���������
     *
     * @var array
     */
    var $_tableClass = array(
        'users' =>                  'FLEA_Acl_Table_Users',
        'roles' =>                  'FLEA_Acl_Table_Roles',
        'userGroups' =>             'FLEA_Acl_Table_UserGroups',
        'permissions' =>            'FLEA_Acl_Table_Permissions',
        'userGroupsHasRoles' =>     'FLEA_Acl_Table_UserGroupsHasRoles',
        'userGroupsHasPermissions' => 'FLEA_Acl_Table_UserGroupsHasPermissions',
        'userHasRoles' =>           'FLEA_Acl_Table_UserHasRoles',
        'userHasPermissions' =>     'FLEA_Acl_Table_UserHasPermissions',
    );

    function FLEA_Acl_Manager($tableClass = array())
    {
        $this->_tableClass = array_merge($this->_tableClass, (array)$tableClass);
    }

    /**
     * ��ȡָ���û�������Ȩ����Ϣ
     *
     * @param array $conditions
     */
    function getUserWithPermissions($conditions)
    {
        $tableUsers =& FLEA::getSingleton($this->_tableClass['users']);
        /* @var $tableUsers FLEA_Acl_Table_Users */
        $user = $tableUsers->find($conditions);
        if (empty($user)) { return false; }

        // ȡ���û������û���Ĳ������
        $tableUserGroups =& FLEA::getSingleton($this->_tableClass['userGroups']);
        /* @var $tableUserGroups FLEA_Acl_Table_UserGroups */
        $rowset = $tableUserGroups->getPath($user['group']);

        // �ҳ��û���ĵ�һ·��
        FLEA::loadHelper('array');
        $ret = array_to_tree($rowset, 'user_group_id', 'parent_id', 'subgroups', true);
        $tree =& $ret['tree'];
        $refs =& $ret['refs'];
        $groupid = $user['user_group_id'];
        $path = array();
        while (isset($refs[$groupid])) {
            array_unshift($path, $refs[$groupid]);
            $groupid = $refs[$groupid]['parent_id'];
        }

        // �����ɫ��Ϣ
        $userRoles = array();

        foreach ($path as $group) {
            $roles = $group['roles'];
            foreach ($roles as $role) {
                $roleid = $role['role_id'];
                if ($role['_join_is_include']) {
                    $userRoles[$roleid] = array('role_id' => $roleid, 'name' => $role['name']);
                } else {
                    unset($userRoles[$roleid]);
                }
            }
        }

        foreach ((array)$user['roles'] as $role) {
            $roleid = $role['role_id'];
            if ($role['_join_is_include']) {
                $userRoles[$roleid] = array('role_id' => $roleid, 'name' => $role['name']);
            } else {
                unset($userRoles[$roleid]);
            }
        }

        // ����Ȩ����Ϣ
        $user['roles'] = $userRoles;
        return $user;
    }
}
