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
require_once JPATH_ROOT . '/components/com_expautospro/helpers/expparams.php';
$document = JFactory::getDocument();
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expshortlist.js'); 
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_shortlist/css/mod_expautospro_shortlist_bootstrap.css');

$expgetcookie = JRequest::getVar('expshortlist', null,  $hash= 'COOKIE');
?>
<div id="mod_expmodshortlist">
    <div id="expmodshortlist" class="expshortlistcl <?php echo $moduleclass_sfx ?>">
      <?php if($expgetcookie):?>
        <?php echo ExpAutosProExpparams::createAdd($expgetcookie);?>
      <?php endif;?>
    </div>
</div>