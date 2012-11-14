-- ----------------------------
-- modify table struct
-- ----------------------------
ALTER TABLE `think_card` ADD `use_time` INT( 11 ) NOT NULL ,ADD `limit_count` INT( 11 ) NOT NULL ,ADD `limit_time` INT( 11 ) NOT NULL,ADD `userif`   TINYINT( 4 ) NOT NULL DEFAULT '0' ;
ALTER TABLE `think_config` ADD `score_rate` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `card_time` ,ADD `score_give` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `score_rate` ,ADD `score_card` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `score_give` ,ADD `reg_if` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `score_card` ;
ALTER TABLE `think_config` ADD `webname` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `mobile_if` ;
ALTER TABLE `think_config_card` ADD `limit_time` INT( 11 ) NOT NULL AFTER `gif_height` ,ADD `limit_count` INT( 11 ) NOT NULL AFTER `limit_time` ;
ALTER TABLE `think_error` ADD `status` TINYINT( 4 ) NOT NULL DEFAULT '0' AFTER `log_time` ,ADD `flag` TINYINT( 4 ) NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `think_news` ADD `cid` INT( 11 ) NOT NULL AFTER `status`;
ALTER TABLE `think_paylist` ADD `tmoney` DOUBLE( 10, 2 ) NOT NULL DEFAULT '0.00' AFTER `remark`;
ALTER TABLE `think_pointlog` ADD `tpoint` DOUBLE( 10, 2 ) NOT NULL DEFAULT '0.00' AFTER `log_time` ,ADD `ptype` TINYINT( 4 ) NOT NULL DEFAULT '0' AFTER `tpoint`;
ALTER TABLE `think_tenancy` ADD `type_buy` TINYINT( 4 ) NOT NULL DEFAULT '0' AFTER `update_time` ,ADD `money` DOUBLE( 10, 2 ) NOT NULL DEFAULT '0.00' AFTER `type_buy`;
ALTER TABLE `think_trade` ADD `extra` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `update_time`;
ALTER TABLE `think_user` ADD `card_time` INT( 11 ) UNSIGNED NOT NULL AFTER `mobile` ,ADD `score` INT( 11 ) NOT NULL DEFAULT '0' AFTER `card_time`;
ALTER TABLE `think_config` ADD `word_filter` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '过滤字符' AFTER `update_time` 
-- ----------------------------
-- Table structure for `think_cards`
-- ----------------------------
DROP TABLE IF EXISTS `think_cards`;
CREATE TABLE `think_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '卡名',
  `price` double(10,2) NOT NULL COMMENT '价格',
  `path` varchar(250) NOT NULL COMMENT '图片地址',
  `content` text NOT NULL COMMENT '介绍',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_cards
-- ----------------------------

-- ----------------------------
-- Table structure for `think_collect`
-- ----------------------------
DROP TABLE IF EXISTS `think_collect`;
CREATE TABLE `think_collect` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_collect
-- ----------------------------

-- ----------------------------
-- Table structure for `think_comment`
-- ----------------------------
DROP TABLE IF EXISTS `think_comment`;
CREATE TABLE `think_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '所属评论',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `ticket_id` int(11) NOT NULL COMMENT '优惠券ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `content` text NOT NULL COMMENT '评论内容',
  `module` varchar(30) DEFAULT NULL,
  `review` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核状态',
  `star` tinyint(4) NOT NULL DEFAULT '0' COMMENT '评分',
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券评论表';

-- ----------------------------
-- Records of think_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `think_huodong`
-- ----------------------------
DROP TABLE IF EXISTS `think_huodong`;
CREATE TABLE `think_huodong` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动';

-- ----------------------------
-- Records of think_huodong
-- ----------------------------

-- ----------------------------
-- Table structure for `think_info`
-- ----------------------------
DROP TABLE IF EXISTS `think_info`;
CREATE TABLE `think_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_info
-- ----------------------------
INSERT INTO think_info VALUES ('1', '公司介绍', '公司介绍内容', '1298451935', '1298451935', '1');
INSERT INTO think_info VALUES ('2', '新手引导', '<div align=\"center\"><img src=\"/imgs/setp.jpg\" /></div>', '1298451957', '1310026940', '1');
INSERT INTO think_info VALUES ('3', '商家加入', '商家加入注意事项，及联系方式', '1298452097', '1298452097', '1');
INSERT INTO think_info VALUES ('4', '联系我们', '联系我们', '1298958809', '1298958809', '1');

-- ----------------------------
-- Table structure for `think_location`
-- ----------------------------
DROP TABLE IF EXISTS `think_location`;
CREATE TABLE `think_location` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='机器放置地点管理';

-- ----------------------------
-- Records of think_location
-- ----------------------------

-- ----------------------------
-- Table structure for `think_newclass`
-- ----------------------------
DROP TABLE IF EXISTS `think_newclass`;
CREATE TABLE `think_newclass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 NOT NULL COMMENT '分类名称',
  `pid` int(11) NOT NULL COMMENT '父类ID',
  `path` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '排序标志',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='新闻分类表';
-- ----------------------------
-- Table structure for `think_news`
-- ----------------------------
DROP TABLE IF EXISTS `think_news`;
CREATE TABLE `think_news` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `think_seckill`
-- ----------------------------
DROP TABLE IF EXISTS `think_seckill`;
CREATE TABLE `think_seckill` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='秒杀兑换表';

-- ----------------------------
-- Records of think_seckill
-- ----------------------------

-- ----------------------------
-- Table structure for `think_seckill_classify`
-- ----------------------------
DROP TABLE IF EXISTS `think_seckill_classify`;
CREATE TABLE `think_seckill_classify` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `remark` varchar(250) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_seckill_classify
-- ----------------------------

-- ----------------------------
-- Table structure for `think_seckill_goods`
-- ----------------------------
DROP TABLE IF EXISTS `think_seckill_goods`;
CREATE TABLE `think_seckill_goods` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_seckill_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `think_seckill_log`
-- ----------------------------
DROP TABLE IF EXISTS `think_seckill_log`;
CREATE TABLE `think_seckill_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT '活动id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `ktype` tinyint(4) NOT NULL COMMENT '类型',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_seckill_log
-- ----------------------------

-- ----------------------------
-- Table structure for `think_trade_collect`
-- ----------------------------
DROP TABLE IF EXISTS `think_trade_collect`;
CREATE TABLE `think_trade_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `trade_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家收藏';

-- ----------------------------
-- Records of think_trade_collect
-- ----------------------------
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
