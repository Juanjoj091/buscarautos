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

class ExpAutosProModelExpmod extends JModelAdmin
{

	protected $text_prefix = 'COM_EXPAUTOSPRO_MODEL';

	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->makeid)) {
			return $user->authorise('core.delete', 'com_expautospro.expmod.'.(int) $record->makeid);
		}
		else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->makeid)) {
			return $user->authorise('core.edit.state', 'com_expautospro.expmod.'.(int) $record->makeid);
		}
		else {
			return parent::canEditState($record);
		}
	}

	public function getTable($type = 'Expmod', $prefix = 'ExpautosproTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_expautospro.expmod', 'expmod', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		if ($this->getState('expmod.id')) {
			$form->setFieldAttribute('makeid', 'action', 'core.edit');
		} else {
			$form->setFieldAttribute('makeid', 'action', 'core.create');
		}

		if (!$this->canEditState((object) $data)) {
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_expautospro.edit.expmod.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			if ($this->getState('expmod.id') == 0) {
				$app = JFactory::getApplication();
				//print_r(JRequest::getInt('makeid', $app->getUserState('com_expautospromodel.expmods.filter.make_id'))."retertre");

				$data->set('makeid', JRequest::getInt('makeid', $app->getUserState('com_expautospro.expmods.filter.make_id')));
			}
		}

		return $data;
	}

	protected function prepareTable(&$table)
	{

	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'makeid = '. (int) $table->makeid;
		$condition[] = 'state >= 0';
		return $condition;
	}

	public function makes_build($buildcat, $pks, $makeaction)
	{

		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}
		if (empty($pks)) {
			$this->setError(JText::_('COM_EXPAUTOSPRO_MODEL_NO_ITEM_SELECTED'));
			return false;
		}
		if (empty($buildcat)) {
			$this->setError(JText::_('COM_EXPAUTOSPRO_MODEL_NO_MAKE_SELECTED'));
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

	public function buildCopy($value, $pks, $variable = 0)
	{
		$parts		= $value;
		$table	= $this->getTable();
		$db		= $this->getDbo();
		$user	= JFactory::getUser();

		while (!empty($pks))
		{
			$pk = array_shift($pks);

			$table->reset();
			if (!$table->load($pk)) {
				if ($error = $table->getError()) {
					// Fatal error
					$this->setError($error);
					return false;
				}
				else {
					$this->setError(JText::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}
			if(!$variable){
			$table->id			= 0;
			}
			$table->makeid	= $value;

			if (!$table->store()) {
				$this->setError($table->getError());
				return false;
			}
			$count--;
		}

		$cache = JFactory::getCache('com_expautospro');
		$cache->clean();

		return true;
	}

}
