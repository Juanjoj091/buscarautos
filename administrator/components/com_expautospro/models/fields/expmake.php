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

class JFormFieldExpmake extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Expmake';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getInput($data=0) {
        // Initialize variables.
        $val_id = $this->form->getValue('make');
        $expcat = JRequest::getInt('expcat', 0);
        $options = array();
        $document = JFactory::getDocument();
        $script = '';
        $script .= "
                    window.addEvent('domready', function() {
                    $('jformmake').addEvent('change', function(e) {
                    var url = 'index.php?option=com_expautospro&view=expadmanagers&format=ajax&expmake_id='+this.value;
                    expchained(url,'jformmodel',e);
                    });
                    })
                ";

        $document->addScriptDeclaration($script);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if ($data) {
            $databasename = (string) $data;
        } else {
            $databasename = (string) $this->element['database'];
        }
        $query->select('a.id As value, CONCAT(a.name," >>> ",c.title) As text');
        $query->from('#__expautos_make AS a');
	$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
        if($expcat>0){
           $query->where('a.catid = '.$expcat); 
        }
        $query->where('a.state > 0');
        $query->order('a.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        // $onchange	= ' onchange="change_cat(this.value);"';
        $onchange = '';
        $return	= '';
            array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT')));

            $return = JHtml::_('select.genericlist', $options, $this->name, $onchange, 'value', 'text', $val_id);

        return $return;
    }
}
