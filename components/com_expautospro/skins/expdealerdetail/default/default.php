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

$params_file = JPATH_COMPONENT . '/skins/expdealerdetail/default/parameters/params.php';
if (file_exists($params_file))
    require_once $params_file;
ExpAutosProHelper::expskin_lang('expdealerdetail', 'default');

$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$document = JFactory::getDocument();
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$document->addScript(JURI::root() . 'components/com_expautospro/skins/expdealerdetail/default/js/ajaxpost.js');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expdealerdetail/default/css/default.css');
//$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expdealerdetail/default/css/tinyslideshow.css');
$countcolumn = $this->expparams->get('c_admanager_mkpage_column');
$topmoduleposition = $this->expparams->get('c_admanager_dealerdetail_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_dealerdetail_bmpname');
$user = JFactory::getUser();
$expgroup = $this->expgroup;
$expgroupfield = $this->expgroupfield;
$logolink = ExpAutosProExpparams::ImgUrlPatchLogo();
$imglink = ExpAutosProExpparams::ImgUrlPatch();
$user_params = ExpAutosProExpparams::getExpParams('expuser', $this->items['id']);
//print_r($this->items);
//print_r($user_params);
?>
<?php if (!isset($_GET['print'])): ?>
    <div class="expautospro_topmodule">
        <div class="expautospro_topmodule_pos">
            <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_dealerdetail_tmpstyle')); ?>
        </div>
        <div class="expautospro_clear"></div>
    </div>
<?php endif; ?>

<!-- Skins Module Position !-->
<?php if ($this->expparams->get('c_admanager_dealerdetail_showskin')): ?>
    <div id="expskins_module">
        <?php
        $expmodparam = array('folder' => $this->expskins);
        echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
        ?>
    </div>
    <div class="expautospro_clear"></div>
<?php endif; ?>

<?php if ($this->items): ?>
    <div id="expautospro">
        <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_TEXT'); ?></h2>
        <div id="expautos_dealerdetail">
            <?php if ($this->expparams->get('c_general_showcontact') || (!$this->expparams->get('c_general_showcontact') && $user->id)): ?>
                <div class="expautos_dealerdetail_topname">
                    <?php if ($this->expparams->get('c_admanager_dealerdetail_showprint')): ?>
                        <?php if (!isset($_GET['print'])) : ?>
                            <span class="exp_autos_pricon">
                                <?php echo JHtml::_('icon.dealerprint_icon', $this->items['userid']); ?>
                            </span>
                        <?php else : ?>
                            <span class="exp_autos_pricon">
                                <?php echo JHtml::_('icon.dealerprint_screen', $this->items['userid']); ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_GENERALINFO_TEXT'); ?></h3>

                    <?php if ($expgroupfield->get('c_photo') && $user_params->get('expphoto')): ?>
                        <div class="expuser_img">
                            <img src='<?php echo $logolink . $user_params->get('expphoto'); ?>' title="<?php echo $this->items['companyname']; ?>" />
                        </div>
                    <?php endif; ?>

                    <?php if ($expgroupfield->get('c_ulogo') && $this->items['logo']): ?>
                        <p>
                            <?php if ($expgroupfield->get('c_uwebsite') && $this->items['web']): ?>
                                <a href="http://<?php echo $this->items['web']; ?>" >
                                <?php endif; ?>
                                <img src='<?php echo $logolink . $this->items['logo']; ?>' title="<?php echo $this->items['companyname']; ?>" />
                                <?php if ($expgroupfield->get('c_uwebsite') && $this->items['web']): ?>
                                </a>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>   
                    <?php
                    if ($expgroupfield->get('c_username') && $this->items['user_name']):
                        $metaexp[] = $this->items['user_name'];
                        $metakeyexp[] = $this->items['user_name'];
                        ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_NAME_TEXT'); ?></span>
                            <?php echo $this->items['user_name']; ?>
                            <?php if ($expgroupfield->get('c_lastname') && $this->items['lastname']): ?>
                                <?php echo $this->items['lastname']; ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    <?php
                    if ($expgroupfield->get('c_username') && $this->items['user_username']):
                        $metaexp[] = $this->items['user_username'];
                        $metakeyexp[] = $this->items['user_username'];
                        ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_USERNAME_TEXT'); ?></span>
                        <?php echo $this->items['user_username']; ?>
                        </p>
                    <?php endif; ?>
        <?php if ($this->expparams->get('c_admanager_dealerdetail_showgroup')): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_GROUP_TEXT'); ?></span>
                        <?php echo $this->expgroup; ?>
                        </p>
                    <?php endif; ?>
        <?php if ($expgroupfield->get('c_uphone') && $this->items['phone']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_PHONE_TEXT'); ?></span>
                        <?php echo $this->items['phone']; ?>
                        </p>
                    <?php endif; ?>
        <?php if ($expgroupfield->get('c_ucphone') && $this->items['mobphone']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_MOBPHONE_TEXT'); ?></span>
                        <?php echo $this->items['mobphone']; ?>
                        </p>
                    <?php endif; ?>
        <?php if ($expgroupfield->get('c_ufax') && $this->items['fax']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_FAX_TEXT'); ?></span>
                        <?php echo $this->items['fax']; ?>
                        </p>
                    <?php endif; ?>
        <?php if ($expgroupfield->get('c_email') && $this->items['user_email']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_EMAIL_TEXT'); ?></span>
                        <?php echo $this->items['user_email']; ?>
                        </p>
                    <?php endif; ?>
                    <?php
                    if ($expgroupfield->get('c_ucompany') && $this->items['companyname']):
                        $metaexp[] = $this->items['companyname'];
                        $metakeyexp[] = $this->items['companyname'];
                        ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_COMPANY_TEXT'); ?></span>
                        <?php echo $this->items['companyname']; ?>
                        </p>
                    <?php endif; ?>
                    <?php
                    if ($expgroupfield->get('c_ucountry') && $this->items['expuser_country']):
                        $metaexp[] = $this->items['expuser_country'];
                        $metakeyexp[] = $this->items['expuser_country'];
                        ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_COUNTRY_TEXT'); ?></span>
                        <?php echo $this->items['expuser_country']; ?>
                        </p>
                    <?php endif; ?>
                    <?php
                    if ($expgroupfield->get('c_ustate') && $this->items['expuser_state']):
                        $metaexp[] = $this->items['expuser_state'];
                        $metakeyexp[] = $this->items['expuser_state'];
                        ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STATE_TEXT'); ?></span>
                        <?php echo $this->items['expuser_state']; ?>
                        </p>
                    <?php endif; ?>
        <?php
        if ($expgroupfield->get('c_ucity') && $this->items['expuser_city']):
            $metaexp[] = $this->items['expuser_city'];
            $metakeyexp[] = $this->items['expuser_city'];
            ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_CITY_TEXT'); ?></span>
                        <?php echo $this->items['expuser_city']; ?>
                        </p>
        <?php endif; ?>
                        <?php
                        if ($expgroupfield->get('c_ustreet') && $this->items['street']):
                            $metaexp[] = $this->items['street'];
                            $metakeyexp[] = $this->items['street'];
                            ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_STREET_TEXT'); ?></span>
                            <?php echo $this->items['street']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($expgroupfield->get('c_uzip') && $this->items['zipcode']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_ZIPCODE_TEXT'); ?></span>
            <?php echo $this->items['zipcode']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($expgroupfield->get('c_uwebsite') && $this->items['web']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_WEB_TEXT'); ?></span>
                            <a href="http://<?php echo $this->items['web']; ?>"><?php echo $this->items['web']; ?></a>
                        </p>
                    <?php endif; ?>
                    <?php if ($expgroupfield->get('c_uinfo') && $this->items['userinfo']): ?>
                        <p>
                            <span><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_OTHERINFOS_TEXT'); ?></span>
                        <?php echo $this->items['userinfo']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($this->expparams->get('c_admanager_dealerdetail_showallads')): ?>
                        <p>
                            <a href="<?php echo JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;userid=' . (int) $this->items['userid'] . '&amp;Itemid=' . (int) $expitem); ?>"><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_SELLERINFO_SHOWALLADS_TEXT'); ?></a>
                        </p>
        <?php endif; ?>
        <?php if (!isset($_GET['print'])): ?>
            <?php if ($this->expparams->get('c_admanager_dealerdetail_showgcontact')): ?>
                            <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_CONTACT_TEXT'); ?></h3>
                            <div id="expautos_mail_form">
                                <form method="get" action=""> 
                                    <div>
                                        <strong><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_YOURNAME_TEXT'); ?></strong>
                                    </div>
                                    <p>
                                        <input type="text" name="expsender_name" id="expsender_name" value="" />
                                    </p>
                                    <div>
                                        <strong><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_YOURPHONE_TEXT'); ?></strong>
                                    </div>
                                    <p>
                                        <input type="text" name="expsender_phone" id="expsender_phone" value="" />
                                    </p>
                                    <div>
                                        <strong><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_YOUREMAIL_TEXT'); ?></strong>
                                    </div>	
                                    <p>
                                        <input type="text" name="expsender_email" id="expsender_email" value="" />
                                    </p>
                                    <div><strong><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_MESSAGE_TEXT'); ?></strong></div>
                                    <p>
                                        <textarea name="expmessage" id="expmessage" rows="4" cols="40"></textarea>
                                    </p>
                                    <input type="button" value="<?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_BUTTONTEXT_TEXT'); ?>" onClick="ajaxgetpost('<?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILSUCCESSFULL_TEXT'); ?>')" /> 
                                    <input type="hidden" name="expuserid" id="expuserid" value="<?php echo $this->items['userid']; ?>" />
                                </form>
                            </div>
                            <img id="expautos_mailimg" src="<?php echo JURI::root(); ?>components/com_expautospro/skins/expdealerdetail/default/images/loader.gif" class="expautos_displaynone"/>
                            <div id="expautos_post_result" class=""></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($this->expparams->get('c_admanager_dealerdetail_showgmap')): ?>
                        <?php if ($this->items['latitude'] && $this->items['longitude']): ?>
                            <div class="expautospro_clear"></div>
                            <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GOOGLEMAP_TEXT'); ?></h3>
                            <?php
                            $zoom = $this->expparams->get('c_admanager_dealerdetail_zoomdef');
                            $lat = $this->items['latitude'];
                            $long = $this->items['longitude'];
                            $document->addScript('http://maps.google.com/maps/api/js?sensor=false');
                            $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expgooglemap.js');
                            $script = '';
                            $script .= "
                                var explat=$lat;
                                var explong =$long;
                                var expzoom =$zoom;
                                var expclick = 0;
                                var expmapTypeId = google.maps.MapTypeId.ROADMAP;
                                var expalert = '" . JText::_('COM_EXPAUTOSPRO_GOOGLEMAP_ALERT_TEXT') . "';
                                var exp_map_canvas = 'exp_mapdealerdetails_canvas';
                                        ";

                            $document->addScriptDeclaration($script);
                            ?>
                            <div id="exp_mapdealerdetails_canvas" style="width: <?php echo $this->expparams->get('c_admanager_dealerdetail_gmapwidth'); ?>px; height: <?php echo $this->expparams->get('c_admanager_dealerdetail_gmapheight'); ?>px;"></div>

                        <?php elseif ($this->items['expuser_country'] && $this->items['expuser_city']): ?>
                            <?php
                            $mapadress = $this->items['expuser_country'] . "+" . $this->items['expuser_city'] . "+" . $this->items['expuser_state'] . "+" . $this->items['street'];
                            ?>
                            <div class="expautospro_clear"></div>
                            <h3><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GOOGLEMAP_TEXT'); ?></h3>
                            <iframe width="<?php echo $this->expparams->get('c_admanager_dealerdetail_gmapwidth'); ?>" height="<?php echo $this->expparams->get('c_admanager_dealerdetail_gmapheight'); ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;geocode=&amp;q=<?php echo $mapadress; ?>&amp;z=14&amp;output=embed"></iframe>
            <?php endif; ?>
        <?php endif; ?>
                </div>
    <?php else: ?>
        <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_ONLYREGISTEREDUSER_TEXT'); ?>
    <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
        <?php echo JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_PROBLEMURL_TEXT'); ?>
<?php endif; ?>
<div class="expautospro_clear"></div>
<?php if (!isset($_GET['print'])): ?>
    <?php if ($bmoduleposition): ?>
        <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_dealerdetail_bmpstyle')); ?>
        </div>
    <?php endif; ?>
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

