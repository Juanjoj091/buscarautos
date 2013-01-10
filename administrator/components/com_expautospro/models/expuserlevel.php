<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class ExpAutosProModelExpuserlevel extends JModelAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_USERLEVELS';

    protected function canDelete($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.delete', 'com_expautospro.expuserlevel.' . (int) $record->catid);
        } else {
            return parent::canDelete($record);
        }
    }

    protected function canEditState($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_expautospro.expuserlevel.' . (int) $record->catid);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getTable($type = 'Expuserlevel', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_expautospro.expuserlevel', 'expuserlevel', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        if ($this->getState('expcat.id')) {
            $form->setFieldAttribute('catid', 'action', 'core.edit');
        } else {
            $form->setFieldAttribute('catid', 'action', 'core.create');
        }

        if (!$this->canEditState((object) $data)) {
            $form->setFieldAttribute('ordering', 'disabled', 'true');
            $form->setFieldAttribute('state', 'disabled', 'true');

            $form->setFieldAttribute('ordering', 'filter', 'unset');
            $form->setFieldAttribute('state', 'filter', 'unset');
        }

        return $form;
    }

    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_expautospro.edit.expuserlevel.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            if ($this->getState('expuserlevel.id') == 0) {
                $app = JFactory::getApplication();
                $data->set('userlevel', JRequest::getInt('userlevel', $app->getUserState('com_expautospro.expuserlevels.filter.group')));
            }
        }

        return $data;
    }

    protected function prepareTable(&$table) {
        
    }

    protected function getReorderConditions($table) {
        $condition = array();
        $condition[] = 'catid = ' . (int) $table->catid;
        $condition[] = 'state >= 0';
        return $condition;
    }

}
