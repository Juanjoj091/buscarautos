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

$params_file = JPATH_COMPONENT . '/skins/exppayment/default/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('exppayment','default');

$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$document = JFactory::getDocument();
$user = JFactory::getUser();
$checkuser = ExpAutosProFields::expaddcheck($this->items->id, $user->id);
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/exppayment/default/css/default.css');
$countcolumn = $this->expparams->get('c_admanager_mkpage_column');
$topmoduleposition = $this->expparams->get('c_admanager_payment_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_payment_bmpname');
$thumbsize = $this->expparams->get('c_images_thumbsize_width');
$itm_id = null;
if(isset($this->items->id)){
    $itm_id = $this->items->id;
}
$link_top = JRoute::_('index.php?option=com_expautospro&amp;view=exppayment&amp;task=exppayment.ftop&amp;id=' . $itm_id);
$link_commercial = JRoute::_('index.php?option=com_expautospro&amp;view=exppayment&amp;task=exppayment.fcommercial&amp;id=' . $itm_id);
$link_special = JRoute::_('index.php?option=com_expautospro&amp;view=exppayment&amp;task=exppayment.special&amp;id=' . $itm_id);
$exppricename = ExpAutosProHelper::exppricename();
if ($this->items->bprice) {
    $expprice = $this->items->bprice;
}elseif($this->items->price) {
    $expprice = $this->items->price;
}
if ($this->items->state) {
    $link = JRoute::_("index.php?option=com_expautospro&amp;view=expdetail&amp;catid=" . $this->items->catid . "&amp;markid=" . $this->items->make . "&amp;modelid=" . $this->items->model . "&amp;id=" . $this->items->id);
} else {
    $link = JRoute::_("#exppayment");
}
//print_r($this->expgroupfields);
//print_r($this->items);
?>

<div class="expautospro_topmodule">
    <div class="expautospro_topmodule_pos">
        <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_payment_tmpstyle')); ?>
    </div>
    <div class="expautospro_clear"></div>
</div>

<!-- Skins Module Position !-->
<?php if($this->expparams->get('c_admanager_payment_showskin')):?>
<div id="expskins_module">
    <?php
    $expmodparam = array('folder' => $this->expskins);
    echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
    ?>
</div>
<div class="expautospro_clear"></div>
<?php endif; ?>

<div id="expautospro">
    <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_TEXT'); ?></h2>
    <?php if($user->id && $checkuser): ?>
        <ul class="expautos_user_ullist">
            <li>
                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_NAME_TEXT'); ?></span><?php echo $user->name; ?>
            </li>
            <li>
                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_USERNAME_TEXT'); ?></span><?php echo $user->username; ?>
            </li>
            <li>
                <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_EMAIL_TEXT'); ?></span><?php echo $user->email; ?>
            </li>
        </ul>
        <div class="expads_id">
            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_ADSID_TEXT'); ?></span><?php echo $this->items->id; ?>
        </div>
        <table class="explist">
            <tr>
                <td width="<?php echo $thumbsize; ?>px">
                    <a href="<?php echo $link; ?>">
                        <img src="<?php echo ExpAutosProExpparams::ImgUrlPatchThumbs() . $this->items->img_name; ?>" alt="" />
                    </a>
                </td>
                <td>
                    <a href="<?php echo $link; ?>">
                        <?php echo $this->items->category_name . "&nbsp;" . $this->items->make_name . "&nbsp;" . $this->items->model_name; ?>
                    </a>
                </td>
                <td align="center">
                    <?php echo $this->items->year; ?>
                </td>
                <td align="center">
                    <?php echo number_format($expprice) . "&nbsp;" . $exppricename; ?>
                </td>
            </tr>
        </table>
        <div class="exppayment">
            <?php if ($this->items->state == 1 && $this->expgroupfields->get('p_pshowpricespecial')): ?>
                <?php if (!$this->items->ftop): ?>
                    <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_ADTOP_TEXT'); ?>
                    <div class="expautospro_clear"></div>
                    <?php if ($this->expgroupfields->get('p_ppricetop') > 0): ?>
                        <div class="exppayment_price">
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PRICE_TEXT'); ?>&nbsp;<?php echo $this->expgroupfields->get('p_ppricetop'); ?>&nbsp;<?php echo $this->expgroupfields->get('p_pcurrency'); ?>
                            <div class="exppayment_module">
                                <?php
                                $expmodparam = array('price' => $this->expgroupfields->get('p_ppricetop'), 'curcode' => $this->expgroupfields->get('p_pcurrency'), 'itemnumber' => $this->expgroupfields->get('p_ptopitemnum'), 'groupname' => JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_ADTOP_PAYTEXT_TEXT'), 'userid' => $user->id, 'item_special' => '2', 'itm_id' => $this->items->id);
                                echo ExpAutosProHelper::load_module_position('explevelpayment', $style = 'none', $expmodparam);
                                ?>
                            </div>
                        </div>
                    <?php elseif ($this->expgroupfields->get('p_ppricetop') == 0): ?>
                        <div class="expspecial_button">
                            <span class="expautos_list_payspecial"><a href="<?php echo $link_top; ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_ADTOP_TEXTLINK_TEXT'); ?></a></span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (!$this->items->fcommercial): ?>
                    <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_COMMTOP_TEXT'); ?>
                    <div class="expautospro_clear"></div>
                    <?php if ($this->expgroupfields->get('p_ppricecommercial') > 0): ?>
                        <div class="exppayment_price">
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PRICE_TEXT'); ?>&nbsp;<?php echo $this->expgroupfields->get('p_ppricecommercial'); ?>&nbsp;<?php echo $this->expgroupfields->get('p_pcurrency'); ?>
                            <div class="exppayment_module">
                                <?php
                                $expmodparam = array('price' => $this->expgroupfields->get('p_ppricecommercial'), 'curcode' => $this->expgroupfields->get('p_pcurrency'), 'itemnumber' => $this->expgroupfields->get('p_pcommitemnum'), 'groupname' => JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_COMMTOP_PAYTEXT_TEXT'), 'userid' => $user->id, 'item_special' => '3', 'itm_id' => $this->items->id);
                                echo ExpAutosProHelper::load_module_position('explevelpayment', $style = 'none', $expmodparam);
                                ?>
                            </div>
                        </div>
                    <?php elseif ($this->expgroupfields->get('p_ppricecommercial') == 0): ?>
                        <div class="expspecial_button">
                            <span class="expautos_list_payspecial"><a href="<?php echo $link_commercial; ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_COMMTOP_TEXTLINK_TEXT'); ?></a></span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (!$this->items->special): ?>
                    <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_SPECIAL_TEXT'); ?>
                    <div class="expautospro_clear"></div>
                    <?php if ($this->expgroupfields->get('p_ppricespecial') > 0): ?>
                        <div class="exppayment_price">
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PRICE_TEXT'); ?>&nbsp;<?php echo $this->expgroupfields->get('p_ppricespecial'); ?>&nbsp;<?php echo $this->expgroupfields->get('p_pcurrency'); ?>
                            <div class="exppayment_module">
                                <?php
                                $expmodparam = array('price' => $this->expgroupfields->get('p_ppricespecial'), 'curcode' => $this->expgroupfields->get('p_pcurrency'), 'itemnumber' => $this->expgroupfields->get('p_pspecialitemnum'), 'groupname' => JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_SPECIAL_PAYTEXT_TEXT'), 'userid' => $user->id, 'item_special' => '4', 'itm_id' => $this->items->id);
                                echo ExpAutosProHelper::load_module_position('explevelpayment', $style = 'none', $expmodparam);
                                ?>
                            </div>
                        </div>
                    <?php elseif ($this->expgroupfields->get('p_ppricespecial') == 0): ?>
                        <div class="expspecial_button">
                            <span class="expautos_list_payspecial"><a href="<?php echo $link_special; ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_SPECIAL_TEXTLINK_TEXT'); ?></a></span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->items->state != 1 && $this->expgroupfields->get('p_pshowpricead')): ?>
                <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_AD_TEXT'); ?>
                <div class="expautospro_clear"></div>
                <div class="exppayment_price">
                    <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PRICE_TEXT'); ?>&nbsp;<?php echo $this->expgroupfields->get('p_ppricead'); ?>&nbsp;<?php echo $this->expgroupfields->get('p_pcurrency'); ?>
                    <div class="exppayment_module">
                        <?php
                        $expmodparam = array('price' => $this->expgroupfields->get('p_ppricead'), 'curcode' => $this->expgroupfields->get('p_pcurrency'), 'itemnumber' => $this->expgroupfields->get('p_paditemnum'), 'groupname' => JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_AD_PAYTEXT_TEXT'), 'userid' => $user->id, 'item_special' => '1', 'itm_id' => $this->items->id);
                        echo ExpAutosProHelper::load_module_position('explevelpayment', $style = 'none', $expmodparam);
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php if(!$user->id): ?>
    <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_PAYMENT_ONLYREGISTERED_TEXT', JRoute::_('index.php?option=com_users&view=login'), JRoute::_('index.php?option=com_users&view=registration'))); ?>
<?php elseif(!$checkuser): ?>
    <?php echo JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_NOTVALIDURL_ERROR_TEXT'); ?>
<?php endif; ?>
<div class="expautospro_clear"></div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_payment_bmpstyle')); ?>
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

