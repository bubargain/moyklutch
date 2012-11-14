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
 * ���� FLEA_Db_TableDataGateway ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: TableDataGateway.php 1401 2008-10-08 03:00:38Z yangkun $
 */

// {{{ includes
FLEA::loadClass('FLEA_Db_TableLink');
// }}}

// {{{ constants
/**
 * HAS_ONE ������ʾһ����¼ӵ����һ�������ļ�¼
 */
define('HAS_ONE',       1);

/**
 * BELONGS_TO ������ʾһ����¼������һ����¼
 */
define('BELONGS_TO',    2);

/**
 * HAS_MANY ������ʾһ����¼ӵ�ж�������ļ�¼
 */
define('HAS_MANY',      3);

/**
 * MANY_TO_MANY ������ʾ�������ݱ�����ݻ�������
 */
define('MANY_TO_MANY',  4);
// }}}

/**
 * FLEA_Db_TableDataGateway �ࣨ��������ڣ���װ�����ݱ�� CRUD ����
 *
 * ������Ӧ�ô� FLEA_Db_TableDataGateway �����Լ����࣬
 * ��ͨ����ӷ�������װ��Ը����ݱ�ĸ����ӵ����ݿ������
 *
 * ����ÿһ����������ڶ��󣬶��������ඨ����ͨ�� $tableName �� $primaryKey
 * ���ֱ�ָ�����ݱ�����ֺ������ֶ�����
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.2
 */
class FLEA_Db_TableDataGateway
{
    /**
     * ���ݿ� schema
     *
     * @var string
     */
    var $schema = '';

    /**
     * ���ݱ�����û�����ǰ׺��
     *
     * @var string
     */
    var $tableName = null;

    /**
     * ����ǰ׺���������ݱ�����
     *
     * @var string
     */
    var $fullTableName = null;

    /**
     * �����ֶ����������ǰ�����������ֶ���������
     *
     * @var sring|array
     */
    var $primaryKey = null;

    /**
     * ����һ��һ����
     *
     * @var array
     */
    var $hasOne = null;

    /**
     * �����������
     *
     * @var array
     */
    var $belongsTo = null;

    /**
     * ����һ�Զ����
     *
     * @var array
     */
    var $hasMany = null;

    /**
     * �����Զ����
     *
     * @var array
     */
    var $manyToMany = null;

    /**
     * ��ǰ���ݱ��Ԫ����
     *
     * Ԫ������һ����ά���飬ÿһ��Ԫ�صļ�������ȫ��д���ֶ�����
     * ����ֵ���Ǹ��ֶε����ݱ��塣
     *
     * @var array
     */
    var $meta = null;

    /**
     * ��ǰ���ݱ�������ֶ���
     *
     * @var array
     */
    var $fields = null;

    /**
     * ָʾ�Ƿ�����ݽ����Զ���֤
     *
     * �� autoValidating Ϊ true ʱ��create() �� update() �����������ݽ�����֤��
     *
     * @var boolean
     */
    var $autoValidating = false;

    /**
     * ����������֤�Ķ���
     *
     * @var FLEA_Helper_Verifier
     */
    var $verifier = null;

    /**
     * ���ӵ���֤����
     *
     * @var array
     */
    var $validateRules = null;

    /**
     * ������¼ʱ��Ҫ�Զ����뵱ǰʱ����ֶ�
     *
     * ֻҪ���ݱ���������ֶ�֮һ������� create() ����������¼ʱ��
     * ���Է�����ʱ���Զ������ֶΡ�
     *
     * @var array
     */
    var $createdTimeFields = array('CREATED', 'CREATED_ON', 'CREATED_AT');

    /**
     * �����͸��¼�¼ʱ��Ҫ�Զ����뵱ǰʱ����ֶ�
     *
     * ֻҪ���ݱ���������ֶ�֮һ������� create() ����������¼�� update() ���¼�¼ʱ��
     * ���Է�����ʱ���Զ������ֶΡ�
     *
     * @var array
     */
    var $updatedTimeFields = array('UPDATED', 'UPDATED_ON', 'UPDATED_AT');

    /**
     * ָʾ���� CRUD ����ʱ�Ƿ������
     *
     * ������Ӧ��ʹ�� enableLinks() �� disableLinks() ���������û���ù�������
     *
     * @var boolean
     */
    var $autoLink = true;

    /**
     * ���ݿ���ʶ���
     *
     * �����߲�Ӧ��ֱ�ӷ��ʸó�Ա����������ͨ�� setDBO() �� getDBO() ����
     * �����ʱ��������ʹ�����ݷ��ʶ���
     *
     * @var FLEA_Db_Driver_Abstract
     */
    var $dbo = null;

    /**
     * �洢������Ϣ
     *
     * $links ��һ�����飬�����б��� TableLink ����
     * ������Ӧ��ʹ�� getLink() �� createLink() �ȷ�����������Щ��������
     *
     * @var array
     */
    var $links = array();

    /**
     * ����ǰ׺�����ݱ���ȫ�޶���
     *
     * @var string
     * @access private
     */
    var $qtableName;

    /**
     * �����ֶ���ȫ�޶���
     *
     * @var string
     * @access private
     */
    var $qpk;

    /**
     * ���ڹ�����ѯʱ�������ֶα���
     */
    var $pka;

    /**
     * ���ڹ�����ѯʱ�������ֶ���ȫ�޶���
     *
     * @var string
     * @access private
     */
    var $qpka;

    /**
     * �������һ��������֤�Ľ��
     *
     * ���� getLastValidation() �������Ի�����һ��������֤�Ľ����
     *
     * @var array
     */
    var $lastValidationResult;

    /**
     * ���� FLEA_Db_TableDataGateway ʵ��
     *
     * $params ��������������ѡ�
     *   - schema: ָ�����ݱ�� schema
     *   - tableName: ָ�����ݱ�����ƣ�
     *   - primaryKey: ָ�������ֶ���
     *   - autoValidating: ָʾ�Ƿ�ʹ���Զ���֤��
     *   - verifierProvider: ָ��Ҫʹ�õ�������֤�������
     *     ���δָ������ʹ��Ӧ�ó������� helper.verifier ָ������֤�����ṩ����
     *   - skipConnect: ָʾ��ʼ����������ڶ���ʱ�Ƿ����ӵ����ݿ⣻
     *   - dbDSN: ָ���������ݿ�Ҫʹ�õ� DSN�����δָ����ʹ��Ĭ�ϵ� DSN ���ã�
     *   - dbo: ָ��Ҫʹ�õ����ݿ���ʶ���;
     *   - skipCreateLinks: ָʾ��ʼ�����������ʱ���Ƿ񲻽���������ϵ
     *
     * @param array $params
     *
     * @return FLEA_Db_TableDataGateway
     */
    function FLEA_Db_TableDataGateway($params = null)
    {
        if (!empty($params['schema'])) {
            $this->schema = $params['schema'];
        }
        if (!empty($params['tableName'])) {
            $this->tableName = $params['tableName'];
        }

        if (!empty($params['primaryKey'])) {
            $this->primaryKey = $params['primaryKey'];
        }

        // ��ʼ����֤�������
        if (isset($params['autoValidating'])) {
            $this->autoValidating = $params['autoValidating'];
        }
        if ($this->autoValidating) {
            if (!empty($params['verifierProvider'])) {
                $provider = $params['verifierProvider'];
            } else {
                $provider = FLEA::getAppInf('helper.verifier');
            }
            if (!empty($provider)) {
                $this->verifier =& FLEA::getSingleton($provider);
            }
        }

        // �� skipInit Ϊ true ʱ������ʼ����������ڶ���
        if (isset($params['skipConnect']) && $params['skipConnect'] != false) {
            return;
        }

        // ��ʼ�����ݷ��ʶ���
        if (!isset($params['dbo'])) {
            if (isset($params['dbDSN'])) {
                $dbo =& FLEA::getDBO($params['dbDSN']);
            } else {
                $dbo =& FLEA::getDBO();
            }
        } else {
            $dbo =& $params['dbo'];
        }
        $this->setDBO($dbo);

        // �� skipCreateLinks ��Ϊ true ʱ����������
        if (!isset($params['skipCreateLinks']) || $params['skipCreateLinks'] == false) {
            $this->relink();
        }
    }

    /**
     * �������ݿ���ʶ���
     *
     * @param FLEA_Db_Driver_Abstract $dbo
     *
     * @return boolean
     */
    function setDBO(& $dbo)
    {
        $this->dbo =& $dbo;

        if (empty($this->schema) && !empty($dbo->dsn['schema'])) {
            $this->schema = $dbo->dsn['schema'];
        }
        if (empty($this->fullTableName)) {
            $this->fullTableName = $dbo->dsn['prefix'] . $this->tableName;
        }
        $this->qtableName = $dbo->qtable($this->fullTableName, $this->schema);

        if (!$this->_prepareMeta()) {
            return false;
        }
        $this->fields = array_keys($this->meta);

        if (is_array($this->validateRules)) {
            foreach ($this->validateRules as $fieldName => $rules) {
                $fieldName = strtoupper($fieldName);
                if (!isset($this->meta[$fieldName])) { continue; }
                foreach ((array)$rules as $ruleName => $rule) {
                    $this->meta[$fieldName][$ruleName] = $rule;
                }
            }
        }

        // ���û��ָ�������������Զ���ȡ
        if (empty($this->primaryKey)) {
            foreach ($this->meta as $field) {
                if ($field['primaryKey']) {
                    $this->primaryKey = $field['name'];
                    break;
                }
            }
        }

        if (is_array($this->primaryKey)) {
            $this->qpk = array();
            $this->pka = array();
            $this->qpka = array();
            foreach ($this->primaryKey as $pk) {
                $qpk = $dbo->qfield($pk, $this->fullTableName, $this->schema);
                $this->qpk[$pk] = $qpk;
                $pka = 'flea_pkref_' . $pk;
                $this->pka[$pk] = $pka;
                $this->qpka[$pk] = $qpk . ' AS ' . $pka;
            }
        } else {
            $this->qpk = $dbo->qfield($this->primaryKey, $this->fullTableName, $this->schema);
            $this->pka = 'flea_pkref_' . $this->primaryKey;
            $this->qpka = $this->qpk . ' AS ' . $this->pka;
        }

        return true;
    }

    /**
     * ���ظñ�������ڶ���ʹ�õ����ݷ��ʶ���
     *
     * @return FLEA_Db_Driver_Abstract
     */
    function & getDBO()
    {
        return $this->dbo;
    }

    /**
     * ���ط��������ĵ�һ����¼�����й��������ݣ���ѯû�н������ false
     *
     * @param mixed $conditions
     * @param string $sort
     * @param mixed $fields
     * @param mixed $queryLinks
     *
     * @return array
     */
    function & find($conditions, $sort = null, $fields = '*', $queryLinks = true)
    {
        $rowset =& $this->findAll($conditions, $sort, 1, $fields, $queryLinks);
        if (is_array($rowset)) {
            $row = reset($rowset);
        } else {
            $row = false;
        }
        unset($rowset);
        return $row;
    }

    /**
     * ��ѯ���з��������ļ�¼��������ݣ�����һ���������м�¼�Ķ�ά���飬ʧ��ʱ���� false
     *
     * @param mixed $conditions
     * @param string $sort
     * @param mixed $limit
     * @param mixed $fields
     * @param mixed $queryLinks
     *
     * @return array
     */
    function & findAll($conditions = null, $sort = null, $limit = null, $fields = '*', $queryLinks = true)
    {
        list($whereby, $distinct) = $this->getWhere($conditions);
        // ��������
        $sortby = $sort != '' ? " ORDER BY {$sort}" : '';
        // ���� $limit
        if (is_array($limit)) {
            list($length, $offset) = $limit;
        } else {
            $length = $limit;
            $offset = null;
        }

        // ����������ѯ���ݵ� SQL ���
        $enableLinks = count($this->links) > 0 && $this->autoLink && $queryLinks;
        $fields = $this->dbo->qfields($fields, $this->fullTableName, $this->schema);
        if ($enableLinks) {
            // ���й�����Ҫ����ʱ������������������ֶ�ֵ
            $sql = "SELECT {$distinct} {$this->qpka}, {$fields} FROM {$this->qtableName} {$whereby} {$sortby}";
        } else {
            $sql = "SELECT {$distinct} {$fields} FROM {$this->qtableName} {$whereby} {$sortby}";
        }

        // ���� $length �� $offset ���������Ƿ�ʹ���޶�������Ĳ�ѯ
        if (null !== $length || null !== $offset) {
            $result = $this->dbo->selectLimit($sql, $length, $offset);
        } else {
            $result = $this->dbo->execute($sql);
        }

        if ($enableLinks) {
            /**
             * ��ѯʱͬʱ������ֵ������ȡ������
             * ����׼��һ��������ֵΪ�����Ķ�ά�������ڹ������ݵ�װ��
             */
            $pkvs = array();
            $assocRowset = null;
            $rowset = $this->dbo->getAllWithFieldRefs($result, $this->pka, $pkvs, $assocRowset);
            $in = 'IN (' . implode(',', array_map(array(& $this->dbo, 'qstr'), $pkvs)) . ')';
        } else {
            $rowset = $this->dbo->getAll($result);
        }
        unset($result);

        // ���û�й�����Ҫ�������û�в�ѯ�������ֱ�ӷ��ز�ѯ���
        if (!$enableLinks || empty($rowset) || !$this->autoLink) {
            return $rowset;
        }

        /**
         * ����ÿһ���������󣬲��ӹ��������ȡ��ѯ���
         *
         * ��ѯ������ݺ󣬽�����������ݺ���������װ����һ��
         */
        $callback = create_function('& $r, $o, $m', '$r[$m] = null;');
        foreach ($this->links as $link) {
            /* @var $link FLEA_Db_TableLink */
            $mn = $link->mappingName;
            if (!$link->enabled || !$link->linkRead) { continue; }
            if (!$link->countOnly) {
                array_walk($assocRowset, $callback, $mn);
                $sql = $link->getFindSQL($in);
                $this->dbo->assemble($sql, $assocRowset, $mn, $link->oneToOne, $this->pka, $link->limit);
            } else {
                $link->calcCount($assocRowset, $mn, $in);
            }
        }

        return $rowset;
    }

    /**
     * �Ե�һ��¼���еݹ��ѯ��������ѯ�����װ����¼��
     *
     * @param string $mappingName
     * @param array $row
     * @param array $enabledLinks
     */
    function assembleRecursionRow($mappingName, & $row, $enabledLinks = null)
    {
        $assoclink =& $this->getLink($mappingName);
        if ($assoclink == false) { return false; }

        $assoclink->init();
        $tdg =& $assoclink->assocTDG;
        $arow =& $row[$mappingName];

        if (!is_array($enabledLinks)) {
            if ($enabledLinks == null) {
                $enabledLinks = array_keys($tdg->links);
            } else {
                $enabledLinks = explode(',', $enabledLinks);
                array_walk($enabledLinks, 'trim');
                $enabledLinks = array_filter($enabledLinks, 'strlen');
            }
        }
        $enabledLinks = array_flip($enabledLinks);
        $enabledLinks = array_change_key_case($enabledLinks, CASE_LOWER);
        $this->enableLinks(array_keys($enabledLinks));

        foreach ($tdg->links as $link) {
            /* @var $link FLEA_Db_TableLink */
            if (!$link->enabled || !$link->linkRead || !isset($enabledLinks[$link->mappingName])) { continue; }

            $in = array();
            switch ($assoclink->type) {
            case HAS_ONE:
            case BELONGS_TO:
                $pkv = $arow[$link->mainTDG->primaryKey];
                $in[] = $pkv;
                $assocRowset = array($pkv => & $arow);
                $arow[$link->mappingName] = null;
                break;
            case HAS_MANY:
            case MANY_TO_MANY:
                $assocRowset = array();
                foreach (array_keys($arow) as $offset) {
                    $pkv = $arow[$offset][$link->mainTDG->primaryKey];
                    $in[] = $pkv;
                    $assocRowset[$pkv] = & $arow[$offset];
                    $arow[$offset][$link->mappingName] = null;
                }
            }
            if (empty($in)) { continue; }

            $in = 'IN (' . implode(',', array_map(array(& $this->dbo, 'qstr'), $in)) . ')';

            $sql = $link->getFindSQL($in);
            $this->dbo->assemble($sql, $assocRowset, $link->mappingName, $link->oneToOne, $link->mainTDG->pka, $link->limit);
        }

        return true;
    }


    /**
     * �Զ��м�¼���ݹ��ѯ��������ѯ�����װ����¼��
     *
     * @param string $mappingName
     * @param array $rowset
     * @param array $enabledLinks
     */
    function assembleRecursionRowset($mappingName, & $rowset, $enabledLinks = null)
    {
        $assoclink =& $this->getLink($mappingName);
        if ($assoclink == false) { return false; }

        $assoclink->init();
        $tdg =& $assoclink->assocTDG;
        $arowset = array();
        foreach (array_keys($rowset) as $offset) {
            $arowset[] =& $rowset[$offset][$mappingName];
        }
        $keys = array_keys($arowset);

        if (!is_array($enabledLinks)) {
            if ($enabledLinks == null) {
                $enabledLinks = array_keys($tdg->links);
            } else {
                $enabledLinks = explode(',', $enabledLinks);
                array_walk($enabledLinks, 'trim');
                $enabledLinks = array_filter($enabledLinks, 'strlen');
            }
        }
        $enabledLinks = array_flip($enabledLinks);
        $enabledLinks = array_change_key_case($enabledLinks, CASE_LOWER);
        $this->enableLinks(array_keys($enabledLinks));

        foreach ($tdg->links as $link) {
            /* @var $link FLEA_Db_TableLink */
            if (!$link->enabled || !$link->linkRead || !isset($enabledLinks[$link->mappingName])) { continue; }

            $in = array();
            $assocRowset = array();
            switch ($assoclink->type) {
            case HAS_ONE:
            case BELONGS_TO:
                foreach ($keys as $key) {
                    $pkv = $arowset[$key][$link->mainTDG->primaryKey];
                    $in[] = $pkv;
                    $assocRowset[$pkv] =& $arowset[$key];
                    $arowset[$key][$link->mappingName] = null;
                }
                break;
            case HAS_MANY:
            case MANY_TO_MANY:
                foreach ($keys as $key) {
                    foreach (array_keys($arowset[$key]) as $offset) {
                        $pkv = $arowset[$key][$offset][$link->mainTDG->primaryKey];
                        $in[] = $pkv;
                        $assocRowset[$pkv] = & $arowset[$key][$offset];
                        $arow[$key][$offset][$link->mappingName] = null;
                    }
                }
            }
            $in = 'IN (' . implode(',', array_map(array(& $this->dbo, 'qstr'), $in)) . ')';

            $sql = $link->getFindSQL($in);
            $this->dbo->assemble($sql, $assocRowset, $link->mappingName, $link->oneToOne, $link->mainTDG->pka, $link->limit);
        }

        return true;
    }

    /**
     * ���ؾ���ָ���ֶ�ֵ�ĵ�һ����¼
     *
     * @param string $field
     * @param mixed $value
     * @param string $sort
     * @param mixed $fields
     *
     * @return array
     */
    function & findByField($field, $value, $sort = null, $fields = '*')
    {
        return $this->find(array($field => $value), $sort, $fields);
    }

    /**
     * ���ؾ���ָ���ֶ�ֵ�����м�¼
     *
     * @param string $field
     * @param mixed $value
     * @param string $sort
     * @param array $limit
     * @param mixed $fields
     *
     * @return array
     */
    function & findAllByField($field, $value, $sort = null, $limit = null, $fields = '*')
    {
        return $this->findAll(array($field => $value), $sort, $limit, $fields);
    }

    /**
     * �԰�������ֵ������Ϊ������ѯ��¼��
     *
     * @param array $pkvs
     * @param mixed $conditions
     * @param string $sort
     * @param mixed $limit
     * @param mixed $fields
     * @param mixed $queryLinks
     *
     * @return array
     */
    function & findAllByPkvs($pkvs, $conditions = null, $sort = null, $limit = null, $fields = '*', $queryLinks = true)
    {
        $in = array('in()' => $pkvs);
        if (empty($conditions)) {
            $conditions = $in;
        } else {
            if (!is_array($conditions)) {
                $conditions = array($in, $conditions);
            } else {
                array_push($conditions, $in);
            }
        }

        return $this->findAll($conditions, $sort, $limit, $fields, $queryLinks);
    }

    /**
     * ֱ��ʹ�� sql ����ȡ��¼���÷������ᴦ��������ݱ�
     *
     * @param string $sql
     * @param mixed $limit
     *
     * @return array
     */
    function & findBySql($sql, $limit = null)
    {
        // ���� $limit
        if (is_array($limit)) {
            list($length, $offset) = $limit;
        } else {
            $length = $limit;
            $offset = null;
        }
        if (is_null($length) && is_null($offset)) {
            return $this->dbo->getAll($sql);
        }

        $result = $this->dbo->selectLimit($sql, $length, $offset);
        if ($result) {
            $rowset = $this->dbo->getAll($result);
        } else {
            $rowset = false;
        }
        return $rowset;
    }

    /**
     * ͳ�Ʒ��������ļ�¼������
     *
     * @param mixed $conditions
     * @param string|array $fields
     *
     * @return int
     */
    function findCount($conditions = null, $fields = null)
    {
        list($whereby, $distinct) = $this->getWhere($conditions);
        if (is_null($fields)) {
            $fields = $this->qpk;
        } else {
            $fields = $this->dbo->qfields($fields, $this->fullTableName);
        }
        $sql = "SELECT {$distinct}COUNT({$fields}) FROM {$this->qtableName}{$whereby}";
        return (int)$this->dbo->getOne($sql);
    }

    /**
     * �������ݵ����ݿ�
     *
     * ������ݰ�������ֵ���� save() ����� update() �����¼�¼��������� create() ��������¼��
     *
     * @param array $row
     * @param boolean $saveLinks
     * @param boolean $updateCounter
     *
     * @return boolean
     */
    function save(& $row, $saveLinks = true, $updateCounter = true)
    {
        if (empty($row[$this->primaryKey])) {
            return $this->create($row, $saveLinks, $updateCounter);
        } else {
            return $this->update($row, $saveLinks, $updateCounter);
        }
    }

    /**
     * ����һ����¼�����������ݣ�
     *
     * @param array $rowset
     * @param boolean $saveLinks
     *
     * @return boolean
     */
    function saveRowset(& $rowset, $saveLinks = true)
    {
        $this->dbo->startTrans();
        foreach ($rowset as $row) {
            if (!$this->save($row, $saveLinks, false)) {
                $this->dbo->completeTrans(false);
                return false;
            }
        }
        $this->dbo->completeTrans();
        return true;
    }

    /**
     * �滻һ�����м�¼������¼�¼�����ؼ�¼������ֵ��ʧ�ܷ��� false
     *
     * @param array $row
     *
     * @return mixed
     */
    function replace(& $row) {
        $this->_setCreatedTimeFields($row);
        $fields = '';
        $values = '';
        foreach ($row as $field => $value) {
            if (!isset($this->meta[strtoupper($field)])) { continue; }
            $fields .= $this->dbo->qfield($field) . ', ';
            $values .= $this->dbo->qstr($value) . ', ';
        }
        $fields = substr($fields, 0, -2);
        $values = substr($values, 0, -2);
        $sql = "REPLACE INTO {$this->fullTableName} ({$fields}) VALUES ({$values})";
        if (!$this->dbo->execute($sql)) { return false; }

        if (!empty($row[$this->primaryKey])) {
            return $row[$this->primaryKey];
        }

        $insertid = $this->dbo->insertId();
        return $insertid;
    }

    /**
     * �滻��¼�����������ݣ������ؼ�¼���������ֶ�ֵ��ʧ�ܷ��� false
     *
     * @param array $rowset
     *
     * @return array
     */
    function replaceRowset(& $rowset)
    {
        $ids = array();
        $this->dbo->startTrans();
        foreach ($rowset as $row) {
            $id = $this->replace($row, false);
            if (!$id) {
                $this->dbo->completeTrans(false);
                return false;
            }
            $ids[] = $id;
        }
        $this->dbo->completeTrans();
        return $ids;
    }

    /**
     * ����һ�����еļ�¼���ɹ����� true��ʧ�ܷ��� false
     *
     * �ò��������� _beforeUpdate()��_beforeUpdateDb() �� _afterUpdateDb() �¼���
     *
     * @param array $row
     * @param boolean $saveLinks
     *
     * @return boolean
     */
    function update(& $row, $saveLinks = true)
    {
        if (!$this->_beforeUpdate($row)) {
            return false;
        }

        // ����Ƿ��ṩ������ֵ
        if (!isset($row[$this->primaryKey])) {
            FLEA::loadClass('FLEA_Db_Exception_MissingPrimaryKey');
            return __THROW(new FLEA_Db_Exception_MissingPrimaryKey($this->primaryKey));
        }

        // �Զ���д��¼��������ʱ���ֶ�
        $this->_setUpdatedTimeFields($row);

        // ����ṩ����֤���������������֤
        if ($this->autoValidating && !is_null($this->verifier)) {
            if (!$this->checkRowData($row, true)) {
                // ��֤ʧ���׳��쳣
                FLEA::loadClass('FLEA_Exception_ValidationFailed');
                return __THROW(new FLEA_Exception_ValidationFailed($this->getLastValidation(), $row));
            }
        }

        // ��ʼ����
        $this->dbo->startTrans();

        // ���� _beforeUpdateDb() �¼�
        if (!$this->_beforeUpdateDb($row)) {
            $this->dbo->completeTrans(false);
            return false;
        }

        // ���� SQL ���
        $pkv = $row[$this->primaryKey];
        unset($row[$this->primaryKey]);
        list($pairs, $values) = $this->dbo->getPlaceholderPair($row, $this->fields);
        $row[$this->primaryKey] = $pkv;

        if (!empty($pairs)) {
            $pairs = implode(',', $pairs);
            $sql = "UPDATE {$this->qtableName} SET {$pairs} WHERE {$this->qpk} = " . $this->dbo->qstr($pkv);

            // ִ�и��²���
            if (!$this->dbo->execute($sql, $values)) {
                $this->dbo->completeTrans(false);
                return false;
            }
        }

        // ����Թ������ݵĸ���
        if ($this->autoLink && $saveLinks) {
            foreach (array_keys($this->links) as $linkKey) {
                $link =& $this->links[$linkKey];
                /* @var $link FLEA_Db_TableLink */
                // ��������Ҫ����Ĺ���
                if (!$link->enabled || !$link->linkUpdate || !isset($row[$link->mappingName]) || !is_array($row[$link->mappingName])) {
                    continue;
                }

                if (!$link->saveAssocData($row[$link->mappingName], $pkv)) {
                    $this->dbo->completeTrans(false);
                    return false;
                }
            }
        }

        $this->_updateCounterCache($row);

        // �ύ����
        $this->dbo->completeTrans();

        $this->_afterUpdateDb($row);

        return true;
    }

    /**
     * ���¼�¼�������м�¼��
     *
     * @param array $rowset
     * @param boolean $saveLinks
     *
     * @return boolean
     */
    function updateRowset(& $rowset, $saveLinks = true)
    {
        $this->dbo->startTrans();
        foreach ($rowset as $row) {
            if (!$this->update($row, $saveLinks, false)) {
                $this->dbo->completeTrans(false);
                return false;
            }
        }
        $this->dbo->completeTrans();
        return true;
    }

    /**
     * ���·��������ļ�¼���ɹ����ظ��µļ�¼������ʧ�ܷ��� false
     *
     * �ò������������κ��¼���Ҳ���ᴦ��������ݡ�
     *
     * @param mixed $conditions
     * @param array $$row
     *
     * @return int|boolean
     */
    function updateByConditions($conditions, & $row)
    {
        $whereby = $this->getWhere($conditions, false);
        $this->_setUpdatedTimeFields($row);

        list($pairs, $values) = $this->dbo->getPlaceholderPair($row, $this->fields);
        $pairs = implode(',', $pairs);
        $sql = "UPDATE {$this->qtableName} SET {$pairs} {$whereby}";
        return $this->dbo->execute($sql, $values);
    }

    /**
     * ���¼�¼��ָ���ֶΣ����ظ��µļ�¼����
     *
     * �ò������������κ��¼���Ҳ���ᴦ��������ݡ�
     *
     * @param mixed $conditions
     * @param string $field
     * @param mixed $value
     *
     * @return int
     */
    function updateField($conditions, $field, $value)
    {
        $row = array($field => $value);
        return $this->updateByConditions($conditions, $row);
    }

    /**
     * ���ӷ��������ļ�¼��ָ���ֶε�ֵ�����ظ��µļ�¼����
     *
     * �ò������������κ��¼���Ҳ���ᴦ��������ݡ�
     *
     * @param mixed $conditions
     * @param string $field
     * @param int $incr
     *
     * @return mixed
     */
    function incrField($conditions, $field, $incr = 1)
    {
        $field = $this->dbo->qfield($field, $this->fullTableName, $this->schema);
        $incr = (int)$incr;

        $row = array();
        $this->_setUpdatedTimeFields($row);
        list($pairs, $values) = $this->dbo->getPlaceholderPair($row, $this->fields);
        $pairs = implode(',', $pairs);
        if ($pairs) {
            $pairs = ', ' . $pairs;
        }

        $whereby = $this->getWhere($conditions, false);
        $sql = "UPDATE {$this->qtableName} SET {$field} = {$field} + {$incr}{$pairs} {$whereby}";
        return $this->dbo->execute($sql, $values);
    }

    /**
     * ��С���������ļ�¼��ָ���ֶε�ֵ�����ظ��µļ�¼����
     *
     * �ò������������κ��¼���Ҳ���ᴦ��������ݡ�
     *
     * @param mixed $conditions
     * @param string $field
     * @param int $decr
     *
     * @return mixed
     */
    function decrField($conditions, $field, $decr = 1)
    {
        $field = $this->dbo->qfield($field, $this->fullTableName, $this->schema);
        $decr = (int)$decr;

        $row = array();
        $this->_setUpdatedTimeFields($row);
        list($pairs, $values) = $this->dbo->getPlaceholderPair($row, $this->fields);
        $pairs = implode(',', $pairs);
        if ($pairs) {
            $pairs = ', ' . $pairs;
        }

        $whereby = $this->getWhere($conditions, false);
        $sql = "UPDATE {$this->qtableName} SET {$field} = {$field}- {$decr}{$pairs} {$whereby}";
        return $this->dbo->execute($sql, $values);
    }

    /**
     * ����һ���¼�¼�������¼�¼������ֵ
     *
     * create() ���������� _beforeCreate()��_beforeCreateDb() �� _afterCreateDb() �¼���
     *
     * @param array $row
     * @param boolean $saveLinks
     *
     * @return mixed
     */
    function create(& $row, $saveLinks = true)
    {
        if (!$this->_beforeCreate($row)) {
            return false;
        }

        // �Զ����������ֶ�
        $this->_setCreatedTimeFields($row);

        // ��������
        $mpk = strtoupper($this->primaryKey);
        $insertId = null;
        $unsetpk = true;
        if (isset($this->meta[$mpk]['autoIncrement']) && $this->meta[$mpk]['autoIncrement'])
        {
            if (isset($row[$this->primaryKey])) {
                if (empty($row[$this->primaryKey])) {
                    // ��������ֶ������������ṩ�ļ�¼������Ȼ���������ֶΣ�
                    // ��ȴ�ǿ�ֵ����ɾ�������ֵ
                    unset($row[$this->primaryKey]);
                } else {
                    $unsetpk = false;
                }
            }
        } else {
            // ��������ֶβ��������ֶΣ�����û���ṩ�����ֶ�ֵʱ�����ȡһ���µ������ֶ�ֵ
            if (!isset($row[$this->primaryKey]) || empty($row[$this->primaryKey])) {
                $insertId = $this->newInsertId();
                $row[$this->primaryKey] = $insertId;
            } else {
                // ʹ�ÿ������ύ�������ֶ�ֵ
                $insertId = $row[$this->primaryKey];
                $unsetpk = false;
            }
        }

        // �Զ���֤����
        if ($this->autoValidating && !is_null($this->verifier)) {
            if (!$this->checkRowData($row)) {
                FLEA::loadClass('FLEA_Exception_ValidationFailed');
                __THROW(new FLEA_Exception_ValidationFailed($this->getLastValidation(), $row));
                return false;
            }
        }

        // ���� _beforeCreateDb() �¼�
        $this->dbo->startTrans();

        if (!$this->_beforeCreateDb($row)) {
            if ($unsetpk) { unset($row[$this->primaryKey]); }
            $this->dbo->completeTrans(false);
            return false;
        }

        // ���� SQL ���
        list($holders, $values) = $this->dbo->getPlaceholder($row, $this->fields);
        $holders = implode(',', $holders);
        $fields = $this->dbo->qfields(array_keys($values));
        $sql = "INSERT INTO {$this->qtableName} ({$fields}) VALUES ({$holders})";

        // ��������
        if (!$this->dbo->Execute($sql, $values, true)) {
            if ($unsetpk) { unset($row[$this->primaryKey]); }
            $this->dbo->completeTrans(false);
            return false;
        }

        // ����ύ��������û�������ֶ�ֵ�����Ի�ȡ�²����¼������ֵ
        if (is_null($insertId)) {
            $insertId = $this->dbo->insertId();
            if (!$insertId) {
                if ($unsetpk) { unset($row[$this->primaryKey]); }
                $this->dbo->completeTrans(false);
                FLEA::loadClass('FLEA_Db_Exception_InvalidInsertID');
                return __THROW(new FLEA_Db_Exception_InvalidInsertID());
            }
        }

        // ����������ݱ�
        if ($this->autoLink && $saveLinks) {
            foreach (array_keys($this->links) as $linkKey) {
                $link =& $this->links[$linkKey];
                /* @var $link FLEA_Db_TableLink */
                if (!$link->enabled || !$link->linkCreate || !isset($row[$link->mappingName]) || !is_array($row[$link->mappingName])) {
                    // ����û�й������ݵĹ����Ͳ���Ҫ����Ĺ���
                    continue;
                }

                if (!$link->saveAssocData($row[$link->mappingName], $insertId)) {
                    if ($unsetpk) { unset($row[$this->primaryKey]); }
                    $this->dbo->completeTrans(false);
                    return false;
                }
            }
        }

        $row[$this->primaryKey] = $insertId;
        $this->_updateCounterCache($row);

        // �ύ����
        $this->dbo->CompleteTrans();

        $this->_afterCreateDb($row);
        if ($unsetpk) { unset($row[$this->primaryKey]); }

        return $insertId;
    }

    /**
     * ������м�¼�����ذ��������¼�¼����ֵ�����飬���ʧ���򷵻� false
     *
     * @param array $rowset
     * @param boolean $saveLinks
     *
     * @return array
     */
    function createRowset(& $rowset, $saveLinks = true)
    {
        $insertids = array();
        $this->dbo->startTrans();
        foreach ($rowset as $row) {
            $insertid = $this->create($row, $saveLinks, false);
            if (!$insertid) {
                $this->dbo->completeTrans(false);
                return false;
            }
            $insertids[] = $insertid;
        }
        $this->dbo->completeTrans();
        return $insertids;
    }

    /**
     * ɾ����¼
     *
     * remove() ���������� _beforeRemove()��_beforeRemoveDbByPkv()��_afterRemoveDbByPkv �� _afterRemoveDb() �¼���
     *
     * @param array $row
     *
     * @return boolean
     */
    function remove(& $row, $removeLink = true)
    {
        if (!$this->_beforeRemove($row)) {
            return false;
        }

        if (!isset($row[$this->primaryKey])) {
            FLEA::loadClass('FLEA_Db_Exception_MissingPrimaryKey');
            __THROW(new FLEA_Db_Exception_MissingPrimaryKey($this->primaryKey));
            return false;
        }
        $ret = $this->removeByPkv($row[$this->primaryKey], $removeLink);
        if ($ret) {
            $this->_afterRemoveDb($row);
        }
        return $ret;
    }

    /**
     * ��������ֵɾ����¼
     *
     * removeByPkv() ���� _beforeRemoveDbByPkv() �� _afterRemoveDbByPkv() �¼���
     *
     * @param mixed $pkv
     * @param boolean $removeLink
     *
     * @return boolean
     */
    function removeByPkv($pkv, $removeLink = true)
    {
        $this->dbo->startTrans();

        if (!$this->_beforeRemoveDbByPkv($pkv)) {
            $this->dbo->completeTrans(false);
            return false;
        }

        /**
         * ����ɾ�����������ݣ���ɾ����������
         */
        $qpkv = $this->dbo->qstr($pkv);

        // ����������ݱ�
        $counterCacheLinks = array();
        if ($this->autoLink && $removeLink) {
            foreach (array_keys($this->links) as $linkKey) {
                $link =& $this->links[$linkKey];
                /* @var $link FLEA_Db_TableLink */
                if (!$link->enabled) { continue; }
                switch ($link->type) {
                case MANY_TO_MANY:
                    /* @var $link FLEA_Db_ManyToManyLink */
                    if (!$link->deleteMiddleTableDataByMainForeignKey($qpkv)) {
                        $this->dbo->completeTrans(false);
                        return false;
                    }
                    break;
                case HAS_ONE:
                case HAS_MANY:
                    /**
                     * ���� HAS_ONE �� HAS_MANY ��������Ϊ�����������
                     *
                     * �� $link->linkRemove Ϊ true ʱ��ֱ��ɾ���������еĹ�������
                     * ������¹������ݵ����ֵΪ $link->linkRemoveFillValue
                     */
                    /* @var $link FLEA_Db_HasOneLink */
                    if ($link->deleteByForeignKey($qpkv) === false) {
                        $this->dbo->completeTrans(false);
                        return false;
                    }
                    break;
                case BELONGS_TO:
                    if ($link->counterCache) {
                        $counterCacheLinks[] = $link->foreignKey;
                    }
                }
            }
        }

        if (!empty($counterCacheLinks)) {
            $counterCacheLinks[] = $this->primaryKey;
            $row = $this->find(array($this->primaryKey => $pkv), null, $counterCacheLinks, false);
        }

        // ɾ����������
        $sql = "DELETE FROM {$this->qtableName} WHERE {$this->qpk} = {$qpkv}";
        if ($this->dbo->execute($sql) == false) {
            $this->dbo->completeTrans(false);
            return false;
        }

        if (!empty($counterCacheLinks)) {
            $this->_updateCounterCache($row);
        }

        // �ύ����
        $this->dbo->completeTrans();

        $this->_afterRemoveDbByPkv($pkv);

        return true;
    }

    /**
     * ɾ�����������ļ�¼
     *
     * @param mixed $conditions
     * @param boolean $removeLink
     *
     * @return boolean
     */
    function removeByConditions($conditions, $removeLink = true)
    {
        $rowset = $this->findAll($conditions, null, null, $this->primaryKey, false);
        $count = 0;
        $this->dbo->startTrans();
        foreach ($rowset as $row) {
            if (!$this->removeByPkv($row[$this->primaryKey], $removeLink)) { break; }
            $count++;
        }
        $this->dbo->completeTrans();
        $rows = $this->dbo->affectedRows();
        if ($rows > 0) { return $count; }
        return 0;
    }

    /**
     * ɾ����������������ֵ�ļ�¼���ò������ᴦ�����
     *
     * @param array $pkvs
     * @param boolean $removeLink
     *
     * @return boolean
     */
    function removeByPkvs($pkvs, $removeLink = true)
    {
        $ret = true;
        $this->dbo->startTrans();
        foreach ($pkvs as $id) {
            $ret = $this->removeByPkv($id, $removeLink);
            if ($ret === false) { break; }
        }
        $this->dbo->completeTrans();
        return $ret;
    }

    /**
     * ɾ�����м�¼
     *
     * @return boolean
     */
    function removeAll()
    {
        $sql = "DELETE FROM {$this->qtableName}";
        $ret = $this->execute($sql);
        return $ret;
    }

    /**
     * ɾ�����м�¼������������
     *
     * @return boolean
     */
    function removeAllWithLinks()
    {
        $this->dbo->startTrans();

        // ����������ݱ�
        if ($this->autoLink) {
            foreach (array_keys($this->links) as $linkKey) {
                $link =& $this->links[$linkKey];
                /* @var $link FLEA_Db_TableLink */
                switch ($link->type) {
                case MANY_TO_MANY:
                    /* @var $link FLEA_Db_ManyToManyLink */
                    $link->init();
                    $sql = "DELETE FROM {$link->qjoinTable}";
                    break;
                case HAS_ONE:
                case HAS_MANY:
                    $link->init();
                    $sql = "DELETE FROM {$link->assocTDG->qtableName}";
                    break;
                default:
                    continue;
                }
                if ($this->dbo->execute($sql) == false) {
                    $this->dbo->completeTrans(false);
                    return false;
                }
            }
        }

        $sql = "DELETE FROM {$this->qtableName}";
        if ($this->dbo->execute($sql) == false) {
            $this->dbo->completeTrans(false);
            return false;
        }

        // �ύ����
        $this->dbo->completeTrans();

        return true;
    }

    /**
     * �������л�������
     *
     * @param string|array $links
     */
    function enableLinks($links = null)
    {
        $this->autoLink = true;
        if (is_null($links)) {
            $links = array_keys($this->links);
        } elseif (!is_array($links)) {
            $links = explode(',', $links);
            $links = array_filter(array_map('trim', $links), 'strlen');
        }

        foreach ($links as $name) {
            $name = strtoupper($name);
            if (isset($this->links[$name])) {
                $this->links[$name]->enabled = true;
            }
        }
    }

    /**
     * ����ָ������
     *
     * @param string $linkName
     *
     * @return FLEA_Db_TableLink
     *
     */
    function enableLink($linkName)
    {
        $link =& $this->getLink($linkName);
        if ($link) { $link->enabled = true; }
        $this->autoLink = true;
        return $link;
    }

    /**
     * �������л�������
     *
     * @param string|array $links
     */
    function disableLinks($links = null)
    {
        if (is_null($links)) {
            $links = array_keys($this->links);
            $this->autoLink = false;
        } elseif (!is_array($links)) {
            $links = explode(',', $links);
            $links = array_filter(array_map('trim', $links), 'strlen');
        }

        foreach ($links as $name) {
            $name = strtoupper($name);
            if (isset($this->links[$name])) {
                $this->links[$name]->enabled = false;
            }
        }
    }

    /**
     * ����ָ������
     *
     * @param string $linkName
     *
     * @return FLEA_Db_TableLink
     */
    function disableLink($linkName)
    {
        $link =& $this->getLink($linkName);
        if ($link) { $link->enabled = false; }
        return $link;
    }

    /**
     * ������й���
     */
    function clearLinks()
    {
        $this->links = array();
    }

    /**
     * �����ඨ��� $hasOne��$hasMany��$belongsTo �� $manyToMany ��Ա�����ؽ����й���
     */
    function relink()
    {
        $this->clearLinks();
        $this->createLink($this->hasOne,     HAS_ONE);
        $this->createLink($this->belongsTo,  BELONGS_TO);
        $this->createLink($this->hasMany,    HAS_MANY);
        $this->createLink($this->manyToMany, MANY_TO_MANY);
    }

    /**
     * ��ȡָ�����ֵĹ���
     *
     * @param string $linkName
     *
     * @return FLEA_Db_TableLink
     */
    function & getLink($linkName)
    {
        $linkName = strtoupper($linkName);
        if (isset($this->links[$linkName])) {
            return $this->links[$linkName];
        }

        FLEA::loadClass('FLEA_Db_Exception_MissingLink');
        __THROW(new FLEA_Db_Exception_MissingLink($linkName));
        $ret = false;
        return $ret;
    }

    /**
     * ����ָ�����Ӷ�Ӧ�ı�������ڶ���
     *
     * @param string $linkName
     *
     * @return FLEA_Db_TableDataGateway
     */
    function & getLinkTable($linkName)
    {
        $link =& $this->getLink($linkName);
        $link->init();
        return $link->assocTDG;
    }

    /**
     * ���ָ�����ֵĹ����Ƿ����
     *
     * @param string $name
     *
     * @return boolean
     */
    function existsLink($name)
    {
        return isset($this->links[strtoupper($name)]);
    }

    /**
     * �������������ҷ����½����Ĺ�������
     *
     * @param array $defines
     * @param enum $type
     *
     * @return FLEA_Db_TableLink
     */
    function createLink($defines, $type)
    {
        if (!is_array($defines)) { return; }
        if (!is_array(reset($defines))) {
            $defines = array($defines);
        }

        // ������������
        foreach ($defines as $define) {
            if (!is_array($define)) { continue; }
            // �������Ӷ���ʵ��
            $link =& FLEA_Db_TableLink::createLink($define, $type, $this);
            $this->links[strtoupper($link->name)] =& $link;
        }
    }

    /**
     * ɾ��ָ���Ĺ���
     *
     * @param string $linkName
     */
    function removeLink($linkName)
    {
        $linkName = strtoupper($linkName);
        if (isset($this->links[$linkName])) {
            unset($this->links[$linkName]);
        }
    }

    /**
     * �����ݽ�����֤
     *
     * ��������Ը��Ǵ˷������Ա���и��ӵ���֤��
     *
     * @param array $row
     * @param int $skip
     *
     * @return boolean
     */
    function checkRowData(& $row, $skip = 0) {
        if (is_null($this->verifier)) { return false; }
        $this->lastValidationResult = $this->verifier->checkAll($row, $this->meta, $skip);
        return empty($this->lastValidationResult);
    }

    /**
     * �������һ��������֤�Ľ��
     *
     * @param string $info
     *
     * @return mixed
     */
    function getLastValidation($info = null) {
        if (is_null($info)) { return $this->lastValidationResult; }

        $arr = array();
        foreach ($this->lastValidationResult as $field => $check) {
            if (empty($check['rule'][$info])) {
                $arr[] = $field;
            } else {
                $arr[] = $check['rule'][$info];
            }
        }
        return $arr;
    }

    /**
     * ���ص�ǰ���ݱ����һ������ ID
     *
     * @return mixed
     */
    function newInsertId() {
        return $this->dbo->nextId($this->fullTableName . '_seq');
    }

    /**
     * ֱ��ִ��һ�� sql ���
     *
     * @param string $sql
     * @param array $inputarr
     *
     * @return mixed
     */
    function execute($sql, $inputarr = false)
    {
        return $this->dbo->execute($sql, $inputarr);
    }

    /**
     * �� SQL ����е� ? �滻Ϊ��Ӧ�Ĳ���ֵ
     *
     * @param string $sql
     * @param array $params
     *
     * @return string
     */
    function qinto($sql, $params = null)
    {
        if (!is_array($params)) {
            FLEA::loadClass('FLEA_Exception_TypeMismatch');
            return __THROW(new FLEA_Exception_TypeMismatch('$params', 'array', gettype($params)));
        }
        $arr = explode('?', $sql);
        $sql = array_shift($arr);
        foreach ($params as $value) {
            $sql .= $this->dbo->qstr($value) . array_shift($arr);
        }
        return $sql;
    }

    /**
     * ������ѯ�����Ͳ���
     *
     * ģʽ1��
     * where('user_id = ?', array($user_id))
     * where('user_id = :user_id', array('user_id' => $user_id))
     * where('user_id in (?)', array(array($id1, $id2, $id3)))
     *
     * ģʽ2��
     * where(array(
     *      'user_id' => $user_id,
     *      'level_ix' => $level_ix,
     * ))
     *
     * @param array|string $where
     * @param array $args
     *
     * @return array|string
     */
    function parseWhere($where, $args = null)
    {
        if (!is_array($args)) {
            $args = array();
        }
        if (is_array($where)) {
            return $this->_parseWhereArray($where);
        } else {
            return $this->_parseWhereString($where, $args);
        }
    }

    /**
     * ����ģʽ2�Բ�ѯ�������з���
     *
     * @param array $where
     *
     * @return array|string
     */
    function _parseWhereArray($where)
    {
        /**
         * ģʽ2��
         * where(array('user_id' => $user_id))
         * where(array('user_id' => $user_id, 'level_ix' => 1))
         * where(array('(', 'user_id' => $user_id, 'OR', 'level_ix' => $level_ix, ')'))
         * where(array('user_id' => array($id1, $id2, $id3)))
         */

        $parts = array();
        $callback = array($this->dbo, 'qstr');
        $next_op = '';

        foreach ($where as $key => $value) {
            if (is_int($key)) {
                $parts[] = $value;
                if ($value == ')') {
                    $next_op = 'AND';
                } else {
                    $next_op = '';
                }
            } else {
                if ($next_op != '') {
                    $parts[] = $next_op;
                }
                $field = $this->_parseWhereQfield(array('', $key));
                if (is_array($value)) {
                    $value = array_map($callback, $value);
                    $parts[] = $field . ' IN (' . implode(',', $value) . ')';
                } else {
                    $value = $this->dbo->qstr($value);
                    $parts[] = $field . ' = ' . $value;
                }
                $next_op = 'AND';
            }
        }

        return implode(' ', $parts);
    }

    /**
     * ����ģʽ1�Բ�ѯ�������з���
     *
     * @param string $where
     * @param array $args
     *
     * @return array|string
     */
    function _parseWhereString($where, $args = null)
    {
        /**
         * ģʽ1��
         * where('user_id = ?', array($user_id))
         * where('user_id = :user_id', array('user_id' => $user_id))
         * where('user_id in (?)', array(array($id1, $id2, $id3)))
         * where('user_id = :user_id', array('user_id' => $user_id))
         * where('user_id IN (:users_id)', array('users_id' => array(1, 2, 3)))
         */

        // ���ȴӲ�ѯ��������ȡ������ʶ����ֶ���
        if (strpos($where, '[') !== false) {
            // ��ȡ�ֶ���
            $where = preg_replace_callback('/\[([a-z0-9_\-\.]+)\]/i', array($this, '_parseWhereQfield'), $where);
        }

        return $this->qinto($where, $args);
    }

    /**
     * ���ֶ����滻Ϊת������ȫ�޶���
     *
     * @param array $matches
     *
     * @return string
     */
    function _parseWhereQfield($matches)
    {
        $p = explode('.', $matches[1]);
        switch (count($p)) {
        case 3:
            list($schema, $table, $field) = $p;
            if ($table == $this->tableName) {
                $table = $this->fullTableName;
            }
            return $this->dbo->qfield($field, $table, $schema);
        case 2:
            list($table, $field) = $p;
            if ($table == $this->tableName) {
                $table = $this->fullTableName;
            }
            return $this->dbo->qfield($field, $table);
        default:
            return $this->dbo->qfield($p[0]);
        }
    }

    /**
     * ����ת��������
     *
     * @param mixed $value
     *
     * @return string
     */
    function qstr($value)
    {
        return $this->dbo->qstr($value);
    }

    /**
     * ���һ���ֶ�������ȫ�޶���
     *
     * @param string $fieldName
     * @param string $tableName
     *
     * @return string
     */
    function qfield($fieldName, $tableName = null)
    {
        if (is_null($tableName)) {
            $tableName = $this->fullTableName;
        }
        return $this->dbo->qfield($fieldName, $tableName, $this->schema);
    }

    /**
     * ��ö���ֶ�������ȫ�޶���
     *
     * @param string|array $fieldsName
     * @param string $tableName
     * @param boolean $returnArray
     *
     * @return string
     */
    function qfields($fieldsName, $tableName = null, $returnArray = false)
    {
        if (is_null($tableName)) {
            $tableName = $this->fullTableName;
        }
        return $this->dbo->qfields($fieldsName, $tableName, $this->schema, $returnArray);
    }

    /**
     * ������ѯ���������� WHERE �Ӿ�
     *
     * @param array $conditions
     * @param boolean $queryLinks
     *
     * @return string
     */
    function getWhere($conditions, $queryLinks = true) {
        // �����ѯ����
        $where = FLEA_Db_SqlHelper::parseConditions($conditions, $this);
        $sqljoin = '';
        $distinct = '';

        do {
            if (!is_array($where)) {
                $whereby = $where != '' ? " WHERE {$where}" : '';
                break;
            }

            $arr = $where;
            list($where, $linksWhere) = $arr;
            unset($arr);

            if (!$this->autoLink || !$queryLinks) {
                $whereby = $where != '' ? " WHERE {$where}" : '';
                break;
            }

            foreach ($linksWhere as $linkid => $lws) {
                if (!isset($this->links[$linkid]) || !$this->links[$linkid]->enabled) {
                    continue;
                }

                $link =& $this->links[$linkid];
                /* @var $link FLEA_Db_TableLink */
                if (!$link->init) { $link->init(); }
                $distinct = 'DISTINCT ';

                switch ($link->type) {
                case HAS_ONE:
                case HAS_MANY:
                    /* @var $link FLEA_Db_HasOneLink */
                    $sqljoin .= "LEFT JOIN {$link->assocTDG->qtableName} ON {$link->mainTDG->qpk} = {$link->qforeignKey} ";
                    break;
                case BELONGS_TO:
                    /* @var $link FLEA_Db_BelongsToLink */
                    $sqljoin .= "LEFT JOIN {$link->assocTDG->qtableName} ON {$link->assocTDG->qpk} = {$link->qforeignKey} ";
                    break;
                case MANY_TO_MANY:
                    /* @var $link FLEA_Db_ManyToManyLink */
                    $sqljoin .= "INNER JOIN {$link->qjoinTable} ON {$link->qforeignKey} = {$this->qpk} INNER JOIN {$link->assocTDG->qtableName} ON {$link->assocTDG->qpk} = {$link->qassocForeignKey} ";
                    break;
                }

                $lw = reset($lws);
                if (isset($lw[3])) {
                    $whereby = $where != '' ? " WHERE {$where} {$lw[3]} " : ' WHERE';
                } else {
                    $whereby = $where != '' ? " WHERE {$where} AND " : ' WHERE';
                }
                foreach ($lws as $lw) {
                    list($field, $value, $op, $expr, $isCommand) = $lw;
                    if (!$isCommand) {
                        $field = $link->assocTDG->qfield($field);
                        $value = $this->dbo->qstr($value);
                        $whereby .= " {$field} {$op} {$value} {$expr}";
                    } else {
                        $whereby .= " {$value} {$expr}";
                    }
                }
                $whereby = substr($whereby, 0, - (strlen($expr) + 1));

                unset($link);
            }

            $whereby = " {$sqljoin} {$whereby}";
        } while (false);

        if ($queryLinks) {
            return array($whereby, $distinct);
        } else {
            return $whereby;
        }
    }

    /**
     * ǿ��ˢ�»�������ݱ� meta ��Ϣ
     */
    function flushMeta()
    {
        $this->_prepareMeta(true);
    }

    /**
     * ���¼�¼�� updated ���ֶ�
     *
     * @param array $row
     */
    function _setUpdatedTimeFields(& $row) {
        foreach ($this->updatedTimeFields as $af) {
            $af = strtoupper($af);
            if (!isset($this->meta[$af])) { continue; }
            switch ($this->meta[$af]['simpleType']) {
            case 'D': // ����
            case 'T': // ����ʱ��
                // �����ݿ�������ȡʱ���ʽ
                $row[$this->meta[$af]['name']] = $this->dbo->dbTimeStamp(time());
                break;
            case 'I': // Unix ʱ���
                $row[$this->meta[$af]['name']] = time();
                break;
            }
        }
    }

    /**
     * ���¼�¼�� created �� updated ���ֶ�
     *
     * @param array $row
     */
    function _setCreatedTimeFields(& $row) {
        $currentTime = time();
        $currentTimeStamp = $this->dbo->dbTimeStamp(time());
        foreach (array_merge($this->createdTimeFields, $this->updatedTimeFields) as $af) {
            $af = strtoupper($af);
            if (!isset($this->meta[$af])) { continue; }
            $afn = $this->meta[$af]['name'];
            if (!empty($row[$afn])) { continue; }

            switch ($this->meta[$af]['simpleType']) {
            case 'D': // ����
            case 'T': // ����ʱ��
                // �����ݿ�������ȡʱ���ʽ
                $row[$afn] = $currentTimeStamp;
                break;
            case 'I': // Unix ʱ���
                $row[$afn] = $currentTime;
                break;
            }
        }
    }

    /**
     * ׼����ǰ���ݱ��Ԫ����
     *
     * @param boolean $flushCache
     *
     * @return boolean
     */
    function _prepareMeta($flushCache = false) {
        $cached = FLEA::getAppInf('dbMetaCached');
        $cacheId = $this->dbo->dsn['id'] . '/' . $this->fullTableName;

        $readFromCache = ($cached != false && $flushCache == false);
        if ($readFromCache) {
            /**
             * ���Դӻ����ȡ
             */
            $meta = FLEA::getCache($cacheId, FLEA::getAppInf('dbMetaLifetime'));
            if (is_array($meta)) {
                $this->meta = $meta;
                return true;
            }
        }

        /**
         * �����ݿ��� meta
         */
        $this->meta = $this->dbo->metaColumns($this->qtableName);
        if (!is_array($this->meta) || empty($this->meta)) {
            FLEA::loadClass('FLEA_Db_Exception_MetaColumnsFailed');
            return __THROW(new FLEA_Db_Exception_MetaColumnsFailed($this->qtableName));
        }

        if ($cached) {
            return FLEA::writeCache($cacheId, $this->meta);
        } else {
            return true;
        }
    }

    /**
     * ���� create() �������������� _beforeCreate �¼�
     *
     * ���Ҫ��ֹ create() ������¼���÷���Ӧ�÷��� false�����򷵻� true��
     *
     * @param array $row
     *
     * @return boolean
     */
    function _beforeCreate(& $row)
    {
        return true;
    }

    /**
     * ���� create() �����󣬱�������ڶ����ݽ��д����������ݿ�ǰ���� _beforeCreateDb �¼�
     *
     * ���Ҫ��ֹ create() ������¼���÷���Ӧ�÷��� false�����򷵻� true��
     *
     * @param array $row
     *
     * @return boolean
     */
    function _beforeCreateDb(& $row)
    {
        return true;
    }

    /**
     * ���� create() �������ҳɹ������ݴ������ݿ������ _afterCreateDb �¼�
     *
     * @param array $row
     */
    function _afterCreateDb(& $row)
    {
    }


    /**
     * ���� update() �������������� _beforeUpdate �¼�
     *
     * ���Ҫ��ֹ update() ���¼�¼���÷���Ӧ�÷��� false�����򷵻� true��
     *
     * @param array $row
     *
     * @return boolean
     */
    function _beforeUpdate(& $row)
    {
        return true;
    }

    /**
     * ���� update() �����󣬱�������ڶ����ݽ��д����������ݿ�ǰ���� _beforeUpdateDb �¼�
     *
     * ���Ҫ��ֹ update() ���¼�¼���÷���Ӧ�÷��� false�����򷵻� true��
     *
     * @param array $row
     *
     * @return boolean
     */
    function _beforeUpdateDb(& $row)
    {
        return true;
    }

    /**
     * ���� update() �������ҳɹ������ݸ��µ����ݿ������ _afterUpdateDb �¼�
     *
     * @param array $row
     */
    function _afterUpdateDb(& $row)
    {
    }

    /**
     * ���� remove() �������������� _beforeRemove �¼�
     *
     * ���Ҫ��ֹ remove() ɾ����¼���÷���Ӧ�÷��� false�����򷵻� true��
     *
     * @param array $row
     *
     * @return boolean
     */
    function _beforeRemove(& $row)
    {
        return true;
    }

    /**
     * ���� remove() �������ҳɹ�ɾ����¼������ _afterRemoveDb �¼�
     *
     * @param array $row
     */
    function _afterRemoveDb($row)
    {
    }

    /**
     * ���� remove() �� removeByPkv() �������������� _beforeRemoveDbByPkv �¼�
     *
     * ���� remove() ����ʱ��_beforeRemoveDbByPkv �¼������� _beforeRemove �¼�֮��
     *
     * ���Ҫ��ֹ remove() �� removeByPkv() ɾ����¼��
     * �÷���Ӧ�÷��� false�����򷵻� true��
     *
     * @param mixed $pkv
     *
     * @return boolean
     */
    function _beforeRemoveDbByPkv($pkv)
    {
        return true;
    }

    /**
     * ���� remove() �� removeByPkv() �������ҳɹ�ɾ����¼������ _afterRemoveDbByPkv �¼�
     *
     * @param array $row
     */
    function _afterRemoveDbByPkv($pkv)
    {
    }

    /**
     * ���ݹ������ counterCache ѡ�����ͳ����Ϣ
     *
     * @param array $row
     */
    function _updateCounterCache(& $row)
    {
        foreach (array_keys($this->links) as $linkKey) {
            $link =& $this->links[$linkKey];
            /* @var $link FLEA_Db_TableLink */
            if ($link->type != BELONGS_TO || !$link->enabled || !$link->counterCache) { continue; }
            $link->init();
            $f = $link->assocTDG->qfield($link->counterCache);
            if (isset($row[$link->foreignKey])) {
                $fkv = $this->dbo->qstr($row[$link->foreignKey]);
            } else {
                $pkv = $this->dbo->qstr($row[$this->primaryKey]);
                $sql = "SELECT {$link->foreignKey} FROM {$this->qtableName} WHERE {$this->qpk} = {$pkv}";
                $fkv = $this->dbo->getOne($sql);
            }

            $conditions = "{$link->qforeignKey} = {$fkv}";
            if ($link->conditions) {
                if (is_array($link->conditions)) {
                    $conditions = FLEA_Db_SqlHelper::parseConditions($link->conditions, $link->assocTDG);
                    if (is_array($conditions)) {
                        $conditions = $conditions[0];
                    }
                } else {
                    $conditions =& $link->conditions;
                }
                if ($conditions) {
                    $conditions = "{$link->qforeignKey} = {$fkv} AND {$conditions}";
                }
            }

            $sql = "UPDATE {$link->assocTDG->qtableName} SET {$f} = (SELECT COUNT(*) FROM {$this->qtableName} WHERE {$conditions}) WHERE {$link->assocTDG->qpk} = {$fkv}";
            $this->dbo->execute($sql);
        }
    }
}
