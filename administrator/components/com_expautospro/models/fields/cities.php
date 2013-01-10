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

class JFormFieldCities extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Cities';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        
        $expview = JRequest::getVar('view');
        $expcat = $this->form->getValue('catid');
        if(!$expcat)
            $expcat = JRequest::getInt('expcat',0);
        $expparams = ExpAutosProImages::getExpParams('config', 1);
        $expcatparams='';
        if($expcat)
            $expcatparams = ExpAutosProImages::getCatParams($expcat);
        $expgoogle ='';
        if(($expview == 'expuser' && $expparams->get('c_admanager_useradd_showgooglemaps')) || ($expview == 'expadd' && $expcatparams->get('usegooglemaps')) || ($expview == 'expadmanager' && $expcatparams->get('usegooglemaps')))
            $expgoogle = 'findAddress(this);return false;';
        $state_id = $this->form->getValue('expstate');
        if(!$state_id){
            $state_id=0;
        }
        $city_id = $this->form->getValue('city');
        $options=ExpAutosProFields::getExpvariables($state_id,'cities','city_name','catid',$city_id,'city_name',$expgoogle,$this->name);

        return $options;
    }

}
