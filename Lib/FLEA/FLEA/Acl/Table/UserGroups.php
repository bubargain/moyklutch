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
 * ���� FLEA_Acl_Table_UserGroups ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: UserGroups.php 1060 2008-05-04 05:02:59Z qeeyuan $
 */

// {{{ includes
FLEA::loadClass('FLEA_Db_TableDataGateway');
// }}}

/**
 * FLEA_Acl_Table_UserGroups ���ṩ���û������ݵĴ洢����
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Acl_Table_UserGroups extends FLEA_Db_TableDataGateway
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
    var $tableName = 'user_groups';

    /**
     * �û�����������ɫ��Ȩ��
     *
     * @var array
     */
    var $manyToMany = array(
        array(
            'tableClass' => 'FLEA_Acl_Table_Roles',
            'foreignKey' => 'user_group_id',
            'assocForeignKey' => 'role_id',
            'joinTableClass' => 'FLEA_Acl_Table_UserGroupsHasRoles',
            'mappingName' => 'roles',
        ),
        array(
            'tableClass' => 'FLEA_Acl_Table_Permissions',
            'foreignKey' => 'user_group_id',
            'assocForeignKey' => 'permission_id',
            'joinTableClass' => 'FLEA_Acl_Table_UserGroupsHasPermissions',
            'mappingName' => 'permissions',
        ),
    );

    /**
     * ���û�����
     *
     * @var string
     */
    var $_rootGroupName = '_#_ROOT_GROUP_#_';

    /**
     * ���һ���û��飬���ظ��û���� ID
     *
     * @param array $group
     * @param int $parentId
     *
     * @return int
     */
    function create($group, $parentId = 0) {
        $parentId = (int)$parentId;
        if ($parentId) {
            $parent = parent::find($parentId);
            if (!$parent) {
                // ָ���ĸ��û��鲻����
                FLEA::loadClass('FLEA_Acl_Exception_UserGroupNotFound');
                __THROW(new FLEA_Acl_Exception_UserGroupNotFound($parentId));
                return false;
            }
        } else {
            // ���δָ�� $parentId Ϊ 0 �� null���򴴽�һ�������û���
            $parent = parent::find(array('name' => $this->_rootGroupName));
            if (!$parent) {
                // ������û��鲻���ڣ����Զ�����
                $parent = array(
                    'name' => $this->_rootGroupName,
                    'description' => '',
                    'left_value' => 1,
                    'right_value' => 2,
                    'parent_id' => -1,
                );
                if (!parent::create($parent)) {
                    return false;
                }
            }
            // ȷ������ _#_ROOT_GROUP_#_ ��ֱ�����û���� parent_id ��Ϊ 0
            $parent[$this->primaryKey] = 0;
        }

        $this->dbo->startTrans();

        // ���ݸ��û������ֵ����ֵ��������
        $sql = "UPDATE {$this->fullTableName} SET left_value = left_value + 2 " .
               "WHERE left_value >= {$parent['right_value']}";
        $this->dbo->execute($sql);
        $sql = "UPDATE {$this->fullTableName} SET right_value = right_value + 2 " .
               "WHERE right_value >= {$parent['right_value']}";
        $this->dbo->execute($sql);

        // �������û����¼
        $group['left_value'] = $parent['right_value'];
        $group['right_value'] = $parent['right_value'] + 1;
        $group['parent_id'] = $parent[$this->primaryKey];
        $ret = parent::create($group);

        if ($ret) {
            $this->dbo->completeTrans();
        } else {
            $this->dbo->completeTrans(false);
        }

        return $ret;
    }

    /**
     * �����û�����Ϣ
     *
     * @param array $group
     *
     * @return boolean
     */
    function update($group) {
        unset($group['left_value']);
        unset($group['right_value']);
        unset($group['parent_id']);
        return parent::update($group);
    }

    /**
     * ɾ��һ���û��鼰�����û�����
     *
     * @param int $groupId
     *
     * @return boolean
     */
    function removeByPkv($groupId) {
        $group = parent::find((int)$groupId);
        if (!$group) {
            FLEA::loadClass('FLEA_Acl_Exception_UserGroupNotFound');
            __THROW(new FLEA_Acl_Exception_UserGroupNotFound($groupId));
            return false;
        }

        $this->dbo->startTrans();

        $group['left_value'] = (int)$group['left_value'];
        $group['right_value'] = (int)$group['right_value'];
        $span = $group['right_value'] - $group['left_value'] + 1;
        $conditions = "WHERE left_value >= {$group['left_value']} AND right_value <= {$group['right_value']}";

        $rowset = $this->findAll($conditions, null, null, $this->primaryKey, false);
        foreach ($rowset as $row) {
            if (!parent::removeByPkv($row[$this->primaryKey])) {
                $this->dbo->completeTrans(false);
                return false;
            }
        }

        if (!parent::removeByPkv($groupId)) {
            $this->dbo->completeTrans(false);
            return false;
        }

        $sql = "UPDATE {$this->fullTableName} " .
               "SET left_value = left_value - {$span} " .
               "WHERE left_value > {$group['right_value']}";
        if (!$this->dbo->execute($sql)) {
            $this->dbo->completeTrans(false);
            return false;
        }

        $sql = "UPDATE {$this->fullTableName} " .
               "SET right_value = right_value - {$span} " .
               "WHERE right_value > {$group['right_value']}";
        if (!$this->dbo->execute($sql)) {
            $this->dbo->completeTrans(false);
            return false;
        }

        $this->dbo->completeTrans();
        return true;
    }

    /**
     * ���ظ��û��鵽ָ���û���·���ϵ������û���
     *
     * ���صĽ����������_#_ROOT_GROUP_#_�����û�������û���ͬ����������û��顣
     * �������һ����ά���飬������ array_to_tree() ����ת��Ϊ��νṹ�����ͣ���
     *
     * @param array $group
     *
     * @return array
     */
    function getPath($group) {
        $group['left_value'] = (int)$group['left_value'];
        $group['right_value'] = (int)$group['right_value'];

        $conditions = "left_value <= {$group['left_value']} AND right_value >= {$group['right_value']}";
        $sort = 'left_value ASC';
        $rowset = $this->findAll($conditions, $sort);
        if (is_array($rowset)) {
            array_shift($rowset);
        }
        return $rowset;
    }

    /**
     * ����ָ���û����ֱ�����û���
     *
     * @param array $group
     *
     * @return array
     */
    function getSubGroups($group) {
        $conditions = "parent_id = {$group[$this->primaryKey]}";
        $sort = 'left_value ASC';
        return $this->findAll($conditions, $sort);
    }

    /**
     * ����ָ���û���Ϊ�����������û�����
     *
     * @param array $group
     *
     * @return array
     */
    function getSubTree($group) {
        $group['left_value'] = (int)$group['left_value'];
        $group['right_value'] = (int)$group['right_value'];

        $conditions = "left_value BETWEEN {$group['left_value']} AND {$group['right_value']}";
        $sort = 'left_value ASC';
        return $this->findAll($conditions, $sort);
    }

    /**
     * ��ȡָ���û���ͬ����������û���
     *
     * @param array $group
     *
     * @return array
     */
    function getCurrentLevelGroups($group) {
        $group['parent_id'] = (int)$group['parent_id'];
        $conditions = "parent_id = {$group['parent_id']}";
        $sort = 'left_value ASC';
        return $this->findAll($conditions, $sort);
    }

    /**
     * ȡ�������û���
     *
     * @return array
     */
    function getAllGroups() {
        return parent::findAll('left_value > 1', 'left_value ASC');
    }

    /**
     * ��ȡ���ж����û��飨�� _#_ROOT_GROUP_#_ ��ֱ�����û��飩
     *
     * @return array
     */
    function getAllTopGroups() {
        $conditions = "parent_id = 0";
        $sort = 'left_value ASC';
        return $this->findAll($conditions, $sort);
    }

    /**
     * �����������û��������
     *
     * @param array $group
     *
     * @return int
     */
    function calcAllChildCount($group) {
        return intval(($group['right_value'] - $group['left_value'] - 1) / 2);
    }

}
