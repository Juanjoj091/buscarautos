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

$params_file = JPATH_COMPONENT . '/skins/expadd/default/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('expadd','default');

$expcat = JRequest::getInt('expcat', 0);
if($this->expcategoryfields->get('usegooglemaps') && ((int)$this->form->getValue('catid') || (int)$expcat)){
    if($this->form->getValue('latitude') && $this->form->getValue('longitude')){
        $lat = $this->form->getValue('latitude');
        $long = $this->form->getValue('longitude');
        $zoom = $this->expparams->get('c_admanager_add_googlemaps_zoomstreet');
    }else{
        $lat = $this->expparams->get('c_admanager_add_googlemaps_latdef');
        $long = $this->expparams->get('c_admanager_add_googlemaps_longdef');
        $zoom = $this->expparams->get('c_admanager_add_googlemaps_zoomdef');
    }
    $expzoombycst = $this->expparams->get('c_admanager_add_googlemaps_zoomcst');
    $expzoombystreet = $this->expparams->get('c_admanager_add_googlemaps_zoomstreet');
    $expgooglewidth = $this->expparams->get('c_admanager_add_googlemaps_width');
    $expgoogleheight = $this->expparams->get('c_admanager_add_googlemaps_height');
}

$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expadd/default/css/default.css');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expchecked.js');
if($this->expcategoryfields->get('usegooglemaps') && ((int)$this->form->getValue('catid') || (int)$expcat)){
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
        var exp_map_canvas = 'exp_mapadd_canvas';
                ";

    $document->addScriptDeclaration($script);
}
$topmoduleposition = $this->expparams->get('c_admanager_add_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_add_bmpname');
$pricename = ExpAutosProExpparams::expgeneral_pricename();
$ads_count = ExpAutosProHelper::getExpcount('admanager','user',$user->id,1);
$itemid = JRequest::getInt('Itemid', 0);
//print_r($this->form->getValue('catid'));
?>
<script type="text/javascript">
    function change_cat(val){
        document.location.href='index.php?option=com_expautospro&view=expadd&layout=edit&id=<?php echo $this->data->id; ?>&expcat='+val+'&Itemid=<?php echo $itemid;?>';
    }

</script>
<div class="expautospro_topmodule">
    <div class="expautospro_topmodule_pos">
        <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_add_tmpstyle')); ?>
    </div>
    <div class="expautospro_clear"></div>
</div>

<!-- Skins Module Position !-->
<?php if($this->expparams->get('c_admanager_add_showskin')):?>
<div id="expskins_module">
    <?php
    $expmodparam = array('folder' => $this->expskins);
    echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
    ?>
</div>
<div class="expautospro_clear"></div>
<?php endif; ?>

<div id="expautospro">
    <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_TEXT') ?></h2>
    <?php if ((int)$user->id && $this->expgroupid && ((int)$this->data->user==(int)$user->id || (int)$this->data->id == 0 ) && ((int)$this->expgroupfields->get('g_adscount') == 0 || ($this->expgroupfields->get('g_adscount') > $ads_count || (int)$this->data->id != 0))): ?>
        <div class="expprofile-edit">
            <form id="expmember-profile" action="<?php echo JRoute::_('index.php?option=com_expautospro&view=expadd&task=expadd'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="expautosprocat-form" class="form-validate">
                <fieldset>
                    <legend><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_CATEGORY_TEXT'); ?></legend>
                    <dl>
                        <dt><?php echo $this->form->getLabel('catid'); ?></dt>
                        <dd><?php echo $this->form->getInput('catid'); ?></dd>
                    </dl>
                </fieldset>
                <?php if((int)$this->form->getValue('catid') || (int)$expcat):?>
                <fieldset>
                    <legend><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_VEHICLEINFO_TEXT'); ?></legend>
                    <dl>
                        <dt><?php echo $this->form->getLabel('id'); ?></dt>
                        <dd><?php echo $this->form->getInput('id'); ?></dd>
                        <dt><?php echo $this->form->getLabel('user'); ?></dt>
                        <dd><?php echo $this->form->getInput('user'); ?></dd>
                        <dt><?php echo $this->form->getLabel('state'); ?></dt>
                        <dd><?php echo $this->form->getInput('state'); ?></dd>
                        
                         <?php
                        if ($this->expcategoryfields->get('usemakes')):
                            if ($this->expparams->get('c_admanager_req_makes')){
                                $this->form->setFieldAttribute('make', 'required', 'true');
                            }
                            ?>
                        <dt><?php echo $this->form->getLabel('make'); ?></dt>
                        <dd><?php echo $this->form->getInput('make'); ?></dd>
                        <?php endif; ?>
                        
                         <?php
                        if ($this->expcategoryfields->get('usemodels')):
                            if ($this->expparams->get('c_admanager_req_models')){
                                if(!$this->expgroupfields->get('g_newmodel'))
                                    $this->form->setFieldAttribute('model', 'required', 'true');
                            }
                            ;?>
                            <dt><?php echo $this->form->getLabel('model'); ?></dt>
                            <dd><?php echo $this->form->getInput('model'); ?></dd>

                                <?php 
                                if ($this->expgroupfields->get('g_newmodel')):?>
                                    <dt><?php echo $this->form->getLabel('checkmodel'); ?></dt>
                                    <dd><?php echo $this->form->getInput('checkmodel'); ?></dd>
                                    <span id="expyourmodel">
                                    <dt><?php echo $this->form->getLabel('expyourmodel'); ?></dt>
                                    <dd><?php echo $this->form->getInput('expyourmodel'); ?></dd>
                                    </span>
                                <?php endif; ?>
                        <?php endif; ?>
                         <?php
                        if ($this->expcategoryfields->get('usespecificmodel')):
                            if ($this->expparams->get('c_admanager_req_specificmodel'))
                                $this->form->setFieldAttribute('specificmodel', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('specificmodel'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('specificmodel'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTSPECMODEL_EXAMPLE_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                            <?php
                        if ($this->expcategoryfields->get('usebodytype')):
                            if ($this->expparams->get('c_admanager_req_bodytype'))
                                $this->form->setFieldAttribute('bodytype', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('bodytype'); ?></dt>
                            <dd><?php echo $this->form->getInput('bodytype'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usedrive')):
                            if ($this->expparams->get('c_admanager_req_drive'))
                                $this->form->setFieldAttribute('drive', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('drive'); ?></dt>
                            <dd><?php echo $this->form->getInput('drive'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usefuel')):
                            if ($this->expparams->get('c_admanager_req_fuel'))
                                $this->form->setFieldAttribute('fuel', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('fuel'); ?></dt>
                            <dd><?php echo $this->form->getInput('fuel'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usetrans')):
                            if ($this->expparams->get('c_admanager_req_trans'))
                                $this->form->setFieldAttribute('trans', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('trans'); ?></dt>
                            <dd><?php echo $this->form->getInput('trans'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usespecifictrans')):
                            if ($this->expparams->get('c_admanager_req_specifictrans'))
                                $this->form->setFieldAttribute('specifictrans', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('specifictrans'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('specifictrans'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTSPECTRANSMISSION_EXAMPLE_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useextrafield1')):
                            if ($this->expparams->get('c_admanager_req_extrafield1'))
                                $this->form->setFieldAttribute('extrafield1', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('extrafield1'); ?></dt>
                            <dd><?php echo $this->form->getInput('extrafield1'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useextrafield2')):
                            if ($this->expparams->get('c_admanager_req_extrafield2'))
                                $this->form->setFieldAttribute('extrafield2', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('extrafield2'); ?></dt>
                            <dd><?php echo $this->form->getInput('extrafield2'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useextrafield3')):
                            if ($this->expparams->get('c_admanager_req_extrafield3'))
                                $this->form->setFieldAttribute('extrafield3', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('extrafield3'); ?></dt>
                            <dd><?php echo $this->form->getInput('extrafield3'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usecondition')):
                            if ($this->expparams->get('c_admanager_req_condition'))
                                $this->form->setFieldAttribute('condition', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('condition'); ?></dt>
                            <dd><?php echo $this->form->getInput('condition'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useextcolor')):
                            if ($this->expparams->get('c_admanager_req_extcolor'))
                                $this->form->setFieldAttribute('extcolor', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('extcolor'); ?></dt>
                            <dd><?php echo $this->form->getInput('extcolor'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usespecificcolor')):
                            if ($this->expparams->get('c_admanager_req_specificcolor'))
                                $this->form->setFieldAttribute('specificcolor', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('specificcolor'); ?></dt>
                            <dd><?php echo $this->form->getInput('specificcolor'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usemetalliccolor')):
                            if ($this->expparams->get('c_admanager_req_metalliccolor'))
                                $this->form->setFieldAttribute('metalliccolor', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('metalliccolor'); ?></dt>
                            <dd><?php echo $this->form->getInput('metalliccolor'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useintcolor')):
                            if ($this->expparams->get('c_admanager_req_intcolor'))
                                $this->form->setFieldAttribute('intcolor', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('intcolor'); ?></dt>
                            <dd><?php echo $this->form->getInput('intcolor'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usemonth')):
                            if ($this->expparams->get('c_admanager_req_month'))
                                $this->form->setFieldAttribute('month', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('month'); ?></dt>
                            <dd><?php echo $this->form->getInput('month'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useyear')):
                            if ($this->expparams->get('c_admanager_req_year'))
                                $this->form->setFieldAttribute('year', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('year'); ?></dt>
                            <dd><?php echo $this->form->getInput('year'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usevincode')):
                            if ($this->expparams->get('c_admanager_req_vincode'))
                                $this->form->setFieldAttribute('vincode', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('vincode'); ?></dt>
                            <dd><?php echo $this->form->getInput('vincode'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usemileage')):
                            if ($this->expparams->get('c_admanager_req_mileage'))
                                $this->form->setFieldAttribute('mileage', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('mileage'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('mileage'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_KM_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usedoors')):
                            if ($this->expparams->get('c_admanager_req_doors'))
                                $this->form->setFieldAttribute('doors', 'required', 'true');
                            ?>
                           <dt><?php echo $this->form->getLabel('doors'); ?></dt>
                           <dd><?php echo $this->form->getInput('doors'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useseats')):
                            if ($this->expparams->get('c_admanager_req_seats'))
                                $this->form->setFieldAttribute('seats', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('seats'); ?></dt>
                            <dd><?php echo $this->form->getInput('seats'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usedisplacement')):
                            if ($this->expparams->get('c_admanager_req_displacement'))
                                $this->form->setFieldAttribute('displacement', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('displacement'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('displacement'); ?>
                            &#40;<?php echo JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT'); ?>&#41;
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_DISPLACEMENT_EXAMPLE_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useengine')):
                            if ($this->expparams->get('c_admanager_req_engine'))
                                $this->form->setFieldAttribute('engine', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('engine'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('engine'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_KW_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useco')):
                            if ($this->expparams->get('c_admanager_req_co'))
                                $this->form->setFieldAttribute('co', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('co'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('co'); ?>
                            &#40;<?php echo JText::_('COM_EXPAUTOSPRO_GKM_TEXT'); ?>&#41;
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTCO2_EXAMPLE_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usestocknum')):
                            if ($this->expparams->get('c_admanager_req_stocknum'))
                                $this->form->setFieldAttribute('stocknum', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('stocknum'); ?></dt>
                            <dd><?php echo $this->form->getInput('stocknum'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usefconsum')):
                            if ($this->expparams->get('c_admanager_req_fconsumcity'))
                                $this->form->setFieldAttribute('fconsumcity', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('fconsumcity'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('fconsumcity'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTFCONSUMCITY_EXAMPLE_TEXT'); ?>
                            </dd>
                            <?php if ($this->expparams->get('c_admanager_req_fconsumfreeway'))
                                $this->form->setFieldAttribute('fconsumfreeway', 'required', 'true'); ?>
                            <dt><?php echo $this->form->getLabel('fconsumfreeway'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('fconsumfreeway'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTFCONSUMFREEWAY_EXAMPLE_TEXT'); ?>
                            </dd>
                            <?php if ($this->expparams->get('c_admanager_req_fconsumcombined'))
                                $this->form->setFieldAttribute('fconsumcombined', 'required', 'true'); ?>
                            <dt><?php echo $this->form->getLabel('fconsumcombined'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('fconsumcombined'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTFCONSUMCOMBINED_EXAMPLE_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useadacceleration')):
                            if ($this->expparams->get('c_admanager_req_adacceleration'))
                                $this->form->setFieldAttribute('adacceleration', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('adacceleration'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('adacceleration'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTACCELERATION_EXAMPLE_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usemaxspeed')):
                            if ($this->expparams->get('c_admanager_req_maxspeed'))
                                $this->form->setFieldAttribute('maxspeed', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('maxspeed'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('maxspeed'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTMAXSPEED_EXAMPLE_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('uselength')):
                            if ($this->expparams->get('c_admanager_req_length'))
                                $this->form->setFieldAttribute('length', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('length'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('length'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_MILIMETERS_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usewidth')):
                            if ($this->expparams->get('c_admanager_req_width'))
                                $this->form->setFieldAttribute('width', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('width'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('width'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_MILIMETERS_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useunweight')):
                            if ($this->expparams->get('c_admanager_req_unweight'))
                                $this->form->setFieldAttribute('unweight', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('unweight'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('unweight'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_KG_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usegrweight')):
                            if ($this->expparams->get('c_admanager_req_grweight'))
                                $this->form->setFieldAttribute('grweight', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('grweight'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('grweight'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_KG_TEXT'); ?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useembedcode')):
                            if ($this->expparams->get('c_admanager_req_embedcode'))
                                $this->form->setFieldAttribute('embedcode', 'required', 'true');
                            ?>
                            <dt>
                            <?php echo $this->form->getLabel('embedcode'); ?>
                            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INSERTEMBEDCODE_EXAMPLE_TEXT'); ?>
                            </dt>
                            <dd><?php echo $this->form->getInput('embedcode'); ?></dd>
                        <?php endif; ?>
                            <dt>
                            <?php echo $this->form->getLabel('otherinfo'); ?>
                            <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_EXPADD_INSERTOTHERINFO_EXAMPLE_TEXT', $this->expparams->get('c_admanager_add_maxotherinfo'))); ?>
                            </dt>
                            <dd><?php echo $this->form->getInput('otherinfo'); ?></dd>
                        <?php
                        if ($this->expcategoryfields->get('useprice')):
                            if ($this->expparams->get('c_admanager_req_price'))
                                $this->form->setFieldAttribute('price', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('price'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('price'); ?>
                            <?php echo $pricename;?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usebprice')):
                            if ($this->expparams->get('c_admanager_req_bprice'))
                                $this->form->setFieldAttribute('bprice', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('bprice'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('bprice'); ?>
                            <?php echo $pricename;?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('useexpprice')):
                            if ($this->expparams->get('c_admanager_req_expprice'))
                                $this->form->setFieldAttribute('expprice', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('expprice'); ?></dt>
                            <dd>
                            <?php echo $this->form->getInput('expprice'); ?>
                            <?php echo $pricename;?>
                            </dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usevattext')):
                            if ($this->expparams->get('c_admanager_req_vattext'))
                                $this->form->setFieldAttribute('vattext', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('vattext'); ?></dt>
                            <dd><?php echo $this->form->getInput('vattext'); ?></dd>
                            <?php endif; ?>
                       
                    </dl>
                </fieldset>
                
                <?php if ($this->expgroupfields->get('g_enablefile')):?>
                <fieldset>
                    <legend><?php echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ATTACHED_FILE_TEXT'); ?></legend>
                        <dt><?php echo $this->form->getLabel('expfile'); ?></dt>
                        <dd><?php echo $this->form->getInput('expfile'); ?>
                            <?php echo JText::sprintf(JText::_("COM_EXPAUTOSPRO_CP_ADMANAGER_ALLOWEDTYPES_TEXT"),$this->expgroupfields->get('g_file_ext')); ?><br />
                            <?php echo JText::sprintf(JText::_("COM_EXPAUTOSPRO_CP_ADMANAGER_MAXFILESIZE_TEXT"),ExpAutosProFields::exp_convertsize($this->expgroupfields->get('g_filemax_size'))); ?>
                        </dd>
                </fieldset>
                <?php endif; ?>
                
                <?php if ($this->expcategoryfields->get('usecountry') || $this->expcategoryfields->get('usegooglemaps')):?>
                <fieldset>
                    <legend><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_VEHICLELOCATION_TEXT'); ?></legend>
                       <?php
                          if ($this->expcategoryfields->get('usecountry')):
                            if ($this->expparams->get('c_admanager_req_country'))
                                $this->form->setFieldAttribute('country', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('country'); ?></dt>
                            <dd><?php echo $this->form->getInput('country'); ?></dd>
                            <?php
                            if ($this->expcategoryfields->get('usestate')):
                                if ($this->expparams->get('c_admanager_req_state'))
                                    $this->form->setFieldAttribute('expstate', 'required', 'true');
                                ?>
                                <dt><?php echo $this->form->getLabel('expstate'); ?></dt>
                                <dd><?php echo $this->form->getInput('expstate'); ?></dd>
                                <?php
                                if ($this->expcategoryfields->get('usecity')):
                                    if ($this->expparams->get('c_admanager_req_city'))
                                        $this->form->setFieldAttribute('city', 'required', 'true');
                                    ?>
                                    <dt><?php echo $this->form->getLabel('city'); ?></dt>
                                    <dd><?php echo $this->form->getInput('city'); ?></dd>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usestreet')):
                            if ($this->expparams->get('c_admanager_req_street'))
                                $this->form->setFieldAttribute('street', 'required', 'true');
                            if($this->expcategoryfields->get('usegooglemaps'))
                                $this->form->setFieldAttribute('street', 'onchange', 'codeStreet(this.value);return false;');
                            ?>
                            <dt><?php echo $this->form->getLabel('street'); ?></dt>
                            <dd><?php echo $this->form->getInput('street'); ?></dd>
                        <?php endif; ?>
                        <?php
                        if ($this->expcategoryfields->get('usezipcode')):
                            if ($this->expparams->get('c_admanager_req_zipcode'))
                                $this->form->setFieldAttribute('zipcode', 'required', 'true');
                            ?>
                            <dt><?php echo $this->form->getLabel('zipcode'); ?></dt>
                            <dd><?php echo $this->form->getInput('zipcode'); ?></dd>
                        <?php endif; ?>
                        <?php if($this->expcategoryfields->get('usegooglemaps')):
                            if ($this->expparams->get('c_admanager_add_latlong')){
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
                    <div class="expautospro_clear"></div>
                    <?php if($this->expcategoryfields->get('usegooglemaps')):?> 
                        <div id="exp_mapadd_canvas" style="width: <?php echo $expgooglewidth;?>px; height: <?php echo $expgoogleheight;?>px;"></div>
                    <?php endif; ?>
                </fieldset>
                <?php endif; ?>
                <?php if ($this->expcategoryfields->get('useequipment')):?>
                <fieldset>
                    <legend><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_EQUIPMENT_TEXT'); ?></legend>
                            <?php echo $this->form->getInput('equipment'); ?>
                </fieldset>
                <?php endif; ?>
                <?php
                if ($this->expparams->get('c_admanager_useparams')):
                    $fieldSets = $this->form->getFieldsets('params');
                    foreach ($fieldSets as $name => $fieldSet) :
                    ?>
                        <fieldset class="panelform" >
                            <legend><?php echo JText::_($fieldSet->label); ?></legend>
                            <dl>
                                <?php foreach ($this->form->getFieldset($name) as $field) : ?>
                                    <dt><?php echo $field->label; ?></dt>
                                   <dd><?php echo $field->input; ?></dd>
                                <?php endforeach; ?>
                            </dl>
                        </fieldset>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div>
                    <button type="submit button" class="validate"><span><?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_SAVEANDNEXT_TEXT'); ?></span></button>

                    <input type="hidden" name="option" value="com_expautospro" />
                    <input type="hidden" name="task" value="expadd.save" />
                    <?php if(!$this->data->id): ?>
                        <input type="hidden" name="expisnew" value="1" />
                    <?php endif; ?>
                    <input type="hidden" name="Itemid" value="<?php echo $expitem;?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
                <?php endif; ?>
            </form>
        </div>
    <?php else: ?>
        <?php if (!$user->id): ?>
            <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_ONLYREGISTERED_TEXT', JRoute::_('index.php?option=com_users&view=login'), JRoute::_('index.php?option=com_users&view=registration'))); ?>
        <?php elseif (!$this->expgroupid): ?>
            <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_PERMISSION_DENIED_TEXT', JRoute::_('index.php?option=com_expautospro&view=exppaylevel'))); ?>
        <?php elseif($this->expgroupfields->get('g_adscount') > $ads_count || (int)$this->data->id == 0):?>
            <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CP_EXPADD_INFO_LIMITADS_TEXT',$this->expgroupfields->get('g_adscount'), JRoute::_('index.php?option=com_expautospro&view=exppaylevel'))); ?>
        <?php elseif ((int)$this->data->user!=(int)$user->id): ?>
            <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_NOTUSERAD_ERROR_TEXT'); ?>
        <?php endif; ?>

    <?php endif; ?>
</div>
<div class="expautospro_clear"></div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_add_bmpstyle')); ?>
    </div>
<?php endif; ?>
