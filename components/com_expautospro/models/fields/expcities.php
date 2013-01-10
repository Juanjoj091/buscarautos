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

class JFormFieldExpcities extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Expcities';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getOptions($data=0,$statefilter='')
	{

		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('a.id As value, CONCAT(a.name," >>> ",c.name) As text');
		$query->from('#__expautos_state AS a');
		$query->join('LEFT', '#__expautos_country AS c ON c.id = a.catid');

		$query->where('a.state > 0');
		if(!empty($statefilter)){
		$query->where('a.catid = '.$statefilter);
		}
		$query->order('a.name');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		if(!$data){
		array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT')));
		}

		return $options;
	}
}
