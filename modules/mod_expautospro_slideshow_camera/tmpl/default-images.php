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

$gall_style = $params->get('position');
$document = JFactory::getDocument();
if ($params->get('jquery') == 1) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/jquery.min.js');
} elseif ($params->get('jquery') == 2) {
    $document->addScript(JURI::root() . 'modules/mod_expautospro_slideshow_camera/js/jquery.min.js');
}
$document->addScript(JURI::root() . 'modules/mod_expautospro_slideshow_camera/js/jquery.mobile.customized.min.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_slideshow_camera/js/jquery.easing.1.3.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_slideshow_camera/js/camera.min.js');
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_slideshow_camera/css/mod_expautospro_slideshow_camera.css');

if($params->get('defimg_patch')){
    $imglink = JURI::root().$params->get('defimg_patch');
}else{
    $imglink = JURI::root();
}
if($params->get('defimg_tmbpatch'))
    $imgtmblink = JURI::root().$params->get('defimg_tmbpatch');

$moduleid = $module->id;
$img_code = $params->get('defimg_code');
$expimages = explode("';", $img_code);
//print_r($expimages);
?>
<style>
<?php if ($expimages) { ?>
        #modexpslideshow_camera<?php echo $moduleid; ?> .slideshow_camera_container {
            position: relative;
        }
<?php } ?>
    
</style>
<script type="text/javascript">
<?php if ($expimages) { ?>
        jQuery(document).ready(function(){
            jQuery('#camera_wrap_<?php echo $moduleid; ?>').camera({
                alignment: '<?php echo $params->get('alignment'); ?>',
                piewrap: <?php echo $moduleid; ?>,
                autoAdvance: <?php echo $params->get('autoAdvance'); ?>,
                mobileAutoAdvance: <?php echo $params->get('mobileAutoAdvance'); ?>,
                barDirection: '<?php echo $params->get('barDirection'); ?>',
                barPosition: '<?php echo $params->get('barPosition'); ?>',
                cols: <?php echo $params->get('cols'); ?>,
                easing: '<?php echo $params->get('easing'); ?>',
                mobileEasing: '<?php echo $params->get('mobileEasing'); ?>',
                fx: '<?php echo $params->get('fx'); ?>',
                mobileFx: '<?php echo $params->get('mobileFx'); ?>',
                gridDifference: <?php echo $params->get('gridDifference'); ?>,
                height: '<?php echo $params->get('height'); ?>',
                hover: <?php echo $params->get('hover'); ?>,
                loader: '<?php echo $params->get('loader'); ?>',
                loaderColor: '<?php echo $params->get('loaderColor'); ?>',
                loaderBgColor: '<?php echo $params->get('loaderBgColor'); ?>',
                loaderOpacity: <?php echo $params->get('loaderOpacity'); ?>,
                loaderPadding: <?php echo $params->get('loaderPadding'); ?>,
                loaderStroke: <?php echo $params->get('loaderStroke'); ?>,
                minHeight: '<?php echo $params->get('minHeight'); ?>',
                navigationHover: <?php echo $params->get('navigationHover'); ?>,
                mobileNavHover: <?php echo $params->get('mobileNavHover'); ?>,
                opacityOnGrid: <?php echo $params->get('opacityOnGrid'); ?>,
                overlayer: <?php echo $params->get('overlayer'); ?>,
                pagination: <?php echo $params->get('pagination'); ?>,
                pauseOnClick: <?php echo $params->get('pauseOnClick'); ?>,
                pieDiameter: <?php echo $params->get('pieDiameter'); ?>,
                piePosition: '<?php echo $params->get('piePosition'); ?>',
                portrait: <?php echo $params->get('portrait'); ?>,
                rows: <?php echo $params->get('rows'); ?>,
                slicedCols: <?php echo $params->get('slicedCols'); ?>,
                slicedRows: <?php echo $params->get('slicedRows'); ?>,
                slideOn: '<?php echo $params->get('slideOn'); ?>',
                thumbnails: <?php echo $params->get('thumbnails'); ?>,
                time: <?php echo $params->get('time'); ?>,
                transPeriod: <?php echo $params->get('transPeriod'); ?>
            });
        });     
<?php } ?>
</script>
<?php if ($expimages): ?>
    <div id="modexpslideshow_camera<?php echo $moduleid; ?>" class="<?php echo $moduleclass_sfx; ?>">
        <div class="slideshow_camera_container">
            <div class="slideshow_camera_ribbon"></div>
            <div class="camera_wrap camera_azure_skin" id="camera_wrap_<?php echo $moduleid; ?>">
                
<?php
                foreach ($expimages as $img):
                    $img_arr = explode("',", $img);
                    $img_arr = str_replace("'", "", $img_arr);

                    $link = '';
                    $caption = '';
                    if($img_arr[2])
                        $link = $img_arr[2];
                    if($img_arr[1])
                        $caption = $img_arr[1];
                    ?>
                    <div data-thumb="<?php echo $imgtmblink.$img_arr[0];?>" data-link="<?php echo $link; ?>" data-target="<?php echo $params->get('targetlink'); ?>" data-src="<?php echo $imglink.$img_arr[0];?>">
                        <?php if($caption):?>
                        <div class="camera_caption fadeFromBottom">
                            <?php echo $caption?>
                        </div>
                        <?php endif;?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="exp_camera_clear"></div>
    </div>
    <div class="exp_camera_clear"></div>
<?php endif; ?>