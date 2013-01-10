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
require_once JPATH_SITE . '/components/com_expautospro/helpers/expparams.php';


class JFormFieldExpJcat extends JFormField {

    protected $type = 'ExpJcat';

    protected function getInput() {
        // Initialise variables.
        $options = array();
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        $expcat = JRequest::getInt('expcat', 0);
        if($expcat){
            $val_id = $expcat;
        }else{
            $val_id = $this->form->getValue('catid');
        }
        $listgroupparams = '';
        if ($UserId > 0) {
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        }
        $expallowcat = $listgroupparams->get('g_categories');
        $published = $this->element['published'] ? $this->element['published'] : array(0, 1);
        $name = (string) $this->element['name'];

        $jinput = JFactory::getApplication()->input;
        if ($this->element['parent'] || $jinput->get('option') == 'com_categories') {
            $oldCat = $jinput->get('id', 0);
            $oldParent = $this->form->getValue($name, 0);
            $extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('extension', 'com_content');
        } else {
        // For items the old category is the category they are in when opened or 0 if new.
            $thisItem = $jinput->get('id', 0);
            $oldCat = $this->form->getValue($name, 0);
            $extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('option', 'com_content');
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('a.id AS value, a.title AS text, a.level, a.published');
        $query->from('#__categories AS a');
        $query->join('LEFT', $db->quoteName('#__categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

        if ($this->element['parent'] == true || $jinput->get('option') == 'com_categories') {
            $query->where('(a.extension = ' . $db->quote($extension) . ' OR a.parent_id = 0)');
        } else {
            $query->where('(a.extension = ' . $db->quote($extension) . ')');
        }
        if ($oldCat != 0 && ($this->element['parent'] == true || $jinput->get('option') == 'com_categories')) {
            $query->join('LEFT', $db->quoteName('#__categories') . ' AS p ON p.id = ' . (int) $oldCat);
            $query->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');

            $rowQuery = $db->getQuery(true);
            $rowQuery->select('a.id AS value, a.title AS text, a.level, a.parent_id');
            $rowQuery->from('#__categories AS a');
            $rowQuery->where('a.id = ' . (int) $oldCat);
            $db->setQuery($rowQuery);
            $row = $db->loadObject();
        }

        if (is_numeric($published)) {
            $query->where('a.published = ' . (int) $published);
        } elseif (is_array($published)) {
            JArrayHelper::toInteger($published);
            $query->where('a.published IN (' . implode(',', $published) . ')');
        }

        $query->group('a.id, a.title, a.level, a.lft, a.rgt, a.extension, a.parent_id');
        $query->order('a.lft ASC');

        $db->setQuery($query);

        $options = $db->loadObjectList();

        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        //print_r($options);

        for ($i = 0, $n = count($options); $i < $n; $i++) {
            if (!in_array($options[$i]->value, $expallowcat)) {
                $options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
                $options[$i] = JHtml::_('select.option', $options[$i]->value, $options[$i]->text, 'value', 'text', true);
                //unset($options[$i]);
            } else {
                if ($this->element['parent'] == true || $jinput->get('option') == 'com_categories') {
                    if ($options[$i]->level == 0) {
                        $options[$i]->text = JText::_('COM_EXPAUTOSPRO_JGLOBAL_ROOT_PARENT');
                    }
                }
                if ($options[$i]->published == 1) {
                    $options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
                } else {
                    $options[$i]->text = str_repeat('- ', $options[$i]->level) . '[' . $options[$i]->text . ']';
                }
            }
        }
        if (($this->element['parent'] == true || $jinput->get('option') == 'com_categories')
                && (isset($row) && !isset($options[0])) && isset($this->element['show_root'])) {
            if ($row->parent_id == '1') {
                $parent = new stdClass();
                $parent->text = JText::_('COM_EXPAUTOSPRO_JGLOBAL_ROOT_PARENT');
                array_unshift($options, $parent);
            }
            array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_JGLOBAL_ROOT')));
        }


        array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_JSELECT_CATEGORY')));
        //$options = array_merge(parent::getOptions(), $options);
        $onchange = ' onchange="change_cat(this.value);"';
        $return = JHtml::_('select.genericlist', $options, $this->name, $onchange, 'value', 'text', $val_id);
        return $return;
    }

}
