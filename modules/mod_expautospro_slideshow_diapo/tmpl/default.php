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
    $document->addScript(JURI::root() . 'modules/mod_expautospro_slideshow_diapo/js/jquery.min.js');
}
$document->addScript(JURI::root() . 'modules/mod_expautospro_slideshow_diapo/js/jquery.easing.1.3.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_slideshow_diapo/js/jquery.hoverIntent.minified.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_slideshow_diapo/js/diapo.js');
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_slideshow_diapo/css/mod_expautospro_slideshow_diapo.css');

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
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_slideshow_diapo/images/ribbon_top.png';
            break;
        case 'fcommercial':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_slideshow_diapo/images/ribbon_commercial.png';
            break;
        case 'special':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_slideshow_diapo/images/ribbon_special.png';
            break;
        case 'solid':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_slideshow_diapo/images/ribbon_sold.png';
            break;
        case 'fid':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_slideshow_diapo/images/ribbon_latest.png';
            break;
        case 'frandom':
            $img_ribbon_icon=JURI::root().'modules/mod_expautospro_slideshow_diapo/images/ribbon_random.png';
            break;
    }
}
//print_r($params);
?>
<style>
<?php if ($expimages) { ?>
        #modexpslideshow_diapo<?php echo $moduleid; ?> {
            margin:0 auto;
        }
        #modexpslideshow_diapo<?php echo $moduleid; ?> .slideshow_diapo_container {
                position: relative;
                padding-top:4px;
                padding-left:4px;
                height: <?php echo $expconfig->get('c_images_middlesize_height'); ?>px;
                width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
        }
        .pix_diapo {
            height: <?php echo $expconfig->get('c_images_middlesize_height'); ?>px;
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
        }
            
        #pix_pag {
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
        }
        #pix_pag_ul .pix_thumb {
            height: <?php echo $expconfig->get('c_images_thumbsize_height'); ?>px;
            width: <?php echo $expconfig->get('c_images_thumbsize_width'); ?>px;
        }
    <?php if ($params->get('showimgicons')) { ?>
            #modexpslideshow_diapo<?php echo $moduleid; ?> .slideshow_diapo_ribbon {
                background:url(<?php echo $img_ribbon_icon; ?>) no-repeat;
                width:111px;
                height:111px;
                position:absolute;
                top:0px;
                left:0px;
                z-index:1003;
            }
    <?php } ?>
<?php } ?>
    
</style>
<script type="text/javascript">
    jQuery(document).ready(function(){
<?php if ($expimages) { ?>
            jQuery('.pix_diapo').diapo({fx:'<?php echo $params->get('effect');?>',loader:'<?php echo $params->get('loader');?>',loaderOpacity:<?php echo $params->get('loader_opacity');?>,loaderColor:'<?php echo $params->get('loader_color');?>',loaderBgColor:'<?php echo $params->get('loader_bgcolor');?>',pieDiameter:<?php echo $params->get('loader_piediametr');?>,barPosition:'<?php echo $params->get('loader_barposition');?>',time:<?php echo $params->get('time');?>,transPeriod:<?php echo $params->get('transperiod');?>,autoAdvance:<?php echo $params->get('autoplay');?>});
                    
<?php } ?>
    });
</script>
<div id="modexpslideshow_diapo<?php echo $moduleid; ?>" class="<?php echo $moduleclass_sfx; ?>">
    <?php if ($expimages): ?>
        <div class="slideshow_diapo_container">
            <div class="slideshow_diapo_ribbon"></div>
            <div id="modslideshow_diapo<?php echo $moduleid; ?>" class="pix_diapo">  
                <?php
                foreach ($expimages as $img):
                    $price = ExpAutosProExpparams::price_formatdata($img->price, 2);
                    $link = JRoute::_("index.php?option=com_expautospro&amp;view=expdetail&amp;id=".$img->adid."&amp;catid=".$img->categid."&amp;makeid=".$img->mkid."&amp;modelid=".$img->mdid."&amp;Itemid=".$itemid);
                ?>
                <div data-thumb="<?php echo $tmblink . $img->img_name; ?>">
                    <a href="<?php echo $link; ?>" target="<?php echo $params->get('targetlink');?>">
                        <img src="<?php echo $middlelink . $img->img_name; ?>"/>
                    </a>
                        <div class="pix_caption elemHover fromLeft">
                            <?php echo $img->make_name; ?>&nbsp;<?php echo $img->model_name; ?><?php if($params->get('showyear') && $img->year):?>&nbsp;<?php echo $img->year ;?><?php endif;?>&nbsp;<?php echo $price; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <div class="exp_diapo_clear"></div>
    <?php endif; ?>
</div>