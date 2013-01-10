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
require_once JPATH_ADMINISTRATOR.'/components/com_expautospro/helpers/expfields.php';

class JFormFieldExpmenucities extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expmenucities';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        
        $state_id = '';
        if($this->form->getValue('request'))
            $state_id = $this->form->getValue('request')->expstate;
        if(!$state_id){
            $state_id=0;
        }
        $city_id = $this->value;
        $options=ExpAutosProFields::getExpvariables($state_id,'cities','city_name','catid',$city_id,'city_name','',$this->name);

        return $options;
    }

}
