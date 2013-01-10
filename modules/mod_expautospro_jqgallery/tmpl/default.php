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

$gall_style=$params->get('position');
$document = JFactory::getDocument();

if ($params->get('jquery') == 1) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/jquery.min.js');
} elseif ($params->get('jquery') == 2) {
    $document->addScript(JURI::root() . 'modules/mod_expautospro_jqgallery/js/jquery.min.js');
}
$document->addScript(JURI::root() . 'modules/mod_expautospro_jqgallery/js/adgallery/jquery.mod_ad-gallery.js');
//$document->addScript(JURI::root() . 'modules/mod_expautospro_jqgallery/js/adgallery/jquery.prettyPhoto.js');
 $document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_jqgallery/css/mod_expautospro_jqgallery.css');
//$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_jqgallery/css/adgallery/adgallery.css');
if($gall_style=='vert'){
    $document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_jqgallery/css/adgallery/jquery.mod_ad-gallery_vert.css');
}elseif($gall_style=='hor'){
    $document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_jqgallery/css/adgallery/jquery.mod_ad-gallery_hori.css');
}
$expconfig = ExpAutosProExpparams::getExpParams('config', 1);
$thumbsize = $expconfig->get('c_images_thumbsize_width');
$tmblink = ExpAutosProExpparams::ImgUrlPatchThumbs();
$middlelink = ExpAutosProExpparams::ImgUrlPatchMiddle();
$biglink = ExpAutosProExpparams::ImgUrlPatchBig();
$logolink = ExpAutosProExpparams::ImgUrlPatchLogo();
$imglink = ExpAutosProExpparams::ImgUrlPatch();
$moduleid = $module->id;
if($params->get('showimgicons')){
    if(!$params->get('expsort2') && $params->get('expgroup')=='id'){
        $img_ribbon='fid';
    }elseif(!$params->get('expsort2') && $params->get('expgroup')=='random'){
        $img_ribbon='frandom';
    }else{
        $img_ribbon=$params->get('expsort2');
    }
    switch ($img_ribbon) {
        case 'ftop':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_jqgallery/images/adgallery/ribbon_top.png';
            break;
        case 'fcommercial':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_jqgallery/images/adgallery/ribbon_commercial.png';
            break;
        case 'special':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_jqgallery/images/adgallery/ribbon_special.png';
            break;
        case 'solid':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_jqgallery/images/adgallery/ribbon_sold.png';
            break;
        case 'fid':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_jqgallery/images/adgallery/ribbon_latest.png';
            break;
        case 'frandom':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_jqgallery/images/adgallery/ribbon_random.png';
            break;
    }
}
//print_r($params);
?>
<style>
<?php if ($expimages) { ?>
    <?php if($gall_style=='vert'){ ?>
        #modexpjqgallery<?php echo $moduleid; ?> .slider_container {
            position: relative;
            background:url('<?php echo JURI::root();?>modules/mod_expautospro_jqgallery/images/adgallery/bg_ad_gallery.png') no-repeat;
            width:730px;
            height:359px;
            margin:0 auto;
            padding-top:21px;
            padding-left:10px;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery {
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
            height: <?php echo $expconfig->get('c_images_middlesize_height'); ?>px;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-image-wrapper {
            height: <?php echo $expconfig->get('c_images_middlesize_height'); ?>px;
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
            margin-left: 15px;
            position: absolute;
            display: block;
            left: 0;
            overflow: hidden;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-controls {
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-nav {
            width: <?php echo $expconfig->get('c_images_middlesize_width') - 75; ?>px;
            height: <?php echo $expconfig->get('c_images_middlesize_height') + 70; ?>px;
        }
        #modexpjqgallery<?php echo $moduleid; ?> li{
            list-style: none;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-image-wrapper{
            clear:both;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-nav .mod_ad-thumbs {
            width: 100%;
            height:<?php echo $expconfig->get('c_images_middlesize_height') + 3; ?>px;
        }
        #modgallery<?php echo $moduleid; ?>{
        }
            
        #modgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-thumbs li .exp_gall_line{
        }
            
        #modgallery<?php echo $moduleid; ?> ul{
            padding: 2px;
            margin: 0;
        }
        #modgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-thumbs li {
        }
          
        #modexpjqgallery<?php echo $moduleid; ?> li a{
            color:#000;
        }
        #modexpjqgallery<?php echo $moduleid; ?> li a:hover{
            color:#095197;
        }
        #modexpjqgallery<?php echo $moduleid; ?> li a span{
            margin:0 2px 2px 10px;
            float:left;
        }
        #modexpjqgallery<?php echo $moduleid; ?> li  p{
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-image-wrapper .mod_ad-next {
            height: 100%;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-image-wrapper .mod_ad-prev {
            height: 100%;
        }
        
        <?php }elseif($gall_style=='hor'){ ?>
        
        #modexpjqgallery<?php echo $moduleid; ?> .slider_container {
            position: relative;
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
            height: <?php echo $expconfig->get('c_images_middlesize_height'); ?>px;
            margin:0 auto;
            padding-top:3px;
            padding-left:3px;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery {
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
            height: <?php echo $expconfig->get('c_images_middlesize_height'); ?>px;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-image-wrapper {
            height: <?php echo $expconfig->get('c_images_middlesize_height'); ?>px;
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
            width: 100%;
            margin-bottom: 10px;
            position: relative;
            overflow: hidden;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-controls {
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-nav {
            width: <?php echo $expconfig->get('c_images_middlesize_width') - 75; ?>px;
        }
        #modexpjqgallery<?php echo $moduleid; ?> li{
            list-style: none;
        }
        .mod_ad-image-wrapper{
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-nav .mod_ad-thumbs {
            width: 100%;
        }
        #modgallery<?php echo $moduleid; ?>{
        }
            
        #modgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-thumbs li .exp_gall_line{
        }
            
        #modgallery<?php echo $moduleid; ?> ul{
            padding: 2px;
            margin: 0;
        }
        #modgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-thumbs li {
        }
          
        #modexpjqgallery<?php echo $moduleid; ?> li a{
            color:#000;
        }
        #modexpjqgallery<?php echo $moduleid; ?> li a:hover{
            color:#095197;
            border:none;
        }
        #modexpjqgallery<?php echo $moduleid; ?> li a span{
            margin:0 2px 2px 10px;
            float:left;
        }
        #modexpjqgallery<?php echo $moduleid; ?> li  p{
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-image-wrapper .mod_ad-next {
            height: 90%;
        }
        #modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-image-wrapper .mod_ad-prev {
            height: 90%;
        }
     <?php } ?>
        <?php if ($params->get('showimgicons')) { ?>    
            #modexpjqgallery<?php echo $moduleid; ?> .ribbon {
                background:url(<?php echo $img_ribbon_icon;?>) no-repeat;
                width:111px;
                height:111px;
                position:absolute;
                top:0px;
                left:0px;
                z-index:300;
            }
        <?php } ?>
<?php } ?>

</style>
<script type="text/javascript">
    jQuery(document).ready(function(){
<?php if ($expimages) { ?>
            jQuery("#modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery .mod_ad-controls p").css({'opacity': .60});							
            var galleriesexp = jQuery('#modexpjqgallery<?php echo $moduleid; ?> .mod_ad-gallery').adGalleryExp({
                loader_image: '<?php echo JURI::root() . "modules/mod_expautospro_jqgallery/images/adgallery/loading.gif"; ?>',
                start_at_index: <?php echo $params->get('gall_start_index');?>,
                thumb_opacity: <?php echo $params->get('thumb_opacity');?>,
                animate_first_image: <?php echo $params->get('animate_first');?>,
                animation_speed: <?php echo $params->get('animation_speed');?>,
                slideshow: {
                    enable: <?php echo $params->get('slideshow_enable');?>,
                    autostart: <?php echo $params->get('auto_start');?>,
                    speed: <?php echo $params->get('slideshow_speed');?>,
                    stop_on_scroll: <?php echo $params->get('stop_scroll');?>,
                    start_label: '',
                    stop_label: ''
                },
                thumb_direction: '<?php echo $params->get('position');?>',
                cycle: <?php echo $params->get('gallery_cycle');?>,
                effect: '<?php echo $params->get('gallery_effect');?>' // or 'slide-vert', 'slide-hori', 'wild', 'fade', or 'resize', 'none'
            });	
                    
<?php } ?>
    });
</script>
<div id="modexpjqgallery<?php echo $moduleid; ?>" class="<?php echo $moduleclass_sfx; ?>">
    <?php if ($expimages): ?>
        <div class="slider_container">
            <div class="ribbon"></div>
            <div id="modgallery<?php echo $moduleid; ?>" class="mod_ad-gallery"> 
                <div class="mod_ad-image-wrapper"> 
                </div> 
                <div class="mod_ad-nav expadnav"> 
                    <div class="mod_ad-thumbs"> 
                        <ul class="mod_ad-thumb-list">
                            <?php
                            foreach ($expimages as $img):
                                $price = ExpAutosProExpparams::price_formatdata($img->price, 2);
                                $link = JRoute::_("index.php?option=com_expautospro&amp;view=expdetail&amp;id=".$img->adid."&amp;catid=".$img->categid."&amp;makeid=".$img->mkid."&amp;modelid=".$img->mdid."&amp;Itemid=".$itemid);
                                ?>
                                <li>
                                    <a href="<?php echo $middlelink . $img->img_name; ?>" target="<?php echo $params->get('targetlink');?>" goto="<?php echo $link; ?>"> 
                                        <div class="exp_gall_line">
                                            <img src="<?php echo $tmblink . $img->img_name; ?>" title="<?php echo $img->make_name; ?>&nbsp;<?php echo $img->model_name; ?>&nbsp;<?php echo $img->year; ?>" longdesc="<?php echo $biglink . $img->img_name; ?>" class="expautos_pretty" alt="" /> 
                                            <?php if($gall_style=='vert'):?>
                                                <span>
                                                    <?php if($img->make_name || $img->model_name):?>
                                                        <p class="exp_strong">
                                                        <?php if($img->make_name):?>
                                                            <?php echo $img->make_name; ?>
                                                        <?php endif; ?>
                                                        <?php if($img->model_name):?>
                                                            <?php echo $img->model_name; ?>
                                                        <?php endif; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if (($params->get('showyear') || $params->get('showmileage')) && ($img->year || $img->mileage)): ?>
                                                        <p>
                                                            <?php if ($params->get('showyear') && $img->year): ?>
                                                                <?php echo $img->year; ?>
                                                            <?php endif; ?>
                                                            <?php if ($params->get('showmileage') && $img->mileage): ?>
                                                                &#58;&#58;<?php echo $img->mileage.JText::_('MOD_EXPAUTOSPRO_JQGALLERY_KM_TEXT'); ?>
                                                            <?php endif; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if($params->get('showprice')): ?>
                                                        <p><?php echo $price; ?></p>
                                                    <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <div style="clear:both;"></div>
                                </li>
                            <?php endforeach; ?>
                        </ul> 
                    </div> 
                </div> 
                <div class="mod_ad-controls"> 
                </div> 
            </div>
        </div>
<?php endif; ?>
</div>
<div style="clear:both;"></div>