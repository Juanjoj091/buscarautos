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

class JFormFieldExpuserlevel extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expuserlevel';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getOptions($data=0) {
        // Initialize variables.
        $options = array();
        $document = JFactory::getDocument();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('a.userlevel');
        $query->from('`#__expautos_userlevel` AS a');

        $query->select('ug.id As value, ug.title AS text');
        $query->join('LEFT', '#__usergroups AS ug ON ug.id = a.userlevel');
        $query->where('a.state > 0');
        $query->order('ug.title');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();
        if($options){
            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
            foreach ($options as $option) {
                $return[] = JHTML::_('select.option', $option->value, $option->text);            
            }

            return $return;
        }else{
            return false;
        }
    }

}
