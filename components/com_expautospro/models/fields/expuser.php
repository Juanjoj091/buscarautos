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

class JFormFieldExpuser extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expuser';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('username');
        $query->from('#__users ');
        $query->where('block = 0');
        $query->where('id = '.(int)$this->value);

        // Get the options.
        $db->setQuery($query);

        $username = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        if (!$data) {
            $options = '<input id="'.$this->id.'" name="'.$this->name.'" value="'.$username.'"  readonly="readonly" size="35" />';
        }

        return $options;
    }

}
