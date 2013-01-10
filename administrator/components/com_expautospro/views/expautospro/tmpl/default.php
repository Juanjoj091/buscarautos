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
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
JToolBarHelper::title(JText::_('COM_EXPAUTOSPRO_TEXT'), 'expautospro.png');
?>
<table width="100%" border="0">
    <tr>
        <td width="60%" valign="top">
            <div id="cpanel">
                <div class="icon">
                    <a href="<?php echo JRoute::_('index.php?option=com_categories&extension=com_expautospro', false); ?>">
                        <?php echo JHTML::_('image', JURI::root().'administrator/components/com_expautospro/assets/images/categories.png' , NULL, NULL, JText::_('COM_EXPAUTOSPRO_CATEGORIES_TEXT') ); ?>
                        <span><?php echo JText::_('COM_EXPAUTOSPRO_CATEGORIES_TEXT'); ?></span>
                    </a>
                </div>
                <?php
                //echo $this->iconimg('expcats', 'categories.png', JText::_('COM_EXPAUTOSPRO_CATEGORIES_TEXT'));
                echo $this->iconimg('expmakes', 'make.png', JText::_('COM_EXPAUTOSPRO_MAKES_TEXT'));
                echo $this->iconimg('expmods', 'model.png', JText::_('COM_EXPAUTOSPRO_MODELS_TEXT'));
                echo $this->iconimg('expbodytypes', 'bodytype.png', JText::_('COM_EXPAUTOSPRO_BODYTYPES_TEXT'));
                echo $this->iconimg('expdrives', 'drive.png', JText::_('COM_EXPAUTOSPRO_DRIVES_TEXT'));
                echo $this->iconimg('expfuels', 'fuel.png', JText::_('COM_EXPAUTOSPRO_FUELS_TEXT'));
                echo $this->iconimg('exptrans', 'tran.png', JText::_('COM_EXPAUTOSPRO_TRANSMISSIONS_TEXT'));
                echo $this->iconimg('expcondits', 'condition.png', JText::_('COM_EXPAUTOSPRO_CONDITIONS_TEXT'));
                echo $this->iconimg('expcolors', 'color.png', JText::_('COM_EXPAUTOSPRO_COLORS_TEXT'));
                echo $this->iconimg('expcountrs', 'country.png', JText::_('COM_EXPAUTOSPRO_COUNTRS_TEXT'));
                echo $this->iconimg('expstates', 'state.png', JText::_('COM_EXPAUTOSPRO_STATE_TEXT'));
                echo $this->iconimg('expcits', 'cities.png', JText::_('COM_EXPAUTOSPRO_CITS_TEXT'));
                echo $this->iconimg('expeqcats', 'catequipment.png', JText::_('COM_EXPAUTOSPRO_EQCATS_TEXT'));
                echo $this->iconimg('expeqs', 'equipment.png', JText::_('COM_EXPAUTOSPRO_EQS_TEXT'));
                echo $this->iconimg('expuserlevels', 'userlevel.png', JText::_('COM_EXPAUTOSPRO_USERLEVELS_TEXT'));
                echo $this->iconimg('expusers', 'user.png', JText::_('COM_EXPAUTOSPRO_USERS_TEXT'));
                echo $this->iconimg('expcurrs', 'currency.png', JText::_('COM_EXPAUTOSPRO_CURRENCIES_TEXT'));
                echo $this->iconimg('expextrafield1s', 'extrafield1.png', JText::_('COM_EXPAUTOSPRO_EXTRAFIELD1S_TEXT'));
                echo $this->iconimg('expextrafield2s', 'extrafield2.png', JText::_('COM_EXPAUTOSPRO_EXTRAFIELD2S_TEXT'));
                echo $this->iconimg('expextrafield3s', 'extrafield3.png', JText::_('COM_EXPAUTOSPRO_EXTRAFIELD3S_TEXT'));
                echo $this->iconimg('exppayments', 'payment.png', JText::_('COM_EXPAUTOSPRO_PAYMENTS_TEXT'));
                echo $this->iconimg('export', 'export.png', JText::_('COM_EXPAUTOSPRO_EXPORT_TEXT'));
                echo $this->iconimg('import', 'import.png', JText::_('COM_EXPAUTOSPRO_IMPORT_TEXT'));
                echo $this->iconimg('expconfig&layout=edit&id=1', 'config.png', JText::_('COM_EXPAUTOSPRO_CONFIG_TEXT'));
                echo $this->iconimg('expadmanagers', 'admanager.png', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TEXT'));
                ?>
            </div>
        </td>
        <td width="40%" valign="top"><?php
                echo $this->pane->startPane('expautos-pane');
                $title = JText::_('COM_EXPAUTOSPRO_FP_EXPABOUT_TEXT');
                echo $this->pane->startPanel($title, 'about-page');
                ?>
            <table class="admintable">     
                <tr>
                    <td width="180" class="key" style="width:180px;">
                        <img src="<?php echo JURI::root(); ?>administrator/components/com_expautospro/img/expautos_logo.png"/>
                    </td>
                    <td valign="top">
                        <h3><?php echo JText::_('COM_EXPAUTOSPRO_FP_EXPHEADER_TEXT'); ?></h3>
                        <?php echo JText::_('COM_EXPAUTOSPRO_FP_EXPMORE_TEXT'); ?>
                    </td>
                </tr> 
                <tr>
                    <td width="180" class="key" style="width:180px;">
                        <label for="title">
                            <?php echo JText::_('COM_EXPAUTOSPRO_FP_EXPVERSION_TEXT'); ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->version_num;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td width="180" class="key" style="width:180px;">
                        <label for="title">
                            <?php echo JText::_('COM_EXPAUTOSPRO_FP_LICENSENUMBER_TEXT'); ?>
                        </label>
                    </td>
                    <td>
                        
                        <?php if($this->license_num){
                        echo $this->license_num;
                        echo "<br /><span class='hasTip' title='".JText::_( 'COM_EXPAUTOSPRO_FP_INSERT_LICENSEAUTHOR_TEXT' )."'><a href='".JRoute::_("index.php?option=com_expautospro&view=expautospro&task=expautospro.sendlicense&number=".$this->license_num, false)."'>".JText::_('COM_EXPAUTOSPRO_FP_LICENSENUMBER_SEND_TEXT')."</a></span>";
                        }else{
                        echo '<span class="hasTip" title="'.JText::_( 'COM_EXPAUTOSPRO_FP_INSERT_LICENSENUMBER_DESC' ).'"><a href="'.JRoute::_("index.php?option=com_expautospro&view=expconfig&layout=edit&id=1", false).'">'.JText::_('COM_EXPAUTOSPRO_FP_INSERT_LICENSENUMBER_TEXT').'</a></span>';
                         } ?>
                    </td>
                </tr>
            </table>
            <?php
            echo $this->pane->endPanel();
            $title = JText::_('COM_EXPAUTOSPRO_FP_EXPSTATSADS_TEXT');
            echo $this->pane->startPanel($title, 'stats-page');
            ?>
            <table class="admintable">   
                <tr>
                    <td width="200" class="key" style="width:200px;">
                        <label for="title">
                            <?php echo JText::_('COM_EXPAUTOSPRO_FP_EXPTOTALADS_TEXT'); ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->total_ad;
                        ?>
                    </td>
                </tr>   
                <tr>
                    <td width="200" class="key" style="width:200px;">
                        <label for="title">
                            <?php echo JText::_('COM_EXPAUTOSPRO_FP_EXPTOTALPUBLADS_TEXT'); ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->total_publ;
                        ?>
                    </td>
                </tr>   
                <tr>
                    <td width="200" class="key" style="width:200px;">
                        <label for="title">
                            <?php echo JText::_('COM_EXPAUTOSPRO_FP_EXPTOTALUNPUBLADS_TEXT'); ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->total_unpubl;
                        ?>
                    </td>
                </tr>
            </table>
            <?php
            echo $this->pane->endPanel();
            $title = JText::_('COM_EXPAUTOSPRO_FP_EXPSTATSUSER_TEXT');
            echo $this->pane->startPanel($title, 'statsuser-page');
            ?>
            <table class="admintable"> 
                <tr>
                    <td width="200" class="key" style="width:200px;">
                        <label for="title">
                            <?php echo JText::_('COM_EXPAUTOSPRO_FP_EXPTOTALUSERS_TEXT'); ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->total_user;
                        ?>
                    </td>
                </tr>   
            </table>
            <?php
            echo $this->pane->endPanel();
            echo $this->pane->endPane();
            ?> 
        </td>
    </tr>
</table>