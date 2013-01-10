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
$filename1 = 'slideshow.js';
$path = JURI::root() . 'modules/mod_expautospro_moogallery/js/';
JHTML::script($filename1, $path, true);
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_moogallery/css/mod_expautospro_moogallery.css');
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_moogallery/css/slideshow_hor.css');
$expconfig = ExpAutosProExpparams::getExpParams('config', 1);
$thumbsize = $expconfig->get('c_images_thumbsize_width');
$tmblink = ExpAutosProExpparams::ImgUrlPatchThumbs();
$middlelink = ExpAutosProExpparams::ImgUrlPatchMiddle();
$biglink = ExpAutosProExpparams::ImgUrlPatchBig();
$logolink = ExpAutosProExpparams::ImgUrlPatchLogo();
$imglink = ExpAutosProExpparams::ImgUrlPatch();
$moduleid = $module->id;
if ($params->get('showimgicons')) {
    if (!$params->get('expsort2') && $params->get('expgroup') == 'id') {
        $img_ribbon = 'fid';
    } elseif (!$params->get('expsort2') && $params->get('expgroup') == 'random') {
        $img_ribbon = 'frandom';
    } else {
        $img_ribbon = $params->get('expsort2');
    }
    switch ($img_ribbon) {
        case 'ftop':
            $img_ribbon_icon = JURI::root() . 'modules/mod_expautospro_jqgallery/images/adgallery/ribbon_top.png';
            break;
        case 'fcommercial':
            $img_ribbon_icon = JURI::root() . 'modules/mod_expautospro_moogallery/images/ribbon_commercial.png';
            break;
        case 'special':
            $img_ribbon_icon = JURI::root() . 'modules/mod_expautospro_moogallery/images/ribbon_special.png';
            break;
        case 'solid':
            $img_ribbon_icon = JURI::root() . 'modules/mod_expautospro_moogallery/images/ribbon_sold.png';
            break;
        case 'fid':
            $img_ribbon_icon = JURI::root() . 'modules/mod_expautospro_moogallery/images/ribbon_latest.png';
            break;
        case 'frandom':
            $img_ribbon_icon = JURI::root() . 'modules/mod_expautospro_moogallery/images/ribbon_random.png';
            break;
    }
}
//print_r($params);
?>
<style>
<?php if ($expimages) { ?>
        #expslide<?php echo $moduleid; ?> {
            height: <?php echo $expconfig->get('c_images_middlesize_height') + $expconfig->get('c_images_thumbsize_height') + 25; ?>px;
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
            padding: 3px 0 0 3px;
        }

        #expslide<?php echo $moduleid; ?> .slideshow-images {
            height: <?php echo $expconfig->get('c_images_middlesize_height'); ?>px;
            width: <?php echo $expconfig->get('c_images_middlesize_width'); ?>px;
        }
        #expslide<?php echo $moduleid; ?> .slideshow-thumbnails {
            bottom: -<?php echo $expconfig->get('c_images_thumbsize_height') + 25; ?>px; height: <?php echo $expconfig->get('c_images_thumbsize_height') + 25; ?>px; left: 0; position: absolute; width: 100%;
        }
    <?php if ($params->get('showimgicons')) { ?>    
            #modexpmoogallery<?php echo $moduleid; ?> .ribbon {
                background:url(<?php echo $img_ribbon_icon; ?>) no-repeat;
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
<?php if ($expimages) { ?>
        window.addEvent('domready', function(){
            var myShow<?php echo $moduleid; ?> = new Slideshow('exp_slidemoo<?php echo $moduleid; ?>', '', { captions: <?php echo $params->get('captions');?>, controller: true, delay: <?php echo $params->get('delay');?>, duration: <?php echo $params->get('duration');?>, loop: true, overlap: <?php echo $params->get('overlap');?>, paused: <?php echo $params->get('paused');?>});
        });	
                            
<?php } ?>
</script>
<div id="modexpmoogallery<?php echo $moduleid; ?>" class="<?php echo $moduleclass_sfx; ?>">
    <?php if ($expimages): ?>
        <div class="slider_container">
            <div class="ribbon"></div>
            <div id="expslide<?php echo $moduleid; ?>">             
                <div id="exp_slidemoo<?php echo $moduleid; ?>" class="slideshow">
                    <div class="slideshow-images">
                        <?php foreach ($expimages as $img): 
                            $price = ExpAutosProExpparams::price_formatdata($img->price, 2);
                            $link = JRoute::_("index.php?option=com_expautospro&amp;view=expdetail&amp;id=".$img->adid."&amp;catid=".$img->categid."&amp;makeid=".$img->mkid."&amp;modelid=".$img->mdid."&amp;Itemid=".$itemid);
                        ?>
                            <a href="<?php echo $link; ?>" title="" target="<?php echo $params->get('targetlink');?>">
                                <img src='<?php echo $middlelink . $img->img_name; ?>' alt='<?php echo $img->make_name; ?>&nbsp;<?php echo $img->model_name; ?>&nbsp;<?php echo $img->year; ?>&nbsp;<?php echo $price; ?>' />
                            </a>
                        <?php endforeach; ?>
                        <div class="slideshow-loader"></div>
                    </div>
                    <div class="slideshow-controller">
                        <ul>
                            <li class="first"><a></a></li>
                            <li class="prev"><a></a></li>
                            <li class="pause play"><a></a></li>
                            <li class="next"><a></a></li>
                            <li class="last"><a></a></li>
                        </ul>
                    </div>
                    <div class="slideshow-thumbnails">
                        <ul>
                            <?php foreach ($expimages as $img): ?>
                                <li>
                                    <a>
                                        <img src='<?php echo $tmblink . $img->img_name; ?>' />
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="expautospro_clear"></div>
    <?php endif; ?>
</div>