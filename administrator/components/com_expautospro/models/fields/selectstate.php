<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/


defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
require_once JPATH_COMPONENT.'/helpers/expfields.php';
require_once JPATH_COMPONENT . '/helpers/expimages.php';

class JFormFieldSelectstate extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Selectstate';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        
        $expview = JRequest::getVar('view');
        $expparams = ExpAutosProImages::getExpParams('config', 1);
        if($this->form->getValue('catid')){
            $expcat = $this->form->getValue('catid');
        }else{
            $expcat = JRequest::getInt('expcat', 0);
        }
        $expcatParams = ExpAutosProImages::getCatParams($expcat);
        $expgoogle ='';
        if(($expview == 'expuser' && $expparams->get('c_admanager_useradd_showgooglemaps')) || ($expview == 'expadd' && $expcatParams->get('usegooglemaps')) || ($expview == 'expadmanager' && $expcatParams->get('usegooglemaps')))
            $expgoogle = 'findAddress(this);return false;';
        $country_id = $this->form->getValue('country');
        $document = JFactory::getDocument();
        $script = '';
        $script .= "
                    window.addEvent('domready', function() {
                    $('jformexpstate').addEvent('change', function(e) {
                    var url = 'index.php?option=com_expautospro&view=expadmanagers&format=ajax&state_id='+this.value;
                    expchained(url,'jformcity',e);
                    });
                    })
                ";

        $document->addScriptDeclaration($script);
        if(!$country_id){
            $country_id=0;
        }
        $state_id = $this->form->getValue('expstate');
        $options=ExpAutosProFields::getExpvariables($country_id,'state','name','catid',$state_id,'name',$expgoogle,$this->name);

        return $options;
    }

}
