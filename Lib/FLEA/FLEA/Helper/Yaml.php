<?php
/**
 * Spyc -- A Simple PHP YAML Class
 * @version 0.2.3 -- 2006-02-04
 * @author Chris Wanstrath <chris@ozmm.org>
 * @link http://spyc.sourceforge.net/
 * @copyright Copyright 2005-2006 Chris Wanstrath
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Core
 */

/**
 * ���� YAML �ļ������ط������
 *
 * load_yaml() ���Զ�ʹ�û��棬ֻ�е� YAML �ļ����ı�󣬻���Ż���¡�
 *
 * ���� YAML ����ϸ��Ϣ,��ο� www.yaml.org ��
 *
 * �÷���
 * <code>
 * $data = load_yaml('myData.yaml');
 * </code>
 *
 * ע�⣺Ϊ�˰�ȫ�������Ҫʹ�� YAML �洢������Ϣ���������롣
 * ���߽� YAML �ļ�����չ������Ϊ .yaml.php��������ÿһ�� YAML �ļ���ͷ��ӡ�exit()����
 * ���磺
 * <code>
 * # <?php exit(); ?>
 *
 * invoice: 34843
 * date   : 2001-01-23
 * bill-to: &id001
 * ......
 * </code>
 *
 * ��������ȷ�����������ֱ�ӷ��ʸ� .yaml.php �ļ���Ҳ�޷��������ݡ�
 *
 * @param string $filename
 * @param boolean $cacheEnabled �Ƿ񻺴��������
 * @param array $replace
 *
 * @return array
 */
function load_yaml($filename, $cacheEnabled = true, $replace = null)
{
    static $objects = array();

    if (!file_exists($filename)) {
        FLEA::loadClass('FLEA_Exception_ExpectedFile');
        return __THROW(new FLEA_Exception_ExpectedFile($filename));
    }
    
    if ($cacheEnabled) {
        $arr = FLEA::getCache('yaml-' . $filename, filemtime($filename), false);
        if ($arr) { return $arr; }
    }

    if (!isset($objects[0])) {
        require_once FLEA_3RD_DIR . '/Spyc/spyc.php';
        $objects[0] =& new Spyc();
    }
    
    $arr = $objects[0]->load($filename, $replace);
    if ($cacheEnabled) {
        FLEA::writeCache('yaml-' . $filename, $arr);
    }
    return $arr;
}
