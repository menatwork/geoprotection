-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `gp_protected` char(1) NOT NULL default '',
  `gp_mode` varchar(7) NOT NULL default '',
  `gp_countries` blob NULL,
  `gp_group_countries` blob NULL,
  `gp_group_id` int(5) unsigned NOT NULL default '0',
  `gp_fallback` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
