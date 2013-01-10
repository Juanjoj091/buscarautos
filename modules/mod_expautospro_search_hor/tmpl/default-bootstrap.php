<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

//no direct access
defined('_JEXEC') or die;
require_once JPATH_ROOT . '/administrator/components/com_categories/models/fields/categoryedit.php';
$app = JFactory::getApplication();
$exp_sef = $app->getCfg('sef');
if($exp_sef){
    $action_link=JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;Itemid='.$itemid);
}else{
    $action_link=JRoute::_('index.php');
}

$document = JFactory::getDocument();
if ($params->get('jquery',0) == 1) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/jquery.min.js');
} elseif ($params->get('jquery',0) == 2) {
    $document->addScript(JURI::root() . 'modules/mod_expautospro_search/js/jquery.min.js');
}

if ($params->get('jq_chained',0)) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchtoggle.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/jqexpsearch.js');
}else{
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchchained.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchtoggle.js');
}
if ($params->get('jq_chosen',0) && $params->get('jq_chained',0)) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/chosen.jquery.min.js');
    $document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/chosen.css');
}
if ($params->get('useshowhide')) {
    $hideclass = "expdisplay_none";
}
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_search_hor/css/mod_expautospro_search_hor_bootstrap.css');
$moduleid = $module->id;
?>
<style>
#modexpautospro_search_hor<?php echo $moduleid ?> ul li{
    list-style: none;
    margin: 0px 10px 0px;
}
</style>
<div id="modexpautospro_search_hor<?php echo $moduleid ?>" class="modexpautospro_search_hor expsearch <?php echo $moduleclass_sfx ?>">
    <form action="<?php echo $action_link;?>" method="get">
	<?php if(!$exp_sef):?>
            <input type="hidden" name="option" value="com_expautospro" />
            <input type="hidden" name="view" value="explist" />
        <?php endif;?>
        <fieldset class="expsutospro_search_fieldset">
            <?php if($params->get('catid')):?>
                    <input type="hidden" name="catid" value="<?php echo $params->get('catid'); ?>" />
            <?php endif;?>
            <?php echo ExpAutosProHelper::load_module_position('expsearch_hor'); ?>
            <ul>
            <?php if(!$params->get('catid')):?>
                <li class="modexpautospro_search_hor">
                    <label for="modexpsearch_cats"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_CATEGORIES_TEXT'); ?></label>
                    <?php echo $list; ?>
                </li>
            <?php endif;?>
            <?php if($params->get('usemakes')):?>
                <li class="modexpautospro_search_hor">
                    <label for="modexpsearch_makes"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_MAKES_TEXT'); ?></label>
                    <?php echo $make; ?>
                </li>
            <?php endif; ?>
            <?php if($params->get('usemodels')):?>
                <li class="modexpautospro_search_hor">
                    <label for="modexpsearch_models"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_MODELS_TEXT'); ?></label>
                    <?php echo $model; ?>
                </li>
			<?php endif; ?>
                <span class="expautos_search_advsearchbox <?php echo $hideclass; ?>" id="expshowhide<?php echo $moduleid; ?>" >
                    <?php if ($params->get('usesmodel')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_specificmodel"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_SPICIFICMODELS_TEXT'); ?></label>
                            <?php echo $specificmodel; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usecondition')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_condition"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_CONDITION_TEXT'); ?></label>
                            <?php echo $condition; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usebodytype')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_bodytype"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_BODYTYPE_TEXT'); ?></label>
                            <?php echo $bodytype; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useextrafield1')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_extrafield1"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_EXTRAFIELD1_TEXT'); ?></label>
                            <?php echo $extrafield1; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useextrafield2')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_extrafield2"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_EXTRAFIELD2_TEXT'); ?></label>
                            <?php echo $extrafield2; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useextrafield3')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_extrafield3"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_EXTRAFIELD3_TEXT'); ?></label>
                            <?php echo $extrafield3; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usecolor')): ?>
                        <li class="expautospro_search">
                            <label for="modexpsearch_color"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_COLOR_TEXT'); ?></label>
                            <?php echo $color; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useyear')): ?>
                        <li class="expautospro_search exphordouble">
                                <label for="modexpsearch_yearfrom" class="exphoryear_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_YEARFROM_TEXT'); ?></label>
                                <?php echo $yearfrom; ?>
                        </li>
                        <li class="expautospro_search exphordouble">
                                <label for="modexpsearch_yearto" class="exphoryear_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_YEARTO_TEXT'); ?></label>
                                <?php echo $yearto; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useprice')): ?>
                        <li class="expautospro_search exphordouble">
                                <label for="modexpsearch_pricefrom" class="exphordouble_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_PRICEFROM_TEXT'); ?></label>
                                <?php echo $pricefrom; ?>
                        </li>
                        <li class="expautospro_search exphordouble">
                                <label for="modexpsearch_priceto" class="exphordouble_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_PRICETO_TEXT'); ?></label>
                                <?php echo $priceto; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usemileage')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_mileage"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_MILEAGE_TEXT'); ?></label>
                            <?php echo $mileage; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usefuel')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_fuel"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_FUEL_TEXT'); ?></label>
                            <?php echo $fuel; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usedrive')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_drive"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_DRIVE_TEXT'); ?></label>
                            <?php echo $drive; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usetrans')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_trans"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_TRANSMISSION_TEXT'); ?></label>
                            <?php echo $trans; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useid')): ?>
                        <li class="expautospro_id">
                            <label for="modexpsearch_id"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_EXPID_TEXT'); ?></label>
                            <?php echo $expid; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usestocknum')): ?>
                        <li class="expautospro_stocknum">
                            <label for="modexpsearch_stocknum"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_STOCNUMBER_TEXT'); ?></label>
                            <?php echo $stocknum; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usevincode')): ?>
                        <li class="expautospro_vincode">
                            <label for="modexpsearch_vincode"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_VINCODE_TEXT'); ?></label>
                            <?php echo $vincode; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usecountry')): ?>
                        <?php if(!$params->get('country')):?>
                                <li class="modexpautospro_search_hor">
                                    <label for="modexpsearch_country"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_COUNTRY_TEXT'); ?></label>
                                    <?php echo $country; ?>
                                </li>
                        <?php endif; ?>
                        <?php if ($params->get('useexpstate')): ?>
                            <li class="modexpautospro_search_hor">
                                <label for="modexpsearch_expstate"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_EXPSTATE_TEXT'); ?></label>
                                <?php echo $expstate; ?>
                            </li>
                            <?php if ($params->get('usecity')): ?>
                                <li class="modexpautospro_search_hor">
                                    <label for="modexpsearch_city"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_CITY_TEXT'); ?></label>
                                    <?php echo $city; ?>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($params->get('useuser')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_user"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_USER_TEXT'); ?></label>
                            <?php echo $expuser; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usezipcode')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_zipcode"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_ZIPCODE_TEXT'); ?></label>
                            <?php echo $zipcode; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useradius')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_radius"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_FORM_RADIUS_TEXT'); ?></label>
                            <?php echo $radius; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useageof')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_ageof"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_SELECT_AGEOF_TEXT'); ?></label>
                            <?php echo $ageof; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usesortby')): ?>
                        <li class="modexpautospro_search_hor">
                            <label for="modexpsearch_sortby"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_SELECT_SORTBY_TEXT'); ?></label>
                            <?php echo $sortby; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useimg')): ?>
                    <div class="expvert_clear"></div>
                    <br />
                        <li class="expautospro_search searchcheckbox">
                            <label for="modexpsearch_img"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_SELECT_IMG_TEXT'); ?></label>
                            <?php echo $img; ?>
                        </li>
                        <div class="expclear"></div>
                    <?php endif; ?>
                    <?php if ($params->get('usewsolid')): ?>
                        <li class="expautospro_search searchcheckbox">
                            <label for="modexpsearch_wsolid"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_SELECT_WSOLID_TEXT'); ?></label>
                            <?php echo $wsolid; ?>
                        </li>
                    <?php endif; ?>
                <?php if ($params->get('useequipment')): ?>
                    <span id="expequip<?php echo $moduleid; ?>">
                    <?php echo $equip; ?>
                    </span>
                    <div class="expclear"></div>
                <?php endif; ?>
                </span>

                <?php if ($params->get('useshowhide')): ?>
                <div class="expvert_clear"></div>
                <?php if($params->get('jq_chained',0)):?>
                    <button id="expsearchtext<?php echo $moduleid; ?>" type="button" class="btn btn-mini" data-toggle="button" onclick="javascript:jqexptoggle('expshowhide<?php echo $moduleid; ?>','expsearchtext<?php echo $moduleid; ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_MORE_TEXT'); ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_CLOSE_TEXT'); ?>');"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_MORE_TEXT'); ?></button>
                <?php else: ?>
                    <button id="expsearchtext<?php echo $moduleid; ?>" type="button" class="btn btn-mini" data-toggle="button" onclick="javascript:exptoggle('expshowhide<?php echo $moduleid; ?>','expsearchtext<?php echo $moduleid; ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_MORE_TEXT'); ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_CLOSE_TEXT'); ?>');"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_MORE_TEXT'); ?></button>
                <?php endif; ?> 
                    <br /><br />
                    <div class="expclear"></div>
                <?php endif; ?>
                <div class="expvert_clear"></div>
            <button id="expbutton<?php echo $moduleid; ?>" type="submit" name="Submit" class="button btn btn-primary expjqsearchbtn" data-loading-text="<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_BTNTEXT_DESC'); ?>">
                <?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_HOR_SEARCH_TEXT') ?> <i class="icon-search icon-white"></i>
              </button>
                <?php if($params->get('country')):?>
                    <input type="hidden" name="country" value="<?php echo $params->get('country'); ?>" />
                <?php endif;?>
                <input type="hidden" name="location" value="<?php echo $params->get('location'); ?>" />
                <input type="hidden" name="radvar" value="<?php echo $params->get('radvar'); ?>" />
                <input type="hidden" name="radval" value="<?php echo $params->get('radval'); ?>" />
                <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </ul>
        </fieldset>
    </form>
    <?php if($params->get('jq_chosen',0) && $params->get('jq_chained',0)):?>
        <script type="text/javascript">
            jQuery(".chzn-expselectsearchhor").chosen();
        </script>
    <?php endif;?>
</div>