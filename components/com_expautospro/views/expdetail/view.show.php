<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_COMPONENT . '/helpers/icon.php';

class ExpautosproViewExpdetail extends JView {

    function display($tpl = null) {
        $state = $this->get('State');
        $items = $this->get('Item');
        $expparams = $this->get('Expparams');
        $expcatfields = $this->get('Expcatfields');
        $expimages = $this->get('Expimages');
        $expcatequipment = $this->get('Expcatequipment');
        $expgroupfield = $this->get('Expgroupfields');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expcatfields', $expcatfields);
        $this->assignRef('expimages', $expimages);
        $this->assignRef('expcatequipment', $expcatequipment);
        $this->assignRef('expgroupfield', $expgroupfield);

        $this->_showDocument();
    }

    protected function _showDocument() {
        ?>
        <script type="text/javascript">
        function ExpSelectAll(id)
        {
            document.getElementById(id).focus();
            document.getElementById(id).select();
        }
        </script>
        <?php
        $href_site = JRoute::_(JURI::root() . 'index.php?option=com_expautospro&view=expdetail&id=' . (int) $this->items['id']);
        $user = JFactory::getUser();
        $imgquery = $this->expimages;
        $expcatequipment = $this->expcatequipment;
        $expgroupfield = $this->expgroupfield;
        $tmblink = ExpAutosProExpparams::ImgUrlPatchThumbs();
        $middlelink = ExpAutosProExpparams::ImgUrlPatchMiddle();
        $biglink = ExpAutosProExpparams::ImgUrlPatchBig();
        $logolink = ExpAutosProExpparams::ImgUrlPatchLogo();
        $imglink = ExpAutosProExpparams::ImgUrlPatch();

        $topname = $this->items['make_name'] . "&nbsp;";
        $topname .= $this->items['model_name'] . "&nbsp;";
        if ($this->items['specificmodel'])
            $topname .= $this->items['specificmodel'] . "&nbsp;";
        if ($this->items['displacement'] > 0)
            $topname .= $this->items['displacement'] . JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT') . "&nbsp;";
        if ($this->items['engine'] > 0)
            $topname .= $this->items['engine'] . JText::_('COM_EXPAUTOSPRO_KW_TEXT');
        ?>
        <textarea cols="70" rows="30" id="txtarea" onClick="ExpSelectAll('txtarea');">
                <table align="center" bgcolor="" border="0" cellpadding="0" cellspacing="0" width="">
                    <tr>
                        <td align="center">
                            <font size="6"><a href="<?php echo $href_site; ?>"><?php echo $topname; ?></a></font>		
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php if ($imgquery): ?>
                                    <table style="">
                                <?php
                                $c = 0;
                                $kr = 2;
                                foreach ($imgquery as $img) {
                                    if (($c % $kr) == 0) {
                                        echo '</tr>';
                                        echo '<tr>';
                                    }
                                    ?>
                                                <td style="">
                                                    <img src="<?php echo $middlelink . $img['imgname']; ?>" title="<?php echo $img['imgdescription']; ?>" alt="<?php echo $img['imgdescription']; ?>" /> 
                                                </td>
                                    <?php
                                    $c++;
                                }
                                ?>
                                    </table>
                        <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <font size="6"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_TEXT'); ?></font>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php
                        echo "<br />" . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ID_TEXT') . $this->items['id'] . "<br />";
                        ?>
                        <?php
                        if ($this->items['cond_name']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CONDITION_TEXT') . $this->items['cond_name'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['stocknum']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STOCKNUMBER_TEXT') . $this->items['stocknum'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['vincode']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_VINCODE_TEXT') . $this->items['vincode'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['mileage']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_MILEAGE_TEXT') . $this->items['mileage'] . "&nbsp;" . JText::_('COM_EXPAUTOSPRO_KM_TEXT') . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['year']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_YEAR_TEXT') . ": ";
                            if ($this->items['month']) {
                                echo $this->items['month'] . "&#47;";
                            }
                            echo $this->items['year'];
                            echo "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['displacement']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ENGINESIZE_TEXT') . $this->items['displacement'] . " " . JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT') . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['engine']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ENGINE_TEXT') . $this->items['engine'] . " " . JText::_('COM_EXPAUTOSPRO_KW_TEXT') . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['co']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CO2_TEXT') . $this->items['co'] . " " . JText::_('COM_EXPAUTOSPRO_GKM_TEXT') . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['fuel_name']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_FUEL_TEXT') . $this->items['fuel_name'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['extcolor_name']) {
                            if ($this->items['specificcolor']) {
                                $speccolor = " (" . $this->items['specificcolor'] . ")";
                            }
                            if ($this->items['metalliccolor']) {
                                $metalliccc = " (" . JText::_('COM_EXPAUTOSPRO_METALLIC_TEXT') . ")";
                            }
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXTCOLOR_TEXT') . $extcolor . $speccolor . $metalliccc . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['intcolor_name']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_INTCOLOR_TEXT') . $this->items['intcolor_name'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['bodytype_name']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_BODYTYPE_TEXT') . $this->items['bodytype_name'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['trans_name']) {
                            if ($this->items['specifictrans']) {
                                $spectrans = " (" . $this->items['specifictrans'] . ")";
                            }
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_TRANS_TEXT') . $this->items['trans_name'] . $spectrans . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['drive_name']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_DRIVE_TEXT') . $this->items['drive_name'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['doors']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_DOORS_TEXT') . $this->items['doors'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['seats']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_SEATS_TEXT') . $this->items['seats'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['length']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_LENGHT_TEXT') . $this->items['length'] . " " . JText::_('COM_EXPAUTOSPRO_MILIMETERS_TEXT') . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['width']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_WIDTH_TEXT') . $this->items['width'] . " " . JText::_('COM_EXPAUTOSPRO_MILIMETERS_TEXT') . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['unweight']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_UNWEIGHT_TEXT') . $this->items['unweight'] . " " . JText::_('COM_EXPAUTOSPRO_KG_TEXT') . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['grweight']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_GRWEIGHT_TEXT') . $this->items['grweight'] . " " . JText::_('COM_EXPAUTOSPRO_KG_TEXT') . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['ef1_name']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXTRAFIELD1_TEXT') . $this->items['ef1_name'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['ef2_name']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXTRAFIELD2_TEXT') . $this->items['ef2_name'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['ef3_name']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXTRAFIELD3_TEXT') . $this->items['ef3_name'] . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['price']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_PRICE_TEXT');
                            $price = ExpAutosProExpparams::price_formatdata($this->items['price'], 2);
                            echo $price . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['bprice']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_BPRICE_TEXT');
                            $price = ExpAutosProExpparams::price_formatdata($this->items['bprice'], 2);
                            echo $price . "<br />";
                        }
                        ?>
                        <?php
                        if ($this->items['expprice']) {
                            echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXPPRICE_TEXT');
                            $price = ExpAutosProExpparams::price_formatdata($this->items['expprice'], 2);
                            echo $price . "<br />";
                        }
                        ?>
                        <?php if ($this->expparams->get('c_general_showcontact') || (!$this->expparams->get('c_general_showcontact') && $user->id)) { ?>
                                    <br />
                            <?php
                            if ($this->items['cnt_name'] && $this->expcatfields->get('usecountry')) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_COUNTRY_TEXT') . $this->items['cnt_name'] . "<br />";
                            }
                            ?>
                            <?php
                            if ($this->items['st_name'] && $this->expcatfields->get('usestate')) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STATE_TEXT') . $this->items['st_name'] . "<br />";
                            }
                            ?>
                            <?php
                            if ($this->items['ct_name'] && $this->expcatfields->get('usecity')) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CITY_TEXT') . $this->items['ct_name'] . "<br />";
                            }
                            ?>
                            <?php
                            if ($this->items['street'] && $this->expcatfields->get('usestreet')) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STREET_TEXT') . $this->items['street'] . "<br />";
                            }
                            ?>
                        <?php } ?>
                        </td>
                    </tr>
                <?php if ($this->expcatfields->get('useequipment') && $this->items['equipment'] && $expcatequipment): ?>
                            <tr>
                                <td align="center">
                                    <font size="6"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_EQUIPMENT_TEXT'); ?></font>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
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
                                    endfor;
                                endforeach;
                                ?>
                                    </table>
                                    <br />
                                    <br />
                                </td>
                            </tr>
                <?php endif; ?>
                <?php if (($this->expcatfields->get('usefconsum') || $this->expcatfields->get('useadacceleration') || $this->expcatfields->get('usemaxspeed')) && ($this->items['fconsumcity'] || $this->items['fconsumfreeway'] || $this->items['fconsumcombined'] || $this->items['adacceleration'] || $this->items['maxspeed'] || $this->items['otherinfo'])): ?>
                            <tr>
                                <td align="center">
                                    <font size="6"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_OTHERINFO_TEXT'); ?></font>
                                </td>
                            </tr>
                    <?php if ($this->items['otherinfo']) { ?>
                                    <tr>
                                        <td>
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_OTHERINFOS_TEXT'); ?> <?php echo $rows->otherinfo; ?>
                                        </td>
                                    </tr>
                    <?php } ?>
                            <tr>
                                <td>
                            <?php if ($this->items['fconsumcity']) { ?>
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_FUELINCITY_TEXT'); ?> <?php echo $this->items['fconsumcity'] ?> <?php echo JText::_('COM_EXPAUTOSPRO_LTRKM_TEXT'); ?><br />
                            <?php } ?>
                            <?php if ($this->items['fconsumfreeway']) { ?>
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_FUELONFREEWAY_TEXT'); ?> <?php echo $this->items['fconsumfreeway']; ?> <?php echo JText::_('COM_EXPAUTOSPRO_LTRKM_TEXT'); ?><br />
                            <?php } ?>
                            <?php if ($this->items['fconsumcombined']) { ?>
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_FUELAVERAGE_TEXT'); ?> <?php echo $this->items['fconsumcombined']; ?> <?php echo JText::_('COM_EXPAUTOSPRO_LTRKM_TEXT'); ?><br />
                            <?php } ?>
                            <?php if ($this->items['adacceleration']) { ?>
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ACCELERATION_TEXT'); ?> <?php echo $this->items['adacceleration']; ?> <?php echo JText::_('COM_EXPAUTOSPRO_SECUND_S_TEXT'); ?><br />
                            <?php } ?>
                            <?php if ($this->items['maxspeed']) { ?>
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_MAXSPEED_TEXT'); ?> <?php echo $this->items['maxspeed']; ?> <?php echo JText::_('COM_EXPAUTOSPRO_KMHOUR_TEXT'); ?><br />
                            <?php } ?>
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php
                if ($this->expparams->get('c_general_showcontact') || (!$this->expparams->get('c_general_showcontact') && $user->id)) {
                    ?>
                        <tr>
                            <td align="center">
                                <font size="6"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_TEXT'); ?></font>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            if ($this->items['user_name']) {
                                echo "<br />" . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_NAME_TEXT') . $this->items['user_name'] . " " . $this->items['expuser_lastname'] . "<br />";
                            }
                            if ($expgroupfield->get('c_username') && $this->items['user_username']) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_USERNAME_TEXT') . $this->items['user_username'] . "<br />";
                            }
                            if ($expgroupfield->get('c_uphone') && $this->items['expuser_phone']) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_PHONE_TEXT') . $this->items['expuser_phone'] . "<br />";
                            }
                            if ($expgroupfield->get('c_ucphone') && $this->items['expuser_mobphone']) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_MOBPHONE_TEXT') . $this->items['expuser_mobphone'] . "<br />";
                            }
                            if ($expgroupfield->get('c_ufax') && $this->items['expuser_fax']) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_FAX_TEXT') . $this->items['expuser_fax'] . "<br />";
                            }
                            if ($expgroupfield->get('c_email') && $this->items['user_email']) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_EMAIL_TEXT') . $this->items['user_email'] . "<br />";
                            }
                            if ($expgroupfield->get('c_ucompany') && $this->items['expuser_companyname']) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_COMPANY_TEXT') . $this->items['expuser_companyname'] . "<br />";
                            }
                            if ($expgroupfield->get('c_ucountry') && $this->items['expuser_country'] && $this->expcatfields->get('usecountry')) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_COUNTRY_TEXT') . $this->items['expuser_country'] . "<br />";
                            }
                            if ($expgroupfield->get('c_ustate') && $this->items['expuser_state'] && $this->expcatfields->get('usestate')) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STATE_TEXT') . $this->items['expuser_state'] . "<br />";
                            }
                            if ($expgroupfield->get('c_ucity') && $this->items['expuser_city'] && $this->expcatfields->get('usecity')) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CITY_TEXT') . $this->items['expuser_city'] . "<br />";
                            }
                            if ($expgroupfield->get('c_ustreet') && $this->items['expuser_street'] && $this->expcatfields->get('usestreet')) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STREET_TEXT') . $this->items['expuser_street'] . "<br />";
                            }
                            if ($expgroupfield->get('c_uzip') && $this->items['expuser_zipcode']) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ZIPCODE_TEXT') . $this->items['expuser_zipcode'] . "<br />";
                            }
                            if ($expgroupfield->get('c_uwebsite') && $this->items['expuser_web']) {
                                echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_WEB_TEXT') . " <a href='http://" . $this->items['expuser_web'] . "'>" . $this->items['expuser_web'] . "</a><br />";
                            }
                            ?>
                                        <a href="<?php echo JRoute::_(JURI::root() . 'index.php?option=com_expautospro&amp;view=explist&amp;userid=' . (int) $this->items['user']); ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_SHOWALLADS_TEXT'); ?></a>
                                        <br/>
                                        <a href="<?php echo JRoute::_(JURI::root() . 'index.php?option=com_expautospro&amp;view=expdealerdetail&amp;userid=' . (int) $this->items['user']); ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_MOREUSERINFO_TEXT'); ?></a>
                            </td>
                        </tr>
                    <?php
                }
                ?>
                    <tr>
                        <td>
                        </td>
                    </tr>
                </table>
        </textarea>
        <?php
    }

}
?>