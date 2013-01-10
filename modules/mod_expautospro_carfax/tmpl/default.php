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
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_carfax/css/mod_expautospro_carfax.css');

//print_r($expcarfax);
?>
<div id="modexpcarfax" class="expcarfax <?php echo $moduleclass_sfx ?>">
    <?php if($expcarfax):?>
        <span><a href="http://www.carfax.com/cfm/check_order.cfm?vin=<?php echo $expcarfax;?>" target="_blank"><img src="<?php echo JURI::root();?>modules/mod_expautospro_carfax/images/carfaxlogo.jpg" /></a></span>
    <?php else:?>
        <?php echo JText::_('MOD_EXPAUTOSPRO_CARFAX_NOHAVE_VINCODE_TEXT') ?>
    <?php endif;?>
</div>