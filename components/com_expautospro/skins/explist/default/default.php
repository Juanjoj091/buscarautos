<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

defined('_JEXEC') or die;

$params_file = JPATH_COMPONENT . '/skins/explist/default/parameters/params.php';
if (file_exists($params_file))
    require_once $params_file;
ExpAutosProHelper::expskin_lang('explist', 'default');

$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$document = JFactory::getDocument();
$user = JFactory::getUser();
if ($this->expparams->get('c_admanager_lspage_showshortlist')) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expshortlist.js');
}
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/explist/default/css/default.css');
$catidlink = '';
$makeidlink = '';
if ($this->items) {
    $catidlink = $this->items[0]->categid;
    $makeidlink = $this->items[0]->make;
}
//$countcolumn = $this->expparams->get('c_admanager_mdpage_column');
$topmoduleposition = $this->expparams->get('c_admanager_lspage_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_lspage_bmpname');
$thumbsize = $this->expparams->get('c_images_thumbsize_width');
$userid = JRequest::getInt('userid', 0);
//print_r($this->items);
if ($user->id) {
    $usergroups = implode(',', $user->groups);
    $usergroupid = ExpAutosProExpparams::getExpgroupid($usergroups);
    $groupparams = ExpAutosProExpparams::getExpParams('userlevel', $usergroupid);
//print_r($groupparams->get('p_pshowpricespecial'));
}
$listgroupparams = $this->expgroupfield;
if ($this->expcatfields) {
    $useid = $this->expparams->get('c_admanager_lspage_showid');
    $usecategory = $this->expparams->get('c_admanager_lspage_showcat');
    if ($this->expcatfields->get('usestocknum') && $this->expparams->get('c_admanager_lspage_showstock')) {
        $usestock = 1;
    } else {
        $usestock = 0;
    }
    $usemake = $this->expcatfields->get('usemakes');
    $usemodel = $this->expcatfields->get('usemodels');
    $usemileage = $this->expcatfields->get('usemileage');
    $usebodytype = $this->expcatfields->get('usebodytype');
    $usedrive = $this->expcatfields->get('usedrive');
    $useextcolor = $this->expcatfields->get('useextcolor');
    $usetrans = $this->expcatfields->get('usetrans');
    $usefuel = $this->expcatfields->get('usefuel');
    $useyear = $this->expcatfields->get('useyear');
    $useprice = $this->expcatfields->get('useprice');
} else {
    $useid = $this->expparams->get('c_admanager_lspage_showid');
    $usemake = $this->expparams->get('c_admanager_lspage_def_make');
    $usemodel = $this->expparams->get('c_admanager_lspage_def_model');
    $usecategory = $this->expparams->get('c_admanager_lspage_showcat');
    $usestock = $this->expparams->get('c_admanager_lspage_def_stocknum');
    $usemileage = $this->expparams->get('c_admanager_lspage_def_mileage');
    $usebodytype = $this->expparams->get('c_admanager_lspage_def_bodytype');
    $usedrive = $this->expparams->get('c_admanager_lspage_def_drive');
    $useextcolor = $this->expparams->get('c_admanager_lspage_def_extcolor');
    $usetrans = $this->expparams->get('c_admanager_lspage_def_trans');
    $usefuel = $this->expparams->get('c_admanager_lspage_def_fuel');
    $useyear = $this->expparams->get('c_admanager_lspage_def_year');
    $useprice = $this->expparams->get('c_admanager_lspage_def_price');
}
$cookiesarray = explode(",", $this->expgetcookie);
$logopatch = ExpAutosProExpparams::ImgUrlPatchLogo();
if ($userid && $this->items)
    $user_params = ExpAutosProExpparams::getExpParams('expuser', $this->items[0]->expuser_id);
?>

<div class="expautospro_topmodule">
    <div class="expautospro_topmodule_pos">
        <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_lspage_tmpstyle')); ?>
    </div>
    <div class="expautospro_clear"></div>
</div>

<!-- Skins Module Position !-->
<?php if ($this->expparams->get('c_admanager_lspage_showskin')): ?>
    <div id="expskins_module">
        <?php
        $expmodparam = array('folder' => $this->expskins);
        echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
        ?>
    </div>
    <div class="expautospro_clear"></div>
<?php endif; ?>

<?php if ($userid && ($this->expparams->get('c_general_showcontact') || (!$this->expparams->get('c_general_showcontact') && $user->id))): ?>

    <?php if ($listgroupparams->get('c_photo') && $this->items && $user_params->get('expphoto')): ?>
        <div class="expuser_img">
            <img src='<?php echo $logopatch . $user_params->get('expphoto'); ?>' title="<?php echo $this->items[0]->user_username; ?>" />
        </div>
    <?php endif; ?>
    <div class="exp_autos_list_userinfo">
        <div class="exp_autos_list_userinfo_left">
            <?php
            if ($listgroupparams->get('c_ulogo') && $this->items && $this->items[0]->expuser_logo):
                $logo_link = $logopatch . $this->items[0]->expuser_logo;
                ?>
                <?php if ($listgroupparams->get('c_uwebsite') && $this->items[0]->expuser_web): ?>
                    <a href="http://<?php echo $this->items[0]->expuser_web; ?>">
                    <?php endif; ?>
                    <img src="<?php echo $logo_link; ?>" class="expautos_list_rightimg" title="<?php echo $this->items[0]->user_username; ?>" alt="<?php echo $this->items[0]->user_username; ?>" />
                    <?php if ($listgroupparams->get('c_uwebsite') && $this->items[0]->expuser_web): ?>
                    </a>
                <?php endif; ?>

            <?php endif; ?>
        </div>
        <ul class="expautos_user_ullist">
            <?php if ($listgroupparams->get('c_lastname') && (isset($this->items[0]->expuser_lastname) || isset($this->items[0]->user_name))): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_NAME_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->user_name; ?>&nbsp;<?php echo $this->items[0]->expuser_lastname; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_username') && isset($this->items[0]->user_username)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_USERNAME_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->user_username; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_uphone') && isset($this->items[0]->expuser_phone)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_PHONE_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_phone; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_ucphone') && isset($this->items[0]->expuser_mobphone)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_CELLPHONE_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_mobphone; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_ufax') && isset($this->items[0]->expuser_fax)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_FAX_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_fax; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_email') && isset($this->items[0]->user_email)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_EMAIL_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->user_email; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_ucompany') && isset($this->items[0]->expuser_companyname)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_COMPANYNAME_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_companyname; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_ucountry') && isset($this->items[0]->expuser_country)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_COUNTRY_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_country; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_ustate') && isset($this->items[0]->expuser_state)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_STATE_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_state; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_ucity') && isset($this->items[0]->expuser_city)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_CITY_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_city; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_ustreet') && isset($this->items[0]->expuser_street)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_STREET_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_street; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_uzip') && isset($this->items[0]->expuser_zipcode)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_ZIPCODE_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_zipcode; ?></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_uwebsite') && isset($this->items[0]->expuser_web)): ?>
                <li class='expautos_user_lilist'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_USERWEB_TEXT') ?>&#58;&nbsp;</span><a href="http://<?php echo $this->items[0]->expuser_web; ?>"><?php echo $this->items[0]->expuser_web; ?></a></li>
            <?php endif; ?>
            <?php if ($listgroupparams->get('c_uinfo') && isset($this->items[0]->expuser_userinfo)): ?>
                <li class='expautos_user_lilist expuser_otherinfo'><span class='expautos_user_lispanlist'><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_USERINFO_TEXT') ?>&#58;&nbsp;</span><?php echo $this->items[0]->expuser_userinfo; ?></li>
            <?php endif; ?>
            <?php if ($useid == $this->expparams->get('c_admanager_lspage_showdealerlink') && isset($this->items[0]->user_username)): ?>
                <a href="<?php echo JRoute::_('index.php?option=com_expautospro&amp;view=expdealerdetail&amp;userid=' . (int) $userid . '&amp;Itemid=' . (int) $expitem); ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_DEALERLINK_TEXT'); ?></a>
            <?php endif; ?>
        </ul>
    </div>
    <div class="expautos_clear"></div>
<?php endif; ?>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>#expautospro" method="post" name="adminForm" id="adminForm">
    <div id="expautospro">
        <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TEXT') ?></h2>
        <center>
            <table class="explist">
                <thead>
                    <tr>
                        <th width="<?php echo $thumbsize; ?>">
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_IMAGE_TEXT'); ?>
                        </th>
                        <?php if ($useid): ?>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_ID_TEXT', 'a.id', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                        <?php if ($usecategory): ?>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_CATEGORY_TEXT', 'catid', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                        <?php if ($usestock): ?>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_STOCKNUM_TEXT', 'a.stocknum', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                        <th class="expautos_th_left">
                        <?php if (!$useid && $use_sortby_date): ?>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_SKIN_SORTBYDATE_TEXT', 'a.id', $listDirn, $listOrder); ?>&nbsp;
                        <?php endif; ?>
                            <?php if ($usemake): ?>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_MAKE_TEXT', 'make_name', $listDirn, $listOrder); ?>
                            <?php endif; ?>
                            <?php if ($usemodel): ?>
                                &nbsp;
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_MODEL_TEXT', 'model_name', $listDirn, $listOrder); ?>
                            <?php endif; ?>
                            <?php if ($usemileage): ?>
                                &nbsp;
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_MILEAGE_TEXT', 'mileage', $listDirn, $listOrder); ?>
                            <?php endif; ?>
                            <?php if ($usebodytype): ?>
                                &nbsp;
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_BODYTYPE_TEXT', 'bodytype_name', $listDirn, $listOrder); ?>
                            <?php endif; ?>
                            <?php if ($usedrive): ?>
                                &nbsp;
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_DRIVE_TEXT', 'drive_name', $listDirn, $listOrder); ?>
                            <?php endif; ?>
                        </th>
                        <?php if ($useextcolor): ?>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_COLOR_TEXT', 'extcolor_name', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                        <?php if ($useyear): ?>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_YEAR_TEXT', 'a.year', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                        <?php if ($usetrans): ?>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_TRANS_TEXT', 'trans_name', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                        <?php if ($usefuel): ?>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_FUEL_TEXT', 'fuel_name', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                        <?php if ($useprice): ?>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_LIST_TABLE_PRICE_TEXT', 'price', $listDirn, $listOrder); ?>
                            </th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <div id="explimitbox">
                                <?php echo $this->pagination->getLimitBox(); ?>
                            </div>
                            <div class="expautospro_clear"></div>
                            <div id="expresultcounter">
                                <?php echo $this->pagination->getResultsCounter(); ?>
                            </div>
                            <div class="expautospro_clear"></div>
                            <div id="exppagelinks">
                                <?php echo $this->pagination->getPagesLinks(); ?>
                            </div>
                            <div class="expautospro_clear"></div>
                            <div id="exppagescounter">
                                <?php echo $this->pagination->getPagesCounter(); ?>
                            </div>
                            <div class="expautospro_clear"></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if ($this->items):
                        foreach ($this->items as $i => $item) :
                            //$countimg = ExpAutosProHelper::getExpcount('images', 'catid', $item->id, 1);
                            $countimg = $item->imgcount;
                            $link = JRoute::_('index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . (int) $item->id . '&amp;catid=' . (int) $item->catid . '&amp;makeid=' . (int) $item->make . '&amp;modelid=' . (int) $item->model . '&amp;Itemid=' . (int) $expitem);
                            $link_edit = JRoute::_('index.php?option=com_expautospro&amp;view=expadd&amp;task=expadd.edit&amp;id=' . $item->id . '&amp;Itemid=' . (int) $expitem);
                            $link_extend = JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;task=explist.extend&amp;id=' . $item->id . '&amp;Itemid=' . (int) $expitem);
                            $link_delete = JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;task=explist.deletelink&amp;id=' . $item->id . '&amp;Itemid=' . (int) $expitem);
                            $link_solid = JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;task=explist.solid&amp;value=' . $item->solid . '&amp;id=' . $item->id . '&amp;Itemid=' . (int) $expitem);
                            $link_expreserved = JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;task=explist.expreserved&amp;value=' . $item->expreserved . '&amp;id=' . $item->id . '&amp;Itemid=' . (int) $expitem);
                            $link_payspecial = JRoute::_('index.php?option=com_expautospro&amp;view=exppayment&amp;id=' . $item->id . '&amp;Itemid=' . (int) $expitem);
                            $link_payadd = JRoute::_('index.php?option=com_expautospro&amp;view=exppayment&amp;id=' . $item->id . '&amp;Itemid=' . (int) $expitem);
                            //print_r($item);
                            /* Extend date */
                            if ($this->expparams->get('c_general_useextend')) {
                                $date_exp = false;
                                $date_plus = JFactory::getDate('+' . $this->expparams->get('c_general_extendbutton') . ' day ' . date('Y-m-d', strtotime('now')))->toMySQL();
                                if ($item->expirdate <= $date_plus) {
                                    $date_exp = true;
                                    $date_expnum = date("Y-m-d", strtotime($item->expirdate));
                                }
                            }

                            /* New ads */
                            $date_range = time() - ($this->expparams->get('c_admanager_lspage_newdate') * 24 * 60 * 60);
                            $zc_new_date = date('Ymd', $date_range);
                            if (date('Ymd', strtotime($item->creatdate)) >= $zc_new_date)
                                $newdateclass = "newdate";
                            else
                                $newdateclass = "";

                            if ($item->fcommercial)
                                $imgclass = "commercial";
                            elseif ($item->ftop)
                                $imgclass = "top";
                            elseif ($item->special)
                                $imgclass = "special";
                            elseif ($item->solid)
                                $imgclass = "solid";
                            else
                                $imgclass = "";

                            if ($item->img_name && $countimg) {
                                $img_file = '<a href="' . $link . '"><span></span><img src="' . ExpAutosProExpparams::ImgUrlPatchThumbs() . $item->img_name . '" alt="' . $item->category_name . '&#45;' . $item->make_name . '&#45;' . $item->model_name . '" /></a>';
                            } else {
                                $img_file = '<a href="' . $link . '"><span></span><img src="' . ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg" alt="' . $item->category_name . '&#45;' . $item->make_name . '&#45;' . $item->model_name . '" /></a>';
                            }
                            ?>

                            <tr class="explistrow<?php echo $i % 2; ?>&#32;<?php echo $imgclass; ?>">
                                <td width="<?php echo $thumbsize; ?>px">
                                    <div class="photo <?php echo $imgclass; ?>">
                                        <?php echo $img_file; ?>
                                    </div>
                                    <div class="expimgcount">
                                        <?php echo $countimg . "&#32;" . JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_COUNTPICT_TEXT'); ?>
                                        <?php if ($newdateclass): ?>
                                            <span class="<?php echo $newdateclass; ?>" title="<?php echo JText::_('COM_EXPAUTOSPRO_LS_SPECIAL_NEWDATE_TEXT') ?>"></span>
                                        <?php endif; ?>
                                        <?php if ($item->expreserved): ?>
                                            <span class="expreserved" title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_RESERVED_TEXT') ?>"></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <?php if ($useid): ?>
                                    <td>
                                        <?php echo $item->id; ?> 
                                    </td>
                                <?php endif; ?>
                                <?php if ($usecategory): ?>
                                    <td>
                                        <?php echo $item->category_name; ?> 
                                    </td>
                                <?php endif; ?>
                                <?php if ($usestock): ?>
                                    <td>
                                        <?php echo $item->stocknum; ?> 
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <div class="expautos_list_markmod">
                                        <a href="<?php echo $link; ?>">
                                            <?php if ($item->make_name): ?>
                                                <?php echo $item->make_name; ?>
                                            <?php endif; ?>
                                            <?php if ($item->model_name): ?>
                                                &nbsp;
                                                <?php echo $item->model_name; ?>
                                            <?php endif; ?>
                                            <?php if ($item->specificmodel): ?>
                                                &nbsp;<?php echo $item->specificmodel; ?>
                                            <?php endif; ?>
                                            <?php if ($item->displacement > 0): ?>
                                                &nbsp;<?php echo $item->displacement . " " . JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT'); ?>
                                            <?php endif; ?>
                                            <?php if ($item->engine): ?>
                                                &nbsp;<?php echo $item->engine . " " . JText::_('COM_EXPAUTOSPRO_KW_TEXT'); ?>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="expautos_list_markmod_bottom">
                                        <?php if ($usemileage && $item->mileage): ?>
                                            <span title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_MILEAGE_TEXT'); ?>"><?php echo $item->mileage . "&nbsp;" . JText::_('COM_EXPAUTOSPRO_KM_TEXT'); ?></span>&#32;&#58;&#58;
                                        <?php endif; ?>
                                        <?php if ($usebodytype && $item->bodytype_name): ?>
                                            <span title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_BODYTYPE_TEXT'); ?>"><?php echo $item->bodytype_name; ?></span>&#32;&#58;&#58;
                                        <?php endif; ?>
                                        <?php if ($usedrive && $item->drive_name): ?>
                                            <span title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_DRIVE_TEXT'); ?>"><?php echo $item->drive_name; ?></span>
                                        <?php endif; ?>
                                        <?php if ($this->expparams->get('c_admanager_lspage_showshortlist')): ?>
                                            <div id="expshortlist<?php echo $item->id; ?>" class="expshortlist_lspage">
                                                <?php if (in_array($item->id, $cookiesarray)): ?>
                                                    <span title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_SHORTLIST_REMOVEFROM_TEXT'); ?>"><a href="javascript:expshortlist(2,<?php echo $item->id; ?>,'<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_SHORTLIST_REMOVED_TEXT'); ?>')"><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_SHORTLIST_REMOVEFROM_TEXT'); ?></a></span>
                                                <?php else: ?>
                                                    <span title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_SHORTLIST_ADDTO_TEXT'); ?>"><a href="javascript:expshortlist(1,<?php echo $item->id; ?>,'<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_SHORTLIST_SAVED_TEXT'); ?>')"><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_SHORTLIST_ADDTO_TEXT'); ?></a></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>    
                                        <?php if ((int) $item->user == (int) $user->id): ?>
                                            <div class="expautos_date">
                                                <p class="expautos_small">
                                                    <?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_CREATEDATE_TEXT'); ?><?php echo $item->creatdate; ?>
                                                </p>
                                                <?php if ($this->expparams->get('c_general_uselifeduration')): ?>
                                                    <p class="expautos_small">
                                                        <?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_EXPIRIESDATE_TEXT'); ?><?php echo $item->expirdate; ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="explistuserposition">
                                            <?php
                                            $expmodparam = array('id' => $item->id);
                                            echo ExpAutosProHelper::load_module_position('explistuserposition', $style = 'none', $expmodparam);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="expautos_list_markmod_modules">
                                        <?php echo ExpAutosProHelper::load_module_position('explistmodmiddle', $style = 'none'); ?>
                                    </div>
                                </td>
                                <?php if ($useextcolor): ?>
                                    <td>
                                        <?php if ($this->expparams->get('c_admanager_lspage_showcolorimg') && $item->extcolor_image): ?>
                                            <img src="<?php echo JURI::root() . $item->extcolor_image; ?>" alt="<?php echo $item->extcolor_name; ?>" title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_EXTCOLOR_TITLE_TEXT') . $item->extcolor_name; ?>" />
                                        <?php else: ?>
                                            <span title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_EXTCOLOR_TITLE_TEXT'); ?>"><?php echo $item->extcolor_name; ?></span>
                                        <?php endif; ?> 
                                    </td>
                                <?php endif; ?>
                                <?php if ($useyear): ?>
                                    <td>
                                        <?php echo (int) $item->year; ?>
                                    </td>
                                <?php endif; ?>
                                <?php if ($usetrans): ?>
                                    <td>
                                        <?php if ($this->expparams->get('c_admanager_lspage_showtranscode') && $item->trans_code): ?>
                                            <span title="<?php echo (string) $item->trans_name; ?>"><?php echo (string) $item->trans_code; ?></span>
                                        <?php else: ?>
                                            <?php echo (string) $item->trans_name; ?>
                                        <?php endif; ?> 
                                    </td>
                                <?php endif; ?>
                                <?php if ($usefuel): ?>
                                    <td>
                                        <?php if ($this->expparams->get('c_admanager_lspage_showfuelcode') && $item->fuel_code): ?>
                                            <span title="<?php echo (string) $item->fuel_name; ?>"><?php echo (string) $item->fuel_code; ?></span>
                                        <?php else: ?>
                                            <?php echo (string) $item->fuel_name; ?>
                                        <?php endif; ?> 
                                    </td>
                                <?php endif; ?>
                                <?php if ($useprice): ?>
                                    <td>
                                        <?php
                                        $price = ExpAutosProExpparams::price_formatdata($item->price);
                                        echo $price;
                                        ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php if ((int) $item->user == (int) $user->id): ?>
                                <tr class="expautoslist_bottom">
                                    <td colspan="16">
                                        <div class="expautos_list_users_link">
                                            <?php if ($this->expparams->get('c_general_enableedit')): ?>
                                                <span><a href="<?php echo $link_edit; ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_EDIT_TEXT'); ?></a></span>
                                            <?php endif; ?>
                                            <?php if ($this->expparams->get('c_general_enablesolid')): ?>
                                                <span>
                                                    <a href="<?php echo $link_solid; ?>">
                                                        <?php if ($item->solid): ?>
                                                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_UNSOLID_TEXT'); ?>
                                                        <?php else: ?>
                                                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_SOLID_TEXT'); ?>
                                                        <?php endif; ?>
                                                    </a>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($this->expparams->get('c_general_enableexpreserved')): ?>
                                                <span>
                                                    <a href="<?php echo $link_expreserved; ?>">
                                                        <?php if ($item->expreserved): ?>
                                                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_UNRESERVED_TEXT'); ?>
                                                        <?php else: ?>
                                                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_RESERVED_TEXT'); ?>
                                                        <?php endif; ?>
                                                    </a>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($this->expparams->get('c_general_enabledelete')): ?>
                                                <span><a href="<?php echo $link_delete; ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_DELETE_TEXT'); ?></a></span>
                                            <?php endif; ?>
                                            <?php if ($this->expparams->get('c_general_useextend') && $date_exp): ?>
                                                <span><a href="<?php echo $link_extend; ?>"><?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_LIST_TABLE_EXTEND_TEXT', $this->expparams->get('c_general_extenddays'))); ?></a></span>
                                            <?php endif; ?>
                                            <?php if ((int) $item->state == 1 && $groupparams->get('p_pshowpricespecial')): ?>
                                                <span class="expautos_list_payspecial"><a href="<?php echo $link_payspecial; ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_PAYSPECIAL_TEXT'); ?></a></span>
                                            <?php endif; ?>
                                            <?php if ((int) $item->state == 1): ?>
                                                <span class="expautos_list_payadd exppublished"><strong><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_PUBLISHED_TEXT'); ?></strong></span>
                                            <?php else: ?>
                                                <span class="expautos_list_payadd expunpublished"><strong><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_UNPUBLISHED_TEXT'); ?></strong>
                                                    <?php if ($listgroupparams->get('p_pshowpricead')): ?>&#40;<a href="<?php echo $link_payadd; ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_PAYAD_TEXT'); ?></a>&#41;<?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php echo ExpAutosProHelper::load_module_position('explistbottomposition', $this->expparams->get('c_admanager_lspage_bmpstyle')); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php
                            $metaexp[] = trim($item->make_name . " " . $item->model_name . " " . $item->specificmodel);
                            $metakeyexp[] = trim($item->make_name . " " . $item->model_name . " " . $item->specificmodel);
                            ?>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </center>
    </div>
    <div>
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    </div>
</form>
<div class="expautospro_clear"></div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $style = 'xhtml'); ?>
    </div>
<?php endif; ?>
<?php
/* insert meta */
if (isset($metaexp)) {
    $metaexp = implode(".", $metaexp);
    $this->document->setDescription($metaexp);
}
if (isset($metakeyexp)) {
    $metakeyexp = implode(",", $metakeyexp);
    $this->document->setMetadata('keywords', $metakeyexp);
}
?>

