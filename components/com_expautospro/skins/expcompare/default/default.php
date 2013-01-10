<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

defined('_JEXEC') or die;

$params_file = JPATH_COMPONENT . '/skins/expcompare/default/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('expcompare','default');

$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expcompare/default/css/default.css');
$topmoduleposition = $this->expparams->get('c_admanager_cmpage_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_cmpage_bmpname');
$thumbsize = $this->expparams->get('c_images_thumbsize_width');
$items = $this->items;
$countitems = count($items);
$maxitems = $this->expparams->get('c_admanager_compare_maxads');
if($countitems > $maxitems){
    $countnum = $maxitems;
}else{
    $countnum = $countitems;
}
$index = 0;
foreach ($items as $item) {
    if ($index < $maxitems) {
        $ids[] = $item->id;
        $catids[] = $item->catid;
        $makes[] = $item->make;
        $models[] = $item->model;
        $img_names[] = $item->img_name;
        $category_names[] = $item->category_name;
        $make_names[] = $item->make_name;
        $model_names[] = $item->model_name;
        $pricies[] = $item->price;
        $years[] = $item->year;
        $months[] = $item->month;
        $mileages[] = $item->mileage;
        $displacements[] = $item->displacement;
        $engines[] = $item->engine;
        $fuel_names[] = $item->fuel_name;
        $bodytype_names[] = $item->bodytype_name;
        $trans_names[] = $item->trans_name;
        $extcolor_names[] = $item->extcolor_name;
        $specificmodels[] = $item->specificmodel;
        $doorss[] = $item->doors;
        $seatss[] = $item->seats;
        $equipments[] = $item->equipment;
        $expuser_companynames[] = $item->expuser_companyname;
        $expuser_countrys[] = $item->expuser_country;
        $expuser_zipcodes[] = $item->expuser_zipcode;
        $expuser_phones[] = $item->expuser_phone;
        $expuser_mobphones[] = $item->expuser_mobphone;
        $expuser_faxs[] = $item->expuser_fax;
        $expuser_webs[] = $item->expuser_web;
        $users[] = $item->user;
    }
    $index++;
}
?>
<style>
#expautospro table td.expcompare_title{
    width: 100px;
}
#expautospro table td{
    width:<?php echo 600/$countnum;?>px;
}   
</style>
<div id="expautospro">
    <div class="expautospro_topmodule">
        <div class="expautospro_topmodule_pos">
<?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_cmpage_tmpstyle')); ?>
        </div>
        <div class="expautospro_clear"></div>
    </div>

<!-- Skins Module Position !-->
<?php if($this->expparams->get('c_admanager_compare_showskin')):?>

<div id="expskins_module">
    <?php
    $expmodparam = array('folder' => $this->expskins);
    echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
    ?>
</div>
<div class="expautospro_clear"></div>
<?php endif; ?>

    <div class="exp_autos_compare">
    <h2><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_COMPAREPAGE_TEXT'); ?></h2>
        <?php if($countitems > $maxitems):?>
            <h3 class="compare_errorcount"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_COUNTERROR_TEXT').$maxitems; ?></h3>
        <?php endif;?>
        <table>
            <?php if (!empty($img_names)): ?>
                <tr>
                    <td class="expcompare_title_img">
                        <div>
                            <?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_COUNT_ADS_TEXT').$countnum; ?>
                        </div>
                    </td>
                <?php
                foreach ($img_names as $key => $img_name):
                    //print_r($ids[$key]);
                    ?>
                        <td>
                        <?php
                        $link = JRoute::_('index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . (int) $ids[$key] . '&amp;catid=' . (int) $catids[$key] . '&amp;makeid=' . (int) $makes[$key] . '&amp;modelid=' . (int) $models[$key] . '&amp;Itemid=' . (int) $expitem);
                        if($this->expparams->get('c_admanager_compare_showimg')){
                            if ($img_name) {
                                $img_file = '<a href="' . $link . '"><span></span><img src="' . ExpAutosProExpparams::ImgUrlPatchThumbs() . $img_name . '" alt="" /></a>';
                            } else {
                                $img_file = '<a href="' . $link . '"><span></span><img src="' . ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg" alt="" /></a>';
                            }
                            echo $img_file;
                        }
                        ?>
                            <br />
                            <a class="expcompare_title_href" href="<?php echo $link; ?>">
                                <span><?php echo $make_names[$key]; ?> <?php echo $model_names[$key]; ?> <?php echo $specificmodels[$key]; ?></span>
                            </a>
                        </td>
                <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($category_names) && $this->expparams->get('c_admanager_compare_showcat')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_CATEGORY_TEXT'); ?></td>
    <?php foreach ($category_names as $category_name): ?>
                        <td>
                        <?php
                        if ($category_name)
                            echo $category_name;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($pricies) && $this->expparams->get('c_admanager_compare_showprice')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_PRICE_TEXT'); ?></td>
    <?php foreach ($pricies as $price): ?>
                        <td>
                        <?php
                        $price_num = ExpAutosProExpparams::price_formatdata($price);
                        echo $price_num;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($years) && $this->expparams->get('c_admanager_compare_showyear')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_YEAR_TEXT'); ?></td>
                        <?php foreach ($years as $key => $year): ?>
                        <td>
                        <?php
                        $year_text = '';
                        if ($months[$key] > 0) {
                            $year_text .= $months[$key] . "/";
                        }
                        $year_text .=$year;
                        echo $year_text;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($mileages) && $this->expparams->get('c_admanager_compare_showmileage')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_MILEAGE_TEXT') . JText::_('COM_EXPAUTOSPRO_KM_TEXT'); ?></td>
                        <?php foreach ($mileages as $mileage): ?>
                        <td>
                        <?php
                        if ($mileage > 0)
                            echo $mileage;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($displacements) && $this->expparams->get('c_admanager_compare_showengine')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_DISPLACMENT_TEXT') . JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT'); ?></td>
                        <?php foreach ($displacements as $key => $displacement): ?>
                        <td>
                        <?php
                        $disp_text = '';
                        if ($displacement > 0)
                            $disp_text = '';
                        $disp_text .= $displacement;
                        if ($engines[$key] > 0)
                            $disp_text .= "(" . $engines[$key] . JText::_('COM_EXPAUTOSPRO_KW_TEXT') . ")";
                        echo $disp_text;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($fuel_names) && $this->expparams->get('c_admanager_compare_showfuel')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_FUEL_TEXT'); ?></td>
                        <?php foreach ($fuel_names as $fuel_name): ?>
                        <td>
                        <?php
                        if ($fuel_name)
                            echo $fuel_name;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($bodytype_names) && $this->expparams->get('c_admanager_compare_showbodytype')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_BODYTYPE_TEXT'); ?></td>
                        <?php foreach ($bodytype_names as $bodytype_name): ?>
                        <td>
                        <?php
                        if ($bodytype_name)
                            echo $bodytype_name;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($trans_names) && $this->expparams->get('c_admanager_compare_showtrans')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_TRANS_TEXT'); ?></td>
                        <?php foreach ($trans_names as $trans_name): ?>
                        <td>
                        <?php
                        if ($trans_name)
                            echo $trans_name;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($extcolor_names) && $this->expparams->get('c_admanager_compare_showextcolor')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_EXTCOLOR_TEXT'); ?></td>
                        <?php foreach ($extcolor_names as $extcolor_name): ?>
                        <td>
                        <?php
                        if ($extcolor_name)
                            echo $extcolor_name;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($doorss) && $this->expparams->get('c_admanager_compare_showdoors')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_DOORS_TEXT'); ?></td>
                        <?php foreach ($doorss as $doors): ?>
                        <td>
                        <?php
                        if ($doors)
                            echo $doors;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($seatss) && $this->expparams->get('c_admanager_compare_showseats')): ?>
                <tr>
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_SEATS_TEXT'); ?></td>
                        <?php foreach ($seatss as $seats): ?>
                        <td>
                        <?php
                        if ($seats)
                            echo $seats;
                        ?>
                        </td>
                        <?php endforeach; ?>
                </tr>
                <?php endif; ?>
            <?php if (isset($equipments) && $this->expparams->get('c_admanager_compare_showequip')): ?>
                <tr valign="top">
                    <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_EQUIPMENTS_TEXT'); ?></td>
                        <?php foreach ($equipments as $key => $equipment): ?>
                        <td>
                            <table class="expcompare_equiptable" valign="top">
                        <?php
                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        $query->select('id, name');
                        $query->from('#__expautos_equipment');
                        $query->where('state=1');
                        $query->where('id IN(' . $equipment . ')');
                        $query->order('ordering');
                        $db->setQuery($query);
                        $equipmentn = $db->loadObjectList();

                        $c = 0;
                        $kr = $this->expparams->get('c_admanager_compare_equip_column');
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
                                //$metaexp[] = $equipmentn[$i]->name;
                                //$metakeyexp[] = $equipmentn[$i]->name;
                                endfor;
                                ?>
                            </table>
                        </td>
                            <?php endforeach; ?>
                </tr>
<?php endif; ?>
                <?php if ($this->expparams->get('c_general_showcontact')): ?>
                    <?php if (isset($users)): ?>
                    <tr>
                        <td class="expcompare_title"><?php echo JText::_('COM_EXPAUTOSPRO_COMPARE_SELLER_TEXT'); ?></td>
                    <?php foreach ($users as $key => $user): ?>
                            <td>
                                <p><?php echo $expuser_companynames[$key]; ?></p>
                                <p><?php echo $expuser_countrys[$key]; ?></p>
                                <p><?php echo $expuser_phones[$key]; ?></p>
                                <p><?php echo $expuser_mobphones[$key]; ?></p>
                                <p><?php echo $expuser_faxs[$key]; ?></p>
                                <p><?php echo $expuser_zipcodes[$key]; ?></p>
                                <p><a href="http://<?php echo $expuser_webs[$key]; ?>"><?php echo $expuser_webs[$key]; ?></a></p>
                                <p><a href="<?php echo JRoute::_('index.php?option=com_expautospro&amp;view=expdealerdetail&amp;userid=' . (int) $user . '&amp;Itemid=' . (int) $expitem); ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_MOREUSERINFO_TEXT'); ?></a></p>
                            </td>
        <?php endforeach; ?>
                    </tr>
    <?php endif; ?>
                <?php endif; ?>
                <?php
                /*
                 * 
                  $expuser_countrys[]= $item->expuser_country;
                  $expuser_zipcodes[]= $item->expuser_zipcode;
                  $expuser_phones[]= $item->expuser_phone;
                  $expuser_mobphones[]= $item->expuser_mobphone;
                  $expuser_faxs[]= $item->expuser_fax;
                  $expuser_webs[]= $item->expuser_web;
                  $users[]= $item->user;
                  if($ids){
                  echo "<tr>";
                  echo "<td>Id</td>";
                  foreach($ids as $id){
                  echo "<td>".$id."</td>";
                  }
                  echo "<tr>";
                  }
                  if($img_names){
                  echo "<tr>";
                  echo "<td>Images</td>";
                  foreach($img_names as $img_name){
                  echo "<td>".$img_name."</td>";
                  }
                  echo "<tr>";
                  }
                  if($pricies){
                  echo "<tr>";
                  echo "<td>Images</td>";
                  foreach($pricies as $price){
                  echo "<td>".$price_num = ExpAutosProExpparams::price_formatdata($item->price);
                  echo $price_num;."</td>";
                  }
                  echo "<tr>";
                  }
                 */
//print_r($items);
                ?> 
        </table>
    </div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
    <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_cmpage_bmpstyle')); ?>
    </div>
<?php endif; ?>
</div>
