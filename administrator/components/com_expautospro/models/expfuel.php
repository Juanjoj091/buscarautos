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

class ExpAutosProModelExpfuel extends JModelAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_FUEL';

    protected function canDelete($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.delete', 'com_expautospro.expfuel.' . (int) $record->catid);
        } else {
            return parent::canDelete($record);
        }
    }

    protected function canEditState($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_expautospro.expfuel.' . (int) $record->catid);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getTable($type = 'Expfuel', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_expautospro.expfuel', 'expfuel', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        if ($this->getState('expfuel.id')) {
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
        $data = JFactory::getApplication()->getUserState('com_expautospro.edit.expfuel.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            if ($this->getState('expfuel.id') == 0) {
                $app = JFactory::getApplication();
                $data->set('catid', JRequest::getInt('catid', $app->getUserState('com_expautospro.expfuel.filter.category_id')));
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
