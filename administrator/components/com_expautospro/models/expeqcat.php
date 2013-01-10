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

class ExpAutosProModelExpeqcat extends JModelAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_EQCAT';

    protected function canDelete($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.delete', 'com_expautospro.expeqcat.' . (int) $record->catid);
        } else {
            return parent::canDelete($record);
        }
    }

    protected function canEditState($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_expautospro.expeqcat.' . (int) $record->catid);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getTable($type = 'Expeqcat', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_expautospro.expeqcat', 'expeqcat', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        if ($this->getState('expeqcat.id')) {
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
        $data = JFactory::getApplication()->getUserState('com_expautospro.edit.expeqcat.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            if ($this->getState('expeqcat.id') == 0) {
                $app = JFactory::getApplication();
                $data->set('catid', JRequest::getInt('catid', $app->getUserState('com_expautospro.expeqcats.filter.category_id')));
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

    public function makes_build($buildcat, $pks, $makeaction) {

        // Sanitize user ids.
        $pks = array_unique($pks);
        JArrayHelper::toInteger($pks);

        // Remove any values of zero.
        if (array_search(0, $pks, true)) {
            unset($pks[array_search(0, $pks, true)]);
        }
        if (empty($pks)) {
            $this->setError(JText::_('COM_EXPAUTOSPRO_EQCAT_NO_ITEM_SELECTED'));
            return false;
        }
        if (empty($buildcat)) {
            $this->setError(JText::_('COM_EXPAUTOSPRO_EQCAT_NO_CATEGOR_SELECTED'));
            return false;
        }

        $done = false;

        if (!empty($buildcat)) {
            if ($makeaction == 'c' && !$this->buildCopy($buildcat, $pks)) {
                return false;
            } else if ($makeaction == 'm' && !$this->buildCopy($buildcat, $pks, '1')) {
                return false;
            }
            $done = true;
        }

        if (!$done) {
            $this->setError(JText::_('COM_EXPAUTOSPRO_INSUFFICIENT_COPYMOVE_INFORMATION'));
            return false;
        }

        return true;
    }

    public function buildCopy($value, $pks, $variable = 0) {
        $parts = $value;
        $table = $this->getTable();
        $db = $this->getDbo();
        $user = JFactory::getUser();

        while (!empty($pks)) {
            $pk = array_shift($pks);

            $table->reset();
            if (!$table->load($pk)) {
                if ($error = $table->getError()) {
                    // Fatal error
                    $this->setError($error);
                    return false;
                } else {
                    // Not fatal error
                    $this->setError(JText::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
                    continue;
                }
            }
            if (!$variable) {
                $table->id = 0;
            }
            $table->catid = $value;

            // Store the row.
            if (!$table->store()) {
                $this->setError($table->getError());
                return false;
            }
            $count--;
        }


        // Clear the component's cache
        $cache = JFactory::getCache('com_expautospro');
        $cache->clean();

        return true;
    }

}
