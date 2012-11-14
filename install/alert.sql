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
ALTER TABLE `think_config` ADD `word_filter` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '¹ýÂË×Ö·û' AFTER `update_time` 
