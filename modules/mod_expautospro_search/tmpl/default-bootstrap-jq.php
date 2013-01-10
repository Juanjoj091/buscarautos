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
if ($exp_sef) {
    $action_link = JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;Itemid=' . $itemid);
} else {
    $action_link = JRoute::_('index.php');
}
$moduleid = $module->id;
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
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_search/css/mod_expautospro_search_bootstrap.css');
//print_r($list);
?>
<div id="testimg"></div>
<div id="modexpautospro_search<?php echo $moduleid ?>" class="modexpautospro_search expsearch <?php echo $moduleclass_sfx ?>">
    <form action="<?php echo $action_link; ?>#expautospro" method="get">
        <?php if (!$exp_sef): ?>
            <input type="hidden" name="option" value="com_expautospro" />
            <input type="hidden" name="view" value="explist" />
        <?php endif; ?>
        <fieldset class="expsutospro_search_fieldset">
            <?php if ($params->get('catid')): ?>
                <input type="hidden" name="catid" value="<?php echo $params->get('catid'); ?>" />
            <?php endif; ?>
            <?php if ($params->get('useid')): ?>
                <p class="expautospro_id">
                    <label for="modexpsearch_id"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_EXPID_TEXT'); ?></label>
                    <?php echo $expid; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('usestocknum')): ?>
                <p class="expautospro_stocknum">
                    <label for="modexpsearch_stocknum"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_STOCNUMBER_TEXT'); ?></label>
                    <?php echo $stocknum; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('usevincode')): ?>
                <p class="expautospro_vincode">
                    <label for="modexpsearch_vincode"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_VINCODE_TEXT'); ?></label>
                    <?php echo $vincode; ?>
                </p>
            <?php endif; ?>
            <?php if (!$params->get('catid')): ?>
                <p class="expautospro_search">
                    <label for="modexpsearch_cats"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_CATEGORIES_TEXT'); ?></label>
                    <?php echo $list; ?>
                </p>
            <?php endif; ?>
            <?php if($params->get('usemakes')):?>
            <p class="expautospro_search">
                <label for="modexpsearch_makes"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_MAKES_TEXT'); ?></label>
                <?php echo $make; ?>
            </p>
            <?php endif; ?>
            <?php if($params->get('usemodels')):?>
            <p class="expautospro_search">
                <label for="modexpsearch_models"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_MODELS_TEXT'); ?></label>
                <?php echo $model; ?>
            </p>
            <?php endif; ?>
            <div class="expautos_search_advsearchbox <?php echo $hideclass; ?>" id="expshowhide<?php echo $moduleid; ?>" >
                <?php if ($params->get('usesmodel')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_specificmodel"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_SPICIFICMODELS_TEXT'); ?></label>
                        <?php echo $specificmodel; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usecondition')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_condition"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_CONDITION_TEXT'); ?></label>
                        <?php echo $condition; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usebodytype')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_bodytype"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_BODYTYPE_TEXT'); ?></label>
                        <?php echo $bodytype; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useextrafield1')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_extrafield1"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_EXTRAFIELD1_TEXT'); ?></label>
                        <?php echo $extrafield1; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useextrafield2')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_extrafield2"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_EXTRAFIELD2_TEXT'); ?></label>
                        <?php echo $extrafield2; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useextrafield3')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_extrafield3"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_EXTRAFIELD3_TEXT'); ?></label>
                        <?php echo $extrafield3; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usecolor')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_color"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_COLOR_TEXT'); ?></label>
                        <?php echo $color; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useyear')): ?>
                    <p class="expautospro_search expdouble">
                        <span class="expdouble_first">
                            <label for="modexpsearch_yearfrom" class="expyear_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_YEARFROM_TEXT'); ?></label>
                            <?php echo $yearfrom; ?>
                        </span>
                        <span>
                            <label for="modexpsearch_yearto" class="expyear_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_YEARTO_TEXT'); ?></label>
                            <?php echo $yearto; ?>
                        </span>
                    </p>
                    <div class="expclear"></div>
                <?php endif; ?>
                <?php if ($params->get('useprice')): ?>
                    <p class="expautospro_search expdouble">
                        <span class="expdouble_first">
                            <label for="modexpsearch_pricefrom" class="expdouble_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_PRICEFROM_TEXT'); ?></label>
                            <?php echo $pricefrom; ?>
                        </span>
                        <span>
                            <label for="modexpsearch_priceto" class="expdouble_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_PRICETO_TEXT'); ?></label>
                            <?php echo $priceto; ?>
                        </span>
                    </p>
                    <div class="expclear"></div>
                <?php endif; ?>
                <?php if ($params->get('usemileage')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_mileage"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_MILEAGE_TEXT'); ?></label>
                        <?php echo $mileage; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usefuel')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_fuel"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_FUEL_TEXT'); ?></label>
                        <?php echo $fuel; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usedrive')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_drive"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_DRIVE_TEXT'); ?></label>
                        <?php echo $drive; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usetrans')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_trans"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_TRANSMISSION_TEXT'); ?></label>
                        <?php echo $trans; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usecountry')): ?>
                    <?php if (!$params->get('country')): ?>
                        <p class="expautospro_search">
                            <label for="modexpsearch_country"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_COUNTRY_TEXT'); ?></label>
                            <?php echo $country; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($params->get('useexpstate')): ?>
                        <p class="expautospro_search">
                            <label for="modexpsearch_expstate"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_EXPSTATE_TEXT'); ?></label>
                            <?php echo $expstate; ?>
                        </p>
                        <?php if ($params->get('usecity')): ?>
                            <p class="expautospro_search">
                                <label for="modexpsearch_city"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_CITY_TEXT'); ?></label>
                                <?php echo $city; ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($params->get('useuser')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_user"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_USER_TEXT'); ?></label>
                        <?php echo $expuser; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usezipcode')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_zipcode"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_ZIPCODE_TEXT'); ?></label>
                        <?php echo $zipcode; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useradius')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_radius"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_FORM_RADIUS_TEXT'); ?></label>
                        <?php echo $radius; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useageof')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_ageof"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_SELECT_AGEOF_TEXT'); ?></label>
                        <?php echo $ageof; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('usesortby')): ?>
                    <p class="expautospro_search">
                        <label for="modexpsearch_sortby"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_SELECT_SORTBY_TEXT'); ?></label>
                        <?php echo $sortby; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useimg')): ?>
                    <p class="expautospro_search searchcheckbox">
                        <label for="modexpsearch_img"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_SELECT_IMG_TEXT'); ?></label>
                        <?php echo $img; ?>
                    </p>
                    <div class="expclear"></div>
                <?php endif; ?>
                <?php if ($params->get('usewsolid')): ?>
                    <p class="expautospro_search searchcheckbox">
                        <label for="modexpsearch_wsolid"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_SELECT_WSOLID_TEXT'); ?></label>
                        <?php echo $wsolid; ?>
                    <div class="expclear"></div>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useequipment')): ?>
                    <span id="expequip<?php echo $moduleid; ?>">
                    <?php echo $equip; ?>
                    </span>
                    <div class="expclear"></div>
                <?php endif; ?>
                    
            </div>
            <?php if ($params->get('useshowhide',1)): ?>
                <br />
                <?php if($params->get('jq_chained',0)):?>
                    <button id="expsearchtext<?php echo $moduleid; ?>" type="button" class="btn btn-mini" data-toggle="button" onclick="javascript:jqexptoggle('expshowhide<?php echo $moduleid; ?>','expsearchtext<?php echo $moduleid; ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_MORE_TEXT'); ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_CLOSE_TEXT'); ?>');"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_MORE_TEXT'); ?></button>
                <?php else: ?>
                    <button id="expsearchtext<?php echo $moduleid; ?>" type="button" class="btn btn-mini" data-toggle="button" onclick="javascript:exptoggle('expshowhide<?php echo $moduleid; ?>','expsearchtext<?php echo $moduleid; ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_MORE_TEXT'); ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_CLOSE_TEXT'); ?>');"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_MORE_TEXT'); ?></button>
                <?php endif; ?> 
                <br /><br />
                <div class="expclear"></div>
            <?php endif; ?>
            <button id="expbutton<?php echo $moduleid; ?>" type="submit" name="Submit" class="button btn btn-primary expjqsearchbtn" data-loading-text="<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_BTNTEXT_DESC'); ?>">
                <?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_SEARCH_TEXT') ?> <i class="icon-search icon-white"></i>
              </button>
            <?php if ($params->get('country')): ?>
                <input type="hidden" name="country" value="<?php echo $params->get('country'); ?>" />
            <?php endif; ?>
            <input type="hidden" name="location" value="<?php echo $params->get('location'); ?>" />
            <input type="hidden" name="radvar" value="<?php echo $params->get('radvar'); ?>" />
            <input type="hidden" name="radval" value="<?php echo $params->get('radval'); ?>" />
            <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </form>
    <?php if($params->get('jq_chosen',0) && $params->get('jq_chained',0)):?>
        <script type="text/javascript">
            jQuery(".chzn-expselectsearch").chosen();
        </script>
    <?php endif;?>
</div>