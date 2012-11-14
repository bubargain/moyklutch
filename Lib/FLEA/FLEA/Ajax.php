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
 * FleaPHP �ü򵥡�����һ���Ե�ģ����ʵ�� Ajax ������
 *
 * ����ʼҳ�������������ʾ�������û��Ĳ����ᴥ��һ�� Ajax ������
 * 1��һ�� Ajax ������������������ĳ����ť�����ӡ��Լ��޸������������ݣ�������
 * ���� FleaPHP �Զ����ɵ� JavaScript ���룩��
 * 2����������������������ݻ��Ա����� URL ����������ʽ���ݵ�����˵Ŀ�����������
 * 3�������������������߱�д�� PHP ���룩ͨ�� $_POST �� $_GET ��� Ajax �����ύ�����ݣ�
 * 4���������������в����󣬷��� HTML ����Ƭ�Ρ�JSON �ַ�����XML �ĵ��������ı���
 * 5���������ĺ����������� FleaPHP �Զ����ɵ� JavaScript ���룩���������������صĽ�����µ�ҳ���ϣ�
 * �����ָ���� JavaScript ������
 *
 * Ҫ��Ӧ�ó�����ʹ�� FleaPHP �ṩ�� Ajax ֧�֣�������һЩ׼��������
 *
 * ���Ƚ� FLEA/Ajax Ŀ¼�е� jquery.js���Ѿ����ɹٷ� form ������ļ����Ƶ�Ӧ�ó�����Ա���������ʵ���Ŀ¼�У�
 * ���� scripts Ŀ¼��
 *
 * ����������Ҫʹ�� Ajax ֧��ҳ��� <head> �� </head> ��ǩ֮����ϣ�
 *
 * <script language="JavaScript" type="text/javascript" src="scripts/jquery.js"></script>
 * <?php $ajax->dumpJs(); ? >
 *
 * �������д���ȷ�� Ajax ֧����Ҫ�� JavaScript �ű������롣
 *
 * �˴��� $ajax ������ FLEA_Ajax ���һ��ʵ����ͨ�� FLEA::initAjax() ��á�
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Ajax.php 1005 2007-11-03 07:43:55Z qeeyuan $
 */

/**
 * Ajax ���ṩ�˴󲿷� Ajax ����
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Ajax
{
    /**
     * �Ѿ�ע����¼�
     *
     * @var array
     */
    var $events;

    /**
     * ���� FLEA_Ajax ֧�ֵĲ���������
     *
     * @var array
     */
    var $paramsType = array(
        'async'         => 'boolean',
        'beforeSend'    => 'function',
        'complete'      => 'function',
        'contentType'   => 'string',
        'params'        => 'pair',
        'data'          => 'object',
        'dataType'      => 'string',
        'error'         => 'function',
        'global'        => 'boolean',
        'ifModified'    => 'boolean',
        'processData'   => 'boolean',
        'success'       => 'function',
        'timeout'       => 'number',
        'type'          => 'string',
        'url'           => 'string',

        'beforeSubmit'  => 'function',
        'semantic'      => 'boolean',
        'clearForm'     => 'boolean',
        'resetForm'     => 'boolean',

        'target'        => 'object',
        'targetValue'   => 'object',
        'clearTarget'   => 'boolean',
    );

    /**
     * ���캯��
     *
     * @return FLEA_Ajax
     */
    function FLEA_Ajax()
    {
        $this->_events = array();
    }

    /**
     * ��� FleaPHP ΪӦ�ó���̬���ɵ� JavaScript �ű�
     *
     * ������û������ JavaScript �ű���ʱ���ú������Զ���������Լ�һ��������Ϣ��
     *
     * �÷���
     * ��ģ���� <?php $ajax->dumpJs(); ? > ���ɡ�
     *
     * @param boolean $return ָʾ�Ƿ񷵻� js ���������ֱ�����
     * @param boolean $wrapper ָʾ�Ƿ������װ�ű��� <script> ���
     *
     * @return string
     */
    function dumpJs($return = false, $wrapper = true)
    {
        $out = '';
        if ($wrapper) {
            $out .= "<script language=\"JavaScript\" type=\"text/javascript\">\n";
        }

        // ������ JavaScript ���Ƿ��Ѿ���ȷ���ص� JavaScript ����
        $out .= $this->returnCheckJs();

        // Ϊ�Ѿ��ڷ����ע����¼������Ҫ�� JavaScript ����
        $out .= $this->returnEventJs($this->_events);

        if ($wrapper) {
            $out .= "</script>\n";
        }

        if ($return) {
            return $out;
        } else {
            echo $out;
            return null;
        }
    }

    /**
     * Ϊָ��ҳ�����ע���¼���Ӧ��������������������¼���Ӧ����������
     *
     * $attribs ����ʹ���������ԣ�
     * - async ָʾ�������첽����ͬ����Ĭ��Ϊ�첽��������Ϊ false ʱʹ��ͬ������
     * - beforeSend ��������ǰҪִ�е� JavaScript ����
     * - complete ������ɺ��� success �� error ָ���ĺ���ִ����ɺ�Ҫִ�е� JavaScript ����
     * - contentType �������ݵ����ͣ�Ĭ��Ϊ "application/x-www-form-urlencoded"
     * - data Ҫ���͵������������ݣ�������һ�� JavaScript ����
     * - params Ҫ��ӵ� URL �����ݣ�������һ�������һ���ַ���
     * - dataType ��������Ԥ�ڵ����ͣ������� html��xml��script��json��Ĭ��Ϊ������Ӧ�� MIME �������Զ��ж�
     * - error ����������ʱҪִ�е� JavaScript ����
     * - global ָʾ��� ajax �����Ƿ���ȫ�����󡣵� global Ϊ false ʱ������������ȫ�ֵ� ajaxStart/ajaxStop �ȴ�����
     * - ifModified Ϊ true ʱ����������Ӧͷ�е� Last-Modified ͷ��Ϣ���ж� ajax �����Ƿ�ɹ�
     * - processData ָʾ�Ƿ��ύ������ת��Ϊ��ѯ�ַ���
     * - success ����ɹ�ʱҪִ�е� javaScript ����
     * - timeout ���� ajax ����ĳ�ʱʱ�䣨�룩
     * - type ��������ͣ�post �� get��Ĭ��Ϊ post
     * - url ��Ӧ����� URL ��ַ
     *
     * - beforeSubmit �������� submit �¼����� ajax �ύ��ǰ���õ� JavaScript �������ڸú����н���������֤��
     * - clearForm �������� submit �¼����� ajax �ɹ��ύ������ձ�
     * - resetForm �������� submit �¼����� ajax �ɹ��ύ�������ñ�
     *
     * - target ����Ӧ�а��������ݸ���ָ����ҳ�����
     * - targetValue ����Ӧ�а��������ݸ���ָ����ҳ������ value ����
     * - clearTarget ����������������Ŀ������ݻ� value ����
     *
     * @param string $control Ҫ�󶨵�ҳ������ ID
     * @param string $event Ҫ�󶨵��¼�
     * @param string $url �ύ Ajax �����Ŀ���ַ
     * @param array $attribs
     *
     * @return string
     */
    function registerEvent($control, $event, $url, $attribs = null)
    {
        $control2 = preg_replace('/[^a-z0-9_]+/i', '', $control);
        $functionName = "ajax_{$control2}_on{$event}";
        $this->_events[] = array($control, $event, $url, $attribs, $functionName);
        return $functionName;
    }

    /**
     * ���ؼ�� jQuery �Ƿ��Ѿ����ص� JavaScript �ű�
     *
     * @return string
     */
    function returnCheckJs()
    {
        $version = FLEA_VERSION;
        return <<<EOT
// generated by FleaPHP {$version}
if (typeof window.jQuery == "undefined") {
  alert('ERROR: jQuery JavaScript framework failed.');
}


EOT;
    }

    /**
     * ����ҳ������¼��� JavaScript ����
     *
     * @param array $eventList
     *
     * @return string
     */
    function returnEventJs(& $eventList)
    {
        $bindEvents = array();
        $out = '';
        foreach ($eventList as $event) {
            $out .= $this->_insertAjaxRequest($event, $bindEvents) . "\n";
        }
        $bindEvents = implode("\n", $bindEvents);
        return $out . "\n$(function() {\n{$bindEvents}\n});\n";
    }

    /**
     * ���� ajax ������Ҫ�� javascript �ű�
     *
     * @param array $event
     * @param array $bindEvents
     *
     * @return string
     */
    function _insertAjaxRequest($eventArr, & $bindEvents)
    {
        list($control, $event, $url, $attribs, $functionName) = $eventArr;
        $this->_formatAttribs($attribs);
        $bindEvents[] = "    $(\"{$control}\").bind(\"{$event}\", function() { return {$functionName}(); });";

        /**
         * ���� ajax ������
         */
        $beforeRequest = array();
        $call = $event == 'submit' ? "$(\"{$control}\").ajaxSubmit" : "$.ajax";

        /**
         * ���� params ����
         */
        if (isset($attribs['params'])) {
            $params = array();
            parse_str($attribs['params'], $params);
            $params = (array)$params;
            if (!empty($params)) {
                $params = encode_url_args($params, FLEA::getAppInf('urlMode'));
                switch (FLEA::getAppInf('urlMode')) {
                case URL_PATHINFO:
                case URL_REWRITE:
                    $url .= '/' . $params;
                    break;
                default:
                    if (strpos($url, '?') === false) {
                        $url .= '?';
                    } else {
                        $url .= '&';
                    }
                    $url .= $params;
                }
            }
            unset($attribs['params']);
        }
        $attribs['url'] = '"' . t2js($url) . '"';

        /**
         * Ĭ��ʹ�� post �ύ����
         */
        if (!isset($attribs['type'])) {
            $attribs['type'] = '"post"';
        }

        /**
         * Ϊ target��targetValue �� clearTarget �������ɶ�Ӧ�Ĵ������
         */
        if (isset($attribs['target']) || isset($attribs['targetValue'])) {
            $targetType = isset($attribs['target']) ? 'html' : 'val';
            $target = ($targetType == 'html') ? $attribs['target'] : $attribs['targetValue'];

            if (isset($attribs['clearTarget']) && $attribs['clearTarget']) {
                $beforeRequest[] = "    {$target}.{$targetType}(\"\");";
            }

            $success = isset($attribs['success']) ? trim($attribs['success']) : '';
            if ($success) {
                $success = preg_replace('/function.+{/i', '{', $success);
                if (substr($success, -1) != ';') { $success .= ';'; }
                $success = "            {$success}\n";
            }

            $attribs['success'] = <<<EOT
function(data) {
            {$target}.{$targetType}(data);
{$success}        }
EOT;

            unset($attribs['target']);
            unset($attribs['targetValue']);
            unset($attribs['clearTarget']);
        }

        $options = '';
        foreach ($attribs as $option => $value) {
            $options .= "        {$option}: {$value},\n";
        }
        $options = substr($options, 0, -2);

        $beforeRequest = implode("\n", $beforeRequest);
        if ($beforeRequest) {
            $beforeRequest = "\n{$beforeRequest}";
        }
        $function = <<<EOT
function {$functionName}()
{{$beforeRequest}
    {$call}({
{$options}
    });

    return false;
}

EOT;

        return $function;
    }

    /**
     * ��ʽ������
     *
     * @param array $attribs
     */
    function _formatAttribs(& $attribs)
    {
        // ��ʽ������
        foreach ($attribs as $option => $value) {
            if (!isset($this->paramsType[$option])) {
                $type = 'object';
            } else {
                $type = $this->paramsType[$option];
            }

            switch ($type) {
            case 'raw':
            case 'function':
            case 'number':
                break;
            case 'pair':
                if (is_array($value)) {
                    $value = t2js(encode_url_args($value));
                }
                break;
            case 'boolean':
                $value = $value ? 'true' : 'false';
                break;
            case 'object':
                $value = "$(\"{$value}\")";
                break;
            case 'string':
            default:
                $value = '"' . t2js($value) . '"';
            }

            $attribs[$option] = $value;
        }
    }
}
