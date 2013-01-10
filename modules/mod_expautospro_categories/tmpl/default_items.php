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
$expallowcat = $expparams->get('c_admanager_fpcat_allowcat');
//$explinkto = $expparams->get('c_admanager_fpcat_catlinkto');
$only_allowed = $params->get('only_allowed',1);
$expcatid = (int) JRequest::getInt('catid', 0);
foreach ($list as $item) :
$img_link = $item->getParams()->get('image');
$cat_icon = $item->getParams()->get('expcaticon');
$listcatfields = ExpAutosProExpparams::getCatParams($item->id);
$inarray_cat = in_array($item->id, $expallowcat);
if($listcatfields->get('expfieldscatlinkto'))
    $explinkto = $listcatfields->get('expfieldscatlinkto');
    if($params->get('showemptycat') || $item->numitems || count($item->getChildren())) :
    ?>
    <li class="<?php if ($item->id == $expcatid) echo 'expactive'; ?><?php if (!$inarray_cat && $only_allowed) echo ' expnolink'; ?><?php if ($item->level == 1) echo ' expcatgeneral'; ?>"> 
        <?php $levelup = $item->level - $startLevel - 1; ?>
        <?php if (($only_allowed && $inarray_cat) || !$only_allowed): ?>
            <a href="<?php echo JRoute::_(ExpautosproHelperRoute::getCategoryRoute($item->id, $explinkto)); ?>">
        <?php endif; ?>
            <?php if($cat_icon):?>
                <i class="<?php echo $cat_icon;?>"></i>
            <?php elseif($img_link):?>
                <img src="<?php echo $img_link; ?>"/>
            <?php endif;?>
            <?php echo $item->title; ?>
            <?php
            if ($params->get('showcount', 1)) {
                $nexpcount = 0;
                if ($item->level == 1)
                    $nexpcount = $item->getNumItems(true);
                else
                    $nexpcount = $item->numitems;
                echo JText::_(JText::sprintf('MOD_EXPAUTOSPRO_CATEGORIES_COUNT_NUM', $nexpcount));
            }
            ?>
        <?php if (($only_allowed && $inarray_cat) || !$only_allowed): ?>
            </a>
        <?php endif; ?>

        <?php
        if ($params->get('show_description', 0)) {
            echo JHtml::_('content.prepare', $item->description, $item->getParams(), 'mod_expautospro_categories.content');
        }
        if ($params->get('show_children', 0) && (($params->get('maxlevel', 0) == 0) || ($params->get('maxlevel') >= ($item->level - $startLevel))) && count($item->getChildren())) {

            echo '<ul id="expcurrent'.$item->id.'">';
            $temp = $list;
            $list = $item->getChildren();
            require JModuleHelper::getLayoutPath('mod_expautospro_categories', $params->get('layout', 'default') . '_items');
            $list = $temp;
            echo '</ul>';
        }
        ?>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>
