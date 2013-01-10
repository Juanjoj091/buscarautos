<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

//no direct access
defined('_JEXEC') or die;

$moduleid = $module->id;
$app = JFactory::getApplication();
$exp_sef = $app->getCfg('sef');
if($exp_sef){
    $action_link=JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;Itemid='.$itemid.'#modexpautospro_listfilter'.$moduleid);
}else{
    $action_link=JRoute::_('index.php#modexpautospro_listfilter'.$moduleid);
}
$moduleid = $module->id;
$document = JFactory::getDocument();
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchchained.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchtoggle.js');
if($params->get('usechosen', 0)){
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/chosen.jquery.min.js');
    $document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/chosen.css');
}
if($params->get('useshowhide')) {
    $hideclass = "expdisplay_none";
}
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_listfilter/css/mod_expautospro_listfilter.css');
?>
<style>
#modexpautospro_listfilter<?php echo $moduleid ?> ul li{
    list-style: none;
    margin: 0px 10px 0px;
}
</style>
<div id="modexpautospro_listfilter<?php echo $moduleid ?>" class="modexpautospro_listfilter expsearch <?php echo $moduleclass_sfx ?>">
    <form action="<?php echo $action_link;?>" method="get">
	<?php if(!$exp_sef):?>
            <input type="hidden" name="option" value="com_expautospro" />
            <input type="hidden" name="view" value="explist" />
        <?php endif;?>
        <?php if(!$params->get('usecat') && $params->get('defcat')):?>
            <input type="hidden" name="catid" value="<?php echo $params->get('defcat');?>" />
        <?php endif;?>
        <fieldset class="expsutospro_search_fieldset">
            <ul>
                <?php if($params->get('usecat')):?>
                    <li class="expautospro_listsearch">
                        <label for="modexpsearch_cats"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_CATEGORIES_TEXT'); ?></label>
                        <?php echo $category; ?>
                    </li>
                <?php endif;?>
                <?php if ($params->get('usemakes')): ?>
                <li class="expautospro_listsearch">
                    <label for="modexpsearch_makes"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_MAKES_TEXT'); ?></label>
                    <?php echo $make; ?>
                </li>
                <?php endif; ?>
                <?php if ($params->get('usemodels')): ?>
                <li class="expautospro_listsearch">
                    <label for="modexpsearch_models"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_MODELS_TEXT'); ?></label>
                    <?php echo $model; ?>
                </li>
                <?php endif; ?>
                <span class="expautos_search_advsearchbox <?php echo $hideclass; ?>" id="expshowhide<?php echo $moduleid; ?>" >
                    <?php if ($params->get('usecondition')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_condition"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_CONDITION_TEXT'); ?></label>
                            <?php echo $condition; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usebodytype')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_bodytype"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_BODYTYPE_TEXT'); ?></label>
                            <?php echo $bodytype; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usetrans')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_trans"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_TRANSMISSION_TEXT'); ?></label>
                            <?php echo $trans; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usefuel')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_fuel"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_FUEL_TEXT'); ?></label>
                            <?php echo $fuel; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usedrive')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_drive"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_DRIVE_TEXT'); ?></label>
                            <?php echo $drive; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useageof')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_ageof"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_SELECT_AGEOF_TEXT'); ?></label>
                            <?php echo $ageof; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useyear')): ?>
                        <li class="expautospro_listsearch">
                                <label for="modexpsearch_yearfrom" class="expyear_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_YEARFROM_TEXT'); ?></label>
                                <?php echo $yearfrom; ?>
                        </li>
                        <li class="expautospro_listsearch">
                                <label for="modexpsearch_yearto" class="expyear_label"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_YEARTO_TEXT'); ?></label>
                                <?php echo $yearto; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usesortby')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_sortby"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_SELECT_SORTBY_TEXT'); ?></label>
                            <?php echo $sortby; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('usecountry')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_country"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_COUNTRY_TEXT'); ?></label>
                            <?php echo $country; ?>
                        </li>
                        <?php if ($params->get('useexpstate')): ?>
                            <li class="expautospro_listsearch">
                                <label for="modexpsearch_expstate"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_EXPSTATE_TEXT'); ?></label>
                                <?php echo $expstate; ?>
                            </li>
                            <?php if ($params->get('usecity')): ?>
                                <li class="expautospro_listsearch">
                                    <label for="modexpsearch_city"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_CITY_TEXT'); ?></label>
                                    <?php echo $city; ?>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($params->get('useextrafield1')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_extrafield1"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_EXTRAFIELD1_TEXT'); ?></label>
                            <?php echo $extrafield1; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useextrafield2')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_extrafield2"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_EXTRAFIELD2_TEXT'); ?></label>
                            <?php echo $extrafield2; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($params->get('useextrafield3')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_extrafield3"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_EXTRAFIELD3_TEXT'); ?></label>
                            <?php echo $extrafield3; ?>
                        </li>
                    <?php endif; ?>
                <?php if ($params->get('usecolor')): ?>
                    <li class="expautospro_listsearch">
                        <label for="modexpsearch_color"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_COLOR_TEXT'); ?></label>
                        <?php echo $color; ?>
                    </li>
                <?php endif; ?>
                    <?php if ($params->get('useuser')): ?>
                        <li class="expautospro_listsearch">
                            <label for="modexpsearch_user"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_FORM_USER_TEXT'); ?></label>
                            <?php echo $expuser; ?>
                        </li>
                    <?php endif; ?>
                </span>

                <?php if ($params->get('useshowhide')): ?>
                <div class="expvert_clear"></div>
                     <button id="expsearchtext<?php echo $moduleid; ?>" type="button" class="btn btn-mini" data-toggle="button" onclick="javascript:exptoggle('expshowhide<?php echo $moduleid; ?>','expsearchtext<?php echo $moduleid; ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_MORE_TEXT'); ?>','<?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_CLOSE_TEXT'); ?>');"><?php echo JText::_('MOD_EXPAUTOSPRO_SEARCH_LISTFILTER_MORE_TEXT'); ?></button>
                    <br /><br />
                    <div class="expclear"></div>
                <?php endif; ?>
                <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </ul>
        </fieldset>
    </form>
</div>
<?php if($params->get('usechosen', 0)):?>
<script type="text/javascript">
        jQuery(".chzn-explsfselect").chosen();
</script>
<?php endif;?>