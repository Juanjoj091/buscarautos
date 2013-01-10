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

class JFormFieldExpcategor extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expcategor';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        // Initialize variables.
        $options = array();
        $expallowcat='';
        if($this->element['allowcat']){
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        $listgroupparams='';
        if ($UserId > 0) {
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        }
        $expallowcat = implode(',', $listgroupparams->get('g_categories'));
        }
        $expcat = JRequest::getInt('expcat', 0);
        if($expcat){
            $val_id = $expcat;
        }else{
            $val_id = $this->form->getValue('catid');
        }
        //print_r($this->form->getValue('catid'));
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.id As value, a.title As text');
        $query->from('#__categories AS a');
        $query->where('a.published > 0');
        if($expallowcat>0)
        $query->where('a.id IN('.$expallowcat.')');
        //$query->order('a.ordering');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        $onchange	= ' onchange="change_cat(this.value);"';
        if (!$data) {
            array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT')));

            $return = JHtml::_('select.genericlist', $options, $this->name, $onchange, 'value', 'text', $val_id);
        }

        return $return;
    }

}
