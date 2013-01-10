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
require_once JPATH_SITE . '/components/com_expautospro/helpers/expparams.php';

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
        $expcat_s = JRequest::getInt('expcat', 0);
        if($expcat_s){
            $expcat = $expcat_s;
        }else{
            $expcat = $this->form->getValue('catid');
        }
        $expcatParams = ExpAutosProExpparams::getCatParams($expcat);
        //print_r($expcatParams->get('usemodels'));
        $options = array();
        if($expcatParams->get('usemodels')){
            $document = JFactory::getDocument();
            $script = '';
            $script .= "
                    function change_make(val){
                    var url = 'index.php?option=com_expautospro&view=expuser&format=ajax&expmake_id='+val;
                        ajaxgetchained(url,'jformmodel')
                    }
                    ";

            $document->addScriptDeclaration($script);
        }
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
        $attr = '';
        $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        $attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
        if($expcatParams->get('usemodels')){
            $attr .= ' onchange="change_make(this.value);"';
        }
        if (!$data) {
            array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT')));

            $return = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $val_id);
        }

        return $return;
    }
}
