<?php
header("Content-type: text/html;charset=utf-8");
//error_reporting(0);
if(file_exists("../install/install.lock"))
{
  echo "<script language=\"JavaScript\">alert(\"系统已经安装!如需要重新安装，请手动删除install/install.lock文件后再重新安装\");</script>";
  exit();
}
@set_time_limit(0);
@set_magic_quotes_runtime(0);
isset($_REQUEST['GLOBALS']) && exit('Access Error');
@$action=$_GET["action"];
switch ($action)
{
	case 'step2':
	{
		require('templates/step2.html');
		break;
	}
	case 'step3':
	{
		if(function_exists( 'mysql_connect')){
			 $mysql_support  = 'ON';
			 $mysql_ver_class ='OK';
			 $checkmysql=1;
		}else {
			 $mysql_support  = 'OFF';
			 $mysql_ver_class ='WARN';
			  $checkmysql=0;
		}
		global $errormsg;
		global $ver_class;
		if(PHP_VERSION<'4.1.0')
			{
			 $ver_class = 'WARN';
			 $errormsg['version']='php 版本过低,必须4.1及以上';
			 $checkv=0;
		   }
		else
			{
			 $ver_class = 'OK';
			 $checkv=1;
		    }
		$dir='../card';
		$handle = opendir($dir);
		$i=0;
        global $w_check;
        $w_check=array();
		while ($filename = readdir($handle))
	    {
            if($filename!="."&&$filename!="..")
			{
			  $sdir = $dir.'/'.$filename;
		      if(is_dir($sdir))
			  {
				$w_check[$i]=$sdir;
				$i=$i+1;
	          }
			}
	    }
        $dir='../img';
		$handle = opendir($dir);
		while ($filename = readdir($handle))
	    {
            if($filename!="."&&$filename!="..")
			{
			  $sdir = $dir.'/'.$filename;
		      if(is_dir($sdir))
			  {
				$w_check[$i]=$sdir;
				$i=$i+1;
	          }
			}
	    }
		 $dir='../editor';
		$handle = opendir($dir);
		while ($filename = readdir($handle))
	    {
            if($filename!="."&&$filename!="..")
			{
			  $sdir = $dir.'/'.$filename;
		      if(is_dir($sdir))
			  {
				$w_check[$i]=$sdir;
				$i=$i+1;
	          }
			}
	    }
        $w_m=array('../Uploads');
		$w_check=array_merge($w_check,$w_m);
		$class_chcek=array();
		$check_msg = array();
		$count=count($w_check);
		$checkup=1;
		for($i=0; $i<$count; $i++)
		{
            if(is_writable($w_check[$i]))
			{
				$check_msg[$i]= '通 过';
				$class_chcek[$i] = 'OK';
				$checkup=1 and $checkup;
			}
			else
			{
				$check_msg[$i]='777属性检测不通过';
				$checkup=0;
				$class_chcek[$i] = 'WARN';
				$checkup=0 and $checkup;
			}
		}
		if(extension_loaded('gd'))
		{
          $checkgd=1;
         }
		 else
          $checkgd=0;
		global $disabled;
		if($checkmysql==0||$checkv==0||$checkup==0||$checkgd==0)
		{
			$disabled = 'disabled';
		}
		ELSE
            $disabled='';
		require('templates/step3.html');
		break;
	}
	case 'step4':
	{
       require('templates/step4.html');
	   break;
	}
	case 'step5':
	{
			$dbhost=trim($_POST['db_host']);
			$dbuser=trim($_POST['db_username']);
			$dbpass=trim($_POST['db_pass']);
			$dbname=trim($_POST['db_name']);
			$dbtable=trim($_POST['db_table']);
            $weburl= trim($_POST['weburl']);
            $db_ucaddress=trim($_POST['db_ucaddress']);
            $db_ucpasswd=trim($_POST['db_ucpasswd']);
            define('UC_API','../App/uc_client/');
            if(!@include_once 'uc_client/client.php')
            {
                die('uc_client目录不存在');
	        }
            $ucinfo = uc_fopen2($db_ucaddress.'/index.php?m=app&a=ucinfo&release='.UC_CLIENT_RELEASE, 500, '', '', 1);
	        list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
            if($status != 'UC_STATUS_OK')
            {
                die("您指定的ucenter地址存在错误，无法检测到ucenter<br/><a href='index.php?action=step4'>返回上一步</a>");
            }
            if($uccharset!='utf-8' && $ucdbcharset='utf8')
            {
                die("数据连接和ucenter连接必须指定为utf8");
            }
            $postdata = "m=app&a=add&ucfounder=&ucfounderpw=".urlencode($db_ucpasswd)."&apptype=".urlencode('OTHER')."&appname=".urlencode('优惠券平台')."&appurl=".urlencode($weburl)."&appip=&appcharset=utf8&appdbcharset=utf-8&release='.UC_CLIENT_RELEASE";
            $s = uc_fopen2($db_ucaddress.'/index.php', 500, $postdata, '', 1);
            if(empty($s)) {
		        die("UCenter用户中心无法连接<br/><a href='index.php?action=step4'>返回上一步</a>");
	        } elseif($s == '-1') {
		        die("UCenter管理员帐号密码不正确<br/><a href='index.php?action=step4'>返回上一步</a>");
	        } else {
                $ucs = explode('|', $s);
		        if(empty($ucs[0]) || empty($ucs[1]))
                {
			        die('UCenter返回的数据出现问题，请参考:<br />'.$s);
                }
                $ucid = $ucs[1];
                $uc_keyword = $ucs[0];
            }
			@mysql_connect($dbhost, $dbuser, $dbpass) or die("不能连接数据库 $dbhost"."请输入正确的数据库地址、用户名和密码！<br><a href='index.php?action=step4'>返回上一步重新输入mysql地址、用户名和密码</a>");
            if(@version_compare(mysql_get_server_info(), '4.1.0', '>='))
			{
                @mysql_query("set names utf8");
                $file_name="ticket.sql"; //要导入的SQL文件名
			}
            else
			{
                $file_name="ticket.sql"; //要导入的SQL文件名
                echo "您的mysql版本过低，可能会影响你的安装,但官方建议您升级到mysql4.1.0以上";
			}
           //系统数据库开始导入
           if(@mysql_select_db($dbname))
		   {
			    $csql="select * from  ".$dbtable."user where 1=0";
			    if (mysql_query($csql))
			    {
                if (!empty($_POST['renewinstall']))
			    {
                      @mysql_select_db($dbname);
					  $result =@mysql_list_tables($dbname);
					  $trows=@mysql_num_rows($result);
                      for ($i = 0; $i <$trows; $i++)
	                  {
                              $oldtablename=explode("_", mysql_tablename($result, $i));
                              if ($oldtablename[0]."_"==trim($dbtable))
		                          mysql_query("drop table if exists `".mysql_tablename($result, $i)."`");
	                  }
                }
                else
                {
					 echo "<script language=\"JavaScript\">alert(\"你输入的数据库里面已经存在本系统的表，你可以换一个数据库名称或者输入新的数据表前缀，并且选择覆盖安装模式！\");</script>";
                     require('templates/step4.html');
                     exit();
                }
				}
		  }
          else
		  {
                $sql="CREATE DATABASE $dbname";
                $myd=mysql_query($sql);
				if (empty($myd))
			     {
			           echo "创建数据库失败！请确认你是有创建数据库权限！";
				       exit();
			    }
          }
           @mysql_select_db($dbname) or die ("打开数据库 $dbname 出现未知错误！无法正常连接数据库！请重新安装，如果不能解决问题，请与技术求助！");//打开数据库
		   $fp = @fopen($file_name, "r") or die("不能打开文件 $file_name 请检查文件是否存在，并且检查该文件夹的权限!");//打开文件
           while($mysql=GetNextSQL())
		   {
                if (!mysql_query($mysql))
				{
                     echo "执行出错：".mysql_error()."<br>";
                     echo "SQL语句为:".$mysql."<br>";
                }
           }
           fclose($fp) or die("Can't close file $file_name");//关闭文件
           $newpassword=md5($_POST["adminpass"]);
           @mysql_select_db($dbname) or die ("打开数据库 $dbname 出现未知错误！无法正常连接数据库！请重新安装，如果不能解决问题，请与技术求助！");//打开数据库
           mysql_query("update  ".$dbtable."user set  password='$newpassword' where account='admin'");
           mysql_close();
           //系统数据库导入结束
           //配置config.php文件设置开始
$contents='<?php
if (!defined(\'THINK_PATH\')) exit();
return $array=array(
		\'URL_MODEL\'=>1,                 // 如果你的环境不支持PATHINFO 请设置为3
		\'DB_TYPE\'=>\'mysql\',
		\'DB_HOST\'=>\''.$dbhost.'\',     //数据库所在IP地址
		\'DB_USER\'=>\''.$dbuser.'\',     //数据库用户
		\'DB_PWD\' =>\''.$dbpass.'\',  	 //数据库密码
		\'DB_NAME\'=>\''.$dbname.'\',     //数据库名
		\'DB_PORT\'=>\'3306\',
		\'DB_PREFIX\'=>\''.$dbtable.'\',
		\'CACHE_TYPE\'=>\'file\',
		\'MEMBER_AUTH_KEY\'=>\'userid\',
		\'APP_DEBUG\' => 0,
		\'APP_GROUP_LIST\'=>\'Home,Admin\',
		\'DEFAULT_GROUP\'=>\'Home\',

		//uc配置
		\'UC_CONNECT\'         => \'POST\',
		\'UC_IP\'              => \'localhost\',
		\'UC_CLIENT_PATH\'     => APP_NAME.\'/uc_client/\',
		\'UC_API\'             => \''.$db_ucaddress.'\',
		\'UC_CHARSET\'         => \'utf8\',
		\'UC_APPID\'           => \''.$ucid.'\',
		\'UC_KEY\'             => \''.$uc_keyword.'\',
		\'MY_UC_KEY\'          => \''.$uc_keyword.'\',
		);
?>';
           $filename = "../App/Conf/config.php";
           $cfp = fopen($filename,'w');
           fwrite($cfp,$contents);
           fclose($cfp);
           $cfp = fopen("../install/install.lock",'w');
           fwrite($cfp,"system is installed !");
           fclose($cfp);
           //配置文件结束
           //销毁自定义的全局变量
           unset($GLOBALS['disabled']);
           unset($GLOBALS['mysql_support']);
           unset($GLOBALS['mysql_ver_class']);
           unset($GLOBALS['errormsg']);
           unset($GLOBALS['ver_class']);
           unset($GLOBALS['errormsg']);
          //销毁自定义全局变量结束
		  require('templates/step5.html');
		  break;
	}
	default:
	{
		require("templates/step1.html");
	}
}
//从sql文件中逐条取SQL
function GetNextSQL()
{
    global $fp;
    $sql="";
    while ($line = @fgets($fp, 40960))
    {
       $line = trim($line);
       if (empty($line)) continue;
       //以下三句在高版本php中不需要
       $line = str_replace("\\\\","\\",$line);
       $line = str_replace("\'","'",$line);
       $line = str_replace("\\r\\n",chr(13).chr(10),$line);
       $line = stripcslashes($line);
       if (strlen($line)>1)
       {
           if ($line[0]=="-" && $line[1]=="-")
          {
            continue;
          }
       }
       $sql.=$line.chr(13).chr(10);
       if (strlen($line)>0)
	   {
           if ($line[strlen($line)-1]==";")
	       {
            break;
           }
       }
    }
	global $dbtable;
	$sql=str_replace("hy_",$dbtable,$sql);
    return $sql;
}
?>