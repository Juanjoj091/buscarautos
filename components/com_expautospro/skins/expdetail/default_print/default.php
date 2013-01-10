<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

defined('_JEXEC') or die;

$params_file = JPATH_COMPONENT . '/skins/expdetail/default_print/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('expdetail','default_print');

$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$document = JFactory::getDocument();
$script = '';
$script .= "
    function jooImage(target,fname,title) {
        document[target].src=fname;
        document[target].title=title;	
    }
    ";
$document->addScriptDeclaration($script);
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expdetail/default_print/css/default.css');
$user = JFactory::getUser();
$imgquery = $this->expimages;
$expcatequipment = $this->expcatequipment;
$expgroupfield = $this->expgroupfield;
$tmblink = ExpAutosProExpparams::ImgUrlPatchThumbs();
$middlelink = ExpAutosProExpparams::ImgUrlPatchMiddle();
$biglink = ExpAutosProExpparams::ImgUrlPatchBig();
$logolink = ExpAutosProExpparams::ImgUrlPatchLogo();
$imglink = ExpAutosProExpparams::ImgUrlPatch();
$adparams = '';
if($this->expparams->get('c_admanager_useparams') && (int)$this->items['id']){
    $adparams = ExpAutosProExpparams::getExpParams('admanager',$this->items['id']);
}
?>
    <div id="expautospro">
        <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_TEXT'); ?></h3>
        <div id="expautos_detail">
            <?php if ($this->expparams->get('c_admanager_detailpage_showprint')): ?>
                <?php if (!isset($_GET['print'])) : ?>
                <span class="exp_autos_pricon">
                    <?php echo JHtml::_('icon.print_icon', $this->items['id']); ?>
                </span>
                <?php else : ?>
                    <span class="exp_autos_pricon">
                        <?php echo JHtml::_('icon.print_screen', $this->items['id']); ?>
                    </span>
                <?php endif; ?>
            <?php endif; ?>            
            <h3>
                <?php echo $this->items['make_name']; ?>
                <?php echo $this->items['model_name']; ?>
                <?php if ($this->items['specificmodel']): ?>
                    <?php echo $this->items['specificmodel']; ?>
                <?php endif; ?>
                <?php if ($this->items['displacement'] > 0): ?>
                    <?php echo $this->items['displacement'] . JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT'); ?>
                <?php endif; ?>
                <?php if ($this->items['engine'] > 0): ?>
                    <?php echo $this->items['engine'] . JText::_('COM_EXPAUTOSPRO_KW_TEXT'); ?>
                <?php endif; ?>
            </h3>
            <div class="expautos_detail_left">
                <?php if ($this->items['expreserved'] || $this->items['solid']): ?>
                    <div class="expautos_detail_img_icon">
                        <?php if ($this->items['expreserved']): ?>
                        <span class="expreserved" title="<?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_TABLE_RESERVED_TEXT') ?>"></span>
                        <?php endif; ?>
                        <?php if ($this->items['solid']): ?>
                        <span class="expsolid" title="<?php echo JText::_('COM_EXPAUTOSPRO_LS_SPECIAL_SOLID_TEXT') ?>"></span>
                        <?php endif; ?>
                    </div>
                <div class="expautospro_clear"></div>
                <?php endif; ?>
                <?php if ($imgquery): ?>
                    <img name='largeimage' src='<?php echo $middlelink . $imgquery[0]['imgname']; ?>' title='<?php echo $imgquery[0]['imgdescription']; ?>' />
                    <ul class="expautos_detail_ul_img">
                        <?php foreach ($imgquery as $img): ?>
                            <li class="expautos_detail_li_img">
                                <a href="javascript:jooImage('largeimage','<?php echo $middlelink . $img['imgname']; ?>','<?php echo $img['imgdescription']; ?>');">
                                    <img src="<?php echo $tmblink . $img['imgname']; ?>" alt="<?php echo $img['imgdescription']; ?>" title="<?php echo $img['imgdescription']; ?>" />
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <div class="expautospro_clear"></div>
                <?php echo ExpAutosProHelper::load_module_position('expdetailimage', $style = 'none'); ?>
                <div class="expautospro_clear"></div>
                <div class="expautos_detail_equipment">
                    <table class="exp_autos_equiptable">
                        <?php if ($this->expcatfields->get('useequipment') && $this->items['equipment'] && $expcatequipment): ?>
                            <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_EQUIPMENT_TEXT'); ?></h3>
                            <?php
                            foreach ($expcatequipment as $eqcat) :
                                $db = JFactory::getDBO();
                                $query = $db->getQuery(true);
                                $query->select('id, name');
                                $query->from('#__expautos_equipment');
                                $query->where('state=1');
                                $query->where('catid = ' . (int) $eqcat->id);
                                $query->where('id IN(' . $this->items['equipment'] . ')');
                                $query->order('ordering');
                                $db->setQuery($query);
                                $equipmentn = $db->loadObjectList();

                                $c = 0;
                                $kr = $this->expparams->get('c_general_equipcolumn');
                                ?>
                                <tr>
                                    <td colspan="<?php echo $kr; ?>">
                                        <?php if ($equipmentn): ?>
                                            <div class="exp_autos_equipname"><?php echo $eqcat->name; ?></div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                                for ($i = 0, $n = count($equipmentn); $i < $n; $i++):
                                    if (($c % $kr) == 0):
                                        echo '</tr>';
                                        echo '<tr>';
                                    endif;
                                    ?>
                                    <td class="exp_autos_equip">
                                        <?php echo "&#45; " . $equipmentn[$i]->name; ?>&nbsp;
                                    </td>
                                    <?php
                                    $c++;
                                    $metaexp[] = $equipmentn[$i]->name;
                                    $metakeyexp[] = $equipmentn[$i]->name;
                                endfor;
                            endforeach;
                        endif;
                        ?>
                    </table> 
                </div>
                <?php if (($this->expcatfields->get('usefconsum') || $this->expcatfields->get('useadacceleration') || $this->expcatfields->get('usemaxspeed') || $this->expcatfields->get('useotherinfo')) && ($this->items['fconsumcity'] || $this->items['fconsumfreeway'] || $this->items['fconsumcombined'] || $this->items['adacceleration'] || $this->items['maxspeed'] || $this->items['otherinfo'])): ?>
                    <div class="expautospro_clear"></div>
                    <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_OTHERINFO_TEXT'); ?></h3>
                    <?php if ($this->items['fconsumcity']): ?>
                        <p>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_FUELINCITY_TEXT') . $this->items['fconsumcity'] . JText::_('COM_EXPAUTOSPRO_LTRKM_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['fconsumfreeway']): ?>
                        <p>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_FUELONFREEWAY_TEXT') . $this->items['fconsumfreeway'] . JText::_('COM_EXPAUTOSPRO_LTRKM_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['fconsumcombined']): ?>
                        <p>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_FUELAVERAGE_TEXT') . $this->items['fconsumcombined'] . JText::_('COM_EXPAUTOSPRO_LTRKM_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['adacceleration']): ?>
                        <p>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ACCELERATION_TEXT') . $this->items['adacceleration'] . JText::_('COM_EXPAUTOSPRO_SECUND_S_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['maxspeed']): ?>
                        <p>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_MAXSPEED_TEXT') . $this->items['maxspeed'] . JText::_('COM_EXPAUTOSPRO_KMHOUR_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['otherinfo']): ?>
                        <p>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_OTHERINFOS_TEXT') . $this->items['otherinfo']; ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($this->expparams->get('c_admanager_detailpage_showgmap')): ?>
                    <?php if (($this->items['expuser_latitude'] && $this->items['expuser_longitude']) || ($this->items['latitude'] && $this->items['longitude'])):?>
                        <div class="expautospro_clear"></div>
                        <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GOOGLEMAP_TEXT'); ?></h3>
                        <?php
                        $zoom = $this->expparams->get('c_admanager_detailpage_gmapzoom');
                        if($this->items['latitude'] && $this->items['longitude']){
                            $lat = $this->items['latitude'];
                            $long = $this->items['longitude'];
                        }else{
                            $lat = $this->items['expuser_latitude'];
                            $long = $this->items['expuser_longitude'];
                        }
                        $document->addScript('http://maps.google.com/maps/api/js?sensor=false');
                        $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expgooglemap.js');
                        $script = '';
                        $script .= "
                            var explat=$lat;
                            var explong =$long;
                            var expzoom =$zoom;
                            var expclick = 0;
                            var expmapTypeId = google.maps.MapTypeId.".$this->expparams->get('c_general_gmapmaptypeid').";
                            var expalert = '".JText::_('COM_EXPAUTOSPRO_GOOGLEMAP_ALERT_TEXT')."';
                            var exp_map_canvas = 'exp_mapdetails_canvas';
                                    ";

                        $document->addScriptDeclaration($script);
                        ?>
                        <div id="exp_mapdetails_canvas" style="width: <?php echo $this->expparams->get('c_admanager_detailpage_gmapwidth'); ?>px; height: <?php echo $this->expparams->get('c_admanager_detailpage_gmapheight'); ?>px;"></div>
                    <?php elseif (($this->items['expuser_country'] && $this->items['expuser_city']) || ($this->items['cnt_name'] && $this->items['st_name'])): ?>
                        <?php
                        if ($this->items['cnt_name']) {
                            $mapadress = $this->items['cnt_name'] . "+" . $this->items['st_name'] . "+" . $this->items['ct_name'] . "+" . $this->items['street'];
                        } else {
                            $mapadress = $this->items['expuser_country'] . "+" . $this->items['expuser_city'] . "+" . $this->items['expuser_state'] . "+" . $this->items['expuser_street'];
                        }
                        ?>
                        <div class="expautospro_clear"></div>
                        <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GOOGLEMAP_TEXT'); ?></h3>
                        <iframe width="<?php echo $this->expparams->get('c_admanager_detailpage_gmapwidth'); ?>" height="<?php echo $this->expparams->get('c_admanager_detailpage_gmapheight'); ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;geocode=&amp;q=<?php echo $mapadress; ?>&amp;z=14&amp;output=embed"></iframe>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="expautospro_clear"></div>
                <?php if ($this->expparams->get('c_admanager_detailpage_showhits')): ?>
                    <div class="expdetail_hits">
                    <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_HITS_TEXT'); ?>
                    <?php echo $this->items['hits']; ?>
                    </div>
                    <div class="expautospro_clear"></div>
                <?php endif; ?>
            </div>
            <div class="expautos_detail_right">
                <?php echo ExpAutosProHelper::load_module_position($righttopmoduleposition, $this->expparams->get('c_admanager_detailpage_rgtstyle')); ?>
                <div class="moduletable_menu">
                    <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_TEXT'); ?></h3>
                    <p>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ID_TEXT'); ?></span>
                        <?php echo $this->items['id']; ?>
                    </p>
                    <?php if ($this->items['cond_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CONDITION_TEXT'); ?></span>
                            <?php echo $this->items['cond_name']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['stocknum']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STOCKNUMBER_TEXT'); ?></span>
                            <?php echo $this->items['stocknum']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['vincode']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_VINCODE_TEXT'); ?></span>
                            <?php echo $this->items['vincode']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['mileage']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_MILEAGE_TEXT'); ?></span>
                            <?php echo $this->items['mileage']; ?>&nbsp;<?php echo JText::_('COM_EXPAUTOSPRO_KM_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['year']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_YEAR_TEXT'); ?></span>
                            <?php if ($this->items['month']): ?><?php echo $this->items['month']; ?>&#47;<?php endif; ?><?php echo $this->items['year']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['displacement'] > 0): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ENGINESIZE_TEXT'); ?></span>
                            <?php echo $this->items['displacement']; ?>&nbsp;<?php echo JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['engine'] > 0): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ENGINE_TEXT'); ?></span>
                            <?php echo $this->items['engine']; ?>&nbsp;<?php echo JText::_('COM_EXPAUTOSPRO_KW_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['co']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CO2_TEXT'); ?></span>
                            <?php echo $this->items['co']; ?>&nbsp;<?php echo JText::_('COM_EXPAUTOSPRO_GKM_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['fuel_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_FUEL_TEXT'); ?></span>
                            <?php echo $this->items['fuel_name']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['extcolor_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXTCOLOR_TEXT'); ?></span>
                            <?php echo $this->items['extcolor_name']; ?>
                            <?php if ($this->items['specificcolor']): ?>
                                &#40;<?php echo $this->items['specificcolor']; ?>&#41;
                            <?php endif; ?>
                            <?php if ($this->items['metalliccolor']): ?>
                                &#40;<?php echo JText::_('COM_EXPAUTOSPRO_METALLIC_TEXT'); ?>&#41;
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['intcolor_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_INTCOLOR_TEXT'); ?></span>
                            <?php echo $this->items['intcolor_name']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['bodytype_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_BODYTYPE_TEXT'); ?></span>
                            <?php echo $this->items['bodytype_name']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['trans_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_TRANS_TEXT'); ?></span>
                            <?php echo $this->items['trans_name']; ?>
                            <?php if ($this->items['specifictrans']): ?>
                                &#40;<?php echo $this->items['specifictrans']; ?>&#41;
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['drive_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_DRIVE_TEXT'); ?></span>
                            <?php echo $this->items['drive_name']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['doors']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_DOORS_TEXT'); ?></span>
                            <?php echo $this->items['doors']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['seats']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_SEATS_TEXT'); ?></span>
                            <?php echo $this->items['seats']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['length']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_LENGHT_TEXT'); ?></span>
                            <?php echo $this->items['length']; ?>&nbsp;<?php echo JText::_('COM_EXPAUTOSPRO_MILIMETERS_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['width']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_WIDTH_TEXT'); ?></span>
                            <?php echo $this->items['width']; ?>&nbsp;<?php echo JText::_('COM_EXPAUTOSPRO_MILIMETERS_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['unweight']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_UNWEIGHT_TEXT'); ?></span>
                            <?php echo $this->items['unweight']; ?>&nbsp;<?php echo JText::_('COM_EXPAUTOSPRO_KG_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['grweight']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_GRWEIGHT_TEXT'); ?></span>
                            <?php echo $this->items['grweight']; ?>&nbsp;<?php echo JText::_('COM_EXPAUTOSPRO_KG_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['ef1_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXTRAFIELD1_TEXT'); ?></span>
                            <?php echo $this->items['ef1_name']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['ef2_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXTRAFIELD2_TEXT'); ?></span>
                            <?php echo $this->items['ef2_name']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['ef3_name']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXTRAFIELD3_TEXT'); ?></span>
                            <?php echo $this->items['ef3_name']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->expcatfields->get('useprice')): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_PRICE_TEXT'); ?></span>
                            <?php
                            $exptime = '';
                            if($adparams && $adparams->get('exptime'))
                                $exptime = JText::sprintf(JText::_("COM_EXPAUTOSPRO_DETAIL_PAGE_DAY_TEXT"),$adparams->get('exptime'));
                            $price = ExpAutosProExpparams::price_formatdata($this->items['price'], 2);
                            echo $price.$exptime;
                            ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['bprice']): ?>
                        <p>
                            <span class="expautos_bprice"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_BPRICE_TEXT'); ?></span>
                            <?php
                            $price = ExpAutosProExpparams::price_formatdata($this->items['bprice'], 2);
                            echo $price;
                            ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['expprice']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXPPRICE_TEXT'); ?></span>
                            <?php
                            $price = ExpAutosProExpparams::price_formatdata($this->items['expprice'], 2);
                            echo $price;
                            ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->items['vattext']): ?>
                        <p>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_VATTEXT_TEXT'); ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                    <?php if (($this->expparams->get('c_general_showcontact') || (!$this->expparams->get('c_general_showcontact') && $user->id)) && $this->items['cnt_name']): ?>
                <div class="moduletable_menu">
                    <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_VEHICLELOCATION_TEXT'); ?></h3>
                        <?php if ($this->items['cnt_name'] && $this->expcatfields->get('usecountry')): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_COUNTRY_TEXT'); ?></span>
                                <?php echo $this->items['cnt_name']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($this->items['st_name'] && $this->expcatfields->get('usestate')): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STATE_TEXT'); ?></span>
                                <?php echo $this->items['st_name']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($this->items['ct_name'] && $this->expcatfields->get('usecity')): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CITY_TEXT'); ?></span>
                                <?php echo $this->items['ct_name']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($this->items['street'] && $this->expcatfields->get('usestreet')): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STREET_TEXT'); ?></span>
                                <?php echo $this->items['street']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($this->items['zipcode'] && $this->expcatfields->get('usezipcode')): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_LIST_USER_ZIPCODE_TEXT'); ?></span>
                                <?php echo $this->items['zipcode']; ?>
                            </p>
                        <?php endif; ?>
                </div>
                <?php endif; ?>
                    
                <div class="moduletable_menu">
                    <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_TEXT'); ?></h3>
                    <?php if ($this->expparams->get('c_general_showcontact') || (!$this->expparams->get('c_general_showcontact') && $user->id)): ?>
                        <?php if ($expgroupfield->get('c_ulogo') && $this->items['expuser_logo']): ?>
                            <p>
                                <?php if ($expgroupfield->get('c_uwebsite') && $this->items['expuser_web']): ?>
                                    <a href="http://<?php echo $this->items['expuser_web']; ?>" >
                                    <?php endif; ?>
                                    <img src='<?php echo $logolink . $this->items['expuser_logo']; ?>' title="<?php echo $this->items['expuser_companyname']; ?>" />
                                    <?php if ($expgroupfield->get('c_uwebsite') && $this->items['expuser_web']): ?>
                                    </a>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_username') && $this->items['user_name']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_NAME_TEXT'); ?></span>
                                <?php echo $this->items['user_name']; ?>
                                <?php if ($expgroupfield->get('c_lastname') && $this->items['expuser_lastname']): ?>
                                    <?php echo $this->items['expuser_lastname']; ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_username') && $this->items['user_username']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_USERNAME_TEXT'); ?></span>
                                <?php echo $this->items['user_username']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_uphone') && $this->items['expuser_phone']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_PHONE_TEXT'); ?></span>
                                <?php echo $this->items['expuser_phone']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_ucphone') && $this->items['expuser_mobphone']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_MOBPHONE_TEXT'); ?></span>
                                <?php echo $this->items['expuser_mobphone']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_ufax') && $this->items['expuser_fax']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_FAX_TEXT'); ?></span>
                                <?php echo $this->items['expuser_fax']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_email') && $this->items['user_email']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_EMAIL_TEXT'); ?></span>
                                <?php echo $this->items['user_email']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_ucompany') && $this->items['expuser_companyname']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_COMPANY_TEXT'); ?></span>
                                <?php echo $this->items['expuser_companyname']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_ucountry') && $this->items['expuser_country']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_COUNTRY_TEXT'); ?></span>
                                <?php echo $this->items['expuser_country']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_ustate') && $this->items['expuser_state']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STATE_TEXT'); ?></span>
                                <?php echo $this->items['expuser_state']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_ucity') && $this->items['expuser_city']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CITY_TEXT'); ?></span>
                                <?php echo $this->items['expuser_city']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_ustreet') && $this->items['expuser_street']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STREET_TEXT'); ?></span>
                                <?php echo $this->items['expuser_street']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_uzip') && $this->items['expuser_zipcode']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ZIPCODE_TEXT'); ?></span>
                                <?php echo $this->items['expuser_zipcode']; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($expgroupfield->get('c_uwebsite') && $this->items['expuser_web']): ?>
                            <p>
                                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_WEB_TEXT'); ?></span>
                                <a href="http://<?php echo $this->items['expuser_web']; ?>"><?php echo $this->items['expuser_web']; ?></a>
                            </p>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_ONLYREGISTEREDUSER_TEXT'); ?>
                    <?php endif; ?>
                </div>
                <?php if($this->expparams->get('c_admanager_useparams') && (int)$this->items['id'] && $adparams->get('addexample1') && $adparams->get('addexample2')):?>
                    <div class="moduletable_menu">
                    <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ADPARAMS_TEXT'); ?></h3>
                        <?php if($adparams && $adparams->get('addexample1')):?>
                            <p>
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ADPARAMS_EXAMPLE1_TEXT'); ?><?php echo $adparams->get('addexample1');?>
                            </p>
                        <?php endif;?>
                        <?php if($adparams && $adparams->get('addexample2')):?>
                            <p>
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ADPARAMS_EXAMPLE2_TEXT'); ?><?php echo $adparams->get('addexample2');?>
                            </p>
                        <?php endif;?>
                    </div>
                <?php endif;?>
                <?php if($adparams && $adparams->get('expfile')): ?>
                    <div class="moduletable_menu">
                        <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ATTACHED_FILE_TEXT'); ?></h3>
                            <p>
                                <a href="<?php echo ExpAutosProExpparams::FilesUrlPatch().$adparams->get('expfile'); ?>" target="_blank"><?php echo $adparams->get('expfile'); ?></a>
                            </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

