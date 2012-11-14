<?php

require('FLEA.php');

$dbDSN = array(
    'driver'    => 'mysqlt',
    'host'      => 'localhost',
    'login'     => 'root',
    'password'  => '',
    'database'  => 'test'
);

FLEA::setAppInf('dbDSN', $dbDSN);
FLEA::setAppInf('internalCacheDir', 'D:/temp');

$dbo =& FLEA::getDBO();
$dbo->startTrans();

/**
 * ����ȫ����Ҫ��Ȩ��
 */
$tablePermissions =& FLEA::getSingleton('FLEA_Acl_Table_Permissions');
/* @var $tablePermissions FLEA_Acl_Table_Permissions */
$permissions = array(
    array('name' => '/Project/Create'),
    array('name' => '/Project/View'),
    array('name' => '/Project/Edit'),
    array('name' => '/Project/Delete'),
    array('name' => '/Bug/Create'),
    array('name' => '/Bug/View'),
    array('name' => '/Bug/Edit'),
    array('name' => '/Bug/AddComment'),
    array('name' => '/Bug/SetFixed'),
    array('name' => '/Bug/SetClosed'),
    array('name' => '/Bug/Delete'),
);
$tablePermissions->createRowset($permissions);

$permissions = $tablePermissions->findAll();
FLEA::loadHelper('array');
$permissions = array_to_hashmap($permissions, 'name');

/**
 * ������ɫ������Ȩ�ް󶨵���ɫ��
 */
$tableRoles =& FLEA::getSingleton('FLEA_Acl_Table_Roles');
/* @var $tableRoles FLEA_Acl_Table_Roles */
$role = array(
    'name' => 'ProjectManager',
    'permissions' => array(
        $permissions['/Project/Create'],
        $permissions['/Project/View'],
        $permissions['/Project/Edit'],
        $permissions['/Project/Delete'],
        $permissions['/Bug/Delete'],
    ),
);
$tableRoles->create($role);

$role = array(
    'name' => 'Developer',
    'permissions' => array(
        $permissions['/Project/View'],
        $permissions['/Bug/View'],
        $permissions['/Bug/AddComment'],
        $permissions['/Bug/SetFixed'],
        $permissions['/Bug/Delete'],
    ),
);
$tableRoles->create($role);

$role = array(
    'name' => 'Tester',
    'permissions' => array(
        $permissions['/Project/Create'],
        $permissions['/Bug/Create'],
        $permissions['/Bug/Edit'],
        $permissions['/Bug/View'],
        $permissions['/Bug/AddComment'],
        $permissions['/Bug/SetClosed'],
    ),
);
$tableRoles->create($role);

/**
 * ��ȡ���н�ɫ��Ϣ�����Խ�ɫ��Ϊ����
 */
$roles = $tableRoles->findAll();
$roles = array_to_hashmap($roles, 'name');

/**
 * �����û����Σ���ָ����ɫ
 *
 * ������
 *   |
 *   +----- QeePHP Team
 *   |
 *   +----- PHPChina Team
 *   |
 *   \----- ������
 */
$tableUserGroups =& FLEA::getSingleton('FLEA_Acl_Table_UserGroups');
/* @var $tableUserGroups FLEA_Acl_Table_UserGroups */
$group = array(
    'name' => '������',
    'roles' => array(
        $roles['Developer'],
    )
);
$tableUserGroups->create($group);
$parent = $tableUserGroups->find(array('name' => '������'));

$group = array(
    'name' => 'QeePHP Team',
    'parent_id' => $parent['user_group_id'],
    'roles' => array(
        $roles['Developer'],
    )
);
$tableUserGroups->create($group);

$group = array(
    'name' => 'PHPChina Team',
    'parent_id' => $parent['user_group_id'],
    'roles' => array(
        $roles['Developer'],
    )
);
$tableUserGroups->create($group);

$group = array(
    'name' => '������',
    'parent_id' => $parent['user_group_id'],
    'roles' => array(
        $roles['Tester'],
        /**
         * �� is_include ָ��Ϊ 0����ʾ���û����ų��ˡ�Developer����ɫ
         */
        array_merge($roles['Developer'], array('#JOIN#' => array('is_include' => 0))),
    )
);
$tableUserGroups->create($group);

$groups = $tableUserGroups->findAll();
$groups = array_to_hashmap($groups, 'name');

/**
 * �����û��������䵽������
 */
$tableUsers =& FLEA::getSingleton('FLEA_Acl_Table_Users');
/* @var $tableUsers FLEA_Acl_Table_Users */
$users = array(
    array(
        'username' => 'liaoyulei',
        'password' => '123456',
        'email' => 'liaoyulei@qeeyuan.com',
        'user_group_id' => $groups['QeePHP Team']['user_group_id'],
    ),
    array(
        'username' => 'liwei',
        'password' => '123456',
        'email' => 'liwei@qeeyuan.com',
        'user_group_id' => $groups['QeePHP Team']['user_group_id'],
    ),
    array(
        'username' => 'liye',
        'password' => '123456',
        'email' => 'liye@qeeyuan.com',
        'user_group_id' => $groups['QeePHP Team']['user_group_id'],
    ),
    array(
        'username' => 'dali',
        'password' => '123456',
        'email' => 'dali@qeeyuan.com',
        'user_group_id' => $groups['QeePHP Team']['user_group_id'],
    ),
);
$tableUsers->createRowset($users);

/**
 * Ϊ�û�ָ�ɵ����Ľ�ɫ
 */
$user = $tableUsers->find(array('username' => 'liaoyulei'));
$user['roles'][] = $roles['ProjectManager'];
$tableUsers->update($user);

$user = $tableUsers->find(array('username' => 'liye'));
$user['roles'][] = $roles['Tester'];
$tableUsers->update($user);

$user = $tableUsers->find(array('username' => 'dali'));
$user['roles'][] = $roles['Tester'];
$tableUsers->update($user);



$users = array(
    array(
        'username' => '������',
        'password' => '123456',
        'email' => 'milizi@phpchina.com',
        'user_group_id' => $groups['PHPChina Team']['user_group_id'],
    ),
    array(
        'username' => 'ĬĬ',
        'password' => '123456',
        'email' => 'momo@phpchina.com',
        'user_group_id' => $groups['PHPChina Team']['user_group_id'],
    ),
    array(
        'username' => '�����',
        'password' => '123456',
        'email' => 'bingciwei@phpchina.com',
        'user_group_id' => $groups['PHPChina Team']['user_group_id'],
    ),
);
$tableUsers->createRowset($users);

$user = $tableUsers->find(array('username' => '������'));
$user['roles'][] = $roles['ProjectManager'];
$tableUsers->update($user);

$users = array(
    array(
        'username' => '��ͬС��',
        'password' => '123456',
        'email' => 'feitongxiaoke@phpchina.com',
        'user_group_id' => $groups['������']['user_group_id'],
    ),
    array(
        'username' => '��ï��',
        'password' => '123456',
        'email' => 'leimaofeng@phpchina.com',
        'user_group_id' => $groups['������']['user_group_id'],
    ),
);
$tableUsers->createRowset($users);

$user = $tableUsers->find(array('username' => '��ͬС��'));
$user['roles'][] = $roles['Developer'];
$tableUsers->update($user);


$dbo->completeTrans();
