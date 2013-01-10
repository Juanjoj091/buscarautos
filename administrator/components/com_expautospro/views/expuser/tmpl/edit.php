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
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'expuser.cancel' || document.formvalidator.isValid(document.id('expautosprocat-form'))) {
            Joomla.submitform(task, document.getElementById('expautosprocat-form'));
        }
    }

</script>
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="expautosprocat-form" class="form-validate">
    <div class="width-100 fltlft">
        <fieldset class="adminform">
            <legend><?php echo empty($this->item->id) ? JText::_('COM_EXPAUTOSPRO_USER_NEW_TITLE') : JText::sprintf('COM_EXPAUTOSPRO_USER_EDIT_TITLE', $this->item->id); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('userid'); ?>
                    <?php echo $this->form->getInput('userid'); ?></li>
                <li><?php echo $this->form->getLabel('lastname'); ?>
                    <?php echo $this->form->getInput('lastname'); ?></li>
                <li><?php echo $this->form->getLabel('companyname'); ?>
                    <?php echo $this->form->getInput('companyname'); ?></li>
                <li><?php echo $this->form->getLabel('country'); ?>
                    <?php echo $this->form->getInput('country'); ?></li>
                <li><?php echo $this->form->getLabel('expstate'); ?>
                    <?php echo $this->form->getInput('expstate'); ?></li>
                <li><?php echo $this->form->getLabel('city'); ?>
                    <?php echo $this->form->getInput('city'); ?></li>
                <?php
                    if($this->expparams->get('c_admanager_useradd_showgooglemaps')){
                        $this->form->setFieldAttribute('street', 'onchange', 'codeStreet(this.value);return false;');
                        $this->form->setFieldAttribute('latitude', 'onchange', 'codeStreet(this.value);return false;');
                        $this->form->setFieldAttribute('longitude', 'onchange', 'codeStreet(this.value);return false;');
                    }
                ?>
                <li><?php echo $this->form->getLabel('street'); ?>
                    <?php echo $this->form->getInput('street'); ?></li>
                <?php
                    $this->form->setFieldAttribute('latitude', 'onchange', 'findLangLong();return false;');
                    $this->form->setFieldAttribute('longitude', 'onchange', 'findLangLong();return false;');
                    ?>
                <li><?php echo $this->form->getLabel('latitude'); ?>
                    <?php echo $this->form->getInput('latitude'); ?></li>
                <li><?php echo $this->form->getLabel('longitude'); ?>
                    <?php echo $this->form->getInput('longitude'); ?></li>
                    <?php if($this->expparams->get('c_admanager_useradd_showgooglemaps')):?> 
                    <div class="clr"></div>
                        <div id="exp_map_canvas" style="width: <?php echo $expgooglewidth;?>px; height: <?php echo $expgoogleheight;?>px;"></div>
                    <div class="clr"></div>
                    <?php endif; ?>
                <li><?php echo $this->form->getLabel('web'); ?>
                    <?php echo $this->form->getInput('web'); ?></li>
                <li><?php echo $this->form->getLabel('phone'); ?>
                    <?php echo $this->form->getInput('phone'); ?></li>
                <li><?php echo $this->form->getLabel('mobphone'); ?>
                    <?php echo $this->form->getInput('mobphone'); ?></li>
                <li><?php echo $this->form->getLabel('fax'); ?>
                    <?php echo $this->form->getInput('fax'); ?></li>
                <li><?php echo $this->form->getLabel('zipcode'); ?>
                    <?php echo $this->form->getInput('zipcode'); ?></li>
                <li><?php echo $this->form->getLabel('logo'); ?>
                    <?php echo $this->form->getInput('logo'); ?></li>
                <div class="clr"></div>
                
                <?php echo $this->loadTemplate('params');  ?>
                
                <div class="clr"></div>
                <li><?php echo $this->form->getLabel('userinfo'); ?>
                    <div class="clr"></div>
                    <?php echo $this->form->getInput('userinfo'); ?></li>
                <li><?php echo $this->form->getLabel('emailstyle'); ?>
                    <?php echo $this->form->getInput('emailstyle'); ?></li>
                <br />
                <br />
                <br />
                <li><?php echo $this->form->getLabel('ucommercial'); ?>
                    <?php echo $this->form->getInput('ucommercial'); ?></li>
                <li><?php echo $this->form->getLabel('utop'); ?>
                    <?php echo $this->form->getInput('utop'); ?></li>
                <li><?php echo $this->form->getLabel('uspecial'); ?>
                    <?php echo $this->form->getInput('uspecial'); ?></li>
                <li><?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?></li>
                <li><?php echo $this->form->getLabel('ordering'); ?>
                    <?php echo $this->form->getInput('ordering'); ?></li>
                <li><?php echo $this->form->getLabel('language'); ?>
                    <?php echo $this->form->getInput('language'); ?></li>
                <li><?php echo $this->form->getLabel('id'); ?>
                    <?php echo $this->form->getInput('id'); ?></li>
            </ul>
        </fieldset>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>
</form>
