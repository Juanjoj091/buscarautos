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

class JFormFieldExpgroups extends JFormFieldList {

    protected $type = 'Expgroups';

    public function getOptions() {
        // Initialize variables.
        $options = array();
        $db = JFactory::getDbo();
        $db->setQuery(
                'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
                ' FROM #__usergroups AS a' .
                ' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
                ' GROUP BY a.id' .
                ' ORDER BY a.lft ASC'
        );
        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        foreach ($options as &$option) {
            $option->text = str_repeat('- ', $option->level) . $option->text;
        }

        return $options;
    }

}

?>
