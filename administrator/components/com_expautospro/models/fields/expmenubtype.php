<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */


defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldExpmenubtype extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expmenubtype';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getOptions($data = 0) {
        // Initialize variables.
        $options = array();
        $document = JFactory::getDocument();
        $expcatid = '';
        if(is_object($this->form->getValue('request')))
            $expcatid = (int)$this->form->getValue('request')->catid;

        if ($this->value) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id As value, name As text');
            $query->from('#__expautos_bodytype');
            $query->where('catid = '.$expcatid);
            $query->where('state > 0');
            $query->order('ordering');

            // Get the options.
            $db->setQuery($query);

            $options = $db->loadObjectList();

            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
        }
        if (!$data) {
            array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT')));
        }

        return $options;
    }

}
