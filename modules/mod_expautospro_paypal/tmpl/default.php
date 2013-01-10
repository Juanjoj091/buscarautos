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
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_paypal/css/mod_expautospro_paypal.css');
$modules = JModuleHelper::getModule('expautospro_paypal');
//print_r($modules);
?>
<div class="exppaypal <?php echo $moduleclass_sfx ?>">
    <form action="<?php echo $params->get('form_action');?>" method="post">
        <input name="cmd" value="_xclick" type="hidden"/>
        <input name="business" value="<?php echo $params->get('payment_email');?>" type="hidden"/>
        <input name="item_name" value="<?php echo $modules->content['groupname']; ?>" type="hidden"/>
        <input name="custom" value="<?php echo $modules->content['item_special']; ?>,<?php echo $modules->content['itm_id']; ?>,<?php echo $modules->content['userid']; ?>" type="hidden"/>
        <input name="item_number" value="<?php echo $modules->content['itemnumber']; ?>" type="hidden"/>
        <input name="no_shipping" value="1" type="hidden"/>
        <input name="return" value="<?php echo JURI::root(); ?><?php echo $params->get('return_url');?>&userid=<?php echo $modules->content['userid']; ?>&groupid=<?php echo $modules->content['itm_id']; ?>" type="hidden"/>
        <input type="hidden" name="notify_url" value="<?php echo JURI::root(); ?><?php echo $params->get('notify_url');?>"/>
        <input name="currency_code" value="<?php echo $modules->content['curcode']; ?>" type="hidden"/>
        <input name="tax" value="0" type="hidden">
        <input name="amount" value="<?php echo $modules->content['price']; ?>" type="hidden"/>
        <input type="image" src="<?php echo $params->get('image_url');?>" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/>
    </form>
</div>