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

class JFormFieldExpmenumodel extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expmenumodel';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        $model_id = $this->value;
        $make_id = '';
        if($this->form->getValue('request'))
            $make_id = $this->form->getValue('request')->makeid;
        $options=ExpAutosProFields::getExpvariables($make_id,'model','name','makeid',$model_id,'name','',$this->name);

        return $options;
    }

}
