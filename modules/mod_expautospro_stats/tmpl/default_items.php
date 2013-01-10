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
$expcatid = (int) JRequest::getInt('catid', 0);
foreach ($list as $item) :
    if($params->get('showemptycat',1) || $item->numitems || count($item->getChildren())) :
    ?>
    <li class="<?php if ($item->id == $expcatid) echo 'expactive'; ?><?php if ($item->level == 1) echo ' expcatgeneral'; ?>"> 
        <?php $levelup = $item->level - $startLevel - 1; ?>
            <?php echo $item->title; ?>
            <?php
            if ($params->get('showcount', 1)) {
                $nexpcount = 0;
                if ($item->level == 1){
                    $nexpcount = $item->getNumItems(true);
                }else{
                    $nexpcount = $item->numitems;
                    $count_all +=$nexpcount;
                }
                echo " = ".$nexpcount;
            }
            ?>

        <?php
            echo '<ul id="expcurrent'.$item->id.'">';
            $temp = $list;
            $list = $item->getChildren();
            require JModuleHelper::getLayoutPath('mod_expautospro_stats', $params->get('layout', 'default') . '_items');
            $list = $temp;
            echo '</ul>';
        ?>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>
