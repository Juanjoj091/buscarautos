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
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_images_sumo/css/mod_expautospro_images_sumo_bootstrap.css');
$expconfig = ExpAutosProExpparams::getExpParams('config', 1);
$thumbsize = $expconfig->get('c_images_thumbsize_width');
$moduleid = $module->id;
$block_width = $params->get('width');
?>
<style type="text/css">
    #modexpimagessumo<?php echo $moduleid; ?> .exp_text_b .label{
        font-size: 9px;
        padding:1px 2px;
    }
    #modexpimagessumo<?php echo $moduleid; ?> .expsumo_full{
        margin:0 auto;
    }
</style>
<div id="modexpimagessumo<?php echo $moduleid; ?>" class="modexpimagessumo <?php echo $moduleclass_sfx; ?>">

    <?php
    $c = 0;
    $kr = $params->get('expcolumnimg');
    $countshortlist = count($expimages);
    for ($i = 0, $n = $countshortlist; $i < $n; $i++):
        $imgclass = null;
        if ($params->get('showimgicons')) {
            /* New ads */
            $date_range = time() - ($expconfig->get('c_admanager_lspage_newdate') * 24 * 60 * 60);
            $zc_new_date = date('Ymd', $date_range);
            if (date('Ymd', strtotime($expimages[$i]->creatdate)) >= $zc_new_date){
                $newdateclass = "newdate";
            }else{
                $newdateclass = "";
            }

            if ($expimages[$i]->fcommercial){
                $imgclass = "commercial";
            }elseif ($expimages[$i]->ftop){
                $imgclass = "top";
            }elseif ($expimages[$i]->special){
                $imgclass = "special";
            }elseif (isset($expimages[$i]->solid)){
                $imgclass = "solid";
            }
        }
        ?>
        <?php if (($c % $kr) == 0): ?>
        </span>
        </ul>
        <ul class="expsumo_full">
            <span class="thumbnails">
        <?php endif; ?>
        <?php
        if ($expimages[$i]->img_name) {
            $imglink = ExpAutosProExpparams::ImgUrlPatchThumbs() . $expimages[$i]->img_name;
        } else {
            $imglink = ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg';
        }
        $price = ExpAutosProExpparams::price_formatdata($expimages[$i]->price, 4, 1);
        $link = JRoute::_("index.php?option=com_expautospro&amp;view=expdetail&amp;id=" . $expimages[$i]->adid . "&amp;catid=" . $expimages[$i]->categid . "&amp;makeid=" . $expimages[$i]->mkid . "&amp;modelid=" . $expimages[$i]->mdid . "&amp;Itemid=" . $itemid);
        ?>
        <li class="well expsumo_li thumbnail" style="width:<?php echo $block_width; ?>px;">
            <!--
         <span width="<?php echo $block_width; ?>" class="expautos_images_images <?php echo $imgclass; ?>">
            -->
            <a href="<?php echo $link; ?>" class=" expsumo_left">
                <div class="expsumo_left_cont">
                    <div class="modimg <?php echo $imgclass; ?> modexpimg">
                        <span></span><img src="<?php echo $imglink; ?>" />
                    </div>
                    <?php if ($params->get('showimgicons')): ?>
                        <div class="exp_text_b">
                            <?php if ($newdateclass): ?>
                                <div class="expautos_images_clear"></div>
                                <div class="expimgsumo_pos">
                                    <div class="modexpimg <?php echo $newdateclass; ?>">
                                        <span class=" label label-important" title="<?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_NEWDATE_TEXT') ?>"><?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_NEWDATE_TEXT') ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($expimages[$i]->expreserved): ?>
                                <div class="expautos_images_clear"></div>
                                <div class="expimgsumo_pos">
                                    <div class="modexpimg expreserved">
                                        <span class=" label label-info" title="<?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_RESERVED_TEXT'); ?>"><?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_RESERVED_TEXT'); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modexpimg_block_right">
                    <?php if ($expimages[$i]->make_name || $expimages[$i]->model_name): ?>
                        <div class="label">
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
                        <div class="expautos_images_text">
                            <?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_YEAR_TEXT') . $expimages[$i]->year; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($params->get('showfuel') && $expimages[$i]->fuel_name): ?>
                        <div class="expautos_images_text">
                            <?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_FUEL_TEXT') . $expimages[$i]->fuel_name; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($params->get('showmileage') && $expimages[$i]->mileage > 0): ?>
                        <div class="expautos_images_text">
                            <?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_MILEAGE_TEXT') . $expimages[$i]->mileage . JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_MILEAGE_KM_TEXT'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($params->get('showprice')): ?>
                        <div class="expautos_images_price badge badge-inverse">
                            <?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_SUMO_PRICE_TEXT') . $price; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </a>
        </li>
        <?php
        $c++;
    endfor;
    ?>
</div>