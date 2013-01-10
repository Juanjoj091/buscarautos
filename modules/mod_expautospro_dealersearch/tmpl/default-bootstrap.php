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
    $action_link=JRoute::_('index.php?option=com_expautospro&amp;view=expdealerlist&amp;Itemid='.$itemid);
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
}
if ($params->get('jq_chosen',0) && $params->get('jq_chained',0)) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/chosen.jquery.min.js');
    $document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/chosen.css');
}
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_dealersearch/css/mod_expautospro_dealersearch_bootstrap.css');

//print_r($params);
?>
<div id="modexpautospro_dealersearch<?php echo $moduleid ?>" class="dealersearch<?php echo $moduleclass_sfx ?>">
    <form action="<?php echo $action_link;?>" method="get">
        <div class="expsearch_dealer_margin">
	<?php if(!$exp_sef):?>
            <input type="hidden" name="option" value="com_expautospro" />
            <input type="hidden" name="view" value="expdealerlist" />
        <?php endif;?>
        <?php if($params->get('usecountry')):?>
                <?php if(!$params->get('country')):?>
                    <p class="expautospro_dealersearch">
                        <label for="modexpsearch_country"><?php echo JText::_('MOD_EXPAUTOSPRO_DEALERSEARCH_FORM_COUNTRY_TEXT'); ?></label>
                        <?php echo $country; ?>
                    </p>
                <?php endif; ?>
                <?php if($params->get('useexpstate')):?>
                    <p class="expautospro_dealersearch">
                        <label for="modexpsearch_expstate"><?php echo JText::_('MOD_EXPAUTOSPRO_DEALERSEARCH_FORM_EXPSTATE_TEXT'); ?></label>
                        <?php echo $expstate; ?>
                    </p>
                    <?php if($params->get('usecity')):?>
                        <p class="expautospro_dealersearch">
                            <label for="modexpsearch_city"><?php echo JText::_('MOD_EXPAUTOSPRO_DEALERSEARCH_FORM_CITY_TEXT'); ?></label>
                            <?php echo $city; ?>
                        </p>
                    <?php endif;?>
                <?php endif;?>
            <?php endif;?>
            <?php if($params->get('useuser')):?>
                <p class="expautospro_dealersearch">
                    <label for="modexpsearch_user"><?php echo JText::_('MOD_EXPAUTOSPRO_DEALERSEARCH_FORM_USER_TEXT'); ?></label>
                    <?php echo $expuser; ?>
                </p>
            <?php endif;?>
            <?php if($params->get('usezipcode')):?>
                <p class="expautospro_dealersearch">
                    <label for="modexpsearch_zipcode"><?php echo JText::_('MOD_EXPAUTOSPRO_DEALERSEARCH_FORM_ZIPCODE_TEXT'); ?></label>
                    <?php echo $zipcode; ?>
                </p>
            <?php endif;?>
            <button id="expbutton<?php echo $moduleid; ?>" type="submit" name="Submit" class="button btn btn-primary" data-loading-text="<?php echo JText::_('MOD_EXPAUTOSPRO_DEALERSEARCH_BTNTEXT_DESC'); ?>">
                <?php echo JText::_('MOD_EXPAUTOSPRO_DEALERSEARCH_SEARCH_TEXT') ?> <i class="icon-search icon-white"></i>
            </button>
            <?php if($params->get('country')):?>
            <input type="hidden" name="country" value="<?php echo $params->get('country'); ?>" />
        <?php endif;?>
        <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
	<?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
    <?php if($params->get('jq_chosen',0) && $params->get('jq_chained',0)):?>
        <script type="text/javascript">
            jQuery(".chzn-expselectdealersearch").chosen();
        </script>
    <?php endif;?>
</div>