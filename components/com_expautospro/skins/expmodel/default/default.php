<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

defined('_JEXEC') or die;

$params_file = JPATH_COMPONENT . '/skins/expmodel/default/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('expmodel','default');

$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expmodel/default/css/default.css');
if($this->items){
    $catidlink=$this->items[0]->categid;
    $makeidlink=$this->items[0]->makeid;
    $linkall = JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;catid='.(int)$catidlink.'&amp;makeid='.(int)$makeidlink."&amp;Itemid=".(int) $expitem);
}
$countcolumn = $this->expparams->get('c_admanager_mdpage_column');
$topmoduleposition = $this->expparams->get('c_admanager_mdpage_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_mdpage_bmpname');
?>

<div class="expautospro_topmodule">
    <div class="expautospro_topmodule_pos">
        <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_mdpage_tmpstyle')); ?>
    </div>
    <div class="expautospro_clear"></div>
</div>

<!-- Skins Module Position !-->
<?php if($this->expparams->get('c_admanager_mdpage_showskin')):?>
<div id="expskins_module">
    <?php
    $expmodparam = array('folder' => $this->expskins);
    echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
    ?>
</div>
<div class="expautospro_clear"></div>
<?php endif; ?>

<div id="expautospro">
    <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_MODELS_TEXT') ?></h2>
    <center>
    <table class="expautos_make_table">
        <?php
        $count_ads = 0;
        foreach ($this->items as $i => $item) :
            $countnum = ExpAutosProHelper::getExpcount('admanager', 'model', $item->id,1);
            if ($this->expparams->get('c_admanager_mdpage_showempty') || $countnum > 0):
                $link = JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;catid='.(int)$catidlink.'&amp;makeid='.(int)$makeidlink.'&amp;modelid=' . (int) $item->id.'&amp;Itemid='.(int) $expitem);
                //print_r($item);
                if (($count_ads % $countcolumn) == 0) {
                    echo '</tr>';
                    echo '<tr>';
                }
                ?>
                <td valign="top">
                    <?php if ($item->image): ?>
                        <img src="<?php echo JURI::root() . $item->image; ?>" alt="<?php echo $item->name; ?>" class="expautospromp_imgleft"/>
                    <?php endif; ?>
                    <div class="expmp_right">
                        <h3 class="expmp_h3">
                            <a href="<?php echo $link; ?>">
                                <?php echo $item->name; ?>
                            </a>
                            <?php if ($this->expparams->get('c_admanager_mdpage_showcount')): ?>
                                <span>
                                    &#40;<?php echo $countnum; ?>&#41;
                                </span>
                            <?php endif; ?>
                        </h3>
                        <?php echo $item->description; ?>
                    </div>
                </td>

            <?php
            $count_ads++;
            endif; ?>
            <?php
            $metaexp[] = trim($item->metadesc);
            $metakeyexp[] = trim($item->metakey);
        endforeach;
        ?>
    </table>
    </center>
</div>
<div class="expautospro_clear"></div>
<?php if ($this->expparams->get('c_admanager_mdpage_showviewall') && $this->items): ?>
    <div id="expautospro_view">
        <a href="<?php echo $linkall; ?>" ><?php echo JText::_('COM_EXPAUTOSPRO_FRP_VIEWALL_TEXT'); ?></a>
    </div>
<?php endif; ?>
    <?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
    <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_mdpage_bmpstyle')); ?>
    </div>
<?php endif; ?>
<?php
/* insert meta */
if (isset($metaexp)) {
    $metaexp = implode(".", $metaexp);
    $this->document->setDescription($metaexp);
}
if (isset($metakeyexp)) {
    $metakeyexp = implode(",", $metakeyexp);
    $this->document->setMetadata('keywords', $metakeyexp);
}
?>

