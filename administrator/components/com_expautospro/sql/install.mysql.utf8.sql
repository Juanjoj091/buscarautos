DROP TABLE IF EXISTS `#__expautos_admanager`;
CREATE TABLE `#__expautos_admanager`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `catid` INT(11) NOT NULL,
  `make` INT(11) NOT NULL,
  `model` INT(11) NOT NULL,
  `country` INT(11) NOT NULL,
  `condition` INT(11) NOT NULL,
  `user` INT(11) NOT NULL,
  `bodytype` INT(11) NOT NULL,
  `drive` INT(11) NOT NULL,
  `fuel` INT(11) NOT NULL,
  `trans` INT(11) NOT NULL,
  `equipment` TEXT NOT NULL,
  `year` INT(4) NOT NULL,
  `month` INT(2) NOT NULL,
  `vincode` VARCHAR(200) NOT NULL,
  `mileage` VARCHAR(255) NOT NULL,
  `price` INT(11) NOT NULL,
  `bprice` INT(11) NOT NULL,
  `extcolor` INT(11) NOT NULL,
  `intcolor` INT(11) NOT NULL,
  `doors` INT(5) NOT NULL,
  `seats` INT(5) NOT NULL,
  `engine` VARCHAR(200) NOT NULL,
  `creatdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expirdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `embedcode` TEXT NOT NULL,
  `fcommercial` TINYINT(1) NOT NULL DEFAULT 0,
  `ffeatured` TINYINT(1) NOT NULL DEFAULT 0,
  `ftop` TINYINT(1) NOT NULL DEFAULT 0,
  `special` TINYINT(1) NOT NULL DEFAULT 0,
  `hits` INT(11) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `expemail` TINYINT(1) NOT NULL DEFAULT 0,
  `otherinfo` TEXT NOT NULL,
  `unweight` INT(11) NOT NULL,
  `grweight` INT(11) NOT NULL,
  `length` INT(11) NOT NULL,
  `width` INT(11) NOT NULL,
  `displacement` VARCHAR(10) NOT NULL,
  `metalliccolor` TINYINT(1) NOT NULL DEFAULT 0,
  `specificcolor` VARCHAR(100) NOT NULL,
  `specificmodel` VARCHAR(150) NOT NULL,
  `specifictrans` VARCHAR(150) NOT NULL,
  `expprice` INT(11) NOT NULL,
  `fconsumcity` VARCHAR(5) NOT NULL,
  `fconsumfreeway` VARCHAR(5) NOT NULL,
  `fconsumcombined` VARCHAR(5) NOT NULL,
  `adacceleration` VARCHAR(7) NOT NULL,
  `maxspeed` VARCHAR(7) NOT NULL,
  `solid` TINYINT(1) NOT NULL DEFAULT 0,
  `co` VARCHAR(100) NOT NULL,
  `params` TEXT NOT NULL,
  `language` CHAR(7) NOT NULL,
  `access` TINYINT(3) UNSIGNED NOT NULL,
  `extrafield1` INT(11) NOT NULL,
  `extrafield2` VARCHAR(255) NOT NULL,
  `extrafield3` TEXT NOT NULL,
  `city` INT(11) NOT NULL,
  `vattext` TINYINT(1) NOT NULL,
  `stocknum` VARCHAR(255) NOT NULL,
  `expstate` INT(11) NOT NULL,
  `expreserved` TINYINT(1) DEFAULT 0,
  `street` VARCHAR(100) DEFAULT NULL,
  `latitude` VARCHAR(100) DEFAULT NULL,
  `longitude` VARCHAR(100) DEFAULT NULL,
  `zipcode` VARCHAR(20) NOT NULL,
  `imgmain` VARCHAR(255) DEFAULT NULL,
  `imgcount` INT(5) DEFAULT NULL,
  `emailstyle` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (id),
  KEY `idexpam_state` (`state`),
  KEY `idexpam_ftop` (`ftop`),
  KEY `idexpam_catid` (`catid`),
  KEY `idexpam_make` (`make`),
  KEY `idexpam_model` (`model`),
  KEY `idexpam_bodytype` (`bodytype`),
  KEY `idexpam_bprice` (`bprice`),
  KEY `idexpam_price` (`price`),
  KEY `idexpam_access` (`access`),
  KEY `idexpam_fuel` (`fuel`),
  KEY `idexpam_ordering` (`ordering`),
  KEY `idexpam_solid` (`solid`),
  KEY `idexpam_user` (`user`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `#__expautos_bodytype`;
CREATE TABLE `#__expautos_bodytype`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `catid` INT(11) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `language` CHAR(7) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpbt_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_categories`;
CREATE TABLE `#__expautos_categories`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `alias` VARCHAR(255) DEFAULT NULL,
  `metakey` TEXT DEFAULT NULL,
  `metadesc` TEXT DEFAULT NULL,
  `access` TINYINT(3) UNSIGNED NOT NULL,
  `language` CHAR(7) NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  `state` TINYINT(1) NOT NULL DEFAULT 1,
  `params` TEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `metadata` TEXT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_catequipment`;
CREATE TABLE `#__expautos_catequipment`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `catid` INT(11) NOT NULL DEFAULT 777,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` CHAR(7) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpce_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_cities`;
CREATE TABLE `#__expautos_cities`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `catid` INT(11) NOT NULL,
  `city_zip` INT(15) UNSIGNED ZEROFILL NOT NULL,
  `city_name` VARCHAR(200) NOT NULL,
  `city_state` CHAR(50) NOT NULL,
  `city_latitude` DOUBLE NOT NULL,
  `city_longitude` DOUBLE NOT NULL,
  `city_county` VARCHAR(250) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `language` CHAR(7) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpci_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_color`;
CREATE TABLE `#__expautos_color`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` CHAR(7) NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  PRIMARY KEY (id),
  KEY `idexpcl_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_condition`;
CREATE TABLE `#__expautos_condition`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` CHAR(7) NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  PRIMARY KEY (id),
  KEY `idexpcn_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_config`;
CREATE TABLE `#__expautos_config`(
  `id` INT(1) NOT NULL DEFAULT 1,
  `params` TEXT NOT NULL,
  `license` VARCHAR(100) NOT NULL,
  `version` VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;
INSERT INTO `#__expautos_config` (`params`, `version`) VALUES ('{"c_general_priceformat":"1","c_general_shortlistcolumn":"1","c_general_shortlisttitle":"1","c_general_showcontact":"1","c_general_equipcolumn":"2","c_general_distanceunit":"0","c_general_uselifeduration":"0","c_general_lifedurationstatus":"0","c_general_adlifeduration":"30","c_general_useadminemail":"1","c_general_adminemail":"your@yourmail.com","c_general_sendexpiriesemail":"1","c_general_expiriesdays":"5","c_general_useextend":"0","c_general_extenddays":"30","c_general_extendbutton":"7","c_general_enableedit":"1","c_general_enabledelete":"2","c_general_enablesolid":"1","c_general_enableexpreserved":"1","c_user_req_autopublished":"1","c_user_req_lastname":"1","c_user_req_companyname":"1","c_user_req_country":"1","c_user_req_state":"0","c_user_req_city":"0","c_user_req_street":"0","c_user_req_phone":"1","c_user_req_cellphone":"0","c_user_req_fax":"0","c_user_req_zipcode":"0","c_user_req_web":"0","c_user_req_maxinfo":"200","c_user_req_logopatch":"media\/com_expautospro\/logo\/","c_admanager_files_folder":"media\/com_expautospro\/files\/","c_user_req_logowidth":"200","c_user_req_logoheight":"100","c_user_req_logoresmethod":"1","c_user_req_logohoralign":"1","c_user_req_logovertalign":"1","c_user_req_logopercent":"0","c_images_thumbpatch":"media\/com_expautospro\/images\/thumbs\/","c_images_middlepatch":"media\/com_expautospro\/images\/middle\/","c_images_bigpatch":"media\/com_expautospro\/images\/big\/","c_images_thumbsize_width":"100","c_images_thumbsize_height":"75","c_images_middlesize_width":"400","c_images_middlesize_height":"300","c_images_minsize_width":"400","c_images_minsize_height":"300","c_images_maxsize_width":"900","c_images_maxsize_height":"600","c_images_numupload":"5","c_images_maxfilesize":"2097152","c_images_resmethod":"1","c_images_horalign":"1","c_images_vertalign":"1","c_images_percent":"0","c_images_wt_use":"1","c_images_wt_imagename":"watermark.png","c_images_wt_horalign":"1","c_images_wt_vertalign":"1","c_images_wt_hormargin":"0","c_images_wt_vertmargin":"0","c_admanager_minyear":"1950","c_admanager_useparams":"0","c_admanager_req_price":"1","c_admanager_req_specificmodel":"0","c_admanager_req_bodytype":"1","c_admanager_req_drive":"1","c_admanager_req_fuel":"1","c_admanager_req_trans":"1","c_admanager_req_country":"1","c_admanager_req_state":"1","c_admanager_req_city":"1","c_admanager_req_street":"0","c_admanager_req_extrafield1":"0","c_admanager_req_extrafield2":"0","c_admanager_req_extrafield3":"0","c_admanager_req_condition":"1","c_admanager_req_extcolor":"0","c_admanager_req_specificcolor":"0","c_admanager_req_intcolor":"0","c_admanager_req_month":"0","c_admanager_req_year":"1","c_admanager_req_vincode":"0","c_admanager_req_mileage":"0","c_admanager_req_displacement":"0","c_admanager_req_engine":"0","c_admanager_req_stocknum":"0","c_admanager_fpcat_skin":"default","c_admanager_fpcat_showimg":"0","c_admanager_fpcat_showcat":"1","c_admanager_fpcat_showcount":"1","c_admanager_fpcat_showviewall":"1","c_admanager_fpcat_showadbutton":"1","c_admanager_fpcat_groupby":"ordering","c_admanager_fpcat_sortby":"asc","c_admanager_fpcat_tmpnameleft":"expfptopleft","c_admanager_fpcat_tmpnameright":"expfptopright","c_admanager_fpcat_bmpname":"expfpbottom","c_admanager_mkpage_skin":"default","c_admanager_mkpage_column":"3","c_admanager_mkpage_showempty":"1","c_admanager_mkpage_showcount":"1","c_admanager_mkpage_showviewall":"1","c_admanager_mkpage_groupby":"name","c_admanager_mkpage_sortby":"asc","c_admanager_mkpage_tmpname":"expmktop","c_admanager_mkpage_bmpname":"expmkbottom","c_admanager_mdpage_skin":"default","c_admanager_mdpage_column":"3","c_admanager_mdpage_showempty":"1","c_admanager_mdpage_showcount":"1","c_admanager_mdpage_showviewall":"1","c_admanager_mdpage_groupby":"name","c_admanager_mdpage_sortby":"asc","c_admanager_mdpage_tmpname":"expmdtop","c_admanager_mdpage_bmpname":"expmdbottom","c_admanager_lspage_skin":"default","c_admanager_lspage_showshortlist":"1","c_admanager_lspage_showcat":"0","c_admanager_lspage_showid":"0","c_admanager_lspage_showstock":"0","c_admanager_lspage_showfuelcode":"1","c_admanager_lspage_showtranscode":"1","c_admanager_lspage_showcolorimg":"1","c_admanager_lspage_showdealerlink":"1","c_admanager_lspage_showsolid":"0","c_admanager_lspage_groupby":"id","c_admanager_lspage_sortby":"asc","c_admanager_lspage_newdate":"7","c_admanager_lspage_tmpname":"explstop","c_admanager_lspage_bmpname":"explsbottom","c_admanager_lspage_def_stocknum":"0","c_admanager_lspage_def_mileage":"1","c_admanager_lspage_def_bodytype":"1","c_admanager_lspage_def_drive":"1","c_admanager_lspage_def_extcolor":"1","c_admanager_lspage_def_trans":"1","c_admanager_lspage_def_fuel":"1","c_admanager_lspage_def_price":"1","c_admanager_lspage_def_year":"1","c_admanager_detailpage_skin":"default","c_admanager_detailpage_showshortlist":"1","c_admanager_detailpage_showprint":"1","c_admanager_detailpage_showemail":"1","c_admanager_detailpage_showhtml":"1","c_admanager_detailpage_showrss":"1","c_admanager_detailpage_showallads":"1","c_admanager_detailpage_showmoreinfo":"1","c_admanager_detailpage_showhits":"1","c_admanager_detailpage_showcontactform":"1","c_admanager_detailpage_showgmap":"1","c_admanager_detailpage_gmapwidth":"470","c_admanager_detailpage_gmapheight":"350","c_admanager_detailpage_tmpname":"expdpstop","c_admanager_detailpage_bmpname":"expdpsbottom","c_admanager_detailpage_rgtname":"expdprgttop","c_admanager_detailpage_rgtbtname":"expdprgtbottom","c_admanager_dealerlspage_skin":"default","c_admanager_dealerlspage_users":"","c_admanager_dealerlspage_logowidth":"100","c_admanager_dealerlspage_logoheight":"50","c_admanager_dealerlspage_sortby":"companyname","c_admanager_dealerlspage_groupby":"ASC","c_admanager_dealerlspage_uselogo":"1","c_admanager_dealerlspage_useothertext":"1","c_admanager_dealerlspage_othertextlimit":"3","c_admanager_dealerlspage_usecountry":"1","c_admanager_dealerlspage_usestate":"1","c_admanager_dealerlspage_usecity":"1","c_admanager_dealerlspage_usezipcode":"1","c_admanager_dealerlspage_usephone":"1","c_admanager_dealerlspage_usestreet":"1","c_admanager_dealerlspage_useemail":"1","c_admanager_dealerlspage_useweb":"1","c_admanager_dealerlspage_tmpname":"expdlstop","c_admanager_dealerlspage_bmpname":"expdlsbottom","c_admanager_compare_skin":"default","c_admanager_compare_popup":"0","c_admanager_compare_maxads":"3","c_admanager_compare_showimg":"1","c_admanager_compare_showcat":"1","c_admanager_compare_showprice":"1","c_admanager_compare_showyear":"1","c_admanager_compare_showmileage":"1","c_admanager_compare_showengine":"1","c_admanager_compare_showfuel":"1","c_admanager_compare_showbodytype":"1","c_admanager_compare_showtrans":"1","c_admanager_compare_showextcolor":"1","c_admanager_compare_showdoors":"1","c_admanager_compare_showseats":"1","c_admanager_compare_showequip":"1","c_admanager_compare_equip_column":"1","c_admanager_dealerdetail_skin":"default","c_admanager_dealerdetail_showprint":"1","c_admanager_dealerdetail_showgroup":"1","c_admanager_dealerdetail_showallads":"1","c_admanager_dealerdetail_showgcontact":"1","c_admanager_dealerdetail_showgmap":"1","c_admanager_dealerdetail_gmapwidth":"470","c_admanager_dealerdetail_gmapheight":"350","c_admanager_dealerdetail_tmpname":"expddtop","c_admanager_dealerdetail_bmpname":"expddbottom","c_admanager_paylevel_skin":"default","c_admanager_paylevel_tmpname":"exppltop","c_admanager_paylevel_bmpname":"expplbottom","c_admanager_payment_skin":"default","c_admanager_payment_tmpname":"exppmtop","c_admanager_payment_bmpname":"exppmbottom","c_admanager_useradd_skin":"default","c_admanager_useradd_tmpname":"expuatop","c_admanager_useradd_bmpname":"expuabottom","c_admanager_add_skin":"default","c_admanager_add_equipcolumn":"3","c_admanager_add_maxdoors":"14","c_admanager_add_maxseats":"20","c_admanager_add_maxdisplacement":"25","c_admanager_add_maxotherinfo":"200","c_admanager_add_tmpname":"expadtop","c_admanager_add_bmpname":"expadbottom","c_admanager_addimages_skin":"default","c_admanager_addimages_tmpname":"expadimgtop","c_admanager_addimages_bmpname":"expadimgbottom"}', '3.5.3');

DROP TABLE IF EXISTS `#__expautos_country`;
CREATE TABLE `#__expautos_country`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `catid` INT(11) NOT NULL DEFAULT 777,
  `language` CHAR(7) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpct_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_currency`;
CREATE TABLE `#__expautos_currency`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `cname` VARCHAR(15) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `exchange` DOUBLE(15, 3) NOT NULL,
  `catid` INT(11) NOT NULL DEFAULT 777,
  `image` VARCHAR(255) NOT NULL,
  `language` CHAR(7) NOT NULL,
  `cvariable` VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpcr_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_drive`;
CREATE TABLE `#__expautos_drive`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` VARCHAR(255) NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  PRIMARY KEY (id),
  KEY `idexpdr_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_equipment`;
CREATE TABLE `#__expautos_equipment`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `catid` INT(11) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` CHAR(7) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpeq_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_expuser`;
CREATE TABLE `#__expautos_expuser`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `userid` INT(11) NOT NULL,
  `lastname` VARCHAR(200) NOT NULL,
  `companyname` VARCHAR(200) NOT NULL,
  `street` VARCHAR(200) NOT NULL,
  `city` VARCHAR(200) NOT NULL,
  `web` TEXT NOT NULL,
  `logo` TEXT NOT NULL,
  `country` INT(11) NOT NULL,
  `phone` VARCHAR(70) NOT NULL,
  `zipcode` VARCHAR(20) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `fax` VARCHAR(70) NOT NULL,
  `mobphone` VARCHAR(70) NOT NULL,
  `userinfo` TEXT NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  `language` CHAR(7) NOT NULL,
  `params` TEXT NOT NULL,
  `expstate` INT(11) NOT NULL,
  `utop` TINYINT(1) DEFAULT 0,
  `ucommercial` TINYINT(1) DEFAULT 0,
  `uspecial` TINYINT(1) DEFAULT 0,
  `usercreatedata` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userexpiriesdata` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `latitude` VARCHAR(100) DEFAULT NULL,
  `longitude` VARCHAR(100) DEFAULT NULL,
  `emailstyle` tinyint(1) DEFAULT '1',
  PRIMARY KEY (id),
  KEY `idexpus_catid` (`catid`),
  KEY `idexpus_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_extrafield1`;
CREATE TABLE `#__expautos_extrafield1`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `catid` INT(11) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `language` CHAR(7) NOT NULL,
  PRIMARY KEY (id)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_extrafield2`;
CREATE TABLE `#__expautos_extrafield2`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` VARCHAR(255) NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  PRIMARY KEY (id)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_extrafield3`;
CREATE TABLE `#__expautos_extrafield3`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` VARCHAR(255) NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  PRIMARY KEY (id)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_fuel`;
CREATE TABLE `#__expautos_fuel`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` CHAR(7) NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  PRIMARY KEY (id),
  KEY `idexpfl_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_images`;
CREATE TABLE `#__expautos_images`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `catid` INT(11) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL DEFAULT 1,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpim_catid` (`catid`),
  KEY `idexpim_ordering` (`ordering`),
  KEY `idexpim_id` (`id`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_make`;
CREATE TABLE `#__expautos_make`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `catid` INT(11) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `alias` VARCHAR(200) DEFAULT NULL,
  `ordering` INT(11) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `state` TINYINT(1) NOT NULL,
  `access` TINYINT(3) UNSIGNED NOT NULL,
  `metakey` TEXT DEFAULT NULL,
  `metadesc` TEXT DEFAULT NULL,
  `language` CHAR(7) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpmk_catid` (`catid`),
  KEY `idexpmk_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_model`;
CREATE TABLE `#__expautos_model`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `makeid` INT(11) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `alias` VARCHAR(200) DEFAULT NULL,
  `ordering` INT(11) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `state` TINYINT(1) NOT NULL,
  `access` TINYINT(3) UNSIGNED NOT NULL,
  `metakey` TEXT DEFAULT NULL,
  `metadesc` TEXT DEFAULT NULL,
  `language` CHAR(7) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  KEY `idexpmd_makeid` (`makeid`),
  KEY `idexpmd_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_payment`;
CREATE TABLE `#__expautos_payment`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `adid` INT(11) NOT NULL,
  `payval` INT(11) NOT NULL,
  `payuser` INT(11) NOT NULL,
  `paysum` DECIMAL(20, 2) NOT NULL,
  `status` VARCHAR(20) NOT NULL,
  `paydate` DATETIME NOT NULL,
  `payname` VARCHAR(255) NOT NULL,
  `paynotice` TEXT DEFAULT NULL,
  `payid` INT(11) DEFAULT NULL,
  `paysysval` INT(11) DEFAULT NULL,
  `state` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_state`;
CREATE TABLE `#__expautos_state`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `catid` INT(11) NOT NULL DEFAULT 777,
  `language` CHAR(7) NOT NULL,
  `code` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY `idexpst_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_trans`;
CREATE TABLE `#__expautos_trans`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `state` TINYINT(1) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `language` CHAR(7) NOT NULL,
  `catid` INT(3) NOT NULL DEFAULT 777,
  PRIMARY KEY (id),
  KEY `idexptr_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `#__expautos_userlevel`;
CREATE TABLE `#__expautos_userlevel`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `userlevel` INT(3) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `params` TEXT NOT NULL,
  `state` TINYINT(1) NOT NULL DEFAULT 1,
  `catid` INT(3) NOT NULL DEFAULT 777,
  PRIMARY KEY (id),
  KEY `idexpul_state` (`state`)
) ENGINE = MYISAM AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8;

