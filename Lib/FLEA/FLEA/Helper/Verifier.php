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
 * ���� FLEA_Helper_Verifier ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Verifier.php 1018 2007-12-04 23:41:47Z qeeyuan $
 */

/**
 * FLEA_Helper_Verifier �������һϵ����֤�����ָ�������ݽ�����֤
 *
 * ��֤�����ɶ��������ɣ�ÿ������������֤һ���ֶΡ�
 *
 * ÿ��������԰������л������ԣ�
 * name:            �ֶ���
 * type:            �ֶ�����
 * simpleType:      ���ֶ�����
 * maxLength:       ��󳤶�
 * notNull:         �Ƿ������� NULL ֵ
 * notEmpty:        �Ƿ�����Ϊ���ַ���
 * binary:          �Ƿ��Ƕ���������
 * unsigned:        �Ƿ����޷�����ֵ
 * hasDefault:      �Ƿ���Ĭ��ֵ
 * defaultValue:    Ĭ��ֵ
 *
 * ��� notNull Ϊ true���� hasDefault Ϊ false�����ʾ���ֶα��������ݡ�
 *
 * simpleType ���Կ���������ֵ��
 *  C        - ����С�ڵ��� 250 ���ַ���
 *  X        - ���ȴ��� 250 ���ַ���
 *  B        - ����������
 *  N        - ��ֵ���߸�����
 *  D        - ����
 *  T        - TimeStamp
 *  L        - �߼�����ֵ
 *  I        - ����
 *  R        - �Զ������������
 *
 * �������Ժ����� SDBO::metaColumns() ����ȡ�õ��ֶ���Ϣ��ȫһ�¡�
 * ��˿���ֱ�ӽ� metaColumns() �ķ��ؽ����Ϊ��֤����
 *
 * Ϊ�˻�ø�ǿ����֤������������ʹ��������չ���ԣ�
 *
 * complexType:     �����ֶ�����
 * min:             ��Сֵ����������ֵ���ֶΣ�
 * max:             ���ֵ����������ֵ���ֶΣ�
 * minLength:       ��С���ȣ��������ַ��ͺ��ı����ֶΣ�
 * maxLength:       ��󳤶ȣ��������ַ��к��ı����ֶΣ�
 *
 * ���� complexType ���ԣ�����������ֵ��
 * NUMBER     - ��ֵ����������������
 * INT        - ����
 * ASCII      - ASCII �ַ��������б���С�ڵ��� 127 ���ַ���
 * EMAIL      - Email ��ַ
 * DATE       - ���ڣ����� GNU Date Input Formats������ yyyy/mm/dd��yyyy-mm-dd��
 * TIME       - ʱ�䣨���� GNU Date Input Formats������ hh:mm:ss��
 * IPv4       - IPv4 ��ַ����ʽΪ a.b.c.h��
 * OCTAL      - �˽�����ֵ
 * BINARY     - ��������ֵ
 * HEX        - ʮ��������ֵ
 * DOMAIN     - Internet ����
 * ANY        - ��������
 * STRING     - �ַ�������ͬ���������ͣ�
 * ALPHANUM   - ���ֺ����֣�26����ĸ��0��9��
 * ALPHA      - ���֣�26����ĸ��
 * ALPHANUMX  - 26����ĸ��10�������Լ� _ ����
 * ALPHAX     - 26����ĸ�Լ� - ����
 *
 * �ڿͻ��˿���ͨ�� verifier.js �ṩ����֤��������ݽ�����֤��
 * ���ͻ�����֤���ṩ���޵���֤������������
 *
 * notNull, hasDefault, min, max, minLength, maxLength
 *
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Helper_Verifier
{
    /**
     * ��������ֶΣ�������֤���
     *
     * @param array $data
     * @param array $rules
     * @param mixed $skip
     *
     * @return array
     */
    function checkAll(& $data, & $rules, $skip = 0)
    {
        $result = array();
        foreach ($rules as $rule) {
            $name = $rule['name'];
            if ($skip === 1 || $skip === true || $skip === 'empty') {
                if (!isset($data[$name]) || empty($data[$name])) { continue; }
            } elseif ($skip === 2 || $skip === 'noset') {
                if (!isset($data[$name])) { continue; }
            }

            do {
                // ��� notNull Ϊ true���� hasDefault Ϊ false�����ʾ���ֶα���������
                if (isset($rule['notNull'])) {
                    if ($rule['notNull']) {
                        if (isset($rule['hasDefault']) && $rule['hasDefault']) { break; }
                        if (isset($rule['simpleType']) && $rule['simpleType'] == 'R') {
                            break;
                        }
                        if (!isset($data[$name]) || is_null($data[$name])) {
                            $result[$name] = array('check' => 'notNull', 'rule' => $rule);
                            break;
                        }
                    } else {
                        if (!isset($data[$name]) || is_null($data[$name]) || $data[$name] === '') { break; }
                    }
                }
                if (isset($rule['notEmpty'])) {
                    if ($rule['notEmpty']) {
                        if (isset($rule['hasDefault']) && $rule['hasDefault']) { break; }
                        if (isset($rule['simpleType']) && $rule['simpleType'] == 'R') {
                            break;
                        }
                        if (!isset($data[$name]) || $data[$name] == '') {
                            $result[$name] = array('check' => 'notEmpty', 'rule' => $rule);
                            break;
                        }
                    } else {
                        if (!isset($data[$name]) || $data[$name] == '') {
                            break;
                        }
                    }
                }

                $ret = $this->check($data[$name], $rule);
                if ($ret !== true) {
                    $result[$name] = array('check' => $ret, 'rule' => $rule);
                }
            } while (false);
        }
        return $result;
    }

    /**
     * ��ָ��������ֵ֤����֤ͨ������ ture�����򷵻�û��ͨ������֤������
     *
     * @param mixed $value
     * @param array $rule
     *
     * @return boolean
     */
    function check($value, & $rule)
    {
        // ����ʹ�� simpleType ��ֵ֤����� simpleType ���Դ��ڣ�
        $checkLength = false;
        $checkMinMax = false;
        $ret = 'simpleType';
        if (isset($rule['simpleType'])) {
            switch ($rule['simpleType']) {
            case 'C': // ����С�ڵ��� 250 ���ַ���
                if (strlen($value) > 250) { return $ret; }
                $checkLength = true;
                break;
            case 'N': // ��ֵ���߸�����
                if (!is_numeric($value)) { return $ret; }
                $checkMinMax = true;
                break;
            case 'D': // ����
                $test = @strtotime($value);
                if ($test === false || $test === -1) { return $ret; }
                break;
            case 'I': // ����
                if (!is_numeric($value)) { return $ret; }
                if (intval($value) != $value) { return $ret; }
                $checkMinMax = true;
                break;
            case 'X': // ���ȴ��� 250 ���ַ���
            case 'B': // ����������
                $checkLength = true;
                break;
            case 'T': // TimeStamp
            case 'L': // �߼�����ֵ
                break;
            case 'R': // �Զ������������
                $checkMinMax = true;
                break;
            default:
            }
        } else {
            $checkLength = true;
            $checkMinMax = true;
        }

        // ����ʹ�� complexType ��ֵ֤����� complexType ���Դ��ڣ�
        $ret = 'complexType';
        if (isset($rule['complexType'])) {
            $func = 'is' . $rule['complexType'];
            if (!method_exists($this, $func)) {
                FLEA::loadClass('FLEA_Exception_InvalidArguments');
                __THROW(new FLEA_Exception_InvalidArguments('$rule[\'complexType\']',
                        $rule['complexType']));
                return null;
            }
            if (!$this->{$func}($value)) { return $ret; }
        }

        // min/max/minLength/maxLength ��֤
        if ($checkMinMax) {
            $ret = 'min';
            if (isset($rule['min']) && $value < $rule['min']) { return $ret; }
            $ret = 'max';
            if (isset($rule['max']) && $value > $rule['max']) { return $ret; }
        }
        $ret = 'length';
        if ($checkLength) {
            $ret = 'minLength';
            if (isset($rule['minLength']) && $rule['minLength'] > 0 &&
                strlen($value) < $rule['minLength']) {
                return $ret;
            }
            $ret = 'maxLength';
            if (isset($rule['maxLength']) && $rule['maxLength'] > 0 &&
                strlen($value) > $rule['maxLength']) {
                return $ret;
            }
        }
        $ret = null;

        return true;
    }

    /**
     * ����
     */
    function isNUMBER($value)
    {
        return is_numeric($value);
    }

    /**
     * ����
     */
    function isINT($value)
    {
        return strlen(intval($value)) == strlen($value) && is_numeric($value);
    }

    /**
     * ASCII �ַ��������б���С�ڵ��� 127 ���ַ���
     */
    function isASCII($value)
    {
        $ar = array();
        $count = preg_match_all('/[\x20-\x7f]/', $value, $ar);
        return $count == strlen($value);
    }

    /**
     * Email ��ַ
     */
    function isEMAIL($value)
    {
        return preg_match('/^[A-Za-z0-9]+([._\-\+]*[A-Za-z0-9]+)*@([A-Za-z0-9]+[-A-Za-z0-9]*[A-Za-z0-9]+\.)+[A-Za-z0-9]+$/', $value) != 0;
    }

    /**
     * ���ڣ����� GNU Date Input Formats������ yyyy/mm/dd��yyyy-mm-dd��
     */
    function isDATE($value)
    {
        $test = @strtotime($value);
        return $test !== -1 && $test !== false;
    }

    /**
     * ʱ�䣨���� GNU Date Input Formats������ hh:mm:ss��
     */
    function isTIME($value)
    {
        $test = strtotime($value);
        return $test !== -1 && $test !== false;
    }

    /**
     * IPv4 ��ַ����ʽΪ a.b.c.h��
     */
    function isIPv4($value)
    {
        $test = ip2long($value);
        return $test !== -1 && $test !== false;
    }

    /**
     * �˽�����ֵ
     */
    function isOCTAL($value)
    {
        return preg_match('/0[0-7]+/', $value) != 0;
    }

    /**
     * ��������ֵ
     */
    function isBINARY($value)
    {
        return preg_match('/[01]+/', $value) != 0;
    }

    /**
     * ʮ��������ֵ
     */
    function isHEX($value)
    {
        return preg_match('/[0-9a-f]+/i', $value) != 0;
    }

    /**
     * Internet ����
     */
    function isDOMAIN($value)
    {
        return preg_match('/[a-z0-9\.]+/i', $value) != 0;
    }

    /**
     * ��������
     */
    function isANY()
    {
        return true;
    }

    /**
     * �ַ�������ͬ���������ͣ�
     */
    function isSTRING()
    {
        return true;
    }

    /**
     * ���ֺ����֣�26����ĸ��0��9��
     */
    function isALPHANUM($value)
    {
        return ctype_alnum($value);
    }

    /**
     * ���֣�26����ĸ��
     */
    function isALPHA($value)
    {
        return ctype_alpha($value);
    }

    /**
     * 26����ĸ��10������
     */
    function isALPHANUMX($value)
    {
        return preg_match('/[^a-z0-9_]/i', $value) == 0;
    }

    /**
     * 26����ĸ�� - ����
     */
    function isALPHAX($value)
    {
        return preg_match('/[^a-z\-]/i', $value) == 0;
    }
}
