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
require_once JPATH_COMPONENT . '/helpers/expimages.php';
require_once JPATH_COMPONENT . '/helpers/expfields.php';

class JFormFieldExpselect extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expselect';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {

        $exppostcat = JRequest::getInt('expcat', 0);
        if ($exppostcat) {
            $expcat = JRequest::getInt('expcat', 0);
        } else {
            $expcat = $this->form->getValue('catid');
        }
        $databasename = (string) $this->element['database'];
        $val_id = $this->form->getValue($databasename);
        $return = ExpAutosProFields::getExpvariables($expcat, $databasename, 'name', 'catid', $val_id,'name','',$this->name);
        
        return $return;
    }

}
