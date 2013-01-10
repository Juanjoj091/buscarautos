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
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_images_tiny/css/mod_expautospro_images_tiny_bootstrap.css');
//$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_images_tiny/css/jquery_tiny.css');

if ($params->get('jquery') == 1) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/jquery.min.js');
} elseif ($params->get('jquery') == 2) {
    $document->addScript(JURI::root() . 'modules/mod_expautospro_images_tiny/js/jquery.min.js');
}
$document->addScript(JURI::root() . 'modules/mod_expautospro_images_tiny/js/jquery.tinycarousel.min.js');
$expconfig = ExpAutosProExpparams::getExpParams('config', 1);
$thumbsize = $expconfig->get('c_images_thumbsize_width');
$moduleid = $module->id;
?>
<style type="text/css">
    /* Tiny Carousel */
    #modexpimagestiny<?php echo $moduleid; ?> .tinyviewport {width:<?php echo $params->get('expblockwidth');?>; height: <?php echo $params->get('expblockheight');?>;}
    #modexpimagestiny<?php echo $moduleid; ?> .tinyoverview {width: <?php echo $thumbsize + 10; ?>px; }
    #modexpimagestiny<?php echo $moduleid; ?> .tinyoverview li{width:<?php echo $thumbsize + 10; ?>px;}
</style>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#modexpimagestiny<?php echo $moduleid; ?>').tinycarousel({
            start: <?php echo $params->get('exptinystart'); ?>,
            display: <?php echo $params->get('exptinydisplay'); ?>,
            axis: '<?php echo $params->get('exptinyaxis'); ?>',
            interval: <?php echo $params->get('exptinyinterval'); ?>,
            intervaltime: <?php echo $params->get('exptinyintervaltime'); ?>,
            animation: <?php echo $params->get('exptinyanimation'); ?>,
            duration: <?php echo $params->get('exptinyduration'); ?>
        });	
    });
</script>
<div id="modexpimagestiny<?php echo $moduleid; ?>" class="modexpimagestiny <?php echo $moduleclass_sfx; ?>">
    <a class="tinybuttons tinyprev" href="#"><i class="icon-chevron-left"></i></a>
    <div class="tinyviewport">
        <ul class="thumbnails expimg_fulltiny tinyoverview">
            <?php
            $countshortlist = count($expimages);
            for ($i = 0, $n = $countshortlist; $i < $n; $i++):
                $imgclass = null;
                if ($params->get('showimgicons')) {
                    /* New ads */
                    $date_range = time() - ($expconfig->get('c_admanager_lspage_newdate') * 24 * 60 * 60);
                    $zc_new_date = date('Ymd', $date_range);
                    if (date('Ymd', strtotime($expimages[$i]->creatdate)) >= $zc_new_date) {
                        $newdateclass = "newdate";
                    } else {
                        $newdateclass = "";
                    }

                    if ($expimages[$i]->fcommercial) {
                        $imgclass = "commercial";
                    } elseif ($expimages[$i]->ftop) {
                        $imgclass = "top";
                    } elseif ($expimages[$i]->special) {
                        $imgclass = "special";
                    } elseif (isset($expimages[$i]->solid)) {
                        $imgclass = "solid";
                    }
                }
                ?>
                <?php
                if ($expimages[$i]->img_name) {
                    $imglink = ExpAutosProExpparams::ImgUrlPatchThumbs() . $expimages[$i]->img_name;
                } else {
                    $imglink = ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg';
                }
                $price = ExpAutosProExpparams::price_formatdata($expimages[$i]->price, 1);
                $link = JRoute::_("index.php?option=com_expautospro&amp;view=expdetail&amp;id=" . $expimages[$i]->adid . "&amp;catid=" . $expimages[$i]->categid . "&amp;makeid=" . $expimages[$i]->mkid . "&amp;modelid=" . $expimages[$i]->mdid . "&amp;Itemid=" . $itemid);
                ?>
                <li class=" <?php echo $imgclass; ?>">
                    <span class="">
                        <a class="thumbnail" href="<?php echo $link; ?>">
                            <div class="modimgtiny <?php echo $imgclass; ?>">
                                <span></span><img src="<?php echo $imglink; ?>" style="width:<?php echo $thumbsize; ?>px" />
                            </div>
                            <?php if ($params->get('showimgicons')): ?>
                                <?php if ($newdateclass): ?>
                                    <div class="expautos_images_clear"></div>
                                    <div class="expimg_pos_tiny">
                                        <div class=" <?php echo $newdateclass; ?>">
                                            <span class="" title="<?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_TINY_NEWDATE_TEXT') ?>"></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($expimages[$i]->expreserved): ?>
                                    <div class="expautos_images_clear"></div>
                                    <div class="expimg_pos_tiny">
                                        <div class="expreserved">
                                            <span class="expreserved" title="<?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_TINY_RESERVED_TEXT') ?>"></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="expautos_images_clear"></div>
                            <?php endif; ?>
                            <div class="expimg_text label label-info">
                                <?php if ($expimages[$i]->make_name || $expimages[$i]->model_name): ?>
                                    <div class="expautos_images_text expimg_strong initialism">
                                        <?php
                                        $text_string = $expimages[$i]->make_name . " " . $expimages[$i]->model_name;
                                        if ($params->get('expmaxlenght')) {
                                            $text_title = ExpAutosProExpparams::getExpcutStr($text_string, $params->get('expmaxlenght'), 0, '...');
                                        } else {
                                            $text_title = $text_string;
                                        }
                                        ?>
                                        <?php echo $text_title; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($params->get('showyear') && $expimages[$i]->year > 0): ?>
                                    <div class="expautos_images_text"><center><?php echo $expimages[$i]->year; ?></center></div>
                                <?php endif; ?>
                                <?php if ($params->get('showmileage') && $expimages[$i]->mileage > 0): ?>
                                    <div class="expautos_images_text"><center><?php echo $expimages[$i]->mileage; ?><?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_TINY_MILEAGE_KM_TEXT') ?></center></div>
                                <?php endif; ?>
                                <?php if ($params->get('showprice')): ?>
                                    <div class="expautos_images_price"><center><?php echo $price; ?></center></div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </span>
                </li>
                <?php
            endfor;
            ?>
        </ul>
    </div>
   <a class="tinybuttons tinynext" href="#"><i class="icon-chevron-right"></i></a>
</div>