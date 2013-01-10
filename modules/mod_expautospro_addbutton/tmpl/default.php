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

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_addbutton/css/mod_expautospro_addbutton.css');
$moduleid = $module->id;
?>
<div id="modexpaddbutton<?php echo $moduleid;?>" class="addbuttoncls <?php echo $moduleclass_sfx;?>">
    <a class="expaddbutton expaddbutton-green" href="<?php echo JRoute::_("index.php?option=com_expautospro&amp;view=expadd&amp;Itemid=" . (int) $itemid);?>">
        <?php echo JText::_('MOD_EXPAUTOSPRO_ADDBUTTON_BUTTON_TEXT');?>
    </a>
</div>