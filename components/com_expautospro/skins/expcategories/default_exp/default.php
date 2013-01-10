<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

defined('_JEXEC') or die;

$params_file = JPATH_COMPONENT . '/skins/expcategories/default_exp/parameters/params.php';
if (file_exists($params_file))
    require_once $params_file;
ExpAutosProHelper::expskin_lang('expcategories', 'default_exp');

$class = ' class="first"';
$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expcategories/default_exp/css/default_exp.css');
$expcat = '';
if((int)$this->parent->id)
    $expcat = '&amp;catid='.(int)$this->parent->id;
$linkall = JRoute::_("index.php?option=com_expautospro&amp;view=explist".$expcat."&amp;Itemid=" . (int) $expitem);
$linkadd = JRoute::_("index.php?option=com_expautospro&amp;view=expadd&amp;Itemid=" . (int) $expitem);
$showcat = $this->expparams->get('c_admanager_fpcat_showcat');
$tlmoduleposition = $this->expparams->get('c_admanager_fpcat_tmpnameleft');
$trmoduleposition = $this->expparams->get('c_admanager_fpcat_tmpnameright');
$tlmiddlemoduleposition = $this->expparams->get('c_admanager_fpcat_tmpnamemiddle');
$bmoduleposition = $this->expparams->get('c_admanager_fpcat_bmpname');
$countcat = ExpAutosProHelper::getExpcount('categories','','',0,'');
$columnwidth = 100 / $countcat - 3;
?>
<div class="expautospro_topmodule_double">
    <?php echo ExpAutosProHelper::load_module_position($tlmoduleposition, $this->expparams->get('c_admanager_fpcat_tmpstyleleft')); ?>
    <div class="expautospro_clear"></div>
</div>
<div class="expautospro_topmodule">
    <div class="expautospro_topmodule_pos">
        <?php echo ExpAutosProHelper::load_module_position($tlmiddlemoduleposition, $this->expparams->get('c_admanager_fpcat_tmpstylemiddle')); ?>
    </div>
    <div class="expautospro_clear"></div>
</div>

<!-- Skins Module Position !-->
<?php if ($this->expparams->get('c_admanager_fpcat_showskin')): ?>
    <div id="expskins_module">
        <?php
        $expmodparam = array('folder' => $this->expskins);
        echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
        ?>
    </div>
    <div class="expautospro_clear"></div>
<?php endif; ?>

<div id="expautospro">
    <div class="categories-list<?php echo $this->pageclass_sfx; ?>">
        <?php if ($this->params->get('show_page_heading', 1)) : ?>
            <h1>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
        <?php endif; ?>
        <?php if ($this->params->get('show_base_description')) : ?>
            <?php //If there is a description in the menu parameters use that; ?>
            <?php if ($this->params->get('categories_description')) : ?>
                <div class="category-desc base-desc">
                    <?php echo JHtml::_('content.prepare', $this->params->get('categories_description'), '', 'com_expautospro.categories'); ?>
                </div>
            <?php else: ?>
                <?php //Otherwise get one from the database if it exists. ?>
                <?php if ($this->parent->description) : ?>
                    <div class="category-desc base-desc">
                        <?php echo JHtml::_('content.prepare', $this->parent->description, '', 'com_expautospro.categories'); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($this->expparams->get('c_admanager_fpcat_showadbutton')): ?>
            <div id="expfp_addbutton">
                <a href="<?php echo $linkadd; ?>" ><?php echo JText::_('COM_EXPAUTOSPRO_FRP_ADDLISTING_TEXT'); ?></a>
            </div>
        <?php endif; ?>
        <?php
        echo $this->loadTemplate('items');
        ?>

    </div>
</div>
<div class="expautospro_clear"></div>
<?php if ($this->expparams->get('c_admanager_fpcat_showviewall')): ?>
    <div id="expautospro_view">
        <a href="<?php echo $linkall; ?>" ><?php echo JText::_('COM_EXPAUTOSPRO_FRP_VIEWALL_TEXT'); ?></a>
    </div>
<?php endif; ?>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_fpcat_bmpstyle')); ?>
    </div>
<?php endif; ?>

