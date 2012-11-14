<?php

/**
 * 缓存类
 */

class cache
{
    var $cache_dir = null; //缓存文件目录
    var $expiry = null; //缓存过期时间
    var $is_serialize = false; //是否需要serialize

    /**
     * 构造函数
     *
     * @author  wj
     * @param  string       $cache_dir      //缓存文件目录
     * @param  int          $expiry         //缓存时间,默认一个小时
     *
     * @return  void
     */
    function __construct($cache_dir, $expiry = 3600)
    {
        $this->cache($cache_dir, $expiry);
    }

    /**
     * 构造函数
     *
     * @author  wj
     * @param  string       $cache_dir      //缓存文件目录
     * @param  int          $expiry         //缓存时间,默认一个小时
     *
     * @return  void
     */
    function cache($cache_dir, $expiry=3600)
    {
        $this->cache_dir = dirname(__FILE__) . DS . $cache_dir;
        $this->expiry = $expiry;
    }

    /**
     * 获取指定key的数据
     *
     * @author  wj
     * @param  string       $key      //数据键值
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
                unlink($file_name); //清除缓存
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
     * 为key值生成唯一的索引
     *
     * @author  wj
     * @param  string       $key        key值
     *
     * @return  string      唯一索引
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