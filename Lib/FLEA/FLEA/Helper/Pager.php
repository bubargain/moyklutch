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
 * ���� FLEA_Helper_Pager ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Pager.php 1016 2007-11-21 13:53:59Z qeeyuan $
 */

/**
 * FLEA_Helper_Pager ���ṩ���ݲ�ѯ��ҳ����
 *
 * FLEA_Helper_Pager ʹ�úܼ򵥣�ֻ��Ҫ����ʱ���� FLEA_Db_TableDataGateway ʵ���Լ���ѯ�������ɡ�
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Helper_Pager
{
    /**
     * ��� $this->source ��һ�� FLEA_Db_TableDataGateway ���������
     * $this->source->findAll() ����ȡ��¼����
     *
     * ����ͨ�� $this->dbo->selectLimit() ����ȡ��¼����
     *
     * @var FLEA_Db_TableDataGateway|string
     */
    var $source;

    /**
     * ���ݿ���ʶ��󣬵� $this->source ����Ϊ SQL ���ʱ���������
     * $this->setDBO() ���ò�ѯʱҪʹ�õ����ݿ���ʶ���
     *
     * @var SDBO
     */
    var $dbo;

    /**
     * ��ѯ����
     *
     * @var mixed
     */
    var $_conditions;

    /**
     * ����
     *
     * @var string
     */
    var $_sortby;

    /**
     * ����ʵ��ҳ��ʱ�Ļ���
     *
     * @var int
     */
    var $_basePageIndex = 0;

    /**
     * ÿҳ��¼��
     *
     * @var int
     */
    var $pageSize = -1;

    /**
     * ���ݱ��з��ϲ�ѯ�����ļ�¼����
     *
     * @var int
     */
    var $totalCount = -1;

    /**
     * ���ݱ��з��ϲ�ѯ�����ļ�¼����
     *
     * @var int
     */
    var $count = -1;

    /**
     * ���������ļ�¼ҳ��
     *
     * @var int
     */
    var $pageCount = -1;

    /**
     * ��һҳ���������� 0 ��ʼ
     *
     * @var int
     */
    var $firstPage = -1;

    /**
     * ��һҳ��ҳ��
     *
     * @var int
     */
    var $firstPageNumber = -1;

    /**
     * ���һҳ���������� 0 ��ʼ
     *
     * @var int
     */
    var $lastPage = -1;

    /**
     * ���һҳ��ҳ��
     *
     * @var int
     */
    var $lastPageNumber = -1;

    /**
     * ��һҳ������
     *
     * @var int
     */
    var $prevPage = -1;

    /**
     * ��һҳ��ҳ��
     *
     * @var int
     */
    var $prevPageNumber = -1;

    /**
     * ��һҳ������
     *
     * @var int
     */
    var $nextPage = -1;

    /**
     * ��һҳ��ҳ��
     *
     * @var int
     */
    var $nextPageNumber = -1;

    /**
     * ��ǰҳ������
     *
     * @var int
     */
    var $currentPage = -1;

    /**
     * ���캯�����ṩ�ĵ�ǰҳ���������� setBasePageIndex() �����¼���ҳ��
     *
     * @var int
     */
    var $_currentPage = -1;

    /**
     * ��ǰҳ��ҳ��
     *
     * @var int
     */
    var $currentPageNumber = -1;

    /**
     * ���캯��
     *
     * ��� $source ������һ�� TableDataGateway ������ FLEA_Helper_Pager �����
     * �� TDG ����� findCount() �� findAll() ��ȷ����¼���������ؼ�¼����
     *
     * ��� $source ������һ���ַ�������ٶ�Ϊ SQL ��䡣��ʱ��FLEA_Helper_Pager
     * �����Զ����ü�������ҳ����������ͨ�� setCount() ������������Ϊ��ҳ����
     * �����ļ�¼������
     *
     * ͬʱ����� $source ����Ϊһ���ַ���������Ҫ $conditions �� $sortby ������
     * ���ҿ���ͨ�� setDBO() ��������Ҫʹ�õ����ݿ���ʶ��󡣷��� FLEA_Helper_Pager
     * �����Ի�ȡһ��Ĭ�ϵ����ݿ���ʶ���
     *
     * @param TableDataGateway|string $source
     * @param int $currentPage
     * @param int $pageSize
     * @param mixed $conditions
     * @param string $sortby
     * @param int $basePageIndex
     *
     * @return FLEA_Helper_Pager
     */
    function FLEA_Helper_Pager(& $source, $currentPage, $pageSize = 20, $conditions = null, $sortby = null, $basePageIndex = 0)
    {
        $this->_basePageIndex = $basePageIndex;
        $this->_currentPage = $this->currentPage = $currentPage;
        $this->pageSize = $pageSize;

        if (is_object($source)) {
            $this->source =& $source;
            $this->_conditions = $conditions;
            $this->_sortby = $sortby;
            $this->totalCount = $this->count = (int)$this->source->findCount($conditions);
            $this->computingPage();
        } elseif (!empty($source)) {
            $this->source = $source;
            $sql = "SELECT COUNT(*) FROM ( $source ) as _count_table";
            $this->dbo =& FLEA::getDBO();
            $this->totalCount = $this->count = (int)$this->dbo->getOne($sql);
            $this->computingPage();
        }
    }

    /**
     * ���÷�ҳ������һҳ�Ļ���
     *
     * @param int $index
     */
    function setBasePageIndex($index)
    {
        $this->_basePageIndex = $index;
        $this->currentPage = $this->_currentPage;
        $this->computingPage();
    }

    /**
     * ���õ�ǰҳ�룬�Ա��� findAll() �������ҳ������
     *
     * @param int $page
     */
    function setPage($page)
    {
        $this->_currentPage = $page;
        $this->currentPage = $page;
        $this->computingPage();
    }

    /**
     * ���ü�¼�������Ӷ����·�ҳ����
     *
     * @param int $count
     */
    function setCount($count)
    {
        $this->count = $count;
        $this->computingPage();
    }

    /**
     * �������ݿ���ʶ���
     *
     * @param SDBO $dbo
     */
    function setDBO(& $dbo)
    {
        $this->dbo =& $dbo;
    }

    /**
     * ���ص�ǰҳ��Ӧ�ļ�¼��
     *
     * @param string $fields
     * @param boolean $queryLinks
     *
     * @return array
     */
    function & findAll($fields = '*', $queryLinks = true)
    {
        if ($this->count == -1) {
            $this->count = 20;
        }

        $offset = ($this->currentPage - $this->_basePageIndex) * $this->pageSize;
        if (is_object($this->source)) {
            $limit = array($this->pageSize, $offset);
            $rowset = $this->source->findAll($this->_conditions, $this->_sortby, $limit, $fields, $queryLinks);
        } else {
            if (is_null($this->dbo)) {
                $this->dbo =& FLEA::getDBO(false);
            }
            $rs = $this->dbo->selectLimit($this->source, $this->pageSize, $offset);
            $rowset = $this->dbo->getAll($rs);
        }
        return $rowset;
    }

    /**
     * ���ط�ҳ��Ϣ��������ģ����ʹ��
     *
     * @param boolean $returnPageNumbers
     *
     * @return array
     */
    function getPagerData($returnPageNumbers = true)
    {
        $data = array(
            'pageSize' => $this->pageSize,
            'totalCount' => $this->totalCount,
            'count' => $this->count,
            'pageCount' => $this->pageCount,
            'firstPage' => $this->firstPage,
            'firstPageNumber' => $this->firstPageNumber,
            'lastPage' => $this->lastPage,
            'lastPageNumber' => $this->lastPageNumber,
            'prevPage' => $this->prevPage,
            'prevPageNumber' => $this->prevPageNumber,
            'nextPage' => $this->nextPage,
            'nextPageNumber' => $this->nextPageNumber,
            'currentPage' => $this->currentPage,
            'currentPageNumber' => $this->currentPageNumber,
        );

        if ($returnPageNumbers) {
            $data['pagesNumber'] = array();
            for ($i = 0; $i < $this->pageCount; $i++) {
                $data['pagesNumber'][$i] = $i + 1;
            }
        }

        return $data;
    }

    /**
     * ����ָ����Χ�ڵ�ҳ��������ҳ��
     *
     * @param int $currentPage
     * @param int $navbarLen
     *
     * @return array
     */
    function getNavbarIndexs($currentPage = 0, $navbarLen = 8)
    {
        $mid = intval($navbarLen / 2);
        if ($currentPage < $this->firstPage) {
            $currentPage = $this->firstPage;
        }
        if ($currentPage > $this->lastPage) {
            $currentPage = $this->lastPage;
        }

        $begin = $currentPage - $mid;
        if ($begin < $this->firstPage) { $begin = $this->firstPage; }
        $end = $begin + $navbarLen - 1;
        if ($end >= $this->lastPage) {
            $end = $this->lastPage;
            $begin = $end - $navbarLen + 1;
            if ($begin < $this->firstPage) { $begin = $this->firstPage; }
        }

        $data = array();
        for ($i = $begin; $i <= $end; $i++) {
            $data[] = array('index' => $i, 'number' => ($i + 1 - $this->_basePageIndex));
        }
        return $data;
    }

    /**
     * ����һ��ҳ��ѡ����ת�ؼ�
     *
     * @param string $caption
     * @param string $jsfunc
     */
    function renderPageJumper($caption = '%u', $jsfunc = 'fnOnPageChanged')
    {
        $out = "<select name=\"PageJumper\" onchange=\"{$jsfunc}(this.value);\">\n";
        for ($i = $this->firstPage; $i <= $this->lastPage; $i++) {
            $out .= "<option value=\"{$i}\"";
            if ($i == $this->currentPage) {
                $out .= " selected";
            }
            $out .=">";
            $out .= sprintf($caption, $i + 1 - $this->_basePageIndex);
            $out .= "</option>\n";
        }
        $out .= "</select>\n";
        return $out;
    }

    /**
     * ��������ҳ����
     */
    function computingPage()
    {
        $this->pageCount = ceil($this->count / $this->pageSize);
        $this->firstPage = $this->_basePageIndex;
        $this->lastPage = $this->pageCount + $this->_basePageIndex - 1;
        if ($this->lastPage < $this->firstPage) { $this->lastPage = $this->firstPage; }

        if ($this->lastPage < $this->_basePageIndex) {
            $this->lastPage = $this->_basePageIndex;
        }

        if ($this->currentPage >= $this->pageCount + $this->_basePageIndex) {
            $this->currentPage = $this->lastPage;
        }

        if ($this->currentPage < $this->_basePageIndex) {
            $this->currentPage = $this->firstPage;
        }

        if ($this->currentPage < $this->lastPage - 1) {
            $this->nextPage = $this->currentPage + 1;
        } else {
            $this->nextPage = $this->lastPage;
        }

        if ($this->currentPage > $this->_basePageIndex) {
            $this->prevPage = $this->currentPage - 1;
        } else {
            $this->prevPage = $this->_basePageIndex;
        }

        $this->firstPageNumber = $this->firstPage + 1 - $this->_basePageIndex;
        $this->lastPageNumber = $this->lastPage + 1 - $this->_basePageIndex;
        $this->nextPageNumber = $this->nextPage + 1 - $this->_basePageIndex;
        $this->prevPageNumber = $this->prevPage + 1 - $this->_basePageIndex;
        $this->currentPageNumber = $this->currentPage + 1 - $this->_basePageIndex;
    }
}
