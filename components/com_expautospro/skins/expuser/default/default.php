<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access.
defined('_JEXEC') or die;

$params_file = JPATH_COMPONENT . '/skins/expuser/default/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('expuser','default');
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');

if ($user->id && $this->expgroupid){
    if($this->expparams->get('c_admanager_useradd_showgooglemaps')){
        if($this->form->getValue('latitude') && $this->form->getValue('longitude')){
            $lat = $this->form->getValue('latitude');
            $long = $this->form->getValue('longitude');
            $zoom = $this->expparams->get('c_admanager_useradd_googlemaps_zoomstreet');
        }else{
            $lat = $this->expparams->get('c_admanager_useradd_googlemaps_latdef');
            $long = $this->expparams->get('c_admanager_useradd_googlemaps_longdef');
            $zoom = $this->expparams->get('c_admanager_useradd_googlemaps_zoomdef');
        }
        $expzoombycst = $this->expparams->get('c_admanager_useradd_googlemaps_zoomcst');
        $expzoombystreet = $this->expparams->get('c_admanager_useradd_googlemaps_zoomstreet');
        $expgooglewidth = $this->expparams->get('c_admanager_useradd_googlemaps_width');
        $expgoogleheight = $this->expparams->get('c_admanager_useradd_googlemaps_height');

        $document->addScript('http://maps.google.com/maps/api/js?sensor=false');
        $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expgooglemap.js');
        $script = '';
        $script .= "
            var explat=$lat;
            var explong =$long;
            var expzoom =$zoom;
            var expzoombycst =$expzoombycst;
            var expzoombystreet =$expzoombystreet;
            var expclick = 1;
            var expmapTypeId = google.maps.MapTypeId.".$this->expparams->get('c_general_gmapmaptypeid').";
            var latid = 'jform_latitude';
            var longid = 'jform_longitude';
            var expalert = '".JText::_('COM_EXPAUTOSPRO_GOOGLEMAP_ALERT_TEXT')."';
            var exp_map_canvas = 'exp_map_canvas';
                    ";

        $document->addScriptDeclaration($script);
    }
}
$topmoduleposition = $this->expparams->get('c_admanager_useradd_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_useradd_bmpname');
$return = JRequest::getVar('return', null, '', 'base64');
//print_r($this->expgroupid);
?>
<div class="expautospro_topmodule">
    <div class="expautospro_topmodule_pos">
        <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_useradd_tmpstyle')); ?>
    </div>
    <div class="expautospro_clear"></div>
</div>

<!-- Skins Module Position !-->
<?php if($this->expparams->get('c_admanager_useradd_showskin')):?>
<div id="expskins_module">
    <?php
    $expmodparam = array('folder' => $this->expskins);
    echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
    ?>
</div>
<div class="expautospro_clear"></div>
<?php endif; ?>

<div id="expautospro">
    <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_TEXT'); ?></h2>
    <?php if ($user->id && $this->expgroupid): ?>
        <div class="expprofile-edit">
            <form id="expmember-profile" action="<?php echo JRoute::_('index.php?option=com_expautospro&view=expuser&task=expuser'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="expautosprocat-form" class="form-validate">
                <fieldset>
                    <legend><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_GENERALINFO_TEXT'); ?></legend>
                    <dl>
                        <dt><?php echo $this->form->getLabel('gname'); ?></dt>
                        <dd><?php echo $this->form->getInput('gname'); ?></dd>
                        <dt><?php echo $this->form->getLabel('gusername'); ?></dt>
                        <dd><?php echo $this->form->getInput('gusername'); ?></dd>
                        <dt><?php echo $this->form->getLabel('gemail'); ?></dt>
                        <dd><?php echo $this->form->getInput('gemail'); ?></dd>
                        <dt></dt>
                        <dd>
                            <a href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $user->id); ?>">
                                <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_GENERALINFO_EDIT_TEXT'); ?>
                            </a>
                        </dd>
                    </dl>
                </fieldset>
                <fieldset>
                    <legend><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_TEXT'); ?></legend>
                    <dl>
                        <dt><?php echo $this->form->getLabel('id'); ?></dt>
                        <dd><?php echo $this->form->getInput('id'); ?></dd>
                        <dt><?php echo $this->form->getLabel('userid'); ?></dt>
                        <dd><?php echo $this->form->getInput('userid'); ?></dd>
                        <?php
                        if ($this->expgroupfields->get('c_lastname')):
                            if ($this->expparams->get('c_user_req_lastname'))
                                $this->form->setFieldAttribute('lastname', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('lastname'); ?></dt>
                            <dd><?php echo $this->form->getInput('lastname'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expgroupfields->get('c_ucompany')):
                            if ($this->expparams->get('c_user_req_companyname'))
                                $this->form->setFieldAttribute('companyname', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('companyname'); ?></dt>
                            <dd><?php echo $this->form->getInput('companyname'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expgroupfields->get('c_uwebsite')):
                            if ($this->expparams->get('c_user_req_web'))
                                $this->form->setFieldAttribute('web', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('web'); ?></dt>
                            <dd><?php echo $this->form->getInput('web'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expgroupfields->get('c_uphone')):
                            if ($this->expparams->get('c_user_req_phone'))
                                $this->form->setFieldAttribute('phone', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('phone'); ?></dt>
                            <dd><?php echo $this->form->getInput('phone'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expgroupfields->get('c_ucphone')):
                            if ($this->expparams->get('c_user_req_cellphone'))
                                $this->form->setFieldAttribute('mobphone', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('mobphone'); ?></dt>
                            <dd><?php echo $this->form->getInput('mobphone'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expgroupfields->get('c_ufax')):
                            if ($this->expparams->get('c_user_req_fax'))
                                $this->form->setFieldAttribute('fax', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('fax'); ?></dt>
                            <dd><?php echo $this->form->getInput('fax'); ?></dd>
                        <?php endif; ?>
                        <dt><?php echo $this->form->getLabel('emailstyle'); ?></dt>
                        <dd><?php echo $this->form->getInput('emailstyle'); ?></dd>
                        <?php if ($this->expgroupfields->get('c_uinfo')): ?>
                            <dt><?php echo $this->form->getLabel('userinfo'); ?></dt>
                            <dd><?php echo $this->form->getInput('userinfo'); ?></dd>
                        <?php endif; ?>
                        <?php if ($this->expgroupfields->get('c_ulogo')): ?>
                            <div class="expautospro_clear"></div>
                            <br />
                            <dt><?php echo $this->form->getLabel('logo'); ?></dt>
                            <dd><?php echo $this->form->getInput('logo'); ?></dd>
                            <div class="expautospro_clear"></div>
                        <?php endif; ?>
                        <?php
                        if ($this->expgroupfields->get('c_ucountry')):
                            if ($this->expparams->get('c_user_req_country'))
                                $this->form->setFieldAttribute('country', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('country'); ?></dt>
                            <dd><?php echo $this->form->getInput('country'); ?></dd>
                            <?php
                            if ($this->expgroupfields->get('c_ustate')):
                                if ($this->expparams->get('c_user_req_state'))
                                    $this->form->setFieldAttribute('expstate', 'required', 'true');
                                ?>
                                <dt><?php echo $this->form->getLabel('expstate'); ?></dt>
                                <dd><?php echo $this->form->getInput('expstate'); ?></dd>
                                <?php
                                if ($this->expgroupfields->get('c_ucity')):
                                    if ($this->expparams->get('c_user_req_city'))
                                        $this->form->setFieldAttribute('city', 'required', 'true');
                                    ?>
                                    <dt><?php echo $this->form->getLabel('city'); ?></dt>
                                    <dd><?php echo $this->form->getInput('city'); ?></dd>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php
                        if ($this->expgroupfields->get('c_ustreet')):
                            if ($this->expparams->get('c_user_req_street'))
                                $this->form->setFieldAttribute('street', 'required', 'true');
                            if($this->expparams->get('c_admanager_useradd_showgooglemaps'))
                                $this->form->setFieldAttribute('street', 'onchange', 'codeStreet(this.value);return false;');
                            ?>
                            <dt><?php echo $this->form->getLabel('street'); ?></dt>
                            <dd><?php echo $this->form->getInput('street'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expgroupfields->get('c_uzip')):
                            if ($this->expparams->get('c_user_req_zipcode'))
                                $this->form->setFieldAttribute('zipcode', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('zipcode'); ?></dt>
                            <dd><?php echo $this->form->getInput('zipcode'); ?></dd>
                        <?php endif; ?>
                        <?php if($this->expparams->get('c_admanager_useradd_showgooglemaps')):
                            if ($this->expparams->get('c_admanager_useradd_latlong')){
                                $this->form->setFieldAttribute('latitude', 'required', 'true');
                                $this->form->setFieldAttribute('longitude', 'required', 'true');
                            }
                            $this->form->setFieldAttribute('latitude', 'onchange', 'findLangLong();return false;');
                            $this->form->setFieldAttribute('longitude', 'onchange', 'findLangLong();return false;');
                            ?>
                                <dt><?php echo $this->form->getLabel('latitude'); ?></dt>
                                <dd><?php echo $this->form->getInput('latitude'); ?></dd>


                                <dt><?php echo $this->form->getLabel('longitude'); ?></dt>
                                <dd><?php echo $this->form->getInput('longitude'); ?></dd>
                        <?php endif; ?>
                            
                    </dl>
                    <div class="expautospro_clear"></div>
                    <?php if($this->expparams->get('c_admanager_useradd_showgooglemaps')):?> 
                        <div id="exp_map_canvas" style="width: <?php echo $expgooglewidth;?>px; height: <?php echo $expgoogleheight;?>px;"></div>
                    <?php endif; ?>
                </fieldset>
                <div>
                    <button type="submit" class="validate button"><span><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_SAVE_TEXT'); ?></span></button>

                    <input type="hidden" name="option" value="com_expautospro" />
                    <input type="hidden" name="task" value="expuser.save" />
                    <input type="hidden" name="return" value="<?php echo $return;?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>
        </div>
    <?php else: ?>
        <?php if (!$user->id): ?>
            <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_ONLYREGISTERED_TEXT', JRoute::_('index.php?option=com_users&view=login'), JRoute::_('index.php?option=com_users&view=registration'))); ?>
        <?php elseif (!$this->expgroupid): ?>
            <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_PERMISSION_DENIED_TEXT', JRoute::_('index.php?option=com_expautospro&view=exppaylevel'))); ?>
        <?php endif; ?>

    <?php endif; ?>
</div>
<div class="expautospro_clear"></div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_useradd_bmpstyle')); ?>
    </div>
<?php endif; ?>