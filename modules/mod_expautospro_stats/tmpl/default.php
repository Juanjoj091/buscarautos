<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// no direct access
defined('_JEXEC') or die;
//print_r($expparams->get('c_admanager_fpcat_allowcat'));
//print_r($expparams->get('c_admanager_fpcat_catlinkto'));
//print_r($list);
$document = JFactory::getDocument();

$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_stats/css/sitemapstyler.css');
$count_all = '';
?>
<ul id="expstats" class="expstats-module <?php echo $moduleclass_sfx; ?>">
<?php
require JModuleHelper::getLayoutPath('mod_expautospro_stats', $params->get('layout', 'default').'_items');
?></ul>
<?php if($params->get('showtotal')):?>
<hr />
<span class='modexpstats_span'><?php echo JText::_('MOD_EXPAUTOSPRO_STATS_TOTAL_TEXT');?></span>&nbsp;&#58;&nbsp;<?php echo $count_all;?>
<?php endif;?>
