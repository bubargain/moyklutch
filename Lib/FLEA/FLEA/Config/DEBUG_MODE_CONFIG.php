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
 * �����ԡ�ģʽ�� FleaPHP Ӧ�ó����Ĭ������
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Config
 * @version $Id: DEBUG_MODE_CONFIG.php 1037 2008-04-19 21:19:55Z qeeyuan $
 */

return array(
    // {{{ ��������

    /**
     * Ӧ�ò����Ĭ��ʱ�����ã������ PHP 5.1 �Ժ�汾
     *
     * ���������Ϊ null�����Է���������Ϊ׼�����������û������ʱ����������Ϊ Asia/ShangHai��
     */
    'defaultTimezone'           => null,

    /**
     * ָʾʹ�� MVC ģʽʱ����Ҫ������һ���ļ�
     */
    'MVCPackageFilename'        => FLEA_DIR . '/Controller/Action.php',

    /**
     * ָʾ�������� url ��������Ĭ�Ͽ�������
     *
     * ����������ֻ����a-z��ĸ��0-9���֣��Լ���_���»��ߡ�
     */
    'controllerAccessor'        => 'controller',
    'defaultController'         => 'Default',

    /**
     * ָʾ ���������� url ��������Ĭ�� ����������
     */
    'actionAccessor'            => 'action',
    'defaultAction'             => 'index',

    /**
     * url �����Ĵ���ģʽ�������Ǳ�׼��PATHINFO��URL ��д��ģʽ
     */
    'urlMode'                   => URL_STANDARD,

    /**
     * ָʾĬ�ϵ�Ӧ�ó�������ļ���
     */
    'urlBootstrap'              => 'index.php',

    /**
     * ָʾ������ url ʱ���Ƿ�����ʹ��Ӧ�ó�������ļ��������� URL_STANDARD ģʽ
     *
     * ���������Ϊ false�������ɵ� url ���ƣ�
     *
     * http://www.example.com/?controller=xxx&action=yyy
     */
    'urlAlwaysUseBootstrap'     => true,

    /**
     * ָʾ������ url ʱ���Ƿ�����ʹ�������Ŀ��������Ͷ�����
     *
     * ���������Ϊ false����Ĭ�ϵĿ������Ͷ�������������� url ��
     */
    'urlAlwaysUseAccessor'      => true,

    /**
     * ָʾ�� PATHINFO �� REWRITE ģʽ�£���ʲô������Ϊ URL �������Ͳ���ֵ�������ַ�
     */
    'urlParameterPairStyle'     => '/',

    /**
     * �Ƿ� url �����а����Ŀ��������ֺͶ�������ǿ��תΪСд
     */
    'urlLowerChar'              => false,

    /**
     * ���� url() ����ʱ��Ҫ���õ� callback ����
     */
    'urlCallback'               => null,

    /**
     * ������������ǰ׺
     */
    'controllerClassPrefix'     => 'Controller_',

    /**
     * �������У�������������ǰ׺�ͺ�׺
     * ʹ��ǰ׺�ͺ�׺���Խ�һ�������������е�˽�з���
     */
    'actionMethodPrefix'        => 'action',
    'actionMethodSuffix'        => '',

    /**
     * Ӧ�ó���Ҫʹ�õ� url ������
     */
    'dispatcher'                => 'FLEA_Dispatcher_Simple',

    /**
     * ����������ʧ�ܣ��������������������������ڣ���Ҫ���õĴ������
     */
    'dispatcherFailedCallback'  => null,

    /**
     * FleaPHP �ڲ��� cache ϵ�к���ʹ�õĻ���Ŀ¼
     * Ӧ�ó���������ø�ѡ�����ʹ�� cache ���ܡ�
     */
    'internalCacheDir'          => null,

    /**
     * ָʾҪ�Զ�������ļ�
     */
    'autoLoad'                  => array(),

    /**
     * ָʾ�Ƿ����� session �ṩ����
     */
    'sessionProvider'           => null,

    /**
     * ָʾ�Ƿ��Զ����� session ֧��
     */
    'autoSessionStart'          => true,

    /**
     * ָʾʹ����Щ�������� HTTP ������й���
     */
    'requestFilters'            => array(),

    // }}}

    // {{{ ���ݿ����

    /**
     * ���ݿ����ã����������飬Ҳ������ DSN �ַ���
     */
    'dbDSN'                     => null,

    /**
     * ָʾ���� TableDataGateway ����ʱ���Ƿ��Զ����ӵ����ݿ�
     */
    'dbTDGAutoInit'             => true,

    /**
     * ���ݱ��ȫ��ǰ׺
     */
    'dbTablePrefix'             => '',

    /**
     * ���ݱ�Ԫ���ݻ���ʱ�䣨�룩����� dbMetaCached ����Ϊ false���򲻻Ỻ�����ݱ�Ԫ����
     * ͨ������ʱ��������Ϊ 10���Ա��޸����ݿ��ṹ��Ӧ�ó����ܹ�����ˢ��Ԫ����
     */
    'dbMetaLifetime'            => 10,

    /**
     * ָʾ�Ƿ񻺴����ݱ��Ԫ����
     */
    'dbMetaCached'              => true,

    // }}}

    // {{{ View ���

    /**
     * Ҫʹ�õ�ģ�����棬'PHP' ��ʾʹ�� PHP ���Ա�����ģ������
     */
    'view'                      => 'PHP',

    /**
     * ģ������Ҫʹ�õ�������Ϣ
     */
    'viewConfig'                => null,

    /**
     * ��ʼ�� Ajax ʱҪ�������
     */
    'ajaxClassName'             => 'FLEA_Ajax',

    /**
     * ��ʼ�� WebControls ʱҪ�������
     */
    'webControlsClassName'      => 'FLEA_WebControls',

    /**
     * WebControls ��չ�ؼ��ı���Ŀ¼
     */
    'webControlsExtendsDir'     => null,

    // }}}

    // {{{ I18N

    /**
     * ָʾ FleaPHP Ӧ�ó����ڲ��������ݺ��������Ҫʹ�õı���
     */
    'responseCharset'           => 'gb2312',

    /**
     * �� FleaPHP �������ݿ�ʱ����ʲô���봫������
     */
    'databaseCharset'           => 'gb2312',

    /**
     * �Ƿ��Զ���� Content-Type: text/html; charset=responseCharset
     */
    'autoResponseHeader'        => true,

    /**
     * ָʾ�Ƿ����ö�����֧��
     */
    'multiLanguageSupport'      => false,

    /**
     * ָ���ṩ������֧�ֵ��ṩ����
     */
    'languageSupportProvider'   => 'FLEA_Language',

    /**
     * ָʾ�����ļ��ı���λ��
     */
    'languageFilesDir'          => null,

    /**
     * ָʾĬ������
     */
    'defaultLanguage'           => 'chinese-gb2312',

    /**
     * �Զ�����������ļ�
     */
    'autoLoadLanguage'          => null,

    // }}}

    // {{{ FLEA_Dispatcher_Auth �� RBAC ���

    /**
     * ������Ҫʹ�õ���֤�����ṩ����
     */
    'dispatcherAuthProvider'    => 'FLEA_Rbac',

    /**
     * ָʾ RBAC ���Ҫʹ�õ�Ĭ�� ACT �ļ�
     */
    'defaultControllerACTFile'  => '',

    /**
     * ָʾ RBAC ����Ƿ���û���ҵ��������� ACT �ļ�ʱ��
     * �Ƿ��Ĭ�� ACT �ļ��в�ѯ�������� ACT
     */
    'autoQueryDefaultACTFile'   => false,

    /**
     * ��������û���ṩ ACT �ļ�ʱ����ʾ������Ϣ
     */
    'controllerACTLoadWarning'  => true,

    /**
     * ָʾ��û��Ϊ�������ṩ ACT ʱ��Ҫʹ�õ�Ĭ�� ACT
     */
    'defaultControllerACT'      => null,

    /**
     * ȫ�� ACT����û��ָ�� ACT ʱ���ȫ�� ACT �в���ָ���������� ACT
     */
    'globalACT'                 => null,

    /**
     * �û�û��Ȩ�޷��ʿ����������������ʱ��Ҫ���õĴ������
     */
    'dispatcherAuthFailedCallback' => null,

    /**
     * ָʾ RBAC �����ʲô������ session �б����û�����
     *
     * �����һ��������ͬʱ���ж��Ӧ�ó���
     * �����Ϊÿһ��Ӧ�ó���ʹ���Լ���һ�޶��ļ���
     */
    'RBACSessionKey'            => 'RBAC_USERDATA',

    // }}}

    // {{{ ��־�ʹ�����
    /**
     * ָʾ�Ƿ�������־����
     */
    'logEnabled'                => true,

    /**
     * ָʾ��־����ĳ���
     */
    'logProvider'               => 'FLEA_Log',

    /**
     * ָʾ��ʲôĿ¼������־�ļ�
     *
     * ���û��ָ����־���Ŀ¼���򱣴浽�ڲ�����Ŀ¼��
     */
    'logFileDir'                => null,

    /**
     * ָʾ��ʲô�ļ���������־
     */
    'logFilename'               => 'access.log',

    /**
     * ָʾ����־�ļ��������� KB ʱ���Զ������µ���־�ļ�����λ�� KB������С�� 512KB
     */
    'logFileMaxSize'            => 4096,

    /**
     * ָʾ��Щ����Ĵ���Ҫ���浽��־��
     */
    'logErrorLevel'             => 'notice, debug, warning, error, exception, log',

    /**
     * �쳣��������
     */
    'exceptionHandler'          => '__FLEA_EXCEPTION_HANDLER',

    /**
     * ָʾ�Ƿ���ʾ������Ϣ
     */
    'displayErrors'             => true,

    /**
     * ָʾ�Ƿ���ʾ�ѺõĴ�����Ϣ
     */
    'friendlyErrorsMessage'     => true,

    /**
     * ָʾ�Ƿ��ڴ�����Ϣ����ʾ����λ�õ�Դ����
     */
    'displaySource'             => true,

    // }}}

    // {{{ ���ֿ�

    /**
     * ������֤��������
     */
    'helper.verifier'           => 'FLEA_Helper_Verifier',

    /**
     * �����㷨����
     */
    'helper.encryption'         => 'FLEA_Helper_Encryption',

    /**
     * ���鴦������
     */
    'helper.array'              => 'FLEA_Helper_Array',

    /**
     * �ļ�ϵͳ��������
     */
    'helper.file'               => 'FLEA_Helper_FileSystem',

    /**
     * ͼ��������
     */
    'helper.image'              => 'FLEA_Helper_Image',

    /**
     * ��ҳ����
     */
    'helper.pager'              => 'FLEA_Helper_Pager',

    /**
     * �ļ��ϴ�����
     */
    'helper.uploader'           => 'FLEA_Helper_FileUploader',

    /**
     * YAML ����
     */
    'helper.yaml'               => 'FLEA_Helper_Yaml',

    /**
     * HTML ����
     */
    'helper.html'               => 'FLEA_Helper_Html',

// }}}

    // {{{ FLEA_Session_Db ����

    /**
     * ָʾʹ��Ӧ�ó�������һ�� DSN ���� session ���ݱ�
     */
    'sessionDbDSN'              => 'dbDSN',

    /**
     * ָʾ���� session �����ݱ�����
     */
    'sessionDbTableName'        => 'sessions',

    /**
     * ָʾ���� session id ���ֶ���
     */
    'sessionDbFieldId'          => 'sess_id',

    /**
     * ָʾ���� session ���ݵ��ֶ���
     */
    'sessionDbFieldData'        => 'sess_data',

    /**
     * ָʾ���� session ���ʱ����ֶ���
     */
    'sessionDbFieldActivity'   => 'activity',

    /**
     * ָʾ session ����Ч��
     *
     * 0 ��ʾ�� PHP ���л���������������ֵΪ�������һ�λʱ��������ʧЧ
     */
    'sessionDbLifeTime'         => 1440,

    // }}}
);
