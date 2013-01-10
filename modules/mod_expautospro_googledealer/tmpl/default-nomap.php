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

$moduleid = $module->id;

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_googledealer/css/mod_expautospro_googledealer.css');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchchained.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchtoggle.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expdownloadxml.js');
$script = '';
$script .= "
    function expdealerchg2(){
    var expdllink = '';
    expdllink += '&expgdcountry='+document.getElementById('country".$moduleid."').value;
    if(".$params->get('useexpstate')."){
    expdllink += '&expgdstate='+document.getElementById('expstate".$moduleid."').value;
    if(".$params->get('usecity')."){
    expdllink += '&expgdcity='+document.getElementById('city".$moduleid."').value;
    }
    }
    if(".$params->get('showdealersfield')."){
    expdllink += '&expgduserid='+document.getElementById('userid".$moduleid."').value;
    }
    if(".$params->get('usezipcode')."){
    expdllink += '&expgdzipcode='+document.getElementById('modexpsearch_zipcode".$moduleid."').value;
    }
     dealerlink = expdllink;
     dealerinitializeads2();
    }

    function dealerinitializeads2() {

      // Change this depending on the name of your PHP file
      downloadUrl('index.php?option=com_expautospro&view=expdealerlist&format=ajax'+dealerlink, function(data) {
        var poiJson = eval(data.responseText);
        var countads = poiJson.length;
        var testtt = document.getElementById('expbutton".$moduleid."').value = '';
         document.getElementById('expbutton".$moduleid."').value = '".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_SEARCH_TEXT')."' + ' ('+ countads + ') ';
      });
    }
    function expgdfindAddress2(selTag) {
    }
            ";

$document->addScriptDeclaration($script);

$app = JFactory::getApplication();
$exp_sef = $app->getCfg('sef');
if ($exp_sef) {
    $action_link = JRoute::_('index.php?option=com_expautospro&amp;view=expdealerlist&amp;Itemid=' . $itemid);
} else {
    $action_link = JRoute::_('index.php');
}
?>
<div id="modexpgoogledealers<?php echo $module->id; ?>" class="modexpgoogledealers expgoogledealers<?php echo $moduleclass_sfx; ?>">

    <div class="expgmapdealersnomap">
        <form action="<?php echo $action_link; ?>" method="get">
            <?php if (!$exp_sef): ?>
                <input type="hidden" name="option" value="com_expautospro" />
                <input type="hidden" name="view" value="expdealerlist" />
            <?php endif; ?>
            <?php if ($params->get('usecountry')): ?>
                <?php if (!$params->get('country')): ?>
                    <p class="expautospro_gmdealers">
                        <label for="modexpsearch_country"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_COUNTRY_TEXT'); ?></label>
                        <?php echo $country; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useexpstate')): ?>
                    <p class="expautospro_gmdealers">
                        <label for="modexpsearch_expstate"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_EXPSTATE_TEXT'); ?></label>
                        <?php echo $expstate; ?>
                    </p>
                    <?php if ($params->get('usecity')): ?>
                        <p class="expautospro_gmdealers">
                            <label for="modexpsearch_city"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_CITY_TEXT'); ?></label>
                            <?php echo $city; ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($params->get('showdealersfield')): ?>
                <p class="expautospro_gmdealers">
                    <label for="modexpsearch_dealers"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_DEALERS_TEXT'); ?></label>
                    <?php echo $dealers; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('usezipcode')): ?>
                <p class="expautospro_gmdealers">
                    <label for="modexpsearch_zipcode"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_ZIPCODE_TEXT'); ?></label>
                    <?php echo $zipcode; ?>
                </p>
            <?php endif; ?>
            <input id="expbutton<?php echo $moduleid; ?>" type="submit" name="Submit" class="button" value="<?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_SEARCH_TEXT') ?>" />
            <div class="expautos_images_clear"></div>
            <?php if ($params->get('country')): ?>
                <input type="hidden" name="country" id="country<?php echo $moduleid; ?>" value="<?php echo $params->get('country'); ?>" />
            <?php endif; ?>
            <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
    <div class="expautos_images_clear"></div>
</div>