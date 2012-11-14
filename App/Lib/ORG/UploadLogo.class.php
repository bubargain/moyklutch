<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * 文件上传类
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Net
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class UploadLogo extends Think
{//类定义开始

    // 上传文件的最大值
    public $maxSize = -1;
    //  留空不作后缀检查
    public $allowExts = array('bmp','jpg','png','gif');
    //  是否要经过图片的审核
    public $ImageCheck      = true;
    //　图片的宽度限制
    public $PictureWidth    = 1063;
    //　图片的高度限制
    public $PictureHeight   = 709;
    //　图片的最后显示格式
    public $saveext         = 'jpg';
    //　控制允许缩略图
    public $thumb           = true;
    //　定义缩略图的宽度
    public $thumbWidth      = 150;
    //  定义缩略图的高度
    public $thumbHeight     = 100;
    //　图片的上传路径
    public $savePath        = './img/shop/';
    //  缩略图的存放路径
    public $thumbPath       = './img/thumshop/';
    //　缩略图的前缀
    public $thumbPrefix     = '';
    // 上传文件命名规则
    // 例如可以是 time uniqid com_create_guid 等
    // 必须是一个无需任何参数的函数名 可以使用自定义函数
    public $saveRule = 'uniqid';
    // 错误信息
    private $error = '';

    // 上传成功的文件信息
    private $uploadFileInfo ;

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct($config='')
    {
        $config = is_array($config)?$config:array();
        foreach ($config as $key => $val)
        {
            if(isset($this->$key))
            {
                $this->$key= $val;
            }
        }
        if(!is_array($this->allowExts))
        {
            $this->allowExts = explode(',',strtolower($this->allowExts));
        }
    }
    /**
     +----------------------------------------------------------
     * 上传所有文件
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $savePath  上传文件保存路径
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function upload($saveid="",$savePath ='')
    {
        //初始化返回数组
        $result = array('status'=>0,'info'=>'图片上传成功','data'=>'');
        //如果不指定保存文件名，则由系统默认
        if(empty($savePath)) $savePath = $this->savePath;
        // 检查上传目录
        if(!is_dir($savePath)) {
            // 检查目录是否编码后的
            if(is_dir(base64_decode($savePath))) {
                $savePath	=	base64_decode($savePath);
            }else{
                // 尝试创建目录
                if(!mkdir($savePath)){
                    $this->error  =  '上传目录'.$savePath.'不存在';
                    return false;
                }
            }
        }else {
            if(!is_writeable($savePath)) {
                $this->error  =  '上传目录'.$savePath.'不可写';
                return false;
            }
        }
        $fileInfo = array();
        $isUpload   = false;

        // 获取上传的文件信息
        // 对$_FILES数组信息处理
        $files	 =	 $this->dealFiles($_FILES);
        foreach($files as $key => $file) {
            //过滤无效的上传
            if($file['name']=='') continue;
            //检查是否是图片文件
            if(substr($file['type'],0,5)!=='image'){
                $result['info'] = '不是图片文件';
                return $result;
            }
            //检查扩展名是否合法
            if(!$this->checkExts($file['name']))
            {
                $result['info'] = '只允许上传'.implode(',',$this->allowExts)."扩展名的文件";
                return $result;
            }
            //检查图片的大小规格是否满足
           if(!$this->checkImgSize($file['tmp_name']))
           {
                    $result['info'] = '图片大小不符合要求，系统要求图片像素大小为:'.$this->PictureWidth."*".$this->PictureHeight;
                    return $result;
           }
            //获取文件名字
           $rule = empty($this->saveRule)?'uniqid':$this->saveRule;
           $savename = $this->savePath."tmp_".$rule().".".$this->getImageinfo($file['tmp_name'],'type');
            if(!move_uploaded_file($file['tmp_name'], auto_charset($savename,'utf-8','gbk'))) {
                $result['info'] = '文件上传保存错误！';
                return $result;
            }
            $source = $this->thumb($savename,$saveid);
            if(!$source)
            {
                $result['info'] = '文件缩略出错';
                return $result;
            }
            $this->thumb($source,$saveid,1);
            $savepath = (substr($this->savePath,0,1)=='.')?substr($this->savePath,1):$this->savePath;
            $fileinfo[] =array('savepath'=>$savepath,'filename'=>$source);
            unlink($savename);//删除临时的缓存文件
        }
        $result['status'] = 1;
        $result['data'] = $fileinfo;
        return $result;
    }
    //检查文件扩展名是否符合规格
    public function checkExts($filename)
    {
        if(empty($this->allowExts)) return true;
        $info = explode('.',$filename);
        return  in_array(strtolower($info[count(info)]),$this->allowExts);
    }
    //检查文件尺寸是否符合规格
    public function checkImgSize($file)
    {
        //判断是否允许图片检测
        if(!$this->ImageCheck) return true;
        $image = $this->getImageinfo($file);
        if($image['width']<$this->PictureWidth || $image['height']<$this->PictureHeight)
        {
            return false;
        }
        return true;
    }
    //获取图片信息
    public function getImageinfo($img,$field='*')
    {
       $imageInfo = getimagesize($img);
        if( $imageInfo!== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
            $imageSize = filesize($img);
            $info = array(
                "width"=>$imageInfo[0],
                "height"=>$imageInfo[1],
                "type"=>$imageType,
                "size"=>$imageSize,
                "mime"=>$imageInfo['mime']
            );
            return ($field=='*')?$info:$info[$field];
        }else {
            return false;
        }
    }

    //生成缩略图
    public function thumb($image,$saveid,$thumb_type=0)
    {
        $picimage = ($thumb_type==0)?$image:$this->savePath.$image;
        $info = $this->getImageinfo($picimage);//获取图息的基本信息
        if($info === false) return false;
        $ext  = empty($this->saveext)?'jpg':$this->saveext;
        $imageFun = 'image'.($ext=='jpg'?'jpeg':$ext);
        if($thumb_type!=0)
        {
            $filename = $this->thumbPrefix.$image;
            $savepath = $this->thumbPath;
            $scale = min($this->thumbWidth/$info['width'], $this->thumbHeight/$info['height']); //计算缩放比例因子
        }else{
           if($saveid!='')
            {
               $filename = $saveid.".".$ext;
            }else{
               $rule = empty($this->saveRule)?'uniqid':$this->saveRule;
               $filename = $rule().".".$ext;
            }
            $savepath = $this->savePath;
            $scale = max($this->PictureWidth/$info['width'], $this->PictureHeight/$info['height']); //计算缩放比例因子
        }

        $width  = (int)($info['width']*$scale);
        $height = (int)($info['height']*$scale);
        // 载入原图
        $createFun = 'ImageCreateFrom'.($info['type']=='jpg'?'jpeg':$info['type']);
        $srcImg     = $createFun($picimage);
        //创建缩略图
        $thumbImg = imagecreatetruecolor($width, $height);
        imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $info['width'],$info['height']);
        if($thumb_type==0 && ($width>$this->PictureWidth || $height > $this->PictureHeight))
        {
            $newthumb = imagecreatetruecolor($this->PictureWidth, $this->PictureHeight);
            $cropStartX = ( $width / 2) - ( $this->PictureWidth/2 );
            $cropStartY = ( $height/ 2) - ( $this->PictureHeight/2 );
            imagecopyresampled($newthumb,$thumbImg,0,0,$cropStartX,$cropStartY,$this->PictureWidth, $this->PictureHeight,$this->PictureWidth, $this->PictureHeight);
            $imageFun($newthumb,$savepath.$filename);
            imagedestroy($newthumb);
        }else{
            $imageFun($thumbImg,$savepath.$filename);
        }
        imagedestroy($thumbImg);
        imagedestroy($srcImg);
        return $filename;
    }

   /**
     +----------------------------------------------------------
     * 转换上传文件数组变量为正确的方式
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param array $files  上传的文件变量
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    private function dealFiles($files) {
       $fileArray = array();
       foreach ($files as $file){
           if(is_array($file['name'])) {
               $keys = array_keys($file);
               $count	 =	 count($file['name']);
               for ($i=0; $i<$count; $i++) {
                   foreach ($keys as $key)
                       $fileArray[$i][$key] = $file[$key][$i];
               }
           }else{
               $fileArray	=	$files;
           }
           break;
       }
       return $fileArray;
    }
}//类定义结束
?>