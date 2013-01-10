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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$document = JFactory::getDocument();
$expusefield = ExpAutosProImages::getCatParams($this->form->getValue('catid'));
$expreqfield = ExpAutosProImages::getExpParams('config', 1);
//print_r($this->name);
$expzoombycst = '';
$expzoombystreet = '';
$expgooglewidth = '';
$expgoogleheight = '';
if($expusefield->get('usegooglemaps')){
    if($this->form->getValue('latitude') && $this->form->getValue('longitude')){
        $lat = $this->form->getValue('latitude');
        $long = $this->form->getValue('longitude');
        $zoom = $expreqfield->get('c_admanager_add_googlemaps_zoomstreet');
    }else{
        $lat = $expreqfield->get('c_admanager_add_googlemaps_latdef');
        $long = $expreqfield->get('c_admanager_add_googlemaps_longdef');
        $zoom = $expreqfield->get('c_admanager_add_googlemaps_zoomdef');
    }
    $expzoombycst = $expreqfield->get('c_admanager_add_googlemaps_zoomcst');
    $expzoombystreet = $expreqfield->get('c_admanager_add_googlemaps_zoomstreet');
    $expgooglewidth = $expreqfield->get('c_admanager_add_googlemaps_width');
    $expgoogleheight = $expreqfield->get('c_admanager_add_googlemaps_height');
    
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
        var expmapTypeId = google.maps.MapTypeId.".$expreqfield->get('c_general_gmapmaptypeid').";
        var latid = 'jform_latitude';
        var longid = 'jform_longitude';
        var expalert = '".JText::_('COM_EXPAUTOSPRO_GOOGLEMAP_ALERT_TEXT')."';
        var exp_map_canvas = 'exp_mapadd_canvas';
                ";

    $document->addScriptDeclaration($script);
}
?>
<script type="text/javascript">
    function change_cat(val){
        document.location.href='index.php?option=com_expautospro&view=expadmanager&layout=edit&id=<?php echo $this->item->id; ?>&expcat='+val;
        //alert("");
    }
    Joomla.submitbutton = function(task)
    {
        /*
        var form = document.getElementById('expautosprocat-form');
        if (form.jform_catid.value == "0" && task != 'expmake.cancel'){
            form.jform_catid.style.backgroundColor = '#FFEAEA';
            form.jform_catid.focus();
            return false;
        }
         */
        if (task == 'expadmanager.cancel' || document.formvalidator.isValid(document.id('expautosprocat-form'))) {
            Joomla.submitform(task, document.getElementById('expautosprocat-form'));
        }
    }

</script>
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="expautosprocat-form" class="form-validate">
    <div class="width-70 fltlft">
        <fieldset class="adminform">
            <legend><?php echo empty($this->item->id) ? JText::_('COM_EXPAUTOSPRO_ADMANAGERS_NEW_TITLE') : JText::sprintf('COM_EXPAUTOSPRO_ADMANAGERS_EDIT_TITLE', $this->item->id); ?></legend>
            <ul class="">

                <li><?php echo $this->form->getLabel('catid'); ?>
                    <?php echo $this->form->getInput('catid'); ?></li>
            </ul>
        </fieldset>
    </div>
    <div class="width-30 fltrt">
        <fieldset class="adminform">
            <legend><?php echo JText::_('JGRID_HEADING_ID'); ?></legend>
            <ul class=""> 
                <li><?php echo $this->form->getLabel('id'); ?>
                    <?php echo $this->form->getInput('id'); ?></li>
            </ul>
        </fieldset>
    </div>
    <?php if ($this->item->id > 0 || $this->expdata > 0): ?>
        <div class="width-100 fltlft">
            <fieldset class="adminform">
                <?php echo JHtml::_('tabs.start', 'expadmanager-tabs-' . $this->item->id, array('useCookie' => 0)); ?>
                <?php echo JHtml::_('tabs.panel', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_GENERAL_TEXT'), 'expadmanager_general'); ?>
                <fieldset class="panelform" >
                    <ul class="adminformlist">
                        <li><?php echo $this->form->getLabel('user'); ?>
                            <?php echo $this->form->getInput('user'); ?></li>
                        <?php
                        if ($expusefield->get('usemakes')):
                            if ($expreqfield->get('c_admanager_req_makes'))
                                $this->form->setFieldAttribute('make', 'required', 'true');
                            ?>
                        <li><?php echo $this->form->getLabel('make'); ?>
                            <?php echo $this->form->getInput('make'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usemodels')):
                            if ($expreqfield->get('c_admanager_req_models'))
                                $this->form->setFieldAttribute('model', 'required', 'true');
                            ?>
                        <li><?php echo $this->form->getLabel('model'); ?>
                            <?php echo $this->form->getInput('model'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usespecificmodel')):
                            if ($expreqfield->get('c_admanager_req_specificmodel'))
                                $this->form->setFieldAttribute('specificmodel', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('specificmodel'); ?>
                                <?php echo $this->form->getInput('specificmodel'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usebodytype')):
                            if ($expreqfield->get('c_admanager_req_bodytype'))
                                $this->form->setFieldAttribute('bodytype', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('bodytype'); ?>
                                <?php echo $this->form->getInput('bodytype'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usedrive')):
                            if ($expreqfield->get('c_admanager_req_drive'))
                                $this->form->setFieldAttribute('drive', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('drive'); ?>
                                <?php echo $this->form->getInput('drive'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usefuel')):
                            if ($expreqfield->get('c_admanager_req_fuel'))
                                $this->form->setFieldAttribute('fuel', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('fuel'); ?>
                                <?php echo $this->form->getInput('fuel'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usetrans')):
                            if ($expreqfield->get('c_admanager_req_trans'))
                                $this->form->setFieldAttribute('trans', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('trans'); ?>
                                <?php echo $this->form->getInput('trans'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usespecifictrans')):
                            if ($expreqfield->get('c_admanager_req_specifictrans'))
                                $this->form->setFieldAttribute('specifictrans', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('specifictrans'); ?>
                                <?php echo $this->form->getInput('specifictrans'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useextrafield1')):
                            if ($expreqfield->get('c_admanager_req_extrafield1'))
                                $this->form->setFieldAttribute('extrafield1', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('extrafield1'); ?>
                            <?php echo $this->form->getInput('extrafield1'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useextrafield2')):
                            if ($expreqfield->get('c_admanager_req_extrafield2'))
                                $this->form->setFieldAttribute('extrafield2', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('extrafield2'); ?>
                            <?php echo $this->form->getInput('extrafield2'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useextrafield3')):
                            if ($expreqfield->get('c_admanager_req_extrafield3'))
                                $this->form->setFieldAttribute('extrafield3', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('extrafield3'); ?>
                            <?php echo $this->form->getInput('extrafield3'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usecondition')):
                            if ($expreqfield->get('c_admanager_req_condition'))
                                $this->form->setFieldAttribute('condition', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('condition'); ?>
                            <?php echo $this->form->getInput('condition'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useextcolor')):
                            if ($expreqfield->get('c_admanager_req_extcolor'))
                                $this->form->setFieldAttribute('extcolor', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('extcolor'); ?>
                            <?php echo $this->form->getInput('extcolor'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usespecificcolor')):
                            if ($expreqfield->get('c_admanager_req_specificcolor'))
                                $this->form->setFieldAttribute('specificcolor', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('specificcolor'); ?>
                            <?php echo $this->form->getInput('specificcolor'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usemetalliccolor')):
                            if ($expreqfield->get('c_admanager_req_metalliccolor'))
                                $this->form->setFieldAttribute('metalliccolor', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('metalliccolor'); ?>
                            <?php echo $this->form->getInput('metalliccolor'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useintcolor')):
                            if ($expreqfield->get('c_admanager_req_intcolor'))
                                $this->form->setFieldAttribute('intcolor', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('intcolor'); ?>
                            <?php echo $this->form->getInput('intcolor'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usemonth')):
                            if ($expreqfield->get('c_admanager_req_month'))
                                $this->form->setFieldAttribute('month', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('month'); ?>
                            <?php echo $this->form->getInput('month'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useyear')):
                            if ($expreqfield->get('c_admanager_req_year'))
                                $this->form->setFieldAttribute('year', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('year'); ?>
                            <?php echo $this->form->getInput('year'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usevincode')):
                            if ($expreqfield->get('c_admanager_req_vincode'))
                                $this->form->setFieldAttribute('vincode', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('vincode'); ?>
                            <?php echo $this->form->getInput('vincode'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usemileage')):
                            if ($expreqfield->get('c_admanager_req_mileage'))
                                $this->form->setFieldAttribute('mileage', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('mileage'); ?>
                            <?php echo $this->form->getInput('mileage'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usedisplacement')):
                            if ($expreqfield->get('c_admanager_req_displacement'))
                                $this->form->setFieldAttribute('displacement', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('displacement'); ?>
                            <?php echo $this->form->getInput('displacement'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usedoors')):
                            if ($expreqfield->get('c_admanager_req_doors'))
                                $this->form->setFieldAttribute('doors', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('doors'); ?>
                            <?php echo $this->form->getInput('doors'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useseats')):
                            if ($expreqfield->get('c_admanager_req_seats'))
                                $this->form->setFieldAttribute('seats', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('seats'); ?>
                            <?php echo $this->form->getInput('seats'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useengine')):
                            if ($expreqfield->get('c_admanager_req_engine'))
                                $this->form->setFieldAttribute('engine', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('engine'); ?>
                            <?php echo $this->form->getInput('engine'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useco')):
                            if ($expreqfield->get('c_admanager_req_co'))
                                $this->form->setFieldAttribute('co', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('co'); ?>
                            <?php echo $this->form->getInput('co'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usestocknum')):
                            if ($expreqfield->get('c_admanager_req_stocknum'))
                                $this->form->setFieldAttribute('stocknum', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('stocknum'); ?>
                            <?php echo $this->form->getInput('stocknum'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usefconsum')):
                            if ($expreqfield->get('c_admanager_req_fconsumcity'))
                                $this->form->setFieldAttribute('fconsumcity', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('fconsumcity'); ?>
                            <?php echo $this->form->getInput('fconsumcity'); ?></li>
                            <?php if ($expreqfield->get('c_admanager_req_fconsumfreeway'))
                                $this->form->setFieldAttribute('fconsumfreeway', 'required', 'true'); ?>
                            <li><?php echo $this->form->getLabel('fconsumfreeway'); ?>
                            <?php echo $this->form->getInput('fconsumfreeway'); ?></li>
                            <?php if ($expreqfield->get('c_admanager_req_fconsumcombined'))
                                $this->form->setFieldAttribute('fconsumcombined', 'required', 'true'); ?>
                            <li><?php echo $this->form->getLabel('fconsumcombined'); ?>
                            <?php echo $this->form->getInput('fconsumcombined'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useadacceleration')):
                            if ($expreqfield->get('c_admanager_req_adacceleration'))
                                $this->form->setFieldAttribute('adacceleration', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('adacceleration'); ?>
                            <?php echo $this->form->getInput('adacceleration'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usemaxspeed')):
                            if ($expreqfield->get('c_admanager_req_maxspeed'))
                                $this->form->setFieldAttribute('maxspeed', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('maxspeed'); ?>
                            <?php echo $this->form->getInput('maxspeed'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('uselength')):
                            if ($expreqfield->get('c_admanager_req_length'))
                                $this->form->setFieldAttribute('length', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('length'); ?>
                            <?php echo $this->form->getInput('length'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usewidth')):
                            if ($expreqfield->get('c_admanager_req_width'))
                                $this->form->setFieldAttribute('width', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('width'); ?>
                            <?php echo $this->form->getInput('width'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useunweight')):
                            if ($expreqfield->get('c_admanager_req_unweight'))
                                $this->form->setFieldAttribute('unweight', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('unweight'); ?>
                            <?php echo $this->form->getInput('unweight'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usegrweight')):
                            if ($expreqfield->get('c_admanager_req_grweight'))
                                $this->form->setFieldAttribute('grweight', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('grweight'); ?>
                            <?php echo $this->form->getInput('grweight'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useembedcode')):
                            if ($expreqfield->get('c_admanager_req_embedcode'))
                                $this->form->setFieldAttribute('embedcode', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('embedcode'); ?>
                            <?php echo $this->form->getInput('embedcode'); ?></li>
                        <?php endif; ?>
                        <?php if ($expusefield->get('useotherinfo')):?>
                            <li><?php echo $this->form->getLabel('otherinfo'); ?>
                            <?php echo $this->form->getInput('otherinfo'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usecountry')):
                            if ($expreqfield->get('c_admanager_req_country'))
                                $this->form->setFieldAttribute('country', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('country'); ?>
                                <?php echo $this->form->getInput('country'); ?></li>
                            <?php
                            if ($expusefield->get('usestate')):
                                if ($expreqfield->get('c_admanager_req_state'))
                                    $this->form->setFieldAttribute('expstate', 'required', 'true');
                                ?>
                                <li><?php echo $this->form->getLabel('expstate'); ?>
                                    <?php echo $this->form->getInput('expstate'); ?></li>
                                <?php
                                if ($expusefield->get('usecity')):
                                    if ($expreqfield->get('c_admanager_req_city'))
                                        $this->form->setFieldAttribute('city', 'required', 'true');
                                    ?>
                                    <li><?php echo $this->form->getLabel('city'); ?>
                                    <?php echo $this->form->getInput('city'); ?></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usestreet')):
                            if ($expreqfield->get('c_admanager_req_street'))
                                $this->form->setFieldAttribute('street', 'required', 'true');
                            if($expusefield->get('usegooglemaps'))
                                $this->form->setFieldAttribute('street', 'onchange', 'codeStreet(this.value);return false;');
                            ?>
                            <li><?php echo $this->form->getLabel('street'); ?>
                                <?php echo $this->form->getInput('street'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usezipcode')):
                            if ($expreqfield->get('c_admanager_req_zipcode'))
                                $this->form->setFieldAttribute('zipcode', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('zipcode'); ?>
                                <?php echo $this->form->getInput('zipcode'); ?></li>
                        <?php endif; ?>
                        <?php if($expusefield->get('usegooglemaps')):
                            if ($expreqfield->get('c_admanager_add_latlong')){
                                $this->form->setFieldAttribute('latitude', 'required', 'true');
                                $this->form->setFieldAttribute('longitude', 'required', 'true');
                            }
                        $this->form->setFieldAttribute('latitude', 'onchange', 'findLangLong();return false;');
                        $this->form->setFieldAttribute('longitude', 'onchange', 'findLangLong();return false;');
                            ?>
                                <li><?php echo $this->form->getLabel('latitude'); ?>
                                    <?php echo $this->form->getInput('latitude'); ?></li>


                                <li><?php echo $this->form->getLabel('longitude'); ?>
                                    <?php echo $this->form->getInput('longitude'); ?></li>
                        <?php endif; ?>
                    <?php if($expreqfield->get('c_admanager_useradd_showgooglemaps')):?> 
                    <div class="clr"></div>
                        <div id="exp_mapadd_canvas" style="width: <?php echo $expgooglewidth;?>px; height: <?php echo $expgoogleheight;?>px;"></div>
                    <div class="clr"></div>
                    <?php endif; ?>
                        <li><?php echo $this->form->getLabel('creatdate'); ?>
                            <?php echo $this->form->getInput('creatdate'); ?></li>
                        <li><?php echo $this->form->getLabel('expirdate'); ?>
                        <?php echo $this->form->getInput('expirdate'); ?></li>
                        <?php
                        if ($expusefield->get('useprice')):
                            if ($expreqfield->get('c_admanager_req_price'))
                                $this->form->setFieldAttribute('price', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('price'); ?>
                            <?php echo $this->form->getInput('price'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usebprice')):
                            if ($expreqfield->get('c_admanager_req_bprice'))
                                $this->form->setFieldAttribute('bprice', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('bprice'); ?>
                            <?php echo $this->form->getInput('bprice'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('useexpprice')):
                            if ($expreqfield->get('c_admanager_req_expprice'))
                                $this->form->setFieldAttribute('expprice', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('expprice'); ?>
                            <?php echo $this->form->getInput('expprice'); ?></li>
                        <?php endif; ?>
                        <?php
                        if ($expusefield->get('usevattext')):
                            if ($expreqfield->get('c_admanager_req_vattext'))
                                $this->form->setFieldAttribute('vattext', 'required', 'true');
                            ?>
                            <li><?php echo $this->form->getLabel('vattext'); ?>
                            <?php echo $this->form->getInput('vattext'); ?></li>
                            <?php endif; ?>
                            
                            <li><?php echo $this->form->getLabel('expfile'); ?>
                            <?php echo $this->form->getInput('expfile'); ?></li>
                            
                        <li><?php echo $this->form->getLabel('hits'); ?>
                            <?php echo $this->form->getInput('hits'); ?></li>
                        <li><?php echo $this->form->getLabel('fcommercial'); ?>
                            <?php echo $this->form->getInput('fcommercial'); ?></li>
                        <li><?php echo $this->form->getLabel('ftop'); ?>
                            <?php echo $this->form->getInput('ftop'); ?></li>
                        <li><?php echo $this->form->getLabel('special'); ?>
                            <?php echo $this->form->getInput('special'); ?></li>
                        <li><?php echo $this->form->getLabel('solid'); ?>
                            <?php echo $this->form->getInput('solid'); ?></li>
                        <li><?php echo $this->form->getLabel('expreserved'); ?>
                            <?php echo $this->form->getInput('expreserved'); ?></li>
                        <li><?php echo $this->form->getLabel('state'); ?>
                            <?php echo $this->form->getInput('state'); ?></li>
                        <li><?php echo $this->form->getLabel('access'); ?>
                            <?php echo $this->form->getInput('access'); ?></li>
                        <li><?php echo $this->form->getLabel('ordering'); ?>
                            <?php echo $this->form->getInput('ordering'); ?></li>
                        <li><?php echo $this->form->getLabel('language'); ?>
    <?php echo $this->form->getInput('language'); ?></li>
                    </ul>
                </fieldset>
                <?php if ($expreqfield->get('c_admanager_useparams')):?>
                    <?php echo $this->loadTemplate('params');  ?>
                <?php endif; ?>
                <?php if ($expusefield->get('useequipment')): ?>
                        <?php echo JHtml::_('tabs.panel', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_EQUIPMENT_TEXT'), 'expadmanager_equipment'); ?>
                    <fieldset class="panelform" >
                        <?php echo $this->form->getInput('equipment'); ?>
                    <?php endif; ?>
                        <?php echo JHtml::_('tabs.panel', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_IMAGES_TEXT'), 'expadmanager_images'); ?>
                    <fieldset class="panelform" >
                    <?php echo $this->form->getInput('expimages'); ?>
                    </fieldset>
    <?php echo JHtml::_('tabs.end'); ?>
                    <div class="clr"></div>
                </fieldset>
        </div>
        <!--
        <div class="width-40 fltrt">
        <?php //echo JHtml::_('sliders.start', 'expcategory-sliders-' . $this->item->id, array('useCookie' => 1));  ?>
        <?php //echo $this->loadTemplate('metadata');  ?>
    <?php //echo JHtml::_('sliders.end');   ?>
        </div>
        -->
    <?php endif; ?>
    <input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>
</form>
