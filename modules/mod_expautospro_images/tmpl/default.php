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
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_images/css/mod_expautospro_images.css');
$expconfig = ExpAutosProExpparams::getExpParams('config', 1);
$thumbsize = $expconfig->get('c_images_thumbsize_width');
$moduleid = $module->id;
?>
<style type="text/css">
    #modexpimages<?php echo $moduleid; ?> table.expautos_images_table td.expautos_images_images a{
        color:#000;
    }

    #modexpimages<?php echo $moduleid; ?> table.expautos_images_table td.expautos_images_images a:hover{
        color: #0055BB;
    }
</style>
<div id="modexpimages<?php echo $moduleid; ?>" class="modexpimages expimages<?php echo $moduleclass_sfx; ?>">
    <table class="expautos_images_table">
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
            <?php if (($c % $kr) == 0): ?>
                </tr>
                <tr class="expautos_images_tr">
                <?php endif; ?>
                <?php
                if ($expimages[$i]->img_name) {
                    $imglink = ExpAutosProExpparams::ImgUrlPatchThumbs() . $expimages[$i]->img_name;
                } else {
                    $imglink = ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg';
                }
                $price = ExpAutosProExpparams::price_formatdata($expimages[$i]->price, 1);
                $link = JRoute::_("index.php?option=com_expautospro&amp;view=expdetail&amp;id=" . $expimages[$i]->adid . "&amp;catid=" . $expimages[$i]->categid . "&amp;makeid=" . $expimages[$i]->mkid . "&amp;modelid=" . $expimages[$i]->mdid . "&amp;Itemid=" . $itemid);
                ?>
                <td valign="top" width="<?php echo $thumbsize; ?>" class="expautos_images_images <?php echo $imgclass; ?>">
                    <a href="<?php echo $link; ?>">
                        <div class="modimg <?php echo $imgclass; ?>">
                            <span></span><img src="<?php echo $imglink; ?>" />
                        </div>
                        <?php if ($params->get('showimgicons')): ?>
                            <div class="modexpimg">
                                <?php if ($newdateclass): ?>
                                    <span class="<?php echo $newdateclass; ?>" title="<?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_NEWDATE_TEXT') ?>"></span>
                                <?php endif; ?>
                                <?php if ($expimages[$i]->expreserved): ?>
                                    <span class="expreserved" title="<?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_RESERVED_TEXT') ?>"></span>
                                <?php endif; ?>
                                <div class="expautos_images_clear"></div>
                            </div>
                        <?php endif; ?>
                        <?php if ($expimages[$i]->make_name || $expimages[$i]->model_name): ?>
                            <div class="expautos_images_text"><center><?php echo $expimages[$i]->make_name; ?> <?php echo $expimages[$i]->model_name; ?></center></div>
                        <?php endif; ?>
                        <?php if ($params->get('showyear') && $expimages[$i]->year > 0): ?>
                            <div class="expautos_images_text"><center><?php echo $expimages[$i]->year; ?></center></div>
                        <?php endif; ?>
                        <?php if ($params->get('showmileage') && $expimages[$i]->mileage > 0): ?>
                            <div class="expautos_images_text"><center><?php echo $expimages[$i]->mileage; ?><?php echo JText::_('MOD_EXPAUTOSPRO_IMAGES_MILEAGE_KM_TEXT') ?></center></div>
                        <?php endif; ?>
                        <?php if ($params->get('showprice')): ?>
                            <div class="expautos_images_price"><center><?php echo $price; ?></center></div>
                        <?php endif; ?>
                    </a>
                </td>
                <?php
                $c++;
            endfor;
            ?>
    </table>
</div>