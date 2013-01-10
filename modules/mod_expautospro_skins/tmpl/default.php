<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

//no direct access
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_skins/css/mod_expautospro_skins.css');
$modules = JModuleHelper::getModule('expautospro_skins');
$folder_name = $modules->content['folder'];
$expgetpagecookie = JRequest::getVar($folder_name, null, $hash = 'COOKIE');
$expskin_post = (string) JRequest::getString('expskin', 0);
if ($expskin_post) {
    $expskin = $expskin_post;
} else {
    $expskin = $expgetpagecookie;
}
$mark_skins = $expskins;
$options = '';
$skins_format = $params->get('expskin_format', 1);
?>
<div id="modexpskins" class="modexpskins<?php echo $moduleclass_sfx ?>">
    <?php if ($mark_skins): ?>
        <form action="<?php echo JRoute::_(JURI::getInstance()->toString()); ?>#modexpskins" method="post">
            <ul>
                <li class="expskin_text"><?php echo JText::_('MOD_EXPAUTOSPRO_SKINS_SELECT_SKIN_TEXT'); ?></li>
                <?php foreach ($mark_skins as $key=>$skin) : ?>
                    <?php if ($skin !== 'default_print'): ?>
                        <?php
                        if ($skins_format == 1 && JFile::exists(JPATH_ROOT . DS . 'components/com_expautospro/skins/' . $folder_name . '/' . $skin . '/images/skin_image.png')) {
                            $skin_src = JURI::base() . 'components/com_expautospro/skins/' . $folder_name . '/' . $skin . '/images/skin_image.png';
                            $skin_link = '<img class="expskin_img" src="' . $skin_src . '" title="' . $skin . '"/>';
                        } elseif($skins_format == 2) {
                            $skin_link = $skin;
                        } else {
                            $skin_link = $key+1;
                        }
                        $expactivestyle = '';
                        if ($skin == $expskin) {
                            $expactivestyle = 'current active';
                        }
                        ?>
                        <li class="<?php echo $expactivestyle; ?>"><button class="<?php echo $expactivestyle; ?>" type="submit" name="expskin" value="<?php echo $skin;?>"><?php echo $skin_link;?></button></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <input type="hidden" name="pagename" value="<?php echo $folder_name; ?>" />
        </form>
    <?php endif; ?>
</div>