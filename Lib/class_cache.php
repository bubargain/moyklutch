<?php

/**
 * ������
 */

class cache
{
    var $cache_dir = null; //�����ļ�Ŀ¼
    var $expiry = null; //�������ʱ��
    var $is_serialize = false; //�Ƿ���Ҫserialize

    /**
     * ���캯��
     *
     * @author  wj
     * @param  string       $cache_dir      //�����ļ�Ŀ¼
     * @param  int          $expiry         //����ʱ��,Ĭ��һ��Сʱ
     *
     * @return  void
     */
    function __construct($cache_dir, $expiry = 3600)
    {
        $this->cache($cache_dir, $expiry);
    }

    /**
     * ���캯��
     *
     * @author  wj
     * @param  string       $cache_dir      //�����ļ�Ŀ¼
     * @param  int          $expiry         //����ʱ��,Ĭ��һ��Сʱ
     *
     * @return  void
     */
    function cache($cache_dir, $expiry=3600)
    {
        $this->cache_dir = dirname(__FILE__) . DS . $cache_dir;
        $this->expiry = $expiry;
    }

    /**
     * ��ȡָ��key������
     *
     * @author  wj
     * @param  string       $key      //���ݼ�ֵ
     *
     * @return  mix
     */
    function get($key,$width,$height)
    {
        $result = false;
        $hash_key = $this->_get_hash_key($key . $width . $height);
        $file_name = $this->cache_dir . DS . $hash_key;
        if (is_file($file_name) && ($data = file_get_contents($file_name)))
        {
            $filetime = $this->get_filetime($file_name);

            if ($filetime < (time() - $this->expiry))
            {
                unlink($file_name); //�������
            }
            else
            {
                $result = $this->get_data($data);
            }
        }

        return $result;
    }

    function set($key, $width , $height, $data)
    {
		$hash_key = $this->_get_hash_key($key . $width . $height);
        $file_name = $this->cache_dir . DS . $hash_key;
        file_put_contents($file_name, $this->make_data($data));
        clearstatcache();
    }


    function make_data($data)
    {
        if($this->is_serialize)
        {
            return serialize($data);
        }
        else
        {
            return $data;
        }
    }

    function get_data($data)
    {
        if($this->is_serialize)
        {
            return unserialize($data);
        }
        else
        {
            return $data;
        }
    }

    /**
     * Ϊkeyֵ����Ψһ������
     *
     * @author  wj
     * @param  string       $key        keyֵ
     *
     * @return  string      Ψһ����
     */
    function _get_hash_key($key)
    {
        return abs(crc32($key)) . '_' .md5($key);
    }

    function get_filetime($filename)
    {
        if(file_exists($filename))
        {
            return intval(filemtime($filename));
        }
        else
        {
            return 0;
        }
    }

}
?>