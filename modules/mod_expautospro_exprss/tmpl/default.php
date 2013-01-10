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
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_exprss/css/mod_expautospro_exprss.css');
$moduleid = $module->id;
?>
<div id="modexprss<?php echo $moduleid;?>" class="exprss<?php echo $moduleclass_sfx;?>">
    <a href="<?php echo JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;format=feed&amp;catid='.(int)$params->get("catid").'&amp;userid='.(int)$params->get("expuser").'&amp;special='.(int)$params->get("special").'&amp;imgrss='.(int)$params->get("imgrss").'&amp;limitrss='.(int)$params->get("exprsslimit").'&amp;Itemid='.$itemid);?>" target="_blank">
        <img src="<?php echo JURI::root();?>modules/mod_expautospro_exprss/images/009.png" />
    </a>
</div>