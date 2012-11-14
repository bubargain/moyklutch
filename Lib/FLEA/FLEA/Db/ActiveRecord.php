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
 * ���� FLEA_Db_ActiveRecord ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ�(www.qeeyuan.com)
 * @package Core
 * @version $Id: ActiveRecord.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Db_ActiveRecord ��ʵ���� ActiveRecord ģʽ
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ�(www.qeeyuan.com)
 * @package Core
 * @version $Id: ActiveRecord.php 972 2007-10-09 20:56:54Z qeeyuan $
 */
class FLEA_Db_ActiveRecord
{
    /**
     * ����ö���Ҫ�ۺϵ���������
     *
     * @var array
     */
    var $_aggregation = array();

    /**
     * ����������ݿ������ TableDataGateway �̳���
     *
     * @var FLEA_Db_TableDataGateway
     */
    var $_table;

    /**
     * �ö��������������
     *
     * @var string
     */
    var $_idname;

    /**
     * ָʾ�ö����Ƿ��Ѿ���ʼ��
     *
     * @var boolean
     */
    var $init = false;

    /**
     * �ֶκͶ�������֮���ӳ���ϵ
     *
     * @var array
     */
    var $_mapping = false;

    /**
     * �̳�����븲�Ǵ˾�̬����
     *
     * @static
     *
     * @return array
     */
    function define()
    {
    }

    /**
     * ���캯��
     *
     * ���� $conditions ������ѯ���������ļ�¼��Ϊ�������ԡ�
     *
     * @param mixed $conditions
     *
     * @return FLEA_Db_ActiveRecord
     */
    function FLEA_Db_ActiveRecord($conditions = null)
    {
        $this->init();
        $this->load($conditions);
    }

    /**
     * ��ʼ��
     *
     * @param array $options
     */
    function init()
    {
        if ($this->init) { return; }
        $this->init = true;

        $myclass = get_class($this);
        $options = call_user_func(array($myclass, 'define'));
        $tableClass = $options['tableClass'];

        $objid = "{$myclass}_tdg";
        if (FLEA::isRegistered($objid)) {
            $this->_table =& FLEA::registry($objid);
        } else {
            FLEA::loadClass($tableClass);
            $this->_table =& new $tableClass(array('skipCreateLinks' => true));
            FLEA::register($this->_table, $objid);
        }

        if (!empty($options['propertiesMapping'])) {
            $this->_mapping = array(
                'p2f' => $options['propertiesMapping'],
                'f2p' => array_flip($options['propertiesMapping']),
            );
            $this->_idname = $this->_mapping['f2p'][$this->_table->primaryKey];
        } else {
            $this->_mapping = array('p2f' => array(), 'f2p' => array());
            foreach ($this->_table->meta as $field) {
                $this->_mapping['p2f'][$field['name']] = $field['name'];
                $this->_mapping['f2p'][$field['name']] = $field['name'];
            }
            $this->_idname = $this->_table->primaryKey;
        }

        if (!isset($options['aggregation']) || !is_array($options['aggregation'])) {
            $options['aggregation'] = array();
        }
        foreach ($options['aggregation'] as $offset => $define) {
            if (!isset($define['mappingName'])) {
                $define['mappingName'] = substr(strtolower($define['tableClass']), 0, 1) . substr($define['tableClass'], 1);
            }
            if ($define['mappingType'] == HAS_MANY || $define['mappingType'] == MANY_TO_MANY) {
                $this->{$define['mappingName']} = array();
            } else {
                $this->{$define['mappingName']} = null;
            }

            /**
             * ��þۺ϶���Ķ�����Ϣ
             */
            FLEA::loadClass($define['class']);
            $options = call_user_func(array($define['class'], 'define'));

            $link = array(
                'tableClass' => $options['tableClass'],
                'mappingName' => $define['mappingName'],
                'foreignKey' => isset($define['foreignKey']) ? $define['foreignKey'] : null,
            );

            if ($define['mappingType'] == MANY_TO_MANY) {
                $link['joinTable'] = isset($define['joinTable']) ? $define['joinTable'] : null;
                $link['assocForeignKey'] = isset($define['assocForeignKey']) ? $define['assocForeignKey'] : null;
            }

            $this->_table->createLink($link, $define['mappingType']);
            $define['link'] =& $this->_table->getLink($link['mappingName']);
            $this->_aggregation[$offset] = $define;
        }
    }

    /**
     * �����ݿ��������������һ������
     *
     * @param mixed $conditions
     */
    function load($conditions)
    {
        $row = $this->_table->find($conditions);
        if (is_array($row)) { $this->attach($row); }
    }

    /**
     * ����������ݿ�
     */
    function save()
    {
        $row =& $this->toArray();
        $this->_table->save($row);
    }

    /**
     * �����ݿ�ɾ������
     */
    function delete()
    {
        $this->_table->removeByPkv($this->getId());
    }

    /**
     * ���ö�������ֵ
     *
     * @param mixed $id
     */
    function setId($id)
    {
        $this->{$this->_idname} = $id;
    }

    /**
     * ���ض�������ֵ
     *
     * @return mixed
     */
    function getId()
    {
        return $this->{$this->_idname};
    }

    /**
     * ����������ת��Ϊ����
     *
     * @return array
     */
    function toArray()
    {
        $arr = array();
        foreach ($this->_mapping['p2f'] as $prop => $field) {
            $arr[$field] = $this->{$prop};
        }
        return $arr;
    }

    /**
     * ����¼��ֵ�󶨵�����
     *
     * @param array $row
     */
    function attach(& $row)
    {
        foreach ($this->_mapping['f2p'] as $field => $prop) {
            if (isset($row[$field])) {
                $this->{$prop} = $row[$field];
            }
        }

        foreach ($this->_aggregation as $define) {
            $mn = $define['link']->mappingName;
            if (!isset($row[$mn])) { continue; }
            if ($define['link']->oneToOne) {
                $this->{$mn} =& new $define['class']($row[$mn]);
            } else {
                $this->{$mn}[] =& new $define['class']($row[$mn]);
            }
        }
    }

}
