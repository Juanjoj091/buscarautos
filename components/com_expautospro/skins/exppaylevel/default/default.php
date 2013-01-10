<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

defined('_JEXEC') or die;

$params_file = JPATH_COMPONENT . '/skins/exppaylevel/default/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('exppaylevel','default');

$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/exppaylevel/default/css/default.css');
$topmoduleposition = $this->expparams->get('c_admanager_paylevel_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_paylevel_bmpname');
$value_params = $this->explevelparams;
$usergroupid = '';
if ($user->id) {
    $usergroups = implode(',', $user->groups);
    $usergroupid = ExpAutosProExpparams::getExpgroupid($usergroups);
}
$metaexp[] = JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_TEXT');
$metakeyexp[] = JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_TEXT');
//print_r($usergroupid);
?>

<div class="expautospro_topmodule">
    <div class="expautospro_topmodule_pos">
        <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_paylevel_tmpstyle')); ?>
    </div>
    <div class="expautospro_clear"></div>
</div>

<!-- Skins Module Position !-->
<?php if($this->expparams->get('c_admanager_paylevel_showskin')):?>
<div id="expskins_module">
    <?php
    $expmodparam = array('folder' => $this->expskins);
    echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
    ?>
</div>
<div class="expautospro_clear"></div>
<?php endif; ?>

<div id="expautospro">
    <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_TEXT'); ?></h2>
        <div id="expdealerpay">
            <?php
            foreach ($this->items as $item):
                $value_params->loadJSON($item->params);
                if ($usergroupid == $item->id) {
                    $ulclass = " yourlevel";
                } else {
                    $ulclass = "";
                }
                $metaexp[] = $item->group_title;
                $metakeyexp[] = $item->group_title;
                ?>
                <ul class="expdealerpay_ul<?php echo $ulclass; ?>">
                    <?php if ($value_params->get('image')): ?>
                        <li>
                            <img src='<?php echo JURI::root() . $value_params->get('image'); ?>' title="<?php echo $item->group_title; ?>" />
                        </li>
                    <?php endif; ?>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_GROUPNAME_TEXT'); ?></span><?php echo $item->group_title; ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_ADSCOUNT_TEXT'); ?></span><?php echo $this->expunlimited_text($value_params->get('g_adscount')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_IMGCOUNT_TEXT'); ?></span><?php echo $this->expunlimited_text($value_params->get('g_imgcount')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_PUBLISHED_TEXT'); ?></span><?php echo $this->exppublished_text($value_params->get('g_published')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWUSERNAME_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_username')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWLASTNAME_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_lastname')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWCOMPANY_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_ucompany')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWPHONE_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_uphone')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWMOBPHONE_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_ucphone')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWFAX_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_ufax')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWEMAIL_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_email')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWCOUNTRY_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_ucountry')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWSTATE_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_ustate')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWCITY_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_ucity')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWSTREET_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_ustreet')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWZIPCODE_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_uzip')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWWEBSITE_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_uwebsite')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWLOGO_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_ulogo')); ?>
                    </li>
                    <li>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SHOWOTHERINFO_TEXT'); ?></span><?php echo $this->expyesno_text($value_params->get('c_uinfo')); ?>
                    </li>
                    <?php if ($value_params->get('p_pshowpricead')): ?>
                        <li>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_ADPRICE_TEXT'); ?></span><?php echo $this->expfree_text($value_params->get('p_ppricead')); ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($value_params->get('p_pshowpricespecial')): ?>
                        <li>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_TOPADSPRICE_TEXT'); ?></span><?php echo $this->expfree_text($value_params->get('p_ppricetop')); ?>
                        </li>
                        <li>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_COMMERCIALADSPRICE_TEXT'); ?></span><?php echo $this->expfree_text($value_params->get('p_ppricecommercial')); ?>
                        </li>
                        <li>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_SPECIALADSPRICE_TEXT'); ?></span><?php echo $this->expfree_text($value_params->get('p_ppricespecial')); ?>
                        </li>
                    <?php endif; ?>
                        <?php if(file_exists(JPATH_ROOT . '/administrator/components/com_expgroups/helpers/expgroups.php')):
                        require_once JPATH_ROOT . '/administrator/components/com_expgroups/helpers/expgroups.php';
                        ?>
                        <?php if(EXPGroupsHelper::expgroup_member($item->userlevel)):?>
                            <li>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_PAYLEVEL_MEMBERSHIPPERIOD_TEXT'); ?></span><?php echo EXPGroupsHelper::expgroup_member($item->userlevel); ?> <?php echo JText::_('COM_EXPAUTOSPRO_DAYS_TEXT'); ?>
                            </li>
                        <?php endif;?>
                    <?php endif;?>
                    <li class="expdealerpay_span">
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_LEVELPRICE_TEXT'); ?></span><?php echo $this->expfree_text($value_params->get('p_plevel')); ?>
                    </li>
                    <?php if ($user->id): ?>
                        <?php if ($value_params->get('p_plevel') && $usergroupid != $item->id): ?>
                    <li>
                        <span>
                            <div class="dealerpay_radio">
                                <?php
                                $expmodparam = array('price' => $value_params->get('p_plevel'), 'curcode' => $value_params->get('p_pcurrency'), 'itemnumber' => $value_params->get('p_plevelitemnum'), 'groupname' => $item->group_title, 'userid' => $user->id, 'groupid' => $item->userlevel, 'item_special' => '5', 'itm_id' => $item->userlevel);
                                echo ExpAutosProHelper::load_module_position('explevelpayment', $style = 'none', $expmodparam);
                                ?>
                            </div>
                        </span>
                    </li>
                        <?php endif; ?>
                    
                    <?php else:?>
                    <li>
                        <span>
                        <?php if($value_params->get('p_plevel')):?>
                            <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_NOLOGIN_TEXT', JRoute::_('index.php?option=com_users&view=login'), JRoute::_('index.php?option=com_users&view=registration'))); ?>
                        <?php endif; ?>
                        </span>
                    </li>
                   <?php endif; ?>
                    <?php if ($usergroupid == $item->id): ?>
                    <li>
                        <span>
                        <div class="expdealerpay_yourlevel">
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_YOURLEVEL_TEXT'); ?>
                        </div>
                        </span>
                    </li>
                    <?php endif; ?>
                </ul>
            <?php endforeach; ?>
            <div class="expautos_clear"></div>
        </div>
</div>
<div class="expautospro_clear"></div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_paylevel_bmpstyle')); ?>
    </div>
<?php endif; ?>
<?php
/* insert meta */
if ($metaexp) {
    $metaexp = implode(".", $metaexp);
    $this->document->setDescription($metaexp);
}
if ($metakeyexp) {
    $metakeyexp = implode(",", $metakeyexp);
    $this->document->setMetadata('keywords', $metakeyexp);
}
?>

