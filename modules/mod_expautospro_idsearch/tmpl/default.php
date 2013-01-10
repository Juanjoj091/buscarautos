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

$app = JFactory::getApplication();
$exp_sef = $app->getCfg('sef');
if($exp_sef){
    $action_link=JRoute::_('index.php?option=com_expautospro&amp;view=expdetail&amp;Itemid='.$itemid);
}else{
    $action_link=JRoute::_('index.php');
}

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_idsearch/css/mod_expautospro_idsearch.css');
//print_r($params);
?>
<div id="modexpidsearch" class="expidsearch<?php echo $moduleclass_sfx ?>">
    <form action="<?php echo $action_link;?>" method="get">
	<?php if(!$exp_sef):?>
            <input type="hidden" name="option" value="com_expautospro" />
            <input type="hidden" name="view" value="expdetail" />
        <?php endif;?>
        <fieldset class="expsutospro_keyword_fieldset">
            <p class="expautospro_search">
                <label for="modidsearch_search"></label>
                <?php echo $expid;?>
            </p>
        </fieldset>
        <input type="submit" name="Submit" class="button" value="<?php echo JText::_('MOD_EXPAUTOSPRO_IDSEARCH_SEARCH_TEXT') ?>" />
        <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
	<?php echo JHtml::_('form.token'); ?>
    </form>
</div>