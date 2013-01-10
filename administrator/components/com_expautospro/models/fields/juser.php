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

class JFormFieldJuser extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Juser';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getOptions($data=0) {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.userid');
        $query->from('`#__expautos_expuser` AS a');
        $query->where('a.state > 0');
        
        $query->select('us.id As value, us.username AS text');
        $query->join('LEFT', '#__users AS us ON us.id = a.userid');
        $query->where('us.block = 0');
        $query->order('us.username');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        if (!$data) {
            array_unshift($options, JHtml::_('select.option', '', JText::_('COM_EXPAUTOSPRO_SELECT_USER_TEXT')));
        }

        return $options;
    }

}
