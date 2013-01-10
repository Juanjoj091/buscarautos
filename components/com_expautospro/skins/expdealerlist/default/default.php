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

$params_file = JPATH_COMPONENT . '/skins/expdealerlist/default/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('expdealerlist','default');

$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$document = JFactory::getDocument();
$user = JFactory::getUser();
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expdealerlist/default/css/default.css');
$topmoduleposition = $this->expparams->get('c_admanager_dealerlspage_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_dealerlspage_bmpname');
$logowidth = $this->expparams->get('c_admanager_dealerlspage_logowidth');
$logoheight = $this->expparams->get('c_admanager_dealerlspage_logoheight');
$userid = JRequest::getInt('userid', 0);
?>
<div class="expautospro_clear"></div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_dealerlspage_tmpstyle')); ?>
    </div>
<?php endif; ?>

<!-- Skins Module Position !-->
<?php if($this->expparams->get('c_admanager_dealerlspage_showskin')):?>
<div id="expskins_module">
    <?php
    $expmodparam = array('folder' => $this->expskins);
    echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
    ?>
</div>
<div class="expautospro_clear"></div>
<?php endif; ?>

<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
    <div id="expautospro">
        <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALERLIST_TEXT') ?></h2>
        <center>
            <table class="explist">
                <thead>
                    <tr>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_uselogo')):?>
                        <th width="<?php echo $logowidth; ?>">
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_LIST_LOGO_TEXT'); ?>
                        </th>
                        <?php endif; ?>
                        <th>
                            <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_DEALER_LIST_COMPANYNAME_TEXT', 'a.companyname', $listDirn, $listOrder); ?>
                        </th>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_usecountry')):?>
                        <th>
                            <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_DEALER_LIST_COUNTRY_TEXT', 'cnt_name', $listDirn, $listOrder); ?>
                        </th>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_usestate')):?>
                        <th>
                            <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_DEALER_LIST_STATE_TEXT', 'st_name', $listDirn, $listOrder); ?>
                        </th>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_usecity')):?>
                        <th>
                            <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_DEALER_LIST_CITY_TEXT', 'ct_name', $listDirn, $listOrder); ?>
                        </th>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_usezipcode')):?>
                        <th>
                            <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CP_DEALER_LIST_ZIPCODE_TEXT', 'a.zipcode', $listDirn, $listOrder); ?>
                        </th>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_general_showcontact') || (!$this->expparams->get('c_general_showcontact') && $user->id)):?>
                        <th>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_LIST_CONTACTINFO_TEXT'); ?>
                        </th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <div id="explimitbox">
                                <?php echo $this->pagination->getLimitBox(); ?>
                            </div>
                            <div class="expautospro_clear"></div>
                            <div id="expresultcounter">
                                <?php echo $this->pagination->getResultsCounter(); ?>
                            </div>
                            <div class="expautospro_clear"></div>
                            <div id="exppagelinks">
                                <?php echo $this->pagination->getPagesLinks(); ?>
                            </div>
                            <div class="expautospro_clear"></div>
                            <div id="exppagescounter">
                                <?php echo $this->pagination->getPagesCounter(); ?>
                            </div>
                            <div class="expautospro_clear"></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    foreach ($this->items as $i => $item) :
                        //print_r($item);
                        $link = JRoute::_( 'index.php?option=com_expautospro&amp;view=expdealerdetail&amp;userid=' . (int) $item->userid.'&amp;Itemid='.(int) $expitem);
                        
                        if ($item->ucommercial)
                            $imgclass = "commercial";
                        elseif ($item->utop)
                            $imgclass = "top";
                        elseif ($item->uspecial)
                            $imgclass = "special";
                        else
                            $imgclass="";
                        
                        if ($item->logo) {
                            $logo_file = '<a href="' . $link . '"><span></span><img src="' . ExpAutosProImages::ImgUrlPatchLogo() . $item->logo . '" alt="' . $item->companyname . '" width="' . $logowidth . '" height="' . $logoheight . '" /></a>';
                        } else {
                            $logo_file = '<a href="' . $link . '"><span></span><img src="' . JURI::root() . 'components/com_expautospro/skins/expdealerlist/default/images/no_logo.png" alt="' . $item->companyname . '" width="' . $logowidth . '" height="' . $logoheight . '" /></a>';
                        }
                        
                        ?>
                    
                    <tr class="explistrow<?php echo $i % 2; ?>&#32;<?php echo $imgclass; ?>">
                        <?php if($this->expparams->get('c_admanager_dealerlspage_uselogo')):?>
                            <td width="<?php echo $logowidth; ?>px">
                                <div class="photo <?php echo $imgclass; ?>">
                                    <?php echo $logo_file; ?>
                                </div>
                            </td>
                        <?php endif; ?>
                            <td>
                                <a href="<?php echo $link;?>"><?php echo $item->companyname; ?></a>
                                <div class="expautos_list_dealer_info">
                                        <?php echo $this->getPrewText(JFilterOutput::cleanText($item->userinfo),$this->expparams->get('c_admanager_dealerlspage_othertextlimit'),200); ?>
                                </div>
                            </td>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_usecountry')):?>
                            <td>
                                <?php echo $item->cnt_name; ?>
                            </td>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_usestate')):?>
                            <td>
                                <?php echo $item->st_name; ?>
                            </td>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_usecity')):?>
                            <td>
                                <?php echo $item->ct_name; ?>
                            </td>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_admanager_dealerlspage_usezipcode')):?>
                            <td>
                                <?php echo $item->zipcode; ?>
                            </td>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_general_showcontact') || (!$this->expparams->get('c_general_showcontact') && $user->id)):?>
                            <td>
                                <div class="expautos_dealer_contact_all">
                                    <?php if($this->expparams->get('c_admanager_dealerlspage_usephone') && $item->phone):?>
                                        <p class="expautos_dealer_contact">
                                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_LIST_CONTACTINFO_PHONE_TEXT'); ?><?php echo $item->phone; ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if($this->expparams->get('c_admanager_dealerlspage_usestreet') && $item->street):?>
                                        <p class="expautos_dealer_contact">
                                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_LIST_CONTACTINFO_STREET_TEXT'); ?><?php echo $item->street; ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if($this->expparams->get('c_admanager_dealerlspage_useemail')):?>
                                        <p class="expautos_dealer_contact">
                                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_LIST_CONTACTINFO_EMAIL_TEXT'); ?><?php echo $item->jemail; ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if($this->expparams->get('c_admanager_dealerlspage_useweb') && $item->web):?>
                                        <p class="expautos_dealer_contact">
                                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_LIST_CONTACTINFO_WEB_TEXT'); ?><a href="http://<?php echo $item->web; ?>"><?php echo $item->web; ?></a>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        <?php endif; ?>
                     </tr>
                            <?php
                            $metaexp[] = trim($item->cnt_name." ".$item->st_name." ".$item->ct_name);
                            $metakeyexp[] = trim($item->cnt_name." ".$item->st_name." ".$item->ct_name);
                            ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </center>
    </div>
    <div>
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    </div>
</form>
<div class="expautospro_clear"></div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_dealerlspage_bmpstyle')); ?>
    </div>
<?php endif; ?>

<?php
/* insert meta */
if (isset($metaexp)) {
    $metaexp = implode(".", $metaexp);
    $this->document->setDescription($metaexp);
}
if (isset($metakeyexp)) {
    $metakeyexp = implode(",", $metakeyexp);
    $this->document->setMetadata('keywords', $metakeyexp);
}
?>

