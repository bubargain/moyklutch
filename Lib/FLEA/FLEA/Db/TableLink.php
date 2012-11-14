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
 * ���� FLEA_Db_TableLink �༰��̳���
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: TableLink.php 1449 2008-10-30 06:16:17Z dualface $
 */

/**
 * FLEA_Db_TableLink ��װ���ݱ�֮��Ĺ�����ϵ
 *
 * FLEA_Db_TableLink ��һ����ȫ�� FleaPHP �ڲ�ʹ�õ��࣬
 * �����߲�Ӧ��ֱ�ӹ��� FLEA_Db_TableLink ����
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.2
 */
class FLEA_Db_TableLink
{
    /**
     * �����ӵ����֣����ڼ���ָ��������
     *
     * ͬһ�����ݱ�Ķ����������ʹ����ͬ�����֡�����������ʱû��ָ�����֣�
     * ���Թ�������� $mappingName ������Ϊ������������֡�
     *
     * @var string
     */
    var $name;

    /**
     * �ù�����ʹ�õı�������ڶ�����
     *
     * @var string
     */
    var $tableClass;

    /**
     * ����ֶ���
     *
     * @var string
     */
    var $foreignKey;

    /**
     * �������ݱ���ӳ�䵽�������е��ֶ���
     *
     * @var string
     */
    var $mappingName;

    /**
     * ָʾ�����������ݼ�����ʱ����һ��һ���ӻ���һ�Զ�����
     *
     * @var boolean
     */
    var $oneToOne;

    /**
     * ����������
     *
     * @var enum
     */
    var $type;

    /**
     * �Թ�������в�ѯʱʹ�õ��������
     *
     * @var string
     */
    var $sort;

    /**
     * �Թ�������в�ѯʱʹ�õ���������
     *
     * @var string
     */
    var $conditions;

    /**
     * �Թ�������в�ѯʱҪ��ȡ�Ĺ������ֶ�
     *
     * @var string|array
     */
    var $fields = '*';

    /**
     * �Թ�������в�ѯʱ���Ʋ���ļ�¼��
     *
     * @var int
     */
    var $limit = null;

    /**
     * �� enabled Ϊ false ʱ����������ڵ��κβ��������ᴦ��ù���
     *
     * enabled �����ȼ����� linkRead��linkCreate��linkUpdate �� linkRemove��
     *
     * @var boolean
     */
    var $enabled = true;

    /**
     * ָʾ�ڲ�ѯ������ʱ�Ƿ����ͳ�Ƽ�¼��������ʵ�ʲ�ѯ����
     *
     * @var boolean
     */
    var $countOnly = false;

    /**
     * ��������¼�������浽ָ�����ֶ�
     *
     * @var string
     */
    var $counterCache = null;

    /**
     * ָʾ�Ƿ��������ȡ��¼ʱҲ��ȡ�ù�����Ӧ�Ĺ�����ļ�¼
     *
     * @var boolean
     */
    var $linkRead = true;

    /**
     * ָʾ�Ƿ�����������¼ʱҲ�����ù�����Ӧ�Ĺ�����ļ�¼
     *
     * @var boolean
     */
    var $linkCreate = true;

    /**
     * ָʾ�Ƿ���������¼�¼ʱҲ���¸ù�����Ӧ�Ĺ�����ļ�¼
     *
     * @var boolean
     */
    var $linkUpdate = true;

    /**
     * ָʾ�Ƿ�������ɾ����¼ʱҲɾ���ù�����Ӧ�Ĺ�����ļ�¼
     *
     * @var boolean
     */
    var $linkRemove = true;

    /**
     * ��ɾ�������¼����ɾ���������¼ʱ����ʲôֵ���������¼������ֶ�
     *
     * @var mixed
     */
    var $linkRemoveFillValue = 0;

    /**
     * ָʾ�������������ʱ�����ú��ַ�����Ĭ��Ϊ save����������Ϊ create��update �� replace
     *
     * @var string
     */
    var $saveAssocMethod = 'save';

    /**
     * ����ı�������ڶ���
     *
     * @var FLEA_Db_TableDataGateway
     */
    var $mainTDG;

    /**
     * ������ı�������ڶ���
     *
     * @var FLEA_Db_TableDataGateway
     */
    var $assocTDG = null;

    /**
     * �������õĶ�������
     *
     * @var array
     */
    var $_req = array(
        'name',             // ����������
        'tableClass',       // �����ı�������ڶ�����
        'mappingName',      // �ֶ�ӳ����
    );

    /**
     * ��ѡ�Ĳ���
     *
     * @var array
     */
    var $_optional = array(
        'foreignKey',
        'sort',
        'conditions',
        'fields',
        'limit',
        'enabled',
        'countOnly',
        'counterCache',
        'linkRead',
        'linkCreate',
        'linkUpdate',
        'linkRemove',
        'linkRemoveFillValue',
        'saveAssocMethod',
    );

    /**
     * ����ֶε���ȫ�޶���
     *
     * @var string
     */
    var $qforeignKey;

    /**
     * ���ݷ��ʶ���
     *
     * @var FLEA_Db_Driver_Abstract
     */
    var $dbo;

    /**
     * ������������ڵĶ�����
     *
     * @var string
     */
    var $assocTDGObjectId;

    /**
     * ָʾ�����ı���������Ƿ��Ѿ���ʼ��
     *
     * @var boolean
     */
    var $init = false;

    /**
     * ���캯��
     *
     * �����߲�Ӧ�����й��� FLEA_Db_TableLink ʵ��������Ӧ��ͨ��
     * FLEA_Db_TableLink::createLink() ��̬����������ʵ����
     *
     * @param array $define
     * @param enum $type
     * @param FLEA_Db_TableDataGateway $mainTDG
     *
     * @return FLEA_Db_TableLink
     */
    function FLEA_Db_TableLink($define, $type, & $mainTDG)
    {
        static $defaultDsnId = null;

        // ������������Ƿ��Ѿ��ṩ
        foreach ($this->_req as $key) {
            if (!isset($define[$key]) || $define[$key] == '') {
                FLEA::loadClass('FLEA_Db_Exception_MissingLinkOption');
                return __THROW(new FLEA_Db_Exception_MissingLinkOption($key));
            } else {
                $this->{$key} = $define[$key];
            }
        }
        // ���ÿ�ѡ����
        foreach ($this->_optional as $key) {
            if (isset($define[$key])) {
                $this->{$key} = $define[$key];
            }
        }
        $this->type = $type;
        $this->mainTDG =& $mainTDG;
        $this->dbo =& $this->mainTDG->getDBO();
        $dsnid = $this->dbo->dsn['id'];

        if (is_null($defaultDsnId)) {
            $defaultDSN = FLEA::getAppInf('dbDSN');
            if ($defaultDSN) {
                $defaultDSN = FLEA::parseDSN($defaultDSN);
                $defaultDsnId = $defaultDSN['id'];
            } else {
                $defaultDsnId = -1;
            }
        }
        if ($dsnid == $defaultDsnId) {
            $this->assocTDGObjectId = null;
        } else {
            $this->assocTDGObjectId = "{$this->tableClass}-{$dsnid}";
        }
    }

    /**
     * ���� FLEA_Db_TableLink ����ʵ��
     *
     * @param array $define
     * @param enum $type
     * @param FLEA_Db_TableDataGateway $mainTDG
     *
     * @return FLEA_Db_TableLink
     */
    function & createLink($define, $type, & $mainTDG)
    {
        static $typeMap = array(
            HAS_ONE         => 'FLEA_Db_HasOneLink',
            BELONGS_TO      => 'FLEA_Db_BelongsToLink',
            HAS_MANY        => 'FLEA_Db_HasManyLink',
            MANY_TO_MANY    => 'FLEA_Db_ManyToManyLink',
        );
        static $instances = array();

        // ��� $type ����
        if (!isset($typeMap[$type])) {
            FLEA::loadClass('FLEA_Db_Exception_InvalidLinkType');
            return __THROW(new FLEA_Db_Exception_InvalidLinkType($type));
        }

        // tableClass �����Ǳ����ṩ��
        if (!isset($define['tableClass'])) {
            FLEA::loadClass('FLEA_Db_Exception_MissingLinkOption');
            return __THROW(new FLEA_Db_Exception_MissingLinkOption('tableClass'));
        }
        // ���û���ṩ mappingName ���ԣ���ʹ�� tableClass ��Ϊ mappingName
        if (!isset($define['mappingName'])) {
            $define['mappingName'] = $define['tableClass'];
        }
        // ���û���ṩ name ���ԣ���ʹ�� mappingName ������Ϊ name
        if (!isset($define['name'])) {
            $define['name'] = $define['mappingName'];
        }

        // ����� MANY_TO_MANY ���ӣ������Ƿ��ṩ�� joinTable ���Ի��� joinTableClass ���ԣ�
        // �Լ�assocForeignKey ����
        if ($type == MANY_TO_MANY) {
            if (!isset($define['joinTable']) && !isset($define['joinTableClass'])) {
                FLEA::loadClass('FLEA_Db_Exception_MissingLinkOption');
                return __THROW(new FLEA_Db_Exception_MissingLinkOption('joinTable'));
            }
        }

        $instances[$define['name']] =& new $typeMap[$type]($define, $type, $mainTDG);
        return $instances[$define['name']];
    }

    /**
     * ����һ�� MANY_TO_MANY ������Ҫ���м������
     *
     * @param string $table1
     * @param string $table2
     *
     * @return string
     */
    function getMiddleTableName($table1, $table2)
    {
        if (strcmp($table1, $table2) < 0) {
            return $this->dbo->dsn['prefix'] . "{$table1}_{$table2}";
        } else {
            return $this->dbo->dsn['prefix'] . "{$table2}_{$table1}";
        }
    }

    /**
     * ��������������¼ʱ���������������
     *
     * @param array $row Ҫ����Ĺ�������
     * @param mixed $pkv ����������ֶ�ֵ
     *
     * @return boolean
     */
    function saveAssocData(& $row, $pkv)
    {
        FLEA::loadClass('FLEA_Exception_NotImplemented');
        return __THROW(new FLEA_Exception_NotImplemented('saveAssocData()', 'FLEA_Db_TableLink'));
    }

    /**
     * ��ʼ����������
     */
    function init()
    {
        if ($this->init) { return; }
        if (FLEA::isRegistered($this->assocTDGObjectId)) {
            $this->assocTDG =& FLEA::registry($this->assocTDGObjectId);
        } else {
            if ($this->assocTDGObjectId) {
                FLEA::loadClass($this->tableClass);
                $this->assocTDG =& new $this->tableClass(array('dbo' => & $this->dbo));
                FLEA::register($this->assocTDG, $this->assocTDGObjectId);
            } else {
                $this->assocTDG =& FLEA::getSingleton($this->tableClass);
            }
        }
        $this->init = true;
    }

    /**
     * ͳ�ƹ�����¼��
     *
     * @param array $assocRowset
     * @param string $mappingName
     * @param string $in
     *
     * @return int
     */
    function calcCount(& $assocRowset, $mappingName, $in)
    {
        FLEA::loadClass('FLEA_Exception_NotImplemented');
        return __THROW(new FLEA_Exception_NotImplemented('calcCount()', 'FLEA_Db_TableLink'));
    }

    /**
     * �������ڲ�ѯ���������ݵ� SQL ���
     *
     * @param string $sql
     * @param string $in
     *
     * @return string
     */
    function _getFindSQLBase($sql, $in)
    {
        if ($in) {
            $sql .= " WHERE {$this->qforeignKey} {$in}";
        }
        if ($this->conditions) {
            if (is_array($this->conditions)) {
                $conditions = FLEA_Db_SqlHelper::parseConditions($this->conditions, $this->assocTDG);
                if (is_array($conditions)) {
                    $conditions = $conditions[0];
                }
            } else {
                $conditions =& $this->conditions;
            }
            if ($conditions) {
                $sql .= " AND {$conditions}";
            }
        }
        if ($this->sort && $this->countOnly == false) {
            $sql .= " ORDER BY {$this->sort}";
        }

        return $sql;
    }

    /**
     * ��������������¼ʱ���������������
     *
     * @param array $row Ҫ����Ĺ�������
     *
     * @return boolean
     */
    function _saveAssocDataBase(& $row)
    {
        switch (strtolower($this->saveAssocMethod)) {
        case 'create':
            return $this->assocTDG->create($row);
        case 'update':
            return $this->assocTDG->update($row);
        case 'replace':
            return $this->assocTDG->replace($row);
        default:
            return $this->assocTDG->save($row);
        }
    }
}

/**
 * FLEA_Db_HasOneLink ��װ has one ��ϵ
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_HasOneLink extends FLEA_Db_TableLink
{
    var $oneToOne = true;

    /**
     * ���캯��
     *
     * @param array $define
     * @param enum $type
     * @param FLEA_Db_TableDataGateway $mainTDG
     *
     * @return FLEA_Db_TableLink
     */
    function FLEA_Db_HasOneLink($define, $type, & $mainTDG)
    {
        parent::FLEA_Db_TableLink($define, $type, $mainTDG);
    }

    /**
     * �������ڲ�ѯ���������ݵ�SQL���
     *
     * @param string $in
     *
     * @return string
     */
    function getFindSQL($in)
    {
        if (!$this->init) { $this->init(); }
        $fields = $this->qforeignKey . ' AS ' . $this->mainTDG->pka . ', ' . $this->dbo->qfields($this->fields, $this->assocTDG->fullTableName, $this->assocTDG->schema);
        $sql = "SELECT {$fields} FROM {$this->assocTDG->qtableName} ";
        return parent::_getFindSQLBase($sql, $in);
    }

    /**
     * ��������������¼ʱ���������������
     *
     * @param array $row Ҫ����Ĺ�������
     * @param mixed $pkv ����������ֶ�ֵ
     *
     * @return boolean
     */
    function saveAssocData(& $row, $pkv)
    {
        if (empty($row)) { return true; }
        if (!$this->init) { $this->init(); }
        $row[$this->foreignKey] = $pkv;
        return $this->_saveAssocDataBase($row);
    }

    /**
     * ɾ������������
     *
     * @param mixed $qpkv
     *
     * @return boolean
     */
    function deleteByForeignKey($qpkv)
    {
        if (!$this->init) { $this->init(); }
        $conditions = "{$this->qforeignKey} = {$qpkv}";
        if ($this->linkRemove) {
            return $this->assocTDG->removeByConditions($conditions);
        } else {
            return $this->assocTDG->updateField($conditions, $this->foreignKey, $this->linkRemoveFillValue);
        }
    }

    /**
     * ��ȫ��ʼ����������
     */
    function init()
    {
        parent::init();
        if (is_null($this->foreignKey)) {
            $this->foreignKey = $this->mainTDG->primaryKey;
        }
        $this->qforeignKey = $this->dbo->qfield($this->foreignKey, $this->assocTDG->fullTableName, $this->assocTDG->schema);
    }

    /**
     * ͳ�ƹ�����¼��
     *
     * @param array $assocRowset
     * @param string $mappingName
     * @param string $in
     *
     * @return int
     */
    function calcCount(& $assocRowset, $mappingName, $in)
    {
        if (!$this->init) { $this->init(); }
        $sql = "SELECT {$this->qforeignKey} AS pid, COUNT({$this->qforeignKey}) AS c FROM {$this->assocTDG->qtableName} ";
        $sql = parent::_getFindSQLBase($sql, $in);
        $sql .= " GROUP BY {$this->qforeignKey}";

        $r = $this->dbo->execute($sql);
        while ($row = $this->dbo->fetchAssoc($r)) {
            $assocRowset[$row['pid']][$mappingName] = (int)$row['c'];
        }
        $this->dbo->freeRes($r);
    }
}

/**
 * FLEA_Db_BelongsToLink ��װ belongs to ��ϵ
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_BelongsToLink extends FLEA_Db_TableLink
{
    var $oneToOne = true;

    /**
     * ���캯��
     *
     * @param array $define
     * @param enum $type
     * @param FLEA_Db_TableDataGateway $mainTDG
     *
     * @return FLEA_Db_TableLink
     */
    function FLEA_Db_BelongsToLink($define, $type, & $mainTDG)
    {
        $this->linkUpdate = $this->linkCreate = $this->linkRemove = false;
        parent::FLEA_Db_TableLink($define, $type, $mainTDG);
    }

    /**
     * �������ڲ�ѯ���������ݵ�SQL���
     *
     * @param string $in
     *
     * @return string
     */
    function getFindSQL($in)
    {
        if (!$this->init) { $this->init(); }
        $fields = $this->mainTDG->qpk . ' AS ' . $this->mainTDG->pka . ', ' . $this->dbo->qfields($this->fields, $this->assocTDG->fullTableName, $this->assocTDG->schema);

        $sql = "SELECT {$fields} FROM {$this->assocTDG->qtableName} LEFT JOIN {$this->mainTDG->qtableName} ON {$this->mainTDG->qpk} {$in} WHERE {$this->qforeignKey} = {$this->assocTDG->qpk} ";
        $in = '';
        return parent::_getFindSQLBase($sql, $in);
    }

    /**
     * ��������������¼ʱ���������������
     *
     * @param array $row Ҫ����Ĺ�������
     * @param mixed $pkv ����������ֶ�ֵ
     *
     * @return boolean
     */
    function saveAssocData(& $row, $pkv)
    {
        if (empty($row)) { return true; }
        if (!$this->init) { $this->init(); }
        return $this->_saveAssocDataBase($row);
    }

    /**
     * ��ȫ��ʼ����������
     */
    function init()
    {
        parent::init();
        if (is_null($this->foreignKey)) {
            $this->foreignKey = $this->assocTDG->primaryKey;
        }
        $this->qforeignKey = $this->dbo->qfield($this->foreignKey, $this->mainTDG->fullTableName, $this->mainTDG->schema);
    }
}

/**
 * FLEA_Db_HasManyLink ��װ has many ��ϵ
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_HasManyLink extends FLEA_Db_HasOneLink
{
    var $oneToOne = false;

    /**
     * ��������������¼ʱ���������������
     *
     * @param array $row Ҫ����Ĺ�������
     * @param mixed $pkv ����������ֶ�ֵ
     *
     * @return boolean
     */
    function saveAssocData(& $row, $pkv)
    {
        if (empty($row)) { return true; }
        if (!$this->init) { $this->init(); }

        foreach ($row as $arow) {
            if (!is_array($arow)) { continue; }
            $arow[$this->foreignKey] = $pkv;
            if (!$this->_saveAssocDataBase($arow)) {
                return false;
            }
        }
        return true;
    }
}

/**
 * FLEA_Db_ManyToManyLink ��װ many to many ��ϵ
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_ManyToManyLink extends FLEA_Db_TableLink
{
    /**
     * ��Ϲ�������ʱ�Ƿ���һ��һ
     *
     * @var boolean
     */
    var $oneToOne = false;

    /**
     * �ڴ����м��ʱ���Ƿ�Ҫ���м����ʵ��
     *
     * @var boolean
     */
    var $joinTableIsEntity = false;

    /**
     * �м����ʵ��ʱ��Ӧ�ı��������
     *
     * @var FLEA_Db_TableDataGateway
     */
    var $joinTDG = null;

    /**
     * �м�������
     *
     * @var string
     */
    var $joinTable = null;

    /**
     * �м�����ȫ�޶���
     *
     * @var string
     */
    var $qjoinTable = null;

    /**
     * �м���б������������ֵ���ֶ�
     *
     * @var string
     */
    var $assocForeignKey = null;

    /**
     * �м���б������������ֵ���ֶε���ȫ�޶���
     *
     * @var string
     */
    var $qassocForeignKey = null;

    /**
     * �м���Ӧ�ı��������
     *
     * @var FLEA_Db_TableDataGateway
     */
    var $joinTableClass = null;

    /**
     * ���캯��
     *
     * @param array $define
     * @param enum $type
     * @param FLEA_Db_TableDataGateway $mainTDG
     *
     * @return FLEA_Db_TableLink
     */
    function FLEA_Db_ManyToManyLink($define, $type, & $mainTDG)
    {
        $this->_optional[] = 'joinTable';
        $this->_optional[] = 'joinTableClass';
        $this->_optional[] = 'assocForeignKey';
        parent::FLEA_Db_TableLink($define, $type, $mainTDG);

        if ($this->joinTableClass != '') {
            $this->joinTableIsEntity = true;
        }
    }

    /**
     * �������ڲ�ѯ���������ݵ�SQL���
     *
     * @param string $in
     *
     * @return string
     */
    function getFindSQL($in)
    {
        static $joinFields = array();

        if (!$this->init) { $this->init(); }

        $fields = $this->qforeignKey . ' AS ' . $this->mainTDG->pka . ', ' . $this->assocTDG->qfields($this->fields);

        if ($this->joinTableIsEntity) {
            if (!isset($joinFields[$this->joinTDG->fullTableName])) {
                $f = '';
                foreach ($this->joinTDG->meta as $field) {
                    $f .= ', ' . $this->joinTDG->qfield($field['name']) . '  AS join_' . $field['name'];
                }
                $joinFields[$this->joinTDG->fullTableName] = $f;
            }
            $fields .= $joinFields[$this->joinTDG->fullTableName];

            $sql = "SELECT {$fields} FROM {$this->joinTDG->qtableName} INNER JOIN {$this->assocTDG->qtableName} ON {$this->assocTDG->qpk} = {$this->qassocForeignKey} ";
        } else {
            $sql = "SELECT {$fields} FROM {$this->qjoinTable} INNER JOIN {$this->assocTDG->qtableName} ON {$this->assocTDG->qpk} = {$this->qassocForeignKey} ";
        }

        return parent::_getFindSQLBase($sql, $in);
    }

    /**
     * ��������������¼ʱ���������������
     *
     * @param array $row Ҫ����Ĺ�������
     * @param mixed $pkv ����������ֶ�ֵ
     *
     * @return boolean
     */
    function saveAssocData(& $row, $pkv)
    {
        if (!$this->init) { $this->init(); }
        $apkvs = array();
        $entityRowset = array();

        foreach ($row as $arow) {
            if (!is_array($arow)) {
                $apkvs[] = $arow;
                continue;
            }
            if (!isset($arow[$this->assocForeignKey])) {
                // ���������¼��δ���浽���ݿ⣬�򴴽�һ���µĹ�����¼
                $newrowid = $this->assocTDG->create($arow);
                if ($newrowid == false) {
                    return false;
                }
                $apkv = $newrowid;
            } else {
                $apkv = $arow[$this->assocForeignKey];
            }
            $apkvs[] = $apkv;
            if ($this->joinTableIsEntity && isset($arow['#JOIN#'])) {
                $entityRowset[$apkv] =& $arow['#JOIN#'];
            }
        }

        // ����ȡ�����еĹ�����Ϣ
        $qpkv = $this->dbo->qstr($pkv);
        $sql = "SELECT {$this->qassocForeignKey} FROM {$this->qjoinTable} WHERE {$this->qforeignKey} = {$qpkv} ";
        $existsMiddle = (array)$this->dbo->getCol($sql);

        // Ȼ��ȷ��Ҫ��ӵĹ�����Ϣ
        $insertAssoc = array_diff($apkvs, $existsMiddle);
        $removeAssoc = array_diff($existsMiddle, $apkvs);

        if ($this->joinTableIsEntity) {
            $insertEntityRowset = array();
            foreach ($insertAssoc as $assocId) {
                if (isset($entityRowset[$assocId])) {
                    $row = $entityRowset[$assocId];
                } else {
                    $row = array();
                }
                $row[$this->foreignKey] = $pkv;
                $row[$this->assocForeignKey] = $assocId;
                $insertEntityRowset[] = $row;
            }
            if ($this->joinTDG->createRowset($insertEntityRowset) === false) {
                return false;
            }
        } else {
            $sql = "INSERT INTO {$this->qjoinTable} ({$this->qforeignKey}, {$this->qassocForeignKey}) VALUES ({$qpkv}, ";
            foreach ($insertAssoc as $assocId) {
                if (!$this->dbo->execute($sql . $this->dbo->qstr($assocId) . ')')) {
                    return false;
                }
            }
        }

        // ���ɾ��������Ҫ�Ĺ�����Ϣ
        if ($this->joinTableIsEntity) {
            $conditions = array($this->foreignKey => $pkv);
            foreach ($removeAssoc as $assocId) {
                $conditions[$this->assocForeignKey] = $assocId;
                if ($this->joinTDG->removeByConditions($conditions) === false) {
                    return false;
                }
            }
        } else {
            $sql = "DELETE FROM {$this->qjoinTable} WHERE {$this->qforeignKey} = {$qpkv} AND {$this->qassocForeignKey} = ";
            foreach ($removeAssoc as $assocId) {
                if (!$this->dbo->execute($sql . $this->dbo->qstr($assocId))) {
                    return false;
                }
            }
        }

        if ($this->counterCache) {
            $sql = "UPDATE {$this->mainTDG->qtableName} SET {$this->counterCache} = (SELECT COUNT(*) FROM {$this->qjoinTable} WHERE {$this->qforeignKey} = {$qpkv}) WHERE {$this->mainTDG->qpk} = {$qpkv}";
            $this->mainTDG->dbo->execute($sql);
        }

        return true;
    }

    /**
     * �������������ֶ�ֵ��ɾ���м�������
     *
     * @param mixed $qpkv
     *
     * @return boolean
     */
    function deleteMiddleTableDataByMainForeignKey($qpkv)
    {
        if (!$this->init) { $this->init(); }
        $sql = "DELETE FROM {$this->qjoinTable} WHERE {$this->qforeignKey} = {$qpkv} ";
        return $this->dbo->execute($sql);
    }

    /**
     * ���ݹ����������ֶ�ֵ��ɾ���м�������
     *
     * @param mixed $pkv
     *
     * @return boolean
     */
    function deleteMiddleTableDataByAssocForeignKey($pkv)
    {
        if (!$this->init) { $this->init(); }
        $qpkv = $this->dbo->qstr($pkv);
        $sql = "DELETE FROM {$this->qjoinTable} WHERE {$this->qassocForeignKey} = {$qpkv} ";
        return $this->dbo->execute($sql);
    }

    /**
     * ��ȫ��ʼ����������
     */
    function init()
    {
        parent::init();
        if ($this->joinTableClass) {
            $this->joinTDG =& FLEA::getSingleton($this->joinTableClass);
            $this->joinTable = $this->joinTDG->tableName;
            $joinSchema = $this->joinTDG->schema;
        } else {
            $joinSchema = $this->mainTDG->schema;
        }
        if (is_null($this->joinTable)) {
            $this->joinTable = $this->getMiddleTableName($this->mainTDG->tableName, $this->assocTableName);
        }
        if (is_null($this->foreignKey)) {
            $this->foreignKey = $this->mainTDG->primaryKey;
        }
        $this->joinTable = $this->dbo->dsn['prefix'] . $this->joinTable;
        $this->qjoinTable = $this->dbo->qtable($this->joinTable, $joinSchema);
        $this->qforeignKey = $this->dbo->qfield($this->foreignKey, $this->joinTable, $joinSchema);
        if (is_null($this->assocForeignKey)) {
            $this->assocForeignKey = $this->assocTDG->primaryKey;
        }
        $this->qassocForeignKey = $this->dbo->qfield($this->assocForeignKey, $this->joinTable, $this->mainTDG->schema);
    }
}

/**
 * FLEA_Db_SqlHelper ���ṩ�˸������� SQL ���ĸ�������
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_SqlHelper
{
    /**
     * ������ѯ����
     *
     * @param mixed $conditions
     * @param FLEA_Db_TableDataGateway $table
     *
     * @return array
     */
    function parseConditions($conditions, & $table)
    {
        // ���� NULL��ֱ�ӷ��� NULL
        if (is_null($conditions)) { return null; }

        // ��������֣���ٶ�Ϊ�����ֶ�ֵ
        if (is_numeric($conditions)) {
            return "{$table->qpk} = {$conditions}";
        }

        // ������ַ�������ٶ�Ϊ�Զ�������
        if (is_string($conditions)) {
            return $conditions;
        }

        // ����������飬˵���ṩ�Ĳ�ѯ��������
        if (!is_array($conditions)) {
            return null;
        }

        $where = '';
        $linksWhere = array();
        $expr = '';

        foreach ($conditions as $offset => $cond) {
            $expr = 'AND';
            /**
             * ��������������ʽ��һ��ת��Ϊ (�ֶ���, ֵ, ����, ���������, ֵ�Ƿ���SQL����) ����ʽ
             */
            if (is_string($offset)) {
                if (!is_array($cond)) {
                    // �ֶ��� => ֵ
                    $cond = array($offset, $cond);
                } else {
                    if (strtolower($offset) == 'in()') {
                        if (count($cond) == 1 && is_array(reset($cond)) && is_string(key($cond))) {
                            $tmp = $table->qfield(key($cond)) . ' IN (' . implode(',', array_map(array(& $table->dbo, 'qstr'), reset($cond))). ')';
                        } else {
                            $tmp = $table->qpk . ' IN (' . implode(',', array_map(array(& $table->dbo, 'qstr'), $cond)). ')';
                        }
                        $cond = array('', $tmp, '', $expr, true);
                    } else {
                        // �ֶ��� => ����
                        array_unshift($cond, $offset);
                    }
                }
            } elseif (is_int($offset)) {
                if (!is_array($cond)) {
                    // ֵ
                    $cond = array('', $cond, '', $expr, true);
                }
            } else {
                continue;
            }

            if (!isset($cond[0])) { continue; }
            if (!isset($cond[2])) { $cond[2] = '='; }
            if (!isset($cond[3])) { $cond[3] = $expr; }
            if (!isset($cond[4])) { $cond[4] = false; }

            list($field, $value, $op, $expr, $isCommand) = $cond;

            $str = '';
            do {
                if (strpos($field, '.') !== false) {
                    list($scheme, $field) = explode('.', $field);
                    $linkname = strtoupper($scheme);
                    if (isset($table->links[$linkname])) {
                        $linksWhere[$linkname][] = array($field, $value, $op, $expr, $isCommand);
                        break;
                    } else {
                        $field = "{$scheme}.{$field}";
                    }
                }

                if (!$isCommand) {
                    $field = $table->qfield($field);
                    $value = $table->dbo->qstr($value);
                    $str = "{$field} {$op} {$value} {$expr} ";
                } else {
                    $str = "{$value} {$expr} ";
                }
            } while (false);

            $where .= $str;
        }

        $where = substr($where, 0, - (strlen($expr) + 2));
        if (empty($linksWhere)) {
            return $where;
        } else {
            return array($where, $linksWhere);
        }
    }

    /**
     * ��ʽ����� SQL ��־
     *
     * @param array $log
     */
    function dumpLog(& $log)
    {
        foreach ($log as $ix => $sql) {
            dump($sql, 'SQL ' . ($ix + 1));
        }
    }
}
