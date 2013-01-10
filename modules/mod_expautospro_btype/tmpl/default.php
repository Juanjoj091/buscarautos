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
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_btype/css/mod_expautospro_btype.css');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchchained.js');
if($params->get('usechosen', 0)){
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/chosen.jquery.min.js');
    $document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/chosen.css');
}
$moduleid = $module->id;
$catid = (int) JRequest::getInt('catid', 0);
$bodytype = (int) JRequest::getInt('bodytype', 0);
if (!$catid && $params->get('catid')) {
    $catid = $params->get('catid');
}
$imgbtype = $params->get('imgbtype', 1);
?>

<?php if ($catid):?>
    <?php
    if ($params->get('usecatfield', 1)) {
        echo $list;
    }
    ?>
    <div id="modexpbtype<?php echo $moduleid; ?>" class="expbtype <?php echo $moduleclass_sfx; ?>">
        <?php echo ExpAutosProExpparams::getExpImgBtype($catid, $imgbtype, $bodytype, $itemid); ?>
    </div>
<?php endif;?>
<?php if($params->get('usechosen', 0)):?>
<script type="text/javascript">
        jQuery(".chzn-expbtselect").chosen();
</script>
<?php endif;?>
