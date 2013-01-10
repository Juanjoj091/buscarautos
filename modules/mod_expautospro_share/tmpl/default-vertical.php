<?php

/****************************************************************************************\
 **   @name		EXP Autos Share                                                 **
 **   @package          Joomla 1.6/1.7/2.5                                              **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2012  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		GNU General Public License version 2 or later                   **
 \****************************************************************************************/

//no direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_share/css/mod_expautospro_share_vert.css');
$document->addScript(JURI::root() . 'modules/mod_expautospro_share/js/vertical/share42.js');
?>
<style>
    
#modexpshare #share42 {
  margin-left: -<?php echo $params->get('icons_left');?>px; /* left margin */
}
</style>
<div id="modexpshare" class="modexpshare<?php echo $moduleclass_sfx ?>">
<div class="share42init" data-url="<?php function getLink() { return $this->link; } ?>" data-title="<?php function getTitle() { return $this->title; } ?>"></div>
<script type="text/javascript">share42('<?php echo JURI::root() . "modules/mod_expautospro_share/images/vertical/" ;?>',<?php echo $params->get('icons_top');?>,<?php echo $params->get('icons_top_scroll');?>)</script>
</div>