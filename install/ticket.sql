-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 08 月 12 日 08:37
-- 服务器版本: 5.1.36
-- PHP 版本: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `ticket`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_addscore`
--

CREATE TABLE IF NOT EXISTS `think_addscore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `money` double(10,2) NOT NULL COMMENT '加分数',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0，网站支付，1：手动操作',
  `remark` varchar(250) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户积分改动表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_addscore`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_area`
--

CREATE TABLE IF NOT EXISTS `think_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '英文标识',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级id',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '状态',
  `remark` varchar(250) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商圈' AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `think_area`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_card`
--

CREATE TABLE IF NOT EXISTS `think_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'cardID',
  `card_id` varchar(20) NOT NULL COMMENT '会员卡内码',
  `password` varchar(20) NOT NULL COMMENT '密码',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `type_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员卡类型',
  `userif` tinyint(4) NOT NULL DEFAULT '0',
  `use_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用次数',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态0：禁用，1：正常',
  `remark` varchar(250) NOT NULL COMMENT '备注',
  `use_time` int(11) NOT NULL COMMENT '最后刷卡时间',
  `limit_count` int(11) NOT NULL COMMENT '打印数量配置计数',
  `limit_time` int(11) NOT NULL COMMENT '时间配置范围内的第一次',
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员卡' AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `think_card`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_cards`
--

CREATE TABLE IF NOT EXISTS `think_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '卡名',
  `price` double(10,2) NOT NULL COMMENT '价格',
  `path` varchar(250) NOT NULL COMMENT '图片地址',
  `content` text NOT NULL COMMENT '介绍',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `think_cards`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_cate`
--

CREATE TABLE IF NOT EXISTS `think_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '英文标识',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级id',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '状态',
  `remark` varchar(250) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商圈' AUTO_INCREMENT=26 ;

--
-- 转存表中的数据 `think_cate`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_collect`
--

CREATE TABLE IF NOT EXISTS `think_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `ticket_id` int(11) NOT NULL COMMENT '优惠券id',
  `start_time` int(11) NOT NULL COMMENT '优惠券开始时间',
  `close_time` int(11) NOT NULL COMMENT '优惠券结束时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `action_type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '收藏',
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `think_collect`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_comment`
--

CREATE TABLE IF NOT EXISTS `think_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '所属评论',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `ticket_id` int(11) NOT NULL COMMENT '优惠券ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` varchar(20) DEFAULT NULL COMMENT '更新时间',
  `content` text NOT NULL COMMENT '评论内容',
  `module` varchar(30) DEFAULT NULL,
  `review` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核状态',
  `star` tinyint(4) NOT NULL DEFAULT '0' COMMENT '评分',
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='优惠券评论表' AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `think_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_config`
--

CREATE TABLE IF NOT EXISTS `think_config` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `zf_username` varchar(100) NOT NULL COMMENT '支付宝商户ID',
  `zf_password` varchar(100) DEFAULT NULL COMMENT '支付宝商户密码',
  `stmp_server` varchar(250) NOT NULL COMMENT 'SMTP主机',
  `stmp_port` varchar(11) NOT NULL COMMENT 'SMTP端口',
  `stmp_username` varchar(100) NOT NULL COMMENT 'SMTP用户名',
  `stmp_password` varchar(100) NOT NULL COMMENT 'SMTP密码',
  `stmp_send` varchar(200) NOT NULL COMMENT '发信地址',
  `stmp_back` varchar(200) NOT NULL COMMENT '回信地址',
  `stmp_rate` tinyint(4) NOT NULL DEFAULT '3' COMMENT '发信频率',
  `stmp_fromname` varchar(100) NOT NULL COMMENT '发信人',
  `stmp_subject` varchar(200) NOT NULL COMMENT '标题',
  `stmp_body` text NOT NULL COMMENT '内容',
  `error_email` varchar(250) NOT NULL COMMENT '系统错误发送邮箱',
  `error_rate` tinyint(4) NOT NULL DEFAULT '5' COMMENT '错误提示频率（天）',
  `money_rate` double(10,2) NOT NULL COMMENT '金币兑换规则',
  `comment_if` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否开启评论，1：开，0：关',
  `card_time` int(11) NOT NULL COMMENT '卡密码保留期',
  `score_rate` int(10) unsigned NOT NULL DEFAULT '0',
  `score_give` int(10) unsigned NOT NULL DEFAULT '0',
  `score_card` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_if` double(10,0) unsigned NOT NULL DEFAULT '0',
  `info_if` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '完善信息加积分',
  `email_if` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '邮箱验证加积分',
  `mobile_if` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '手机验证加积分',
  `sms_username` varchar(250) NOT NULL COMMENT '短信接口帐号',
  `sms_password` varchar(250) NOT NULL COMMENT '短信密码',
  `webname` varchar(100) NOT NULL,
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `word_filter` varchar(200) NOT NULL COMMENT '过滤的字符',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统配置' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `think_config`
--
INSERT INTO `think_config` (`id`, `zf_username`, `zf_password`, `stmp_server`, `stmp_port`, `stmp_username`, `stmp_password`, `stmp_send`, `stmp_back`, `stmp_rate`, `stmp_fromname`, `stmp_subject`, `stmp_body`, `error_email`, `error_rate`, `money_rate`, `comment_if`, `card_time`, `score_rate`, `score_give`, `score_card`, `reg_if`, `info_if`, `email_if`, `mobile_if`, `sms_username`, `sms_password`, `webname`, `update_time`, `word_filter`) VALUES
(1, '', '', '', '', '', '', '', '', 0, '', '', '', '', 0, 0.00, 0, 0, 0, 0, 0, 10, 10.00, 0.00, 0.00, '', '', '中国人特纳', 1313564032, '中国,日本');

-- --------------------------------------------------------

--
-- 表的结构 `think_config_card`
--

CREATE TABLE IF NOT EXISTS `think_config_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) NOT NULL COMMENT '网络传输校验码',
  `password_expire` int(11) NOT NULL COMMENT '密码过期时间（天）',
  `card_mode` int(11) NOT NULL COMMENT '卡模式',
  `sitename` varchar(250) NOT NULL COMMENT '网站名',
  `point_name` varchar(250) NOT NULL COMMENT '积分名',
  `maxSize` int(11) NOT NULL DEFAULT '2097152' COMMENT '最大上传大小（B）',
  `gif_height` int(11) NOT NULL DEFAULT '800' COMMENT '生成gif文件的高度',
  `limit_time` int(11) NOT NULL COMMENT '限制时间范围',
  `limit_count` int(11) NOT NULL COMMENT '限制打印数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `think_config_card`
--

INSERT INTO `think_config_card` (`id`, `key`, `password_expire`, `card_mode`, `sitename`, `point_name`, `maxSize`, `gif_height`, `limit_time`, `limit_count`) VALUES
(1, 'VSoftXinYue NetWork', 3, 1, '乐卡仕', '金币', 2097152, 800, 7, 5);

-- --------------------------------------------------------

--
-- 表的结构 `think_error`
--

CREATE TABLE IF NOT EXISTS `think_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `source` varchar(20) NOT NULL DEFAULT '' COMMENT '错误来源 web 打印机编号',
  `mac_id` varchar(8) DEFAULT NULL COMMENT '机器编号',
  `error_content` varchar(80) NOT NULL DEFAULT '' COMMENT '错误内容',
  `level` int(1) NOT NULL DEFAULT '0' COMMENT '错误级别 1 严重 2 一般',
  `log_time` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:未处理，-1：已处理',
  `flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '标识是否为连接错误',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='错误日志' AUTO_INCREMENT=495 ;

--
-- 转存表中的数据 `think_error`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_exhibition`
--

CREATE TABLE IF NOT EXISTS `think_exhibition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL COMMENT 'location ID',
  `bt_position` varchar(20) NOT NULL COMMENT '按钮位置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_exhibition`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_huodong`
--

CREATE TABLE IF NOT EXISTS `think_huodong` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '标题',
  `typeid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:试用派发，0:精彩活动',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `close_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `content` text NOT NULL COMMENT '详细内容',
  `path` varchar(250) NOT NULL COMMENT '图片地址',
  `thumpath` varchar(250) NOT NULL COMMENT '缩略图地址',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `phone` varchar(100) NOT NULL COMMENT '联系电话',
  `tags` varchar(250) NOT NULL COMMENT '标签',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='活动' AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `think_huodong`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_info`
--

CREATE TABLE IF NOT EXISTS `think_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `think_info`
--

INSERT INTO `think_info` (`id`, `title`, `content`, `create_time`, `update_time`, `status`) VALUES
(1, '公司介绍', '公司介绍内容', 1298451935, 1298451935, 1),
(2, '新手引导', '<div align="center"><img src="/imgs/setp.jpg" /></div>', 1298451957, 1310026940, 1),
(3, '商家加入', '商家加入注意事项，及联系方式', 1298452097, 1298452097, 1),
(4, '联系我们', '联系我们', 1298958809, 1298958809, 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_location`
--

CREATE TABLE IF NOT EXISTS `think_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '地点ID',
  `name` varchar(30) NOT NULL COMMENT '地点名称',
  `area` mediumint(9) NOT NULL COMMENT '归属地区',
  `address` varchar(250) NOT NULL COMMENT '详细地址',
  `contact` varchar(30) NOT NULL COMMENT '地区租赁联系人',
  `phone` varchar(20) NOT NULL COMMENT '固定电话',
  `mobile` varchar(20) NOT NULL COMMENT '移动电话',
  `start_time` int(11) unsigned NOT NULL COMMENT '租赁起始时间',
  `close_time` int(11) unsigned NOT NULL COMMENT '租赁结束时间',
  `macno` varchar(20) NOT NULL COMMENT '机器码',
  `open` int(11) NOT NULL DEFAULT '0' COMMENT '开机时间',
  `close` int(11) NOT NULL DEFAULT '0' COMMENT '关机时间',
  `money` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '每个展位的月租金',
  `remark` text NOT NULL COMMENT '备注信息',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态1：正常，0：预订',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  `link_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后连接时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='机器放置地点管理' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `think_location`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_log`
--

CREATE TABLE IF NOT EXISTS `think_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `action_name` varchar(40) NOT NULL COMMENT 'action',
  `operate` varchar(30) NOT NULL COMMENT '操作',
  `target` varchar(100) NOT NULL COMMENT '操作对象',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=140 ;

--
-- 转存表中的数据 `think_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_menu`
--

CREATE TABLE IF NOT EXISTS `think_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL COMMENT '菜单标题',
  `module` varchar(30) NOT NULL COMMENT '模块名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='菜单管理' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `think_menu`
--

INSERT INTO `think_menu` (`id`, `title`, `module`) VALUES
(1, '用户管理', 'user'),
(2, '商家管理', 'trade'),
(3, '优惠券管理', 'ticket'),
(4, '打印机管理', 'machine');

-- --------------------------------------------------------

--
-- 表的结构 `think_newclass`
--

CREATE TABLE IF NOT EXISTS `think_newclass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 NOT NULL COMMENT '分类名称',
  `pid` int(11) NOT NULL COMMENT '父类ID',
  `path` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '排序标志',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='新闻分类表' AUTO_INCREMENT=40 ;

--
-- 转存表中的数据 `think_newclass`
--

INSERT INTO `think_newclass` (`id`, `name`, `pid`, `path`) VALUES
(39, '国内新闻', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `think_news`
--

CREATE TABLE IF NOT EXISTS `think_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '标题',
  `stitle` varchar(250) NOT NULL COMMENT '副标题',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `tags` varchar(250) NOT NULL COMMENT '标签',
  `author` varchar(200) NOT NULL COMMENT '作者',
  `content` text NOT NULL COMMENT '内容',
  `count_num` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '所属分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `think_news`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_partner`
--

CREATE TABLE IF NOT EXISTS `think_partner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '标题',
  `typeid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:友情链接，1：合作伙伴',
  `address` varchar(250) NOT NULL COMMENT '链接地址',
  `content` text NOT NULL COMMENT '详细内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `picpath` varchar(250) NOT NULL COMMENT '链接图片',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `think_partner`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_paylist`
--

CREATE TABLE IF NOT EXISTS `think_paylist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_id` int(11) NOT NULL COMMENT '商家id',
  `money` double(10,2) NOT NULL COMMENT '充值金额',
  `tmoney` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '当时余额',
  `create_time` int(11) NOT NULL COMMENT '充值时间',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0：支付宝，1：手动,2：购买展位',
  `remark` varchar(250) NOT NULL COMMENT '充值备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_paylist`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_pointlog`
--

CREATE TABLE IF NOT EXISTS `think_pointlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `why` varchar(100) NOT NULL COMMENT '操作',
  `point` varchar(50) NOT NULL COMMENT '积分',
  `tpoint` double(10,2) NOT NULL DEFAULT '0.00',
  `ptype` tinyint(4) NOT NULL DEFAULT '0',
  `log_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `think_pointlog`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_seckill`
--

CREATE TABLE IF NOT EXISTS `think_seckill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '标题',
  `price` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '价值',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '所需积分',
  `ktype` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1：积分兑换，2：秒杀',
  `begin_time` int(11) NOT NULL COMMENT '秒杀时间',
  `pic` varchar(150) NOT NULL COMMENT '展示图片',
  `content` text NOT NULL COMMENT '简介',
  `user_count` int(11) NOT NULL DEFAULT '0' COMMENT '当前人数',
  `limit_count` int(11) NOT NULL COMMENT '人数限制',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='秒杀兑换表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `think_seckill`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_seckill_classify`
--

CREATE TABLE IF NOT EXISTS `think_seckill_classify` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `remark` varchar(250) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `think_seckill_classify`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_seckill_goods`
--

CREATE TABLE IF NOT EXISTS `think_seckill_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` tinyint(2) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `score` int(10) unsigned NOT NULL,
  `signupscore` int(10) unsigned NOT NULL,
  `failscore` int(10) unsigned NOT NULL,
  `price` varchar(25) NOT NULL,
  `thumpath` varchar(200) NOT NULL,
  `path` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `think_seckill_goods`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_seckill_log`
--

CREATE TABLE IF NOT EXISTS `think_seckill_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT '活动id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `ktype` tinyint(4) NOT NULL COMMENT '类型',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_seckill_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_seckill_user`
--

CREATE TABLE IF NOT EXISTS `think_seckill_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `tel` varchar(25) NOT NULL,
  `address` varchar(255) NOT NULL,
  `typeid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `failscore` int(10) unsigned NOT NULL COMMENT '秒杀失败扣除的分数',
  `sectype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_seckill_user`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_tag`
--

CREATE TABLE IF NOT EXISTS `think_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '英文标识',
  `title` varchar(250) NOT NULL COMMENT '名称',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `remark` varchar(250) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `think_tag`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_tenancy`
--

CREATE TABLE IF NOT EXISTS `think_tenancy` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '租赁ID',
  `bid` int(11) NOT NULL COMMENT '对应exhibition的id',
  `p_id` int(10) unsigned NOT NULL COMMENT '对应打印机的location',
  `bt_position` varchar(20) NOT NULL COMMENT '按钮位置',
  `trade_id` int(10) unsigned NOT NULL COMMENT 'trade表id',
  `start_time` int(10) unsigned NOT NULL COMMENT '租赁开始时间',
  `close_time` int(11) unsigned NOT NULL COMMENT '租赁结束时间',
  `remark` text NOT NULL COMMENT '租赁备注',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，2:未付款-1:到期 0:预约中 1:正常',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  `type_buy` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：一般购买，1：协议购买',
  `money` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '花费金额',
  PRIMARY KEY (`id`),
  KEY `p_id` (`p_id`),
  KEY `trade_id` (`trade_id`),
  KEY `bt_position` (`bt_position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='租赁管理' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `think_tenancy`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_ticket`
--

CREATE TABLE IF NOT EXISTS `think_ticket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '优惠券ID',
  `trade_id` int(10) unsigned NOT NULL COMMENT '对应商家的ID',
  `cate_id` int(10) unsigned NOT NULL COMMENT '对应商品的分类ID',
  `name` varchar(20) NOT NULL COMMENT '商品英文名称',
  `title` varchar(50) NOT NULL COMMENT '商品中文名称',
  `tags` varchar(250) NOT NULL DEFAULT '0' COMMENT '优惠券标签',
  `keyword` varchar(200) NOT NULL,
  `introduce` varchar(250) NOT NULL COMMENT '商品摘要',
  `logo` varchar(200) NOT NULL COMMENT '展示图片',
  `content` text NOT NULL COMMENT '商品详细内容',
  `attention` text NOT NULL COMMENT '商品注意事项',
  `start_time` int(11) unsigned NOT NULL COMMENT '优惠券开始时间',
  `close_time` int(11) unsigned NOT NULL COMMENT '优惠券结束时间',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '下载优惠券所用积分',
  `money` double(10,2) NOT NULL COMMENT '商家获得的结算金额',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '可供下载的数量（-1无限制）',
  `click_count` int(11) NOT NULL DEFAULT '0' COMMENT '点击总数',
  `print_count` int(11) NOT NULL DEFAULT '0' COMMENT '打印总数',
  `collect_count` int(11) NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `sortnum` int(11) NOT NULL COMMENT '排序，数值越大越靠前',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态（-1:禁用 0:待审核 1:审核通过）',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  `operator` int(11) NOT NULL COMMENT '操作人员ID',
  `html` text,
  PRIMARY KEY (`id`),
  KEY `Idx_ticket_id` (`id`),
  KEY `Idx_ticket_tradeid` (`trade_id`),
  KEY `title` (`title`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='优惠券' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `think_ticket`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_ticket_statistic`
--

CREATE TABLE IF NOT EXISTS `think_ticket_statistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `position_id` int(10) unsigned NOT NULL COMMENT '机器位置ID',
  `trade_id` int(10) unsigned NOT NULL COMMENT '商家ID',
  `ticket_id` int(10) unsigned NOT NULL COMMENT '优惠券ID',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `action_type` tinyint(3) NOT NULL COMMENT '操作类型（0-点击 1-打印 2-收藏）',
  `create_time` int(11) unsigned NOT NULL COMMENT '发生时间',
  `ip` varchar(40) NOT NULL COMMENT '发生的IP地址（仅为网上点击时有效）',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1:打印机打印，0：网站打印',
  PRIMARY KEY (`id`),
  KEY `Idx_stat_other1` (`ticket_id`,`action_type`,`create_time`),
  KEY `Idx_stat_other2` (`user_id`,`action_type`,`create_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='优惠券统计表' AUTO_INCREMENT=34 ;

--
-- 转存表中的数据 `think_ticket_statistic`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_trade`
--

CREATE TABLE IF NOT EXISTS `think_trade` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家ID',
  `name` varchar(30) NOT NULL COMMENT '商家英文名称',
  `title` varchar(40) NOT NULL COMMENT '商家名称',
  `logo` varchar(250) NOT NULL DEFAULT ' ' COMMENT 'Logo',
  `attach_id` varchar(100) NOT NULL COMMENT '更多图片',
  `contact` varchar(20) NOT NULL COMMENT '联系人',
  `phone` varchar(20) NOT NULL COMMENT '固定电话',
  `mobile` varchar(20) NOT NULL COMMENT '移动电话',
  `address` varchar(250) NOT NULL COMMENT '地址',
  `fax` varchar(20) NOT NULL COMMENT '传真',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `tags` varchar(250) NOT NULL,
  `sort` int(11) NOT NULL COMMENT '排序位',
  `keyword` varchar(250) NOT NULL COMMENT '关键字',
  `introduce` text NOT NULL COMMENT '公司介绍',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `getmoney` double(10,2) NOT NULL COMMENT '商家得到的结算金额',
  `money` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '商家储值',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `extra` text NOT NULL COMMENT '待审核',
  PRIMARY KEY (`id`),
  KEY `Idx_trade_id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商家管理' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `think_trade`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_trademoney`
--

CREATE TABLE IF NOT EXISTS `think_trademoney` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_id` int(11) NOT NULL COMMENT '商家ID',
  `ticket_id` int(11) NOT NULL COMMENT '优惠券ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `money` double NOT NULL DEFAULT '0' COMMENT '商家获得结算额',
  `password` varchar(30) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `think_trademoney`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_trade_branch`
--

CREATE TABLE IF NOT EXISTS `think_trade_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_id` int(11) NOT NULL COMMENT '商家ID',
  `area` int(11) NOT NULL COMMENT '商圈ID',
  `title` varchar(250) NOT NULL COMMENT '分店名称',
  `address` varchar(250) NOT NULL COMMENT '分店地址',
  `phone` varchar(250) NOT NULL COMMENT '联系电话',
  `remark` text NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分店' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_trade_branch`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_trade_collect`
--

CREATE TABLE IF NOT EXISTS `think_trade_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `trade_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商家收藏' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `think_trade_collect`
--


-- --------------------------------------------------------

--
-- 表的结构 `think_user`
--

CREATE TABLE IF NOT EXISTS `think_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `account` varchar(64) NOT NULL COMMENT '账户名称',
  `nickname` varchar(50) NOT NULL COMMENT '用户昵称',
  `password` varchar(40) NOT NULL COMMENT '账户密码',
  `register_ip` varchar(40) NOT NULL COMMENT '注册IP',
  `last_login_time` int(11) NOT NULL COMMENT '最后一次登录时间',
  `last_login_ip` varchar(40) NOT NULL COMMENT '最后一次登录IP',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `verify` varchar(32) NOT NULL COMMENT '验证码',
  `remark` varchar(255) NOT NULL COMMENT '注释',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `type_id` tinyint(4) NOT NULL COMMENT '0:后台管理员，1:商家，2：普通用户',
  `card_id` varchar(30) NOT NULL COMMENT '绑定卡id',
  `card_time` int(11) unsigned NOT NULL COMMENT '绑定卡时间',
  `ticket_id` varchar(250) NOT NULL COMMENT '优惠券id,用，隔开',
  `userinfo_if` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否完善信息',
  `realname` varchar(30) NOT NULL COMMENT '真实姓名',
  `logo` varchar(250) NOT NULL COMMENT '头像',
  `sex` varchar(10) NOT NULL COMMENT '性别',
  `birthday` int(11) NOT NULL COMMENT '生日',
  `region` varchar(100) NOT NULL COMMENT '所在地区',
  `address` varchar(100) NOT NULL COMMENT '地址',
  `reg_ip` varchar(40) NOT NULL COMMENT '注册地址',
  `zip_code` varchar(100) NOT NULL COMMENT '邮编地址',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `email_if` tinyint(4) NOT NULL DEFAULT '0' COMMENT '邮箱是否验证',
  `wap_if` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否访问wap',
  `phone` varchar(20) NOT NULL COMMENT '电话',
  `mobile` varchar(20) NOT NULL COMMENT '手机',
  `verify_email` varchar(20) NOT NULL COMMENT '验证邮箱',
  `verify_mobile` varchar(20) NOT NULL COMMENT '验证手机',
  `mobile_if` tinyint(4) NOT NULL DEFAULT '0' COMMENT '手机是否验证',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '金币',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户基础表' AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `think_user`
--

INSERT INTO `think_user` (`id`, `account`, `nickname`, `password`, `register_ip`, `last_login_time`, `last_login_ip`, `login_count`, `verify`, `remark`, `create_time`, `update_time`, `status`, `type_id`, `card_id`, `card_time`, `ticket_id`, `userinfo_if`, `realname`, `logo`, `sex`, `birthday`, `region`, `address`, `reg_ip`, `zip_code`, `email`, `email_if`, `wap_if`, `phone`, `mobile`, `verify_email`, `verify_mobile`, `mobile_if`, `score`) VALUES
(1, 'admin', '1231', '21232f297a57a5a743894a0e4a801fc3', '127.0.0.1', 1312276400, '127.0.0.1', 0, '', '31231222', 1222907803, 1295178768, 1, 0, '', 0, '', 0, '', '', '0', 0, '', '', '', '', '312312', 0, 0, '', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_user_trade`
--

CREATE TABLE IF NOT EXISTS `think_user_trade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `trade_id` int(11) NOT NULL COMMENT '商家ID',
  `create_time` int(11) NOT NULL COMMENT '授权时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户商家对应表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `think_user_trade`
--

